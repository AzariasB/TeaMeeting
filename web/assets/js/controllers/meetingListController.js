
(function () {
    'use strict';

    angular.module('TeaMeeting')
            .controller('Controller', ['post', meetingListController]);

    function meetingListController(post) {

        var self = this;
        self.saveAnswer = saveAnswer;

        function saveAnswer($event, meetingId) {
            $event.preventDefault();
            var $el = $($event.toElement);
            var url = $el.attr('href');
            var answer = $el.parent('div')
                    .find('input[name="presence-meeting-' + meetingId + '"]:checked').val();
            if (answer) {
                var data = {
                    'answer': answer
                };
                
                post(url, answerChanged, $.param(data));
            }
        }

        function answerChanged(response) {
            var data = response.data;
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
    }

})();