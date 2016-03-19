
$(document).ready(function () {

    var seeProfilePath = $("#profilePath").text();

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
                e.preventDefault();
                hideDivs();
                disActivate();
                $("#" + idsDivs[key]).removeClass('hidden');
                $(this).addClass('active');
            });
        });

        $("#create_user").on('click', launchCreateUserModal);
    }

    function hideDivs() {
        $(".admin-item").addClass('hidden');
    }

    function disActivate() {
        $(".admin-choice").removeClass('active');
    }

    function launchCreateUserModal(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.post(url, {
            nope: "hey"
        }, function (res) {
            $("#userModal .modal-content").html(res);
            $("#create-user-form").on('submit', function (e) {
                e.preventDefault();
                $("#submit-create-user").prop('disabled', true);
                postForm($(this).serialize(), url, confirmCreation);
            });
            $("#userModal").modal();
        });
    }

    function confirmCreation(data) {
        if (data.success) {
            addUser(data.username,data.userId);
            $("#userModal .modal-content .modal-body")
                    .html("The user's pseudo is <b>" + data.password + "</b>");
        } else {
            $("#userModal .modal-content .modal-body").html(data.error);
        }
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

        /*
         * Throw the form values to the server!
         */
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