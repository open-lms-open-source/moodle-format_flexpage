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
 * @copyright Copyright (c) 2009 Blackboard Inc. (http://www.blackboard.com)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @package format_flexpage
 * @author Mark Nielsen
 */

/**
 * @see course_format_flexpage_repository_page
 */
require_once(dirname(__DIR__).'/repository/page.php');

/**
 * @see course_format_flexpage_repository_condition
 */
require_once(dirname(__DIR__).'/repository/condition.php');

/**
 * @see course_format_flexpage_model_abstract
 */
require_once(dirname(__DIR__).'/model/abstract.php');

/**
 * @see course_format_flexpage_lib_condition
 */
require_once(dirname(__DIR__).'/lib/condition.php');

/**
 * Flexpage Model Cache
 *
 * This class caches page information for rapid access.
 *
 * To use, @see format_flexpage_cache
 *
 * @author Mark Nielsen
 * @package format_flexpage
 * @todo Not sure if this makes sense as a model anymore, should probably be refactored to a lib class
 */
class course_format_flexpage_model_cache extends course_format_flexpage_model_abstract {
    /**
     * Cache has not been built
     */
    const BUILD_CODE_NOT = 0;

    /**
     * Cache was built with just pages
     */
    const BUILD_CODE_BASIC = 1;

    /**
     * Cache was built with pages and availability conditions
     */
    const BUILD_CODE_AVAILABLE = 2;

    /**
     * Cache was built with pages, availability conditions and completion conditions
     */
    const BUILD_CODE_AVAILABLE_COMPLETE = 3;

    /**
     * @var int
     */
    protected $courseid = null;

    /**
     * @var course_format_flexpage_model_page[]
     */
    protected $pages;

    /**
     * This code is used to determine how the cache was built
     *
     * @var int
     */
    protected $buildcode = 0;

    /**
     * @var int
     */
    protected $timemodified;

    /**
     * @var course_format_flexpage_repository_page
     */
    protected $pagerepo;

    /**
     * @var course_format_flexpage_repository_condition
     */
    protected $condrepo;

    /**
     * Page availability cache
     *
     * @var array
     */
    protected $pageavailable = array();

    public function __construct() {
        $this->set_repository_page(new course_format_flexpage_repository_page())
             ->set_repository_condition(new course_format_flexpage_repository_condition())
             ->set_timemodified();
    }

    /**
     * @return int|null
     */
    public function get_courseid() {
        return $this->courseid;
    }

    /**
     * @param int $id
     * @return course_format_flexpage_model_cache
     */
    public function set_courseid($id) {
        $this->courseid = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function get_buildcode() {
        return $this->buildcode;
    }

    /**
     * Generate a new build code
     *
     * This may not represent how the cache is CURRENTLY built.
     *
     * @return int
     */
    public function get_new_buildcode() {
        global $CFG, $DB, $COURSE;

        if (!empty($CFG->enableavailability)) {
            if ($COURSE->id == $this->get_courseid()) {
                $course = $COURSE;
            } else {
                $course = $DB->get_record('course', array('id' => $this->get_courseid()), '*', MUST_EXIST);
            }
            $completion = new completion_info($course);
            if ($completion->is_enabled()) {
                return self::BUILD_CODE_AVAILABLE_COMPLETE;
            }
            return self::BUILD_CODE_AVAILABLE;
        }
        return self::BUILD_CODE_BASIC;
    }

    /**
     * Must set to one of the BUILD_CODE_XXX class constants
     *
     * @param int $code
     * @return course_format_flexpage_model_cache
     */
    public function set_buildcode($code) {
        $this->buildcode = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function get_timemodified() {
        return $this->timemodified;
    }

    /**
     * @param null|int $time Unix timestamp or null
     * @return course_format_flexpage_model_cache
     */
    public function set_timemodified($time = null) {
        if (is_null($time)) {
            $time = time();
        }
        $this->timemodified = $time;
        return $this;
    }

    /**
     * @param course_format_flexpage_repository_page $pagerepo
     * @return course_format_flexpage_model_cache
     */
    public function set_repository_page(course_format_flexpage_repository_page $pagerepo) {
        $this->pagerepo = $pagerepo;
        return $this;
    }

    /**
     * @param course_format_flexpage_repository_condition $condrepo
     * @return course_format_flexpage_model_cache
     */
    public function set_repository_condition(course_format_flexpage_repository_condition $condrepo) {
        $this->condrepo = $condrepo;
        return $this;
    }

    /**
     * @throws moodle_exception
     * @param int $pageid
     * @return course_format_flexpage_model_page
     */
    public function get_page($pageid) {
        $this->require_built();
        if (!array_key_exists($pageid, $this->pages)) {
            throw new moodle_exception('pagenotfound', 'format_flexpage', '', $pageid);
        }
        return $this->pages[$pageid];
    }

    /**
     * @return course_format_flexpage_model_page[]
     */
    public function get_pages() {
        $this->require_built();
        return $this->pages;
    }

    /**
     * @param course_format_flexpage_model_page[]|null $pages
     * @return course_format_flexpage_model_cache
     */
    public function set_pages(array $pages = null) {
        if (is_array($pages)) {
            unset($this->pages); // Might help with garbage collection?
        }
        $this->pages = $pages;
        return $this;
    }

    /**
     * Get page parents
     *
     * @param course_format_flexpage_model_page $page The page to find the parents of
     * @param bool $includechild Include the passed page in the return
     * @return course_format_flexpage_model_page[]
     */
    public function get_page_parents(course_format_flexpage_model_page $page, $includechild = false) {
        $this->require_built();
        $parents = array();

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

    /**
     * Get's the currently active page
     *
     * Checks include:
     *  - pageid request parameter
     *  - Session
     *  - Last, first available page (may not actually be available though ha)
     *
     * @return course_format_flexpage_model_page
     */
    public function get_current_page() {
        global $USER;

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
        if (!empty($USER->format_flexpage_display[$this->get_courseid()])) {
            try {
                return $this->get_page($USER->format_flexpage_display[$this->get_courseid()]);
            } catch (Exception $e) {
                // Continue looking for a page
            }
        }

        // Unset, AKA default page
        unset($USER->format_flexpage_display[$this->get_courseid()]);

        // Everything failed, so return first page
        return $this->get_first_available_page();
    }


    /**
     * Get the first page
     *
     * @return course_format_flexpage_model_page
     */
    public function get_first_page() {
        foreach ($this->get_pages() as $page) {
            return $page;
        }
    }

    /**
     * Find the first available page - if no pages are
     * available, then the first page in the hierarchy is returned
     *
     * @return course_format_flexpage_model_page
     */
    public function get_first_available_page() {
        $this->require_built();

        // First, try to find one that is actually available
        foreach ($this->get_pages() as $page) {
            if ($this->is_page_available($page) === true) {
                return $page;
            }
        }

        // OK, weird, just return first
        return $this->get_first_page();
    }

    /**
     * Get the next page if it's available
     *
     * @param course_format_flexpage_model_page $page
     * @param bool $ignoremenu Ignore menu display settings
     * @return bool|course_format_flexpage_model_page
     */
    public function get_next_page(course_format_flexpage_model_page $page, $ignoremenu = true) {
        $found = false;
        foreach ($this->get_pages() as $nextpage) {
            if ($nextpage->get_id() == $page->get_id()) {
                $found = true;
            } else if ($found) {
                if ($this->is_page_available($nextpage) !== true) {
                    continue;
                }
                if (!$ignoremenu and !$this->is_page_in_menu($nextpage)) {
                    continue;
                }
                return $nextpage;
            }
        }
        return false;
    }

    /**
     * Get the previous page if it's available
     *
     * @param course_format_flexpage_model_page $page
     * @param bool $ignoremenu Ignore menu display settings
     * @return bool|course_format_flexpage_model_page
     */
    public function get_previous_page(course_format_flexpage_model_page $page, $ignoremenu = true) {
        $previouspage = false;
        foreach ($this->get_pages() as $apage) {
            if ($apage->get_id() == $page->get_id()) {
                return $previouspage;
            }
            if ($this->is_page_available($apage) !== true) {
                continue;
            }
            if (!$ignoremenu and !$this->is_page_in_menu($apage)) {
                continue;
            }
            $previouspage = $apage;
        }
        return false;
    }

    /**
     * Determine if the page is available - checks parents!
     *
     * @param course_format_flexpage_model_page $page
     * @param course_modinfo $modinfo
     * @return bool|string
     */
    public function is_page_available(course_format_flexpage_model_page $page, course_modinfo $modinfo = null) {
        $available = $this->get_cached_page_available($page, $modinfo);

        if ($available === true) {
            // The page in question is available, now check parents
            $parents = $this->get_page_parents($page);
            foreach ($parents as $parent) {
                if ($this->get_cached_page_available($parent, $modinfo) !== true) {
                    return false;
                }
            }
        }
        return $available;
    }

    /**
     * Determine if the page is available in menus - checks parents!
     *
     * @param course_format_flexpage_model_page $page
     * @return bool
     */
    public function is_page_in_menu(course_format_flexpage_model_page $page) {
        $parents = $this->get_page_parents($page, true);
        foreach ($parents as $parent) {
            if ($parent->get_display() != course_format_flexpage_model_page::DISPLAY_VISIBLE_MENU) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the hierarchy depth, starts at zero
     *
     * @param course_format_flexpage_model_page $page
     * @return int
     */
    public function get_page_depth(course_format_flexpage_model_page $page) {
        $this->require_built();
        return count($this->get_page_parents($page));
    }

    /**
     * Determine if a page is a child of another
     *
     * @param course_format_flexpage_model_page $parent
     * @param course_format_flexpage_model_page $child
     * @return bool
     */
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

    /**
     * Determine of the cache has been built yet
     *
     * @return bool
     */
    public function has_been_built() {
        return ($this->get_buildcode() != self::BUILD_CODE_NOT);
    }

    /**
     * Require that the cache has been built, throws an exception when it isn't
     *
     * @throws coding_exception
     * @return void
     */
    public function require_built() {
        if (!$this->has_been_built()) {
            throw new coding_exception('Cache must be built');
        }
    }

    /**
     * Build the cache
     *
     * @throws coding_exception
     * @return void
     */
    public function build() {
        global $CFG;

        if (is_null($this->get_courseid())) {
            throw new coding_exception('Must set course ID before building cache');
        }

        // Ensure that we have at least one page
        $this->pagerepo->create_default_page($this->get_courseid());

        // Fetch our pages and conditions
        $pages = $this->pagerepo->get_pages(array('courseid' => $this->get_courseid()), 'parentid, weight');

        // Make sure our weights are all in order
        $this->repair_page_weights($pages);

        if (!empty($CFG->enableavailability)) {
            // Associate conditions to pages
            $conditions = $this->condrepo->get_course_conditions($this->get_courseid());
            foreach ($pages as $page) {
                if (array_key_exists($page->get_id(), $conditions)) {
                    $pageconditions = $conditions[$page->get_id()];
                } else {
                    $pageconditions = array();
                }
                $page->set_conditions($pageconditions);
            }
        }

        // Sort all of the pages
        $pages = $this->sort_pages($pages);

        // Update instance with new info
        $this->set_pages($pages)
             ->set_timemodified()
             ->set_buildcode($this->get_new_buildcode());

        unset($pages);
    }

    /**
     * Clear the cache
     *
     * @return void
     */
    public function clear() {
        $this->set_pages(null)
             ->set_buildcode(self::BUILD_CODE_NOT)
             ->set_timemodified();
    }

    /**
     * Sorts pages
     *
     * @param course_format_flexpage_model_page[] $pages Parent pages to process
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
     * Get the cached page available value
     *
     * @param course_format_flexpage_model_page $page
     * @param course_modinfo $modinfo
     * @return bool|string
     */
    protected function get_cached_page_available(course_format_flexpage_model_page $page, course_modinfo $modinfo = null) {
        if (!array_key_exists($page->get_id(), $this->pageavailable)) {
            $this->pageavailable[$page->get_id()] = $this->get_page_available($page, $modinfo);
        }
        return $this->pageavailable[$page->get_id()];
    }

    /**
     * Determine if a singe page is available
     *
     * @param course_format_flexpage_model_page $page
     * @param course_modinfo $modinfo
     * @return bool|string
     */
    protected function get_page_available(course_format_flexpage_model_page $page, course_modinfo $modinfo = null) {
        global $CFG;

        $userid = null;
        if (!is_null($modinfo)) {
            $userid = $modinfo->get_user_id();
        }

        // #1: If the user has manage pages cap, then it's available to them
        if (has_capability('format/flexpage:managepages', context_course::instance($page->get_courseid()), $userid)) {
            return true;
        }

        // #2: If the page is hidden, then not available
        if ($page->get_display() == course_format_flexpage_model_page::DISPLAY_HIDDEN) {
            return false;
        }
        if (!empty($CFG->enableavailability)) {
            if (is_null($userid)) {
                $userid = 0; // Different default for is_available
            }
            $conditionlib = new course_format_flexpage_lib_condition($page);
            $available    = $conditionlib->is_available($info, true, $userid, $modinfo);

            // #3: Based on conditions, it is available to the user?  If not, see if we still show it...
            if (!$available) {
                // #4: Not available, but if we have info, return it
                if (!empty($info)) {
                    return $info;
                }
                // #5: Not available and no info to show
                return false;
            }
        }
        return true;
    }
}
