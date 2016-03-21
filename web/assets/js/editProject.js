

$(document).ready(function () {

    var actions = {
        'add-meeting': {
            'formId': 'create-meeting',
            'listId': 'list-meetings',
            'newLine': function (data) {
                var meeting = data.meeting;
                var date = new Date(meeting.date.date);
                return $('<li class="list-group-item" data-id="' + meeting.id + '" >' +
                        ' Meeting at ' + this.dateString(date) +
                        ' in room  ' + meeting.room +
                        '<span class="pull-right">' +
                        '<a href="#" class="btn btn-primary btn-xs">' +
                        '<i class="glyphicon glyphicon-pencil"></i> ' +
                        ' Edit </a> <a href="#" class="btn btn-warning btn-xs"> ' +
                        '<i class="glyphicon glyphicon-remove"></i> ' +
                        ' Delete </a></span></li>');
            },
            'dateString': function (date) {
                return date.getDate() + '/' + date.getMonth() + '/' +
                        date.getFullYear() + ' ' + date.getHours() + 1 + ':' + date.getMinutes();
            }
        },
        'add-role': {
            'formId': 'create-role',
            'listId': 'list-roles',
            'newLine': function (data) {
                var role = data.role;
                return  $('<li class="list-group-item" data-id="{{ ro.id }}" >' +
                        '<strong>' + role.student.name + '</strong> : ' +
                        role.name + '<span class="pull-right">' +
                        '<a href="#" class="btn btn-primary btn-xs">' +
                        '<i class="glyphicon glyphicon-pencil"></i> ' +
                        ' Edit </a> \n <a href="#" class="btn btn-warning btn-xs">' +
                        '<i class="glyphicon glyphicon-remove"></i> ' +
                        ' Delete </a></span></li>');
            }
        }
    };

    function init() {
        Object.keys(actions).forEach(function (i) {
            var id = "button-" + i;
            $("#" + id).on('click', function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var pId = $(this).data('project');
                firstRequest(url, pId, actions[i]);
            });
        });
    }

    function firstRequest(url, projectId, obj) {
        $.post(url, {'project-id': projectId}, function (res) {
            $("#modal-main-content").html(res);
            $("#form-" + obj.formId).on('submit', function (e) {
                e.preventDefault();
                $(this).find("#project-id").val(projectId);
                formSubmitted(url, this, obj);
            });
            $("#modal-main").modal();
        });
    }

    function addLineTo(listId, newLine) {
        $("#" + listId).append(newLine);
    }

    function formSubmitted(url, form, obj) {
        var $form = $(form);
        $.post(url, $form.serialize(), function (data) {
            if (data.success) {
                addLineTo(obj.listId, obj.newLine.call(obj, data));
                $("#modal-main").modal('hide');
            } else {
                $("#modal-main-content").html(data.page);
            }
        });
    }

    init();
});