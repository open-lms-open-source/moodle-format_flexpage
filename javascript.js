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

M.format_flexpage.init_movepage = function(Y, url) {
    var dialog = M.format_flexpage.init_default_dialog(Y, "movepagepanel");

    // Customize buttons
    dialog.cfg.queueProperty("buttons", [
        { text: M.str.format_flexpage.movepage, handler: dialog.submit, isDefault: true }
    ]);

    M.format_flexpage.populate_panel(Y, dialog, url);
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
                    onsuccess();
                }
            },
            failure: function(id, o) {
                M.format_flexpage.error_dialog(Y, M.str.format_flexpage.genericasyncfail);
            }
        }
    });
}

M.format_flexpage.init_default_dialog = function(Y, id) {
    var dialog = new YAHOO.widget.Dialog(id, {
        // postmethod: 'form', // Very handy for debugging
        constraintoviewport: true,
        modal: true,
        underlay: "none",
        close: true,
        buttons: [
            { text: M.str.moodle.savechanges, handler: function() { this.submit() }, isDefault: true },
            { text: M.str.moodle.cancel, handler: function() { this.cancel() } }
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