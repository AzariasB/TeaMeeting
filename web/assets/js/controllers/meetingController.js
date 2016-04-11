
(function () {
    'use strict';

    var meetingId = window.location.pathname.split('/').pop();

    angular.module('TeaMeeting')
            .controller('Controller', meetingController);

    function meetingController(post, modalForm) {
        var self = this;
        self.items = [];
        self.requests = [];
        self.init = init();
        self.swap = swap;
        self.newItem = newItem;
        self.saveItems = saveItems;
        self.newRequest = newRequest;
        self.showRequestForm = showRequestForm;
        self.sendRequest = sendRequest;
        self.updateRequest = updateRequest;
        self.updateItem = updateItem;
        self.removeItem = removeItem;
        self.removeItemAction = removeItemAction;

        function init() {
            var url = $("#agenda-json").data('href');
            post(url, initItems);
        }

        function initItems(response) {
            var data = response.data;
            self.items = data.items.sort(function (a, b) {
                return a.position - b.position;
            });
            self.requests = data.requests;
        }

        function swap(low, up) {
            var a = self.items[low];
            self.items[low] = self.items[up];
            self.items[up] = a;
        }

        function newItem($event) {
            $event.preventDefault();
            var url = $($event.toElement).attr('href');
            post(url, function (response) {
                modalForm(response.data, "form-create-item", url, function (dt) {
                    self.items.push(dt.item);
                });
            });
        }

        function saveItems(event) {
            event.preventDefault();
            var url = $(event.toElement).attr('href');
            post(url, function (reponse) {
                console.log(reponse.data);
            }, {'items': self.items});
        }

        function newRequest(data) {
            self.requests.push(data.request);
        }

        function showRequestForm(data, url) {
            modalForm(data,"form-send-request", url, newRequest);
        }

        function sendRequest(event) {
            event.preventDefault();
            var url = $(event.toElement).attr('href');
            post(url, function (response) {
                self.showRequestForm(response.data, url);
            });
        }

        function stateUpdated(data) {
            var r = data.request;
            self.requests = self.requests.map(function (req) {
                return req.id === r.id ? r : req;
            });
        }

        function itemUpdated(data) {
            var updated = data.item;
            var meeting = data.itemMeeting;
            self.items = self.items.map(function (itm) {
                if (itm.id === updated.id) {
                    if ((meeting.id | 0) !== (meetingId | 0)) {
                        return null;
                    } else {
                        return updated;
                    }
                } else {
                    return itm;
                }
            }).filter(function (val) {
                return !!val;
            });
        }

        function updateRequest(event, reqId) {
            event.preventDefault();
            var url = $("#path-update-request").data("href").replace(/__name__/, reqId);
            post(url, function (response) {
                modalForm(response.data,'form-update-request',url, stateUpdated);
            });
        }

        function updateItem(event, itemId) {
            event.preventDefault();
            var url = $("#path-update-item").data('href').replace(/__name__/, itemId);
            post(url, function (response) {
                modalForm(response.data, 'form-create-item', url, itemUpdated);
            });
        }

        function removeItem(removedItemId) {
            removedItemId = removedItemId | 0;
            self.items = self.items.filter(function (it) {
                return it.id !== removedItemId;
            });
        }


        function removeItemAction(event, itemId) {
            event.preventDefault();
            var url = $("#path-remove-item").data('href').replace(/__name__/, itemId);
            post(url, function (response) {
                var data = response.data;
                if (data.success) {
                    self.removeItem(data.removed);
                }

            });
        };

        init();
    }
})();