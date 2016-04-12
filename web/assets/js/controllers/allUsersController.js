
(function () {
    'use strict';

    angular.module('TeaMeeting')
            .controller('Controller', ['post', 'Notification', allUsersController]);

    function allUsersController(post, Notification) {
        var self = this;

        self.users = [];
        self.init = init;
        self.resetPassword = resetPassword;

        function init() {
            var url = $("#all-users-path").data('href');
            post(url, function (response) {
                self.users = response.data;
            });
        }


        function resetPassword(event, userId) {
            event.preventDefault();
            var url = $(event.toElement).attr('href').replace(/__name__/, userId);
            post(url, function (response) {
                var data = response.data;
                if (data.success) {
                    Notification.success({
                        message: 'The user\'s new passord is ' + data.password,
                        closeOnClick: false
                    });
                }
            });
        }

        init();
    }

})();