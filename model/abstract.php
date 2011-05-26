<?php
/**
 * Flexpage Model Abstract
 *
 * Note: not all models have to extend this!
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
abstract class course_format_flexpage_model_abstract {
    /**
     * @var int
     */
    protected $id;

    /**
     * @return int
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return course_format_flexpage_model_abstract
     */
    public function set_id($id) {
        if (!empty($this->id)) {
            throw new coding_exception('Cannot re-assign cache ID');
        }
        $this->id = $id;
        return $this;
    }

    /**
     * Determine if this has an ID or not
     *
     * @return bool
     */
    public function has_id() {
        return !empty($this->id);
    }

    /**
     * Attach this model's ID to another object
     * and return if it was successful or not
     *
     * @param object|array $var
     * @return bool
     */
    public function attach_id($var) {
        if ($this->has_id()) {
            if (is_array($var)) {
                $var['id'] = $this->get_id();
            } else {
                $var->id = $this->get_id();
            }
            return true;
        }
        return false;
    }

    /**
     * A way to bulk set model properties
     *
     * @param array|object $options
     * @return course_format_flexpage_model_abstract
     */
    public function set_options($options) {
        foreach ($options as $name => $value) {
            $method = "set_$name";
            if (method_exists($this, $method)) {
                $this->$method($value);
            } else {
                // Ignore things that are not a property of this model
                if (property_exists($this, $name)) {
                    $this->$name = $value;
                }
            }
        }
        return $this;
    }
}