
(function () {
    'use strict';

    angular.module('TeaMeeting')
            .factory('post', ['$http', postService]);

    function postService($http) {
        function defaultFailure(response) {
            console.error(response.data);
        }

        function post(url, callback, data, failure) {
            failure = failure || defaultFailure;
            $http({
                method: 'POST',
                url: url,
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(callback, failure);
        }

        return post;
    }
})();