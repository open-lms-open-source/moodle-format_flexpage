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
     * Add an activity as a block to the current page
     *
     * @static
     * @param int $cmid Add this course module
     * @param string|bool $region The region to add the activity to
     * @return void
     */
    public static function add_activity_block($cmid, $region = false) {
        global $DB;

        $cm = $DB->get_record('course_modules', array('id' => $cmid), '*', MUST_EXIST);

        if ($instance = self::add_block('flexpagemod', $cm->course, $region)) {
            $block = block_instance('flexpagemod', $instance);
            $block->instance_config_save((object) array('cmid' => $cm->id));
        }
    }

    /**
     * Add a block to the current page
     *
     * @static
     * @param string $blockname The block's name
     * @param int $courseid
     * @param string|bool $region The region to add the block to
     * @return bool|mixed
     */
    public static function add_block($blockname, $courseid, $region = false) {
        global $DB, $CFG;

        require_once($CFG->dirroot.'/course/format/flexpage/lib.php');

        $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

        $page = new moodle_page();
        $page->set_course($course);
        $page->set_context(get_context_instance(CONTEXT_COURSE, $course->id));
        $page->set_url('/course/view.php', array('id' => $course->id));
        callback_flexpage_set_pagelayout($page);
        $page->set_pagetype('course-view-flexpage');
        $page->blocks->load_blocks(true);

        if ($region and $region != $page->blocks->get_default_region() and $page->blocks->is_known_region($region)) {
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

        // This should fetch the instance that we just created (being overly specific here)
        $instances = $DB->get_records('block_instances', array(
            'blockname'       => $blockname,
            'parentcontextid' => $page->context->id,
            'pagetypepattern' => 'course-view-*',
            'subpagepattern'  => $page->subpage,
        ), 'id DESC', '*', 0, 1);

        if (!empty($instances)) {
            return current($instances);
        }
        return false;
    }
}