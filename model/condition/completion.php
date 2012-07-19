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
 * This condition is based on the completion of a course activity
 *
 * @package format_flexpage
 * @author Mark Nielsen
 */
class course_format_flexpage_model_condition_completion {
    /**
     * The course module ID
     *
     * @var int
     */
    protected $cmid;

    /**
     * The required completion status
     *
     * This is set to a completion constant. EG:
     * COMPLETION_INCOMPLETE
     * COMPLETION_COMPLETE
     * COMPLETION_COMPLETE_PASS
     * COMPLETION_COMPLETE_FAIL
     *
     * @var int
     */
    protected $requiredcompletion;

    /**
     * @param int $cmid The course module ID
     * @param int $requiredcompletion The required completion status
     */
    public function __construct($cmid, $requiredcompletion) {
        $this->cmid               = $cmid;
        $this->requiredcompletion = $requiredcompletion;
    }

    /**
     * @return int
     */
    public function get_cmid() {
        return $this->cmid;
    }

    /**
     * @return int
     */
    public function get_requiredcompletion() {
        return $this->requiredcompletion;
    }
}
