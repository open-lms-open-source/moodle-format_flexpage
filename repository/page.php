<?php

// @todo require model/page.php

class course_format_flexpage_respository_page {
    /**
     * @param  $courseid
     * @param string $sort
     * @return course_format_flexpage_model_page[]
     */
    public function get_pages($courseid, $sort = 'parentid, sortorder') {
        global $DB;

        $pages = array();
        $rs    = $DB->get_recordset('format_page', array('courseid' => $courseid), $sort);
        foreach ($rs as $page) {
            $pages[$page->id] = new course_format_flexpage_model_page($page);
        }
        $rs->close();

        return $pages;
    }
}