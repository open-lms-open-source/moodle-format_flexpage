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
 * @copyright Copyright (c) 2009 Blackboard Inc. (http://www.blackboardopenlms.com)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @package format_flexpage
 * @author Mark Nielsen
 */

/**
 * This condition is based on user fields
 *
 * @package format_flexpage
 * @author Mark Nielsen
 */
class course_format_flexpage_model_condition_field {
    /**
     * If string, then standard user field
     * If int, then custom profile field
     *
     * @var string|int
     */
    protected $field;

    /**
     * The field name or label
     *
     * @var string
     */
    protected $fieldname;

    /**
     * This is the custom profile field's
     * short name.  This was added in at a
     * much later date to support the new
     * condition API.
     *
     * @var string
     */
    protected $shortname;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var string
     */
    protected $value;

    function __construct($field, $fieldname, $operator, $value, $shortname = null) {
        $this->field     = $field;
        $this->fieldname = $fieldname;
        $this->operator  = $operator;
        $this->value     = $value;
        $this->shortname = $shortname;

        if (empty($this->fieldname)) {
            $this->fieldname = '!missing';
        }
    }

    /**
     * @return int|string
     */
    public function get_field() {
        return $this->field;
    }

    /**
     * @return string
     */
    public function get_fieldname() {
        return $this->fieldname;
    }

    /**
     * The custom profile field's
     * short name.
     *
     * @return null|string
     */
    public function get_shortname() {
        if ($this->is_custom()) {
            return $this->shortname;
        }
        return null;
    }

    /**
     * @return string
     */
    public function get_operator() {
        return $this->operator;
    }

    /**
     * @return string
     */
    public function get_value() {
        return $this->value;
    }

    /**
     * Determine if this user field is custom or not
     *
     * @return bool
     */
    public function is_custom() {
        return is_numeric($this->get_field());
    }
}
