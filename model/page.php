<?php

// @todo implement condition_availability or w/e
class course_format_flexpage_model_page {
    /**
     * Publish the page
     */
    const DISPLAY_PUBLISH = 1;

    /**
     * Show page in theme as top tabs
     *
     * @deprecated
     */
    const DISPLAY_THEME = 2;

    /**
     * Show page in menus
     */
    const DISPLAY_MENU = 4;

    /**
     * Display next button
     */
    const BUTTON_NEXT = 1;

    /**
     * Display previous button
     */
    const BUTTON_PREV = 2;

    /**
     * Display both previous and next buttons
     */
    const BUTTON_BOTH = 3;

    protected $id;
    protected $courseid;
    protected $name;
    protected $altname;
    protected $display;
    protected $parentid;
    protected $weight;
    protected $template;
    protected $showbuttons;
    protected $releasecode;
    protected $availablefrom;
    protected $availableuntil;

    /**
     * @var condition_info_controller
     */
    protected $conditions;

    public function __construct(array $options = array()) {
        $this->set_options($options);
    }

    public function get_id() {
        return $this->id;
    }

    public function get_courseid() {
        return $this->courseid;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_altname() {
        return $this->altname;
    }

    public function get_display() {
        return $this->display;
    }

    public function get_parentid() {
        return $this->parentid;
    }

    public function get_weight() {
        return $this->weight;
    }

    public function get_template() {
        return $this->template;
    }

    public function get_showbuttons() {
        return $this->showbuttons;
    }

    public function get_availablefrom() {
        return $this->availablefrom;
    }

    public function get_availableuntil() {
        return $this->availableuntil;
    }

    public function get_releasecode() {
        return $this->releasecode;
    }

    /**
     * Get condition information
     *
     * @return condition_info_controller
     */
    public function get_conditions() {
        return $this->conditions;
    }

    public function set_options(array $options) {
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
        $this->conditions = new condition_info_controller($conditions);
    }

    public function is_available($inmenu = false) {
        // This method should take into consideration the following:
        //      1. The user's capabilities
        //      2. Page display settings (in or not in a menu)
        //      3. Page availability conditions (parent pages too?)
    }
}