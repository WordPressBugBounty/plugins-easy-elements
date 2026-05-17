(function ($) {
    "use strict";

    const SEPARATORS = { comma: ",", dot: ".", space: " ", underline: "_" };

    function formatWithSeparator(num, separator) {
        const str = num.toString();
        if (!separator) return str;
        const negative = str.charAt(0) === "-";
        const body = negative ? str.slice(1) : str;
        if (body.length < 4) return str;
        return (negative ? "-" : "") + body.replace(/\B(?=(\d{3})+(?!\d))/g, separator);
    }

    function runCounter(counter, target, start, duration, separator) {
        const startTime = performance.now();
        function tick(time) {
            const progress = Math.min((time - startTime) / duration, 1);
            const current = Math.floor(start + (target - start) * progress);
            counter.textContent = formatWithSeparator(current, separator);
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    function runOdometer(counter, target, duration, separator) {
        const targetInt = Math.floor(Math.abs(target));
        const targetStr = targetInt.toString();
        const negative = target < 0;

        counter.innerHTML = "";
        counter.classList.add("eel-cnt-odometer-wrap");

        if (negative) {
            const s = document.createElement("span");
            s.className = "eel-cnt-odometer-sep";
            s.textContent = "-";
            counter.appendChild(s);
        }

        const animEls = [];
        let digitIndex = 0;

        for (let i = 0; i < targetStr.length; i++) {
            const posFromRight = targetStr.length - i;
            if (i > 0 && separator && posFromRight % 3 === 0) {
                const sep = document.createElement("span");
                sep.className = "eel-cnt-odometer-sep";
                sep.textContent = separator;
                counter.appendChild(sep);
            }

            const col = document.createElement("span");
            col.className = "eel-cnt-odometer-digit";

            const roll = document.createElement("span");
            roll.className = "eel-cnt-odometer-roll";

            const spins = 2 + digitIndex;
            let html = "";
            for (let s = 0; s < spins; s++) {
                for (let n = 0; n <= 9; n++) {
                    html += '<span class="eel-cnt-odometer-num">' + n + '</span>';
                }
            }
            html += '<span class="eel-cnt-odometer-num">' + targetStr.charAt(i) + '</span>';
            roll.innerHTML = html;
            roll.style.transform = "translateY(0)";

            col.appendChild(roll);
            counter.appendChild(col);
            animEls.push({ roll: roll, spins: spins });
            digitIndex++;
        }

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                animEls.forEach(function (item) {
                    item.roll.style.transition = "transform " + duration + "ms cubic-bezier(.22,.85,.34,1)";
                    item.roll.style.transform = "translateY(-" + (item.spins * 10) + "em)";
                });
            });
        });
    }

    function eelRunCounter($scope) {
        const counters = $scope.find(".eel-counter");
        if (!counters.length) return;

        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;

                const counter = entry.target;
                observer.unobserve(counter);

                const target = parseInt(counter.dataset.count, 10) || 0;
                const start = parseInt(counter.dataset.start, 10) || 0;
                const duration = parseInt(counter.dataset.duration, 10) || 1000;
                const format = counter.dataset.format || "default";
                const animation = counter.dataset.animation || "counter";
                const separator = SEPARATORS[format] || "";

                if (animation === "odometer") {
                    runOdometer(counter, target, duration, separator);
                } else {
                    runCounter(counter, target, start, duration, separator);
                }
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
