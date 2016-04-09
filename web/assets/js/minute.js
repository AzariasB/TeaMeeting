
var app = angular.module('app', []);

app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('//');
    $interpolateProvider.endSymbol('//');
});


app.controller('controller', function ($scope, $http) {

    this.presenceList = [];
    this.items = [];
    var self = this;

    function init() {
        var url = $("#minute-json-path").data('href');
        postReq({}, url, initArrays);
    }

    function initArrays(response) {
        var minute = response.data.minute;
        self.items = minute.items;
        self.presenceList = minute.presenceList;
        console.log(self.presenceList);
    }

    function postReq(data, url, callback) {
        $http({
            method: 'POST',
            url: url,
            data: data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(callback);
    }

    this.editPresence = function ($event, presenceId) {
        $event.preventDefault();
        var url = $($event.toElement).attr('href').replace(/__id__/, presenceId);
        postReq({}, url, function (response) {
            showModal(response.data, url, "update-presence-form", updatePresence);
        });
    };

    function showModal(data, url, formId, callback) {
        $("#modal-main-content").html(data);
        $("#" + formId).on('submit', function (e) {
            e.preventDefault();
            var form = $(this).serialize();
            postReq(form, url, function (response) {
                var data = response.data;
                if(data.success){
                    callback && callback(data);
                    $("#modal-main").modal('hide');
                }
            });
        });
        $("#modal-main").modal();
    }

    function updatePresence(data) {
        var nwPresence = data.presence;
        self.presenceList = self.presenceList.map(function (old) {
            return (old.id | 0) === (nwPresence.id | 0) ? nwPresence : old;
        });
    }

    init();
});