<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Event observer class for Flexpage format.
 *
 * @package    format_flexpage
 * @author     Sam Chaffee
 * @copyright  Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace format_flexpage;

use core\event\course_content_deleted;

defined('MOODLE_INTERNAL') || die();

/**
 * Event observer class for Flexpage format.
 *
 * @package    format_flexpage
 * @copyright  Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {

    /**
     * Cleanup all things flexpage on course deletion.
     *
     * @param course_content_deleted $event
     */
    public static function course_content_deleted(course_content_deleted $event) {
        global $CFG;

        require_once($CFG->dirroot.'/blocks/flexpagenav/repository/menu.php');
        require_once($CFG->dirroot.'/course/format/flexpage/repository/page.php');
        require_once($CFG->dirroot.'/course/format/flexpage/repository/cache.php');

        $courseid = $event->objectid;

        $menurepo = new \block_flexpagenav_repository_menu();
        $menurepo->delete_course_menus($courseid);

        $pagerepo = new \course_format_flexpage_repository_page();
        $pagerepo->delete_course_pages($courseid);

        $cacherepo = new \course_format_flexpage_repository_cache();
        $cacherepo->delete_course_cache($courseid);
    }
}