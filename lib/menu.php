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
 * @package local/mr
 * @author Mark Nielsen
 */

/**
 * @see course_format_flexpage_lib_menu_action
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/menu/action.php');

/**
 * Menu
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_lib_menu implements renderable {
    /**
     * @var string
     */
    protected $id;

    /**
     * @var course_format_flexpage_lib_menu_action[]
     */
    protected $actions = array();

    /**
     * @param string $id
     */
    public function __construct($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get display name
     *
     * @return string
     */
    public function get_name() {
        return get_string($this->get_id().'menu', 'format_flexpage');
    }

    /**
     * @throws coding_exception
     * @param string $action The action to get
     * @return course_format_flexpage_lib_menu_action
     */
    public function get_action($action) {
        if (!array_key_exists($action, $this->actions)) {
            throw new coding_exception("The action with name $action does not exist");
        }
        return $this->actions[$action];
    }

    /**
     * @return course_format_flexpage_lib_menu_action[]
     */
    public function get_actions() {
        return $this->actions;
    }

    /**
     * @param course_format_flexpage_lib_menu_action $action
     * @return course_format_flexpage_lib_menu
     */
    public function add_action(course_format_flexpage_lib_menu_action $action) {
        $this->actions[$action->get_action()] = $action;
        return $this;
    }
}