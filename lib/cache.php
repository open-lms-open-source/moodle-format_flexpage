<?php
/**
 * @see course_format_flexpage_repository_page
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/page.php');

/**
 * @see course_format_flexpage_repository_condition
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/condition.php');

class course_format_flexpage_lib_cache {
    protected $courseid;
    protected $flat = array();
    protected $nested = array();

    /**
     * @var course_format_flexpage_model_page[]
     */
    protected $pages = array();

    /**
     * @var course_format_flexpage_lib_cache
     */
    protected static $instance;

    public static function singleton($course = null) {
        global $DB, $COURSE;

        if (is_null($course)) {
            $course = $COURSE;
        } else {
            $course = $course;
        }
        if (!self::$instance instanceof course_format_flexpage_lib_cache or self::$instance->get_courseid() != $course->id) {
            if ($info = $DB->get_field('format_page_info', 'info', array('courseid' => $course->id))) {
                $info = unserialize($info);
            }
            if (!$info instanceof course_format_flexpage_lib_cache) {
                $info = new course_format_flexpage_lib_cache($course->id);
                $info->rebuild();
                // @todo Serialize and store in DB here?
            }
            self::$instance = $info;
        }
        return self::$instance;
    }

    public function __construct($courseid) {
        $this->courseid = $courseid;
    }

    public function get_courseid() {
        return $this->courseid;
    }

    public function get_page_parents($pageid) {
        // return array of parents of a page
    }

    public function get_pages() {
        return $this->pages;
    }
    public function get_page($pageid) {
        if (!array_key_exists($pageid, $this->pages)) {
            // @todo Better error recovery?
            throw new coding_exception("Page with id = $pageid does not exist in cache");
        }
        return $this->pages[$pageid];
    }

    public function rebuild(course_format_flexpage_repository_page $pagerepo = null,
                            course_format_flexpage_repository_condition $condrepo = null) {

        if (is_null($pagerepo)) {
            $pagerepo = new course_format_flexpage_repository_page();
        }
        if (is_null($condrepo)) {
            $condrepo = new course_format_flexpage_repository_condition();
        }
        $this->pages = $pagerepo->get_pages($this->get_courseid(), 'parentid, weight');
        $conditions  = $condrepo->get_course_conditions($this->get_courseid());

        // Associate conditions to pages
        foreach ($this->pages as $page) {
            if (array_key_exists($page->get_id(), $conditions)) {
                $pageconditions = $conditions[$page->get_id()];
            } else {
                $pageconditions = array();
            }
            $page->set_conditions($pageconditions);
        }

        $this->counts = array('_sort' => 0, 'get_depth' => 0, 'is_child' => 0);
        $pages = $this->pages;
        $microtime = microtime();
        uasort($this->pages, array($this, '_sort'));
        $difftime = microtime_diff($microtime, microtime());
        mtrace("Execution took ".$difftime." seconds");

        print_object($this->counts);

        $this->counts = array('_sort' => 0, 'get_depth' => 0, 'is_child' => 0);
        uasort($pages, array($this, '_sort2'));
        print_object($this->counts);

        // 1. (DON'T NEED TO ANYMORE) Prune bad condition data
        // 2. DONE Pull all pages, setup classes
        // 3. DONE Pull all conditions, reorganize and associate to pages
        // 4. Build hierarchy arrays, store only IDs
        // 5. While building hierarchy arrays, I'm sure we can repair weight
        // 6. Serialize and store in database
    }

    public function get_depth(course_format_flexpage_model_page $page, $depth = 0) {
        $this->counts['get_depth']++;

        while ($page->get_parentid() > 0) {
            $depth++;
            $page = $this->get_page($page->get_parentid());
        }
        return $depth;
    }

    public function is_child(course_format_flexpage_model_page $parent, course_format_flexpage_model_page $child) {
        $this->counts['is_child']++;
        while ($parent->get_id() != $child->get_parentid() and $child->get_parentid() > 0) {
            $child = $this->get_page($child->get_parentid());
        }
        if ($parent->get_id() == $child->get_parentid()) {
            return true;
        }
        return false;
    }
}