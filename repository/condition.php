<?php

class course_format_flexpage_respository_condition {
/*
    public function get_conditions($courseid) {
        global $DB;

        $rs = $DB->get_recordset_sql(
            "SELECT c.*, a.pageid, a.type
               FROM {format_page} p
         INNER JOIN {format_page_availability} a ON p.id = a.pageid
         INNER JOIN {format_page_condition} c ON a.id = c.availabilityid
              WHERE p.courseid = ?", array($courseid)
        );

        $conditions = array();
        $grouped = array();
        if ($rs) {
            foreach ($rs as $record) {
                $id = $record->availabilityid;
                if (empty($grouped[$id])) {
                    $grouped[$id] = array();
                }
                $grouped[$id]['type'] = $record->type;
                $grouped[$id][$record->name] = $record->value;
            }
            $rs->close();
        }
        foreach ($grouped as $group) {
            switch ($group['type']) {
                case 'condition_daterange':
                    $conditions[] = new condition_daterange($group['availablefrom'], $group['availableuntil']);
                    break;
                case 'condition_grade':
                    $conditions[] = new condition_grade($group['availablefrom'], $group['availableuntil']);
//                    condition_releasecode
//                    condition_completion
            }
        }
    }

    public function get_grade_conditions($courseid) {
        $rs = $DB->get_recordset_sql(
            "SELECT gi.*, a.id, a.pageid,
               FROM {format_page} p
         INNER JOIN {format_page_availability} a ON p.id = a.pageid
         INNER JOIN {format_page_condition} c1 ON a.id = c.availabilityid AND c.name = 'gradeitemid'
         INNER JOIN {grade_items} i ON
              WHERE p.courseid = ?", array($courseid)
        );
    }
*/

    public function get_course_conditions($courseid) {
        return $this->get_conditions($courseid);
    }

    // @todo only allow param of type course_format_flexpage_model_page ?
    public function get_page_condtions($param) {
        if ($param instanceof course_format_flexpage_model_page) {
            $pageid = $param->get_id();
        } else if (is_number($param)) {
            $pageid = $param;
        } else {
            throw new coding_exception('Invalid parameter passed, expecting a page ID or page instance');
        }
        // Pass through current() because conditions are keyed by pageid
        $conditions = current($this->get_conditions(null, $pageid));

        if ($param instanceof course_format_flexpage_model_page) {
            $param->set_conditions($conditions);
        } else {
            return $conditions;
        }
    }

    protected function get_conditions($courseid, $pageid = null) {
        $conditions = $this->get_gradeitem_conditions($courseid, $pageid);

        foreach ($this->get_completion_conditions($courseid, $pageid) as $pageid => $otherconditions) {
            if (array_key_exists($pageid, $conditions)) {
                $conditions[$pageid] = array_merge($conditions[$pageid], $otherconditions);
            } else {
                $conditions[$pageid] = $otherconditions;
            }
        }
        return $conditions;
    }

    protected function get_gradeitem_conditions($courseid, $pageid = null) {
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
               FROM {format_page} p
         INNER JOIN {format_page_gradeitem} c ON p.id = c.pageid
         INNER JOIN {grade_items} g ON g.ic = c.gradeitemid
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
               FROM {format_page} p
         INNER JOIN {format_page_completion} c ON p.id = c.pageid
         INNER JOIN {course_modules} cm ON cm.id = c.sourcecmid
              WHERE $sqlwhere
           ORDER BY c.id", $params
        );

        $conditions = array();
        foreach ($rs as $record) {
            $conditions[$record->pageid][] = new condition_completion(
                $record->sourcecmid, $record->requiredcompletion
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
        $DB->delete_records('format_page_gradeitem', array('pageid' => $pageid));
        $DB->delete_records('format_page_completion', array('pageid' => $pageid));

        if ($param instanceof course_format_flexpage_model_page) {
            $param->set_conditions(array());
        }
    }

    // @todo delete
    public function save_page_conditions($pageid, condition_base $condition) {
        global $DB;

        if ($condition instanceof condition_grade) {
            $DB->insert_record('format_page_gradeitem', (object) array(
                'pageid' => $pageid,
                'gradeitemid' => $condition->get_gradeitemid(),
                'grademin' => $condition->get_min(),
                'grademax'=> $condition->get_max()
            ), false);
        } else if ($condition instanceof condition_completion) {
            $DB->insert_record('format_page_completion', (object) array(
                'coursesectionid' => $pageid,
                'sourcecmid' => $condition->get_cmid(),
                'requiredcompletion' => $condition->get_requiredcompletion()
            ), false);
        } else {
            throw new coding_exception('Unsupported condition: '.get_class($condition));
        }
    }
}