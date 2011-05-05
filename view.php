<?php
/**
 * View renderer
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */

require_once('../../../config.php');
require($CFG->dirroot.'/local/mr/bootstrap.php');
require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

mr_controller::render('course/format/flexpage', 'pluginname', 'format_flexpage');