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

defined('MOODLE_INTERNAL') || die();

require_once(dirname(dirname(dirname(__FILE__))).'/format/lib.php');

/**
 * Main class for the Flexpage course format
 *
 * @package format_flexpage
 * @author Mark Nielsen
 */
class format_flexpage extends format_base {
    /**
     * Load the navigation with all of the course's flexpages
     *
     * @param global_navigation $navigation
     * @param navigation_node $node
     * @return array
     */
    public function extend_course_navigation($navigation, navigation_node $node) {
        global $CFG, $COURSE;

        if (!$course = $this->get_course()) {
            return array();
        }
        require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

        $cache         = format_flexpage_cache($course->id);
        $current       = $cache->get_current_page();
        $activepageids = $cache->get_page_parents($current);
        $activepageids = array_keys($activepageids);
        $parentnodes   = array(0 => $node);
        $modinfo       = get_fast_modinfo($course);

        foreach ($cache->get_pages() as $page) {
            /**
             * @var navigation_node $childnode
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
            $childnode = $parentnode->add(format_string($page->get_name()), $page->get_url());
            $childnode->hidden = is_string($availability);
            $parentnodes[$page->get_id()] = $childnode;

            // Only force open or make active when it's the current course
            if ($COURSE->id == $course->id) {
                if (in_array($page->get_id(), $activepageids)) {
                    $childnode->force_open();
                } else if ($page->get_id() == $current->get_id()) {
                    $childnode->make_active();
                }
            }
        }
        unset($activepageids, $parentnodes);

        // @todo Would be neat to return section zero with the name of "Activities" and it had every activity underneath it.
        // @todo This would require though that every activity was stored in section zero and had proper ordering

        return array();
    }

    public function supports_ajax() {
        return (object) array(
            'capable'        => true,
            'testedbrowsers' => array(
                'MSIE'   => 6.0,
                'Gecko'  => 20061111,
                'Safari' => 531,
                'Chrome' => 6.0
            )
        );
    }

    public function get_view_url($section, $options = array()) {
        return new moodle_url('/course/view.php', array('id' => $this->get_courseid()));
    }

    public function get_default_blocks() {
        return array(
            BLOCK_POS_LEFT  => array(),
            BLOCK_POS_RIGHT => array(),
        );
    }

    /**
     * Modify the page layout if view the course page
     *
     * @param moodle_page $page
     */
    public function page_set_course(moodle_page $page) {
        global $CFG, $SCRIPT;

        // ONLY modify layout if we are going to view the course page
        if ($SCRIPT != '/course/view.php') {
            return;
        }
        require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

        if ($page->pagelayout == 'admin') {
            return; // This is for block editing.
        }
        if (format_flexpage_set_pagelayout($page)) {
            // Hack alert - we call this to "freeze" the page layout et al
            // See format_flexpage_renderer::__construct for the rest of the hack
            $page->theme;
        }
    }

    /**
     * Prevent viewing of the activity if all of the
     * flexpages it is on are not available to the user.
     *
     * @param moodle_page $page
     * @throws moodle_exception
     */
    public function page_set_cm(moodle_page $page) {
        global $DB, $CFG;

        require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

        $context = context_course::instance($page->cm->course);

        $records = $DB->get_recordset_sql('
            SELECT DISTINCT i.subpagepattern AS pageid
              FROM {block_instances} i
        INNER JOIN {block_flexpagemod} f ON i.id = f.instanceid
             WHERE f.cmid = ?
               AND i.parentcontextid = ?
               AND i.subpagepattern IS NOT NULL
        ', array($page->cm->id, $context->id));

        if ($records->valid()) {
            $cache   = format_flexpage_cache($page->cm->course);
            $visible = false;
            foreach ($records as $record) {
                $parents = $cache->get_page_parents($cache->get_page($record->pageid), true);
                foreach ($parents as $parent) {
                    if ($cache->is_page_available($parent) !== true) {
                        // If any parent not available, then go onto next page
                        continue 2;
                    }
                }
                // Means the page is visible (because itself and parents are visible),
                // If one page is visible then cm is available
                $visible = true;
                break;
            }
            $records->close();

            // Means no pages were visible, cm is not available
            if (!$visible) {
                throw new moodle_exception('preventactivityview', 'format_flexpage', new moodle_url('/course/view.php', array('id' => $page->cm->course)));
            }
        }
        $records->close();
    }
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