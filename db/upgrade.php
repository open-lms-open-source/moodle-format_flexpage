<?php
/**
 * Format Upgrade Path
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
function xmldb_format_flexpage_upgrade($oldversion = 0) {
    global $CFG, $DB, $OUTPUT;

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

    if ($oldversion < 2011062802) {
        $cacherepo->clear_all_cache();

        // flexpage savepoint reached
        upgrade_plugin_savepoint(true, 2011062802, 'format', 'flexpage');
    }

    return true;
}