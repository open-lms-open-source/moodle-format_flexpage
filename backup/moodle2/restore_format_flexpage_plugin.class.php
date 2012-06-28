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
 * @copyright Copyright (c) 2009 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @package local/mr
 * @author Mark Nielsen
 */

/**
 * Flexpage restore plugin
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class restore_format_flexpage_plugin extends restore_format_plugin {
    /**
     * Returns the paths to be handled by the plugin at course level
     */
    protected function define_course_plugin_structure() {
        return array(
            new restore_path_element('flexpage_page', $this->get_pathfor('/pages/page')),
            new restore_path_element('flexpage_region', $this->get_pathfor('/pages/page/regions/region')),
            new restore_path_element('flexpage_completion', $this->get_pathfor('/pages/page/completions/completion')),
            new restore_path_element('flexpage_grade', $this->get_pathfor('/pages/page/grades/grade')),
            new restore_path_element('flexpage_menu', $this->get_pathfor('/menus/menu')),
            new restore_path_element('flexpage_link', $this->get_pathfor('/menus/menu/links/link')),
            new restore_path_element('flexpage_config', $this->get_pathfor('/menus/menu/links/link/configs/config')),
        );
    }

    /**
     * Restore a single page
     */
    public function process_flexpage_page($data) {
        global $DB;

        $data  = (object) $data;
        $oldid = $data->id;
        $data->courseid = $this->task->get_courseid();
        $data->availablefrom = $this->apply_date_offset($data->availablefrom);
        $data->availableuntil = $this->apply_date_offset($data->availableuntil);

        $newid = $DB->insert_record('format_flexpage_page', $data);

        $this->set_mapping('flexpage_page', $oldid, $newid);
    }

    /**
     * Restore a page region width
     */
    public function process_flexpage_region($data) {
        global $DB;

        $data  = (object) $data;
        $data->pageid = $this->get_new_parentid('flexpage_page');

        $DB->insert_record('format_flexpage_region', $data);
    }

    /**
     * Restore a page completion condition
     */
    public function process_flexpage_completion($data) {
        global $DB;

        $data  = (object) $data;
        $data->pageid = $this->get_new_parentid('flexpage_page');

        $DB->insert_record('format_flexpage_completion', $data);
    }

    /**
     * Restore a page grade condition
     */
    public function process_flexpage_grade($data) {
        global $DB;

        $data  = (object) $data;
        $data->pageid = $this->get_new_parentid('flexpage_page');

        $DB->insert_record('format_flexpage_grade', $data);
    }

    /**
     * Restore blocks/flepagenav menu
     */
    public function process_flexpage_menu($data) {
        global $DB;

        $data  = (object) $data;
        $oldid = $data->id;
        $data->courseid = $this->task->get_courseid();

        $newid = $DB->insert_record('block_flexpagenav_menu', $data);

        $this->set_mapping('flexpage_menu', $oldid, $newid);
    }

    /**
     * Restore blocks/flepagenav link
     */
    public function process_flexpage_link($data) {
        global $DB;

        $data  = (object) $data;
        $oldid = $data->id;
        $data->menuid = $this->get_new_parentid('flexpage_menu');

        $newid = $DB->insert_record('block_flexpagenav_link', $data);

        $this->set_mapping('flexpage_link', $oldid, $newid);
    }

    /**
     * Restore blocks/flepagenav config
     */
    public function process_flexpage_config($data) {
        global $DB;

        $data  = (object) $data;
        $data->linkid = $this->get_new_parentid('flexpage_link');

        $DB->insert_record('block_flexpagenav_config', $data);
    }

    /**
     * ID remapping done here
     */
    public function after_restore_course() {
        global $CFG, $DB;

        require_once($CFG->libdir.'/blocklib.php');
        require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');
        require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

        $context = get_context_instance(CONTEXT_COURSE, $this->task->get_courseid());
        $course  = $DB->get_record('course', array('id' => $context->instanceid), 'id, category', MUST_EXIST);

        // Remap parentids
        $pages = $DB->get_recordset_select('format_flexpage_page', 'parentid != 0 AND courseid = ?', array($this->task->get_courseid()), '', 'id, parentid');
        foreach ($pages as $page) {
            if (!$newid = $this->get_mappingid('flexpage_page', $page->parentid)) {
                $newid = 0;  // Hope this never happens...
            }
            $DB->set_field('format_flexpage_page', 'parentid', $newid, array('id' => $page->id));
        }
        $pages->close();

        // Remap course module IDs
        $completions = $DB->get_recordset_sql('
            SELECT c.id, c.cmid
              FROM {format_flexpage_completion} c
        INNER JOIN {format_flexpage_page} p ON p.id = c.pageid
             WHERE p.courseid = ?
         ', array($this->task->get_courseid()));

        foreach ($completions as $completion) {
            if ($newid = $this->get_mappingid('course_module', $completion->cmid)) {
                $DB->set_field('format_flexpage_completion', 'cmid', $newid, array('id' => $completion->id));
            } else {
                $DB->delete_records('format_flexpage_completion', array('id' => $completion->id));
            }
        }
        $completions->close();

        // Remap grade item IDs
        $grades = $DB->get_recordset_sql('
            SELECT g.id, g.gradeitemid
              FROM {format_flexpage_grade} g
        INNER JOIN {format_flexpage_page} p ON p.id = g.pageid
             WHERE p.courseid = ?
         ', array($this->task->get_courseid()));

        foreach ($grades as $grade) {
            if ($newid = $this->get_mappingid('grade_item', $grade->gradeitemid)) {
                $DB->set_field('format_flexpage_grade', 'gradeitemid', $newid, array('id' => $grade->id));
            } else {
                $DB->delete_records('format_flexpage_grade', array('id' => $grade->id));
            }
        }
        $grades->close();

        if ($course->category == 0) {
            $pagetype        = 'site-index';
            $subpagepatterns = array('site-index');
        } else {
            $pagetype        = 'course-view-flexpage';
            $subpagepatterns = array_keys(
                generate_page_type_patterns($pagetype, $context, $context)
            );
        }
        list($pagetypesql, $params) = $DB->get_in_or_equal($subpagepatterns);
        $params[] = $context->id;

        // Remap block subpagepattern and subpage
        $instances = $DB->get_recordset_select('block_instances', "pagetypepattern $pagetypesql AND parentcontextid = ? AND subpagepattern IS NOT NULL", $params, '', 'id, subpagepattern');
        foreach ($instances as $instance) {
            if ($newid = $this->get_mappingid('flexpage_page', $instance->subpagepattern)) {
                $DB->set_field('block_instances', 'subpagepattern', $newid, array('id' => $instance->id));
            } else {
                $DB->set_field('block_instances', 'subpagepattern', null, array('id' => $instance->id));
            }
        }
        $positions = $DB->get_recordset_select('block_positions', 'contextid = ? AND pagetype = ? AND subpage != \'\'', array($context->id, $pagetype), '', 'id, subpage');
        foreach ($positions as $position) {
            if ($newid = $this->get_mappingid('flexpage_page', $position->subpage)) {
                $DB->set_field('block_positions', 'subpage', $newid, array('id' => $position->id));
            } else {
                $DB->delete_records('block_positions', array('id' => $position->id));
            }
        }

        // Remap menu link config values
        $configs = $DB->get_recordset_sql('
            SELECT c.*
              FROM {block_flexpagenav_menu} m
        INNER JOIN {block_flexpagenav_link} l ON m.id = l.menuid
        INNER JOIN {block_flexpagenav_config} c ON l.id = c.linkid
             WHERE m.courseid = ?
               AND l.type != ?
               AND l.type != ?
        ', array($this->task->get_courseid(), 'url', 'ticket'));

        foreach ($configs as $config) {
            $newvalue = false;
            if ($config->name == 'pageid') {
                if (!$newvalue = $this->get_mappingid('flexpage_page', $config->value)) {
                    $newvalue = 0;
                }
            } else if ($config->name == 'exclude') {
                if (!empty($config->value)) {
                    $newvalue = array();
                    $pageids  = explode(',', $config->value);
                    foreach ($pageids as $pageid) {
                        if ($newpageid = $this->get_mappingid('flexpage_page', $pageid)) {
                            $newvalue[] = $newpageid;
                        }
                    }
                    $newvalue = implode(',', $newvalue);
                }
            } else if ($config->name == 'cmid') {
                if (!$newvalue = $this->get_mappingid('course_module', $config->value)) {
                    $newvalue = 0;
                }
            } else if ($config->name == 'menuid') {
                if (!$newvalue = $this->get_mappingid('flexpage_menu', $config->value)) {
                    $newvalue = 0;
                }
            }
            if ($newvalue !== false) {
                $DB->set_field('block_flexpagenav_config', 'value', $newvalue, array('id' => $config->id));
            }
        }
        $configs->close();

        $repo = new course_format_flexpage_repository_cache();
        if ($repo->cache_exists($this->task->get_courseid())) {
            format_flexpage_clear_cache($this->task->get_courseid());
        }
    }
}