(function($) {
    "use strict";
    $(document).ready(function(){
        var win = $(window);
        var totop = $('#easyel-top-to-bottom');
        function checkScroll(currentScroll) {
            if (currentScroll > 150) {
                totop.fadeIn();
                $('header').addClass('eel-scoll-to-top');
                totop.addClass('eel-scroll-visible');
            } else {
                totop.fadeOut();
                $('header').removeClass('eel-scoll-to-top');
                totop.removeClass('eel-scroll-visible');
            }
        }
        if (window.lenis) {
            window.lenis.on('scroll', (e) => {
                checkScroll(e.scroll);
            });
        } else {
            win.on('scroll', function() {
                checkScroll(win.scrollTop());
            });
        }
        totop.on('click', function(e) {
            e.preventDefault();
            if (window.lenis) {
                window.lenis.scrollTo(0, { 
                    duration: 1.2,
                    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)) 
                });
            } else {
                $("html, body").animate({ scrollTop: 0 }, 500);
            }
        }); 
    });
})(jQuery);