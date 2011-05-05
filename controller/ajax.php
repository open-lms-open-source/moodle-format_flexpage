<?php
/**
 * @see course_format_flexpage_repository_cache
 */
require_once($CFG->dirroot.'/course/format/flexpage/repository/cache.php');

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

    /**
     * Set's errors through mr_notify
     *
     * @param Exception $e
     * @return void
     */
    public function exception_handler($e) {
        $this->notify->bad('ajaxexception', $e->getMessage());
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
            if (!empty($addedpages)) {
                $this->notify->good('addedpages', implode(', ', array_reverse($addedpages)));
            }
        } else {
            $repo = new course_format_flexpage_repository_cache();
            $pageoptions = array();
            foreach ($repo->get_cache()->get_pages() as $page) {
                $pageoptions[$page->get_id()] = $this->output->pad_page_name($page);
            }
            $moveoptions = course_format_flexpage_model_page::get_move_options();

            $submiturl = $this->new_url(array('sesskey' => sesskey(), 'action' => 'addpages', 'add' => 1));

            echo json_encode((object) array(
                'header' => get_string('addpagesaction', 'format_flexpage'),
                'body' => $this->output->render_addpages($submiturl, $pageoptions, $moveoptions),
            ));
        }
    }
}