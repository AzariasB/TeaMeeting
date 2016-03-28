
var app = angular.module('app', []);

app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('//');
    $interpolateProvider.endSymbol('//');
});

app.controller('controller', function ($scope, $http) {

    this.items = [];
    this.requests = [];
    var self = this;

    this.init = function () {
        var url = $("#agenda-json").data('href');
        this.postReq({}, url, initItems);
    };

    function initItems(response) {
        var data = response.data;
        self.items = data.items.sort(function (a, b) {
            return a.position - b.position;
        });
        self.requests = data.requests;
    }

    this.swap = function (low, up) {
        var a = this.items[low];
        this.items[low] = this.items[up];
        this.items[up] = a;
    };

    this.postReq = function (data, url, callback) {
        $http({
            method: 'POST',
            url: url,
            data: data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(callback);
    };

    this.newItem = function (event) {
        event.preventDefault();
        var url = $(event.toElement).attr('href');
        this.postReq({}, url, function (reponse) {
            showModal("form-create-item", reponse.data, url, function (dt) {
                self.items.push(dt.item);
            });
        });
    };

    this.saveItems = function (event) {
        event.preventDefault();
        var url = $(event.toElement).attr('href');
        this.postReq({'items': this.items}, url, function (reponse) {
            console.log(reponse.data);
        });
    };

    function showModal(formId, data, url, callback) {
        $("#modal-main-content").html(data);
        $("#" + formId).on('submit', function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            self.postReq(data, url, function (response) {
                var dt = response.data;
                updateModal(dt);
                if (dt.success && callback) {
                    callback.call(null, dt);
                }
            });
        });
        $("#modal-main").modal();
    }

    function updateModal(data) {
        if (data.success) {
            $("#modal-main").modal('hide');
        } else {
            $("#modal-main-content").html(data.page);
        }
    }

    this.newRequest = function (data) {
        self.requests.push(data.request);
    };

    this.showRequestForm = function (data, url) {
        showModal("form-send-request", data, url, this.newRequest);
    };

    this.sendRequest = function (event) {
        event.preventDefault();
        var url = $(event.toElement).attr('href');
        this.postReq({}, url, function (response) {
            self.showRequestForm(response.data, url);
        });
    };

    function stateUpdated(data) {
        var r = data.request;
        self.requests = self.requests.map(function (req) {
            return req.id === r.id ? r : req;
        });
    }

    this.updateRequest = function (event, reqId) {
        event.preventDefault();
        var url = $("#path-update-request").data("href").replace(/__name__/, reqId);
        this.postReq({}, url, function (response) {
            showModal('form-update-request', response.data, url, stateUpdated);
        });
    };

    this.init();
});