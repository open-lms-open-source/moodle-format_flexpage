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
            ),
            'strings' => array(
                array('savechanges'),
                array('cancel'),
                array('close', 'format_flexpage'),
                array('addpages', 'format_flexpage'),
                array('genericasyncfail', 'format_flexpage'),
                array('error', 'format_flexpage'),
                array('movepage', 'format_flexpage'),
            )
        );
    }

    public function pad_page_name(course_format_flexpage_model_page $page, $amount = null) {
        $name = format_string($page->get_display_name(), true, $page->get_courseid());

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
                           ->add_new_cell($addbutton, array('class' => 'format_flexpage_addpages_addbutton'));

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
}