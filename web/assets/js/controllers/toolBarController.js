
(function () {

    angular.module('TeaMeeting')
            .controller('ToolbarController', ['post', 'modalForm', 'Notification', toolbarController]);


    function toolbarController(post, modalForm, Notification) {
        var self = this;

        self.changePassword = changePassword;

        function changePassword($event) {
            $event.preventDefault();
            var url = $($event.toElement).attr('href');
            post(url, function (response) {
                var data = response.data;
                modalForm(data, 'reset-password-form', url, passwordChanged);
            });
        }

        function passwordChanged(data) {
            Notification.success('Your password was successfully changed');
        }
    }

})();