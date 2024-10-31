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
