(function($) {
    "use strict";
    function handle_faq_($scope) {
        const $faqItems = $scope.find('.eel-faq-item');
        const duration = 300;

        $faqItems.each(function () {
            const $item = $(this);
            const $question = $item.find('.eel-faq-question');
            const $answer = $item.find('.eel-faq-answer');

            $question.off('click.faqToggle');

            // Initial state
            if ($item.hasClass('active')) {
                $answer.stop().slideDown(0);
            } else {
                $answer.stop().slideUp(0);
            }

            $question.on('click.faqToggle', function () {
                const isActive = $item.hasClass('active');

                // Close all other items
                $faqItems.not($item).each(function () {
                    var $other = $(this);
                    if ($other.hasClass('active')) {
                        $other.removeClass('active');
                        $other.find('.eel-faq-answer').stop(true).slideUp(duration);
                    }
                });

                // Toggle current
                if (isActive) {
                    $item.removeClass('active');
                    $answer.stop(true).slideUp(duration);
                } else {
                    $item.addClass('active');
                    $answer.stop(true).slideDown(duration);
                }
            });
        });
    }

    function checkStickySection() {
        const stickySection = document.querySelector('.eel-faq-sticky');
        document.body.classList.toggle('sticky-enabled-overlap-faq', !!stickySection);
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', handle_faq_);
        elementorFrontend.hooks.addAction('frontend/element_ready/eel-faq-accordion.default', handle_faq_);

        checkStickySection();

        const observer = new MutationObserver(checkStickySection);
        observer.observe(document.body, { childList: true, subtree: true });
    });

})(jQuery);
