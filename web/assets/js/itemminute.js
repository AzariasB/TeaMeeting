
var app = angular.module('app', []);


app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('//');
    $interpolateProvider.endSymbol('//');
});

app.controller('controller', function ($scope, $http) {

    var actionStates = [
        {class: 'info', title: 'In progress'},
        {class: 'danger', title: 'Late'},
        {class: 'default', title: 'In review'},
        {class: 'success', title: 'Complete'},
        {class: '', title: 'No longer required'}
    ];

    this.actions = [];
    this.userId;
    var self = this;

    function init() {
        var url = window.location.pathname + '/json';
        postReq({}, url, initArrays);
    }

    this.addAction = function ($event) {
        $event.preventDefault();
        var url = $($event.toElement).attr('href');
        postReq({}, url, function (response) {
            launchModal(response.data, url, 'create-action-form', actionCreated);
        });
    };

    this.setUserId = function (userId) {
        this.userId = userId;
    };

    this.getActionClass = function (action) {
        return actionStates[action.state].class;
    };

    this.getActionTitle = function (action) {
        return actionStates[action.state].title;
    };

    this.updateAction = function ($event, actionId) {
        $event.preventDefault();
        var url = $($event.toElement).attr('href').replace(/__id__/, actionId);
        postReq({}, url,function(response){
            launchModal(response.data,url,'create-action-form',actionUpdated);
        });
    };
    
    this.submitAction = function($event,actionId){
        $event.preventDefault();
        var url = $(event.toElement).attr('href').replace(/__id__/,actionId);
        postReq({},url,function(response){
           //...
           if(response.data.success){
                actionUpdated(response.data);
           }
        });
    };

    function actionUpdated(data) {
        self.actions = self.actions.map(function (oldAction) {
            return (oldAction.id | 0) === (data.action.id | 0) ? data.action : oldAction;
        });
    }

    function actionCreated(data) {
        self.actions.push(data.action);
    }

    function launchModal(data, url, formId, callback) {
        $("#modal-main-content").html(data);
        $("#" + formId).on('submit', function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            postReq(data, url, function (response) {
                var resDa = response.data;
                if (resDa.success) {
                    callback && callback(resDa);
                    $("#modal-main").modal('hide');
                } else {
                    $("#modal-main-content").html(data.page);
                }
            });
        });
        $("#modal-main").modal();
    }

    function initArrays(response) {
        var item = response.data.item;
        self.actions = item.actions;
        console.log(self.actions);
    }

    function postReq(data, url, callback) {
        $http({
            method: 'POST',
            url: url,
            data: data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(callback);
    }

    init();
});