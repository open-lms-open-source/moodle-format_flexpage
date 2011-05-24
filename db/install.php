<?php
/**
 * @see course_format_flexpage_repository_page
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/page.php');

/**
 * @see course_format_flexpage_repository_condition
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/condition.php');

function xmldb_format_flexpage_install() {
    global $DB, $CFG;

    // @todo Migration of navigation

    // Handle plugin name changes...
    $DB->set_field('course', 'format', 'flexpage', array('format' => 'page'));
    $DB->set_field('course', 'theme', 'flexpage', array('theme' => 'page'));
    $DB->set_field('user', 'theme', 'flexpage', array('theme' => 'page'));

    if ($CFG->theme == 'page') {
        set_config('theme', 'flexpage');
    }
    if ($DB->get_manager()->table_exists('format_page')) {
        $oldtonew = array();
        $pagerepo = new course_format_flexpage_repository_page();
        $condrepo = new course_format_flexpage_repository_condition();
        $records  = $DB->get_recordset('format_page', null, 'courseid, parent, weight');
        foreach ($records as $record) {
            // Migrate display value
            if (($record->display & 4) == 4) {
                $display = course_format_flexpage_model_page::DISPLAY_VISIBLE_MENU;
            } else if (($record->display & 1) == 1) {
                $display = course_format_flexpage_model_page::DISPLAY_VISIBLE;
            } else {
                $display = course_format_flexpage_model_page::DISPLAY_HIDDEN;
            }
            // Re-map parent
            if (!empty($record->parent)) {
                if (!array_key_exists($record->parent, $oldtonew)) {
                    // This shouldn't happen...
                    throw new coding_exception("Could not find parent ID $record->parent for format_page.id = $record->id");
                }
                $parentid = $oldtonew[$record->parent];
            } else {
                $parentid = 0;
            }

            // Migrate page locking to conditional release
            $showavailability = 1;
            $conditions       = array();
            if (!empty($record->locks)) {
                $locks = unserialize(base64_decode($record->locks));

                if (empty($locks->visible)) {
                    $showavailability = 0;
                }
                if (!empty($locks->locks)) {
                    foreach ($locks->locks as $lock) {
                        if ($lock['type'] == 'grade') {
                            $lockgrades = explode(':', $lock['grade']);

                            if (count($lockgrades) == 2) {
                                $max = $lockgrades[1];
                            } else {
                                $max = 100;
                            }
                            $conditions[] = new condition_grade($lock['id'], $lockgrades[0], $max);
                        } else if ($lock['type'] == 'access') {
                            if ($cm = get_coursemodule_from_id(false, $lock['cmid'])) {
                                if (plugin_supports('mod', $cm->modname, FEATURE_COMPLETION_TRACKS_VIEWS, false)) {
                                    if ($cm->completionview != COMPLETION_VIEW_NOT_REQUIRED) {
                                        if ($cm->completion == COMPLETION_TRACKING_NONE) {
                                            $cm->completion = COMPLETION_TRACKING_AUTOMATIC;
                                        }
                                        $DB->update_record('course_modules', (object) array(
                                            'id' => $cm->id,
                                            'completion' => $cm->completion,
                                            'completionview' => COMPLETION_VIEW_REQUIRED,
                                        ));
                                    }
                                    $conditions[] = new condition_completion($cm->id, COMPLETION_COMPLETE);
                                }
                            }
                        }
                    }
                }
            }

            $page = new course_format_flexpage_model_page(array(
                'courseid' => $record->courseid,
                'name' => $record->nameone,
                'altname' => $record->nametwo,
                'display' => $display,
                'navigation' => $record->showbuttons,
                'showavailability' => $showavailability,
                'parentid' => $parentid,
                'weight' => $record->sortorder,
            ));
            $pagerepo->save_page($page);

            if (!empty($conditions)) {
                $condrepo->save_page_conditions($page, $conditions);
            }

            // Migrate page widths
            $widths = array();
            if (is_number($record->prefleftwidth) and !empty($record->prefleftwidth)) {
                $widths['side-pre'] = $record->prefleftwidth;
            }
            if (is_number($record->prefcenterwidth) and !empty($record->prefcenterwidth)) {
                $widths['main'] = $record->prefcenterwidth;
            }
            if (is_number($record->prefrightwidth) and !empty($record->prefrightwidth)) {
                $widths['side-post'] = $record->prefrightwidth;
            }
            if (!empty($widths)) {
                $pagerepo->save_page_region_widths($page, $widths);
            }
        }
        $records->close();

        // Migrate Page Items
        $context = false;
        $records = $DB->get_recordset_sql('
            SELECT i.*, c.id AS courseid
              FROM {format_page_items} i
        INNER JOIN {format_page} p ON p.id = i.pageid
        INNER JOIN {course} c ON c.id = p.courseid
          ORDER BY c.id
        ');
        foreach ($records as $record) {
            if (!array_key_exists($record->pageid, $oldtonew)) {
                continue;
            }
            if ($record->position == 'l') {
                $region = 'side-pre';
            } else if ($record->position == 'r') {
                $region = 'side-post';
            } else {
                $region = 'main';
            }
            if (!empty($record->blockinstance)) {
                // For blocks, just update the block instance record
                $DB->update_record('block_instances', (object) array(
                    'id' => $record->blockinstance,
                    'subpagepattern' => $oldtonew[$record->pageid],
                    'defaultweight' => $record->sortorder,
                    'defaultregion' => $region,
                ));
            } else {
                // For modules, insert flexpagemod instance
                if (!$context or $context->instanceid != $record->courseid) {
                    $context = get_context_instance(CONTEXT_COURSE, $record->courseid);
                }
                $record->blockinstance = $DB->insert_record('block_instances', (object) array(
                    'blockname' => 'flexpagemod',
                    'parentcontextid' => $context->id,
                    'showinsubcontexts' => 0,
                    'pagetypepattern' => 'course-view-*',
                    'subpagepattern' => $oldtonew[$record->pageid],
                    'defaultregion' => $region,
                    'defaultweight' => $record->sortorder,
                    'configdata' => base64_encode(serialize((object) array('cmid' => $record->cmid)))
                ));
                $DB->insert_record('block_flexpagemod', (object) array(
                    'instanceid' => $record->blockinstance,
                    'cmid' => $record->cmid,
                ));
            }

            // In order to set visibility, we need to set a block position
            if (empty($record->visible)) {
                if ($instance = $DB->get_record('block_instances', array('id' => $record->blockinstance))) {
                    if (!$context or $context->instanceid != $record->courseid) {
                        $context = get_context_instance(CONTEXT_COURSE, $record->courseid);
                    }
                    $bp = new stdClass;
                    $bp->blockinstanceid = $instance->id;
                    $bp->contextid = $context->id;
                    $bp->pagetype = 'course-view-flexpage';
                    $bp->subpage = $oldtonew[$record->pageid];
                    $bp->visible = 0;
                    $bp->region = $instance->defaultregion;
                    $bp->weight = $instance->defaultweight;

                    $DB->insert_record('block_positions', $bp);
                }
            }
        }
        $records->close();
    }
}