<?php
/**
 * Format capabilities
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */

$capabilities = array(

    'format/flexpage:managepages' => array (

        'riskbitmask' => RISK_XSS,

        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),
);