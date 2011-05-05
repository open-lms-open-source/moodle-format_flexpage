<?php
/**
 * @see course_format_flexpage_lib_cache
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/cache.php');

class course_format_flexpage_repository_cache {
    /**
     * Local store of cache objects (right now, only stores last used)
     *
     * @var course_format_flexpage_lib_cache[]
     */
    protected static $caches = array();

    /**
     * Gets the course cache
     *
     * This can update the cache record if it needs to be rebuilt.
     *
     * @param int $courseid The course ID
     * @return course_format_flexpage_lib_cache
     */
    public function get_cache($courseid = null) {
        global $DB, $COURSE;

        if (is_null($courseid)) {
            $courseid = $COURSE->id;
        }
        if (!array_key_exists($courseid, self::$caches)) {
            if ($cache = $DB->get_field('format_flexpage_cache', 'cache', array('courseid' => $courseid))) {
                $cache = unserialize($cache);
            }
            if (!$cache instanceof course_format_flexpage_lib_cache) {
                $cache = new course_format_flexpage_lib_cache($courseid);
                $cache->rebuild();
                $this->save_cache($cache);
            }
            self::$caches = array($courseid => $cache);
        }
        return self::$caches[$courseid];
    }

    /**
     * Save cache
     *
     * @param course_format_flexpage_lib_cache $cache
     * @return void
     */
    public function save_cache(course_format_flexpage_lib_cache $cache) {
        global $DB;

        $record = (object) array(
            'courseid' => $cache->get_courseid(),
            'cache' => serialize($cache),
            'timemodified' => time(),
        );

        if ($id = $DB->get_field('format_flexpage_cache', 'id', array('courseid' => $cache->get_courseid()))) {
            $record->id = $id;
            $DB->update_record('format_flexpage_cache', $record);
        } else {
            $DB->insert_record('format_flexpage_cache', $record);
        }
    }

    public function clear_cache($courseid = null) {
        global $DB, $COURSE;

        if (is_null($courseid)) {
            $courseid = $COURSE->id;
        }
        if ($cache = $DB->get_record('format_flexpage_cache', array('courseid' => $courseid))) {
            $cache->cache = null;
            $cache->timemodified = time();
            $DB->update_record('format_flexpage_cache', $cache);
        }
        unset(self::$caches[$courseid]);
    }
}