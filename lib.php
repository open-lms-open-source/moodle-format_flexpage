<?php
/**
 * Indicates this format uses sections or not
 *
 * @return bool
 */
function callback_flexpage_uses_sections() {
    return false;
}

/**
 * Used to display the course structure for a course
 *
 * This is called automatically by {@link load_course()} if the current course
 * format = flexpage.
 *
 * @param global_navigation $navigation Navigation
 * @param stdClass $course The course we are loading the section for
 * @param navigation_node $coursenode The course node
 */
function callback_flexpage_load_content(global_navigation &$navigation, stdClass $course, navigation_node $coursenode) {
    global $CFG;

    require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

    $cache         = format_flexpage_cache($course->id);
    $current       = $cache->get_current_page();
    $activepageids = $cache->get_page_parents($current);
    $activepageids = array_keys($activepageids);
    $parentnodes   = array(0 => $coursenode);

    foreach ($cache->get_pages() as $page) {
        /**
         * @var navigation_node $node
         * @var navigation_node $parentnode
         */

        if (!$cache->is_page_in_menu($page)) {
            continue;
        }
        if (!array_key_exists($page->get_parentid(), $parentnodes)) {
            continue;
        }
        $parentnode = $parentnodes[$page->get_parentid()];

        if ($parentnode->hidden) {
            continue;
        }
        $node = $parentnode->add(format_string($page->get_display_name()), $page->get_url(), navigation_node::TYPE_CUSTOM, null, $page->get_id());
        $node->hidden = (!$cache->is_page_available($page));
        $parentnodes[$page->get_id()] = $node;

        if (in_array($page->get_id(), $activepageids)) {
            $node->force_open();
        } else if ($page->get_id() == $current->get_id()) {
            $node->make_active();
        }
    }
    unset($activepageids, $parentnodes);

    // @todo Would be neat to return section zero with the name of "Activities" and it had every activity underneath it.
    // @todo This would require though that every activity was stored in section zero and had proper ordering

    return array();
}

/**
 * The string that is used to describe a section of the course
 * e.g. Topic, Week...
 *
 * @return string
 */
function callback_flexpage_definition() {
    return get_string('section');
}

/**
 * The GET argument variable that is used to identify the section being
 * viewed by the user (if there is one)
 *
 * @return string
 */
function callback_flexpage_request_key() {
    return 'section';
}

/**
 * Declares support for course AJAX features
 *
 * @see course_format_ajax_support()
 * @return stdClass
 */
function callback_flexpage_ajax_support() {
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
            $parents = $cache->get_page_parents($cache->get_page($pageid), true);
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

    if (empty($CFG->enableavailability) or $cache->is_page_available($currentpage)) {
        $page->set_pagelayout(course_format_flexpage_lib_moodlepage::LAYOUT);
        $page->set_subpage($currentpage->get_id());
    }
}