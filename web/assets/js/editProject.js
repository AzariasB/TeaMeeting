

$(document).ready(function () {

    function init() {
        $("#button-add-meeting").on('click', launchMeetingModal)
    }

    function launchMeetingModal(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var pId = $(this).data('project');
        $.post(url, {},
                function (res) {
                    $("#modal-meeting-content").html(res);
                    $("#form-create-meeting").on('submit', function (e) {
                        e.preventDefault();
                        $(this).find('#project-id').val(pId);
                        meetingSubmitted(url, this);
                    });
                    $("#modal-meeting").modal();
                });
    }

    function meetingSubmitted(url, form) {
        var $form = $(form);
        $.post(url, $form.serialize(), respMeeting);
    }

    function respMeeting(data) {
        if (data.success) {
            var meeting = JSON.parse(data.meeting);
            createMeetingLine(meeting);
            $("#modal-meeting").modal('hide')
        } else {
            $("#modal-meeting-content").html(data.page);
        }
    }

    function createMeetingLine(meeting) {
        var date = JSON.parse(meeting.date);
        date = new Date(date.date);
        var $li = $('<li class="list-group-item" data-id="' + meeting.id + '" >' +
                ' Meeting at ' + dateString(date) +
                ' in room  ' + meeting.room +
                '<span class="pull-right">' +
                '<a href="#" class="btn btn-primary btn-xs">' +
                '<i class="glyphicon glyphicon-pencil"></i> ' +
                ' Edit </a> <a href="#" class="btn btn-warning btn-xs"> ' +
                '<i class="glyphicon glyphicon-remove"></i> ' +
                ' Delete </a></span></li>');
        $("#list-meetings").append($li);
    }

    function dateString(date) {
        return date.getDate() + '/' + date.getMonth() + '/' +
                date.getFullYear() + ' ' + date.getHours() + 1 + ':' + date.getMinutes();
    }

    init();
});