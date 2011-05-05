<?php
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
            )
        );
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
        $otherdiv = html_writer::tag('div', 'hi', array('style' => 'float:right;'));
        $menudiv = html_writer::tag('div', $otherdiv, array('id' => 'format_flexpage_actionbar_menu'));
        return html_writer::tag('div', $menudiv, array('id' => 'format_flexpage_actionbar'));
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

    public function pad_page_name(course_format_flexpage_model_page $page, $amount = null) {
        global $CFG;

        /**
         * @var course_format_flexpage_repository_cache $repo
         */
        static $repo = null;

        $name = format_string($page->get_display_name(), true, $page->get_courseid());

        if (is_null($amount)) {
            if (is_null($repo)) {
                require_once($CFG->dirroot.'/course/format/flexpage/repository/cache.php');
                $repo = new course_format_flexpage_repository_cache();
            }
            $cache  = $repo->get_cache($page->get_courseid());
            $amount = $cache->get_page_depth($page);
        }
        if ($amount == 0) {
            return $name;
        }
        return str_repeat('&nbsp;&nbsp;', $amount).'-&nbsp;'.$name;
    }
}