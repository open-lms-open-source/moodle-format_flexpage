<?php

require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir.'/conditionlib.php');

class course_format_flexpage_repository_condition {

    public function get_course_conditions($courseid) {
        return $this->get_conditions($courseid);
    }

    public function get_page_condtions($pageid) {
        $conditions = $this->get_conditions(null, $pageid);

        if (array_key_exists($pageid, $conditions)) {
            return $conditions[$pageid];
        }
        return array();
    }

    public function set_page_conditions(course_format_flexpage_model_page $page) {
        $page->set_conditions($this->get_page_condtions($page->get_id()));
    }

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

    protected function get_grade_conditions($courseid, $pageid = null) {
        global $DB;

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

    // @todo Shouldn't get if the course doesn't have completion enabled
    protected function get_completion_conditions($courseid, $pageid = null) {
        global $DB;

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

    public function delete_page_conditions($param) {
        global $DB;

        if ($param instanceof course_format_flexpage_model_page) {
            $pageid = $param->get_id();
        } else if (is_number($param)) {
            $pageid = $param;
        } else {
            throw new coding_exception('Invalid parameter passed, expecting a page ID or page instance');
        }
        $DB->delete_records('format_flexpage_grade', array('pageid' => $pageid));
        $DB->delete_records('format_flexpage_completion', array('pageid' => $pageid));

        if ($param instanceof course_format_flexpage_model_page) {
            $param->set_conditions(array());
        }
    }

    /**
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
     * @param course_format_flexpage_model_page $page
     * @param condition_completion[] $conditions
     * @return void
     */
    public function save_page_completion_conditions(course_format_flexpage_model_page $page, array $conditions) {
        global $DB;

        // @todo Check if course completion is enabled?

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