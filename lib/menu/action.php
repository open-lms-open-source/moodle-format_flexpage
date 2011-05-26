<?php

require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

/**
 * Menu item for a menu
 *
 * @author Mark Nielsen
 * @package format_flexpage
 * @see course_format_flexpage_lib_menu
 */
class course_format_flexpage_lib_menu_action {
    /**
     * The menu item's action
     *
     * @var string
     */
    protected $action;

    /**
     * Human readable menu item name
     *
     * @var string
     */
    protected $name;

    /**
     * @var moodle_url
     */
    protected $url;

    /**
     * If the menu item is visible to the current user or not
     *
     * @var bool
     */
    protected $visible = true;

    /**
     * @param string $action
     * @param bool $visible
     * @param array|moodle_url $url
     * @param null|string $name
     */
    public function __construct($action, $visible = true, $url = array(), $name = null) {
        $this->action = $action;

        $this->set_name($name)
             ->set_url($url)
             ->set_visible($visible);
    }

    /**
     * @return string
     */
    public function get_action() {
        return $this->action;
    }

    /**
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * @return moodle_url
     */
    public function get_url() {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function get_visible() {
        return $this->visible;
    }

    /**
     * @param null|string $name
     * @return course_format_flexpage_lib_menu_action
     */
    public function set_name($name = null) {
        if (is_null($name)) {
            $this->name = get_string($this->action.'action', 'format_flexpage');
        } else {
            $this->name = $name;
        }
        return $this;
    }

    /**
     * @param array|moodle_url $url
     * @return course_format_flexpage_lib_menu_action
     */
    public function set_url($url = array()) {
        if ($url instanceof moodle_url) {
            $this->url = $url;
        } else {
            $page   = format_flexpage_cache()->get_current_page();
            $params = array(
                'controller' => 'ajax',
                'action' => $this->action,
                'courseid' => $page->get_courseid(),
                'pageid' => $page->get_id()
            );
            $this->url = new moodle_url('/course/format/flexpage/view.php', array_merge($params, $url));
        }
        return $this;
    }

    /**
     * @param bool $boolean
     * @return course_format_flexpage_lib_menu_action
     */
    public function set_visible($boolean) {
        $this->visible = (boolean) $boolean;
        return $this;
    }
}