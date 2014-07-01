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
 * Event hooks
 *
 * @author Mark Nielsen
 * @package format_flexpage
 **/
$observers = array(
    array(
        'eventname'   => '\core\event\course_module_created',
        'includefile' => '/course/format/flexpage/lib/eventhandler.php',
        'callback'    => 'course_format_flexpage_lib_eventhandler::mod_created',
    ),

    array(
        'eventname'   => '\core\event\course_module_deleted',
        'includefile' => '/course/format/flexpage/lib/eventhandler.php',
        'callback'    => 'course_format_flexpage_lib_eventhandler::mod_deleted',
    ),
);
