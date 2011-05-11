<?php
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
        global $CFG, $DB, $COURSE;

        require_once($CFG->dirroot.'/course/lib.php');

        if (is_null($course)) {
            $course = $COURSE;
        }
        $urlbase       = "/course/mod.php?id=$course->id&section=0&sesskey=".sesskey().'&add=';
        $stractivities = get_string('activities');
        $strresources  = get_string('resources');
        $options       = array(
            $stractivities => array(),
            $strresources  => array(),
        );

        $mods = $DB->get_records('modules');
        foreach ($mods as $mod) {
            if (!course_allowed_module($course, $mod->name)) {
                continue;
            }

            try {
                if ($types = self::callback($mod->name, 'get_types')) {
                    $menu = array();
                    $atype = null;
                    $groupname = null;
                    foreach($types as $type) {
                        if ($type->typestr === '--') {
                            continue;
                        }
                        if (strpos($type->typestr, '--') === 0) {
                            $groupname = str_replace('--', '', $type->typestr);
                            continue;
                        }
                        $type->type = str_replace('&amp;', '&', $type->type);
                        if ($type->modclass == MOD_CLASS_RESOURCE) {
                            $atype = MOD_CLASS_RESOURCE;
                        }
                        $menu[$urlbase.$type->type] = array(
                            'module' => $mod->name,
                            'label'  => $type->typestr
                        );
                    }
                    if (is_null($groupname)) {
                        if ($atype == MOD_CLASS_RESOURCE) {
                            $groupname = $strresources;
                        } else {
                            $groupname = $stractivities;
                        }
                    }
                    if (empty($options[$groupname])) {
                        $options[$groupname] = $menu;
                    } else {
                        $options[$groupname] = array_merge($options[$groupname], $menu);
                    }
                }
            } catch (coding_exception $e) {
                $archetype = plugin_supports('mod', $mod->name, FEATURE_MOD_ARCHETYPE, MOD_ARCHETYPE_OTHER);
                if ($archetype == MOD_ARCHETYPE_RESOURCE) {
                    $groupname = $strresources;
                } else {
                    $groupname = $stractivities;
                }
                $options[$groupname][$urlbase.$mod->name] = array(
                    'module' => $mod->name,
                    'label'  => get_string('modulename', $mod->name)
                );
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

    /**
     * Perform a module callback
     *
     * @param string $module Module dir name
     * @param string $function The function name to call
     * @param array $arguments Args to pass to the function
     * @return mixed
     */
    public static function callback($module, $function, $arguments = array()) {
        global $CFG;

        $lib = "$CFG->dirroot/mod/$module/lib.php";

        if (!file_exists($lib)) {
            throw new coding_exception('Module lib file does not exist');
        }
        @require_once($lib);

        $function = "{$module}_$function";

        if (!function_exists($function)) {
            throw new coding_exception("Function does not exist: $function");
        }
        return call_user_func_array($function, $arguments);
    }
}