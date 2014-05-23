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

use core\event\course_module_created;
use core\event\course_module_deleted;

/**
 * Event Handler Class
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_lib_eventhandler {
    /**
     * Do the fastest check possible to determine if the
     * passed course ID has format flexpage.
     *
     * @static
     * @param int $courseid
     * @return bool
     */
    protected static function is_flexpage_format($courseid) {
        global $DB, $COURSE;

        if ($COURSE->id != $courseid) {
            $format = $DB->get_field('course', 'format', array('id' => $courseid));
        } else {
            $format = $COURSE->format;
        }
        return ($format == 'flexpage');
    }

    /**
     * Handler for event mod_created
     *
     * When the format is flexpage, we add
     * new activities as a block to the current page.
     *
     * @static
     * @param course_module_created $event
     * @return void
     */
    public static function mod_created(course_module_created $event) {
        global $CFG, $SESSION;

        if (!empty($SESSION->format_flexpage_mod_region)) {
            $region = $SESSION->format_flexpage_mod_region;
        } else {
            $region = false;
        }
        unset($SESSION->format_flexpage_mod_region);

        if (self::is_flexpage_format($event->courseid) && !PHPUNIT_TEST) {
            require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');
            require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

            try {
                $page = format_flexpage_cache()->get_current_page();
                course_format_flexpage_lib_moodlepage::add_activity_block($page, $event->objectid, $region);
            } catch (Exception $e) {
            }
        }
    }

    /**
     * Handler for event mod_deleted
     *
     * When the format is flexpage, we remove every
     * flexpagemod block that is associated to the
     * deleted activity.
     *
     * @static
     * @param course_module_deleted $event
     * @return void
     */
    public static function mod_deleted(course_module_deleted $event) {
        global $CFG;

        if (self::is_flexpage_format($event->courseid) && !PHPUNIT_TEST) {
            require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');
            course_format_flexpage_lib_moodlepage::delete_mod_blocks($event->objectid);
        }
    }
}