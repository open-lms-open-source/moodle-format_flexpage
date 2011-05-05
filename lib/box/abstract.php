<?php
/**
 * Abstract representation of a box
 */
abstract class course_format_flexpage_lib_box_abstract implements renderable {
    /**
     * @var array
     */
    protected $attributes;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array()) {
        $this->set_attributes($attributes);
    }

    /**
     * Get the box's unique class name
     *
     * @abstract
     * @return string
     */
    abstract protected function get_classname();

    /**
     * @return array
     */
    public function get_attributes() {
        $attributes = $this->attributes;
        if (!empty($attributes['class'])) {
            $attributes['class'] = $this->get_classname().' '.$attributes['class'];
        } else {
            $attributes['class'] = $this->get_classname();
        }
        return $attributes;
    }

    /**
     * Set and override any existing attributes
     *
     * @param array $attributes
     * @return course_format_flexpage_lib_box_abstract
     */
    public function set_attributes(array $attributes) {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Set and merge with any existing attributes
     *
     * @param array $attributes
     * @return course_format_flexpage_lib_box_abstract
     */
    public function add_attributes(array $attributes) {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }
}