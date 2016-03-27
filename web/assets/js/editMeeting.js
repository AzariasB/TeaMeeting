
var app = angular.module('app', []);

app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('//');
    $interpolateProvider.endSymbol('//');
});

app.controller('controller', function ($scope, $http) {

    this.items = [];
    var self = this;

    this.init = function () {
        var url = $("#items-link").data('href');
        this.postReq({}, url, initItems);
    };

    function initItems(data) {
        self.items = data.data.sort(function (a, b) {
            return a.position - b.position;
        });
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
            showModal(reponse.data, url);
        });
    };

    this.saveItems = function (evetn) {
        event.preventDefault();
        var url = $(event.toElement).attr('href');
        this.postReq({'items': this.items}, url, function (reponse) {
            console.log(reponse.data);
        });
    };

    function showModal(data, url) {
        $("#modal-main-content").html(data);
        $("#form-create-item").on('submit', function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            console.log(data);
            self.postReq(data, url, function (data) {
                creationAnswer(data, url);
            });
        });
        $("#modal-main").modal();
    }

    function creationAnswer(reponse) {
        var data = reponse.data;
        if (data.success) {
            $("#modal-main").modal('hide');
            self.items.push(data.item);
        } else {
            $("#modal-main-content").html(data.page);
        }
    }

    this.init();
});