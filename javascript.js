/**
 * @namespace M.format_flexpage
 */
M.format_flexpage = M.format_flexpage || {};

/**
 * Keeps track of opened panels
 */
M.format_flexpage.panel_stack = [];

/**
 * Keeps track of any panel ever created
 */
M.format_flexpage.panels = [];

/**
 * Generate the action bar menu
 *
 * @param {YUI} Y
 */
M.format_flexpage.init_actionbar = function(Y) {
    Y.use('node-menunav', function(Y) {
        var node = Y.one('#format_flexpage_actionbar');
        node.removeClass('javascript-disabled');
        node.plug(Y.Plugin.NodeMenuNav);

        node.on('click', function(e) {
            // Make sure it's not some other menu item
            if (e.target.ancestor('#format_flexpage_actionbar_nav')) {
                return;
            }
            if (e.target.hasClass('yui3-menu-label')) {
                return;
            }
            if (e.target.get('tagName').toUpperCase() != 'A') {
                return;
            }

            // This trickery hides the menu after an item has been chosen
            var menuBarItem = Y.one('a.yui3-menu-label-menuvisible');
            if (menuBarItem) {
                menuBarItem.removeClass('yui3-menu-label-menuvisible');
            }
            e.target.ancestor('div.yui3-menu').addClass('yui3-menu-hidden');
            // End trickery :(

            e.preventDefault();

            var params = Y.QueryString.parse(e.target.get('href').split('?')[1]);
            if (params.action != undefined) {
                M.format_flexpage['init_' + params.action](Y, e.target.get('href'));
            }
        });
    });

    Y.one('#format_flexpage_actionbar_help').on('click', function(e) {
        M.format_flexpage.init_actionbar_help_icon(Y);
    });
};

/**
 * General method to execute JS after DOM has loaded on the editing screen
 *
 * @param Y
 */
M.format_flexpage.init_edit = function(Y) {
    // Add a warning when the delete widget is click for a module
    Y.all('.editing .commands a.editing_delete').on('click', function(e) {
        if (e.target.test('a')) {
            var url = e.target.get('href');
        } else if ((targetancestor = e.target.ancestor('a')) !== null) {
            var url = targetancestor.get('href');
        }
        if (url != undefined) {
            e.preventDefault();

            var dialog = new YAHOO.widget.SimpleDialog("modDeleteDialog", {
                constraintoviewport: true,
                modal: true,
                underlay: "none",
                close: true,
                text: M.str.format_flexpage.deletemodwarn,
                icon: YAHOO.widget.SimpleDialog.ICON_WARN,
                buttons: [
                    { text: M.str.moodle.cancel, handler: function () { this.hide(); }, isDefault:true },
                    { text: M.str.format_flexpage.continuedotdotdot, handler: function () { this.hide(); window.location = url } }
                ]
            });
            dialog.setHeader(M.str.format_flexpage.warning);
            dialog.render(document.body);
            dialog.show();
            dialog.center();
        }
    });
};

/**
 * Init add pages modal
 *
 * @param Y
 * @param url
 */
M.format_flexpage.init_addpages = function(Y, url) {
    var dialog = M.format_flexpage.init_default_dialog(Y, "addpagespanel");

    // Customize buttons
    dialog.cfg.queueProperty("buttons", [
        { text: M.str.moodle.cancel, handler: dialog.cancel },
        { text: M.str.format_flexpage.addpages, handler: dialog.submit, isDefault: true }
    ]);

    M.format_flexpage.populate_panel(Y, dialog, url, function() {
        var button = new YAHOO.widget.Button('addpagebutton');
        button.on("click", function () {
            Y.one('div.format_flexpage_addpages_elements_row').appendChild(
                Y.one('#format_flexpage_addpages_template div.format_flexpage_addpages_elements').cloneNode(true)
            );
        });
    });

    return dialog;
};

/**
 * Init edit page modal
 *
 * @param Y
 * @param url
 */
M.format_flexpage.init_editpage = function(Y, url) {
    var dialog = M.format_flexpage.init_default_dialog(Y, "editpagepanel");

    dialog.validate = function() {
        var data = this.getData();
        if (data.name == undefined || Y.Lang.trim(data.name) == "") {
            Y.one('input[name="name"]').addClass('format_flexpage_error_bg');
            M.format_flexpage.init_error_dialog(Y, M.str.format_flexpage.formnamerequired);
            return false;
        } else {
            return true;
        }
    };

    M.format_flexpage.populate_panel(Y, dialog, url, function() {
        // Clears any validation error coloring
        Y.one('input[name="name"]').on('focus', function(e) {
            e.target.removeClass('format_flexpage_error_bg');
        });

        if (Y.one('#condition_grade_add_button')) {
            var buttonGrade = new YAHOO.widget.Button('condition_grade_add_button');
            buttonGrade.on("click", function () {
                Y.one('#condition_grades').appendChild(
                    Y.one('#format_flexpage_condition_templates .format_flexpage_condition_grade').cloneNode(true)
                );
            });
        }
        if (Y.one('#condition_completion_add_button')) {
            var buttonCompletion = new YAHOO.widget.Button('condition_completion_add_button');
            buttonCompletion.on("click", function () {
                Y.one('#condition_completions').appendChild(
                    Y.one('#format_flexpage_condition_templates .format_flexpage_condition_completion').cloneNode(true)
                );
            });
        }
        M.format_flexpage.init_calendar(Y, 'availablefrom');
        M.format_flexpage.init_calendar(Y, 'availableuntil');

        // Re-center because calendar rendering can extend the panel
        dialog.center();
    });

    return dialog;
};

/**
 * Init delete page modal
 *
 * @param Y
 * @param url
 */
M.format_flexpage.init_deletepage = function(Y, url) {
    var dialog = M.format_flexpage.init_default_dialog(Y, "deletepagepanel");

    // Customize buttons
    dialog.cfg.queueProperty("buttons", [
        { text: M.str.moodle.cancel, handler: dialog.cancel, isDefault: true },
        { text: M.str.format_flexpage.deletepage, handler: dialog.submit }
    ]);

    M.format_flexpage.populate_panel(Y, dialog, url);

    return dialog;
};

/**
 * Init Manage pages modal (this opens other modals)
 *
 * @param Y
 * @param url
 */
M.format_flexpage.init_managepages = function(Y, url) {
    var dialog = M.format_flexpage.init_default_dialog(Y, "managepagespanel");

    // Remove buttons
    dialog.cfg.queueProperty("buttons", []);

    // When the user finally hides the dialog, we reload the page
    dialog.hideEvent.subscribe(function(e) {
        if (M.format_flexpage.panel_stack.length == 0) {
            window.location.reload();
        }
    });

    M.format_flexpage.populate_panel(Y, dialog, url, function() {
        M.format_flexpage.constrain_panel_to_viewport(Y, dialog);

        Y.all('select.format_flexpage_actions_select').each(function(node) {
            M.format_flexpage.init_action_menu(Y, node, dialog, function () {
                return M.format_flexpage.init_managepages(Y, url);
            });
        });

        Y.all('select.format_flexpage_action_select').each(function(node) {
            var button = M.format_flexpage.init_button_menu(Y, node);

            button.on("selectedMenuItemChange", function(e) {
                var menuItem = e.newValue;
                var label    = menuItem.cfg.getProperty("text");

                // Have a loading image while the change is taking place
                this.set("label", label + ' <img class="icon" src="' + M.util.image_url('i/loading') + '" />');
                this.set('disabled', true);

                Y.io(menuItem.value, {
                    method: 'post',
                    arguments: {
                        button: button,
                        label: label
                    },
                    on: {
                        complete: function(id, o, arguments) {
                            arguments.button.set("label", arguments.label);
                            arguments.button.set('disabled', false);
                        },
                        failure: function(id, o, arguments) {
                            M.format_flexpage.init_error_dialog(Y, M.str.format_flexpage.genericasyncfail);
                        }
                    }
                });
            });
        });
    });

    return dialog;
};

/**
 * Init add activity modal
 *
 * This one actually submits instead of using AJAX
 *
 * @param Y
 * @param url
 */
M.format_flexpage.init_addactivity = function(Y, url) {
    var dialog = M.format_flexpage.init_default_dialog(Y, "addactivitypanel");

    // Remove buttons
    dialog.cfg.queueProperty("buttons", []);

    // Actually post to the endpoint
    dialog.cfg.queueProperty('postmethod', 'form');

    M.format_flexpage.populate_panel(Y, dialog, url, function(buttons) {
        var buttonGroup = M.format_flexpage.init_region_buttons(Y, buttons);
        M.format_flexpage.constrain_panel_to_viewport(Y, dialog);

        Y.all('a.format_flexpage_addactivity_link').on('click', function(e) {
            e.preventDefault();

            // Update our form so we know what the user selected
            Y.one('input[name="addurl"]').set('value', e.target.get('href'));

            // Update our form so we know what region was selected
            M.format_flexpage.set_region_input(Y, buttonGroup, 'region');

            dialog.submit();
        });
    });

    return dialog;
};

/**
 * Init add existing activity modal
 *
 * @param Y
 * @param url
 */
M.format_flexpage.init_addexistingactivity = function(Y, url) {
    var dialog = M.format_flexpage.init_default_dialog(Y, "addexistingactivitypanel");

    var buttonGroup;

    // Customize buttons
    dialog.cfg.queueProperty("buttons", [
        { text: M.str.moodle.cancel, handler: dialog.cancel },
        { text: M.str.format_flexpage.addactivities, isDefault: true, handler: function() {
            M.format_flexpage.set_region_input(Y, buttonGroup, 'region');
            dialog.submit();
        }}
    ]);

    M.format_flexpage.populate_panel(Y, dialog, url, function(buttons) {
        M.format_flexpage.constrain_panel_to_viewport(Y, dialog);
        buttonGroup = M.format_flexpage.init_region_buttons(Y, buttons);
    });

    return dialog;
};

/**
 * Init add block modal
 *
 * @param Y
 * @param url
 */
M.format_flexpage.init_addblock = function(Y, url) {
    var dialog = M.format_flexpage.init_default_dialog(Y, "addblockpanel");

    // Remove buttons
    dialog.cfg.queueProperty("buttons", []);

    M.format_flexpage.populate_panel(Y, dialog, url, function(buttons) {
        var buttonGroup = M.format_flexpage.init_region_buttons(Y, buttons);
        M.format_flexpage.constrain_panel_to_viewport(Y, dialog);

        Y.all('#format_flexpage_addblock_links a').on('click', function(e) {
            e.preventDefault();

            // Update our form so we know what the user selected
            Y.one('input[name="blockname"]').set('value', e.target.get('name'));

            // Update our form so we know what region was selected
            M.format_flexpage.set_region_input(Y, buttonGroup, 'region');

            dialog.submit();
        });
    });

    return dialog;
};

/**
 * Init move page modal
 *
 * @param Y
 * @param url
 */
M.format_flexpage.init_movepage = function(Y, url) {
    var dialog = M.format_flexpage.init_default_dialog(Y, "movepagepanel");

    // Customize buttons
    dialog.cfg.queueProperty("buttons", [
        { text: M.str.moodle.cancel, handler: dialog.cancel },
        { text: M.str.format_flexpage.movepage, handler: dialog.submit, isDefault: true }
    ]);

    M.format_flexpage.populate_panel(Y, dialog, url, function() {
        if (Y.one('.format_flexpage_movepage_nooptions')) {
            dialog.cfg.queueProperty("buttons", [
                { text: M.str.format_flexpage.close, handler: dialog.cancel, isDefault: true }
            ]);
            dialog.render();  // Must re-render due to button change
        }
    });

    return dialog;
};

/**
 * Init default panel
 *
 * @param Y
 * @param id
 */
M.format_flexpage.init_default_panel = function(Y, id) {
    if (M.format_flexpage.panels[id] != undefined) {
        M.format_flexpage.panels[id].destroy();
        delete M.format_flexpage.panels[id];
    }
    M.format_flexpage.panels[id] = new YAHOO.widget.Panel(id, {
        constraintoviewport: true,
        modal: true,
        underlay: "none",
        close: true
    });
    return M.format_flexpage.panels[id];
};

/**
 * Init default dialog
 *
 * @param Y
 * @param id
 */
M.format_flexpage.init_default_dialog = function(Y, id) {
    // Due to odd bugs (which I think has to do with events) we
    // must destroy any panel that we have previously created
    if (M.format_flexpage.panels[id] != undefined) {
        M.format_flexpage.panels[id].destroy();
        delete M.format_flexpage.panels[id];
    }
    var dialog = new YAHOO.widget.Dialog(id, {
        // postmethod: 'form', // Very handy for debugging
        constraintoviewport: true,
        // fixedcenter: true, // @todo It's actually kind of nice
        modal: true,
        zIndex: 100,
        underlay: "none",
        close: true,
        buttons: [
            { text: M.str.moodle.cancel, handler: function() { this.cancel() } },
            { text: M.str.moodle.savechanges, handler: function() { this.submit() }, isDefault: true }
        ]
    });
    dialog.callback.success = function(o) {
        window.location.reload();
    };
    dialog.callback.failure = function(o) {
        M.format_flexpage.init_error_dialog(Y, M.str.format_flexpage.genericasyncfail);
    };

    M.format_flexpage.panels[id] = dialog;

    return dialog;
};

/**
 * Populates a panel (or dialog) with information found at endpoint
 *
 * @param Y
 * @param url
 */
M.format_flexpage.populate_panel = function(Y, panel, url, onsuccess) {
    Y.io(url, {
        on: {
            success: function(id, o) {
                var response = Y.JSON.parse(o.responseText);

                if (response.header != undefined) {
                    panel.setHeader(response.header);
                }
                if (response.body != undefined) {
                    panel.setBody(response.body);
                }
                if (response.footer != undefined) {
                    panel.setFooter(response.footer);
                }
                panel.render(document.body);
                panel.show();
                panel.center();

                M.format_flexpage.init_help_icons(Y);

                if (typeof onsuccess == 'function') {
                    if (response.args != undefined) {
                        onsuccess(response.args);
                    } else {
                        onsuccess();
                    }
                }
            },
            failure: function(id, o) {
                M.format_flexpage.init_error_dialog(Y, M.str.format_flexpage.genericasyncfail);
            }
        }
    });
};

/**
 * Connect two dialogs - used when one dialog pops up another
 *
 * @param Y
 * @param parent Parent dialog
 * @param child Child dialog (spawned from parent)
 * @param reInit A callback to re-initialize the parent dialog
 */
M.format_flexpage.connect_dialogs = function(Y, parent, child, reInit) {

    M.format_flexpage.panel_stack.push({
        dialog: parent,
        reInit: reInit
    });
    M.format_flexpage.connect_dialogs_attach_events(Y, child);
};

/**
 * Attach event handlers to a child dialog
 *
 * @param Y
 * @param child
 */
M.format_flexpage.connect_dialogs_attach_events = function(Y, child) {
    // When child shows, hide parent
    child.beforeShowEvent.subscribe(function(e) {
        M.format_flexpage.panel_stack[M.format_flexpage.panel_stack.length - 1].dialog.hide();
    });

    // Re-init parent when child submits
    child.callback.success = function(o) {
        M.format_flexpage.connect_dialogs_attach_events(Y, M.format_flexpage.panel_stack.pop().reInit());
    };

    // Show error dialog and re-init parent when child submit fails
    child.callback.failure = function(o) {
        M.format_flexpage.init_error_dialog(Y, M.str.format_flexpage.genericasyncfail).hideEvent.subscribe(function(e) {
            M.format_flexpage.connect_dialogs_attach_events(Y, M.format_flexpage.panel_stack.pop().reInit());
        });
    };

    // Show parent when child has been canceled
    child.cancelEvent.subscribe(function(e) {
        M.format_flexpage.panel_stack.pop().dialog.show();
    });
};

/**
 * Init generic error dialog
 *
 * @param Y
 * @param url
 */
M.format_flexpage.init_error_dialog = function(Y, errorMessage) {
    var dialog = new YAHOO.widget.SimpleDialog("errorDialog", {
        constraintoviewport: true,
        modal: true,
        zIndex: 200,
        underlay: "none",
        close: true,
        text: errorMessage,
        icon: YAHOO.widget.SimpleDialog.ICON_WARN,
        buttons: [
            { text: M.str.format_flexpage.close, handler: function () { this.hide(); }, isDefault:true }
        ]
    });
    dialog.setHeader(M.str.format_flexpage.error);
    dialog.render(document.body);
    dialog.show();
    dialog.center();

    return dialog;
};

/**
 * Init calendar
 *
 * @param Y
 * @param url
 */
M.format_flexpage.init_calendar = function(Y, name) {

    var renderingId   = "calendar" + name;
    var calendarId    = "calendar" + name + '_id';
    var checkboxName  = "enable" + name;
    var renderingNode = Y.one('#' + renderingId);

    if (renderingNode) {
        var input    = Y.one('input[name="' + name + '"]');
        var checkbox = Y.one('input[name="' + checkboxName + '"]');

        var calendar = new YAHOO.widget.Calendar(calendarId, renderingId, {
            selected: input.get('value')
        });
        calendar.selectEvent.subscribe(function(type, args, obj) {
            var date = args[0][0];
            var year = date[0], month = date[1], day = date[2];
            input.set('value', month + "/" + day + "/" + year);
        }, calendar, true)

        calendar.render();

        if (checkbox.get('checked')) {
            renderingNode.removeClass('hiddenifjs');
        }
        checkbox.on('change', function(e) {
            if (checkbox.get('checked')) {
                renderingNode.removeClass('hiddenifjs');
            } else {
                renderingNode.addClass('hiddenifjs');
            }
        });
    }
};

/**
 * Init region buttons (turns a radio group into buttons
 *
 * @param Y
 * @param url
 */
M.format_flexpage.init_region_buttons = function(Y, buttons) {
    var buttonGroup = new YAHOO.widget.ButtonGroup({
        id: "format_flexpage_region_radios_id",
        name: "region",
        container: "format_flexpage_region_radios"
    });
    buttonGroup.addButtons(buttons);

    return buttonGroup;
};

/**
 * Syncs currently selected region button to a hidden input element
 *
 * @param Y
 * @param buttonGroup
 * @param inputname
 */
M.format_flexpage.set_region_input = function(Y, buttonGroup, inputname) {
    var buttons = buttonGroup.getButtons();
    for (var i = 0; i < buttons.length; i++) {
        var button = buttons[i];
        if (button.get('checked')) {
            Y.one('input[name="' + inputname + '"]').set('value', button.get('value'));
            break;
        }
    }
};

/**
 * Converts a select element into a YUI button menu
 *
 * @param Y
 * @param select
 */
M.format_flexpage.init_button_menu = function(Y, select) {
    var index  = select.get('selectedIndex');
    var label  = select.get("options").item(index).get('innerHTML');

    return new YAHOO.widget.Button({
        id: select.get('id') + '_menuid',
        name: select.get('id') + '_menuname',
        label: label,
        type: "menu",
        menu: select.get('id'),
        container: select.get('parentNode').get('id'),
        lazyloadmenu: true
    });
};

/**
 * Convert a select element into a menu button that launches other modals
 *
 * @param Y
 * @param selectNode
 * @param parentDialog
 * @param reInitParentDialog
 */
M.format_flexpage.init_action_menu = function(Y, selectNode, parentDialog, reInitParentDialog) {
    var button = M.format_flexpage.init_button_menu(Y, selectNode);
    button.set('label', M.str.moodle.choosedots);

    button.on("selectedMenuItemChange", function(e) {
        var menuItem = e.newValue;
        var info     = Y.JSON.parse(menuItem.value);
        var funcName = 'init_' + info.action;
        var dialog   = M.format_flexpage[funcName](Y, info.url);

        M.format_flexpage.connect_dialogs(Y, parentDialog, dialog, reInitParentDialog);
    });

    return button;
};

/**
 * Handler for when the help icon is pushed in the action bar
 *
 * @param Y
 */
M.format_flexpage.init_actionbar_help_icon = function(Y) {
    var panel = M.format_flexpage.init_default_panel(Y, 'actionhelpbuttonpanel');

    panel.cfg.queueProperty('width', '500px');

    panel.setHeader(M.str.format_flexpage.actionbar);
    panel.setBody(M.str.format_flexpage.actionbar_help);
    panel.render(document.body);
    panel.show();
    panel.center();

    M.format_flexpage.constrain_panel_to_viewport(Y, panel);

    return panel;
};

/**
 * Make Moodle help icons active that come back through JSON response
 *
 * @param Y
 */
M.format_flexpage.init_help_icons = function(Y) {
    Y.all('span.format_flexpage_helpicon').each(function(node) {
        // Prevent re-processing help icons
        if (!node.hasClass('format_flexpage_helpicon_processed')) {
            node.addClass('format_flexpage_helpicon_processed');

            var atag = node.one('a');

            M.util.help_icon.add(Y, {
                id: atag.get('id'),
                url: atag.get('href')
            })
        }
    });
};

/**
 * When a panel has an unknown height (potentially very large) then
 * use this to constrain its height to the view port
 *
 * @param Y
 * @param panel
 */
M.format_flexpage.constrain_panel_to_viewport = function(Y, panel) {
    var headerHeight = 0;
    var footerHeight = 0;
    var panelPadding = 20;

    if (panel.footer != undefined) {
        footerHeight = panel.footer.offsetHeight;
    }
    if (panel.header != undefined) {
        headerHeight = panel.header.offsetHeight;
    }

    // We take total height minus view port offset minus footer minus header minus panel body padding
    var height = YAHOO.util.Dom.getViewportHeight() - (YAHOO.widget.Overlay.VIEWPORT_OFFSET * 2) - footerHeight - headerHeight - panelPadding;

    Y.one('#' + panel.id + ' .bd').setStyle('maxHeight', height + 'px')
                                  .setStyle('overflow', 'auto');

    // Odd bug where panels that are larger than view port, make the view port scroll down
    window.scrollTo(0, 0);
    panel.center();
}
