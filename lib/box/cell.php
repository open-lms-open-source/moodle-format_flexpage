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
 * @copyright Copyright (c) 2009 Blackboard Inc. (http://www.blackboardopenlms.com)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @package format_flexpage
 * @author Mark Nielsen
 */

/**
 * @see course_format_flexpage_lib_box_abstract
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/box/abstract.php');

/**
 * A box cell that can contain content
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_lib_box_cell extends course_format_flexpage_lib_box_abstract {
    /**
     * @var string
     */
    protected $contents = '';

    /**
     * @param string $contents
     * @param array $attributes
     */
    public function __construct($contents = '', array $attributes = array()) {
        $this->set_contents($contents);
        parent::__construct($attributes);
    }

    /**
     * Get the box's unique class name
     *
     * @return string
     */
    protected function get_classname() {
        return 'format_flexpage_cell';
    }

    /**
     * @return string
     */
    public function get_contents() {
        return $this->contents;
    }

    /**
     * @param string $contents
     * @return course_format_flexpage_lib_box_cell
     */
    public function set_contents($contents) {
        $this->contents = $contents;
        return $this;
    }

    /**
     * Append more content to current contents
     *
     * @param string $morecontents
     * @return course_format_flexpage_lib_box_cell
     */
    public function append_contents($morecontents) {
        $this->contents .= $morecontents;
        return $this;
    }
}
