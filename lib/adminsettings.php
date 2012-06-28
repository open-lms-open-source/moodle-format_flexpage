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
 * @package local/mr
 * @author Mark Nielsen
 */

/**
 * This class modifies the site course's format value
 * and thus enables the flexpage format for front page
 *
 * We set the site format to flexpage so the site will backup
 * with flexpage data.
 *
 * @author Mark Nielsen
 * @package format_flexpage
 **/
class admin_setting_special_formatflexpageonfrontpage extends admin_setting_configcheckbox {
    public function __construct() {
        parent::__construct('format_flexpage/onfrontpage', get_string('frontpage', 'format_flexpage'), get_string('frontpagedesc', 'format_flexpage'), 0);
    }

    public function config_read($name) {
        $site = get_site();

        if ($site->format == 'flexpage') {
            return 1;
        }
        $return = parent::config_read($name);
        if ($return == 1) {
            return 0;
        }
        return $return;
    }

    public function config_write($name, $value) {
        global $DB;

        if ($value == 1) {
            $format = 'flexpage';
        } else {
            $format = 'site';
        }
        if ($DB->set_field('course', 'format', $format, array('id' => SITEID))) {
            return parent::config_write($name, $value);
        }
        return true;
    }
}
