<?php

class course_format_page_lib_hierarchy {
    protected $flat = array();
    protected $nested = array();

    /**
     * @var course_format_page_model_page[]
     */
    protected $pages = array();

    public function __construct($pages) {
        // Build flat/nested arrays
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
}