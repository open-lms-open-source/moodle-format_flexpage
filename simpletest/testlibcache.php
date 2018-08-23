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
 * @copyright Copyright (c) 2009 Blackboard Inc. (http://www.blackboardopenlms.com)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @package format_flexpage
 * @author Mark Nielsen
 */

/**
 * @see course_format_flexpage_model_cache
 */
require_once($CFG->dirroot.'/course/format/flexpage/model/cache.php');

Mock::generate('course_format_flexpage_repository_page', 'mock_course_format_flexpage_repository_page');
Mock::generate('course_format_flexpage_repository_condition', 'mock_course_format_flexpage_repository_condition');

/**
 * Test course_format_flexpage_model_cache
 *
 * @package format_flexpage
 * @author Mark Nielsen
 */
class course_format_flexpage_lib_cache_test extends UnitTestCase {

    public static $includecoverage = array('course/format/flexpage/model/cache.php');

    /**
     * @var course_format_flexpage_model_page[]
     */
    protected $fixture;

    public function setUp() {
        global $CFG;

        /**
         * So cache doesn't do anything extra...
         */
        $CFG->enableavailability = 0;

        // Mimic DB call
        $this->fixture = array(
            1 => new course_format_flexpage_model_page(array(
                'id' => 1,
                'parentid' => 0,
                'weight' => 0,
            )),
            6 => new course_format_flexpage_model_page(array(
                'id' => 6,
                'parentid' => 0,
                'weight' => 1,
            )),
            2 => new course_format_flexpage_model_page(array(
                'id' => 2,
                'parentid' => 1,
                'weight' => 0,
            )),
            5 => new course_format_flexpage_model_page(array(
                'id' => 5,
                'parentid' => 1,
                'weight' => 1,
            )),
            3 => new course_format_flexpage_model_page(array(
                'id' => 3,
                'parentid' => 2,
                'weight' => 0,
            )),
            4 => new course_format_flexpage_model_page(array(
                'id' => 4,
                'parentid' => 2,
                'weight' => 1,
            )),
            7 => new course_format_flexpage_model_page(array(
                'id' => 7,
                'parentid' => 6,
                'weight' => 0,
            )),
            10 => new course_format_flexpage_model_page(array(
                'id' => 10,
                'parentid' => 6,
                'weight' => 1,
            )),
            8 => new course_format_flexpage_model_page(array(
                'id' => 8,
                'parentid' => 7,
                'weight' => 0,
            )),
            9 => new course_format_flexpage_model_page(array(
                'id' => 9,
                'parentid' => 7,
                'weight' => 1,
            )),
        );
    }

    public function test_sort() {
        $pagerepo = new mock_course_format_flexpage_repository_page();
        $pagerepo->setReturnValue('get_pages', $this->fixture);
        $condrepo = new mock_course_format_flexpage_repository_condition();
        $condrepo->setReturnValue('get_course_conditions', array());

        $cache = new course_format_flexpage_model_cache();
        $cache->set_courseid(0);
        $cache->set_repository_page($pagerepo);
        $cache->set_repository_condition($condrepo);
        $cache->build();

        $this->assertIdentical(array(1,2,3,4,5,6,7,8,9,10), array_keys($cache->get_pages()));
    }

    public function test_get_page_depth() {
        $pagerepo = new mock_course_format_flexpage_repository_page();
        $pagerepo->setReturnValue('get_pages', $this->fixture);
        $condrepo = new mock_course_format_flexpage_repository_condition();
        $condrepo->setReturnValue('get_course_conditions', array());

        $cache = new course_format_flexpage_model_cache();
        $cache->set_courseid(0);
        $cache->set_repository_page($pagerepo);
        $cache->set_repository_condition($condrepo);
        $cache->build();

        $this->assertEqual(2, $cache->get_page_depth($cache->get_page(4)));
    }

    public function test_is_child_page() {
        $pagerepo = new mock_course_format_flexpage_repository_page();
        $pagerepo->setReturnValue('get_pages', $this->fixture);
        $condrepo = new mock_course_format_flexpage_repository_condition();
        $condrepo->setReturnValue('get_course_conditions', array());

        $cache = new course_format_flexpage_model_cache();
        $cache->set_courseid(0);
        $cache->set_repository_page($pagerepo);
        $cache->set_repository_condition($condrepo);
        $cache->build();

        $this->assertTrue($cache->is_child_page($cache->get_page(1), $cache->get_page(4)));
        $this->assertFalse($cache->is_child_page($cache->get_page(4), $cache->get_page(1)));
    }
}
