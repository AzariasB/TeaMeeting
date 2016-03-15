

$(document).ready(function () {
    var $lines = $('.role-line');
    var rolesCount = $lines.size();
    
    for(var i = 0; i< rolesCount; i++){
        addDeleteLink($($lines.get(i)));
    }

    $('#add-role').click(function (e) {
        console.log(rolesCount);
        e.preventDefault();

        var roleList = jQuery('#roles-fields-list');

        // grab the prototype template
        var newWidget = roleList.data('prototype');
        // replace the "__name__" used in the id and name of the prototype

        newWidget = newWidget.replace(/__name__/g, rolesCount);
        rolesCount++;

        // create a new list element and add it to the list
        var newLi = $('<li/>', {class: 'role-line'}).html(newWidget);
        addDeleteLink(newLi);
        newLi.appendTo(roleList);
    });


    function addDeleteLink($target) {
        var $removeLink = $('<a href="#">Delete role</a>');
        $target.append($removeLink);

        $removeLink.on('click', function (e) {
            e.preventDefault();
            $target.remove();
        });
    }
});