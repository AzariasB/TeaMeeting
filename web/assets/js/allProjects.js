

var app = angular.module('app', []);

app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('//');
    $interpolateProvider.endSymbol('//');
});

app.controller('controller', function ($scope, $http) {

    this.projects = [];

    var self = this;


    this.init = function () {
        var url = $("#all-projects-path").data('href');
        this.postReq({}, url, function (response) {
            self.projects = response.data;
        });
    };

    this.projectModal = function (event) {
        event.preventDefault();
        var url = $(event.toElement).attr('href');
        this.postReq({}, url, function (response) {
            showModal(url, "create-project-form", response.data, confirmCreation);
        });
    };

    this.lockProject = function (event, projId, locking) {
        event.preventDefault();
        var url = $(event.toElement).attr('href').replace(/__name__/, projId);
        this.postReq({}, url, function (response) {
            var data = response.data;
            if (data.success) {
                self.projects = self.projects.map(function (proj) {
                    return proj.id === data.project.id ? data.project : proj;
                });
            }
        });
    };

    this.deleteProject = function (event, projId) {
        event.preventDefault();
        var url = $(event.toElement).attr('href').replace(/__name__/, projId);
        this.postReq({}, url, function (response) {
            var data = response.data;
            if (data.success) {
                self.projects = self.projects.filter(function (proj) {
                    return (proj.id | 0) !== (data.projectId | 0);
                });
            }
        });
    };

    this.seeProject = function ($event, projId) {
        $event.preventDefault();
        var url = $($event.toElement).attr('href').replace(/__name__/, projId);
        window.location = url;
    };


    function showModal(url, formId, data, submitCallback) {
        $("#modal-main-content").html(data);
        $("#" + formId).on('submit', function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            self.postReq(data, url, function (response) {
                var data = response.data;
                if (data.success) {
                    $("#modal-main").modal('hide');
                    submitCallback && submitCallback(data);
                }
            });
        });
        $("#modal-main").modal();
    }

    function confirmCreation(data) {
        self.projects.push(data.project);
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