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

    if ($oldversion < 2013080200) {

        // Define table format_flexpage_field to be created
        $table = new xmldb_table('format_flexpage_field');

        // Adding fields to table format_flexpage_field
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('pageid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userfield', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('customfieldid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('operator', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('value', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table format_flexpage_field
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('pageid', XMLDB_KEY_FOREIGN, array('pageid'), 'format_flexpage_page', array('id'));

        // Conditionally launch create table for format_flexpage_field
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // flexpage savepoint reached
        upgrade_plugin_savepoint(true, 2013080200, 'format', 'flexpage');
    }

    if ($oldversion < 2014093000) {
        $cacherepo->clear_all_cache();

        // flexpage savepoint reached
        upgrade_plugin_savepoint(true, 2014093000, 'format', 'flexpage');
    }

    return true;
}
