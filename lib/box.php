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
 * @see course_format_flexpage_lib_box_abstract
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/box/abstract.php');

/**
 * @see course_format_flexpage_lib_box_row
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/box/row.php');

/**
 * A box that can contain rows
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_lib_box extends course_format_flexpage_lib_box_abstract {
    /**
     * @var course_format_flexpage_lib_box_row[]
     */
    protected $rows = array();

    /**
     * Get the box's unique class name
     *
     * @return string
     */
    protected function get_classname() {
        return 'format_flexpage_box';
    }

    /**
     * @return array|course_format_flexpage_lib_box_row[]
     */
    public function get_rows() {
        return $this->rows;
    }

    /**
     * @param course_format_flexpage_lib_box_row $row
     * @return course_format_flexpage_lib_box
     */
    public function add_row(course_format_flexpage_lib_box_row $row) {
        $this->rows[] = $row;
        return $this;
    }

    /**
     * @param array $attributes
     * @return course_format_flexpage_lib_box_row Returns the newly created row so you can add cells
     */
    public function add_new_row(array $attributes = array()) {
        $row = new course_format_flexpage_lib_box_row($attributes);
        $this->add_row($row);
        return $row;
    }
}