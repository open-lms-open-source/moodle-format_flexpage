<?php
/**
 * Flexpage
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://opensource.org/licenses/gpl-3.0.html.
 *
 * @copyright Copyright (c) 2009 Blackboard Inc. (http://www.blackboardopenlms.com)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @package format_flexpage
 * @author Mark Nielsen
 */

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

/**
 * @see course_format_flexpage_lib_moodlepage
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

/**
 * Migrate from old format to new
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
function xmldb_format_flexpage_install() {
    $migrate = new xmldb_format_flexpage_install_migration();

    // Migrate flexpages
    $migrate->pages();

    // Migrate mod/pagemenu to blocks/flexpagenav
    $migrate->menus();

    // Migrate flexpage items (EG: blocks)
    $migrate->page_items();

    // Migrate top tabs (Build a new menu that's designated as the top tabs)
    $migrate->top_tabs();

    // Cleanup routine
    $migrate->cleanup();

    unset($migrate);
}

/**
 * Migration assistant
 *
 * @throws coding_exception
 * @author Mark Nielsen
 * @package format_flexpage
 */
class xmldb_format_flexpage_install_migration {
    /**
     * Stores old to new IDs
     *
     * @var array
     */
    protected $ids = array();

    /**
     * Map an old ID to a new ID
     *
     * @param string $type
     * @param int $oldid
     * @param int $newid
     * @return void
     */
    public function map_id($type, $oldid, $newid) {
        $this->ids[$type][$oldid] = $newid;
    }

    /**
     * Get the new ID from the ID map
     *
     * @param string $type
     * @param int $oldid
     * @param bool $default Return this if not found
     * @return bool
     */
    public function get_newid($type, $oldid, $default = false) {
        if (!array_key_exists($type, $this->ids)) {
            return $default;
        }
        if (!array_key_exists($oldid, $this->ids[$type])) {
            return $default;
        }
        return $this->ids[$type][$oldid];
    }

    /**
     * Get page patterns
     *
     * @param bool $frontpage If we are on the front page or not
     * @return array
     */
    public function get_page_patterns($frontpage = false) {
        if ($frontpage) {
            $pagepattern   = 'site-index';
            $bppagepattern = 'site-index';
        } else {
            $pagepattern   = 'course-view-*';
            $bppagepattern = 'course-view-flexpage';
        }
        return array($pagepattern, $bppagepattern);
    }

    /**
     * Sorts pages
     *
     * @param stdClass[] $pages Parent pages to process
     * @param int $parentid The parent ID of the children to sort
     * @return stdClass[]
     */
    public function sort_pages($pages, $parentid = 0) {
        $return     = array();
        $childpages = $this->filter_children($parentid, $pages);
        foreach ($childpages as $page) {
            $return[$page->id] = $page;
            $return  += $this->sort_pages($pages, $page->id);
        }
        return $return;
    }

    /**
     * Assists with sorting, find child pages of a parent ID
     *
     * @param int $parentid The parent page ID to find the children of
     * @param stdClass[] $childpages Potential child pages
     * @return stdClass[]
     */
    public function filter_children($parentid, array &$childpages) {
        $collected = false;
        $return    = array();
        foreach ($childpages as $page) {
            if ($page->parent == $parentid) {
                $return[$page->id] = $page;

                // Remove from all pages to improve seek times later
                unset($childpages[$page->id]);

                // This will halt seeking after we get all the children
                $collected = true;
            } else if ($collected) {
                // Since $pages is organized by parent,
                // then once we find one, we get them all in a row
                break;
            }
        }
        return $return;
    }

    /**
     * Migrates pages from format_page to format_flexpage_page
     *
     * @throws coding_exception
     * @return void
     */
    public function pages() {
        global $CFG, $DB;

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

            /** @var $courses stdClass[] */
            $courses  = $DB->get_recordset('course', array('format' => 'flexpage'), '', 'id');
            foreach ($courses as $course) {
                $records = $DB->get_records('format_page', array('courseid' => $course->id), 'courseid, parent, sortorder');
                if (empty($records)) {
                    continue;
                }
                $records = $this->sort_pages($records);

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
                        $parentid = $this->get_newid('page', $record->parent);

                        if ($parentid === false) { // This shouldn't happen...
                            throw new coding_exception("Could not find parent ID $record->parent for format_page.id = $record->id");
                        }
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
                                    /** @var $cm stdClass */
                                    if ($cm = get_coursemodule_from_id(false, $lock['cmid'])) {
                                        switch ($cm->modname) {
                                            case 'forum':
                                                if ($cm->completion == COMPLETION_TRACKING_NONE) {
                                                    $DB->set_field('course_modules', 'completion', COMPLETION_TRACKING_AUTOMATIC, array('id' => $cm->id));
                                                }
                                                if ($DB->record_exists('forum', array('id' => $cm->instance, 'completionposts' => 0))) {
                                                    $DB->set_field('forum', 'completionposts', 1, array('id' => $cm->instance));
                                                }
                                                $conditions[] = new course_format_flexpage_model_condition_completion($cm->id, COMPLETION_COMPLETE);
                                                break;

                                            case 'choice':
                                                if ($cm->completion == COMPLETION_TRACKING_NONE) {
                                                    $DB->set_field('course_modules', 'completion', COMPLETION_TRACKING_AUTOMATIC, array('id' => $cm->id));
                                                }
                                                $DB->set_field('choice', 'completionsubmit', 1, array('id' => $cm->instance));
                                                $conditions[] = new course_format_flexpage_model_condition_completion($cm->id, COMPLETION_COMPLETE);
                                                break;

                                            case 'glossary':
                                                if ($cm->completion == COMPLETION_TRACKING_NONE) {
                                                    $DB->set_field('course_modules', 'completion', COMPLETION_TRACKING_AUTOMATIC, array('id' => $cm->id));
                                                }
                                                if ($DB->record_exists('glossary', array('id' => $cm->instance, 'completionentries' => 0))) {
                                                    $DB->set_field('glossary', 'completionentries', 1, array('id' => $cm->instance));
                                                }
                                                $conditions[] = new course_format_flexpage_model_condition_completion($cm->id, COMPLETION_COMPLETE);
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
                                                        $conditions[] = new course_format_flexpage_model_condition_completion($cm->id, COMPLETION_COMPLETE);
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
                                    $conditions[] = new course_format_flexpage_model_condition_grade($lock['id'], $lockgrades[0], $max);
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
                                            $conditions[] = new course_format_flexpage_model_condition_completion($cm->id, COMPLETION_COMPLETE);
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
                    $this->map_id('page', $record->id, $page->get_id());

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
            }
            $courses->close();
        }
    }

    /**
     * Migrates format_page_items into block_instances and block_positions
     *
     * @return void
     */
    public function page_items() {
        global $DB;

        if ($DB->get_manager()->table_exists('format_page') and $DB->get_manager()->table_exists('format_page_items')) {
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

            /** @var $records stdClass[] */
            $records = $DB->get_recordset_sql('
                SELECT i.*, c.id AS courseid, c.category AS coursecat
                  FROM {format_page_items} i
            INNER JOIN {format_page} p ON p.id = i.pageid
            INNER JOIN {course} c ON c.id = p.courseid
              ORDER BY c.id
            ');
            foreach ($records as $record) {
                if (!$this->get_newid('page', $record->pageid)) {
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
                        'subpagepattern' => $this->get_newid('page', $record->pageid),
                        'defaultweight' => $record->sortorder,
                        'defaultregion' => $region,
                    ));
                } else {
                    // For modules, insert flexpagemod or flexpagenav instance
                    if (!$context or $context->instanceid != $record->courseid) {
                        /** @var $context stdClass */
                        $context = context_course::instance($record->courseid);
                    }
                    list($pagepattern, $bppagepattern) = $this->get_page_patterns(($record->coursecat == 0));

                    if (array_key_exists($record->cmid, $pagemenucmidmap)) {
                        $record->blockinstance = $DB->insert_record('block_instances', (object) array(
                            'blockname' => 'flexpagenav',
                            'parentcontextid' => $context->id,
                            'showinsubcontexts' => 0,
                            'pagetypepattern' => $pagepattern,
                            'subpagepattern' => $this->get_newid('page', $record->pageid),
                            'defaultregion' => $region,
                            'defaultweight' => $record->sortorder,
                            'configdata' => base64_encode(serialize((object) array('menuid' => $this->get_newid('menu', $pagemenucmidmap[$record->cmid]))))
                        ));
                    } else {
                        $record->blockinstance = $DB->insert_record('block_instances', (object) array(
                            'blockname' => 'flexpagemod',
                            'parentcontextid' => $context->id,
                            'showinsubcontexts' => 0,
                            'pagetypepattern' => $pagepattern,
                            'subpagepattern' => $this->get_newid('page', $record->pageid),
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
                    /** @var $instance stdClass */
                    if ($instance = $DB->get_record('block_instances', array('id' => $record->blockinstance))) {

                        list($pagepattern, $bppagepattern) = $this->get_page_patterns(($record->coursecat == 0));

                        if (!$context or $context->instanceid != $record->courseid) {
                            $context = context_course::instance($record->courseid);
                        }
                        $bp = new stdClass;
                        $bp->blockinstanceid = $instance->id;
                        $bp->contextid = $context->id;
                        $bp->pagetype = $bppagepattern;
                        $bp->subpage = $this->get_newid('page', $record->pageid);
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

    /**
     * Migrates pagemenu, pagemenu_links and pagemenu_link_data to block_flexpagenav tables
     *
     * @return void
     */
    public function menus() {
        global $DB;

        if ($DB->get_manager()->table_exists('pagemenu')) {
            // Link config names got renamed...
            $configrename = array(
                'linkname' => 'label', 'linkurl' => 'url', 'moduleid' => 'cmid', 'pageid' => 'pageid', 'exclude' => 'exclude', 'ticketname' => 'label', 'ticketsubject' => 'subject',
            );
            $linktyperename = array(
                'link' => 'url', 'module' => 'mod', 'page' => 'flexpage', 'ticket' => 'ticket',
            );
            $renderrename = array(
                'list' => 'tree', 'select' => 'select',
            );

            $menurepo = new block_flexpagenav_repository_menu();
            $linkrepo = new block_flexpagenav_repository_link();

            /** @var $records stdClass[] */
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
                $this->map_id('menu', $record->id, $menu->get_id());

                /** @var $linkrecords stdClass[] */
                if ($linkrecords = $DB->get_records('pagemenu_links', array('pagemenuid' => $record->id), 'previd ASC')) {
                    /** @var $datarecords stdClass[] */
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

                        $link = new block_flexpagenav_model_link();
                        $link->set_menuid($menu->get_id())
                             ->set_type($linktyperename[$linkrecord->type])
                             ->set_weight($weight);
                        $linkrepo->save_link($link);

                        $data = array();
                        foreach ($datarecords as $datarecord) {
                            if ($datarecord->linkid == $linkrecord->id) {
                                $name = $configrename[$datarecord->name];

                                if ($link->get_type() == 'flexpage') {
                                    if ($name == 'pageid') {
                                        $value = $this->get_newid('page', $datarecord->value, 0);
                                    } else if ($name == 'exclude') {
                                        $value = $this->get_newid('page', $datarecord->value);
                                        if ($value === false) {
                                            continue;
                                        }
                                    }
                                } else {
                                    $value = $datarecord->value;
                                }
                                if (array_key_exists($name, $data)) {
                                    $values = $data[$name];
                                    if (!is_array($values)) {
                                        $values = array($values);
                                    }
                                    $values[] = $value;
                                    $data[$name] = $values;
                                } else {
                                    $data[$name] = $value;
                                }
                            }
                        }

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
    }

    public function top_tabs() {
        global $DB;

        if ($DB->get_manager()->table_exists('pagemenu') and $DB->get_manager()->table_exists('format_page')) {
            /** @var $records stdClass[] */
            $records = $DB->get_recordset('course', array('format' => 'flexpage'), '', 'id');
            foreach ($records as $record) {
                $links = array();

                /** @var $menurecords stdClass[] */
                $menurecords = $DB->get_recordset('pagemenu', array('course' => $record->id, 'useastab' => 1), 'taborder, name', 'id');
                foreach ($menurecords as $menurecord) {
                    if (!$this->get_newid('menu', $menurecord->id)) {
                        continue;
                    }
                    $link = new block_flexpagenav_model_link();
                    $link->set_type('flexpagenav')
                         ->set_configs(array(new block_flexpagenav_model_link_config('menuid', $this->get_newid('menu', $menurecord->id))));
                    $links[] = $link;
                }
                $menurecords->close();

                // (2 | 1) means DISP_THEME and DISP_PUBLISH
                /** @var $pagerecords stdClass[] */
                $pagerecords = $DB->get_recordset_select('format_page', 'courseid = ? AND ((display & ?) = ?) AND parent = 0', array($record->id, (2 | 1), (2 | 1)), 'sortorder');
                foreach ($pagerecords as $pagerecord) {
                    if (!$this->get_newid('page', $pagerecord->id)) {
                        continue;
                    }
                    $link = new block_flexpagenav_model_link();
                    $link->set_type('flexpage')
                         ->set_configs(array(
                            new block_flexpagenav_model_link_config('pageid', $this->get_newid('page', $pagerecord->id)),
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
    }

    public function cleanup() {
        global $DB, $CFG;

    /// Cleanup block/page_module
        $instances = $DB->get_recordset('block_instances', array('blockname' => 'page_module'));
        foreach ($instances as $instance) {
            blocks_delete_instance($instance);
        }
        $instances->close();

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
        if ($DB->get_manager()->table_exists('format_page')) {
            uninstall_plugin('format', 'page');
        }
    }
}
