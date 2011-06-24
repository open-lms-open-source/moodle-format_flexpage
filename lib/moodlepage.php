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
     * Determine if a layout exists in the page's theme
     *
     * @static
     * @param moodle_page|null $page
     * @param string|null $layout
     * @return bool
     */
    public static function layout_exists(moodle_page $page = null, $layout = null) {
        global $PAGE;

        if (is_null($page)) {
            $page = $PAGE;
        }
        if (is_null($layout)) {
            $layout = self::LAYOUT;
        }
        return array_key_exists($layout, $page->theme->layouts);
    }

    /**
     * Get a layout from a page
     *
     * @static
     * @throws coding_exception
     * @param string $layout
     * @param moodle_page|null $page
     * @return array
     */
    public static function get_layout($layout, moodle_page $page = null) {
        global $PAGE;

        if (is_null($page)) {
            $page = $PAGE;
        }
        if (!self::layout_exists($page, $layout)) {
            throw new coding_exception("Layout does not exist in current theme: $layout");
        }
        return $page->theme->layouts[$layout];
    }

    /**
     * Get theme regions
     *
     * @static
     * @return array
     */
    public static function get_regions() {
        global $PAGE;

        $layout  = self::get_layout(self::LAYOUT);
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
        $layout = self::get_layout(self::LAYOUT);
        return $layout['defaultregion'];
    }

    /**
     * Get the add block options
     *
     * @static
     * @param course_format_flexpage_model_page $page
     * @return array
     */
    public static function get_add_block_options(course_format_flexpage_model_page $page) {
        $moodlepage = self::new_moodle_page($page);
        $moodlepage->blocks->load_blocks(true);

        $options = array();
        $blocks  = $moodlepage->blocks->get_addable_blocks();

        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                if ($block->name == 'flexpagemod') {
                    continue;
                }
                $blockobject = block_instance($block->name);
                if ($blockobject !== false and $blockobject->user_can_addto($moodlepage)) {
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
     * @param course_format_flexpage_model_page $page
     * @param int $cmid Add this course module
     * @param string|bool $region The region to add the activity to
     * @return void
     */
    public static function add_menu_block(course_format_flexpage_model_page $page, $menuid, $region = false) {
        global $SESSION;

        $SESSION->block_flexpagenav_create_menuids = array($menuid);

        self::add_block($page, 'flexpagenav', $region);
    }

    /**
     * Add an activity as a block to the current page
     *
     * @static
     * @param course_format_flexpage_model_page $page
     * @param int $cmid Add this course module
     * @param string|bool $region The region to add the activity to
     * @return void
     */
    public static function add_activity_block(course_format_flexpage_model_page $page, $cmid, $region = false) {
        global $SESSION;

        $SESSION->block_flexpagemod_create_cmids = array($cmid);

        self::add_block($page, 'flexpagemod', $region);
    }

    /**
     * Add a block to the current page
     *
     * @static
     * @param course_format_flexpage_model_page $page
     * @param string $blockname The block's name
     * @param string|bool $region The region to add the block to
     * @return void
     */
    public static function add_block(course_format_flexpage_model_page $page, $blockname, $region = false) {
        $moodlepage = self::new_moodle_page($page);
        $moodlepage->blocks->load_blocks(true);

        if (!$moodlepage->user_can_edit_blocks()) {
            throw new moodle_exception('nopermissions', '', $moodlepage->url->out(), get_string('addblock'));
        }
        if (!array_key_exists($blockname, $moodlepage->blocks->get_addable_blocks())) {
            throw new moodle_exception('cannotaddthisblocktype', '', $moodlepage->url->out(), $blockname);
        }
        if (!empty($region) and $region != $moodlepage->blocks->get_default_region() and $moodlepage->blocks->is_known_region($region)) {
            // Determine weight...
            $blocks = $moodlepage->blocks->get_blocks_for_region($region);
            if (empty($blocks)) {
                $weight = 0;
            } else {
                $block  = end($blocks);
                $weight = $block->instance->weight + 1;
            }
            $moodlepage->blocks->add_block($blockname, $region, $weight, false, 'course-view-*', $moodlepage->subpage);
        } else {
            $moodlepage->blocks->add_block_at_end_of_default_region($blockname);
        }
    }

    /**
     * Copy blocks from one page to another
     *
     * @static
     * @param course_format_flexpage_model_page $frompage
     * @param course_format_flexpage_model_page $destpage
     * @return void
     */
    public static function copy_page_blocks(course_format_flexpage_model_page $frompage, course_format_flexpage_model_page $destpage) {
        global $DB;

        $context   = get_context_instance(CONTEXT_COURSE, $frompage->get_courseid());
        $instances = $DB->get_recordset('block_instances', array(
            'parentcontextid' => $context->id,
            'subpagepattern'  => $frompage->get_id(),
        ), 'defaultweight');
        foreach ($instances as $instance) {
            if ($instance->blockname == 'flexpagemod') {
                $block = block_instance('flexpagemod', $instance);
                if (!empty($block->config->cmid)) {
                    self::add_activity_block($destpage, $block->config->cmid, $instance->defaultregion);
                }
            } else if ($instance->blockname == 'flexpagenav') {
                $block = block_instance('flexpagenav', $instance);
                if (!empty($block->config->menuid)) {
                    self::add_activity_block($destpage, $block->config->menuid, $instance->defaultregion);
                }
            } else {
                self::add_block($destpage, $instance->blockname, $instance->defaultregion);
            }
        }
        $instances->close();
    }

    /**
     * @static
     * @param course_format_flexpage_model_page $page
     * @return void
     */
    public static function delete_blocks(course_format_flexpage_model_page $page) {
        global $DB;

        $context   = get_context_instance(CONTEXT_COURSE, $page->get_courseid());
        $instances = $DB->get_recordset('block_instances', array(
            'parentcontextid' => $context->id,
            'subpagepattern'  => $page->get_id(),
        ));
        foreach ($instances as $instance) {
            blocks_delete_instance($instance, true);
        }
        $instances->close();
    }

    /**
     * Deletes all blocks linked to display the passed course module ID
     *
     * @static
     * @param int $cmid
     * @return void
     */
    public static function delete_mod_blocks($cmid) {
        global $DB;

        $instances = $DB->get_recordset_sql("
            SELECT i.*
              FROM {block_instances} i
        INNER JOIN {block_flexpagemod} f ON i.id = f.instanceid
             WHERE f.cmid = ?
        ", array($cmid));

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
     * @param course_format_flexpage_model_page $page
     * @return moodle_page
     */
    public static function new_moodle_page(course_format_flexpage_model_page $page) {
        global $CFG, $DB;

        require_once($CFG->dirroot.'/course/format/flexpage/lib.php');

        $course = $DB->get_record('course', array('id' => $page->get_courseid()), '*', MUST_EXIST);

        $moodlepage = new moodle_page();
        $moodlepage->set_course($course);
        $moodlepage->set_context(get_context_instance(CONTEXT_COURSE, $course->id));
        $moodlepage->set_url('/course/view.php', array('id' => $course->id));
        $moodlepage->set_pagelayout(self::LAYOUT);
        $moodlepage->set_subpage($page->get_id());
        $moodlepage->set_pagetype('course-view-flexpage');
        $moodlepage->set_other_editing_capability('moodle/course:manageactivities');

        return $moodlepage;
    }
}