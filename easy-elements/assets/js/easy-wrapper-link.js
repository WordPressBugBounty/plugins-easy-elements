jQuery(document).ready(function ($) {
    'use strict';

    function initWrapperLink($el) {
        // Avoid double init (Elementor may re-render in editor).
        if ($el.data('easyelWrapperLinkInit')) {
            return;
        }

        const settings = $el.data('easyel-wrapper-link');
        if (!settings || !settings.url) {
            return;
        }

        const url = String(settings.url).trim();
        if (!url) {
            return;
        }

        $el.data('easyelWrapperLinkInit', true);

        // The wrapper needs to be a positioning context for the overlay.
        if ($el.css('position') === 'static') {
            $el.css('position', 'relative');
        }

        // Build a real <a> overlay so the browser shows the URL in the
        // status bar on hover (like a normal anchor) and supports native
        // behaviours: ctrl/middle click to open in new tab, right-click
        // "Open link in new tab", copy link address, etc.
        const $overlay = $('<a>', {
            'class': 'easyel-wrapper-link-overlay',
            'href': url,
            'aria-label': url,
            'tabindex': '-1'
        });

        const rel = [];
        if (settings.is_external) {
            $overlay.attr('target', '_blank');
            rel.push('noopener', 'noreferrer');
        }
        if (settings.nofollow) {
            rel.push('nofollow');
        }
        if (rel.length) {
            $overlay.attr('rel', rel.join(' '));
        }

        $overlay.css({
            position: 'absolute',
            top: 0,
            left: 0,
            width: '100%',
            height: '100%',
            'z-index': 1,
            margin: 0,
            padding: 0,
            border: 0,
            background: 'transparent',
            'text-decoration': 'none',
            'font-size': 0,
            'line-height': 0
        });

        // Smooth scroll for anchor links, handled natively otherwise.
        if (url.startsWith('#')) {
            $overlay.on('click', function (e) {
                const target = $(url);
                if (target.length) {
                    e.preventDefault();
                    $('html, body').animate(
                        { scrollTop: target.offset().top },
                        600
                    );
                }
            });
        }

        $el.prepend($overlay);

        // Keep real interactive children (links, buttons, inputs) above the
        // overlay so they remain clickable on their own.
        $el.find('a, button, input, select, textarea, [role="button"], .elementor-button')
            .not('.easyel-wrapper-link-overlay')
            .each(function () {
                const $child = $(this);
                if ($child.css('position') === 'static') {
                    $child.css('position', 'relative');
                }
                $child.css('z-index', 2);
            });
    }

    $('.easyel-wrapper-link').each(function () {
        initWrapperLink($(this));
    });

    // Re-init for widgets rendered after load (Elementor frontend / editor).
    if (window.elementorFrontend && elementorFrontend.hooks) {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
            const $links = $scope.hasClass('easyel-wrapper-link')
                ? $scope
                : $scope.find('.easyel-wrapper-link');
            $links.each(function () {
                initWrapperLink($(this));
            });
        });
    }
});
