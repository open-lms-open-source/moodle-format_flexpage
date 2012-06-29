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
 * Flexpage Model Abstract
 *
 * Note: not all models have to extend this!
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
abstract class course_format_flexpage_model_abstract {
    /**
     * @var int
     */
    protected $id;

    /**
     * @return int
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return course_format_flexpage_model_abstract
     */
    public function set_id($id) {
        if (!empty($this->id)) {
            throw new coding_exception('Cannot re-assign cache ID');
        }
        $this->id = $id;
        return $this;
    }

    /**
     * Determine if this has an ID or not
     *
     * @return bool
     */
    public function has_id() {
        return !empty($this->id);
    }

    /**
     * Attach this model's ID to another object
     * and return if it was successful or not
     *
     * @param object|array $var
     * @return bool
     */
    public function attach_id($var) {
        if ($this->has_id()) {
            if (is_array($var)) {
                $var['id'] = $this->get_id();
            } else {
                $var->id = $this->get_id();
            }
            return true;
        }
        return false;
    }

    /**
     * A way to bulk set model properties
     *
     * @param array|object $options
     * @return course_format_flexpage_model_abstract
     */
    public function set_options($options) {
        foreach ($options as $name => $value) {
            $method = "set_$name";
            if (method_exists($this, $method)) {
                $this->$method($value);
            } else {
                // Ignore things that are not a property of this model
                if (property_exists($this, $name)) {
                    $this->$name = $value;
                }
            }
        }
        return $this;
    }
}