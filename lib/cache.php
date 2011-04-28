<?php

// @todo require repository/page.php

class course_format_flexpage_lib_info {
    protected $courseid;
    protected $flat = array();
    protected $nested = array();

    /**
     * @var course_format_flexpage_model_page[]
     */
    protected $pages = array();

    /**
     * @var course_format_flexpage_lib_info
     */
    protected static $instance;

    public static function singleton($course = null) {
        global $DB, $COURSE;

        if (is_null($course)) {
            $course = $COURSE;
        } else {
            $course = $course;
        }
        if (!self::$instance instanceof course_format_flexpage_lib_info or self::$instance->get_courseid() != $course->id) {
            if ($info = $DB->get_field('format_page_info', 'info', array('courseid' => $course->id))) {
                $info = unserialize($info);
            }
            if (!$info instanceof course_format_flexpage_lib_info) {
                $info = new course_format_flexpage_lib_info($course->id);
                $info->rebuild();
                // @todo Serialize and store in DB here?
            }
            self::$instance = $info;
        }
        return self::$instance;
    }

    protected function __construct($courseid) {
        // @todo we don't actually want the whole course object to be serialized into the db
        $this->course = $courseid;
    }

    public function get_courseid() {
        return $this->courseid;
    }

    public function get_hierarchy() {
        return $this->hierarchy;
    }

    public function get_flat() {
        return $this->flat;
    }

    public function get_nested() {
        return $this->nested;
    }

    public function get_page_parents($pageid) {
        // return array of parents of a page
    }

    public function get_page($pageid) {
        if (!array_key_exists($page, $this->pages)) {
            // @todo Better error recovery?
            throw new coding_exception("Page with id = $pageid does not exist in cache");
        }
        return $this->pages[$pageid];
    }

    public function rebuild() {
        // @todo break all of these into functions for testing

        $pagerepo = new course_format_flexpage_respository_page();
        $pages    = $pagerepo->get_pages($this->get_courseid());

        $condrepo   = new course_format_flexpage_respository_condition();
        $conditions = $condrepo->get_course_conditions($this->get_courseid());

        // Associate conditions to pages
        foreach ($pages as $page) {
            if (array_key_exists($page->get_id(), $conditions)) {
                $pageconditions = $conditions[$page->get_id()];
            } else {
                $pageconditions = array();
            }
            $page->set_conditions($pageconditions);
        }

        // 1. (DON'T NEED TO ANYMORE) Prune bad condition data
        // 2. DONE Pull all pages, setup classes
        // 3. DONE Pull all conditions, reorganize and associate to pages
        // 4. Build hierarchy arrays, store only IDs
        // 5. While building hierarchy arrays, I'm sure we can repair weight
        // 6. Serialize and store in database
    }
}