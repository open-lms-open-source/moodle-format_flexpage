<?php
/**
 * @see course_format_flexpage_lib_box_abstract
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/box/abstract.php');

/**
 * @see course_format_flexpage_lib_box_cell
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/box/cell.php');

/**
 * A box row that can contain cells
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_lib_box_row extends course_format_flexpage_lib_box_abstract {
    /**
     * @var course_format_flexpage_lib_box_cell[]
     */
    protected $cells = array();

    /**
     * Get the box's unique class name
     *
     * @return string
     */
    protected function get_classname() {
        return 'format_flexpage_row';
    }

    /**
     * @return array|course_format_flexpage_lib_box_cell[]
     */
    public function get_cells() {
        return $this->cells;
    }

    /**
     * @param course_format_flexpage_lib_box_cell $cell
     * @return course_format_flexpage_lib_box_row
     */
    public function add_cell(course_format_flexpage_lib_box_cell $cell) {
        $this->cells[] = $cell;
        return $this;
    }

    /**
     * Sort of like a cell factory
     *
     * @param string $contents
     * @param array $attributes
     * @return course_format_flexpage_lib_box_row
     */
    public function add_new_cell($contents, array $attributes = array()) {
        return $this->add_cell(new course_format_flexpage_lib_box_cell($contents, $attributes));
    }
}