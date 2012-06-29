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
 * @package course/format/flexpage
 * @author Mark Nielsen
 */

/**
 * Format hook into course/view.php
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */

require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

/**
 * @var format_flexpage_renderer $output
 */
$output = $PAGE->get_renderer('format_flexpage');
$layout = course_format_flexpage_lib_moodlepage::LAYOUT;

if (!course_format_flexpage_lib_moodlepage::layout_exists($PAGE, $layout)) {
    echo $output->box(get_string('themelayoutmissing', 'format_flexpage', $layout), 'generalbox');

} else if ($PAGE->user_is_editing()) {
    require($CFG->dirroot.'/local/mr/bootstrap.php');
    require_once($CFG->dirroot.'/course/format/flexpage/lib/actionbar.php');

    $mroutput = $PAGE->get_renderer('local_mr');

    echo $output->render(course_format_flexpage_lib_actionbar::factory());
    echo $mroutput->render(new mr_html_notify('format_flexpage'));

    $PAGE->requires->js_init_call('M.format_flexpage.init_edit', null, true, $output->get_js_module());

} else if (!empty($CFG->enableavailability)) {
    $cache = format_flexpage_cache();
    $page  = $cache->get_current_page();

    if (!$cache->is_page_available($page)) {
        echo $output->heading_with_help(
            get_string('pagexnotavailable', 'format_flexpage', $page->get_name()),
            'pagenotavailable',
            'format_flexpage'
        );
        echo $output->page_available_info($cache->get_page_parents($page, true));

        $gotopage = $cache->get_first_available_page();
        if ($cache->is_page_available($gotopage)) {
            echo $output->continue_button(new moodle_url('/course/view.php', array('id' => $COURSE->id, 'pageid' => $gotopage->get_id())));
        } else {
            echo $output->continue_button('/');
        }
    }
}