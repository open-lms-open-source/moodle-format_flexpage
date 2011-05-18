<?php
/**
 * @see course_format_flexpage_repository_page
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/page.php');

/**
 * @see course_format_flexpage_repository_condition
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/condition.php');

class course_format_flexpage_model_cache {
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $courseid = null;

    /**
     * @var course_format_flexpage_model_page[]
     */
    protected $pages;

    /**
     * @var int
     */
    protected $timemodified;

    /**
     * Flag for if the cache has been built or not
     *
     * @var bool
     */
    protected $built = false;

    /**
     * @var course_format_flexpage_repository_page
     */
    protected $pagerepo;

    /**
     * @var course_format_flexpage_repository_condition
     */
    protected $condrepo;

    /**
     * It is most efficient to access the course cache via
     * course_format_flexpage_repository_cache class.
     *
     * @param  $courseid
     */
    public function __construct() {
        $this->pagerepo = new course_format_flexpage_repository_page();
        $this->condrepo = new course_format_flexpage_repository_condition();
        $this->set_timemodified();
    }

    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_courseid() {
        return $this->courseid;
    }

    public function set_courseid($id) {
        $this->courseid = $id;
    }

    public function get_timemodified() {
        return $this->timemodified;
    }

    public function set_timemodified($time = null) {
        if (is_null($time)) {
            $time = time();
        }
        $this->timemodified = $time;
        return $this;
    }

    /**
     * @throws moodle_exception
     * @param  $pageid
     * @return course_format_flexpage_model_page
     */
    public function get_page($pageid) {
        $this->require_built();
        if (!array_key_exists($pageid, $this->pages)) {
            // @todo Better error recovery?
            throw new moodle_exception('pagenotfound', 'format_flexpage', '', $pageid);
        }
        return $this->pages[$pageid];
    }

    public function get_pages() {
        $this->require_built();
        return $this->pages;
    }

    public function set_pages(array $pages) {
        $this->pages = $pages;
        $this->built = true;
        return $this;
    }

    /**
     * Get page parents
     *
     * @param int $pageid The page ID to find the parents of
     * @param bool $includechild Include the passed pageid in the return
     * @return course_format_flexpage_model_page[]
     */
    public function get_page_parents($pageid, $includechild = false) {
        $this->require_built();
        $parents = array();
        $page    = $this->get_page($pageid);

        if ($includechild) {
            $parents[$page->get_id()] = $page;
        }
        while ($page->get_parentid() != 0) {
            $page = $this->get_page($page->get_parentid());
            $parents[$page->get_id()] = $page;
        }
        // Get correct order
        return array_reverse($parents, true);
    }

    public function set_repository_page(course_format_flexpage_repository_page $pagerepo) {
        $this->pagerepo = $pagerepo;
        return $this;
    }

    public function set_repository_condition(course_format_flexpage_repository_condition $condrepo) {
        $this->condrepo = $condrepo;
        return $this;
    }

    public function has_been_built() {
        return $this->built;
    }

    public function require_built() {
        if (!$this->has_been_built()) {
            throw new coding_exception('Cache must be built');
        }
    }

    public function build() {
        if (is_null($this->get_courseid())) {
            throw new coding_exception('Must set course ID before building cache');
        }

        // Ensure that we have at least one page
        $this->pagerepo->create_default_page($this->get_courseid());

        // Fetch our pages and conditions
        $pages       = $this->pagerepo->get_pages(array('courseid' => $this->get_courseid()), 'parentid, weight');
        $conditions  = $this->condrepo->get_course_conditions($this->get_courseid());

        // Make sure our weights are all in order
        $this->repair_page_weights($pages);

        // Associate conditions to pages
        foreach ($pages as $page) {
            if (array_key_exists($page->get_id(), $conditions)) {
                $pageconditions = $conditions[$page->get_id()];
            } else {
                $pageconditions = array();
            }
            $page->set_conditions($pageconditions);
        }

        // Sort all of the pages
        $pages = $this->sort_pages($pages);

        // Store in house
        $this->set_pages($pages);
        $this->set_timemodified();
        unset($pages);
    }

    public function clear() {
        unset($this->pages);
        $this->pages = null;
        $this->built = false;
        $this->set_timemodified();
    }

    /**
     * Sorts pages
     *
     * @param course_format_flexpage_model_page[] $parentpages Parent pages to process
     * @param int $parentid The parent ID of the children to sort
     * @return course_format_flexpage_model_page[]
     */
    protected function sort_pages($pages, $parentid = 0) {
        $return     = array();
        $childpages = $this->filter_children($parentid, $pages);
        foreach ($childpages as $page) {
            $return[$page->get_id()] = $page;
            $return  += $this->sort_pages($pages, $page->get_id());
        }
        return $return;
    }

    /**
     * Assists with sorting, find child pages of a parent ID
     *
     * @param int $parentid The parent page ID to find the children of
     * @param course_format_flexpage_model_page[] $childpages Potential child pages
     * @return course_format_flexpage_model_page[]
     */
    protected function filter_children($parentid, array &$childpages) {
        $collected = false;
        $return    = array();
        foreach ($childpages as $page) {
            if ($page->get_parentid() == $parentid) {
                $return[$page->get_id()] = $page;

                // Remove from all pages to improve seek times later
                unset($childpages[$page->get_id()]);

                // This will halt seeking after we get all the children
                $collected = true;
            } else if ($collected) {
                // Since $pages is organized by parent,
                // then once we find one, we get them all in a row
                break;
            }
        }
        return $return;
    }

    /**
     * Repairs weight values
     *
     * @param course_format_flexpage_model_page[] $pages These must be sorted by parentid, weight
     * @return void
     */
    protected function repair_page_weights(array $pages) {
        $weight = $parentid = 0;
        foreach ($pages as $page) {
            if ($page->get_parentid() != $parentid) {
                $weight    = 0;
                $parentid  = $page->get_parentid();
            }
            if ($page->get_weight() != $weight) {
                $page->set_weight($weight);
                $this->pagerepo->save_page($page);
            }
            $weight++;
        }
    }

    /**
     * @return course_format_flexpage_model_page
     */
    public function get_current_page() {
        global $COURSE, $USER;

        $this->require_built();

        if (empty($USER->format_flexpage_display)) {
            $USER->format_flexpage_display = array();
        }

        $pageid = optional_param('pageid', 0, PARAM_INT);

        // See if we are requesting a specific page
        if (!empty($pageid)) {
            try {
                $page = $this->get_page($pageid);
                $USER->format_flexpage_display[$page->get_courseid()] = $page->get_id();
                return $page;
            } catch (Exception $e) {
                // Continue looking for a page
            }
        }

        // See if we know the last page the user was on
        if (!empty($USER->format_flexpage_display[$COURSE->id])) {
            try {
                return $this->get_page($USER->format_flexpage_display[$COURSE->id]);
            } catch (Exception $e) {
                // Continue looking for a page
            }
        }

        // Set to zero, AKA default page
        $USER->format_flexpage_display[$COURSE->id] = 0;

        // Everything failed, so return first page
        return $this->get_first_available_page();
    }

    /**
     * @return course_format_flexpage_model_page
     */
    public function get_first_available_page() {
        $this->require_built();

        // First, try to find one that is actually available
        foreach ($this->get_pages() as $page) {
            if ($this->is_page_available($page->get_id())) {
                return $page;
            }
        }

        // OK, weird, just return first
        foreach ($this->get_pages() as $page) {
            return $page;
        }
    }

    public function is_page_available($pageid) {
        $parents = $this->get_page_parents($pageid, true);
        foreach ($parents as $parent) {
            if ($parent->is_available() !== true) {
                return false;
            }
        }
        return true;
    }

    public function get_page_depth(course_format_flexpage_model_page $page) {
        $this->require_built();
        return count($this->get_page_parents($page->get_id()));
    }

    public function is_child_page(course_format_flexpage_model_page $parent, course_format_flexpage_model_page $child) {
        $this->require_built();

        while ($parent->get_id() != $child->get_parentid() and $child->get_parentid() > 0) {
            $child = $this->get_page($child->get_parentid());
        }
        if ($parent->get_id() == $child->get_parentid()) {
            return true;
        }
        return false;
    }

    // @todo add visibility checks?
    public function get_next_page(course_format_flexpage_model_page $page) {
        $found = false;
        foreach ($this->get_pages() as $nextpage) {
            if ($nextpage->get_id() == $page->get_id()) {
                $found = true;
            } else if ($found) {
                return $nextpage;
            }
        }
        return false;
    }

    // @todo add visibility checks?
    public function get_previous_page(course_format_flexpage_model_page $page) {
        $previouspage = false;
        foreach ($this->get_pages() as $apage) {
            if ($apage->get_id() == $page->get_id()) {
                return $previouspage;
            }
            $previouspage = $apage;
        }
        return false;
    }
}