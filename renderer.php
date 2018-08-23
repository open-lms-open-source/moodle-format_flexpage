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
 * @copyright Copyright (c) 2009 Blackboard Inc. (http://www.blackboardopenlms.com)
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
        global $CFG, $SCRIPT;

        require_once($CFG->dirroot.'/course/format/flexpage/lib/moodlepage.php');

        parent::__construct($page, $target);

        // 2nd part of hack, re-set page layout as it is overridden in course/view.php
        // See first part in format_flexpage::page_set_course
        $subpage = $page->subpage; // Due to magic methods...
        $layout  = course_format_flexpage_lib_moodlepage::LAYOUT;
        if ($SCRIPT == '/course/view.php' and !empty($subpage) and $page->pagelayout != $layout) {
            $page->set_pagelayout($layout);
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
                'moodle-core-popuphelp'
            ),
            'strings' => array(
                array('savechanges'),
                array('cancel'),
                array('choosedots'),
                array('close', 'form'),
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
     * Markup for region selector, JS builds out the rest
     *
     * @return string
     */
    public function region_selector() {
        $output  = html_writer::tag('legend', get_string('addto', 'format_flexpage'), array('class' => 'format_flexpage_addactivity_heading'));
        $output .= html_writer::tag('div', '', array('id' => 'format_flexpage_region_radios'));

        return html_writer::tag('fieldset', $output, array('class' => 'format_flexpage_region_selector'));
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
            $content .= $this->custom_menu_item($menuitem);
        }
        $content .= html_writer::end_tag('ul');
        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('div');

        return $content;
    }

    /**
     * Copied from renderer_base - currently we require
     * this to be YUI3, don't allow themes to override.
     *
     * This has been modified slighty.
     *
     * @param custom_menu_item $menunode
     * @return string
     */
    protected function custom_menu_item(custom_menu_item $menunode) {
        if ($menunode->has_children()) {
            $id = html_writer::random_id('cm_submenu_');
            // If the child has menus render it as a sub menu
            $content = html_writer::start_tag('li');
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#'.$id;
            }
            $content .= html_writer::link($url, $menunode->get_text(), array('class' => 'yui3-menu-label', 'title' => $menunode->get_title()));
            $content .= html_writer::start_tag('div', array('id' => $id, 'class' => 'yui3-menu custom_menu_submenu'));
            $content .= html_writer::start_tag('div', array('class' => 'yui3-menu-content'));
            $content .= html_writer::start_tag('ul');
            foreach ($menunode->get_children() as $menunode) {
                $content .= $this->custom_menu_item($menunode);
            }
            $content .= html_writer::end_tag('ul');
            $content .= html_writer::end_tag('div');
            $content .= html_writer::end_tag('div');
            $content .= html_writer::end_tag('li');
        } else {
            // The node doesn't have children so produce a final menuitem
            $content = html_writer::start_tag('li', array('class' => 'yui3-menuitem'));
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#';
            }
            $content .= html_writer::link($url, $menunode->get_text(), array('class' => 'yui3-menuitem-content', 'title' => $menunode->get_title()));
            $content .= html_writer::end_tag('li');
        }
        // Return the sub menu
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
        $jumpselect = new single_select($this->page->url, 'pageid', $options, $currentpage->get_id(), array(), 'jumptopageid');
        $jumpselect->set_label(get_string('jumptoflexpage', 'format_flexpage'), array('class' => 'accesshide'));

        $jumptopage = html_writer::tag('span', $this->output->render($jumpselect), array('id' => 'format_flexpage_jumptopage'));
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
                $formattedinfo = \core_availability\info::format_info($info, $page->get_courseid());

                $box->add_new_row()->add_new_cell(
                    html_writer::div(format_string($page->get_name()), 'format_flexpage_pagename').
                    html_writer::div($formattedinfo, 'availabilityinfo')
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
        $box   = new course_format_flexpage_lib_box();
        $cell1 = new course_format_flexpage_lib_box_cell();
        $cell2 = new course_format_flexpage_lib_box_cell();

        $options              = new stdClass();
        $options->trusted     = false;
        $options->noclean     = false;
        $options->smiley      = false;
        $options->filter      = false;
        $options->para        = true;
        $options->newlines    = false;
        $options->overflowdiv = false;

        foreach ($activities as $groupname => $modules) {
            $items = array();
            foreach ($modules as $module) {
                if (empty($module->help)) {
                    $module->help = get_string('nohelpforactivityorresource', 'moodle');
                }
                $title = format_text($module->help, FORMAT_MARKDOWN, $options);
                $title = html_to_text($title);

                $items[] = html_writer::link(
                    new moodle_url($module->link.'&section=0'),
                    $module->icon.' '.$module->title,
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
               $this->region_selector().
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
               $this->region_selector().
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
                $this->region_selector().
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
                $fullinfo     = $conditionlib->get_full_information();
                if ($fullinfo) {
                    $formattedinfo = \core_availability\info::format_info($fullinfo, $page->get_courseid());
                    $pagename .= html_writer::div($formattedinfo, 'availabilityinfo');
                }
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

            if (array_key_exists('releasecode', core_component::get_plugin_list('availability'))) {
                $box->add_new_row()->add_new_cell(html_writer::label($this->flexpage_help_icon('releasecode'), 'id_releasecode'))
                                   ->add_new_cell(html_writer::empty_tag('input', array('id' => 'id_releasecode', 'type' => 'text', 'name' => 'releasecode', 'maxlength' => 50, 'size' => 50, 'value' => $page->get_releasecode())));
            }

            $box->add_new_row()->add_new_cell($this->flexpage_help_icon('gradecondition'))
                               ->add_new_cell($this->page_conditions($page, 'course_format_flexpage_model_condition_grade'));

            $templates = $this->condition_grade();

            $box->add_new_row()->add_new_cell($this->flexpage_help_icon('userfield'))
                ->add_new_cell($this->page_conditions($page, 'course_format_flexpage_model_condition_field'));

            $templates .= $this->condition_field();

            $completion = new completion_info($COURSE);
            if ($completion->is_enabled()) {
                $box->add_new_row()->add_new_cell($this->flexpage_help_icon('completioncondition'))
                                   ->add_new_cell($this->page_conditions($page, 'course_format_flexpage_model_condition_completion'));

                $templates .= $this->condition_completion();
            }
            $showopts = array(
                1 => get_string('showavailability_show', 'format_flexpage'),
                0 => get_string('showavailability_hide', 'format_flexpage')
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
        } else if ($conditionclass == 'course_format_flexpage_model_condition_field') {
            $rendermethod = 'condition_field';
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
            $gradeoptions = array(0 => get_string('none', 'format_flexpage')) + $gradeoptions;
        }
        $elements = html_writer::select($gradeoptions, 'gradeitemids[]', $gradeitemid, false).
                    ' '.get_string('grade_atleast','format_flexpage').' '.
                    html_writer::empty_tag('input', array('name' => 'mins[]', 'size' => 3, 'type' => 'text', 'value' => $min)).
                    '% '.get_string('grade_upto','format_flexpage').' '.
                    html_writer::empty_tag('input', array('name' => 'maxes[]', 'size' => 3, 'type' => 'text', 'value' => $max)).
                    '%';

        return html_writer::tag('div', $elements, array('class' => 'format_flexpage_condition_grade'));
    }

    /**
     * Grade condition specific UI
     *
     * @param course_format_flexpage_model_condition_field|null $condition
     * @return string
     */
    public function condition_field(course_format_flexpage_model_condition_field $condition = null) {
        global $DB;

        // Static so we only build it once...
        static $operators   = null;
        static $useroptions = null;

        if (is_null($condition)) {
            $field    = 0;
            $operator = '';
            $value    = '';
        } else {
            $field    = $condition->get_field();
            $operator = $condition->get_operator();
            $value    = $condition->get_value();
        }
        if (is_null($operators) || is_null($useroptions)) {
            $operators = array(
                OP_CONTAINS         => get_string('contains', 'format_flexpage'),
                OP_DOES_NOT_CONTAIN => get_string('doesnotcontain', 'format_flexpage'),
                OP_IS_EQUAL_TO      => get_string('isequalto', 'format_flexpage'),
                OP_STARTS_WITH      => get_string('startswith', 'format_flexpage'),
                OP_ENDS_WITH        => get_string('endswith', 'format_flexpage'),
                OP_IS_EMPTY         => get_string('isempty', 'format_flexpage'),
                OP_IS_NOT_EMPTY     => get_string('isnotempty', 'format_flexpage'),
            );
            $useroptions = array(
                'firstname'   => get_user_field_name('firstname'),
                'lastname'    => get_user_field_name('lastname'),
                'email'       => get_user_field_name('email'),
                'city'        => get_user_field_name('city'),
                'country'     => get_user_field_name('country'),
                'url'         => get_user_field_name('url'),
                'icq'         => get_user_field_name('icq'),
                'skype'       => get_user_field_name('skype'),
                'aim'         => get_user_field_name('aim'),
                'yahoo'       => get_user_field_name('yahoo'),
                'msn'         => get_user_field_name('msn'),
                'idnumber'    => get_user_field_name('idnumber'),
                'institution' => get_user_field_name('institution'),
                'department'  => get_user_field_name('department'),
                'phone1'      => get_user_field_name('phone1'),
                'phone2'      => get_user_field_name('phone2'),
                'address'     => get_user_field_name('address')
            );

            // Go through the custom profile fields now
            if ($user_info_fields = $DB->get_records('user_info_field')) {
                foreach ($user_info_fields as $userfield) {
                    $useroptions[$userfield->id] = format_string(
                        $userfield->name, true, array('context' => $this->page->context)
                    );
                }
            }
            asort($useroptions);
            $useroptions = array(0 => get_string('none', 'format_flexpage')) + $useroptions;
        }
        $fieldid    = html_writer::random_id('field');
        $operatorid = html_writer::random_id('field');
        $valueid    = html_writer::random_id('field');

        $elements = html_writer::label(get_string('userfield', 'format_flexpage'), $fieldid, false, array('class' => 'accesshide')).
            html_writer::select($useroptions, 'fields[]', $field, false, array('id' => $fieldid)).
            html_writer::label(get_string('operator', 'format_flexpage'), $operatorid, false, array('class' => 'accesshide')).
            html_writer::select($operators, 'operators[]', $operator, false, array('id' => $operatorid)).
            html_writer::label(get_string('fieldvalue', 'format_flexpage'), $valueid, false, array('class' => 'accesshide')).
            html_writer::empty_tag('input', array('type' => 'text', 'name' => 'values[]', 'value' => $value, 'id' => $valueid));

        return html_writer::tag('div', $elements, array('class' => 'format_flexpage_condition_field'));
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
            $completionoptions = array(0 => get_string('none', 'format_flexpage')) + $completionoptions;
        }
        $completionvalues=array(
            COMPLETION_COMPLETE      => get_string('completion_complete','format_flexpage'),
            COMPLETION_INCOMPLETE    => get_string('completion_incomplete','format_flexpage'),
            COMPLETION_COMPLETE_PASS => get_string('completion_pass','format_flexpage'),
            COMPLETION_COMPLETE_FAIL => get_string('completion_fail','format_flexpage'),
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
     * @param array $attributes
     * @return string
     */
    public function navigation_link($type, course_format_flexpage_model_page $page = null, $label = null, $attributes = array()) {
        if ($page) {
            if (is_null($label)) {
                $label = get_string("{$type}page", 'format_flexpage', format_string($page->get_name()));
            }
            $attributes = array_merge(array('id' => "format_flexpage_{$type}_page"), $attributes);
            return html_writer::link($page->get_url(), $label, $attributes);
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
            $PAGE->requires->js_init_call("(function(Y) { Y.use('yui2-button', function(Y) { new Y.YUI2.widget.Button(\"format_flexpage_{$type}_page\"); }) })");
        }
        return $link;
    }
}
