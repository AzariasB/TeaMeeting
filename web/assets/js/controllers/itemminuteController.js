
(function () {

    var actionStates = [
        {class: 'info', title: 'In progress'},
        {class: 'danger', title: 'Late'},
        {class: 'default', title: 'In review'},
        {class: 'success', title: 'Complete'},
        {class: '', title: 'No longer required'}
    ];

    angular.module('TeaMeeting')
            .controller('Controller', itemMinuteController);

    function itemMinuteController(post, modalForm, Notification) {
        var self = this;
        var initToggle = 1;

        //Attributes
        self.actions = [];
        self.postponed = null;
        self.userId;

        //Functions
        self.addAction = addAction;
        self.setUserId = setUserId;
        self.getActionClass = getActionClass;
        self.getActionTitle = getActionTitle;
        self.updateAction = updateAction;
        self.submitAction = submitAction;
        self.togglePostponed = togglePostponed;

        function init() {
            var url = window.location.pathname + '/json';
            post(url, initArrays);
        }

        function addAction($event) {
            var url = getUrl($event);
            post(url, function (response) {
                modalForm(response.data, 'create-action-form', url, actionCreated);
            });
        }

        function setUserId(userId) {
            this.userId = userId;
        }

        function getActionClass(action) {
            return actionStates[action.state].class;
        }

        function getActionTitle(action) {
            return actionStates[action.state].title;
        }

        function updateAction($event, actionId) {
            var url = getUrl($event, actionId);
            post(url, function (response) {
                modalForm(response.data, 'create-action-form', url, actionUpdated);
            });
        }

        function submitAction($event, actionId) {
            var url = getUrl($event, actionId);
            post(url, function (response) {
                if (response.data.success) {
                    actionUpdated(response.data);
                }
            });
        }
        ;

        function actionUpdated(data) {
            self.actions = self.actions.map(function (oldAction) {
                return (oldAction.id | 0) === (data.action.id | 0) ? data.action : oldAction;
            });
        }

        function actionCreated(data) {
            self.actions.push(data.action);
        }

        function initArrays(response) {
            var item = response.data.item;
            self.actions = item.actions;
            self.postponed = item.postponed;

        }

        function togglePostponed() {
            if (initToggle === 1) {
                initToggle = 0;
            } else {
                var url = $("#postponed-toggle").attr('href');
                post(url, function (response) {
                    var data = response.data;
                    if (data.success) {
                        var msg = self.postponed ?
                                'The item is now postponed' :
                                'The item is not postponed';
                        Notification.success(msg);
                    }
                });
            }

        }

        init();
    }

    function getUrl($event, id) {
        $event.preventDefault();
        var url = $($event.toElement).attr('href');
        return id ? url.replace(/__id__/, id) : url;
    }

})();