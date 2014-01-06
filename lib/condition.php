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

require_once($CFG->libdir.'/conditionlib.php');

/**
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_lib_condition extends condition_info_base {
    /**
     * @param course_format_flexpage_model_page $page
     */
    public function __construct(course_format_flexpage_model_page $page) {
        // Generate an $item that this class likes
        if ($page->get_display() == course_format_flexpage_model_page::DISPLAY_HIDDEN) {
            $visible = 0;
        } else {
            $visible = 1;
        }
        $item = (object) array(
            'id'                   => $page->get_id(),
            'course'               => $page->get_courseid(),
            'visible'              => $visible,
            'availablefrom'        => $page->get_availablefrom(),
            'availableuntil'       => $page->get_availableuntil(),
            'releasecode'          => $page->get_releasecode(),
            'showavailability'     => $page->get_showavailability(),
            'conditionscompletion' => array(),
            'conditionsgrade'      => array(),
            'conditionsfield'      => array(),
        );
        foreach ($page->get_conditions() as $condition) {
            if ($condition instanceof course_format_flexpage_model_condition_completion) {
                $item->conditionscompletion[$condition->get_cmid()] = $condition->get_requiredcompletion();
            } else if ($condition instanceof course_format_flexpage_model_condition_grade) {
                $item->conditionsgrade[$condition->get_gradeitemid()] = (object) array(
                    'min'  => $condition->get_min(),
                    'max'  => $condition->get_max(),
                    'name' => $condition->get_name(),
                );
            } else if ($condition instanceof course_format_flexpage_model_condition_field) {
                $item->conditionsfield[$condition->get_field()] = (object) array(
                    'fieldname' => $condition->get_fieldname(),
                    'operator'  => $condition->get_operator(),
                    'value'     => $condition->get_value(),
                );
            }
        }
        parent::__construct($item, 'format_flexpage', 'pageid', CONDITION_MISSING_NOTHING, true);
    }

    public static function fill_availability_conditions($page) {
        self::prevent_usage(__FUNCTION__);
    }

    public function add_completion_condition($cmid, $requiredcompletion) {
        self::prevent_usage(__FUNCTION__);
    }

    public function add_grade_condition($gradeitemid, $min, $max, $updateinmemory = false) {
        self::prevent_usage(__FUNCTION__);
    }

    public function wipe_conditions() {
        self::prevent_usage(__FUNCTION__);
    }

    protected function get_context() {
        return context_course::instance($this->item->course);
    }

    /**
     * We do not use a lot of features from this class, so prevent
     * ones that would break anyways.
     *
     * @static
     * @param string $function
     * @throws coding_exception
     */
    protected static function prevent_usage($function) {
        throw new coding_exception("The '$function' method cannot be called on this class'");
    }
}
