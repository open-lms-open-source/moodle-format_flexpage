<?php
/**
 * @see course_format_flexpage_model_page
 */
require_once($CFG->dirroot.'/course/format/flexpage/model/page.php');

/**
 * @see course_format_flexpage_repository_cache
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/cache.php');

class course_format_flexpage_repository_page {
    /**
     * @var course_format_flexpage_repository_cache
     */
    protected $cacherepo;

    public function __construct() {
        $this->set_cache_repository(new course_format_flexpage_repository_cache());
    }

    public function set_cache_repository($repo) {
        $this->cacherepo = $repo;
    }

    public function get_page($id) {
        global $DB;

        $record = $DB->get_record('format_flexpage_page', array('id' => $id), '*', MUST_EXIST);

        return new course_format_flexpage_model_page($record);
    }

    /**
     * @param  $courseid
     * @param string $sort
     * @return array|course_format_flexpage_model_page[]
     */
    public function get_pages($courseid, $sort = 'parentid, weight') {
        global $DB;

        $pages = array();
        $rs    = $DB->get_recordset('format_flexpage_page', array('courseid' => $courseid), $sort);
        foreach ($rs as $page) {
            $pages[$page->id] = new course_format_flexpage_model_page($page);
        }
        $rs->close();

        return $pages;
    }

    public function create_default_page($courseorid) {
        global $DB;

        if (is_object($courseorid)) {
            $courseid = $courseorid->id;
        } else {
            $courseid = $courseorid;
        }
        if (!$DB->record_exists('format_flexpage_page', array('courseid' => $courseid))) {
            if (is_object($courseorid)) {
                $course = $courseorid;
            } else {
                $course = $DB->get_record('course', array('id' => $courseorid), 'id, fullname, shortname', MUST_EXIST);
            }
            $a = (object) array(
                'fullname'  => $course->fullname,
                'shortname' => $course->shortname
            );
            $this->save_page(new course_format_flexpage_model_page(array(
                'courseid' => $courseid,
                'name'     => get_string('defaultcoursepagename', 'format_flexpage', $a),
            )));
        }
        return $this;
    }

    /**
     * Save a page
     *
     * On insert, automatically populates the page object with the new ID
     *
     * @param course_format_flexpage_model_page $page
     * @return course_format_flexpage_repository_page
     */
    public function save_page(course_format_flexpage_model_page &$page) {
        global $DB;

        $id = $page->get_id();

        // @todo This doesn't seem the best... maybe model has to_record() method?
        $record = (object) array(
            'courseid' => $page->get_courseid(),
            'name' => $page->get_name(),
            'altname' => $page->get_altname(),
            'display' => $page->get_display(),
            'showbuttons' => $page->get_showbuttons(),
            'template' => $page->get_template(),
            'parentid' => $page->get_parentid(),
            'weight' => $page->get_weight(),
        );

        if (!empty($id)) {
            $record->id = $id;
            $DB->update_record('format_flexpage_page', $record);
        } else {
            $page->set_id(
                $DB->insert_record('format_flexpage_page', $record)
            );
        }
        return $this;
    }

    public function move_page(course_format_flexpage_model_page &$page, $move, $referencepageid) {
        global $DB;

        $refpage = $this->get_page($referencepageid);

        if ($page->get_id() > 0) {
            $this->remove_page_position($page);
        }

        switch ($move) {
            case course_format_flexpage_model_page::MOVE_BEFORE:
                // The page gets the refpage's weight and parent
                $page->set_weight($refpage->get_weight())
                     ->set_parentid($refpage->get_parentid());

                // Push weights up to make room
                $DB->execute("
                    UPDATE {format_flexpage_page}
                       SET weight = (weight + 1)
                     WHERE parentid = ?
                       AND weight >= ?
                ", array($refpage->get_parentid(), $refpage->get_weight()));

                break;

            case course_format_flexpage_model_page::MOVE_AFTER:
                // The page gets the refpage's parent and weight + 1
                $page->set_weight(($refpage->get_weight() + 1))
                     ->set_parentid($refpage->get_parentid());

                // Push weights up to make room
                $DB->execute("
                    UPDATE {format_flexpage_page}
                       SET weight = (weight + 1)
                     WHERE parentid = ?
                       AND weight > ?
                ", array($refpage->get_parentid(), $refpage->get_weight()));

                break;

            case course_format_flexpage_model_page::MOVE_CHILD:
                // The page gets the refpage's ID and weight of zero
                $page->set_weight(0)
                     ->set_parentid($refpage->get_id());

                // Push weights up to make room
                $DB->execute("
                    UPDATE {format_flexpage_page}
                       SET weight = (weight + 1)
                     WHERE parentid = ?
                ", array($refpage->get_id()));

                break;
        }
        // @todo Remove from here?
        $this->cacherepo->clear_cache($page->get_courseid());
        return $this;
    }

    protected function remove_page_position(course_format_flexpage_model_page &$page) {
        global $DB;

        $DB->execute("
            UPDATE {format_flexpage_page}
               SET weight = (weight - 1)
             WHERE parentid = ?
               AND weight > ?
        ", array($page->get_parentid(), $page->get_weight()));

        $page->set_parentid(0)
             ->set_weight(0);

        return $this;
    }
}