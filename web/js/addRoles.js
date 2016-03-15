

$(document).ready(function () {
    console.log(rolesCount);

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
        var newLi = $('<li/>').html(newWidget);
        newLi.appendTo(roleList);
    });
});