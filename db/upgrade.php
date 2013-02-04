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
 * Format Upgrade Path
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
function xmldb_format_flexpage_upgrade($oldversion = 0) {
    global $CFG, $DB;

    require_once($CFG->dirroot.'/course/format/flexpage/repository/cache.php');

    $dbman     = $DB->get_manager();
    $cacherepo = new course_format_flexpage_repository_cache();

    if ($oldversion < 2011062800) {
        $DB->execute('
            UPDATE {format_flexpage_page}
               SET name = altname
             WHERE altname IS NOT NULL
               AND altname != \'\'
        ');

        // Define field altname to be dropped from format_flexpage_page
        $table = new xmldb_table('format_flexpage_page');
        $field = new xmldb_field('altname');

        // Conditionally launch drop field altname
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // flexpage savepoint reached
        upgrade_plugin_savepoint(true, 2011062800, 'format', 'flexpage');
    }

    if ($oldversion < 2012071900) {
        $cacherepo->clear_all_cache();

        // flexpage savepoint reached
        upgrade_plugin_savepoint(true, 2012071900, 'format', 'flexpage');
    }

    if ($oldversion < 2013020400) {
        // Clean out automatically migrated options
        $DB->delete_records('course_format_options', array('format' => 'flexpage'));

        // flexpage savepoint reached
        upgrade_plugin_savepoint(true, 2013020400, 'format', 'flexpage');
    }
    return true;
}