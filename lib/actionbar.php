<?php
/**
 * @see course_format_flexpage_lib_menu
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/menu.php');

/**
 * @see course_format_flexpage_lib_menu_action
 */
require_once($CFG->dirroot.'/course/format/flexpage/lib/menu/action.php');

class course_format_flexpage_lib_actionbar implements renderable {
    /**
     * @var course_format_flexpage_lib_menu[]
     */
    protected $menus = array();

    public function get_menus() {
        return $this->menus;
    }

    public function add_menu(course_format_flexpage_lib_menu $menu) {
        $this->menus[] = $menu;
        return $this;
    }

    /**
     * @static
     * @return course_format_flexpage_lib_actionbar
     */
    public static function factory() {
        $actionbar = new course_format_flexpage_lib_actionbar();

        $addmenu = new course_format_flexpage_lib_menu(get_string('add', 'format_flexpage'));
        $addmenu->add_action(new course_format_flexpage_lib_menu_action('addpages'))
                ->add_action(new course_format_flexpage_lib_menu_action('addactivity'))
                ->add_action(new course_format_flexpage_lib_menu_action('addexistingactivity'));

        $managemenu = new course_format_flexpage_lib_menu(get_string('manage', 'format_flexpage'));
        $managemenu->add_action(new course_format_flexpage_lib_menu_action('movepage'))
                   ->add_action(new course_format_flexpage_lib_menu_action('managepages'))
                   ->add_action(new course_format_flexpage_lib_menu_action('managepage'));

        return $actionbar->add_menu($addmenu)->add_menu($managemenu);
    }
}