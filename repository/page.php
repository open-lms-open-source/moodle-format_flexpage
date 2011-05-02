<?php
/**
 * @see course_format_flexpage_model_page
 */
require_once($CFG->dirroot.'/course/format/flexpage/model/page.php');

class course_format_flexpage_repository_page {
    /**
     * @param  $courseid
     * @param string $sort
     * @return array|course_format_flexpage_model_page[]
     */
    public function get_pages($courseid, $sort = 'parentid, weight') {
        global $DB;

        $pages = array();
        $rs    = $DB->get_recordset('format_flexpage_page', array('courseid' => $courseid), $sort);
        foreach ($rs as $page) {
            $pages[$page->id] = new course_format_flexpage_model_page($page);
        }
        $rs->close();

        return $pages;
    }

    public function create_default_page($courseorid) {
        global $DB;

        if (is_object($courseorid)) {
            $courseid = $courseorid->id;
        } else {
            $courseid = $courseorid;
        }
        if (!$DB->record_exists('format_flexpage_page', array('courseid' => $courseid))) {
            if (is_object($courseorid)) {
                $course = $courseorid;
            } else {
                $course = $DB->get_record('course', array('id' => $courseorid), 'id, fullname, shortname', MUST_EXIST);
            }
            $a = (object) array(
                'fullname'  => $course->fullname,
                'shortname' => $course->shortname
            );
            $this->save_page(new course_format_flexpage_model_page(array(
                'courseid' => $courseid,
                'name'     => get_string('defaultcoursepagename', 'format_flexpage', $a),
            )));
        }
    }

    /**
     * Save a page
     *
     * On insert, automatically populates the page object with the new ID
     *
     * @param course_format_flexpage_model_page $page
     * @return void
     */
    public function save_page(course_format_flexpage_model_page &$page) {
        global $DB;

        $id = $page->get_id();

        // @todo This doesn't seem the best... maybe model has to_record() method?
        $record = (object) array(
            'courseid' => $page->get_courseid(),
            'name' => $page->get_name(),
            'altname' => $page->get_altname(),
            'display' => $page->get_display(),
            'parent' => $page->get_parentid(),
            'weight' => $page->get_weight(),
            'template' => $page->get_template(),
            'showbuttons' => $page->get_showbuttons(),
        );

        if (!empty($id)) {
            $record->id = $id;
            $DB->update_record('format_flexpage_page', $record);
        } else {
            $page->set_id(
                $DB->insert_record('format_flexpage_page', $record)
            );
        }
    }
}