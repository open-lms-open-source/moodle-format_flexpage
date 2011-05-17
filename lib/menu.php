<?php
/**
 * @see course_format_flexpage_lib_menu_action
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/menu/action.php');

class course_format_flexpage_lib_menu implements renderable {
    /**
     * @var string
     */
    protected $id;

    /**
     * @var course_format_flexpage_lib_menu_action[]
     */
    protected $actions = array();

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