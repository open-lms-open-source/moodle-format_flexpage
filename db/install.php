<?php
/**
 * @see course_format_flexpage_repository_page
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/page.php');

/**
 * @see course_format_flexpage_repository_condition
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/condition.php');

/**
 * @see block_flexpagenav_repository_menu
 */
require_once($CFG->dirroot.'/blocks/flexpagenav/repository/menu.php');

/**
 * @see block_flexpagenav_repository_link
 */
require_once($CFG->dirroot.'/blocks/flexpagenav/repository/link.php');

function xmldb_format_flexpage_install() {
    global $DB, $CFG;

    // Tracks format_page.id to format_flexpage_page.id
    $pageidmap = array();
    // Tracks pagemenu.id to block_flexpagenav_menu.id
    $menuidmap = array();

/// Migration of mod/pagemenu to blocks/flexpagenav
    if ($DB->get_manager()->table_exists('pagemenu')) {
        // Link config names got renamed...
        $configrename = array(
            'linkname' => 'label', 'linkurl' => 'url', 'moduleid' => 'cmid', 'pageid' => 'pageid', 'ticketname' => 'label', 'ticketsubject' => 'subject',
        );
        $linktyperename = array(
            'link' => 'url', 'module' => 'mod', 'page' => 'flexpage', 'ticket' => 'ticket',
        );
        $renderrename = array(
            'list' => 'tree', 'select' => 'select',
        );

        $menurepo = new block_flexpagenav_repository_menu();
        $linkrepo = new block_flexpagenav_repository_link();

        $records  = $DB->get_recordset('pagemenu');
        foreach ($records as $record) {
            $menu = new block_flexpagenav_model_menu();
            $menu->set_couseid($record->course)
                 ->set_name($record->name)
                 ->set_render($renderrename[$record->render])
                 ->set_displayname($record->displayname)
                 ->set_useastab(0);
            $menurepo->save_menu($menu);

            // Save old to new
            $menuidmap[$record->id] = $menu->get_id();

            if ($linkrecords = $DB->get_records('pagemenu_links', array('pagemenuid' => $record->id), 'previd ASC')) {
                $datarecords = $DB->get_records_sql('
                    SELECT d.*
                      FROM {pagemenu_links} l
                INNER JOIN {pagemenu_link_data} d ON l.id = d.linkid
                     WHERE l.pagemenuid = ?
                ', array($record->id));

                $linkrecord = reset($linkrecords);
                $linkid = $linkrecord->id;

                $weight = 0;
                while ($linkid) {
                    if (!array_key_exists($linkid, $linkrecords)) {
                        continue;
                    }
                    $linkrecord = $linkrecords[$linkid];

                    $data = array();
                    foreach ($datarecords as $datarecord) {
                        if ($datarecord->linkid == $linkrecord->id) {
                            $name = $configrename[$datarecord->name];

                            if (array_key_exists($name, $data)) {
                                $value = $data[$name];
                                if (!is_array($value)) {
                                    $value = array($value);
                                }
                                $value[] = $datarecord->value;
                                $data[$name] = $value;
                            } else {
                                $data[$name] = $datarecord->value;
                            }
                        }
                    }
                    $link = new block_flexpagenav_model_link();
                    $link->set_menuid($menu->get_id())
                         ->set_type($linktyperename[$linkrecord->type])
                         ->set_weight($weight);
                    $linkrepo->save_link($link);

                    $configs = array();
                    foreach ($data as $name => $value) {
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }
                        $configs[] = new block_flexpagenav_model_link_config($name, $value);
                    }
                    if ($link->get_type() == 'flexpage') {
                        $configs[] = new block_flexpagenav_model_link_config('children', 1);
                    }
                    $linkrepo->save_link_config($link, $configs);

                    $linkid = $linkrecord->nextid;
                    $weight++;
                }
            }

        }
        $records->close();
    }

/// Migration of course/format/page to course/format/flexpage

    // Handle plugin name changes...
    $DB->set_field('course', 'format', 'flexpage', array('format' => 'page'));
    $DB->set_field('course', 'theme', 'flexpage', array('theme' => 'page'));
    $DB->set_field('user', 'theme', 'flexpage', array('theme' => 'page'));

    if ($CFG->theme == 'page') {
        set_config('theme', 'flexpage');
    }
    if ($DB->get_manager()->table_exists('format_page')) {
        $pagerepo = new course_format_flexpage_repository_page();
        $condrepo = new course_format_flexpage_repository_condition();
        $records  = $DB->get_recordset('format_page', null, 'courseid, parent, sortorder');
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
                if (!array_key_exists($record->parent, $pageidmap)) {
                    // This shouldn't happen...
                    throw new coding_exception("Could not find parent ID $record->parent for format_page.id = $record->id");
                }
                $parentid = $pageidmap[$record->parent];
            } else {
                $parentid = 0;
            }

            // Migrate page locking to conditional release
            $showavailability = 1;
            $conditions       = array();
            if (!empty($record->locks)) {
                $locks = unserialize(base64_decode($record->locks));

                if (empty($locks['visible'])) {
                    $showavailability = 0;
                }
                if (!empty($locks['locks'])) {
                    foreach ($locks['locks'] as $lock) {
                        if ($lock['type'] == 'post') {
                            if ($cm = get_coursemodule_from_id(false, $lock['cmid'])) {
                                switch ($cm->modname) {
                                    case 'forum':
                                        if ($cm->completion == COMPLETION_TRACKING_NONE) {
                                            $DB->set_field('course_modules', 'completion', COMPLETION_TRACKING_AUTOMATIC, array('id' => $cm->id));
                                        }
                                        if ($DB->record_exists('forum', array('id' => $cm->instance, 'completionposts' => 0))) {
                                            $DB->set_field('forum', 'completionposts', 1, array('id' => $cm->instance));
                                        }
                                        $conditions[] = new condition_completion($cm->id, COMPLETION_COMPLETE);
                                        break;

                                    case 'choice':
                                        if ($cm->completion == COMPLETION_TRACKING_NONE) {
                                            $DB->set_field('course_modules', 'completion', COMPLETION_TRACKING_AUTOMATIC, array('id' => $cm->id));
                                        }
                                        $DB->set_field('choice', 'completionsubmit', 1, array('id' => $cm->instance));
                                        $conditions[] = new condition_completion($cm->id, COMPLETION_COMPLETE);
                                        break;

                                    case 'glossary':
                                        if ($cm->completion == COMPLETION_TRACKING_NONE) {
                                            $DB->set_field('course_modules', 'completion', COMPLETION_TRACKING_AUTOMATIC, array('id' => $cm->id));
                                        }
                                        if ($DB->record_exists('glossary', array('id' => $cm->instance, 'completionentries' => 0))) {
                                            $DB->set_field('glossary', 'completionentries', 1, array('id' => $cm->instance));
                                        }
                                        $conditions[] = new condition_completion($cm->id, COMPLETION_COMPLETE);
                                        break;

                                    default:
                                        if (plugin_supports('mod', $cm->modname, FEATURE_GRADE_HAS_GRADE, false)) {
                                            if (is_null($cm->completiongradeitemnumber)) {
                                                if ($cm->completion == COMPLETION_TRACKING_NONE) {
                                                    $cm->completion = COMPLETION_TRACKING_AUTOMATIC;
                                                }
                                                $DB->update_record('course_modules', (object) array(
                                                    'id' => $cm->id,
                                                    'completion' => $cm->completion,
                                                    'completiongradeitemnumber' => 0,
                                                ));
                                            }
                                            if (is_null($cm->completiongradeitemnumber) or $cm->completiongradeitemnumber == 0) {
                                                $conditions[] = new condition_completion($cm->id, COMPLETION_COMPLETE);
                                            }
                                        }
                                }
                            }
                        } else if ($lock['type'] == 'grade') {
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
                                    if ($cm->completionview == COMPLETION_VIEW_NOT_REQUIRED) {
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

            if (!empty($record->nametwo)) {
                $record->nameone = $record->nametwo;
            }
            $page = new course_format_flexpage_model_page(array(
                'courseid' => $record->courseid,
                'name' => $record->nameone,
                'display' => $display,
                'navigation' => $record->showbuttons,
                'showavailability' => $showavailability,
                'parentid' => $parentid,
                'weight' => $record->sortorder,
            ));
            $pagerepo->save_page($page);

            // Save the map
            $pageidmap[$record->id] = $page->get_id();

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
        $pagemenucmidmap = array();
        if ($DB->get_manager()->table_exists('pagemenu')) {
            $pagemenucmidmap = $DB->get_records_sql_menu('
                SELECT cm.id, cm.instance
                  FROM {course_modules} cm
            INNER JOIN {modules} m ON cm.module = m.id
                 WHERE m.name = ?
            ', array('pagemenu'));
        }

        $context = false;
        $records = $DB->get_recordset_sql('
            SELECT i.*, c.id AS courseid
              FROM {format_page_items} i
        INNER JOIN {format_page} p ON p.id = i.pageid
        INNER JOIN {course} c ON c.id = p.courseid
          ORDER BY c.id
        ');
        foreach ($records as $record) {
            if (!array_key_exists($record->pageid, $pageidmap)) {
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
                    'subpagepattern' => $pageidmap[$record->pageid],
                    'defaultweight' => $record->sortorder,
                    'defaultregion' => $region,
                ));
            } else {
                // For modules, insert flexpagemod or flexpagenav instance
                if (!$context or $context->instanceid != $record->courseid) {
                    $context = get_context_instance(CONTEXT_COURSE, $record->courseid);
                }
                if (array_key_exists($record->cmid, $pagemenucmidmap)) {
                    $instanceid = $pagemenucmidmap[$record->cmid];
                    if (array_key_exists($instanceid, $menuidmap)) {
                        $menuid = $menuidmap[$instanceid];
                    } else {
                        $menuid = 0;
                    }
                    $record->blockinstance = $DB->insert_record('block_instances', (object) array(
                        'blockname' => 'flexpagenav',
                        'parentcontextid' => $context->id,
                        'showinsubcontexts' => 0,
                        'pagetypepattern' => 'course-view-*',
                        'subpagepattern' => $pageidmap[$record->pageid],
                        'defaultregion' => $region,
                        'defaultweight' => $record->sortorder,
                        'configdata' => base64_encode(serialize((object) array('menuid' => $menuid)))
                    ));
                } else {
                    $record->blockinstance = $DB->insert_record('block_instances', (object) array(
                        'blockname' => 'flexpagemod',
                        'parentcontextid' => $context->id,
                        'showinsubcontexts' => 0,
                        'pagetypepattern' => 'course-view-*',
                        'subpagepattern' => $pageidmap[$record->pageid],
                        'defaultregion' => $region,
                        'defaultweight' => $record->sortorder,
                        'configdata' => base64_encode(serialize((object) array('cmid' => $record->cmid)))
                    ));
                    $DB->insert_record('block_flexpagemod', (object) array(
                        'instanceid' => $record->blockinstance,
                        'cmid' => $record->cmid,
                    ));
                }
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
                    $bp->subpage = $pageidmap[$record->pageid];
                    $bp->visible = 0;
                    $bp->region = $instance->defaultregion;
                    $bp->weight = $instance->defaultweight;

                    $DB->insert_record('block_positions', $bp);
                }
            }
        }
        $records->close();
    }

/// Migration of top tabs (we build a new menu that's designated as the top tabs)
    if ($DB->get_manager()->table_exists('pagemenu') and $DB->get_manager()->table_exists('format_page')) {
        $records = $DB->get_recordset('course', array('format' => 'flexpage'), '', 'id');
        foreach ($records as $record) {
            $links = array();

            $menurecords = $DB->get_recordset('pagemenu', array('course' => $record->id, 'useastab' => 1), 'taborder, name', 'id');
            foreach ($menurecords as $menurecord) {
                if (!array_key_exists($menurecord->id, $menuidmap)) {
                    continue;
                }
                $link = new block_flexpagenav_model_link();
                $link->set_type('flexpagenav')
                     ->set_configs(array(new block_flexpagenav_model_link_config('menuid', $menuidmap[$menurecord->id])));
                $links[] = $link;
            }
            $menurecords->close();

            // (2 | 1) means DISP_THEME and DISP_PUBLISH
            $pagerecords = $DB->get_recordset_select('format_page', 'courseid = ? AND ((display & ?) = ?) AND parent = 0', array($record->id, (2 | 1), (2 | 1)), 'sortorder');
            foreach ($pagerecords as $pagerecord) {
                if (!array_key_exists($pagerecord->id, $pageidmap)) {
                    continue;
                }
                $link = new block_flexpagenav_model_link();
                $link->set_type('flexpage')
                     ->set_configs(array(
                        new block_flexpagenav_model_link_config('pageid', $pageidmap[$pagerecord->id]),
                        new block_flexpagenav_model_link_config('children', 0),
                        new block_flexpagenav_model_link_config('exclude', ''),
                     )
                );
                $links[] = $link;
            }
            $pagerecords->close();

            if (!empty($links)) {
                $menurepo = new block_flexpagenav_repository_menu();
                $linkrepo = new block_flexpagenav_repository_link();

                $menu = new block_flexpagenav_model_menu();
                $menu->set_couseid($record->id)
                     ->set_name(get_string('migrationtoptabs', 'block_flexpagenav'))
                     ->set_render('navhorizontal')
                     ->set_displayname(0)
                     ->set_useastab(1);
                $menurepo->save_menu($menu);

                $weight = 0;
                foreach ($links as $link) {
                    $link->set_menuid($menu->get_id())
                         ->set_weight($weight);

                    $linkrepo->save_link($link)
                             ->save_link_config($link, $link->get_configs());

                    $weight++;
                }
            }
        }
        $records->close();
    }

/// Cleanup block/page_module
    $instances = $DB->get_records('block_instances', array('blockname' => 'page_module'));
    if(!empty($instances)) {
        foreach($instances as $instance) {
            blocks_delete_instance($instance);
        }
    }
    $DB->delete_records('block', array('name' => 'page_module'));
    drop_plugin_tables('page_module', "$CFG->dirroot/blocks/page_module/db/install.xml", false);
    drop_plugin_tables('block_page_module', "$CFG->dirroot/blocks/page_module/db/install.xml", false);
    capabilities_cleanup('block/page_module');
    events_uninstall('block/page_module');

/// Cleanup mod/pagemenu
    if ($DB->record_exists('modules', array('name' => 'pagemenu'))) {
        uninstall_plugin('mod', 'pagemenu');
    }

/// Cleanup course/format/page
    foreach (array('format_page', 'format_page_items') as $table) {
        if ($DB->get_manager()->table_exists($table)) {
            $DB->get_manager()->drop_table(new xmldb_table($table));
        }
    }
}