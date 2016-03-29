
var app = angular.module('app', []);

app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('//');
    $interpolateProvider.endSymbol('//');
});


app.controller('controller', function ($scope, $http) {
    var self = this;

    this.users = [];

    this.init = function () {
        var url = $("#all-users-path").data('href');
        this.postReq({}, url, function (response) {
            self.users = response.data;
        });
    };

    this.resetPassword = function (event, userId) {
        event.preventDefault();
        var url = $(event.toElement).attr('href').replace(/__name__/, userId);
        this.postReq({}, url, function (response) {
            var data = response.data;
            if (data.success) {
                showModal('The user\'s new passord is ' + data.password);
            }
        });
    };

    function showModal(data) {
        $("#modal-main-content").html(data);
        $("#modal-main").modal();
    }

    this.postReq = function (data, url, callback) {
        $http({
            method: 'POST',
            url: url,
            data: data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(callback);
    };


    this.init();
});
