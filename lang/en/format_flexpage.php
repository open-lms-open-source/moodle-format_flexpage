<?php
/**
 * Flexpage language definitions
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
$string['pluginname'] = 'Flexpage format';
$string['defaultcoursepagename'] = 'Default Page (Change me)';
$string['pagenotfound'] = 'The page with id = {$a} does not exist in this course.';
$string['addmenu'] = 'Add';
$string['managemenu'] = 'Manage';
$string['addactivityaction'] = 'Add activity';
$string['addpagesaction'] = 'Add pages';
$string['managepagesaction'] = 'Manage all pages';
$string['editpageaction'] = 'Manage page settings';
$string['editpage'] = 'Page settings';
$string['movebefore'] = 'before';
$string['moveafter'] = 'after';
$string['movechild'] = 'as first child of';
$string['ajaxexception'] = 'Application error: {$a}';
$string['addedpages'] = 'Added pages: {$a}';
$string['addpages'] = 'Add pages';
$string['error'] = 'Error';
$string['genericasyncfail'] = 'The request failed for an unknown reason, please try the action again.';
$string['close'] = 'Close';
$string['gotoa'] = 'Go to "{$a}"';
$string['movepageaction'] = 'Move page';
$string['movepage'] = 'Move page';
$string['movepagea'] = 'Move page <strong>{$a}</strong>';
$string['movedpage'] = 'Moved page "{$a->movepage}" {$a->move} page "{$a->refpage}"';
$string['addactivity'] = 'Add activity';
$string['addactivity_help'] = 'Choose where you would like to place the new activity on the page by selecting one of the buttons on the top of the <strong>Add activity</strong> window. Next, choose the activity or resource that you would like to add to the course and page.';
$string['addto'] = 'Add to:';
$string['addexistingactivity'] = 'Add existing activity';
$string['addexistingactivity_help'] = 'Choose where you would like to place the existing activity on the page by selecting one of the buttons on the top of the <strong>Add existing activity</strong> window. Next, place a checkmark next to the activities that you would like to add to this page. Finally, click the "Add activities" button at the bottom of the window to complete the action.';
$string['addexistingactivityaction'] = 'Add existing activity';
$string['addactivities'] = 'Add activities';
$string['addblock'] = 'Add block';
$string['addblock_help'] = 'Choose where you would like to place the block on the page by selecting one of the buttons on the top of the <strong>Add block</strong> window. Next, click on the name of the block that you would like to add to the course.';
$string['addblockaction'] = 'Add block';
$string['block'] = 'Block';
$string['displayhidden'] = 'Hidden';
$string['displayvisible'] = 'Visible but not in menus';
$string['displayvisiblemenu'] = 'Visible and in menus';
$string['navnone'] = 'No navigation';
$string['navprev'] = 'Previous page only';
$string['navnext'] = 'Next page only';
$string['navboth'] = 'Both previous and next page';
$string['navigation'] = 'Navigation';
$string['navigation_help'] = 'Used to display next and/or previous buttons on this page.  The buttons take the user to the next/previous available page.';
$string['display'] = 'Display';
$string['name'] = 'Name';
$string['name_help'] = 'This is the name of your page and it will appear to course users in menus and the like.';
$string['formnamerequired'] = 'The page name is a required field.';
$string['regionwidths'] = 'Block region widths';
$string['regionwidths_help'] = 'One can specify how wide each region of blocks can be in pixels.  An example would be to set left to 200, main to 500 and right to 200.  Please note though that available regions and their names can change from theme to theme.';
$string['managepages'] = 'Manage pages';
$string['managepages_help'] = 'From this window, you can view the index of all pages, and quickly manage, move or delete individual pages; as well as alter display settings; as well as control navigation settings.';
$string['pagename'] = 'Page name';
$string['deletepage'] = 'Delete page';
$string['deletepageaction'] = 'Delete page';
$string['areyousuredeletepage'] = 'Are you sure that you want to permanently delete page <strong>{$a}</strong>?';
$string['deletedpagex'] = 'Deleted page "{$a}"';
$string['flexpage:managepages'] = 'Manage pages';
$string['pagexnotavailable'] = '{$a} is not available';
$string['pagenotavailable'] = 'Not available';
$string['pagenotavailable_help'] = 'This page is not available to you.  Below might be a list of conditions that you must satisfy to in order to view the page.';
$string['sectionname'] = 'Section';
$string['page'] = 'Page';
$string['copydotdotdot'] = 'Copy...';
$string['nextpage'] = 'Next >';
$string['previouspage'] = '< Previous';
$string['themelayoutmissing'] = 'Your current theme does not support Flexpage.  Please change the theme (or if enabled, the course theme or your preferred theme in your profile) to one that has a "{$a}" layout.';
$string['deletemodwarn'] = 'If this activity is deleted, then it will be removed from all pages.';
$string['continuedotdotdot'] = 'Continue...';
$string['warning'] = 'Warning';
$string['actionbar'] = 'Edit bar';
$string['availablefrom'] = 'Allow access from';
$string['availablefrom_help'] = 'This page will be available to course users after this date.';
$string['availableuntil'] = 'Allow access until';
$string['availableuntil_help'] = 'This page will be available to course users before this date.';
$string['showavailability'] = 'Before this can be accessed';
$string['showavailability_help'] = 'If the page is unavailable to the user, this setting determines if this page\'s restriction information is displayed or nothing at all.';


$string['display_help'] = 'Configure if this page is:
<ol>
    <li>Hidden completely to non-editors.</li>
    <li>Visible to course users, but does not appear in Flexpage Menus and course navigation.</li>
    <li>Visible to course users and appears in Flexpage Menus and course navigation.</li>
</ol>';

$string['addpages_help'] = 'From here you can new pages to your course.  Going from left to right on the form:
 <ol>
    <li>Enter the name of your page (blank names are not added).</li>
    <li>The next <em>two</em> drop-downs determine where in the index of pages your new page will be added.  So, you can add your new page before, after or as a child (sub-page) of another page.</li>
    <li>(Optional) In the last drop-down, one can choose an existing page to copy into your newly created page.</li>
</ol>
To add more than one page at a time, click on the "+" icon and fill out the new row.  If you click on the "+" icon too many times, just blank out the page names and they will not be added.';

$string['actionbar_help'] = '
<p>With Flexpage, course designers can create multiple pages within the course. Each page can have unique or shared content on them.</p>

<p>With the Edit bar, on can jump to different pages within this course by clicking the name of the page in the drop-down menu.</p>

<p>Available actions under the <strong>Add</strong> Edit bar menu item:
    <ul>
        <li>To add a page, select the <strong>Add pages</strong> link from the drop-down menu. When you add new pages, you will want to determine where they are located in the index of pages. Pages can be children of other pages (sub-pages). Or they can simply be placed before or after other pages. New pages can also be blank, or a copy of an existing page. To add multiple pages to a course, press the "+" icon on the right side of the <strong>Add pages</strong> window.</li>
        <li>To add a new activity to this page, select <strong>Add activity</strong> link from the drop-down menu. Choose where you would like to place the new activity on the page by selecting one of the buttons on the top of the <strong>Add activity</strong> window. Next, choose the activity or resource that you would like to add to the course and page.</li>
        <li>To add an existing activity to this page, select <strong>Add existing activity</strong> link from the down-down menu. Choose where you would like to place the existing activity on the page by selecting one of the buttons on the top of the <strong>Add existing activity</strong> window. Next, place a checkmark next to the activities that you would like to add to this page. Finally, click the "Add activities" button at the bottom of the window to complete the action.</li>
        <li>To add a block to this page, select <strong>Add block</strong> from the drop-down menu. Choose where you would like to place the block on the page by selecting one of the buttons on the top of the <strong>Add block</strong> window. Next, click on the name of the block that you would like to add to the course.</li>
        <li>To add an existing menu to this page, select <strong>Add existing menu</strong> from the drop-down menu. Choose where you would like to place the block on the page by selecting one of the buttons on the top of the <strong>Add existing menu</strong> window. Next, click on the name of the menu that you would like to add to the course.</li>
    </ul>
</p>

<p>Available actions under the <strong>Manage</strong> Edit bar menu item:
    <ul>
        <li>To configure the settings for this page, click the <strong>Manage page settings</strong> link from the drop-down menu. From this window, you can edit the page name; change the widths of the page regions; indicate whether the page should be hidden, visible, or visible in menus; as well as determine whether the pages should have "previous and next" navigation buttons.</li>
        <li>To move a page, click the <strong>Move page</strong> link from the drop-down menu. From this window, you can choose whether the page is a child to another page, or whether it is before or after another page in the index.</li>
        <li>To delete a page, click the <strong>Delete page</strong> link from the drop-down menu. From this window, you can confirm that want to delete the current page.</li>
        <li>To manage the settings of multiple pages, click the <strong>Manage all pages</strong> link from the drop-down menu. From this window, you can view the index of all pages, and quickly manage, move or delete individual pages; as well as alter display settings; as well as control navigation settings.</li>
        <li>To manage Tabs for your course, as well as other menus, click the <strong>Manage all menus</strong> link from the drop-down menu. From this window you can create menus, as well as quickly edit menus, delete menus, and manage links within the menus.</li>
    </ul>
</p>';
