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

/**
 * View renderer
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */

define('NO_DEBUG_DISPLAY', true);

require_once('../../../config.php');
require($CFG->dirroot.'/local/mr/bootstrap.php');
require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

mr_controller::render('course/format/flexpage', 'pluginname', 'format_flexpage');
