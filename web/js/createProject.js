

$(document).ready(function () {
    var $collectionHolder;

    var $addRoleLink = $('<a href="#" class="add_tag_link">Add a tag</a>');
    var $newLiLink = $('<li/>').append($addRoleLink);

    $collectionHolder = $('ul.roles');

    $collectionHolder.append($newLiLink);

    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addRoleLink.on('click', function (e) {
        e.preventDefault();

        addRoleForm($collectionHolder, $newLiLink);
    });


    function addRoleForm($mainUl,$newLinkLi) {
        var linkProto = $mainUl.data('prototype');

        var index = $mainUl.data('index');

        var nwForm = linkProto.replace(/__name__/g, index);
        $mainUl.data('index', index + 1);

        var $nwFormLi = $('<li/>').append(nwForm);
        $newLinkLi.before($nwFormLi);
    }
});