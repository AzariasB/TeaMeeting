
$(document).ready(function () {

    var seeProfilePath = $("#profilePath").text(),
            seeProjectPath = $("#seeProjectPath").data('href'),
            lockProjectPath = $("#lockProjectPath").data('href'),
            deleteProjectPath = $("#deleteProjectPath").data('href');

    var idsDivs = {
        'see-profile': 'admin-profile',
        'see-projects': 'projects-list',
        'see-users': 'users-list'
    };

    function init() {
        /**
         * Click on a nav button => show the equivalent
         * div
         */
        Object.keys(idsDivs).forEach(function (key) {
            $("#" + key).on('click', function (e) {
                changeHash(key);
                e.preventDefault();
                hideDivs();
                disActivate();
                $("#" + idsDivs[key]).removeClass('hidden');
                $(this).addClass('active');
            });
        });

        var h = window.location.hash.substr(1);
        if (h) {
            if(idsDivs[h]){
                $("#"+h).click();
            }else{
                changeHash('');
            }
        }

        $("#create_user").on('click', launchCreateUserModal);
        $("#create-project").on('click', launchCreateProjectModal);
    }

    function changeHash(nwHash) {
        window.location.hash = nwHash;
    }

    function hideDivs() {
        $(".admin-item").addClass('hidden');
    }

    function disActivate() {
        $(".admin-choice").removeClass('active');
    }

    function launchCreateProjectModal(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.post(url, {}, function (res) {
            $("#projectModal .modal-content").html(res);
            $("#create-project-form").on('submit', function (e) {
                e.preventDefault();
                $("#submit-create-project").prop('disabled', true);
                postForm($(this).serialize(), url, confirmProjectCreation);
            });
            $("#projectModal").modal();
        });
    }

    function confirmProjectCreation(data) {
        if (data.success) {
            addProject(data.projectName, data.projectId);
            $("#projectModal").modal('hide');
        } else {
            $("#projectModal .modal-content").html(data.newContent);
        }
    }

    function launchCreateUserModal(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.post(url, {},
                function (res) {
                    $("#userModal .modal-content").html(res);
                    $("#create-user-form").on('submit', function (e) {
                        e.preventDefault();
                        $("#submit-create-user").prop('disabled', true);
                        postForm($(this).serialize(), url, confirmUserCreation);
                    });
                    $("#userModal").modal();
                });
    }

    function confirmUserCreation(data) {
        if (data.success) {
            addUser(data.username, data.userId);
            $("#userModal .modal-content .modal-body")
                    .html("The user's pseudo is <b>" + data.password + "</b>");
        } else {
            $("#userModal .modal-content .modal-body").html(data.error);
        }
    }

    function addProject(projectName, projectId) {
        var seeHref = seeProjectPath.replace(/__number__/, projectId),
                lockHref = lockProjectPath.replace(/__number__/, projectId),
                deleteHref = deleteProjectPath.replace(/__number__/, projectId);
        var $li = $('<li class="list-group-item clearfix" >' +
                '<a href="' + seeHref + '">' + projectName + '</a>' +
                '<span class="pull-right" ><a class="btn btn-warning btn-sm" ' +
                'href="' + lockHref + '"><i class="fa fa-lock" ></i>' +
                ' Lock </a>\n<a class="btn btn-danger btn-sm"' +
                'href="' + deleteHref + '"><i class="glyphicon glyphicon-trash" ></i>' +
                ' Delete </a></span></li>');
        $("#project_list").append($li);
    }

    function addUser(userName, userId) {
        var href = seeProfilePath.replace(/__number__/, userId);
        var $li = $('<li class="list-group-item clearfix" >' +
                '<a href="' + href + '">' + userName + '</a>' +
                '<span class="pull-right">' +
                '<a class="btn btn-xs btn-danger" href="#">' +
                '<i class="glyphicon glyphicon-trash" ></i>' +
                'Delete user</a></span></li>');
        $("#user_list").append($li);
    }

    function postForm(data, url, callback) {
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function (data) {
                callback(data);
            }
        });

    }

    init();

});