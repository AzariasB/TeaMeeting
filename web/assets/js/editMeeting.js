
$(document).ready(function () {

    function init() {
        $("#add-meeting").on('click', addMeeting);
    }


    function addMeeting(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        postForm({}, url, function (data) {
            showMeetingForm(data, url);
        });
    }

    function showMeetingForm(data, url) {
        $("#modal-main-content").html(data);
        $("#form-create-item").on('submit', function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            postForm(data, url, function (data) {
                creationAnswer(data, url);
            });
        });
        $("#modal-main").modal();
    }

    function creationAnswer(data) {
        console.log(data);
        if (data.success) {
            $("#modal-main").modal('hide');
            //Add item to the list
        } else {
            $("#modal-main-content").html(data.page);
        }
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
