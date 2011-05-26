<?php

require_once($CFG->libdir.'/conditionlib.php');

/**
 * @see course_format_flexpage_model_abstract
 */
require_once($CFG->dirroot.'/course/format/flexpage/model/abstract.php');

/**
 * Flexpage Model Page
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_model_page extends course_format_flexpage_model_abstract {
    /**
     * Page is hidden
     */
    const DISPLAY_HIDDEN = 0;

    /**
     * Publish the page
     */
    const DISPLAY_VISIBLE = 1;

    /**
     * Show page in menus
     */
    const DISPLAY_VISIBLE_MENU = 2;

    /**
     * Display no navigation
     */
    const NAV_NONE = 0;

    /**
     * Display next navigation
     */
    const NAV_NEXT = 1;

    /**
     * Display previous navigation
     */
    const NAV_PREV = 2;

    /**
     * Display both previous and next navigation
     */
    const NAV_BOTH = 3;

    /**
     * Move action: before
     */
    const MOVE_BEFORE = 'before';

    /**
     * Move action: after
     */
    const MOVE_AFTER = 'after';

    /**
     * Move action: child
     */
    const MOVE_CHILD = 'child';

    /**
     * @var int
     */
    protected $courseid;

    /**
     * @var string
     */
    protected $name;

    /**
     * The page's alternative name, generally used for display
     * in menus.
     *
     * @var null|string
     */
    protected $altname = null;

    /**
     * Set to one of the DISPLAY_XXX constants
     *
     * @var int
     */
    protected $display = 0;

    /**
     * The page's parent page ID
     *
     * @var int
     */
    protected $parentid = 0;

    /**
     * The page's sort weight
     *
     * @var int
     */
    protected $weight = 0;

    /**
     * Set to one of the NAV_XXX constants
     *
     * @var int
     */
    protected $navigation = 0;

    /**
     * Conditional release: release code
     *
     * @var null|string
     */
    protected $releasecode = null;

    /**
     * Conditional release: available after this time
     *
     * @var int
     */
    protected $availablefrom = 0;

    /**
     * Conditional release: available until this time
     *
     * @var int
     */
    protected $availableuntil = 0;

    /**
     * Determine if we show availability information or not
     *
     * @var int
     */
    protected $showavailability;

    /**
     * Page region widths
     *
     * @var array
     */
    protected $regionwidths = array();

    /**
     * @var condition_info_controller
     */
    protected $conditions = null;

    public function __construct($options = array()) {
        $this->showavailability = CONDITION_STUDENTVIEW_SHOW;
        $this->set_options($options);
    }

    /**
     * @throws coding_exception
     * @param int|null $id
     * @return course_format_flexpage_model_page
     */
    public function set_id($id) {
        if (!empty($this->id) and !is_null($id)) {
            throw new coding_exception('Cannot re-assign page ID');
        }
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function get_courseid() {
        return $this->courseid;
    }

    /**
     * @param int $id
     * @return course_format_flexpage_model_page
     */
    public function set_courseid($id) {
        $this->courseid = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return course_format_flexpage_model_page
     */
    public function set_name($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null|string
     */
    public function get_altname() {
        return $this->altname;
    }

    /**
     * @param string|null $name
     * @return course_format_flexpage_model_page
     */
    public function set_altname($name) {
        if (empty($name)) {
            $this->altname = null;
        } else {
            $this->altname = $name;
        }
        return $this;
    }

    /**
     * Gets the name to be displayed to the end user
     *
     * @return string
     */
    public function get_display_name() {
        $name = $this->get_altname();
        if (empty($name)) {
            $name = $this->get_name();
        }
        return $name;
    }

    /**
     * @return int
     */
    public function get_display() {
        return $this->display;
    }

    /**
     * @throws coding_exception
     * @param int $display  One of the DISPLAY_XXX constants
     * @return course_format_flexpage_model_page
     */
    public function set_display($display) {
        if (!array_key_exists($display, self::get_display_options())) {
            throw new coding_exception("Try to set unknown display value: $display");
        }
        $this->display = $display;
        return $this;
    }

    /**
     * @return int
     */
    public function get_parentid() {
        return $this->parentid;
    }

    /**
     * @param  $id
     * @return course_format_flexpage_model_page
     */
    public function set_parentid($id) {
        $this->parentid = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function get_weight() {
        return $this->weight;
    }

    /**
     * @throws coding_exception
     * @param int $weight Must be zero or more
     * @return course_format_flexpage_model_page
     */
    public function set_weight($weight) {
        if ($weight < 0) {
            throw new coding_exception("Page weight must be zero or more: $weight");
        }
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return int
     */
    public function get_navigation() {
        return $this->navigation;
    }

    /**
     * @throws coding_exception
     * @param int $navigation Navigation constant
     * @return course_format_flexpage_model_page
     */
    public function set_navigation($navigation) {
        if (!array_key_exists($navigation, self::get_navigation_options())) {
            throw new coding_exception("Try to set unknown navigation value: $navigation");
        }
        $this->navigation = $navigation;
        return $this;
    }

    /**
     * Determines if the page is set to show next navigation
     *
     * @return bool
     */
    public function has_navigation_next() {
        $nav = $this->get_navigation();
        if ($nav == self::NAV_NEXT or $nav == self::NAV_BOTH) {
            return true;
        }
        return false;
    }

    /**
     * Determines if the page is set to show previous navigation
     *
     * @return bool
     */
    public function has_navigation_previous() {
        $nav = $this->get_navigation();
        if ($nav == self::NAV_PREV or $nav == self::NAV_BOTH) {
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function get_availablefrom() {
        return $this->availablefrom;
    }

    /**
     * @param int $time
     * @return course_format_flexpage_model_page
     */
    public function set_availablefrom($time) {
        $this->availablefrom = (int) $time;
        return $this;
    }

    /**
     * @return int
     */
    public function get_availableuntil() {
        return $this->availableuntil;
    }

    /**
     * @param int $time
     * @return course_format_flexpage_model_page
     */
    public function set_availableuntil($time) {
        $this->availableuntil = (int) $time;
        return $this;
    }

    /**
     * @return null|string
     */
    public function get_releasecode() {
        return $this->releasecode;
    }

    /**
     * @param string|null $code
     * @return course_format_flexpage_model_page
     */
    public function set_releasecode($code) {
        if ($code === '') {
            $code = null;
        }
        $this->releasecode = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function get_showavailability() {
        return $this->showavailability;
    }

    /**
     * Get condition information
     *
     * @return condition_info_controller|null
     */
    public function get_conditions() {
        return $this->conditions;
    }

    /**
     * @return array
     */
    public function get_region_widths() {
        return $this->regionwidths;
    }

    /**
     * @param array $widths
     * @return course_format_flexpage_model_page
     */
    public function set_region_widths(array $widths) {
        $this->regionwidths = $widths;
        return $this;
    }

    /**
     * Get a URL to view this page
     *
     * @param array $extraparams
     * @return moodle_url
     */
    public function get_url($extraparams = array()) {
        return new moodle_url('/course/view.php', array_merge(
            array('id' => $this->get_courseid(), 'pageid' => $this->get_id()),
            $extraparams
        ));
    }

    /**
     * Set page conditions
     *
     * @param array $conditions
     * @return void
     */
    public function set_conditions(array $conditions) {
        if (!is_null($this->releasecode)) {
            $conditions[] = new condition_releasecode($this->releasecode);
        }
        if (!empty($this->availablefrom) or !empty($this->availableuntil)) {
            $conditions[] = new condition_daterange(
                $this->availablefrom,
                $this->availableuntil
            );
        }
        if (!empty($conditions)) {
            $this->conditions = new condition_info_controller($conditions);
        } else {
            $this->conditions = null;
        }
    }

    /**
     * This method determines if the page is available to the user or not.
     * If the page should be shown with availability information, that information
     * string will be returned.  Otherwise, boolean.
     *
     * WARNING: does not take into account parent pages or menu display
     *
     * @return bool|string
     */
    public function is_available(course_modinfo $modinfo = null) {
        // #1: If the user has manage pages cap, then it's available to them
        if (has_capability('format/flexpage:managepages', get_context_instance(CONTEXT_COURSE, $this->get_courseid()))) {
            return true;
        }

        // #2: If the page is hidden, then not available
        if ($this->get_display() == self::DISPLAY_HIDDEN) {
            return false;
        }
        if (!is_null($this->get_conditions())) {
            $this->process_conditions($modinfo);

            // #3: Based on conditions, it is available to the user?  If not, see if we still show it...
            if (!$this->conditions->get_user_available()) {
                $info = $this->conditions->get_user_available_info();

                // #4: Not available, but if we have info and we are to show it, return it
                if (!empty($info) and $this->get_showavailability() > 0) {
                    return $info;
                }
                // #5: Not available and no info to show
                return false;
            }
        }
        return true;
    }

    /**
     * Get available info
     *
     * @param course_modinfo|null $modinfo
     * @return string
     */
    public function get_available_info(course_modinfo $modinfo = null) {
        if (is_null($this->get_conditions())) {
            return '';
        }
        $this->process_conditions($modinfo);
        return $this->conditions->get_user_available_info();
    }

    /**
     * Process conditions
     *
     * @param course_modinfo|null $modinfo
     * @return course_format_flexpage_model_page
     */
    public function process_conditions(course_modinfo $modinfo = null) {
        global $DB, $COURSE;

        if (!is_null($this->get_conditions()) and !$this->conditions->get_processed()) {
            if (!$modinfo instanceof course_modinfo) {
                if ($COURSE->id != $this->get_courseid()) {
                    $course = $DB->get_record('course', array('id' => $this->get_courseid()), '*', MUST_EXIST);
                } else {
                    $course = $COURSE;
                }
                $modinfo = get_fast_modinfo($course);
            }
            $this->conditions->process_conditions($modinfo, true);
        }
        return $this;
    }

    /**
     * Get page display options
     *
     * @static
     * @return array
     */
    public static function get_display_options() {
        return array(
            self::DISPLAY_HIDDEN       => get_string('displayhidden', 'format_flexpage'),
            self::DISPLAY_VISIBLE      => get_string('displayvisible', 'format_flexpage'),
            self::DISPLAY_VISIBLE_MENU => get_string('displayvisiblemenu', 'format_flexpage'),
        );
    }

    /**
     * Get page navigation options
     *
     * @static
     * @return array
     */
    public static function get_navigation_options() {
        return array(
            self::NAV_NONE => get_string('navnone', 'format_flexpage'),
            self::NAV_PREV => get_string('navprev', 'format_flexpage'),
            self::NAV_NEXT => get_string('navnext', 'format_flexpage'),
            self::NAV_BOTH => get_string('navboth', 'format_flexpage'),
        );
    }

    /**
     * Get page move options
     *
     * @static
     * @return array
     */
    public static function get_move_options() {
        return array(
            self::MOVE_CHILD  => get_string('movechild', 'format_flexpage'),
            self::MOVE_BEFORE => get_string('movebefore', 'format_flexpage'),
            self::MOVE_AFTER  => get_string('moveafter', 'format_flexpage'),
        );
    }
}