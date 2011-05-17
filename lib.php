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
 * Gets the name for the provided section.
 *
 * @param stdClass $course
 * @param stdClass $section
 * @return string
 */
function callback_flexpage_get_section_name($course, $section) {
    // @todo Probably delete this function
    debugging('here');
    global $CFG;
    require_once($CFG->dirroot.'/course/format/topics/lib.php');
    return callback_topics_get_section_name($course, $section);
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
 * Setup the page layout and other properties
 *
 * @param moodle_page $page
 * @return void
 */
function callback_flexpage_set_pagelayout($page) {
    global $CFG;

    require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');
    require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

    $currentpage = format_flexpage_cache()->get_current_page();

    $page->set_pagelayout(course_format_flexpage_lib_moodlepage::LAYOUT);
    $page->set_subpage($currentpage->get_id());
}