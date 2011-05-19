<?php

require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

/**
 * @see course_format_flexpage_lib_box
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/box.php');

/**
 * Format Flexpage Renderer
 */
class format_flexpage_renderer extends plugin_renderer_base {
    /**
     * The javascript module used by the presentation layer
     *
     * @return array
     */
    public function get_js_module() {
        return array(
            'name'      => 'format_flexpage',
            'fullpath'  => '/course/format/flexpage/javascript.js',
            'requires'  => array(
                'base',
                'node',
                'event-custom',
                'json-parse',
                'yui2-yahoo',
                'yui2-dom',
                'yui2-event',
                'yui2-element',
                'yui2-button',
                'yui2-container',
                'yui2-menu',
                'yui2-calendar',
            ),
            'strings' => array(
                array('savechanges'),
                array('cancel'),
                array('choosedots'),
                array('close', 'format_flexpage'),
                array('addpages', 'format_flexpage'),
                array('genericasyncfail', 'format_flexpage'),
                array('error', 'format_flexpage'),
                array('movepage', 'format_flexpage'),
                array('addactivities', 'format_flexpage'),
                array('formnamerequired', 'format_flexpage'),
                array('deletepage', 'format_flexpage'),
            )
        );
    }

    public function pad_page_name(course_format_flexpage_model_page $page, $amount = null, $link = false) {
        $name = format_string($page->get_display_name(), true, $page->get_courseid());

        if ($link) {
            $name = html_writer::link(new moodle_url('/course/view.php', array('id' => $page->get_courseid(), 'pageid' => $page->get_id())), $name);
        }
        if (is_null($amount)) {
            $amount = format_flexpage_cache()->get_page_depth($page);
        }
        if ($amount == 0) {
            return $name;
        }
        return str_repeat('&nbsp;&nbsp;', $amount).'-&nbsp;'.$name;
    }

    /**
     * Render the action bar
     *
     * @param course_format_flexpage_lib_actionbar $actionbar
     * @return string
     */
    public function render_course_format_flexpage_lib_actionbar(course_format_flexpage_lib_actionbar $actionbar) {
        global $PAGE;

        $menus = array();

        foreach ($actionbar->get_menus() as $menu) {
            $menuobj = (object) array(
                'text' => $menu->get_name(),
                'submenu' => (object) array(
                    'id' => html_writer::random_id(),
                    'itemdata' => array(),
                )
            );
            foreach ($menu->get_actions() as $action) {
                if (!$action->get_visible()) {
                    continue;
                }
                $menuobj->submenu->itemdata[] = (object) array(
                    'text' => $action->get_name(),
                    'id' => $action->get_action(),
                    'url' => $action->get_url()->out(false),
                );
            }
            $menus[] = $menuobj;
        }

        $arguments = array($menus);

        $PAGE->requires->js_init_call('M.format_flexpage.init_actionbar', $arguments, false, $this->get_js_module());
        $menudiv = html_writer::tag('div', $this->render_actionbar_navigation(), array('id' => 'format_flexpage_actionbar_menu'));
        return html_writer::tag('div', $menudiv, array('id' => 'format_flexpage_actionbar'));
    }

    /**
     * Renders action bar's navigation
     *
     * @return string
     */
    public function render_actionbar_navigation() {
        $currentpage = format_flexpage_cache()->get_current_page();
        $options = array();
        foreach (format_flexpage_cache()->get_pages() as $page) {
            $options[$page->get_id()] = $this->pad_page_name($page);
        }

        if ($prevpage = format_flexpage_cache()->get_previous_page($currentpage)) {
            $previcon = new pix_icon('t/moveleft', get_string('gotoa', 'format_flexpage', format_string($prevpage->get_display_name())));
            $prevpage = $this->output->action_icon(new moodle_url('/course/view.php', array('id' => $prevpage->get_courseid(), 'pageid' => $prevpage->get_id())), $previcon);
            $prevpage = html_writer::tag('span', $prevpage, array('id' => 'format_flexpage_prevpage'));
        } else {
            $prevpage = '';
        }
        if ($nextpage = format_flexpage_cache()->get_next_page($currentpage)) {
            $nexticon = new pix_icon('t/removeright', get_string('gotoa', 'format_flexpage', format_string($nextpage->get_display_name())));
            $nextpage = $this->output->action_icon(new moodle_url('/course/view.php', array('id' => $nextpage->get_courseid(), 'pageid' => $nextpage->get_id())), $nexticon);
            $nextpage = html_writer::tag('span', $nextpage, array('id' => 'format_flexpage_nextpage'));
        } else {
            $nextpage = '';
        }
        $jumptopage = $this->output->single_select(
            new moodle_url('/course/view.php', array('id' => $currentpage->get_courseid())),
            'pageid', $options, $currentpage->get_id(), array(), 'jumptopageid'
        );
        $jumptopage = html_writer::tag('span', $jumptopage, array('id' => 'format_flexpage_jumptopage'));

        return html_writer::tag('div', $prevpage.$jumptopage.$nextpage, array('id' => 'format_flexpage_actionbar_nav'));
    }

    /**
     * Render a box
     *
     * @param course_format_flexpage_lib_box $box
     * @return string
     */
    public function render_course_format_flexpage_lib_box(course_format_flexpage_lib_box $box) {
        $rows = '';
        foreach ($box->get_rows() as $row) {
            $rows .= $this->render($row);
        }
        return html_writer::tag('div', $rows, $box->get_attributes());
    }

    /**
     * Render a box row
     *
     * @param course_format_flexpage_lib_box_row $row
     * @return string
     */
    public function render_course_format_flexpage_lib_box_row(course_format_flexpage_lib_box_row $row) {
        $cells = '';
        foreach ($row->get_cells() as $cell) {
            $cells .= $this->render($cell);
        }
        return html_writer::tag('div', $cells, $row->get_attributes());
    }

    /**
     * Render a box cell
     *
     * @param course_format_flexpage_lib_box_cell $cell
     * @return string
     */
    public function render_course_format_flexpage_lib_box_cell(course_format_flexpage_lib_box_cell $cell) {
        return html_writer::tag('div', $cell->get_contents(), $cell->get_attributes());
    }

    /**
     * @param course_format_flexpage_model_page[] $pages
     * @return string
     */
    public function render_page_available_info(array $pages) {
        $box = new course_format_flexpage_lib_box(array('class' => 'format_flexpage_page_availability'));
        foreach ($pages as $page) {
            $info = $page->is_available();
            if (is_string($info)) {
                $box->add_new_row()->add_new_cell(
                    html_writer::tag('div', format_string($page->get_display_name()), array('class' => 'format_flexpage_pagename')).
                    html_writer::tag('div', $info, array('class' => 'availabilityinfo'))
                );
            }
        }
        if (count($box->get_rows()) > 0) {
            return $this->output->box($this->render($box), 'generalbox boxwidthwide boxaligncenter');
        }
        return '';
    }

    public function render_addpages(moodle_url $url, array $pageoptions, array $moveoptions) {
        $elements   = array();
        $elements[] = html_writer::empty_tag('input', array('type' => 'text', 'name' => 'name[]'));
        $elements[] = html_writer::select($moveoptions, 'move[]', 'child', false);
        $elements[] = html_writer::select($pageoptions, 'referencepageid[]', '', false);

        $elements     = html_writer::tag('div', implode('&nbsp;&nbsp;', $elements), array('class' => 'format_flexpage_addpages_elements'));
        $addbutton    = html_writer::tag('button', '+', array('type' => 'button', 'value' => '+', 'id' => 'addpagebutton'));
        $copyelements = html_writer::tag('div', $elements, array('id' => 'addpagetemplate'));

        $box = new course_format_flexpage_lib_box();
        $box->add_new_row()->add_new_cell($elements, array('class' => 'format_flexpage_addpages_elements_row'))
                           ->add_new_cell($addbutton, array('class' => 'format_flexpage_add_button'));

        return html_writer::start_tag('form', array('method' => 'post', 'action' => $url->out_omit_querystring())).
               html_writer::input_hidden_params($url).
               html_writer::tag('div', $this->render($box), array('class' => 'format_flexpage_addpages_wrapper')).
               html_writer::end_tag('form').
               $copyelements;
    }

    public function render_movepage(course_format_flexpage_model_page $page, moodle_url $url, array $pageoptions, array $moveoptions) {
        $output  = html_writer::tag('span', get_string('movepagea', 'format_flexpage', format_string($page->get_display_name())), array('id' => 'format_flexpage_movingtext'));
        $output .= html_writer::select($moveoptions, 'move', 'child', false);
        $output .= html_writer::select($pageoptions, 'referencepageid', '', false);

        return html_writer::start_tag('form', array('method' => 'post', 'action' => $url->out_omit_querystring())).
               html_writer::input_hidden_params($url).
               html_writer::tag('div', $output, array('class' => 'format_flexpage_movepage_wrapper')).
               html_writer::end_tag('form');
    }

    public function render_addactivity(moodle_url $url, array $activities) {
        $box   = new course_format_flexpage_lib_box();
        $cell1 = new course_format_flexpage_lib_box_cell();
        $cell2 = new course_format_flexpage_lib_box_cell();
        foreach ($activities as $groupname => $modules) {
            $items = array();
            foreach ($modules as $addurl => $module) {
                $icon    = $this->output->pix_icon('icon', $module['label'], $module['module']);
                $items[] = html_writer::link(
                    new moodle_url($addurl),
                    $icon.' '.$module['label'],
                    array('class' => 'format_flexpage_addactivity_link')
                );
            }
            $title = html_writer::tag('div', "$groupname:", array('class' => 'format_flexpage_addactivity_heading'));
            $list  = html_writer::alist($items);
            $contents = html_writer::tag('div', $title.$list, array('class' => 'format_flexpage_addactivity_group'));

            // First group goes into cell1, rest into cell2
            if ($cell1->get_contents() === '') {
                $cell1->set_contents($contents);
            } else {
                $cell2->append_contents($contents);
            }
        }
        $box->add_new_row()->add_cell($cell1)->add_cell($cell2);

        return html_writer::start_tag('form', array('method' => 'post', 'action' => $url->out_omit_querystring())).
               html_writer::input_hidden_params($url).
               html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'region', 'value' => '')).
               html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'addurl', 'value' => '')).
               html_writer::tag('div', get_string('addto', 'format_flexpage'), array('class' => 'format_flexpage_addactivity_heading')).
               html_writer::tag('div', '', array('id' => 'format_flexpage_region_radios')).
               $this->render($box).
               html_writer::end_tag('form');
    }

    public function render_addexistingactivity(moodle_url $url, array $activities) {
        $checkboxes = '';
        foreach ($activities as $groupname => $modules) {
            $items = array();
            $icon  = false;
            foreach ($modules as $cmid => $module) {
                if (!$icon) {
                    $icon = $this->output->pix_icon('icon', $groupname, $module['module']);
                }
                $items[] = html_writer::checkbox('cmids[]', $cmid, false, '&nbsp;'.$module['label']);
            }
            $title = html_writer::tag('div', "$icon $groupname:", array('class' => 'format_flexpage_addactivity_heading'));
            $list  = html_writer::alist($items);
            $checkboxes .= html_writer::tag('div', $title.$list, array('class' => 'format_flexpage_addactivity_group'));

        }
        return html_writer::start_tag('form', array('method' => 'post', 'action' => $url->out_omit_querystring())).
               html_writer::input_hidden_params($url).
               html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'region', 'value' => '')).
               html_writer::tag('div', get_string('addto', 'format_flexpage'), array('class' => 'format_flexpage_addactivity_heading')).
               html_writer::tag('div', '', array('id' => 'format_flexpage_region_radios')).
               html_writer::tag('div', $checkboxes, array('class' => 'format_flexpage_existing_activity_list')).
               html_writer::end_tag('form');
    }

    public function render_addblock(moodle_url $url, array $blocks) {
        $form = html_writer::start_tag('form', array('method' => 'post', 'action' => $url->out_omit_querystring())).
                html_writer::input_hidden_params($url).
                html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'region', 'value' => '')).
                html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'blockname', 'value' => '')).
                html_writer::tag('div', get_string('addto', 'format_flexpage'), array('class' => 'format_flexpage_addactivity_heading')).
                html_writer::tag('div', '', array('id' => 'format_flexpage_region_radios')).
                html_writer::end_tag('form');

        $title = html_writer::tag('div', get_string('block', 'format_flexpage').':', array('class' => 'format_flexpage_addactivity_heading'));

        $box = new course_format_flexpage_lib_box();
        $box->add_new_row()->add_new_cell($form);
        $box->add_new_row()->add_new_cell($title);
        $row = $box->add_new_row(array('id' => 'format_flexpage_addblock_links'));

        $chunks = array_chunk($blocks, ceil(count($blocks) / 3), true);
        foreach ($chunks as $chunk) {
            $items = array();
            foreach ($chunk as $blockname => $blocktitle) {
                $link = clone($url);
                $link->param('blockname', $blockname);

                $items[] = html_writer::link($link, format_string($blocktitle), array('name' => $blockname));
            }
            $row->add_new_cell(html_writer::alist($items));
        }
        return $this->render($box);
    }

    /**
     * @param moodle_url $url
     * @param course_format_flexpage_model_page[] $pages
     * @param course_format_flexpage_lib_menu_action[] $actions
     * @return void
     */
    public function render_managepages(moodle_url $displayurl, array $pages, array $actions) {
        global $CFG, $PAGE;

        require($CFG->dirroot.'/local/mr/bootstrap.php');

        $displayopts = course_format_flexpage_model_page::get_display_options();

        $box = new course_format_flexpage_lib_box(array('class' => 'format_flexpage_box_managepages'));
        $row = $box->add_new_row(array('class' => 'format_flexpage_box_headers'));
        $row->add_new_cell(get_string('pagename', 'format_flexpage'))
            ->add_new_cell(get_string('managemenu', 'format_flexpage'))
            ->add_new_cell(get_string('display', 'format_flexpage'));

        foreach ($pages as $page) {
            $options = array();
            $selected = '';
            foreach ($displayopts as $option => $label) {
                $displayurl->params(array(
                    'pageid' => $page->get_id(),
                    'display' => $option,
                ));
                $options[$displayurl->out(false)] = $label;

                if ($option == $page->get_display()) {
                    $selected = $displayurl->out(false);
                }
            }

            $displayselect = html_writer::select($options, 'display', $selected, false, array(
                'id'    => html_writer::random_id(),
                'class' => 'format_flexpage_display_select'
            ));

            $options = array();
            foreach ($actions as $action) {
                if ($action->get_visible()) {
                    $url = $action->get_url();
                    $url->param('pageid', $page->get_id());

                    $option = json_encode((object) array(
                        'action' => $action->get_action(),
                        'url' => $url->out(false),
                    ));
                    $options[$option] = $action->get_name();
                }
            }
            $actionselect = html_writer::select($options, 'actions', '', false, array(
                'id'    => html_writer::random_id(),
                'class' => 'format_flexpage_actions_select'
            ));

            $pagename = html_writer::tag('div', $this->pad_page_name($page, null, true), array('id' => html_writer::random_id(), 'class' => 'format_flexpage_pagename'));

            if (!empty($CFG->enableavailability)) {
                $pagename .= html_writer::tag('div', $page->get_available_info(), array('class' => 'availabilityinfo'));
            }
            $row = $box->add_new_row(array('pageid' => $page->get_id()));
            $row->add_new_cell($pagename, array('class' => 'format_flexpage_name_cell'))
                ->add_new_cell($actionselect, array('id' => html_writer::random_id()))
                ->add_new_cell($displayselect, array('id' => html_writer::random_id(), 'class' => 'format_flexpage_display_cell'));
        }
        return $PAGE->get_renderer('local_mr')->render(new mr_html_notify('format_flexpage')).
               $this->render($box);
    }

    public function render_deletepage(moodle_url $url, course_format_flexpage_model_page $page) {
        $areyousure = get_string('areyousuredeletepage', 'format_flexpage', format_string($page->get_name()));

        return html_writer::start_tag('form', array('method' => 'post', 'action' => $url->out_omit_querystring())).
               html_writer::input_hidden_params($url).
               html_writer::tag('div', $areyousure, array('class' => 'format_flexpage_deletepage')).
               html_writer::end_tag('form');
    }

    public function render_editpage(moodle_url $url, course_format_flexpage_model_page $page, array $regions) {
        global $CFG, $COURSE;

        $navigationopts = course_format_flexpage_model_page::get_navigation_options();
        $displayopts    = course_format_flexpage_model_page::get_display_options();
        $templates      = '';
        $labelattr      = array('class' => 'format_flexpage_cell_label');

        $box = new course_format_flexpage_lib_box(array('class' => 'format_flexpage_editpage'));
        $box->add_new_row()->add_new_cell(html_writer::label(get_string('name', 'format_flexpage'), 'id_name'), $labelattr)
                           ->add_new_cell(html_writer::empty_tag('input', array('id' => 'id_name', 'name' => 'name', 'type' => 'text', 'size' => 50, 'value' => $page->get_name())));

        $box->add_new_row()->add_new_cell(html_writer::label(get_string('altname', 'format_flexpage'), 'id_altname'), $labelattr)
                           ->add_new_cell(html_writer::empty_tag('input', array('id' => 'id_altname', 'name' => 'altname', 'type' => 'text', 'size' => 50, 'value' => $page->get_altname())));

        $regioncell = new course_format_flexpage_lib_box_cell();
        $pagewidths = $page->get_region_widths();
        foreach ($regions as $region => $name) {
            $value = '';
            if (array_key_exists($region, $pagewidths)) {
                $value = $pagewidths[$region];
            }
            $regioncell->append_contents(
                html_writer::tag('span',
                    html_writer::empty_tag('input', array('id' => "id_region_$region", 'name' => "regions[$region]", 'type' => 'text', 'size' => 4, 'value' => $value)).
                    html_writer::label("&nbsp;$name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", "id_region_$region")
                )
            );
        }
        $box->add_new_row()->add_new_cell(get_string('regrionwidths', 'format_flexpage'), $labelattr)
                           ->add_cell($regioncell);

        $box->add_new_row()->add_new_cell(html_writer::label(get_string('display', 'format_flexpage'), 'id_display'), $labelattr)
                           ->add_new_cell(html_writer::select($displayopts, 'display', $page->get_display(), false, array('id' => 'id_display')));

        $box->add_new_row()->add_new_cell(html_writer::label(get_string('navigation', 'format_flexpage'), 'id_navigation'), $labelattr)
                           ->add_new_cell(html_writer::select($navigationopts, 'navigation', $page->get_navigation(), false, array('id' => 'id_navigation')));

        if (!empty($CFG->enableavailability)) {
            $box->add_new_row()->add_new_cell(get_string('availablefrom', 'condition'), $labelattr)
                               ->add_new_cell($this->render_calendar('availablefrom', $page->get_availablefrom()));

            $box->add_new_row()->add_new_cell(get_string('availableuntil', 'condition'), $labelattr)
                               ->add_new_cell($this->render_calendar('availableuntil', $page->get_availableuntil()));

            $box->add_new_row()->add_new_cell(html_writer::label(get_string('releasecode', 'local_mrooms'), 'id_releasecode'), $labelattr)
                               ->add_new_cell(html_writer::empty_tag('input', array('id' => 'id_releasecode', 'type' => 'text', 'name' => 'releasecode', 'maxlength' => 50, 'size' => 50, 'value' => $page->get_releasecode())));

            $box->add_new_row()->add_new_cell(get_string('gradecondition', 'condition'), $labelattr)
                               ->add_new_cell($this->render_conditions($page, 'condition_grade'));

            $templates = $this->render_condition_grade();

            $completion = new completion_info($COURSE);
            if ($completion->is_enabled()) {
                $box->add_new_row()->add_new_cell(get_string('completioncondition', 'condition'), $labelattr)
                                   ->add_new_cell($this->render_conditions($page, 'condition_completion'));

                $templates .= $this->render_condition_completion();
            }
            $showopts = array(
                CONDITION_STUDENTVIEW_SHOW => get_string('showavailability_show', 'condition'),
                CONDITION_STUDENTVIEW_HIDE => get_string('showavailability_hide', 'condition')
            );
            $box->add_new_row()->add_new_cell(html_writer::label(get_string('showavailability', 'condition'), 'id_showavailability'), $labelattr)
                               ->add_new_cell(html_writer::select($showopts, 'showavailability', $page->get_showavailability(), false, array('id' => 'id_showavailability')));
        }

        return html_writer::start_tag('form', array('method' => 'post', 'action' => $url->out_omit_querystring())).
               html_writer::input_hidden_params($url).
               $this->render($box).
               html_writer::end_tag('form').
               html_writer::tag('div', $templates, array('id' => 'condition_templates'));
    }

    public function render_conditions(course_format_flexpage_model_page $page, $conditionclass) {
        $renderfunc = 'render_'.$conditionclass;
        $conditions = $page->get_conditions();

        if (!is_null($conditions)) {
            $conditions = $conditions->get_conditions($conditionclass);
        }

        // Render a blank one if none exist
        if (empty($conditions)) {
            $conditions = array(null);
        }
        $condbox  = new course_format_flexpage_lib_box(array('class' => 'format_flexpage_conditions'));
        $condcell = new course_format_flexpage_lib_box_cell();
        $condcell->set_attributes(array('id' => $conditionclass.'s'));
        $condadd = html_writer::tag('button', '+', array('type' => 'button', 'value' => '+', 'id' => $conditionclass.'_add_button'));

        foreach ($conditions as $condition) {
            $condcell->append_contents(
                $this->$renderfunc($condition)
            );
        }
        $condbox->add_new_row()->add_cell($condcell)
                               ->add_new_cell($condadd, array('class' => 'format_flexpage_add_button'));

        return $this->render($condbox);
    }

    public function render_condition_grade(condition_grade $condition = null) {
        global $CFG, $COURSE;

        require_once($CFG->libdir.'/gradelib.php');

        // Static so we only build it once...
        static $gradeoptions = null;

        if (is_null($condition)) {
            $gradeitemid = 0;
            $min = '';
            $max = '';
        } else {
            $gradeitemid = $condition->get_gradeitemid();
            $min = rtrim(rtrim($condition->get_min(),'0'),'.');
            $max = rtrim(rtrim($condition->get_max(),'0'),'.');
        }
        if (is_null($gradeoptions)) {
            $gradeoptions = array();
            if ($items = grade_item::fetch_all(array('courseid'=> $COURSE->id))) {
                foreach($items as $id => $item) {
                    $gradeoptions[$id] = $item->get_name();
                }
            }
            asort($gradeoptions);
            $gradeoptions = array(0 => get_string('none', 'condition')) + $gradeoptions;
        }
        $elements = html_writer::select($gradeoptions, 'gradeitemids[]', $gradeitemid, false).
                    ' '.get_string('grade_atleast','condition').' '.
                    html_writer::empty_tag('input', array('name' => 'mins[]', 'size' => 3, 'type' => 'text', 'value' => $min)).
                    '% '.get_string('grade_upto','condition').' '.
                    html_writer::empty_tag('input', array('name' => 'maxes[]', 'size' => 3, 'type' => 'text', 'value' => $max)).
                    '%';

        return html_writer::tag('div', $elements, array('class' => 'format_flexpage_condition_grade'));
    }

    public function render_condition_completion(condition_completion $condition = null) {
        global $COURSE;

        static $completionoptions = null;

        if (is_null($condition)) {
            $cmid = 0;
            $requiredcompletion = '';
        } else {
            $cmid = $condition->get_cmid();
            $requiredcompletion = $condition->get_requiredcompletion();
        }
        if (is_null($completionoptions)) {
            $completionoptions = array();
            $modinfo = get_fast_modinfo($COURSE);
            foreach($modinfo->get_cms() as $id => $cm) {
                if ($cm->completion) {
                    $completionoptions[$id] = $cm->name;
                }
            }
            asort($completionoptions);
            $completionoptions = array(0 => get_string('none', 'condition')) + $completionoptions;
        }
        $completionvalues=array(
            COMPLETION_COMPLETE      => get_string('completion_complete','condition'),
            COMPLETION_INCOMPLETE    => get_string('completion_incomplete','condition'),
            COMPLETION_COMPLETE_PASS => get_string('completion_pass','condition'),
            COMPLETION_COMPLETE_FAIL => get_string('completion_fail','condition'),
        );
        $elements = html_writer::select($completionoptions, 'cmids[]', $cmid, false).'&nbsp;'.
                    html_writer::select($completionvalues, 'requiredcompletions[]', $requiredcompletion, false);

        return html_writer::tag('div', $elements, array('class' => 'format_flexpage_condition_completion'));
    }

    public function render_calendar($name, $defaulttime = 0, $optional = true) {
        if ($defaulttime > 0) {
            $value = date('m/d/Y', $defaulttime);
        } else {
            $value = date('m/d/Y');
        }

        $output = '';
        $attributes  = array('id' => "calendar$name");
        if ($optional) {
            $output = html_writer::checkbox("enable$name", 1, ($defaulttime > 0), '&nbsp;'.get_string('enable'));
            $output = html_writer::tag('div', $output);
            $attributes['class'] = 'hiddenifjs';
        }
        $output .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => $name, 'value' => $value)).
                   html_writer::tag('div', '', $attributes);

        return $output;
    }
}