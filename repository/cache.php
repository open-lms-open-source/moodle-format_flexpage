<?php
/**
 * @see course_format_flexpage_model_cache
 */
require_once($CFG->dirroot.'/course/format/flexpage/model/cache.php');

class course_format_flexpage_repository_cache {
    /**
     * Gets the course cache
     *
     * This can update the cache record if it needs to be rebuilt.
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
            $cache->set_id($record->id);
            $cache->set_courseid($record->courseid);
            $cache->set_timemodified($record->timemodified);

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

        $pages = null;

        // Generally, when the cache hasn't been built and we are saving, then really
        // what we are doing is saving the cleared cache...
        if ($cache->has_been_built()) {
            $pages = $cache->get_pages();
            if (!empty($pages)) {
                $pages = serialize($pages);
            }
        }
        $record = (object) array(
            'courseid' => $cache->get_courseid(),
            'pages' => $pages,
            'timemodified' => $cache->get_timemodified(),
        );

        // Ensure only one per course, try by course id if cache id is not set
        if (!($cache->get_id() > 0) and $id = $DB->get_field('format_flexpage_cache', 'id', array('courseid' => $cache->get_courseid()))) {
            $cache->set_id($id);
        }
        if ($cache->get_id() > 0) {
            $record->id = $cache->get_id();
            $DB->update_record('format_flexpage_cache', $record);
        } else {
            $cache->set_id(
                $DB->insert_record('format_flexpage_cache', $record)
            );
        }
    }
}