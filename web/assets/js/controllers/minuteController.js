
(function () {
    'use strict';

    angular.module('TeaMeeting')
            .controller('Controller', ['post', 'modalForm', minuteController]);

    function minuteController(post, modalForm) {
        var self = this;

        self.presenceList = [];
        self.items = [];
        self.comments = [];
        self.currentComment = '';

        function init() {
            var url = $("#minute-json-path").data('href');
            post(url, initArrays);
        }

        function initArrays(response) {
            var minute = response.data.minute;
            self.items = minute.items;
            self.presenceList = minute.presenceList;
            self.comments = minute.comments;
        }

        this.postComment = function ($event) {
            $event.preventDefault();
            if (self.currentComment) {
                var url = $($event.currentTarget).attr('action');
                post(url, commentPosted, "comment=" + this.currentComment);
                self.currentComment = '';
            }
        };

        function commentPosted(response) {
            var data = response.data;
            if (data.success) {
                self.comments.push(data.comment);
            }
        }

        this.editPresence = function ($event, presenceId) {
            var url = getUrl($event, presenceId);
            post(url, function (response) {
                modalForm(response.data, 'update-presence-form', url, updatePresence);
            });
        };

        this.seeItem = function ($event, itemId) {
            var url = getUrl($event, itemId);
            window.location = url;
        };


        function updatePresence(data) {
            var nwPresence = data.presence;
            self.presenceList = self.presenceList.map(function (old) {
                return (old.id | 0) === (nwPresence.id | 0) ? nwPresence : old;
            });
        }

        init();
    }

    function getUrl($event, idToReplace) {
        $event.preventDefault();
        console.log($($event.toElement).attr('href'));
        return $($event.toElement).attr('href').replace(/__id__/, idToReplace);
    }
})();