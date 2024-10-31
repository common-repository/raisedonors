function formatSelect2(repo) {
    if (repo.loading) {
        return repo.text;
    }

    return jQuery("<div class='select2-result-repository clearfix'>" +
        "<div><b>Campaign ID:</b> " + repo.campaignId + "</div>" +
        "<div><b>Campaign Title:</b> " + repo.publicTitle + "</div>" +
        "<div><b>Internal Campaign Title:</b> " + repo.internalTitle + "</div>" +
        "</div>");
}

function formatSelect2Selection(repo) {
    return (repo.publicTitle && repo.internalTitle) ? repo.publicTitle + ' (' + repo.internalTitle + ')' : repo.text;
}

jQuery(function ($) {
    elementor.hooks.addAction('panel/open_editor/widget/raise-donors', function (panel, model, view) {

        let $select2 = panel.$el.find('[data-setting="campaign"]');
        $select2.select2('destroy');

        if (model.attributes.settings.attributes.campaign) {
            $.ajax({
                url: wpApiSettings.root + 'raise-donors/v1/campaign/' + model.attributes.settings.attributes.campaign,
                dataType: 'json',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
                }
            }).done(function (response) {
                $select2.append('<option value="' + response.campaignId + '">' + response.publicTitle + ' (' + response.internalTitle + ')</option>')
                    .val(response.campaignId)
                    .trigger('change');
            });
        }

        $select2.select2({
            ajax: {
                url: wpApiSettings.root + 'raise-donors/v1/campaigns',
                dataType: 'json',
                transport: function (params, success, failure) {
                    params.beforeSend = function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
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
    });
});