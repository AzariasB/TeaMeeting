
var app = angular.module('app',[]);

app.controller('controller',function(){
    
});

$(document).ready(function () {

    var actions = {
        'add-meeting': {
            'formId': 'create-meeting',
            'listId': 'list-meetings',
            'newLine': function (data) {
                var meeting = data.meeting;
                var date = new Date(meeting.date.date);
                var deleteHref = $("#path-remove-meeting").data('href').replace(/__name__/, meeting.id);
                return $('<li class="list-group-item" data-id="' + meeting.id + '" >' +
                        ' Meeting at ' + this.dateString(date) +
                        ' in room  ' + meeting.room +
                        '<span class="pull-right">' +
                        '<a href="#" class="btn btn-primary btn-xs">' +
                        '<i class="glyphicon glyphicon-pencil"></i> ' +
                        ' Edit </a> <a href="' + deleteHref +
                        '" class="btn btn-warning btn-xs btn-remove"> ' +
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
                var deleteHref = $("#path-remove-role").data('href').replace(/__name__/, role.id);
                return  $('<li class="list-group-item" data-id="' + role.id + '" >' +
                        '<strong>' + role.student.name + '</strong> : ' +
                        role.name + '<span class="pull-right">' +
                        '<a href="#" class="btn btn-primary btn-xs">' +
                        '<i class="glyphicon glyphicon-pencil"></i> ' +
                        ' Edit </a> \n <a href="' + deleteHref +
                        '" class="btn btn-warning btn-xs btn-remove">' +
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

        $("#close-alert").on('click', hideAlert);

        $("body").on('click', '.btn-remove', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var $parent = $(this).parents('li');

            //Set timeout to destruction
            var t = setTimeout(function () {
                confirmedDelete(url, $parent);
            }, 5000);

            //Hide object
            $parent.hide();

            //Show alert to the user
            showAlert("user", function () {
                clearTimeout(t);
                $parent.show();
            });
        });
    }

    function confirmedDelete(url, $div) {
        hideAlert();
        $.post(url, {}, function (res) {
            if (!res.success) {
                //Show problem
                showAlert('User not deleted');
            }
        });
        $div.remove();
    }

    function showAlert(removed, action) {
        $("#removed-object").text(removed);
        $("#message-alert").addClass('show');
        $("#cancel-action").on('click', function (e) {
            e.preventDefault();
            action.call();
            hideAlert();
            $(this).off('click');
        });
    }

    function hideAlert() {
        $("#message-alert").removeClass('show');
    }

    function firstRequest(url, projectId, obj) {
        $.post(url, {'project-id': projectId}, function (res) {
            $("#modal-main-content").html(res);
            $("#" + obj.formId + "-form").on('submit', function (e) {
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