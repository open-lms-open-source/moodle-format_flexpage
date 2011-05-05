<?php

class course_format_flexpage_lib_menu_action {
    protected $action;
    protected $name;

    /**
     * @var moodle_url
     */
    protected $url;
    protected $visible = true;

    public function __construct($action, $url = array(), $visible = true, $name = null) {
        $this->action = $action;

        $this->set_name($name)
             ->set_url($url)
             ->set_visible($visible);
    }

    public function get_action() {
        return $this->action;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_url() {
        return $this->url;
    }

    public function get_visible() {
        return $this->visible;
    }

    public function set_name($name = null) {
        if (is_null($name)) {
            $this->name = get_string($this->action.'action', 'format_flexpage');
        } else {
            $this->name = $name;
        }
        return $this;
    }

    public function set_url($url = array()) {
        global $CFG;

        require_once($CFG->dirroot.'/course/format/flexpage/repository/cache.php');

        if ($url instanceof moodle_url) {
            $this->url = $url;
        } else {
            $repo = new course_format_flexpage_repository_cache();
            $page = $repo->get_cache()->get_current_page();

            $params = array(
                'controller' => 'ajax',
                'action' => $this->action,
                'courseid' => $page->get_courseid(),
                'pageid' => $page->get_id()
            );

            $this->url = new moodle_url(
                '/course/format/flexpage/view.php',
                array_merge($params, $url)
            );
        }
        return $this;
    }

    public function set_visible($boolean) {
        $this->visible = (boolean) $boolean;
        return $this;
    }
}