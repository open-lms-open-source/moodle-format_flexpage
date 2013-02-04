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
 * @copyright Copyright (c) 2009 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @package format_flexpage
 * @author Mark Nielsen
 */

/**
 * Methods for handling random module tasks
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_lib_mod {
    /**
     * Get options for adding activities
     *
     * @static
     * @param object|null $course Course object
     * @return array
     */
    public static function get_add_options($course = null) {
        global $CFG, $COURSE;

        require_once($CFG->dirroot.'/course/lib.php');

        if (is_null($course)) {
            $course = $COURSE;
        }
        $sectionlink   = '&section=0';
        $stractivities = get_string('activities');
        $strresources  = get_string('resources');
        $options       = array(
            $stractivities => array(),
            $strresources  => array(),
        );

        $modnames = get_module_types_names();
        $modules = get_module_metadata($course, $modnames);

        foreach ($modules as $module) {
            if (isset($module->types)) {
                // This module has a subtype
                // NOTE: this is legacy stuff, module subtypes are very strongly discouraged!!
                $subtypes = array();
                foreach ($module->types as $subtype) {
                    $subtypes[$subtype->link.$sectionlink] = array(
                        'module' => $module->name,
                        'label'  => $module->title,
                    );
                }

                // Sort module subtypes into the list
                if (!empty($module->title)) {
                    $options[$module->title] = $subtypes;
                } else {
                    // This grouping does not have a name
                    if ($module->archetype == MOD_CLASS_RESOURCE) {
                        $options[$strresources] = array_merge($options[$strresources], $subtypes);
                    } else {
                        $options[$stractivities] = array_merge($options[$stractivities], $subtypes);
                    }
                }
            } else {
                // This module has no subtypes
                $option = array('module' => $module->name, 'label'  => $module->title);
                if ($module->archetype == MOD_ARCHETYPE_RESOURCE) {
                    $options[$strresources][$module->link.$sectionlink] = $option;
                } else if ($module->archetype === MOD_ARCHETYPE_SYSTEM) {
                    // System modules cannot be added by user, do not add to dropdown
                } else {
                    $options[$stractivities][$module->link.$sectionlink] = $option;
                }
            }
        }
        foreach ($options as $groupname => $activities) {
            uasort($activities, 'course_format_flexpage_lib_mod::sort_options');
            $options[$groupname] = $activities;
        }
        return $options;
    }

    /**
     * Get options for adding existing activities
     *
     * @static
     * @param object|null $course Course object
     * @return array
     */
    public static function get_existing_options($course = null) {
        global $COURSE;

        if (is_null($course)) {
            $course = $COURSE;
        }
        $modinfo = get_fast_modinfo($course);
        $options = array();
        foreach ($modinfo->get_instances() as $module => $instances) {
            $group = array();
            foreach ($instances as $instance) {
                $group[$instance->id] = array(
                    'module' => $instance->modname,
                    'label' => $instance->name,
                );
            }
            uasort($group, 'course_format_flexpage_lib_mod::sort_options');

            $options[get_string('modulenameplural', $module)] = $group;
        }
        ksort($options);

        return $options;
    }

    /**
     * Sorting method, used internally
     *
     * @static
     * @param  $a
     * @param  $b
     * @return int
     */
    public static function sort_options($a, $b) {
        return strnatcasecmp($a['label'], $b['label']);
    }
}