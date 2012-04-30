<?php
/**
 * @see course_format_flexpage_repository_page
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/page.php');

/**
 * @see course_format_flexpage_repository_condition
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/condition.php');

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
 *
 * @author Mark Nielsen
 * @package format_flexpage
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

    /**
     * Must have manage pages for all and then also
     * check block and activity capabilities for those actions.
     *
     * @return void
     */
    public function require_capability() {
        require_capability('format/flexpage:managepages', $this->get_context());

        switch ($this->action) {
            case 'addblock':
                require_capability('moodle/site:manageblocks', $this->get_context());
                break;
            case 'addexistingactivity':
            case 'addactivity':
                require_capability('moodle/course:manageactivities', $this->get_context());
                break;
        }
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

            $names = optional_param_array('name', array(), PARAM_MULTILANG);
            $moves = optional_param_array('move', array(), PARAM_ACTION);
            $copypageids = optional_param_array('copypageid', array(), PARAM_INT);
            $referencepageids = optional_param_array('referencepageid', array(), PARAM_INT);

            // Add pages in reverse order, will match user expectations better
            $names = array_reverse($names, true);
            $moves = array_reverse($moves, true);
            $referencepageids = array_reverse($referencepageids, true);

            $pagerepo   = new course_format_flexpage_repository_page();
            $condrepo   = new course_format_flexpage_repository_condition();
            $addedpages = array();
            foreach ($names as $key => $name) {
                $name = trim($name);

                // Required values...
                if (empty($name) or empty($moves[$key]) or empty($referencepageids[$key])) {
                    continue;
                }
                if (!empty($copypageids[$key])) {
                    $copypage = $pagerepo->get_page($copypageids[$key]);
                    $page     = clone($copypage);
                } else {
                    $copypage = null;
                    $page     = new course_format_flexpage_model_page();
                }

                $addedpages[] = format_string($name);

                $page->set_id(null)
                     ->set_name($name)
                     ->set_courseid($COURSE->id);

                $pagerepo->move_page($page, $moves[$key], $referencepageids[$key])
                         ->save_page($page);

                // Copy widths, conditions and blocks
                if ($copypage instanceof course_format_flexpage_model_page) {
                    $pagerepo->save_page_region_widths($page, $pagerepo->get_page_region_widths($copypage));
                    $condrepo->save_page_conditions($page, $condrepo->get_page_condtions($copypage));
                    course_format_flexpage_lib_moodlepage::copy_page_blocks($copypage, $page);
                }
            }
            format_flexpage_clear_cache();

            if (!empty($addedpages)) {
                $this->notify->good('addedpages', implode(', ', array_reverse($addedpages)));
            }
        } else {
            $pageoptions = array();
            foreach (format_flexpage_cache()->get_pages() as $page) {
                $pageoptions[$page->get_id()] = $this->output->pad_page_name($page, true);
            }
            $copyoptions = array(0 => get_string('copydotdotdot', 'format_flexpage')) + $pageoptions;
            $moveoptions = course_format_flexpage_model_page::get_move_options();

            $submiturl = $this->new_url(array('sesskey' => sesskey(), 'action' => 'addpages', 'add' => 1));

            echo json_encode((object) array(
                'header' => $this->output->flexpage_help_icon('addpages'),
                'body' => $this->output->add_pages($submiturl, $pageoptions, $moveoptions, $copyoptions),
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

        if (optional_param('confirmmove', 0, PARAM_BOOL)) {
            require_sesskey();

            $move = required_param('move', PARAM_ACTION);
            $referencepageid = required_param('referencepageid', PARAM_INT);

            $repo->move_page($movepage, $move, $referencepageid)
                 ->save_page($movepage);

            format_flexpage_clear_cache();

            $refpage = $repo->get_page($referencepageid);

            $this->notify->good('movedpage', (object) array(
                'movepage' => format_string($movepage->get_name()),
                'move' => $moveoptions[$move],
                'refpage' => format_string($refpage->get_name()),
            ));
        } else {
            $pageoptions = array();
            foreach (format_flexpage_cache()->get_pages() as $page) {
                // Skip the page that we are moving and any of its children
                if ($page->get_id() == $movepage->get_id() or format_flexpage_cache()->is_child_page($movepage, $page)) {
                    continue;
                }
                $pageoptions[$page->get_id()] = $this->output->pad_page_name($page, true);
            }
            $submiturl = $this->new_url(array('sesskey' => sesskey(), 'action' => 'movepage', 'pageid' => $pageid, 'confirmmove' => 1));

            echo json_encode((object) array(
                'header' => get_string('movepage', 'format_flexpage'),
                'body' => $this->output->move_page($movepage, $submiturl, $pageoptions, $moveoptions),
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
            'header' => $this->output->flexpage_help_icon('addactivity'),
            'body' => $this->output->add_activity(
                $this->new_url(array('sesskey' => sesskey(), 'action' => 'addactivity', 'add' => 1)),
                course_format_flexpage_lib_mod::get_add_options()
            ),
        ));
    }

    /**
     * Add Existing Activity Modal
     */
    public function addexistingactivity_action() {

        $pageid = required_param('pageid', PARAM_INT);
        $repo   = new course_format_flexpage_repository_page();
        $page   = $repo->get_page($pageid);

        if (optional_param('add', 0, PARAM_BOOL)) {
            require_sesskey();

            $cmids  = optional_param_array('cmids', array(), PARAM_INT);
            $region = optional_param('region', false, PARAM_ACTION);

            if (!is_array($cmids)) {
                $cmids = array($cmids);
            }
            foreach ($cmids as $cmid) {
                course_format_flexpage_lib_moodlepage::add_activity_block($page, $cmid, $region);
            }
        } else {
            echo json_encode((object) array(
                'args' => course_format_flexpage_lib_moodlepage::get_region_json_options(),
                'header' => $this->output->flexpage_help_icon('addexistingactivity'),
                'body' => $this->output->add_existing_activity(
                    $this->new_url(array('sesskey' => sesskey(), 'action' => 'addexistingactivity', 'pageid' => $page->get_id(), 'add' => 1)),
                    course_format_flexpage_lib_mod::get_existing_options()
                ),
            ));
        }
    }

    /**
     * Add Block Modal
     */
    public function addblock_action() {

        $pageid = required_param('pageid', PARAM_INT);
        $repo   = new course_format_flexpage_repository_page();
        $page   = $repo->get_page($pageid);

        if (optional_param('add', 0, PARAM_BOOL)) {
            require_sesskey();

            $blockname = optional_param('blockname', '', PARAM_ACTION);
            $region    = optional_param('region', false, PARAM_ACTION);

            if (!empty($blockname)) {
                course_format_flexpage_lib_moodlepage::add_block($page, $blockname, $region);
            }
        } else {
            echo json_encode((object) array(
                'args' => course_format_flexpage_lib_moodlepage::get_region_json_options(),
                'header' => $this->output->flexpage_help_icon('addblock'),
                'body' => $this->output->add_block(
                    $this->new_url(array('sesskey' => sesskey(), 'action' => 'addblock', 'pageid' => $page->get_id(), 'add' => 1)),
                    course_format_flexpage_lib_moodlepage::get_add_block_options($page)
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

            $name = required_param('name', PARAM_MULTILANG);
            $name = trim($name);

            if (empty($name)) {
                throw new coding_exception('Name cannot be blank');
            }
            $page->set_options(array(
                'name' => $name,
                'display' => required_param('display', PARAM_INT),
                'navigation' => required_param('navigation', PARAM_INT),
            ));

            $regions = optional_param_array('regions', array(), PARAM_INT);
            $pagerepo->save_page_region_widths($page, $regions);

            if (!empty($CFG->enableavailability)) {

                $page->set_options(array(
                    'releasecode' => required_param('releasecode', PARAM_ALPHANUM),
                    'showavailability' => required_param('showavailability', PARAM_INT),
                ));

                if (optional_param('enableavailablefrom', 0, PARAM_BOOL)) {
                    $availablefrom = required_param('availablefrom', PARAM_SAFEPATH);
                    $parts = explode('/', $availablefrom);
                    $parts = clean_param_array($parts, PARAM_INT);
                    $page->set_availablefrom(
                        make_timestamp($parts[2], $parts[0], $parts[1])
                    );
                } else {
                    $page->set_availablefrom(0);
                }
                if (optional_param('enableavailableuntil', 0, PARAM_BOOL)) {
                    $availableuntil = required_param('availableuntil', PARAM_SAFEPATH);
                    $parts = explode('/', $availableuntil);
                    $parts = clean_param_array($parts, PARAM_INT);
                    $page->set_availableuntil(
                        make_timestamp($parts[2], $parts[0], $parts[1], 23, 59, 59)
                    );
                } else {
                    $page->set_availableuntil(0);
                }

                $conditions   = array();
                $gradeitemids = optional_param_array('gradeitemids', array(), PARAM_INT);
                $mins         = optional_param_array('mins', array(), PARAM_FLOAT);
                $maxes        = optional_param_array('maxes', array(), PARAM_FLOAT);

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
                    $cmids = optional_param_array('cmids', array(), PARAM_INT);
                    $requiredcompletions = optional_param_array('requiredcompletions', array(), PARAM_INT);

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
                'body'   => $this->output->edit_page(
                    $this->new_url(array('sesskey' => sesskey(), 'action' => 'editpage', 'pageid' => $page->get_id(), 'edit' => 1)),
                    $page,
                    course_format_flexpage_lib_moodlepage::get_regions()
                ),
            ));
        }
    }

    /**
     * Manage Page Modal
     */
    public function managepages_action() {
        global $CFG;

        require_once($CFG->dirroot.'/course/format/flexpage/lib/actionbar.php');

        $actionbar = course_format_flexpage_lib_actionbar::factory();
        $menu      = $actionbar->get_menu('manage');
        $actions   = array();
        foreach (array('editpage', 'movepage', 'deletepage') as $actionname) {
            $action = $menu->get_action($actionname);
            if ($action->get_visible()) {
                $actions[$actionname] = $action;
            }
        }
        echo json_encode((object) array(
            'header' => $this->output->flexpage_help_icon('managepages'),
            'body'   => $this->output->manage_pages(
                $this->new_url(),
                format_flexpage_cache()->get_pages(),
                $actions
            ),
        ));
    }

    /**
     * Delete Page Modal
     */
    public function deletepage_action() {
        $pageid  = required_param('pageid', PARAM_INT);

        $repo = new course_format_flexpage_repository_page();
        $page = $repo->get_page($pageid);

        if (optional_param('delete', 0, PARAM_BOOL)) {
            $repo->delete_page($page);
            $this->notify->good('deletedpagex', format_string($page->get_name()));
            format_flexpage_clear_cache();
        } else {
            echo json_encode((object) array(
                'header' => get_string('deletepage', 'format_flexpage'),
                'body'   => $this->output->delete_page(
                    $this->new_url(array('sesskey' => sesskey(), 'action' => 'deletepage', 'delete' => 1, 'pageid' => $page->get_id())),
                    $page
                ),
            ));
        }
    }

    /**
     * Allows the setting the display value of a page
     */
    public function setdisplay_action() {
        require_sesskey();

        $pageid  = required_param('pageid', PARAM_INT);
        $display = required_param('display', PARAM_INT);

        $repo = new course_format_flexpage_repository_page();
        $page = $repo->get_page($pageid);
        $page->set_display($display);
        $repo->save_page($page);
        format_flexpage_clear_cache();
    }

    /**
     * Allows the setting the navigation value of a page
     */
    public function setnavigation_action() {
        require_sesskey();

        $pageid     = required_param('pageid', PARAM_INT);
        $navigation = required_param('navigation', PARAM_INT);

        $repo = new course_format_flexpage_repository_page();
        $page = $repo->get_page($pageid);
        $page->set_navigation($navigation);
        $repo->save_page($page);
        format_flexpage_clear_cache();
    }
}