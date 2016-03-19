
$(document).ready(function () {

    var idsDivs = {
        'see-profile': 'admin-profile',
        'see-projects': 'projects-list',
        'see-users': 'users-list'
    };

    function init() {
        /**
         * Avoid var persistance
         */
        Object.keys(idsDivs).forEach(function (key) {
            $("#" + key).on('click', function (e) {
                e.preventDefault();
                hideDivs();
                disActivate();
                $("#"+idsDivs[key]).removeClass('hidden');
                $(this).addClass('active');
            });
        });
    }

    function hideDivs() {
        $(".admin-item").addClass('hidden');
    }

    function disActivate() {
        $(".admin-choice").removeClass('active');
    }

    init();

});