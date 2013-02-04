<?php
/**
 * Flexpage
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://opensource.org/licenses/gpl-3.0.html.
 *
 * @copyright Copyright (c) 2009 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @package format_flexpage
 * @author Mark Nielsen
 */

require($CFG->dirroot.'/local/mr/bootstrap.php');
require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

/**
 * @see course_format_flexpage_lib_box
 */
require_once(__DIR__.'/lib/box.php');

/**
 * @see course_format_flexpage_lib_condition
 */
require_once(__DIR__.'/lib/condition.php');

/**
 * Format Flexpage Renderer
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class format_flexpage_renderer extends plugin_renderer_base {

    public function __construct(moodle_page $page, $target) {
        global $SCRIPT;

        parent::__construct($page, $target);

        // 2nd part of hack, re-set page layout as it is overridden in course/view.php
        // See first part in format_flexpage::page_set_course
        $subpage = $page->subpage; // Due to magic methods...
        if ($SCRIPT == '/course/view.php' and !empty($subpage)) {
            $page->set_pagelayout('format_flexpage');
        }
    }

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
                'querystring',
                'yui2-yahoo',
                'yui2-dom',
                'yui2-connection',
                'yui2-dragdrop',
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
                array('deletemodwarn', 'format_flexpage'),
                array('continuedotdotdot', 'format_flexpage'),
                array('warning', 'format_flexpage'),
                array('actionbar', 'format_flexpage'),
                array('actionbar_help', 'format_flexpage'),
            )
        );
    }

    /**
     * Pads a page's name with spaces and a hyphen based on hierarchy depth or passed amount
     *
     * @param course_format_flexpage_model_page $page
     * @param null|int|boolean $length Shorten page name to this length (Pass true to use default length)
     * @param bool $link To link the page name or not
     * @param null|int $amount Amount of padding
     * @return string
     */
    public function pad_page_name(course_format_flexpage_model_page $page, $length = null, $link = false, $amount = null) {
        $name = format_string($page->get_name(), true, $page->get_courseid());

        if (!is_null($length)) {
            if ($length === true) {
                $length = 30;
            }
            $name = shorten_text($name, $length);
        }
        if ($link) {
            $name = html_writer::link($page->get_url(), $name);
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
     * Generates a help icon with specific class wrapped around it
     *
     * @param string $identifier
     * @param string $component
     * @param bool $showlabel
     * @return string
     */
    public function flexpage_help_icon($identifier, $component = 'format_flexpage', $showlabel = true) {
        $help = html_writer::tag('span', $this->help_icon($identifier, $component), array('class' => 'format_flexpage_helpicon'));

        if ($showlabel) {
            $help = get_string($identifier, $component)."&nbsp;$help";
        }
        return $help;
    }

    /**
     * Render the action bar
     *
     * @param course_format_flexpage_lib_actionbar $actionbar
     * @return string
     */
    public function render_course_format_flexpage_lib_actionbar(course_format_flexpage_lib_actionbar $actionbar) {
        global $PAGE;

        /** @var $renderer block_flexpagenav_renderer */
        $renderer = $PAGE->get_renderer('block_flexpagenav');

        $PAGE->requires->js_init_call('M.format_flexpage.init_actionbar', null, false, $this->get_js_module());
        $PAGE->requires->js_init_call('M.format_flexpage.init_flexpagenav_actionbar', null, false, $renderer->get_js_module());

        $content  = html_writer::start_tag('div', array('id' => 'custommenu', 'class' => 'format_flexpage_actionbar'));
        $content .= html_writer::start_tag('div', array('id' => 'format_flexpage_actionbar', 'class' => 'yui3-menu yui3-menu-horizontal javascript-disabled'));
        $content .= html_writer::start_tag('div', array('class'=>'yui3-menu-content'));
        $content .= $this->actionbar_navigation();
        $content .= html_writer::start_tag('ul', array('id' => 'format_flexpage_actionbar_ul'));

        foreach ($actionbar->get_menus() as $menu) {
            $menuitem = new custom_menu_item($menu->get_name());
            foreach ($menu->get_actions() as $action) {
                if (!$action->get_visible()) {
                    continue;
                }
                $menuitem->add($action->get_name(), $action->get_url());
            }
            $content .= $this->render($menuitem);
        }
        $content .= html_writer::end_tag('ul');
        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('div');

        return $content;
    }

    /**
     * Renders action bar's navigation
     *
     * @return string
     */
    public function actionbar_navigation() {
        $currentpage = format_flexpage_cache()->get_current_page();
        $options = array();
        foreach (format_flexpage_cache()->get_pages() as $page) {
            $options[$page->get_id()] = $this->pad_page_name($page, true);
        }

        if ($prevpage = format_flexpage_cache()->get_previous_page($currentpage)) {
            $previcon = new pix_icon('t/moveleft', get_string('gotoa', 'format_flexpage', format_string($prevpage->get_name())));
            $prevpage = $this->output->action_icon($prevpage->get_url(), $previcon);
            $prevpage = html_writer::tag('span', $prevpage, array('id' => 'format_flexpage_prevpage'));
        } else {
            $prevpage = '';
        }
        if ($nextpage = format_flexpage_cache()->get_next_page($currentpage)) {
            $nexticon = new pix_icon('t/removeright', get_string('gotoa', 'format_flexpage', format_string($nextpage->get_name())));
            $nextpage = $this->output->action_icon($nextpage->get_url(), $nexticon);
            $nextpage = html_writer::tag('span', $nextpage, array('id' => 'format_flexpage_nextpage'));
        } else {
            $nextpage = '';
        }
        $jumptopage = $this->output->single_select(
            new moodle_url('/course/view.php', array('id' => $currentpage->get_courseid())),
            'pageid', $options, $currentpage->get_id(), array(), 'jumptopageid'
        );
        $jumptopage = html_writer::tag('span', $jumptopage, array('id' => 'format_flexpage_jumptopage'));
        $helpicon   = $this->pix_icon('help', get_string('help'), 'moodle', array('id' => 'format_flexpage_actionbar_help'));
        $helpicon   = html_writer::tag('span', $helpicon);

        return html_writer::tag('div', $prevpage.$jumptopage.$nextpage.$helpicon, array('id' => 'format_flexpage_actionbar_nav'));
    }

    /**
     * Render a box
     *
     * @param course_format_flexpage_lib_box $box
     * @return string
     */
    public function render_course_format_flexpage_lib_box(course_format_flexpage_lib_box $box) {
        $rows = '';
        foreach ($box->get_rows() as $key => $row) {
            $row->append_attribute('class', "row$key")
                ->append_attribute('class', 'oddeven'.($key % 2));

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
        foreach ($row->get_cells() as $key => $cell) {
            $cell->append_attribute('class', "cell$key")
                 ->append_attribute('class', 'oddeven'.($key % 2));

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
     * Render page availability information
     *
     * @param course_format_flexpage_model_page[] $pages
     * @param course_format_flexpage_model_cache $cache
     * @return string
     */
    public function page_available_info(array $pages, course_format_flexpage_model_cache $cache) {
        $box = new course_format_flexpage_lib_box(array('class' => 'format_flexpage_page_availability'));
        foreach ($pages as $page) {
            $info = $cache->is_page_available($page);
            if (is_string($info)) {
                $box->add_new_row()->add_new_cell(
                    html_writer::tag('div', format_string($page->get_name()), array('class' => 'format_flexpage_pagename')).
                    html_writer::tag('div', $info, array('class' => 'availabilityinfo'))
                );
            }
        }
        if (count($box->get_rows()) > 0) {
            return $this->output->box($this->render($box), 'generalbox boxwidthwide boxaligncenter');
        }
        return '';
    }

    /**
     * Add pages UI
     *
     * @param moodle_url $url
     * @param array $pageoptions An array of available pages
     * @param array $moveoptions Page move options
     * @param array $copyoptions An array of copy page options
     * @return string
     */
    public function add_pages(moodle_url $url, array $pageoptions, array $moveoptions, array $copyoptions) {
        $elements   = array();
        $elements[] = html_writer::empty_tag('input', array('type' => 'text', 'name' => 'name[]'));
        $elements[] = html_writer::select($moveoptions, 'move[]', 'child', false);
        $elements[] = html_writer::select($pageoptions, 'referencepageid[]', '', false);
        $elements[] = html_writer::select($copyoptions, 'copypageid[]', '', false);

        $elements     = html_writer::tag('div', implode('&nbsp;&nbsp;', $elements), array('class' => 'format_flexpage_addpages_elements'));
        $addbutton    = html_writer::tag('button', '+', array('type' => 'button', 'value' => '+', 'id' => 'addpagebutton'));
        $copyelements = html_writer::tag('div', $elements, array('id' => 'format_flexpage_addpages_template'));

        $box = new course_format_flexpage_lib_box();
        $box->add_new_row()->add_new_cell($elements, array('class' => 'format_flexpage_addpages_elements_row'))
                           ->add_new_cell($addbutton, array('class' => 'format_flexpage_add_button'));

        return html_writer::start_tag('form', array('method' => 'post', 'action' => $url->out_omit_querystring())).
               html_writer::input_hidden_params($url).
               html_writer::tag('div', $this->render($box), array('class' => 'format_flexpage_addpages_wrapper')).
               html_writer::end_tag('form').
               $copyelements;
    }

    /**
     * Move page UI
     *
     * @param course_format_flexpage_model_page $page
     * @param moodle_url $url
     * @param array $pageoptions An array of available pages
     * @param array $moveoptions Page move options
     * @return string
     */
    public function move_page(course_format_flexpage_model_page $page, moodle_url $url, array $pageoptions, array $moveoptions) {
        if (empty($pageoptions)) {
            return html_writer::tag('div', get_string('nomoveoptionserror', 'format_flexpage'), array('class' => 'format_flexpage_movepage_nooptions'));
        }
        $output  = html_writer::tag('span', get_string('movepagea', 'format_flexpage', format_string($page->get_name())), array('id' => 'format_flexpage_movingtext'));
        $output .= html_writer::select($moveoptions, 'move', 'child', false);
        $output .= html_writer::select($pageoptions, 'referencepageid', '', false);

        return html_writer::start_tag('form', array('method' => 'post', 'action' => $url->out_omit_querystring())).
               html_writer::input_hidden_params($url).
               html_writer::tag('div', $output, array('class' => 'format_flexpage_movepage_wrapper')).
               html_writer::end_tag('form');
    }

    /**
     * Add activity UI
     *
     * @param moodle_url $url
     * @param array $activities Available activities to add, grouped by a group name
     * @return string
     */
    public function add_activity(moodle_url $url, array $activities) {
        $sm    = get_string_manager();
        $box   = new course_format_flexpage_lib_box();
        $cell1 = new course_format_flexpage_lib_box_cell();
        $cell2 = new course_format_flexpage_lib_box_cell();
        foreach ($activities as $groupname => $modules) {
            $items = array();
            foreach ($modules as $addurl => $module) {
                if ($sm->string_exists('modulename_help', $module['module'])) {
                    $title = trim(html_to_text(get_string('modulename_help', $module['module'])));
                } else {
                    $title = '';
                }
                $icon    = $this->output->pix_icon('icon', $module['label'], $module['module']);
                $items[] = html_writer::link(
                    new moodle_url($addurl),
                    $icon.' '.$module['label'],
                    array('class' => 'format_flexpage_addactivity_link', 'title' => $title)
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

    /**
     * Add existing activity UI
     *
     * @param moodle_url $url
     * @param array $activities  An array of existing activities, grouped by a group name
     * @return string
     */
    public function add_existing_activity(moodle_url $url, array $activities) {
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

    /**
     * Add block UI
     *
     * @param moodle_url $url
     * @param array $blocks List of available blocks to add
     * @return string
     */
    public function add_block(moodle_url $url, array $blocks) {
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
     * Manage pages UI
     *
     * @param moodle_url $url
     * @param course_format_flexpage_model_page[] $pages
     * @param course_format_flexpage_lib_menu_action[] $actions Actions to take on pages
     * @return string
     */
    public function manage_pages(moodle_url $url, array $pages, array $actions) {
        global $CFG, $PAGE;

        require($CFG->dirroot.'/local/mr/bootstrap.php');

        $displayopts    = course_format_flexpage_model_page::get_display_options();
        $navigationopts = course_format_flexpage_model_page::get_navigation_options();

        $box = new course_format_flexpage_lib_box(array('class' => 'format_flexpage_box_managepages'));
        $row = $box->add_new_row(array('class' => 'format_flexpage_box_headers'));
        $row->add_new_cell(get_string('pagename', 'format_flexpage'))
            ->add_new_cell(get_string('managemenu', 'format_flexpage'))
            ->add_new_cell($this->flexpage_help_icon('display'))
            ->add_new_cell($this->flexpage_help_icon('navigation'));

        foreach ($pages as $page) {
            $options = array();
            $selected = '';
            foreach ($displayopts as $option => $label) {
                $displayurl = clone($url);
                $displayurl->params(array(
                    'sesskey' => sesskey(),
                    'action'  => 'setdisplay',
                    'pageid'  => $page->get_id(),
                    'display' => $option,
                ));
                $options[$displayurl->out(false)] = $label;

                if ($option == $page->get_display()) {
                    $selected = $displayurl->out(false);
                }
            }

            $displayselect = html_writer::select($options, 'display', $selected, false, array(
                'id'    => html_writer::random_id(),
                'class' => 'format_flexpage_action_select'
            ));

            $options = array();
            $selected = '';
            foreach ($navigationopts as $option => $label) {
                $navurl = clone($url);
                $navurl->params(array(
                    'sesskey'    => sesskey(),
                    'action'     => 'setnavigation',
                    'pageid'     => $page->get_id(),
                    'navigation' => $option,
                ));
                $options[$navurl->out(false)] = $label;

                if ($option == $page->get_navigation()) {
                    $selected = $navurl->out(false);
                }
            }

            $navigationselect = html_writer::select($options, 'navigation', $selected, false, array(
                'id'    => html_writer::random_id(),
                'class' => 'format_flexpage_action_select'
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
                $conditionlib = new course_format_flexpage_lib_condition($page);
                $pagename .= html_writer::tag('div', $conditionlib->get_full_information(), array('class' => 'availabilityinfo'));
            }
            $row = $box->add_new_row(array('pageid' => $page->get_id()));
            $row->add_new_cell($pagename, array('class' => 'format_flexpage_name_cell'))
                ->add_new_cell($actionselect, array('id' => html_writer::random_id()))
                ->add_new_cell($displayselect, array('id' => html_writer::random_id(), 'class' => 'format_flexpage_display_cell'))
                ->add_new_cell($navigationselect, array('id' => html_writer::random_id(), 'class' => 'format_flexpage_navigation_cell'));
        }
        return $PAGE->get_renderer('local_mr')->render(new mr_html_notify('format_flexpage')).
               $this->render($box);
    }

    /**
     * Delete page UI
     *
     * @param moodle_url $url
     * @param course_format_flexpage_model_page $page
     * @return string
     */
    public function delete_page(moodle_url $url, course_format_flexpage_model_page $page) {
        $areyousure = get_string('areyousuredeletepage', 'format_flexpage', format_string($page->get_name()));

        return html_writer::start_tag('form', array('method' => 'post', 'action' => $url->out_omit_querystring())).
               html_writer::input_hidden_params($url).
               html_writer::tag('div', $areyousure, array('class' => 'format_flexpage_deletepage')).
               html_writer::end_tag('form');
    }

    /**
     * Edit page UI
     *
     * @param moodle_url $url
     * @param course_format_flexpage_model_page $page
     * @param array $regions All possible regions
     * @return string
     */
    public function edit_page(moodle_url $url, course_format_flexpage_model_page $page, array $regions) {
        global $CFG, $COURSE;

        $navigationopts = course_format_flexpage_model_page::get_navigation_options();
        $displayopts    = course_format_flexpage_model_page::get_display_options();
        $templates      = '';

        $box = new course_format_flexpage_lib_box(array('class' => 'format_flexpage_form'));
        $box->add_new_row()->add_new_cell(html_writer::label($this->flexpage_help_icon('name'), 'id_name'))
                           ->add_new_cell(html_writer::empty_tag('input', array('id' => 'id_name', 'name' => 'name', 'type' => 'text', 'size' => 50, 'value' => $page->get_name())));

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
                    html_writer::label("&nbsp;px&nbsp;$name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", "id_region_$region")
                )
            );
        }
        $box->add_new_row()->add_new_cell($this->flexpage_help_icon('regionwidths'))
                           ->add_cell($regioncell);

        $box->add_new_row()->add_new_cell(html_writer::label($this->flexpage_help_icon('display'), 'id_display'))
                           ->add_new_cell(html_writer::select($displayopts, 'display', $page->get_display(), false, array('id' => 'id_display')));

        $box->add_new_row()->add_new_cell(html_writer::label($this->flexpage_help_icon('navigation'), 'id_navigation'))
                           ->add_new_cell(html_writer::select($navigationopts, 'navigation', $page->get_navigation(), false, array('id' => 'id_navigation')));

        if (!empty($CFG->enableavailability)) {
            $box->add_new_row()->add_new_cell($this->flexpage_help_icon('availablefrom'))
                               ->add_new_cell($this->calendar('availablefrom', $page->get_availablefrom()));

            $box->add_new_row()->add_new_cell($this->flexpage_help_icon('availableuntil'))
                               ->add_new_cell($this->calendar('availableuntil', $page->get_availableuntil()));

            if (class_exists('local_mrooms_lib_condition_releasecode')) {
                $box->add_new_row()->add_new_cell(html_writer::label($this->flexpage_help_icon('releasecode', 'local_mrooms'), 'id_releasecode'))
                                   ->add_new_cell(html_writer::empty_tag('input', array('id' => 'id_releasecode', 'type' => 'text', 'name' => 'releasecode', 'maxlength' => 50, 'size' => 50, 'value' => $page->get_releasecode())));
            }

            $box->add_new_row()->add_new_cell($this->flexpage_help_icon('gradecondition', 'condition'))
                               ->add_new_cell($this->page_conditions($page, 'course_format_flexpage_model_condition_grade'));

            $templates = $this->condition_grade();

            $completion = new completion_info($COURSE);
            if ($completion->is_enabled()) {
                $box->add_new_row()->add_new_cell($this->flexpage_help_icon('completioncondition', 'condition'))
                                   ->add_new_cell($this->page_conditions($page, 'course_format_flexpage_model_condition_completion'));

                $templates .= $this->condition_completion();
            }
            $showopts = array(
                CONDITION_STUDENTVIEW_SHOW => get_string('showavailability_show', 'condition'),
                CONDITION_STUDENTVIEW_HIDE => get_string('showavailability_hide', 'condition')
            );
            $box->add_new_row()->add_new_cell(html_writer::label($this->flexpage_help_icon('showavailability'), 'id_showavailability'))
                               ->add_new_cell(html_writer::select($showopts, 'showavailability', $page->get_showavailability(), false, array('id' => 'id_showavailability')));
        }

        return html_writer::start_tag('form', array('method' => 'post', 'action' => $url->out_omit_querystring())).
               html_writer::input_hidden_params($url).
               $this->render($box).
               html_writer::end_tag('form').
               html_writer::tag('div', $templates, array('id' => 'format_flexpage_condition_templates'));
    }

    /**
     * Condition UI
     *
     * @param course_format_flexpage_model_page $page
     * @param string $conditionclass
     * @return string
     */
    public function page_conditions(course_format_flexpage_model_page $page, $conditionclass) {
        $conditions     = array();
        $pageconditions = $page->get_conditions();
        foreach ($pageconditions as $pagecondition) {
            if ($pagecondition instanceof $conditionclass) {
                $conditions[] = $pagecondition;
            }
        }
        // Render a blank one if none exist
        if (empty($conditions)) {
            $conditions = array(null);
        }
        if ($conditionclass == 'course_format_flexpage_model_condition_grade') {
            $rendermethod = 'condition_grade';
        } else {
            $rendermethod = 'condition_completion';
        }
        $condbox  = new course_format_flexpage_lib_box(array('class' => 'format_flexpage_conditions'));
        $condcell = new course_format_flexpage_lib_box_cell();
        $condcell->set_attributes(array('id' => $rendermethod.'s'));
        $condadd = html_writer::tag('button', '+', array('type' => 'button', 'value' => '+', 'id' => $rendermethod.'_add_button'));

        foreach ($conditions as $condition) {
            $condcell->append_contents(
                $this->$rendermethod($condition)
            );
        }
        $condbox->add_new_row()->add_cell($condcell)
                               ->add_new_cell($condadd, array('class' => 'format_flexpage_add_button'));

        return $this->render($condbox);
    }

    /**
     * Grade condition specific UI
     *
     * @param course_format_flexpage_model_condition_grade|null $condition
     * @return string
     */
    public function condition_grade(course_format_flexpage_model_condition_grade $condition = null) {
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

    /**
     * Completion condition specific UI
     *
     * @param course_format_flexpage_model_condition_completion|null $condition
     * @return string
     */
    public function condition_completion(course_format_flexpage_model_condition_completion $condition = null) {
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

    /**
     * Calendar element
     *
     * @param string $name Should be unique
     * @param int $timedefault
     * @param bool $optional
     * @return string
     */
    public function calendar($name, $timedefault = 0, $optional = true) {
        if ($timedefault > 0) {
            $value = date('m/d/Y', $timedefault);
        } else {
            $value = date('m/d/Y');
        }

        $output = '';
        $attributes  = array('id' => "calendar$name");
        if ($optional) {
            $output = html_writer::checkbox("enable$name", 1, ($timedefault > 0), '&nbsp;'.get_string('enable'));
            $output = html_writer::tag('div', $output);
            $attributes['class'] = 'hiddenifjs';
        }
        $output .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => $name, 'value' => $value)).
                   html_writer::tag('div', '', $attributes);

        return $output;
    }

    /**
     * @param string $type Basically next or previous
     * @param null|course_format_flexpage_model_page $page
     * @param null|string $label
     * @return string
     */
    public function navigation_link($type, course_format_flexpage_model_page $page = null, $label = null) {
        if ($page) {
            if (is_null($label)) {
                $label = get_string("{$type}page", 'format_flexpage', format_string($page->get_name()));
            }
            return html_writer::link($page->get_url(), $label, array('id' => "format_flexpage_{$type}_page"));
        }
        return '';
    }

    /**
     * @param string $type Basically next or previous
     * @param null|course_format_flexpage_model_page $page
     * @param null|string $label
     * @return string
     */
    public function navigation_button($type, course_format_flexpage_model_page $page = null, $label = null) {
        global $PAGE;

        $link = $this->navigation_link($type, $page, $label);
        if (!empty($link)) {
            // This will render the link as a button
            $PAGE->requires->yui2_lib('button');
            $PAGE->requires->js_init_call("(function(Y) { new YAHOO.widget.Button(\"format_flexpage_{$type}_page\"); })");
        }
        return $link;
    }
}