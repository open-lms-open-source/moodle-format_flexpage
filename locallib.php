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
     * @var course_format_flexpage_model_cache[]
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

    if (!$cache->has_been_built()) {
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