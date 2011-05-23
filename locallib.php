<?php
/**
 * @see course_format_flexpage_repository_cache
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/cache.php');

/**
 * @param int|null $courseid
 * @return course_format_flexpage_model_cache
 */
function format_flexpage_cache($courseid = null) {
    global $COURSE;

    /**
     * @var course_format_flexpage_model_cache[] $caches
     */
    static $caches = array();

    if (is_null($courseid)) {
        $courseid = $COURSE->id;
    }
    if (!array_key_exists($courseid, $caches)) {
        $repo   = new course_format_flexpage_repository_cache();
        $cache  = $repo->get_cache($courseid);
        $caches = array($courseid => $cache);
    }
    $cache =& $caches[$courseid];

    // Build cache when:
    //    * The cache hasn't been built yet
    //    * The cache's current build code doesn't match what the new build could would be
    if (!$cache->has_been_built() or $cache->get_buildcode() != $cache->get_new_buildcode()) {
        $cache->build();
        $repo = new course_format_flexpage_repository_cache();
        $repo->save_cache($cache);
    }
    return $cache;
}

/**
 * @param int|null $courseid
 * @return void
 */
function format_flexpage_clear_cache($courseid = null) {
    $repo  = new course_format_flexpage_repository_cache();
    $cache = format_flexpage_cache($courseid);
    $cache->clear();
    $repo->save_cache($cache);
}

/**
 * Get a width for a region
 *
 * @param null|string $region Get the width for this theme region
 * @param null|string $default The default if no width is found
 * @return null|string
 */
function format_flexpage_region_width($region, $default = null) {
    global $CFG;

    static $widths = null;

    require_once($CFG->dirroot.'/course/format/flexpage/repository/page.php');

    if (is_null($widths)) {
        $page   = format_flexpage_cache()->get_current_page();
        $repo   = new course_format_flexpage_repository_page();
        $widths = $repo->get_page_region_widths($page);
    }
    if (array_key_exists($region, $widths)) {
        return $widths[$region];
    } else {
        return $default;
    }
}

/**
 * Get all region widths
 *
 * @param array $defaults Define region defaults.  Key is region and value is default.
 * @return array Array of string and null values
 */
function format_flexpage_region_widths($defaults = array()) {
    global $CFG;

    require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

    $widths = array();
    foreach (course_format_flexpage_lib_moodlepage::get_regions() as $region => $name) {
        if (array_key_exists($region, $defaults)) {
            $default = $defaults[$region];
        } else {
            $default = null;
        }
        $widths[$region] = format_flexpage_region_width($region, $default);
    }
    return $widths;
}

/**
 * This generates a <style> tag for a theme's <head> tag to adjust
 * column widths for the default Moodle 3 column layout according
 * to the current Flexpage page.
 *
 * @param int $sidepredefault
 * @param int $sidepostdefault
 * @return string
 */
function format_flexpage_default_width_styles($sidepredefault = 200, $sidepostdefault = 200) {
    $widths = format_flexpage_region_widths(array(
        'side-pre'  => $sidepredefault,
        'side-post' => $sidepostdefault,
    ));

    $regionmain    = $widths['side-pre'] + $widths['side-post'];
    $regionpostbox = ($widths['side-post'] + 200) * -1;

    $othercss = '';
    if ($widths['side-top']) {
        $othercss .= "\n    #page-content #region-top { width: {$widths['side-top']}px; }";
    }
    if (!is_null($widths['main'])) {
        $othercss .= "\n    #page-content #region-main-box { width: ".(($regionmain + $widths['main']) * 2).'px; }';
    }
    return <<<EOT

<style type="text/css">$othercss
    #page-content #region-post-box { margin-left: {$regionpostbox}px; }
    #page-content #region-pre { left: {$widths['side-post']}px; width: {$widths['side-pre']}px; }
    #page-content #region-main { margin-left: {$regionmain}px; }
    #page-content #region-post { width: {$widths['side-post']}px; }
</style>

EOT;
}

/**
 * Get the previous page
 *
 * @return bool|course_format_flexpage_model_page
 */
function format_flexpage_previous_page() {
    $cache = format_flexpage_cache();
    $page  = $cache->get_current_page();

    if ($page->has_navigation_previous()) {
        return $cache->get_previous_page($page);
    }
    return false;
}

/**
 * Get the next page
 *
 * @return bool|course_format_flexpage_model_page
 */
function format_flexpage_next_page() {
    $cache = format_flexpage_cache();
    $page  = $cache->get_current_page();

    if ($page->has_navigation_next()) {
        return $cache->get_next_page($page);
    }
    return false;
}

/**
 * Get the previous page URL
 *
 * @return bool|moodle_url
 */
function format_flexpage_previous_url() {
    if ($page = format_flexpage_previous_page()) {
        return $page->get_url();
    }
    return false;
}

/**
 * Get the next page URL
 *
 * @return bool|moodle_url
 */
function format_flexpage_next_url() {
    if ($page = format_flexpage_next_page()) {
        return $page->get_url();
    }
    return false;
}

/**
 * Get the previous page link
 *
 * @param null|string $label The label for the link
 * @return string
 */
function format_flexpage_previous_link($label = null) {
    if ($page = format_flexpage_previous_page()) {
        if (is_null($label)) {
            $label = get_string('previouspage', 'format_flexpage', format_string($page->get_display_name()));
        }
        return html_writer::link($page->get_url(), $label, array('id' => 'format_flexpage_previous_page'));
    }
    return '';
}

/**
 * Get the next page link
 *
 * @param null|string $label The label for the link
 * @return string
 */
function format_flexpage_next_link($label = null) {
    if ($page = format_flexpage_next_page()) {
        if (is_null($label)) {
            $label = get_string('nextpage', 'format_flexpage', format_string($page->get_display_name()));
        }
        return html_writer::link($page->get_url(), $label, array('id' => 'format_flexpage_next_page'));
    }
    return '';
}

/**
 * Get the previous page button
 *
 * @param null|string $label The label for the link
 * @return string
 */
function format_flexpage_previous_button($label = null) {
    global $PAGE;

    $link = format_flexpage_previous_link($label);
    if (!empty($link)) {
        $PAGE->requires->yui2_lib('button');
        $PAGE->requires->js_init_call('(function(Y) { new YAHOO.widget.Button("format_flexpage_previous_page"); })');
    }
    return $link;
}

/**
 * Get the next page button
 *
 * @param null|string $label The label for the link
 * @return string
 */
function format_flexpage_next_button($label = null) {
    global $PAGE;

    $link = format_flexpage_next_link($label);
    if (!empty($link)) {
        $PAGE->requires->yui2_lib('button');
        $PAGE->requires->js_init_call('(function(Y) { new YAHOO.widget.Button("format_flexpage_next_page"); })');
    }
    return $link;
}