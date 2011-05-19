<?php
/**
 * @see course_format_flexpage_lib_moodlepage
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

/**
 * @see course_format_flexpage_model_page
 */
require_once($CFG->dirroot.'/course/format/flexpage/model/page.php');

/**
 * Repository mapper for course_format_flexpage_model_page
 */
class course_format_flexpage_repository_page {
    /**
     * Get a page from ID
     *
     * @param int $id
     * @return course_format_flexpage_model_page
     */
    public function get_page($id) {
        global $DB;

        $record = $DB->get_record('format_flexpage_page', array('id' => $id), '*', MUST_EXIST);

        return new course_format_flexpage_model_page($record);
    }

    /**
     * Get pages
     *
     * @param array $conditions
     * @param string $sort
     * @return array|course_format_flexpage_model_page[]
     */
    public function get_pages(array $conditions, $sort = '') {
        global $DB;

        $pages = array();
        $rs    = $DB->get_recordset('format_flexpage_page', $conditions, $sort);
        foreach ($rs as $page) {
            $pages[$page->id] = new course_format_flexpage_model_page($page);
        }
        $rs->close();

        return $pages;
    }

    /**
     * Create the course default page
     *
     * @param int $courseid
     * @return course_format_flexpage_repository_page
     */
    public function create_default_page($courseid) {
        global $DB;

        if (!$DB->record_exists('format_flexpage_page', array('courseid' => $courseid))) {

            $course = $DB->get_record('course', array('id' => $courseid), 'id, fullname, shortname', MUST_EXIST);

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
    public function save_page(course_format_flexpage_model_page $page) {
        global $DB;

        $id = $page->get_id();

        $record = (object) array(
            'courseid'         => $page->get_courseid(),
            'name'             => $page->get_name(),
            'altname'          => $page->get_altname(),
            'display'          => $page->get_display(),
            'navigation'       => $page->get_navigation(),
            'availablefrom'    => $page->get_availablefrom(),
            'availableuntil'   => $page->get_availableuntil(),
            'releasecode'      => $page->get_releasecode(),
            'showavailability' => $page->get_showavailability(),
            'parentid'         => $page->get_parentid(),
            'weight'           => $page->get_weight(),
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

    /**
     * Get page region widths
     *
     * @param int $pageid
     * @return array
     */
    public function get_page_region_widths($pageid) {
        global $DB;

        $regionwidths = array();
        if ($regions = $DB->get_records('format_flexpage_region', array('pageid' => $pageid))) {
            foreach ($regions as $region) {
                $regionwidths[$region->region] = $region->width;
            }
        }
        return $regionwidths;
    }

    /**
     * Get page region widths and set them to the page
     *
     * @param course_format_flexpage_model_page $page
     * @return void
     */
    public function set_page_region_widths(course_format_flexpage_model_page $page) {
        $page->set_region_widths($this->get_page_region_widths($page->get_id()));
    }

    /**
     * Save page region widths
     *
     * @param course_format_flexpage_model_page $page
     * @param array $regionwidths Key, region name, value, region width
     * @return void
     */
    public function save_page_region_widths(course_format_flexpage_model_page $page, array $regionwidths) {
        global $DB;

        foreach ($regionwidths as $region => $width) {
            if (empty($width)) {
                unset($regionwidths[$region]);
                continue;
            }
            $conditions = array('pageid' => $page->get_id(), 'region' => $region);
            if ($DB->record_exists('format_flexpage_region', $conditions)) {
                $DB->set_field('format_flexpage_region', 'width', $width, $conditions);
            } else {
                $conditions['width'] = $width;
                $DB->insert_record('format_flexpage_region', (object) $conditions);
            }
        }
        if (!empty($regionwidths)) {
            list($select, $params) = $DB->get_in_or_equal(array_keys($regionwidths), SQL_PARAMS_QM, '', false);
            $params[] = $page->get_id();

            $DB->delete_records_select('format_flexpage_region', "region $select AND pageid = ?", $params);
        } else {
            $DB->delete_records('format_flexpage_region', array('pageid' => $page->get_id()));
        }
    }

    /**
     * Delete page region widths and remove from page
     *
     * @param course_format_flexpage_model_page $page
     * @return void
     */
    public function remove_page_region_widths(course_format_flexpage_model_page $page) {
        $this->save_page_region_widths($page, array());
        $page->set_region_widths(array());
    }

    /**
     * Move page in association to another page
     *
     * @param course_format_flexpage_model_page $page
     * @param int $move Page model move constant
     * @param int $referencepageid The page ID to reference the move action
     * @return course_format_flexpage_repository_page
     */
    public function move_page(course_format_flexpage_model_page $page, $move, $referencepageid) {
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
        return $this;
    }

    /**
     * Remove a page from hierarchy
     *
     * @param course_format_flexpage_model_page $page
     * @return course_format_flexpage_repository_page
     */
    protected function remove_page_position(course_format_flexpage_model_page $page) {
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

    /**
     * Delete a page
     *
     * @param course_format_flexpage_model_page $page  Will set it's id to null
     * @return void
     */
    public function delete_page(course_format_flexpage_model_page $page) {
        global $CFG, $DB;

        require_once($CFG->dirroot.'/course/format/flexpage/repository/condition.php');

        $parentid = $page->get_parentid();
        $context  = get_context_instance(CONTEXT_COURSE, $page->get_courseid());

        // Remove page blocks
        course_format_flexpage_lib_moodlepage::delete_blocks($context->id, $page->get_id());

        // Get the page out of the way
        $this->remove_page_position($page);

        // Now we need to move the page's children to children of the page's parent

        // Default, if we don't find any other children of the parent, then we move
        // the page's first child as the first child of parent
        $referencepageid = $parentid;
        $move = course_format_flexpage_model_page::MOVE_CHILD;

        // Look for the last child of the parent, if found, we move after it
        $parentchilren = $DB->get_records('format_flexpage_page', array('parentid' => $parentid), 'weight DESC', 'id', 0, 1);
        foreach ($parentchilren as $lastchild) {
            $referencepageid = $lastchild->id;
            $move = course_format_flexpage_model_page::MOVE_AFTER;
        }

        // Move all of the child pages
        $children = $this->get_pages(array('parentid' => $page->get_id()), 'weight');
        foreach ($children as $child) {
            $this->move_page($child, $move, $referencepageid)
                 ->save_page($child);

            // Move each subsequent child after the prior child
            $referencepageid = $child->get_id();
            $move = course_format_flexpage_model_page::MOVE_AFTER;
        }

        // Remove conditions, region widths and finally the page
        $condrepo = new course_format_flexpage_repository_condition();
        $condrepo->remove_page_conditions($page);

        $this->remove_page_region_widths($page);

        $DB->delete_records('format_flexpage_page', array('id' => $page->get_id()));

        // Null out id!
        $page->set_id(null);
    }
}