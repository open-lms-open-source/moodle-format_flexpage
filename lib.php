<?php
/**
 * Indicates this format uses sections.
 *
 * @return bool Returns true
 */
function callback_flexpage_uses_sections() {
    return false;
}

/**
 * Used to display the course structure for a course where format=weeks
 *
 * This is called automatically by {@link load_course()} if the current course
 * format = flexpage.
 *
 * @param navigation_node $navigation The course node
 * @param array $path An array of keys to the course node
 * @param stdClass $course The course we are loading the section for
 */
//function callback_flexpage_load_content(&$navigation, $course, $coursenode) {
// @todo Implement, maybe populate with pages
//}

/**
 * Toogle display of course contents (sections, activities)
 *
 * @return bool
 */
//function callback_flexpage_display_content() {
// @todo Implement, has to do with navigation...
//    return false;
//}

/**
 * The string that is used to describe a section of the course
 * e.g. Topic, Week...
 *
 * @return string
 */
function callback_flexpage_definition() {
    debugging('here');
    return get_string('page', 'format_flexpage');
}

/**
 * The GET argument variable that is used to identify the section being
 * viewed by the user (if there is one)
 *
 * @return string
 */
function callback_flexpage_request_key() {
    return 'pageid';
}

/**
 * Declares support for course AJAX features
 *
 * @see course_format_ajax_support()
 * @return stdClass
 */
function callback_flexpage_ajax_support() {
    // @todo Finnish implementation...
    $ajaxsupport = new stdClass();
    $ajaxsupport->capable = true;
    $ajaxsupport->testedbrowsers = array('MSIE' => 6.0, 'Gecko' => 20061111, 'Safari' => 531, 'Chrome' => 6.0);
    return $ajaxsupport;
}

/**
 * Determines cm availability based on the pages that the cm is
 * displayed on and their availability
 *
 * @param cm_info $cm
 * @return bool
 */
function callback_flexpage_course_module_available(cm_info $cm) {
    global $DB, $CFG;

    require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

    static $cmidtopages = null;

    if (is_null($cmidtopages)) {
        $context = get_context_instance(CONTEXT_COURSE, $cm->course);

        $records = $DB->get_recordset_sql(
            'SELECT i.subpagepattern AS pageid, f.cmid
               FROM {block_instances} i
         INNER JOIN {block_flexpagemod} f ON i.id = f.instanceid
              WHERE i.parentcontextid = ?
                AND i.subpagepattern IS NOT NULL',
        array($context->id));

        $cmidtopages = array();
        foreach ($records as $record) {
            if (!array_key_exists($record->cmid, $cmidtopages)) {
                $cmidtopages[$record->cmid] = array();
            }
            $cmidtopages[$record->cmid][$record->pageid] = $record->pageid;
        }
        $records->close();
    }
    if (array_key_exists($cm->id, $cmidtopages)) {
        $cache = format_flexpage_cache($cm->course);
        foreach ($cmidtopages[$cm->id] as $pageid) {
            $parents = $cache->get_page_parents($pageid, true);
            foreach ($parents as $parent) {
                if ($parent->is_available($cm->get_modinfo()) !== true) {
                    // If any parent not available, then go onto next page
                    continue 2;
                }
            }
            // Means the page is visible (because itself and parents are visible),
            // If one page is visible then cm is available
            return true;
        }
        // Means no pages were visible, cm is not available
        return false;
    }
    return true;
}

/**
 * Setup the page layout and other properties
 *
 * @param moodle_page $page
 * @return void
 */
function callback_flexpage_set_pagelayout($page) {
    global $CFG;

    require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');
    require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

    $cache = format_flexpage_cache();
    $currentpage = $cache->get_current_page();

    if (empty($CFG->enableavailability) or $cache->is_page_available($currentpage->get_id())) {
        $page->set_pagelayout(course_format_flexpage_lib_moodlepage::LAYOUT);
        $page->set_subpage($currentpage->get_id());
    }
}