

$(document).ready(function () {
    var $roleLines = $('.role-line');
    var $meetinLines = $('.meeting-line');
    var rolesCount = $roleLines.size();
    var meetingCount = $meetinLines.size();

    for (var i = 0; i < rolesCount; i++) {
        addDeleteLink($($roleLines.get(i)));
    }
    for (var i = 0; i < meetingCount; i++) {
        addDeleteLink($($meetinLines.get(i)));
    }

    $("#add-meeting").click(function (e) {
        e.preventDefault();
        var meetingList = $("#meetings-fields-list");
        var created = addNewLine(meetingList,meetingCount,'meeting-line');
        setMeetingDate(created,meetingCount);
        meetingCount++;
    });


    $('#add-role').click(function (e) {
        e.preventDefault();
        var roleList = jQuery('#roles-fields-list');
        addNewLine(roleList,rolesCount,'role-line');
        roleList++;
    });

    function addNewLine($target, newId, className ) {
        var nwWidget = $target.data('prototype');
        nwWidget = nwWidget.replace(/__name__/g, newId);
        
        var nwLi = $('<li/>',{class : className}).html(nwWidget);
        addDeleteLink(nwLi);
        nwLi.appendTo($target);
        return nwLi;
    }


    function addDeleteLink($target) {
        var $removeLink = $('<a href="#">Delete</a>');
        $target.append($removeLink);

        $removeLink.on('click', function (e) {
            e.preventDefault();
            $target.remove();
        });
    }
    
    function setMeetingDate(target,count){
        var now = new Date(Date.now());
        var $mainDiv = $('#project_roles_meetings_'+count+'_date');
        var $selects = $mainDiv.find('select');
        console.log($selects.size());
        console.log(now);
    }
});