(function ($) {
    "use strict";

   window.initCountdown = function($scope, $) {
      // Use $scope to find element inside editor iframe
      const countdownWrapper = $scope.find('#eel-countdowns')[0] || document.getElementById('eel-countdowns');
      if (!countdownWrapper) return;

      const targetDateStr = countdownWrapper.dataset.target;
      const countDownDate = new Date(targetDateStr).getTime();
      if (isNaN(countDownDate)) return;

      const second = 1000,
         minute = second * 60,
         hour = minute * 60,
         day = hour * 24;

         const daysEl    = countdownWrapper.querySelector('.eel-cntdwn-days');
         const hoursEl   = countdownWrapper.querySelector('.eel-cntdwn-hours');
         const minutesEl = countdownWrapper.querySelector('.eel-cntdwn-minutes');
         const secondsEl = countdownWrapper.querySelector('.eel-cntdwn-seconds');

      if (!daysEl || !hoursEl || !minutesEl || !secondsEl) return;

      function updateCountdown() {
         const now = new Date().getTime();
         const distance = countDownDate - now;

         if (distance <= 0) {
               countdownWrapper.innerHTML = "Countdown Finished!";
               clearInterval(intervalId);
               return;
         }

         daysEl.textContent    = Math.floor(distance / day);
         hoursEl.textContent   = Math.floor((distance % day) / hour);
         minutesEl.textContent = Math.floor((distance % hour) / minute);
         secondsEl.textContent = Math.floor((distance % minute) / second);
      }

      updateCountdown();
      const intervalId = setInterval(updateCountdown, 1000);
   };

   // Elementor hook (editor + frontend)
   jQuery(window).on('elementor/frontend/init', function () {
      elementorFrontend.hooks.addAction('frontend/element_ready/global', function($scope, $){
         if (typeof window.initCountdown === 'function') {
               window.initCountdown($scope, $);
         }
      });
   });

})(jQuery);
