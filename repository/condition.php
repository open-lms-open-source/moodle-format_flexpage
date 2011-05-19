<?php

require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir.'/conditionlib.php');

/**
 * @see course_format_flexpage_model_page
 */
require_once($CFG->dirroot.'/course/format/flexpage/model/page.php');

/**
 * Repository mapper for page conditions
 */
class course_format_flexpage_repository_condition {
    /**
     * Get all page conditions for a course
     *
     * @param int $courseid
     * @return array
     */
    public function get_course_conditions($courseid) {
        return $this->get_conditions($courseid);
    }

    /**
     * Get all page conditions
     *
     * @param course_format_flexpage_model_page $page
     * @return array
     */
    public function get_page_condtions(course_format_flexpage_model_page $page) {
        $conditions = $this->get_conditions($page->get_courseid(), $page->get_id());

        if (array_key_exists($page->get_id(), $conditions)) {
            return $conditions[$page->get_id()];
        }
        return array();
    }

    /**
     * Get all page conditions and set them to the page
     *
     * @param course_format_flexpage_model_page $page
     * @return void
     */
    public function set_page_conditions(course_format_flexpage_model_page $page) {
        $page->set_conditions($this->get_page_condtions($page));
    }

    /**
     * Delete conditions from database and remove them from the page
     *
     * @param course_format_flexpage_model_page $page
     * @return void
     */
    public function remove_page_conditions(course_format_flexpage_model_page $page) {
        global $DB;

        $DB->delete_records('format_flexpage_grade', array('pageid' => $page->get_id()));
        $DB->delete_records('format_flexpage_completion', array('pageid' => $page->get_id()));

        $page->set_conditions(array());
    }

    /**
     * Fetches all conditions for a page or course
     *
     * @param int $courseid
     * @param null|int $pageid
     * @return array
     */
    protected function get_conditions($courseid, $pageid = null) {
        $conditions = $this->get_grade_conditions($courseid, $pageid);

        foreach ($this->get_completion_conditions($courseid, $pageid) as $pageid => $otherconditions) {
            if (array_key_exists($pageid, $conditions)) {
                $conditions[$pageid] = array_merge($conditions[$pageid], $otherconditions);
            } else {
                $conditions[$pageid] = $otherconditions;
            }
        }
        return $conditions;
    }

    /**
     * Get grade conditions
     *
     * @param int $courseid
     * @param null|int $pageid
     * @return array
     */
    protected function get_grade_conditions($courseid, $pageid = null) {
        global $CFG, $DB;

        if (empty($CFG->enableavailability)) {
            return array();
        }
        if (!is_null($pageid)) {
            $sqlwhere = 'p.id = ?';
            $params   = array($pageid);
        } else {
            $sqlwhere = 'p.courseid = ?';
            $params   = array($courseid);
        }
        $rs = $DB->get_recordset_sql(
            "SELECT c.id AS conditionid, g.*, c.pageid, c.grademin AS conditiongrademin, c.grademax AS conditiongrademax
               FROM {format_flexpage_page} p
         INNER JOIN {format_flexpage_grade} c ON p.id = c.pageid
         INNER JOIN {grade_items} g ON g.id = c.gradeitemid
              WHERE $sqlwhere
           ORDER BY conditionid", $params
        );

        $conditions = array();
        foreach ($rs as $record) {
            $conditions[$record->pageid][] = new condition_grade(
                $record, $record->conditiongrademin, $record->conditiongrademax
            );
        }
        return $conditions;
    }

    /**
     * Get completion conditions
     *
     * @param int $courseid
     * @param null|int $pageid
     * @return array
     */
    protected function get_completion_conditions($courseid, $pageid = null) {
        global $DB;

        $completion = new completion_info($DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST));
        if (!$completion->is_enabled()) {
            return array();
        }

        if (!is_null($pageid)) {
            $sqlwhere = 'p.id = ?';
            $params   = array($pageid);
        } else {
            $sqlwhere = 'p.courseid = ?';
            $params   = array($courseid);
        }
        $rs = $DB->get_recordset_sql(
            "SELECT c.*
               FROM {format_flexpage_page} p
         INNER JOIN {format_flexpage_completion} c ON p.id = c.pageid
         INNER JOIN {course_modules} cm ON cm.id = c.cmid
              WHERE $sqlwhere
           ORDER BY c.id", $params
        );

        $conditions = array();
        foreach ($rs as $record) {
            $conditions[$record->pageid][] = new condition_completion(
                $record->cmid, $record->requiredcompletion
            );
        }
        return $conditions;
    }

    /**
     * Save grade conditions
     *
     * @param course_format_flexpage_model_page $page
     * @param condition_grade[] $conditions
     * @return void
     */
    public function save_page_grade_conditions(course_format_flexpage_model_page $page, array $conditions) {
        global $DB;

        $saveids = array();

        foreach ($conditions as $condition) {
            $record = (object) array(
                'pageid' => $page->get_id(),
                'gradeitemid' => $condition->get_gradeitemid(),
                'grademin' => $condition->get_min(),
                'grademax'=> $condition->get_max()
            );
            $params = array(
                'pageid' => $page->get_id(),
                'gradeitemid' => $condition->get_gradeitemid()
            );
            if ($id = $DB->get_field('format_flexpage_grade', 'id', $params)) {
                $saveids[] = $id;
                $record->id = $id;
                $DB->update_record('format_flexpage_grade', $record);
            } else {
                $saveids[] = $DB->insert_record('format_flexpage_grade', $record);
            }
        }
        if (!empty($saveids)) {
            list($select, $params) = $DB->get_in_or_equal($saveids, SQL_PARAMS_QM, '', false);
            $params[] = $page->get_id();

            $DB->delete_records_select('format_flexpage_grade', "id $select AND pageid = ?", $params);
        } else {
            $DB->delete_records('format_flexpage_grade', array('pageid' => $page->get_id()));
        }
    }

    /**
     * Save completion conditions
     *
     * @param course_format_flexpage_model_page $page
     * @param condition_completion[] $conditions
     * @return void
     */
    public function save_page_completion_conditions(course_format_flexpage_model_page $page, array $conditions) {
        global $DB;

        $saveids = array();

        foreach ($conditions as $condition) {
            $record = (object) array(
                'pageid' => $page->get_id(),
                'cmid' => $condition->get_cmid(),
                'requiredcompletion' => $condition->get_requiredcompletion()
            );
            $params = array(
                'pageid' => $page->get_id(),
                'cmid' => $condition->get_cmid(),
            );
            if ($id = $DB->get_field('format_flexpage_completion', 'id', $params)) {
                $saveids[] = $id;
                $record->id = $id;
                $DB->update_record('format_flexpage_completion', $record);
            } else {
                $saveids[] = $DB->insert_record('format_flexpage_completion', $record);
            }
        }
        if (!empty($saveids)) {
            list($select, $params) = $DB->get_in_or_equal($saveids, SQL_PARAMS_QM, '', false);
            $params[] = $page->get_id();

            $DB->delete_records_select('format_flexpage_completion', "id $select AND pageid = ?", $params);
        } else {
            $DB->delete_records('format_flexpage_completion', array('pageid' => $page->get_id()));
        }
    }
}