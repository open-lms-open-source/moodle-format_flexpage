<?php
/**
 * @see course_format_flexpage_repository_page
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/page.php');

/**
 * @see course_format_flexpage_repository_condition
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/condition.php');

class course_format_flexpage_lib_cache implements Serializable {
    protected $courseid;

    protected $built = false;

    /**
     * @var course_format_flexpage_model_page[]
     */
    protected $pages = array();

    /**
     * @var course_format_flexpage_repository_page
     */
    protected $pagerepo;

    /**
     * @var course_format_flexpage_repository_condition
     */
    protected $condrepo;

    /**
     * @var course_format_flexpage_lib_cache
     */
    protected static $instance;

    /**
     * It is most efficient to access the course cache via
     * course_format_flexpage_repository_cache class.
     *
     * @param  $courseid
     */
    public function __construct($courseid) {
        $this->courseid = $courseid;
        $this->init();
    }

    protected function init() {
        $this->pagerepo = new course_format_flexpage_repository_page();
        $this->condrepo = new course_format_flexpage_repository_condition();
    }

    /**
     * String representation of object
     *
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string
     */
    public function serialize() {
        return serialize(array(
            $this->get_courseid(),
            $this->get_pages(),
        ));
    }

    /**
     * Constructs the object
     *
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized The string representation of the object.
     * @return mixed
     */
    public function unserialize($serialized) {
        list($this->courseid, $this->pages) = unserialize($serialized);
        $this->init();
        $this->built = true;
    }

    public function get_courseid() {
        return $this->courseid;
    }

    public function get_page_parents($pageid) {
        $this->require_built();
        // return array of parents of a page
    }

    public function get_pages() {
        $this->require_built();
        return $this->pages;
    }
    public function get_page($pageid) {
        $this->require_built();
        if (!array_key_exists($pageid, $this->pages)) {
            // @todo Better error recovery?
            throw new moodle_exception('pagenotfound', 'format_flexpage', '', $pageid);
        }
        return $this->pages[$pageid];
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

    public function rebuild() {
        // Ensure that we have at least one page
        $this->pagerepo->create_default_page($this->get_courseid());

        // Fetch our pages and conditions
        $this->pages = $this->pagerepo->get_pages($this->get_courseid(), 'parentid, weight');
        $conditions  = $this->condrepo->get_course_conditions($this->get_courseid());

        // Make sure our weights are all in order
        $this->repair_page_weights($this->pages);

        // Associate conditions to pages
        foreach ($this->pages as $page) {
            if (array_key_exists($page->get_id(), $conditions)) {
                $pageconditions = $conditions[$page->get_id()];
            } else {
                $pageconditions = array();
            }
            $page->set_conditions($pageconditions);
        }

        // Sort all of the pages
        $this->pages = $this->sort_pages($this->pages);

        // Flag cache a built
        $this->built = true;
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
        $this->require_built();

        $pageid = optional_param('pageid', 0, PARAM_INT);
        if (!empty($pageid)) {
            // Requesting a specific page
            return $this->get_page($pageid);
        } else {
            // Return the first page
            foreach ($this->get_pages() as $page) {
                return $page;
            }
        }
    }

    public function get_page_depth(course_format_flexpage_model_page $page) {
        $this->require_built();

        $depth = 0;
        while ($page->get_parentid() > 0) {
            $depth++;
            $page = $this->get_page($page->get_parentid());
        }
        return $depth;
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
}