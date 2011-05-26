<?php
/**
 * Flexpage backup plugin
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class backup_format_flexpage_plugin extends backup_format_plugin {
    /**
     * Returns the format information to attach to course element
     */
    protected function define_course_plugin_structure() {

        // Define the virtual plugin element with the condition to fulfill
        $plugin = $this->get_plugin_element(null, '/course/format', 'flexpage');

        // Create one standard named plugin element (the visible container)
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        // Connect the visible container ASAP
        $plugin->add_child($pluginwrapper);

        // Now create the format specific structures
        $page = new backup_nested_element('page', array('id'), array(
            'name',
            'altname',
            'display',
            'navigation',
            'availablefrom',
            'availableuntil',
            'releasecode',
            'showavailability',
            'parentid',
            'weight',
        ));

        $regions = new backup_nested_element('regions');
        $region  = new backup_nested_element('region', array('id'), array('region', 'width'));

        $completions = new backup_nested_element('completions');
        $completion  = new backup_nested_element('completion', array('id'), array('cmid', 'requiredcompletion'));

        $grades = new backup_nested_element('grades');
        $grade  = new backup_nested_element('grade', array('id'), array('gradeitemid', 'grademin', 'grademax'));

        // Now the format specific tree
        $pluginwrapper->add_child($page);

        $page->add_child($regions);
        $regions->add_child($region);

        $page->add_child($completions);
        $completions->add_child($completion);

        $page->add_child($grades);
        $grades->add_child($grade);

        // Set source to populate the data
        $page->set_source_table('format_flexpage_page', array('courseid' => backup::VAR_COURSEID));
        $region->set_source_table('format_flexpage_region', array('pageid' => backup::VAR_PARENTID));
        $completion->set_source_table('format_flexpage_completion', array('pageid' => backup::VAR_PARENTID));
        $grade->set_source_table('format_flexpage_grade', array('pageid' => backup::VAR_PARENTID));

        // Annotate ids
        $grade->annotate_ids('grade_item', 'gradeitemid');

        return $plugin;
    }
}