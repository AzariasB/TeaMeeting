

$(document).ready(function () {

    function init() {
        $("#button-add-meeting").on('click', launchMeetingModal);
        $("#button-add-role").on('click', launchRoleModal);
    }

    function launchRoleModal(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var pId = $(this).data('project');
        $.post(url, {'project-id': pId}, function (res) {
            $("#modal-role-content").html(res);
            $("#form-create-role").on('submit', function (e) {
                e.preventDefault();
                $(this).find("#project-id").val(pId);
                formSubmitted(url, this, respRole);
            });
            $("#modal-role").modal();
        });
    }

    function launchMeetingModal(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var pId = $(this).data('project');
        $.post(url, {}, function (res) {
            $("#modal-meeting-content").html(res);
            $("#form-create-meeting").on('submit', function (e) {
                e.preventDefault();
                $(this).find('#project-id').val(pId);
                formSubmitted(url, this, respMeeting);
            });
            $("#modal-meeting").modal();
        });
    }

    function respRole(data) {
        if (data.success) {
            var role = data.role;
            createRoleLine(role);
            $("#modal-role").modal('hide');
        } else {
            $("#modal-role-content").html(data.page);
        }
    }

    function formSubmitted(url, form, callBack) {
        var $form = $(form);
        $.post(url, $form.serialize(), callBack);
    }

    function respMeeting(data) {
        if (data.success) {
            var meeting = data.meeting;
            createMeetingLine(meeting);
            $("#modal-meeting").modal('hide');
        } else {
            $("#modal-meeting-content").html(data.page);
        }
    }

    function createRoleLine(role) {
        var $li = $('<li class="list-group-item" data-id="{{ ro.id }}" >' +
                '<strong>' + role.student.name + '</strong> : ' +
                role.name + '<span class="pull-right">' +
                '<a href="#" class="btn btn-primary btn-xs">' +
                '<i class="glyphicon glyphicon-pencil"></i> ' +
                ' Edit </a> \n <a href="#" class="btn btn-warning btn-xs">' +
                '<i class="glyphicon glyphicon-remove"></i> ' +
                ' Delete </a></span></li>');
        $("#list-roles").append($li);
    }

    function createMeetingLine(meeting) {
        var date = new Date(meeting.date.date);
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