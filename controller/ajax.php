<?php
/**
 * @see course_format_flexpage_repository_page
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/page.php');

/**
 * @see course_format_flexpage_lib_mod
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/mod.php');

/**
 * @see course_format_flexpage_lib_moodlepage
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

/**
 * AJAX Controller
 */
class course_format_flexpage_controller_ajax extends mr_controller {
    /**
     * Since this handles AJAX, set our own exception handler
     *
     * @return void
     */
    protected function init() {
        set_exception_handler(array($this, 'exception_handler'));
    }

    public function require_capability() {
        // @todo
    }

    /**
     * Set's errors through mr_notify
     *
     * @param Exception $e
     * @return void
     */
    public function exception_handler($e) {
        $this->notify->bad('ajaxexception', $e->getMessage());

        if (debugging('', DEBUG_DEVELOPER)) {
            $this->notify->add_string(format_backtrace(get_exception_info($e)->backtrace));
        }
    }

    /**
     * Add Pages Modal
     */
    public function addpages_action() {
        global $COURSE;

        if (optional_param('add', 0, PARAM_BOOL)) {
            require_sesskey();

            $names = optional_param('name', array(), PARAM_MULTILANG);
            $moves = optional_param('move', array(), PARAM_ACTION);
            $referencepageids = optional_param('referencepageid', array(), PARAM_INT);

            // Add pages in reverse order, will match user expectations better
            $names = array_reverse($names, true);
            $moves = array_reverse($moves, true);
            $referencepageids = array_reverse($referencepageids, true);

            $repo = new course_format_flexpage_repository_page();
            $addedpages = array();
            foreach ($names as $key => $name) {
                // Required values...
                if (empty($name) or empty($moves[$key]) or empty($referencepageids[$key])) {
                    continue;
                }
                $addedpages[] = format_string($name);

                $page = new course_format_flexpage_model_page();
                $page->set_name($name);
                $page->set_courseid($COURSE->id);

                $repo->move_page($page, $moves[$key], $referencepageids[$key])
                     ->save_page($page);
            }
            format_flexpage_clear_cache();

            if (!empty($addedpages)) {
                $this->notify->good('addedpages', implode(', ', array_reverse($addedpages)));
            }
        } else {
            $pageoptions = array();
            foreach (format_flexpage_cache()->get_pages() as $page) {
                $pageoptions[$page->get_id()] = $this->output->pad_page_name($page);
            }
            $moveoptions = course_format_flexpage_model_page::get_move_options();

            $submiturl = $this->new_url(array('sesskey' => sesskey(), 'action' => 'addpages', 'add' => 1));

            echo json_encode((object) array(
                'header' => get_string('addpages', 'format_flexpage'),
                'body' => $this->output->render_addpages($submiturl, $pageoptions, $moveoptions),
            ));
        }
    }

    /**
     * Move Page Modal
     */
    public function movepage_action() {

        $pageid      = required_param('pageid', PARAM_INT);
        $repo        = new course_format_flexpage_repository_page();
        $movepage    = $repo->get_page($pageid);
        $moveoptions = course_format_flexpage_model_page::get_move_options();

        if (optional_param('move', 0, PARAM_BOOL)) {
            require_sesskey();

            $move = required_param('move', PARAM_ACTION);
            $referencepageid = required_param('referencepageid', PARAM_INT);

            $repo->move_page($movepage, $move, $referencepageid)
                 ->save_page($movepage);

            format_flexpage_clear_cache();

            $refpage = $repo->get_page($referencepageid);

            $this->notify->good('movedpage', (object) array(
                'movepage' => format_string($movepage->get_display_name()),
                'move' => $moveoptions[$move],
                'refpage' => format_string($refpage->get_display_name()),
            ));
        } else {
            $pageoptions = array();
            foreach (format_flexpage_cache()->get_pages() as $page) {
                // Skip the page that we are moving and any of its children
                if ($page->get_id() == $movepage->get_id() or format_flexpage_cache()->is_child_page($movepage, $page)) {
                    continue;
                }
                $pageoptions[$page->get_id()] = $this->output->pad_page_name($page);
            }
            $submiturl = $this->new_url(array('sesskey' => sesskey(), 'action' => 'movepage', 'pageid' => $pageid, 'move' => 1));

            echo json_encode((object) array(
                'header' => get_string('movepage', 'format_flexpage'),
                'body' => $this->output->render_movepage($movepage, $submiturl, $pageoptions, $moveoptions),
            ));
        }
    }

    /**
     * Add New Activity Modal
     */
    public function addactivity_action() {
        global $SESSION;

        if (optional_param('add', 0, PARAM_BOOL)) {
            require_sesskey();

            $url    = required_param('addurl', PARAM_URL);
            $region = optional_param('region', false, PARAM_ACTION);

            $SESSION->format_flexpage_mod_region = $region;

            redirect(new moodle_url($url));
        }

        echo json_encode((object) array(
            'args' => course_format_flexpage_lib_moodlepage::get_region_json_options(),
            'header' => get_string('addactivity', 'format_flexpage'),
            'body' => $this->output->render_addactivity(
                $this->new_url(array('sesskey' => sesskey(), 'action' => 'addactivity', 'add' => 1)),
                course_format_flexpage_lib_mod::get_add_options()
            ),
        ));
    }

    /**
     * Add Existing Activity Modal
     */
    public function addexistingactivity_action() {

        if (optional_param('add', 0, PARAM_BOOL)) {
            require_sesskey();

            $cmids  = optional_param('cmids', array(), PARAM_INT);
            $region = optional_param('region', false, PARAM_ACTION);

            if (!is_array($cmids)) {
                $cmids = array($cmids);
            }
            foreach ($cmids as $cmid) {
                course_format_flexpage_lib_moodlepage::add_activity_block($cmid, $region);
            }
        } else {
            echo json_encode((object) array(
                'args' => course_format_flexpage_lib_moodlepage::get_region_json_options(),
                'header' => get_string('addexistingactivity', 'format_flexpage'),
                'body' => $this->output->render_addexistingactivity(
                    $this->new_url(array('sesskey' => sesskey(), 'action' => 'addexistingactivity', 'add' => 1)),
                    course_format_flexpage_lib_mod::get_existing_options()
                ),
            ));
        }
    }

    /**
     * Add Block Modal
     */
    public function addblock_action() {
        global $COURSE;

        if (optional_param('add', 0, PARAM_BOOL)) {
            require_sesskey();

            $blockname = optional_param('blockname', '', PARAM_ACTION);
            $region    = optional_param('region', false, PARAM_ACTION);

            if (!empty($blockname)) {
                course_format_flexpage_lib_moodlepage::add_block($blockname, $COURSE->id, $region);
            }
        } else {
            echo json_encode((object) array(
                'args' => course_format_flexpage_lib_moodlepage::get_region_json_options(),
                'header' => get_string('addblock', 'format_flexpage'),
                'body' => $this->output->render_addblock(
                    $this->new_url(array('sesskey' => sesskey(), 'action' => 'addblock', 'add' => 1)),
                    course_format_flexpage_lib_moodlepage::get_add_block_options($COURSE->id)
                ),
            ));
        }
    }

    /**
     * Edit Page Modal
     */
    public function editpage_action() {
        global $CFG, $COURSE;

        $pageid   = required_param('pageid', PARAM_INT);
        $pagerepo = new course_format_flexpage_repository_page();
        $condrepo = new course_format_flexpage_repository_condition();
        $page     = $pagerepo->get_page($pageid);
        $pagerepo->set_page_region_widths($page);
        $condrepo->set_page_conditions($page);

        if (optional_param('edit', 0, PARAM_BOOL)) {
            require_sesskey();

            $page->set_options(array(
                'name' => required_param('name', PARAM_MULTILANG),
                'altname' => required_param('altname', PARAM_MULTILANG),
                'display' => required_param('display', PARAM_INT),
                'navigation' => required_param('navigation', PARAM_INT),
            ));

            $regions = optional_param('regions', array(), PARAM_INT);
            $pagerepo->save_page_region_widths($page, $regions);

            if (!empty($CFG->enableavailability)) {

                $page->set_options(array(
                    'releasecode' => required_param('releasecode', PARAM_ALPHANUM),
                    'showavailability' => required_param('showavailability', PARAM_INT),
                ));

                if (optional_param('enableavailablefrom', 0, PARAM_BOOL)) {
                    $availablefrom = required_param('availablefrom', PARAM_SAFEPATH);
                    $parts = explode('/', $availablefrom);
                    $parts = clean_param($parts, PARAM_INT);
                    $page->set_availablefrom(
                        make_timestamp($parts[2], $parts[0], $parts[1])
                    );
                } else {
                    $page->set_availablefrom(0);
                }
                if (optional_param('enableavailableuntil', 0, PARAM_BOOL)) {
                    $availableuntil = required_param('availableuntil', PARAM_SAFEPATH);
                    $parts = explode('/', $availableuntil);
                    $parts = clean_param($parts, PARAM_INT);
                    $page->set_availableuntil(
                        make_timestamp($parts[2], $parts[0], $parts[1], 23, 59, 59)
                    );
                } else {
                    $page->set_availableuntil(0);
                }

                $conditions   = array();
                $gradeitemids = optional_param('gradeitemids', array(), PARAM_INT);
                $mins         = optional_param('mins', array(), PARAM_FLOAT);
                $maxes        = optional_param('maxes', array(), PARAM_FLOAT);

                foreach ($gradeitemids as $key => $gradeitemid) {
                    if (empty($gradeitemid)) {
                        continue;
                    }
                    $min = $max = null;
                    if (array_key_exists($key, $mins)) {
                        $min = $mins[$key];
                    }
                    if (array_key_exists($key, $maxes)) {
                        $max = $maxes[$key];
                    }
                    $conditions[] = new condition_grade($gradeitemid, $min, $max);
                }
                $condrepo->save_page_grade_conditions($page, $conditions);

                $completion = new completion_info($COURSE);
                if ($completion->is_enabled()) {
                    $conditions = array();
                    $cmids = optional_param('cmids', array(), PARAM_INT);
                    $requiredcompletions = optional_param('requiredcompletions', array(), PARAM_INT);

                    foreach ($cmids as $key => $cmid) {
                        if (empty($cmid)) {
                            continue;
                        }
                        if (!array_key_exists($key, $requiredcompletions)) {
                            continue;
                        }
                        $conditions[] = new condition_completion($cmid, $requiredcompletions[$key]);
                    }
                    $condrepo->save_page_completion_conditions($page, $conditions);
                }
            }
            $pagerepo->save_page($page);
            format_flexpage_clear_cache();
        } else {
            echo json_encode((object) array(
                'header' => get_string('editpage', 'format_flexpage'),
                'body'   => $this->output->render_editpage(
                    $this->new_url(array('sesskey' => sesskey(), 'action' => 'editpage', 'pageid' => $page->get_id(), 'edit' => 1)),
                    $page,
                    course_format_flexpage_lib_moodlepage::get_regions()
                ),
            ));
        }
    }
}