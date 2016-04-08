
var app = angular.module('app', []);

app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('//');
    $interpolateProvider.endSymbol('//');
});


app.controller('controller', function ($scope, $http) {

    var projectId = $("#get-project-path").data('project-id');

    this.participants = [];
    this.roles = [];
    this.meetings = [];

    var self = this;

    this.postReq = function (data, url, callback) {
        $http({
            method: 'POST',
            url: url,
            data: data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(callback);
    };

    function init() {
        var href = $("#get-project-path").data('href');
        self.postReq({}, href, initArrays);
    }

    function initArrays(response) {
        var data = response.data;
        self.participants = data.participants;
        self.roles = data.roles;
        self.meetings = data.meetings;
    }



    
    this.seeMeeting = function ($event, meetinId) {
        $event.preventDefault();
        var url = $(event.toElement).attr('href').replace(/__id__/, meetinId);
        window.location = url;
    };

    this.deleteRole = function ($event, roleId) {
        $event.preventDefault();
        var url = $($event.toElement).attr('href').replace(/__id__/, roleId);
        console.log(url);
        self.postReq({}, url, roleDeleted);
    };

    function roleDeleted(response) {
        var data = response.data;
        if (data.success) {
            self.meetings = self.meetings.filter(function (meeting) {
                return (meeting.id | 0) !== (data.id | 0);
            });
        }
    }

    this.editRole = function ($event, roleId) {
        $event.preventDefault();
    };

    this.addRole = function ($event) {
        $event.preventDefault();
        var url = $($event.toElement).attr('href');
        this.postReq({}, url, function (response) {
            var data = response.data;
            showModalForm(data, 'create-role-form', url,roleAdded);
        });
    };

    this.addMeeting = function ($event) {
        $event.preventDefault();
        var url = $(event.toElement).attr('href');
        this.postReq({}, url, function (response) {
            var data = response.data;
            showModalForm(data, 'create-meeting-form', url,meetingAdded);
        });
    };

    function showModalForm(res, formId, url,callback) {
        $("#modal-main-content").html(res);
        $("#" + formId).on('submit', function (e) {
            e.preventDefault();
            self.postReq($(this).serialize(), url, callback);
        });
        $("#modal-main").modal();
    }
    
    function meetingAdded(response){
        var data = response.data;
        if(data.success){
            self.meetings.push(data.meeting);
            $("#modal-main").modal('hide');
        }
    }

    function roleAdded(response) {
        var data = response.data;
        if (data.success) {
            self.roles.push(data.role);
            $("#modal-main").modal('hide');
        }
    }

    init();

});
