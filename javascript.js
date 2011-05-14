/**
 * @namespace M.format_flexpage
 */
M.format_flexpage = M.format_flexpage || {};

/**
 * Generate the action bar menu
 *
 * @namespace M.format_flexpage
 * @function
 * @param {YUI} Y
 * @param {object} menuitems
 */
M.format_flexpage.init_actionbar = function(Y, menuitems) {
    Y.one('body').addClass('yui-skin-sam');

    var menubar = new YAHOO.widget.MenuBar("format_flexpage_actionbar_menu", {
        autosubmenudisplay: true,
        hidedelay: 750
    });
    menubar.addItems(menuitems);
    menubar.render();

    Y.all('#format_flexpage_actionbar_menu div.bd li a').on('click', function(e) {
        e.preventDefault();

        var funcName = 'init_' + e.target.get('parentNode').get('id');
        M.format_flexpage[funcName](Y, e.target.get('href'));
    });
}

M.format_flexpage.init_addpages = function(Y, url) {
    var dialog = M.format_flexpage.init_default_dialog(Y, "addpagespanel");

    // Customize buttons
    dialog.cfg.queueProperty("buttons", [
        { text: M.str.format_flexpage.addpages, handler: dialog.submit, isDefault: true }
    ]);

    M.format_flexpage.populate_panel(Y, dialog, url, function() {
        var button = new YAHOO.widget.Button('addpagebutton');
        button.on("click", function () {
            Y.one('div.format_flexpage_addpages_elements_row').appendChild(
                Y.one('#addpagetemplate div.format_flexpage_addpages_elements').cloneNode(true)
            );
        });
    });
}

M.format_flexpage.init_editpage= function(Y, url) {
    var dialog = M.format_flexpage.init_default_dialog(Y, "editpagepanel");

    dialog.validate = function() {
        var data = this.getData();
        if (data.name == "" || data.name == undefined) {
            Y.one('input[name="name"]').addClass('format_flexpage_error_bg');
            M.format_flexpage.error_dialog(Y, M.str.format_flexpage.formnamerequired);
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
                    Y.one('#condition_templates .format_flexpage_condition_grade').cloneNode(true)
                );
            });
        }
        if (Y.one('#condition_completion_add_button')) {
            var buttonCompletion = new YAHOO.widget.Button('condition_completion_add_button');
            buttonCompletion.on("click", function () {
                Y.one('#condition_completions').appendChild(
                        Y.one('#condition_templates .format_flexpage_condition_completion').cloneNode(true)
                        );
            });
        }
        M.format_flexpage.init_calendar(Y, 'availablefrom');
        M.format_flexpage.init_calendar(Y, 'availableuntil');

        // Re-center because calendar rendering can extend the panel
        dialog.center();
    });
}

M.format_flexpage.init_addactivity = function(Y, url) {
    var panel = M.format_flexpage.init_default_panel(Y, "addactivitypanel");

    M.format_flexpage.populate_panel(Y, panel, url, function(buttons) {
        var buttonGroup = M.format_flexpage.init_region_buttons(Y, buttons);

        Y.all('a.format_flexpage_addactivity_link').on('click', function(e) {
            e.preventDefault();

            // Update our form so we know what the user selected
            Y.one('input[name="addurl"]').set('value', e.target.get('href'));

            // Update our form so we know what region was selected
            M.format_flexpage.set_region_input(Y, buttonGroup, 'region');

            document.getElementById('addactivity_form').submit();
        });
    });
}

M.format_flexpage.init_addexistingactivity = function(Y, url) {
    var dialog = M.format_flexpage.init_default_dialog(Y, "addexistingactivitypanel");

    var buttonGroup;

    // Customize buttons
    dialog.cfg.queueProperty("buttons", [
        { text: M.str.format_flexpage.addactivities, isDefault: true, handler: function() {
            M.format_flexpage.set_region_input(Y, buttonGroup, 'region');
            dialog.submit();
        }}
    ]);

    M.format_flexpage.populate_panel(Y, dialog, url, function(buttons) {
        buttonGroup = M.format_flexpage.init_region_buttons(Y, buttons);
    });
}

M.format_flexpage.init_addblock = function(Y, url) {
    var dialog = M.format_flexpage.init_default_panel(Y, "addblockpanel");

    // Customize buttons
    dialog.cfg.queueProperty("buttons", []);

    M.format_flexpage.populate_panel(Y, dialog, url, function(buttons) {
        var buttonGroup = M.format_flexpage.init_region_buttons(Y, buttons);

        Y.all('#format_flexpage_addblock_links a').on('click', function(e) {
            e.preventDefault();

            // Update our form so we know what the user selected
            Y.one('input[name="blockname"]').set('value', e.target.get('name'));

            // Update our form so we know what region was selected
            M.format_flexpage.set_region_input(Y, buttonGroup, 'region');

            dialog.submit();
        });
    });
}

M.format_flexpage.init_movepage = function(Y, url) {
    var dialog = M.format_flexpage.init_default_dialog(Y, "movepagepanel");

    // Customize buttons
    dialog.cfg.queueProperty("buttons", [
        { text: M.str.format_flexpage.movepage, handler: dialog.submit, isDefault: true }
    ]);

    M.format_flexpage.populate_panel(Y, dialog, url);
}

M.format_flexpage.init_default_dialog = function(Y, id) {
    var dialog = new YAHOO.widget.Dialog(id, {
        // postmethod: 'form', // Very handy for debugging
        constraintoviewport: true,
        modal: true,
        underlay: "none",
        close: true,
        buttons: [
            { text: M.str.moodle.savechanges, handler: function() { this.submit() }, isDefault: true }
        ]
    });
    dialog.callback.success = function(o) {
        window.location.reload();
    };
    dialog.callback.failure = function(o) {
        M.format_flexpage.error_dialog(Y, M.str.format_flexpage.genericasyncfail);
    };
    return dialog;
}

M.format_flexpage.init_default_panel = function(Y, id) {
    return new YAHOO.widget.Panel(id, {
        constraintoviewport: true,
        modal: true,
        underlay: "none",
        close: true
    });
}

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

                // @todo Remove this?
                panel.render(document.body);
                panel.show();
                panel.center();

                if (typeof onsuccess == 'function') {
                    if (response.args != undefined) {
                        onsuccess(response.args);
                    } else {
                        onsuccess();
                    }
                }
            },
            failure: function(id, o) {
                M.format_flexpage.error_dialog(Y, M.str.format_flexpage.genericasyncfail);
            }
        }
    });
}

M.format_flexpage.error_dialog = function(Y, errorMessage) {
    var errorDialog = new YAHOO.widget.SimpleDialog("errorDialog", {
        constraintoviewport: true,
        modal: true,
        underlay: "none",
        close: true,
        text: errorMessage,
        icon: YAHOO.widget.SimpleDialog.ICON_WARN,
        buttons: [
            { text: M.str.format_flexpage.close, handler: function () { this.hide(); }, isDefault:true }
        ]
    });
    errorDialog.setHeader(M.str.format_flexpage.error);
    errorDialog.render(document.body);
    errorDialog.show();
    errorDialog.center();
}

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
}

M.format_flexpage.init_region_buttons = function(Y, buttons) {
    var buttonGroup = new YAHOO.widget.ButtonGroup({
        id: "format_flexpage_region_radios_id",
        name: "region",
        container: "format_flexpage_region_radios"
    });
    buttonGroup.addButtons(buttons);

    return buttonGroup;
}

M.format_flexpage.set_region_input = function(Y, buttonGroup, inputname) {
    var buttons = buttonGroup.getButtons();
    for (var i = 0; i < buttons.length; i++) {
        var button = buttons[i];
        if (button.get('checked')) {
            Y.one('input[name="' + inputname + '"]').set('value', button.get('value'));
            break;
        }
    }
}