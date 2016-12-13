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

$plugin->version      = 2016121300;
$plugin->requires     = 2016052300;
$plugin->component    = 'format_flexpage';
$plugin->release      = '3.1.3';
$plugin->maturity     = MATURITY_STABLE;
$plugin->dependencies = array(
    'block_flexpagenav' => 2016121300,
    'block_flexpagemod' => 2016121300,
    'theme_flexpage'    => 2016121300,
    'local_mr'          => 2016121300,
);
