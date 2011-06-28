<?php
/**
 * @see course_format_flexpage_model_cache
 */
require_once($CFG->dirroot.'/course/format/flexpage/model/cache.php');

/**
 * Repository mapper for course_format_flexpage_model_cache
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class course_format_flexpage_repository_cache {
    /**
     * Gets the course cache
     *
     * Does not guarantee that the cache has been built though
     *
     * @param int $courseid The course ID
     * @return course_format_flexpage_model_cache
     */
    public function get_cache($courseid = null) {
        global $DB, $COURSE;

        if (is_null($courseid)) {
            $courseid = $COURSE->id;
        }
        if ($record = $DB->get_record('format_flexpage_cache', array('courseid' => $courseid))) {
            $cache = new course_format_flexpage_model_cache();
            $cache->set_id($record->id)
                  ->set_courseid($record->courseid)
                  ->set_buildcode($record->buildcode)
                  ->set_timemodified($record->timemodified);

            if (!empty($record->pages)) {
                $pages = unserialize($record->pages);
                if (!empty($pages)) {
                    $cache->set_pages($pages);
                }
                unset($pages);
            }
        } else {
            $cache = new course_format_flexpage_model_cache();
            $cache->set_courseid($courseid);
        }
        return $cache;
    }

    /**
     * Save cache
     *
     * @param course_format_flexpage_model_cache $cache
     * @return void
     */
    public function save_cache(course_format_flexpage_model_cache &$cache) {
        global $DB;

        // Generally, when the cache hasn't been built and we are saving, then really
        // what we are doing is saving the cleared cache...
        if ($cache->has_been_built()) {
            $pages = $cache->get_pages();
            if (!empty($pages)) {
                $pages = serialize($pages);
            }
        } else {
            $pages = null;
        }
        $record = (object) array(
            'courseid' => $cache->get_courseid(),
            'pages' => $pages,
            'buildcode' => $cache->get_buildcode(),
            'timemodified' => $cache->get_timemodified(),
        );

        // Ensure only one per course, try by course id if cache id is not set
        if (!$cache->has_id() and $id = $DB->get_field('format_flexpage_cache', 'id', array('courseid' => $cache->get_courseid()))) {
            $cache->set_id($id);
        }
        if ($cache->attach_id($record)) {
            $DB->update_record('format_flexpage_cache', $record);
        } else {
            $cache->set_id(
                $DB->insert_record('format_flexpage_cache', $record)
            );
        }
    }

    /**
     * @param int $courseid
     * @return void
     */
    public function delete_course_cache($courseid) {
        global $DB;

        $DB->delete_records('format_flexpage_cache', array('courseid' => $courseid));
    }

    /**
     * Clear all caches for the entire site
     *
     * @return void
     */
    public function clear_all_cache() {
        global $DB;

        $DB->execute('
            UPDATE {format_flexpage_cache}
               SET pages = NULL, buildcode = ?, timemodified = ?
        ', array(course_format_flexpage_model_cache::BUILD_CODE_NOT, time()));
    }
}