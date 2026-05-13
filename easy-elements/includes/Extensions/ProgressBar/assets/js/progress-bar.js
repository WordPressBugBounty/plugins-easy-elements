(function ($) {
    "use strict";

    $(function () {

        var easyprogressBar = $('#easyel-reading-progress-bar');
        if (!easyprogressBar.length) return;

        var activated = false;

        function updateProgressfunc() {
            var winHeight = $(window).height();
            var docHeight = $(document).height();
            var scrollTop = $(window).scrollTop();

            var max = docHeight - winHeight;
            easyprogressBar.attr('max', max);
            easyprogressBar.val(scrollTop);
        }

        easyprogressBar
            .val(0)
            .css({ opacity: 0, visibility: 'hidden' });

        $(window).one('scroll', function () {
            activated = true;
            updateProgressfunc();
            easyprogressBar.css({ opacity: 1, visibility: 'visible' });
        });

        $(window).on('scroll resize', function () {
            if (!activated) return;
            window.requestAnimationFrame(updateProgressfunc);
        });

    });

})(jQuery);
