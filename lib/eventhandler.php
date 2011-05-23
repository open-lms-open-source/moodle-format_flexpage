<?php
/**
 * Event Handler Class
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_lib_eventhandler {
    /**
     * Do the fastest check possible to determine if the
     * passed course ID has format flexpage.
     *
     * @static
     * @param int $courseid
     * @return bool
     */
    protected static function is_flexpage_format($courseid) {
        global $DB, $COURSE;

        if ($COURSE->id != $courseid) {
            $format = $DB->get_field('course', 'format', array('id' => $courseid));
        } else {
            $format = $COURSE->format;
        }
        return ($format == 'flexpage');
    }

    /**
     * Handler for event mod_created
     *
     * When the format is flexpage, we add
     * new activities as a block to the current page.
     *
     * @static
     * @param object $eventdata Event data object
     * @return void
     */
    public static function mod_created($eventdata) {
        global $CFG, $SESSION;

        if (!empty($SESSION->format_flexpage_mod_region)) {
            $region = $SESSION->format_flexpage_mod_region;
        } else {
            $region = false;
        }
        unset($SESSION->format_flexpage_mod_region);

        if (self::is_flexpage_format($eventdata->courseid)) {
            require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');
            require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

            try {
                $page = format_flexpage_cache()->get_current_page();
                course_format_flexpage_lib_moodlepage::add_activity_block($page, $eventdata->cmid, $region);
            } catch (Exception $e) {
            }
        }
    }

    /**
     * Handler for event mod_deleted
     *
     * When the format is flexpage, we remove every
     * flexpagemod block that is associated to the
     * deleted activity.
     *
     * @static
     * @param object $eventdata Event data object
     * @return void
     */
    public static function mod_deleted($eventdata) {
        global $CFG;

        if (self::is_flexpage_format($eventdata->courseid)) {
            require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');
            course_format_flexpage_lib_moodlepage::delete_mod_blocks($eventdata->cmid);
        }
    }
}