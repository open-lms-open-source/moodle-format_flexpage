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

    /**
     * @throws coding_exception
     * @param string $id Menu ID
     * @return course_format_flexpage_lib_menu
     */
    public function get_menu($id) {
        if (!array_key_exists($id, $this->menus)) {
            throw new coding_exception("Menu with id = $id does not exist");
        }
        return $this->menus[$id];
    }

    /**
     * @return course_format_flexpage_lib_menu[]
     */
    public function get_menus() {
        return $this->menus;
    }

    /**
     * @param course_format_flexpage_lib_menu $menu
     * @return course_format_flexpage_lib_actionbar
     */
    public function add_menu(course_format_flexpage_lib_menu $menu) {
        $this->menus[$menu->get_id()] = $menu;
        return $this;
    }

    /**
     * @static
     * @return course_format_flexpage_lib_actionbar
     */
    public static function factory() {
        $actionbar = new course_format_flexpage_lib_actionbar();

        $addmenu = new course_format_flexpage_lib_menu('add');
        $addmenu->add_action(new course_format_flexpage_lib_menu_action('addpages'))
                ->add_action(new course_format_flexpage_lib_menu_action('addactivity'))
                ->add_action(new course_format_flexpage_lib_menu_action('addexistingactivity'))
                ->add_action(new course_format_flexpage_lib_menu_action('addblock'));

        $managemenu = new course_format_flexpage_lib_menu('manage');
        $managemenu->add_action(new course_format_flexpage_lib_menu_action('editpage'))
                   ->add_action(new course_format_flexpage_lib_menu_action('movepage'))
                   ->add_action(new course_format_flexpage_lib_menu_action('managepages'));

        return $actionbar->add_menu($addmenu)->add_menu($managemenu);
    }
}