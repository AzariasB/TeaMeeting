
(function () {
    'use strict';

    angular.module('TeaMeeting')
            .factory('modalForm', ['post', modalFormService]);

    function modalFormService(post) {
        $(document).ready(function () {
            if (!$("#modal-main")) {
                throw new "The modal must be in the page";
            }
        });

        function defaultFailure(response) {
            console.error(response.data);
        }

        function ajaxCallback(submitCallback) {

            return function (response) {
                var data = response.data;
                if (data.success) {
                    $("#modal-main").modal('hide');
                    submitCallback && submitCallback(data);
                }
            };
        }

        function showModal(data, formId, url, submitCallback, failure) {
            failure = failure || defaultFailure;
            $("#modal-main-content").html(data);
            $("#" + formId).on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                post(url, ajaxCallback(submitCallback), data, failure);
            });
            $("#modal-main").modal();
        }

        return showModal;
    }
})();