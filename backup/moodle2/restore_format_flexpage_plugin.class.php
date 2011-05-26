<?php
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
            new restore_path_element('flexpage_page', $this->get_pathfor('/page')),
            new restore_path_element('flexpage_region', $this->get_pathfor('/page/regions/region')),
            new restore_path_element('flexpage_completion', $this->get_pathfor('/page/completions/completion')),
            new restore_path_element('flexpage_grade', $this->get_pathfor('/page/grades/grade')),
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
     * ID remapping done here
     */
    public function after_restore_course() {
        global $DB;

        $context = get_context_instance(CONTEXT_COURSE, $this->task->get_courseid());

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

        // Remap block subpagepattern and subpage
        $instances = $DB->get_recordset_select('block_instances', 'parentcontextid = ? AND pagetypepattern = ? AND subpagepattern IS NOT NULL', array($context->id, 'course-view-*'), '', 'id, subpagepattern');
        foreach ($instances as $instance) {
            if ($newid = $this->get_mappingid('flexpage_page', $instance->subpagepattern)) {
                $DB->set_field('block_instances', 'subpagepattern', $newid, array('id' => $instance->id));
            } else {
                $DB->set_field('block_instances', 'subpagepattern', null, array('id' => $instance->id));
            }
        }
        $positions = $DB->get_recordset_select('block_positions', 'contextid = ? AND pagetype = ? AND subpage != \'\'', array($context->id, 'course-view-flexpage'), '', 'id, subpage');
        foreach ($positions as $position) {
            if ($newid = $this->get_mappingid('flexpage_page', $position->subpage)) {
                $DB->set_field('block_positions', 'subpage', $newid, array('id' => $position->id));
            } else {
                $DB->delete_records('block_positions', array('id' => $position->id));
            }
        }
    }
}