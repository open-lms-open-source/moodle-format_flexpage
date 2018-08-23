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
 * This condition is based on a grade item and it's score
 *
 * @package format_flexpage
 * @author Mark Nielsen
 */
class course_format_flexpage_model_condition_grade {
    /**
     * The grade item ID
     *
     * @var int
     */
    protected $gradeitemid;

    /**
     * The minimum percent score needed in grade item
     * Null means this limit is ignored.
     *
     * @var int|null
     */
    protected $min = null;

    /**
     * The maximum percent score needed in grade item.
     * Null means this limit is ignored.
     *
     * @var int|null
     */
    protected $max = null;

    /**
     * The grade item's name
     *
     * @var string
     */
    protected $name;

    /**
     * @param int $gradeitemid The grade item ID
     * @param int|null $min
     * @param int|null $max
     * @param string $name
     */
    public function __construct($gradeitemid, $min, $max, $name = '!missing') {
        $this->set_gradeitemid($gradeitemid)
             ->set_min($min)
             ->set_max($max)
             ->set_name($name);
    }

    /**
     * @param int $gradeitemid
     * @return course_format_flexpage_model_condition_grade
     */
    public function set_gradeitemid($gradeitemid) {
        $this->gradeitemid = $gradeitemid;
        return $this;
    }

    /**
     * @return int
     */
    public function get_gradeitemid() {
        return $this->gradeitemid;
    }

    /**
     * @param int|null $max
     * @return course_format_flexpage_model_condition_grade
     */
    public function set_max($max) {
        if ($max === '') {
            $max = NULL;
        }
        $this->max = $max;
        return $this;
    }

    /**
     * @return int|null
     */
    public function get_max() {
        return $this->max;
    }

    /**
     * @param int|null $min
     * @return course_format_flexpage_model_condition_grade
     */
    public function set_min($min) {
        if ($min === '') {
            $min = null;
        }
        $this->min = $min;
        return $this;
    }

    /**
     * @return int|null
     */
    public function get_min() {
        return $this->min;
    }

    /**
     * @param string $name
     * @return course_format_flexpage_model_condition_grade
     */
    public function set_name($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function get_name() {
        return $this->name;
    }
}
