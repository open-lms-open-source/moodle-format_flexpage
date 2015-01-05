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

use core_availability\info;

require_once($CFG->libdir.'/conditionlib.php');

/**
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_lib_condition extends info {
    /**
     * @var course_format_flexpage_model_page
     */
    protected $page;

    /**
     * @param course_format_flexpage_model_page $page
     */
    public function __construct(course_format_flexpage_model_page $page) {
        $this->page = $page;

        $rec = (object) array(
            'id'               => $page->get_id(),
            'course'           => $page->get_courseid(),
            'availablefrom'    => $page->get_availablefrom(),
            'availableuntil'   => $page->get_availableuntil(),
            'releasecode'      => $page->get_releasecode(),
            'showavailability' => $page->get_showavailability(),
        );

        $availability = self::convert_legacy_fields($rec, true);

        $show = $page->get_showavailability();
        foreach ($page->get_conditions() as $condition) {
            if ($condition instanceof course_format_flexpage_model_condition_completion) {
                $completion   = (object) array(
                    'sourcecmid'         => $condition->get_cmid(),
                    'requiredcompletion' => $condition->get_requiredcompletion(),
                );
                $availability = self::add_legacy_availability_condition($availability, $completion, $show);
            } else if ($condition instanceof course_format_flexpage_model_condition_grade) {
                $completion   = (object) array(
                    'grademin'    => $condition->get_min(),
                    'grademax'    => $condition->get_max(),
                    'gradeitemid' => $condition->get_gradeitemid(),
                );
                $availability = self::add_legacy_availability_condition($availability, $completion, $show);
            } else if ($condition instanceof course_format_flexpage_model_condition_field) {
                $field = (object) array(
                    'operator' => $condition->get_operator(),
                    'value'    => $condition->get_value(),
                );
                if ($condition->is_custom()) {
                    if ($condition->get_shortname() === null) {
                        continue; // Cannot use, field no longer exists.
                    }
                    $field->shortname = $condition->get_shortname();
                } else {
                    $field->userfield = $condition->get_field();
                }
                $availability = self::add_legacy_availability_field_condition($availability, $field, $show);
            }
        }
        if ($page->get_display() == course_format_flexpage_model_page::DISPLAY_HIDDEN) {
            $visible = 0;
        } else {
            $visible = 1;
        }
        parent::__construct(get_course($page->get_courseid()), $visible, $availability);
    }

    /**
     * Gets context used for checking capabilities for this item.
     *
     * @return \context Context for this item
     */
    public function get_context() {
        return \context_course::instance($this->get_course()->id);
    }

    /**
     * Obtains the name of the item (cm_info or section_info, at present) that
     * this is controlling availability of. Name should be formatted ready
     * for on-screen display.
     *
     * @return string Name of item
     */
    protected function get_thing_name() {
        return format_string($this->page->get_name());
    }

    /**
     * Stores an updated availability tree JSON structure into the relevant
     * database table.
     *
     * @param string $availabilty New JSON value
     */
    protected function set_in_database($availabilty) {
        throw new coding_exception('The set_in_database method has not been implemented for Flexpage');
    }
}
