<?php
/**
 * @see course_format_flexpage_repository_page
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/page.php');

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
        global $CFG, $SESSION;

        require_once($CFG->dirroot.'/course/format/flexpage/lib/mod.php');
        require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

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
        global $CFG;

        require_once($CFG->dirroot.'/course/format/flexpage/lib/mod.php');
        require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

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
        global $CFG, $COURSE;

        require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

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
}