jQuery(document).ready(function ($) {
    'use strict';

    let touchStartY = 0;
    let touchMoved = false;

    $('body')
        .on('touchstart', '.easyel-wrapper-link', function (e) {
            touchStartY = e.originalEvent.touches[0].clientY;
            touchMoved = false;
        })
        .on('touchmove', '.easyel-wrapper-link', function (e) {
            const currentY = e.originalEvent.touches[0].clientY;
            if (Math.abs(currentY - touchStartY) > 10) {
                touchMoved = true; 
            }
        })
        .on('touchend click', '.easyel-wrapper-link', function (e) {
            if (touchMoved) {
                //  Scroll 
                return;
            }

            const $el = $(this);
            const settings = $el.data('easyel-wrapper-link');

            if (!settings || !settings.url) return;

            const url = settings.url.trim();

            // Anchor scroll
            if (url.startsWith('#')) {
                e.preventDefault();
                const target = $(url);
                if (target.length) {
                    $('html, body').animate(
                        { scrollTop: target.offset().top },
                        600
                    );
                }
                return;
            }

            // Mail / Tel
            if (url.startsWith('mailto:') || url.startsWith('tel:')) {
                window.location.href = url;
                return;
            }

            if (!/^https?:\/\//i.test(url)) return;

            e.preventDefault();

            if (settings.is_external) {
                window.open(url, '_blank', 'noopener,noreferrer');
            } else {
                window.location.href = url;
            }
        });
});