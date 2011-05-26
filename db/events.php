<?php
/**
 * Event hooks
 *
 * @author Mark Nielsen
 * @package format_flexpage
 **/
$handlers = array(
    'mod_created' => array(
        'handlerfile'     => '/course/format/flexpage/lib/eventhandler.php',
        'handlerfunction' => array('course_format_flexpage_lib_eventhandler', 'mod_created'),
        'schedule'        => 'instant'
    ),
    'mod_deleted' => array(
        'handlerfile'     => '/course/format/flexpage/lib/eventhandler.php',
        'handlerfunction' => array('course_format_flexpage_lib_eventhandler', 'mod_deleted'),
        'schedule'        => 'instant'
    ),
);