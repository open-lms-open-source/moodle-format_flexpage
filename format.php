<?php
/**
 * Format hook into course/view.php
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */

if ($PAGE->user_is_editing()) {
    require($CFG->dirroot.'/local/mr/bootstrap.php');
    require_once($CFG->dirroot.'/course/format/flexpage/lib/actionbar.php');

    $output = $PAGE->get_renderer('format_flexpage');
    $mroutput = $PAGE->get_renderer('local_mr');

    echo $output->render(course_format_flexpage_lib_actionbar::factory());
    echo $mroutput->render(new mr_html_notify('format_flexpage'));
}