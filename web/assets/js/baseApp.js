

(function () {
    'use strict';

    angular.module('TeaMeeting', [])
            .config(function ($interpolateProvider) {
                $interpolateProvider.startSymbol('//');
                $interpolateProvider.endSymbol('//');
            });
})();
