
(function () {
    'use strict';

    angular.module('TeaMeeting')
            .controller('Controller', ['post', 'modalForm', 'Notification', AllProjectsController]);


    function AllProjectsController(post, modalForm, Notification) {

        var self = this;

        self.projects = [];
        self.init = init;
        self.projectModal = projectModal;
        self.lockProject = lockProject;
        self.deleteProject = deleteProject;
        self.seeProject = seeProject;

        function init() {
            var url = $("#all-projects-path").data('href');
            post(url, function (response) {
                self.projects = response.data;
            });
        }


        function projectModal(event) {
            event.preventDefault();
            var url = $(event.toElement).attr('href');
            post(url, function (response) {
                modalForm(response.data, "create-project-form", url, confirmCreation);
            });
        }


        function lockProject(event, projId, locking) {
            event.preventDefault();
            var url = $(event.toElement).attr('href').replace(/__name__/, projId);
            post(url, function (response) {
                var data = response.data;
                if (data.success) {
                    self.projects = self.projects.map(function (proj) {
                        return proj.id === data.project.id ? data.project : proj;
                    });
                }
                var message = 'The project was successfully ' + (locking ? 'locked' : 'unlocked');
                Notification.success(message);
            });
        }


        function deleteProject(event, projId) {
            event.preventDefault();
            var url = $(event.toElement).attr('href').replace(/__name__/, projId);
            post(url, function (response) {
                var data = response.data;
                if (data.success) {
                    self.projects = self.projects.filter(function (proj) {
                        return (proj.id | 0) !== (data.projectId | 0);
                    });
                    Notification.success('The project was successfully deleted');
                }
            });
        }


        function seeProject($event, projId) {
            $event.preventDefault();
            var url = $($event.toElement).attr('href').replace(/__name__/, projId);
            window.location = url;
        }

        function confirmCreation(data) {
            self.projects.push(data.project);
            Notification.success('The project was successfully created');

        }

        init();
    }

})();