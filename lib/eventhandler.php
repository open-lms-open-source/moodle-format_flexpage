<?php
/**
 * Event Handler Class
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_lib_eventhandler {
    /**
     * Handler for event mod_created
     *
     * When the format is flexpage, we add
     * new activities as a block to the current page.
     *
     * @static
     * @throws Exception
     * @param object $eventdata Event data object
     * @return void
     */
    public static function mod_created($eventdata) {
        global $CFG, $DB, $COURSE, $SESSION;

        if (!empty($SESSION->format_flexpage_mod_region)) {
            $region = $SESSION->format_flexpage_mod_region;
        } else {
            $region = false;
        }
        unset($SESSION->format_flexpage_mod_region);

        // Do cheapest check possible so we don't unnecessarily slow down Moodle
        if ($COURSE->id != $eventdata->courseid) {
            $format = $DB->get_field('course', 'format', array('id' => $eventdata->courseid));
        } else {
            $format = $COURSE->format;
        }
        if ($format === 'flexpage') {

            require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

            try {
                course_format_flexpage_lib_moodlepage::add_activity_block($eventdata->cmid, $region);
            } catch (Exception $e) {
                throw $e; // @todo Remove
            }
        }
    }
}