
(function () {
    'use strict';

    angular.module('TeaMeeting')
            .controller('Controller', ['post', 'modalForm', projectController]);

    function projectController(post, modalForm) {
        var projectId = $("#get-project-path").data('project-id');
        var self = this;

        //Attributes
        self.participants = [];
        self.roles = [];
        self.meetings = [];

        //Functions
        self.seeMeeting = seeMeeting;
        self.deleteRole = deleteRole;
        self.editItem = editItem;
        self.addRole = addRole;
        self.addMeeting = addMeeting;

        function init() {
            var href = $("#get-project-path").data('href');
            post(href, initArrays);
        }

        function initArrays(response) {
            var data = response.data;
            self.participants = data.participants;
            self.roles = data.roles;
            self.meetings = data.meetings;
        }


        function seeMeeting($event, meetinId) {
            $event.preventDefault();
            var url = $($event.toElement).attr('href').replace(/__id__/, meetinId);
            window.location = url;
        }


        function deleteRole($event, roleId) {
            $event.preventDefault();
            var url = $($event.toElement).attr('href').replace(/__id__/, roleId);
            post(url, roleDeleted);
        }

        function roleDeleted(response) {
            var data = response.data;
            if (data.success) {
                self.roles = self.roles.filter(function (meet) {
                    return (meet.id | 0) !== (data.id | 0);
                });
            }
        }

        function editItem($event, roleId, itemType) {
            $event.preventDefault();
            var url = $($event.toElement).attr('href').replace(/__id__/, roleId);
            post(url, function (response) {
                var data = response.data;
                var formId = 'create-' + itemType + '-form';
                modalForm(data, formId, url, itemUpdated(itemType));
            });
        }

        function addRole($event) {
            $event.preventDefault();
            var url = $($event.toElement).attr('href');
            post(url, function (response) {
                var data = response.data;
                modalForm(data, 'create-role-form', url, roleAdded);
            });
        }

        function addMeeting($event) {
            $event.preventDefault();
            var url = $($event.toElement).attr('href');
            post(url, function (response) {
                var data = response.data;
                modalForm(data, 'create-meeting-form', url, meetingAdded);
            });
        }

        function meetingAdded(data) {
            self.meetings.push(data.meeting);
            self.meetings.sort(function (a, b) {
                return a.date - b.date;
            });
        }

        function roleAdded(data) {
            self.roles.push(data.role);
        }

        function itemUpdated(itemType) {
            return function (data) {
                var nwItem = data[itemType];
                var array = self[itemType + 's'];
                self[itemType + 's'] = updateArray(array, nwItem);
            };
        }

        init();
    }

})();

function updateArray(array, nwItem) {
    return array.map(function (oldItem) {
        return (oldItem.id | 0) !== (nwItem.id | 0) ? oldItem : nwItem;
    });
}

function getId($event,replacement){
    $event.preventDefault();
    
}