<?php
/**
 * @see course_format_flexpage_lib_box_abstract
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/box/abstract.php');

/**
 * A box cell
 */
class course_format_flexpage_lib_box_cell extends course_format_flexpage_lib_box_abstract {
    /**
     * @var string
     */
    protected $contents = '';

    /**
     * @param string $contents
     * @param array $attributes
     */
    public function __construct($contents = '', array $attributes = array()) {
        $this->set_contents($contents);
        parent::__construct($attributes);
    }

    /**
     * Get the box's unique class name
     *
     * @return string
     */
    protected function get_classname() {
        return 'format_flexpage_cell';
    }

    /**
     * @return string
     */
    public function get_contents() {
        return $this->contents;
    }

    /**
     * @param string $contents
     * @return course_format_flexpage_lib_box_cell
     */
    public function set_contents($contents) {
        $this->contents = $contents;
        return $this;
    }

    /**
     * Append more content to current contents
     *
     * @param string $morecontents
     * @return course_format_flexpage_lib_box_cell
     */
    public function append_contents($morecontents) {
        $this->contents .= $morecontents;
        return $this;
    }
}