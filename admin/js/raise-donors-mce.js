(function ($, tinymce) {
    "use strict"; // Failsafe in case variables were not properly declared on page.
    if (typeof rd_shortcodes_ui === 'undefined') {
        return;
    }
    let $myDialog = $('<div id="my-dialog" class="hidden" style="max-width:800px"><p class="post-attributes-label-wrapper page-template-label-wrapper"><label class="post-attributes-label" for="raise-donors-campaign">' + rd_shortcodes_ui.I10n.popup_select_campaign_title + '</label></p><select name="raise-donors-campaign" id="raise-donors-campaign" style="width: 100%"></select><p class="post-attributes-label-wrapper page-template-label-wrapper"><label class="raise-donors-display" for="raise-donors-display">' + rd_shortcodes_ui.I10n.popup_select_campaign_display_type + '</label></p><select name="raise" id="raise-donors-display" style="width: 100%"><option value="full">' + rd_shortcodes_ui.I10n.popup_select_campaign_display_type_full + '</option><option value="form">' + rd_shortcodes_ui.I10n.popup_select_campaign_display_type_form + '</option></select><br><br><input type="button" value="' + rd_shortcodes_ui.I10n.popup_submit + '" class="button-primary" id="generate-shortcode"/></div>'),
        $campaign = $myDialog.find('#raise-donors-campaign'),
        $display = $myDialog.find('#raise-donors-display'),
        $generate = $myDialog.find('#generate-shortcode');
    $('body').append($myDialog);

    $campaign.select2({
        dropdownParent: $myDialog,
        ajax: {
            url: rd_shortcodes_ui.rest_endpoint,
            dataType: 'json',
            transport: function (params, success, failure) {
                params.beforeSend = function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', rd_shortcodes_ui.nonce);
                };
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            }
        },
        templateResult: formatSelect2,
        templateSelection: formatSelect2Selection
    });

    $myDialog.dialog({
        title: rd_shortcodes_ui.I10n.popup_title,
        dialogClass: 'wp-dialog',
        autoOpen: false,
        draggable: false,
        width: 'auto',
        modal: true,
        resizable: false,
        closeOnEscape: true,
        position: {
            my: "center",
            at: "center",
            of: window
        },
        create: function () {
            // style fix for WordPress admin
            $('.ui-dialog-titlebar-close').addClass('ui-button');
        },
    });

    $generate.on('click', function () {
        if ($campaign.val()) {
            let shortcode = '[raise-donors campaignId="' + $campaign.val() + '" display="' + $display.val() + '"]';
            tinymce.execCommand('mceInsertContent', false, shortcode);
            $myDialog.dialog('close');
        }
    });


    tinymce.PluginManager.add('rd_shortcodes', function (editor) {
        editor.addButton('rd_shortcodes', {
            type: 'button',
            icon: 'rd_shortcodes',
            tooltip: rd_shortcodes_ui.I10n.shortcode_ui_button_tooltip || '',
            onclick: function onclick() {
                $myDialog.dialog('open');
            }
        });
    });


})(jQuery, tinymce || {});