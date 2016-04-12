

(function () {
    'use strict';

    angular.module('TeaMeeting', ['ui-notification'])
            .config(function ($interpolateProvider) {
                $interpolateProvider.startSymbol('//');
                $interpolateProvider.endSymbol('//');
            })
            .config(['$httpProvider', function ($httpProvider) {
                    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
                }])
            .config(function (NotificationProvider) {
                NotificationProvider.setOptions({
                    delay: 5000,
                    startTop: 20,
                    startRight: 10,
                    verticalSpacing: 20,
                    horizontalSpacing: 20,
                    positionX: 'left',
                    positionY: 'bottom'
                });
            });
})();
