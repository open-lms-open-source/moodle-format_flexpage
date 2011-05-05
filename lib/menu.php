<?php
/**
 * @see course_format_flexpage_lib_menu_action
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/menu/action.php');

class course_format_flexpage_lib_menu implements renderable {
    protected $name;

    /**
     * @var course_format_flexpage_lib_menu_action[]
     */
    protected $actions = array();

    public function __construct($name) {
        $this->name = $name;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_actions() {
        return $this->actions;
    }

    public function add_action(course_format_flexpage_lib_menu_action $action) {
        if ($action->get_visible()) {
            $this->actions[] = $action;
        }
        return $this;
    }
}