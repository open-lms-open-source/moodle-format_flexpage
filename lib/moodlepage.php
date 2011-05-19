<?php
/**
 * Handles things that involve moodle_page, theme_config, block_manager, etc
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_lib_moodlepage {
    /**
     * The theme layout we want
     */
    const LAYOUT = 'format_flexpage';

    /**
     * Get theme regions
     *
     * @static
     * @return array
     */
    public static function get_regions() {
        global $PAGE;

        // @todo better validation
        $layout  = $PAGE->theme->layouts[self::LAYOUT];
        $regions = $PAGE->theme->get_all_block_regions();

        $return = array();
        foreach ($layout['regions'] as $region) {
            $return[$region] = $regions[$region];
        }
        return $return;
    }

    /**
     * @return array
     */
    public static function get_region_json_options() {
        $default = self::get_default_region();
        $options = array();
        foreach (self::get_regions() as $region => $name) {
            $options[] = (object) array(
                'value' => $region,
                'label' => $name,
                'checked' => ($region == $default),
            );
        }
        return $options;
    }

    /**
     * Get theme's default region
     *
     * @static
     * @return array
     */
    public static function get_default_region() {
        global $PAGE;

        // @todo better validation
        return $PAGE->theme->layouts[self::LAYOUT]['defaultregion'];
    }

    /**
     * Get the add block options
     *
     * @static
     * @param int $courseid
     * @return array
     */
    public static function get_add_block_options($courseid) {
        $page = self::new_moodle_page($courseid);
        $page->blocks->load_blocks(true);

        $options = array();
        $blocks  = $page->blocks->get_addable_blocks();

        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                if ($block->name == 'flexpagemod') {
                    continue;
                }
                $blockobject = block_instance($block->name);
                if ($blockobject !== false and $blockobject->user_can_addto($page)) {
                    $options[$block->name] = $blockobject->get_title();
                }
            }
            textlib_get_instance()->asort($options);
        }
        return $options;
    }

    /**
     * Add an activity as a block to the current page
     *
     * @static
     * @param int $cmid Add this course module
     * @param string|bool $region The region to add the activity to
     * @return void
     */
    public static function add_activity_block($cmid, $region = false) {
        global $DB, $SESSION;

        $cm = $DB->get_record('course_modules', array('id' => $cmid), 'id, course', MUST_EXIST);

        $SESSION->block_flexpagemod_create_cmids = array($cm->id);

        self::add_block('flexpagemod', $cm->course, $region);
    }

    /**
     * Add a block to the current page
     *
     * @static
     * @param string $blockname The block's name
     * @param int $courseid
     * @param string|bool $region The region to add the block to
     * @return void
     */
    public static function add_block($blockname, $courseid, $region = false) {
        $page = self::new_moodle_page($courseid);
        $page->blocks->load_blocks(true);

        if (!$page->user_can_edit_blocks()) {
            throw new moodle_exception('nopermissions', '', $page->url->out(), get_string('addblock'));
        }
        if (!array_key_exists($blockname, $page->blocks->get_addable_blocks())) {
            throw new moodle_exception('cannotaddthisblocktype', '', $page->url->out(), $blockname);
        }
        if (!empty($region) and $region != $page->blocks->get_default_region() and $page->blocks->is_known_region($region)) {
            // Determine weight...
            $blocks = $page->blocks->get_blocks_for_region($region);
            if (empty($blocks)) {
                $weight = 0;
            } else {
                $block  = end($blocks);
                $weight = $block->instance->weight + 1;
            }
            $page->blocks->add_block($blockname, $region, $weight, false, 'course-view-*', $page->subpage);
        } else {
            $page->blocks->add_block_at_end_of_default_region($blockname);
        }
    }

    /**
     * @static
     * @param int $contextid The context that the block's belong to
     * @param null|mixed $subpagepattern The subpage pattern
     * @return void
     */
    public static function delete_blocks($contextid, $subpagepattern = null) {
        global $DB;

        $instances = $DB->get_recordset('block_instances', array(
            'parentcontextid' => $contextid,
            'subpagepattern' => $subpagepattern
        ));
        foreach ($instances as $instance) {
            blocks_delete_instance($instance, true);
        }
        $instances->close();
    }

    /**
     * Generate a new moodle_page that looks like the
     * page made on course/view.php
     *
     * @static
     * @param int $courseid
     * @return moodle_page
     */
    public static function new_moodle_page($courseid) {
        global $CFG, $DB;

        require_once($CFG->dirroot.'/course/format/flexpage/lib.php');

        $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

        $page = new moodle_page();
        $page->set_course($course);
        $page->set_context(get_context_instance(CONTEXT_COURSE, $course->id));
        $page->set_url('/course/view.php', array('id' => $course->id));
        callback_flexpage_set_pagelayout($page);
        $page->set_pagetype('course-view-flexpage');
        $page->set_other_editing_capability('moodle/course:manageactivities');

        return $page;
    }
}