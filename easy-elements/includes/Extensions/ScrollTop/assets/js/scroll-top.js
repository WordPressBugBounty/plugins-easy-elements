(function ($) {
    "use strict";

    $(document).ready(function () {
        var $btn = $('.easyel-scroll-top-btn');
        if (!$btn.length) return;

        var offset = (typeof easyelScrollTopData !== 'undefined' && easyelScrollTopData.offset)
            ? parseInt(easyelScrollTopData.offset, 10)
            : 300;

        function checkScroll(currentScroll) {
            if (currentScroll > offset) {
                $btn.addClass('easyel-scroll-top-visible');
            } else {
                $btn.removeClass('easyel-scroll-top-visible');
            }
        }

        // Lenis smooth scroll support
        if (window.lenis) {
            window.lenis.on('scroll', function (e) {
                checkScroll(e.scroll);
            });
        } else {
            $(window).on('scroll', function () {
                window.requestAnimationFrame(function () {
                    checkScroll($(window).scrollTop());
                });
            });
        }

        // Click — scroll to top
        $btn.on('click', function (e) {
            e.preventDefault();
            if (window.lenis) {
                window.lenis.scrollTo(0, {
                    duration: 1.2,
                    easing: function (t) { return Math.min(1, 1.001 - Math.pow(2, -10 * t)); }
                });
            } else {
                $('html, body').animate({ scrollTop: 0 }, 500);
            }
        });

        // Initial check
        checkScroll(window.lenis ? 0 : $(window).scrollTop());
    });
})(jQuery);
