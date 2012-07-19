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
 * @package format_flexpage
 * @author Mark Nielsen
 */

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
 * @return array
 */
function callback_flexpage_load_content(global_navigation &$navigation, stdClass $course, navigation_node $coursenode) {
    global $CFG, $COURSE;

    require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

    $cache         = format_flexpage_cache($course->id);
    $current       = $cache->get_current_page();
    $activepageids = $cache->get_page_parents($current);
    $activepageids = array_keys($activepageids);
    $parentnodes   = array(0 => $coursenode);
    $modinfo       = get_fast_modinfo($course);

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
        $availability = $cache->is_page_available($page, $modinfo);

        if ($availability === false) {
            continue;
        }
        $node = $parentnode->add(format_string($page->get_name()), $page->get_url());
        $node->hidden = is_string($availability);
        $parentnodes[$page->get_id()] = $node;

        // Only force open or make active when it's the current course
        if ($COURSE->id == $course->id) {
            if (in_array($page->get_id(), $activepageids)) {
                $node->force_open();
            } else if ($page->get_id() == $current->get_id()) {
                $node->make_active();
            }
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
    if (!function_exists('course_format_set_pagelayout')) {
        global $PAGE;
        callback_flexpage_set_pagelayout($PAGE);
    }
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
                if ($cache->is_page_available($parent, $cm->get_modinfo()) !== true) {
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

    if (empty($CFG->enableavailability) or $cache->is_page_available($currentpage) === true) {
        $page->set_pagelayout(course_format_flexpage_lib_moodlepage::LAYOUT);
        $page->set_subpage($currentpage->get_id());
    }
}

/**
 * Don't show the add block UI
 *
 * @return bool
 */
function callback_flexpage_add_block_ui() {
    return false;
}

/**
 * Cleanup all things flexpage on course deletion
 *
 * @param int $courseid
 * @return void
 */
function format_flexpage_delete_course($courseid) {
    global $CFG;

    require_once($CFG->dirroot.'/blocks/flexpagenav/repository/menu.php');
    require_once($CFG->dirroot.'/course/format/flexpage/repository/page.php');
    require_once($CFG->dirroot.'/course/format/flexpage/repository/cache.php');

    $menurepo = new block_flexpagenav_repository_menu();
    $menurepo->delete_course_menus($courseid);

    $pagerepo = new course_format_flexpage_repository_page();
    $pagerepo->delete_course_pages($courseid);

    $cacherepo = new course_format_flexpage_repository_cache();
    $cacherepo->delete_course_cache($courseid);
}