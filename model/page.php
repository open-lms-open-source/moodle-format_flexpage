<?php

require_once($CFG->libdir.'/conditionlib.php');

class course_format_flexpage_model_page {
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

    const MOVE_BEFORE = 'before';
    const MOVE_AFTER = 'after';
    const MOVE_CHILD = 'child';

    protected $id;
    protected $courseid;
    protected $name;
    protected $altname = null;
    protected $display = 0;
    protected $parentid = 0;
    protected $weight = 0;
    protected $template = 0;
    protected $navigation = 0;
    protected $releasecode = null;
    protected $availablefrom = 0;
    protected $availableuntil = 0;
    protected $showavailability = 1; // CONDITION_STUDENTVIEW_SHOW
    protected $regionwidths = array();

    /**
     * @var condition_info_controller
     */
    protected $conditions;

    public function __construct($options = array()) {
        $this->set_options($options);
    }

    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        if (!empty($this->id)) {
            throw new coding_exception('Cannot re-assign page ID');
        }
        $this->id = $id;
        return $this;
    }

    public function get_courseid() {
        return $this->courseid;
    }

    public function set_courseid($id) {
        $this->courseid = $id;
        return $this;
    }

    public function get_name() {
        return $this->name;
    }

    public function set_name($name) {
        $this->name = $name;
        return $this;
    }

    public function get_altname() {
        return $this->altname;
    }

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

    public function get_display() {
        return $this->display;
    }

    public function set_display($display) {
        if (!array_key_exists($display, self::get_display_options())) {
            throw new coding_exception("Try to set unknown display value: $display");
        }
        $this->display = $display;
        return $this;
    }

    public function get_parentid() {
        return $this->parentid;
    }

    public function set_parentid($id) {
        $this->parentid = $id;
        return $this;
    }

    public function get_weight() {
        return $this->weight;
    }

    public function set_weight($weight) {
        if ($weight < 0) {
            throw new coding_exception("Page weight must be zero or more: $weight");
        }
        $this->weight = $weight;
        return $this;
    }

    public function get_template() {
        return $this->template;
    }

    public function get_navigation() {
        return $this->navigation;
    }

    public function set_navigation($navigation) {
        if (!array_key_exists($navigation, self::get_navigation_options())) {
            throw new coding_exception("Try to set unknown navigation value: $navigation");
        }
        $this->navigation = $navigation;
        return $this;
    }

    public function get_availablefrom() {
        return $this->availablefrom;
    }

    public function set_availablefrom($time) {
        $this->availablefrom = (int) $time;
        return $this;
    }

    public function get_availableuntil() {
        return $this->availableuntil;
    }

    public function set_availableuntil($time) {
        $this->availableuntil = (int) $time;
        return $this;
    }

    public function get_releasecode() {
        return $this->releasecode;
    }

    public function set_releasecode($code) {
        if ($code === '') {
            $code = null;
        }
        $this->releasecode = $code;
        return $this;
    }

    public function get_showavailability() {
        return $this->showavailability;
    }

    /**
     * Get condition information
     *
     * @return condition_info_controller
     */
    public function get_conditions() {
        return $this->conditions;
    }

    public function get_region_widths() {
        return $this->regionwidths;
    }

    public function set_region_widths(array $widths) {
        $this->regionwidths = $widths;
        return $this;
    }

    public static function get_display_options() {
        return array(
            self::DISPLAY_HIDDEN       => get_string('displayhidden', 'format_flexpage'),
            self::DISPLAY_VISIBLE      => get_string('displayvisible', 'format_flexpage'),
            self::DISPLAY_VISIBLE_MENU => get_string('displayvisiblemenu', 'format_flexpage'),
        );
    }

    public static function get_navigation_options() {
        return array(
            self::NAV_NONE => get_string('navnone', 'format_flexpage'),
            self::NAV_PREV => get_string('navprev', 'format_flexpage'),
            self::NAV_NEXT => get_string('navnext', 'format_flexpage'),
            self::NAV_BOTH => get_string('navboth', 'format_flexpage'),
        );
    }

    public static function get_move_options() {
        return array(
            self::MOVE_CHILD  => get_string('movechild', 'format_flexpage'),
            self::MOVE_BEFORE => get_string('movebefore', 'format_flexpage'),
            self::MOVE_AFTER  => get_string('moveafter', 'format_flexpage'),
        );
    }

    public function set_options($options) {
        foreach ($options as $name => $value) {
            $method = "set_$name";
            if (method_exists($this, $method)) {
                $this->$method($value);
            } else {
                if (!property_exists($this, $name)) {
                    throw new coding_exception("Property does not exist: $name");
                }
                $this->$name = $value;
            }
        }
        return $this;
    }

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
        // @todo Don't make an instance if conditions are empty? Would reduce cache size...
        $this->conditions = new condition_info_controller($conditions);
    }

    public function is_available($inmenu = false) {
        // This method should take into consideration the following:
        //      1. The user's capabilities
        //      2. Page display settings (in or not in a menu)
        //      3. Page availability conditions (parent pages too?)
    }
}