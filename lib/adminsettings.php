<?php
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
