(function ($) {
    "use strict";

   function eelRunCounter($scope) {
      const counters = $scope.find(".eel-counter");
      if (!counters.length) return;

      const observer = new IntersectionObserver(entries => {
         entries.forEach(entry => {
               if (!entry.isIntersecting) return;

               const counter = entry.target;
               observer.unobserve(counter);

               const target = parseInt(counter.dataset.count, 10) || 0;
               const duration = parseInt(counter.dataset.duration, 10) || 1000;
               const format = counter.dataset.format || "default";

               let current = 0;
               const startTime = performance.now();

               function formatNumber(num) {
                  if (num < 1000) return num;

                  const groups = num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, {
                     comma: ",",
                     dot: ".",
                     space: " ",
                     underline: "_"
                  }[format] || "");

                  return format === "underline" ? `${groups}` : groups;
               }

               function animate(time) {
                  const progress = Math.min((time - startTime) / duration, 1);
                  current = Math.floor(progress * target);

                  if (format === "underline") {
                     counter.innerHTML = formatNumber(current);
                  } else {
                     counter.textContent = formatNumber(current);
                  }

                  if (progress < 1) requestAnimationFrame(animate);
               }

               requestAnimationFrame(animate);
         });
      }, { threshold: 0.2 });

      counters.each(function () {
         observer.observe(this);
      });
   }

   $(window).on("elementor/frontend/init", function () {
      elementorFrontend.hooks.addAction("frontend/element_ready/widget", function ($scope) {
         if ($scope.find(".eel-counter").length) {
               eelRunCounter($scope);
         }
      });
   });

})(jQuery);
