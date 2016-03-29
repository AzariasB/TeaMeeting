
var module = angular.module('app',[]);

module.controller('controller',function(){
    
});

$(document).ready(function () {

    $(".save-answer").on('click',saveAnswer);

    function saveAnswer(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var parent = $(this).parent('div');
        var meetingId = $(this).data('target');
        var answer = parent.find('input[name=presence-meeting-' + meetingId + ']:checked').val();
        if (answer) {
            var data = {
                'answer': answer
            };
            postForm(data, url, answerChanged);
        }
    }

    function answerChanged(data) {
        console.log(data);
        if (data.success && data.answer) {
            var ans = data.answer;
            if (ans.answer === "3") {
                $("#radio-answers-" + ans.meeting.id).hide();
                $("#answered-yes-" + ans.meeting.id).show();
            }
        } else {
            console.error(data.error);
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
});
