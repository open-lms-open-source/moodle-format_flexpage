<?php
/**
 * View renderer
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */

require_once('../../../config.php');
require($CFG->dirroot.'/local/mr/bootstrap.php');

mr_controller::render('course/format/flexpage', 'pluginname', 'format_flexpage');