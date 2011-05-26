<?php
/**
 * @see course_format_flexpage_lib_box_abstract
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/box/abstract.php');

/**
 * @see course_format_flexpage_lib_box_row
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/box/row.php');

/**
 * A box that can contain rows
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_lib_box extends course_format_flexpage_lib_box_abstract {
    /**
     * @var course_format_flexpage_lib_box_row[]
     */
    protected $rows = array();

    /**
     * Get the box's unique class name
     *
     * @return string
     */
    protected function get_classname() {
        return 'format_flexpage_box';
    }

    /**
     * @return array|course_format_flexpage_lib_box_row[]
     */
    public function get_rows() {
        return $this->rows;
    }

    /**
     * @param course_format_flexpage_lib_box_row $row
     * @return course_format_flexpage_lib_box
     */
    public function add_row(course_format_flexpage_lib_box_row $row) {
        $this->rows[] = $row;
        return $this;
    }

    /**
     * @param array $attributes
     * @return course_format_flexpage_lib_box_row Returns the newly created row so you can add cells
     */
    public function add_new_row(array $attributes = array()) {
        $row = new course_format_flexpage_lib_box_row($attributes);
        $this->add_row($row);
        return $row;
    }
}