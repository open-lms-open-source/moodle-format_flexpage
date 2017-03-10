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
 * Testcase class for Flexpage format event observer.
 *
 * @package    format_flexpage
 * @author     Sam Chaffee
 * @copyright  Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../config.php');
global $CFG;

require_once(__DIR__ . '/../../../../blocks/flexpagenav/repository/menu.php');
require_once(__DIR__ . '/../repository/page.php');
require_once(__DIR__ . '/../repository/cache.php');

/**
 * Testcase class for Flexpage format event observer.
 *
 * @package    format_flexpage
 * @copyright  Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class format_flexpage_observer_testcase extends advanced_testcase {

    public function test_course_content_deleted_observed() {
        global $DB;
        $this->resetAfterTest(true);

        $course = $this->getDataGenerator()->create_course(['format' => 'flexpage']);

        $menurepo = new \block_flexpagenav_repository_menu();
        $menu = new \block_flexpagenav_model_menu();
        $menu->set_couseid($course->id)->set_displayname(1)->set_name('name')->set_render('tree')->set_useastab(1);
        $menurepo->save_menu($menu);

        $pagerepo = new \course_format_flexpage_repository_page();
        $page = new \course_format_flexpage_model_page();
        $page->set_name('name')->set_courseid($course->id);
        $pagerepo->save_page($page);

        $cacherepo = new \course_format_flexpage_repository_cache();
        $cache = new \course_format_flexpage_model_cache();
        $cache->set_courseid($course->id)->set_timemodified(time());
        $cacherepo->save_cache($cache);

        $event = \core\event\course_content_deleted::create([
            'objectid' => $course->id,
            'context' => context_course::instance($course->id),
            'other' => [
                'shortname' => $course->shortname,
                'fullname' => $course->fullname,
                'options' => [],
            ],
        ]);

        format_flexpage\observer::course_content_deleted($event);

        $this->assertFalse($DB->record_exists('block_flexpagenav_menu', ['courseid' => $course->id]));
        $this->assertFalse($DB->record_exists('format_flexpage_page', ['courseid' => $course->id]));
        $this->assertFalse($DB->record_exists('format_flexpage_cache', ['courseid' => $course->id]));
    }
}