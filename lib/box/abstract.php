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
 * @copyright Copyright (c) 2009 Blackboard Inc. (http://www.blackboard.com)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @package format_flexpage
 * @author Mark Nielsen
 */

/**
 * Abstract representation of a box
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
abstract class course_format_flexpage_lib_box_abstract implements renderable {
    /**
     * HTML tag attributes
     *
     * @var array
     */
    protected $attributes;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array()) {
        $this->set_attributes($attributes);
    }

    /**
     * Get the box's unique class name
     *
     * @abstract
     * @return string
     */
    abstract protected function get_classname();

    /**
     * @return array
     */
    public function get_attributes() {
        $attributes = $this->attributes;
        if (!empty($attributes['class'])) {
            $attributes['class'] = $this->get_classname().' '.$attributes['class'];
        } else {
            $attributes['class'] = $this->get_classname();
        }
        return $attributes;
    }

    /**
     * Set and override any existing attributes
     *
     * @param array $attributes
     * @return course_format_flexpage_lib_box_abstract
     */
    public function set_attributes(array $attributes) {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Set and merge with any existing attributes
     *
     * @param array $attributes
     * @return course_format_flexpage_lib_box_abstract
     */
    public function add_attributes(array $attributes) {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    /**
     * Append an attribute to it's existing value
     *
     * @param string $name
     * @param string $value
     * @return course_format_flexpage_lib_box_abstract
     */
    public function append_attribute($name, $value) {
        $attributes = $this->attributes;
        if (!empty($attributes[$name])) {
            $this->attributes[$name] = $attributes[$name].' '.$value;
        } else {
            $this->attributes[$name] = $value;
        }
        return $this;
    }
}
