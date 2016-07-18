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

/**
 * Format Version
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */

$plugin->version      = 2016012600;
$plugin->requires     = 2015111604; // Requires this Moodle version (3.0.4).
$plugin->component    = 'format_flexpage';
$plugin->release      = '3.0.4 (Build: 20160509)';
$plugin->maturity     = MATURITY_STABLE;
$plugin->dependencies = array(
    'block_flexpagenav' => 2016012600,
    'block_flexpagemod' => 2016012600,
    'theme_flexpage'    => 2016012600,
    'local_mr'          => 2016012600,
);
