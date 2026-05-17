(function($) {
    "use strict";
  	function initBackPostSlider($scope) {
  	    var $slider = $scope.find('.eel-all-slider');
  	    if (!$slider.length) {
  	        return;
  	    }
  	    var loop = $slider.data('loop') === true || $slider.data('loop') === 'true';
  	    var autoplay = $slider.data('autoplay') === true || $slider.data('autoplay') === 'true';
  	    var autoplayDelay = parseInt($slider.data('autoplay-delay'), 10) || 3000;
  	    var slidesPerView = parseInt($slider.data('slides-per-view'), 10) || 3;
  	    var spvSkin1   = parseInt($slider.data('slides-per-view-skin1'), 10);
        var spvSkin4   = parseInt($slider.data('slides-per-view-skin4'), 10);
        var spvSpecificDefault = parseInt($slider.data('slides-per-view-default'), 10);
		var finalSlidesPerView;
		if (spvSkin1) {
            finalSlidesPerView = spvSkin1;
        } else if (spvSkin4) {
            finalSlidesPerView = spvSkin4;
        } else if (spvSpecificDefault) {
            finalSlidesPerView = spvSpecificDefault;
        } else {
            finalSlidesPerView = slidesPerView;
        }
  	    var speed = parseInt($slider.data('speed'), 10) || 600;
  	    var centeredSlides = $slider.data('centered-slides') === true || $slider.data('centered-slides') === 'true';
  	    var freeMode = $slider.data('free-mode') === true || $slider.data('free-mode') === 'true';
  	    var spaceBetween = parseInt($slider.data('space-between'), 10) || 0;
  	    var slidesPerViewTablet = parseInt($slider.data('slides-per-view-tablet'), 10) || 2;
  	    var slidesPerViewMobile = parseInt($slider.data('slides-per-view-mobile'), 10) || 1;
  	    var paginationType = $slider.data('pagination') !== 'none' ? $slider.data('pagination') : false;
	    var navigation = $slider.data('navigation') === true || $slider.data('navigation') === 'true';
	    var effect = $slider.data('effect') || 'slide';
	    var cubeShadow = $slider.data('cube-shadow') === true || $slider.data('cube-shadow') === 'true';
	    var coverflowRotate = parseInt($slider.data('coverflow-rotate'), 10) || 50;
	    var coverflowStretch = parseInt($slider.data('coverflow-stretch'), 10) || 0;
	    
	    // Find navigation elements within the scope
	    var $nextButton = $scope.find('.swiper-next');
	    var $prevButton = $scope.find('.swiper-prev');
	    var $pagination = $scope.find('.swiper-pagination');
	    
	    // Prevent multiple instances and duplicated handlers if Elementor re-runs init
	    if ($slider[0] && $slider[0].swiper) {
	        try { $slider[0].swiper.destroy(true, true); } catch (e) {}
	    }
	    if ($nextButton.length) { $nextButton.off('click.easyStopAuto click.easyFadeNav'); }
	    if ($prevButton.length) { $prevButton.off('click.easyStopAuto click.easyFadeNav'); }
	    
	    var hasProgressBar = $pagination.hasClass('swiper-pagination-progressbar');
		var kbDirection = $slider.data('kenburns-direction') || 'in';
  	    
  	    var effectOptions = {};

  	    if (effect === 'cube') {
  	        effectOptions = {
  	            cubeEffect: {
  	                shadow: cubeShadow,
  	                slideShadows: cubeShadow,
  	                shadowOffset: 20,
  	                shadowScale: 0.94,
  	            }
  	        };
  	    } else if (effect === 'coverflow') {
  	        effectOptions = {
  	            coverflowEffect: {
  	                rotate: coverflowRotate,
  	                stretch: coverflowStretch,
  	                depth: 100,
  	                modifier: 1,
  	                slideShadows: true,
  	            }
  	        };
  	    } else if (effect === 'flip') {
  	        effectOptions = {
  	            flipEffect: {
  	                slideShadows: true,
  	                limitRotation: true,
  	            }
  	        };
  	    } else if (effect === 'cards') {
  	        effectOptions = {
  	            cardsEffect: {
  	                slideShadows: true,
  	                rotate: true,
  	                perSlideOffset: 8,
  	            }
  	        };
	    } else if (effect === 'creative') {
			var creativeStyle = $slider.data('creative-style') || 'default';
			if (creativeStyle === 'zoom') {
				effectOptions = {
					creativeEffect: {
						limitProgress: true,
						prev: { scale: 1.1, opacity: 0, translate: [0, 0, 0] },
						next: { scale: 1.6, opacity: 0, translate: [0, 0, 0] },
					},
				};
			} else {
				effectOptions = {
					creativeEffect: {
						limitProgress: true,
						prev: { shadow: true, translate: [0, 0, -400] },
						next: { translate: ['100%', 0, 0] },
					},
				};
			}
		} else if (effect === 'fade') {
			effectOptions = {
				fadeEffect: { crossFade: true }
			};
		} else if (effect === 'kenburns') {
			effectOptions = {
				effect: 'fade',
				fadeEffect: { crossFade: true },
				on: {
					init: function () {
						var swiper = this;
						setTimeout(function() {
							var $activeSlide = $(swiper.el).find('.swiper-slide-active');
							$activeSlide.find('.hero-content-wrapper').addClass('eel-text-animated');
							$activeSlide.find('.hero-slide-background img').addClass('eel-kb-active-' + kbDirection);
						}, 200);
					},
					slideChange: function () {
						var $allSlides = $(this.el).find('.swiper-slide');
						$allSlides.find('.hero-content-wrapper').removeClass('eel-text-animated');
						$allSlides.find('.hero-slide-background img').removeClass('eel-kb-active-in eel-kb-active-out');
					},
					transitionEnd: function () {
						var swiper = this;						
						var $activeSlide = $(swiper.el).find('.swiper-slide-active');
						$activeSlide.find('.hero-content-wrapper').addClass('eel-text-animated');
						$activeSlide.find('.hero-slide-background img').addClass('eel-kb-active-' + kbDirection);
					}
				}
			};
		}
	    
	    // Loop handling tuned to avoid double-advance on loop edges
	    var totalSlides = $slider.find('.swiper-slide').length;
	    var isFade = effect === 'fade';
	    var visibleSlides = isFade ? 1 : slidesPerView;
	    var canLoop = totalSlides > visibleSlides;
	    var loopConfig = {};
	    if (loop && (canLoop || isFade)) {
	        loopConfig = {
	            loop: true,
	            loopAdditionalSlides: isFade ? totalSlides : 20,
	            loopedSlides: isFade ? totalSlides : Math.max(Math.ceil(visibleSlides), Math.min(totalSlides, 10))
	        };
	    } else {
	        loopConfig = { loop: false, rewind: !!loop };
	    }
  	    
	    var swiper = new Swiper($slider[0], {
	        ...loopConfig,
  	        speed: speed,
  	        effect: effect,
	        normalizeSlideIndex: true,
	        preventInteractionOnTransition: true,
  	        simulateTouch: true,    
  	        touchRatio: 1, 
  	        grabCursor: true,
  	        mousewheel: hasProgressBar ? {
	            enabled: true,
	            sensitivity: 1,
	            thresholdDelta: 50,
	            thresholdTime: 200,
	            releaseOnEdges: true,
	            invert: false,
	            forceToAxis: false,
	            eventsTarget: 'container'
	        } : false,
	        autoplay: autoplay ? {
  	            delay: autoplayDelay,
	            disableOnInteraction: isFade ? true : false,
  	        } : false,
	        slidesPerView: visibleSlides,
	        spaceBetween: isFade ? 0 : spaceBetween,
	        centeredSlides: isFade ? false : centeredSlides,
  	        freeMode: freeMode,            
  	        pagination: paginationType && $pagination.length ? {
				el: $pagination[0],
				clickable: true,
				type: paginationType,
				renderBullet: function (index, className) {
					let num = (index + 1).toString().padStart(2, '0');
					return `<span class="${className}">${num}</span>`;
				}
			} : false,
			
        // Progress bar for mouse scroll - only when swiper-pagination-progressbar class is present
        progressbar: hasProgressBar ? {
            el: '.swiper-progressbar',
            type: 'progressbar',
        } : false,			
			
	        navigation: (!isFade) && navigation && ($nextButton.length || $prevButton.length) ? {
  	            nextEl: $nextButton.length ? $nextButton[0] : null,
  	            prevEl: $prevButton.length ? $prevButton[0] : null,
  	        } : false,
  	        breakpoints: {
  	            0: {
	                slidesPerView: isFade ? 1 : slidesPerViewMobile,
  	            },
  	            768: {
	                slidesPerView: isFade ? 1 : slidesPerViewTablet,
  	            },
  	            1024: {
	                slidesPerView: isFade ? 1 : finalSlidesPerView,
  	            },
  	        },
  	        ...effectOptions,
	    });
	    
	    // Fade: attach custom navigation so Next/Prev work
	    if (isFade && ($nextButton.length || $prevButton.length)) {
	        var isTransitioning = false;
	        swiper.on('transitionStart', function () { isTransitioning = true; });
	        swiper.on('transitionEnd', function () { isTransitioning = false; });
	        if ($nextButton.length) {
	            $nextButton.off('click.easyFadeNav').on('click.easyFadeNav', function (e) {
	                e.preventDefault();
	                e.stopPropagation();
	                if (swiper && swiper.autoplay && swiper.autoplay.stop) { swiper.autoplay.stop(); }
	                if (isTransitioning || (swiper && swiper.animating)) return;
	                swiper.slideNext();
	            });
	        }
	        if ($prevButton.length) {
	            $prevButton.off('click.easyFadeNav').on('click.easyFadeNav', function (e) {
	                e.preventDefault();
	                e.stopPropagation();
	                if (swiper && swiper.autoplay && swiper.autoplay.stop) { swiper.autoplay.stop(); }
	                if (isTransitioning || (swiper && swiper.animating)) return;
	                swiper.slidePrev();
	            });
	        }
	    }

	    // Non-fade: if loop+autoplay, stop autoplay on manual nav to avoid double-advance
	    if (!isFade && loop && autoplay) {
	        if ($nextButton.length) {
	            $nextButton.on('click.easyStopAuto', function () {
	                if (swiper && swiper.autoplay && swiper.autoplay.stop) {
	                    swiper.autoplay.stop();
	                }
	            });
	        }
	        if ($prevButton.length) {
	            $prevButton.on('click.easyStopAuto', function () {
	                if (swiper && swiper.autoplay && swiper.autoplay.stop) {
	                    swiper.autoplay.stop();
	                }
	            });
	        }
	    }		
  	}
	

    if (!window.EEL_AddWidgetTypeBodyClass) {
        window.EEL_AddWidgetTypeBodyClass = true;

        function addWidgetTypeToBody($scope) {
            if (!$scope || !$scope.length) return;

            var widgetType = $scope.data('widget_type');
            if (!widgetType) return;

            var safeWidgetType = widgetType
                .toString()
                .replace(/[^a-z0-9_-]/gi, '-')
                .toLowerCase();

            var className = 'easy-eel-has-widget-' + safeWidgetType;

            if (!document.body.classList.contains(className)) {
                document.body.classList.add(className);
            }
        }

        /* -------------------------------------------------
         * Elementor Hooks
         * ------------------------------------------------- */
        $(window).on('elementor/frontend/init', function () {

            // Slider init
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/global',
                initBackPostSlider
            );

            // Widget type → body class
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/global',
                function ($scope) {
                    addWidgetTypeToBody($scope);
                }
            );

        });
    }


	$(window).on('elementor/frontend/init', function () {

        function checkStickySection() {
            const stickySection = document.querySelector('.eel-sticky-section');
            if (stickySection) {
                document.body.classList.add('sticky-enabled-overlap');
            } else {
                document.body.classList.remove('sticky-enabled-overlap');
            }
        }

        checkStickySection();

        const observer = new MutationObserver(checkStickySection);
        observer.observe(document.body, { childList: true, subtree: true });
    });


	
    $(window).on('elementor/frontend/init', function () {
        function initReveal_ad($scope) {
            const revealElements = $scope.find(".eel-img-advance-wrap");
            const revealAnimate = (el) => {
                const target = el.querySelector(".eel-advance-img-main");
                if(target && !target.classList.contains("el-reveal-animate")) {
                    target.classList.add("el-reveal-animate");
                }
            };
            const isEditor = window.elementorFrontend && elementorFrontend.isEditMode && elementorFrontend.isEditMode();
            if(isEditor){
                revealElements.each(function(){
                    revealAnimate(this);
                });
            } else {
                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach(entry => {
                        if(entry.isIntersecting){
                            revealAnimate(entry.target);
                            obs.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.3 });

                revealElements.each(function(){
                    observer.observe(this);
                });

                // Lenis safe
                if (window.Lenis && typeof window.Lenis.raf === 'function') {
                    window.Lenis.raf(() => {});
                }
            }
        }

        elementorFrontend.hooks.addAction(
            'frontend/element_ready/eel-advance-image.default',
            initReveal_ad
        );

        function initReveal($scope) {
            const revealElements = $scope.find(".eel-img-reveal-wrap");

            const revealAnimate = (el) => {
                const target = el.querySelector(".eel-reveal-img-main");
                if(target && !target.classList.contains("el-reveal-animate")) {
                    target.classList.add("el-reveal-animate");
                }
            };

            const isEditor = window.elementorFrontend && elementorFrontend.isEditMode && elementorFrontend.isEditMode();

            if(isEditor){
                revealElements.each(function(){
                    revealAnimate(this);
                });
            } else {
                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach(entry => {
                        if(entry.isIntersecting){
                            revealAnimate(entry.target);
                            obs.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.3 });

                revealElements.each(function(){
                    observer.observe(this);
                });

                // Lenis safe
                if (window.Lenis && typeof window.Lenis.raf === 'function') {
                    window.Lenis.raf(() => {});
                }
            }
        }
		
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/eel-image-reveal.default',
            initReveal
        );

    });


	$(window).on("elementor/frontend/init", function () {
		var EasyJarallax = function ($scope, $) {
			if (typeof jarallax === 'undefined') return;

			var $wrappers = $scope.hasClass('eele-has-jarallax') ? $scope : $scope.find('.eele-has-jarallax, .eele-inner-jarallax');
			if (!$wrappers.length) return;

			$wrappers.each(function () {
				var $wrapper = $(this);
				var bg = $wrapper.data('jarallax-bg');
				var speed = $wrapper.data('jarallax-speed') || 0.5;
				if (!bg) return;

				// Destroy previous instance if re-rendering in editor
				var $oldJarallax = $wrapper.find('.jarallax');
				if ($oldJarallax.length) {
					$oldJarallax.each(function () {
						jarallax(this, 'destroy');
					});
					$oldJarallax.remove();
				}

				var $div = $('<div>', { 'class': 'jarallax eele-jarallax-bg', 'data-speed': speed });
				var $img = $('<img>', { 'class': 'jarallax-img', src: bg, alt: '', decoding: 'async' });
				$div.append($img);
				$wrapper.append($div);

				jarallax($div[0], {
					speed: speed,
					imgPosition: '50% 50%',
					imgRepeat: 'no-repeat',
					imgSize: 'cover',
					zIndex: -100
				});
			});
		};

		// Image widget parallax
		var EasyJarallaxImage = function ($scope, $) {
			if (typeof jarallax === 'undefined') return;
			if (!$scope.hasClass('eele-has-jarallax-img-yes')) return;

			var $img = $scope.find('.elementor-widget-container img').first();
			if (!$img.length || !$img.attr('src')) return;

			var $container = $scope.find('.elementor-widget-container');

			// If already wrapped from a previous run (editor re-render), unwrap to start clean
			if ($img.parent('.eele-img-jarallax-wrap').length) {
				try { jarallax($img.parent()[0], 'destroy'); } catch (e) {}
				$img.unwrap();
				$img.removeClass('jarallax-img');
			}

			// Clean up any leftover empty jarallax bg divs from older buggy code
			$container.find('> .eele-jarallax-bg, > a > .eele-jarallax-bg').each(function () {
				try { jarallax(this, 'destroy'); } catch (e) {}
			}).remove();

			// Wrap the original image so it becomes the parallax source itself.
			// Do NOT add `eele-jarallax-bg` — that class has global CSS
			// (position:absolute; z-index:-1) for container background parallax,
			// which would hide the image entirely.
			$img.addClass('jarallax-img');
			$img.wrap('<div class="jarallax jarallax-container eele-img-jarallax-wrap"></div>');
			var $div = $img.parent();

			function initParallax() {
				// Give the wrapper a size so it doesn't collapse once jarallax positions
				// the img absolutely. User-set width/height (via panel) uses !important
				// and will still override these fallbacks.
				var nW = $img[0].naturalWidth;
				var nH = $img[0].naturalHeight;
				if (nW && nH) {
					if (!$div[0].style.width)  $div.css('width', '100%');
					if (!$div[0].style.height) $div.css('aspect-ratio', nW + ' / ' + nH);
				} else if (!$div[0].style.height) {
					var fallbackH = $div[0].getBoundingClientRect().height;
					if (fallbackH > 0) $div.css('height', fallbackH + 'px');
				}

				jarallax($div[0], {
					speed: 0.5,
					imgPosition: '50% 50%',
					imgRepeat: 'no-repeat',
					imgSize: 'cover',
					zIndex: 0
				});
			}

			if ($img[0].complete && $img[0].naturalWidth > 0) {
				initParallax();
			} else {
				$img.one('load', initParallax);
			}
		};

		elementorFrontend.hooks.addAction("frontend/element_ready/global", EasyJarallax);
		elementorFrontend.hooks.addAction("frontend/element_ready/image.default", EasyJarallaxImage);
		elementorFrontend.hooks.addAction("frontend/element_ready/eel-advance-image.default", EasyJarallaxImage);

		// --- Lenis integration for Jarallax ---
		function syncJarallaxWithLenis() {
			if (window.lenis && typeof jarallax !== 'undefined') {
				window.lenis.on('scroll', function () {
					jarallax(document.querySelectorAll('.jarallax'), 'onScroll');
				});
			} else {
				setTimeout(syncJarallaxWithLenis, 200);
			}
		}
		syncJarallaxWithLenis();
	});
	
	
	jQuery(window).on('elementor/frontend/init', function() {

		elementorFrontend.hooks.addAction('frontend/element_ready/eel-video-popup.default', function($scope, $) {
			
			const wrapper = $scope.find('.eel-video-popup-wrapper');
			const overlay = wrapper.find('.eel-video-overlay');
			// Find video element (try with class first, then without)
			let videoEl = wrapper.find('video.eel-normal-video');
			if (!videoEl.length) {
				videoEl = wrapper.find('video');
			}
			// Find iframe element (try with class first, then without)
			let iframeEl = wrapper.find('iframe.eel-normal-video');
			if (!iframeEl.length) {
				iframeEl = wrapper.find('iframe');
			}

			// Helper function to get YouTube embed URL
			function getYouTubeEmbedUrl(url) {
				const regExp = /(?:v=|\/)([0-9A-Za-z_-]{11}).*/;
				const match = url.match(regExp);
				if (match && match[1]) {
					return 'https://www.youtube.com/embed/' + match[1] + '?autoplay=1';
				}
				return null;
			}

			// Helper function to get Vimeo embed URL
			function getVimeoEmbedUrl(url) {
				const match = url.match(/(\d+)/);
				if (match && match[1]) {
					return 'https://player.vimeo.com/video/' + match[1] + '?autoplay=1';
				}
				return null;
			}

			// Helper function to open lightbox
			function openLightbox(videoType, videoUrl, popupId, animation) {
				const lightboxOverlay = $('#' + popupId);
				const iframeWrapper = lightboxOverlay.find('.eel-video-popup-iframe-wrapper');
				const lightboxContent = lightboxOverlay.find('.eel-video-popup-content');
				
				if (!lightboxOverlay.length) return;
				
				// Prevent duplicate opening if already active
				if (lightboxOverlay.hasClass('active')) {
					return;
				}
				
				let embedHtml = '';
				
				if (videoType === 'youtube') {
					const embedUrl = getYouTubeEmbedUrl(videoUrl);
					if (embedUrl) {
						embedHtml = '<iframe src="' + embedUrl + '" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
					}
				} else if (videoType === 'vimeo') {
					const embedUrl = getVimeoEmbedUrl(videoUrl);
					if (embedUrl) {
						embedHtml = '<iframe src="' + embedUrl + '" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
					}
				} else if (videoType === 'self_hosted') {
					embedHtml = '<video src="' + videoUrl + '" controls autoplay></video>';
				}
				
				if (embedHtml) {
					iframeWrapper.html(embedHtml);
					
					// Apply animation if set
					if (animation && animation.trim() !== '') {
						// Remove any existing animation classes
						const currentClasses = lightboxContent.attr('class') || '';
						const animationClasses = currentClasses.match(/\banimated\s+\S+/g);
						if (animationClasses) {
							lightboxContent.removeClass(animationClasses.join(' '));
						}
						// Add new animation classes
						lightboxContent.addClass('animated ' + animation.trim());
					} else {
						// Remove animation classes if no animation
						const currentClasses = lightboxContent.attr('class') || '';
						const animationClasses = currentClasses.match(/\banimated\s+\S+/g);
						if (animationClasses) {
							lightboxContent.removeClass(animationClasses.join(' '));
						}
					}
					
					lightboxOverlay.addClass('active');
					$('body').css('overflow', 'hidden');
				}
			}

			// Close lightbox handler
			$(document).on('click', '.eel-video-popup-close', function(e) {
				e.preventDefault();
				const lightboxOverlay = $(this).closest('.eel-video-popup-overlay');
				const lightboxContent = lightboxOverlay.find('.eel-video-popup-content');
				lightboxOverlay.removeClass('active');
				lightboxContent.removeClass('animated');
				lightboxOverlay.find('.eel-video-popup-iframe-wrapper').html('');
				$('body').css('overflow', '');
			});

			// Close lightbox on overlay click
			$(document).on('click', '.eel-video-popup-overlay', function(e) {
				if ($(e.target).hasClass('eel-video-popup-overlay')) {
					const lightboxContent = $(this).find('.eel-video-popup-content');
					$(this).removeClass('active');
					lightboxContent.removeClass('animated');
					$(this).find('.eel-video-popup-iframe-wrapper').html('');
					$('body').css('overflow', '');
				}
			});

			// Close lightbox on ESC key
			$(document).on('keydown', function(e) {
				if (e.keyCode === 27) { // ESC key
					$('.eel-video-popup-overlay.active').each(function() {
						const lightboxContent = $(this).find('.eel-video-popup-content');
						$(this).removeClass('active');
						lightboxContent.removeClass('animated');
						$(this).find('.eel-video-popup-iframe-wrapper').html('');
						$('body').css('overflow', '');
					});
				}
			});

			if (overlay.length) {
				// Remove existing handler to prevent duplicate
				overlay.off('click.eelVideoOverlay');
				overlay.on('click.eelVideoOverlay', function(e) {
					e.preventDefault();
					e.stopPropagation();
					
					const $overlay = $(this);
					const useLightbox = $overlay.data('lightbox') === 'yes';
					const videoType = $overlay.data('video-type');
					const videoUrl = $overlay.data('video-url');
					const popupId = $overlay.data('popup-id');
					const animation = $overlay.data('animation') || '';
					
					// If lightbox is enabled, open lightbox
					if (useLightbox && videoType && videoUrl && popupId) {
						// Check if lightbox is already open to prevent duplicate
						const lightboxOverlay = $('#' + popupId);
						if (lightboxOverlay.hasClass('active')) {
							return;
						}
						openLightbox(videoType, videoUrl, popupId, animation);
						return;
					}
					
					// Otherwise, play inline (original behavior)
					// Self-hosted video play
					if (videoEl.length) {
						const video = videoEl.get(0);
						
						// Function to play video
						const playVideo = function() {
							const playPromise = video.play();
							if (playPromise !== undefined) {
								playPromise.catch(function(error) {
									console.log('Video play failed:', error);
									// If play fails due to autoplay policy, try with muted
									if (error.name === 'NotAllowedError' && !video.muted) {
										video.muted = true;
										video.play().then(function() {
											// Unmute after play starts (if user wants sound)
											setTimeout(function() {
												video.muted = false;
											}, 100);
										}).catch(function(err) {
											console.log('Video play with mute also failed:', err);
										});
									}
								});
							}
						};
						
						// Always try to load and play
						if (video.readyState >= 2) {
							// Video is already loaded, play immediately
							playVideo();
						} else {
							// Video not loaded, load it first then play
							video.load();
							// Wait for video to be ready to play
							const tryPlay = function() {
								if (video.readyState >= 2) {
									playVideo();
								} else {
									video.addEventListener('canplay', playVideo, { once: true });
									video.addEventListener('loadeddata', playVideo, { once: true });
								}
							};
							tryPlay();
						}
					}

					// YouTube / Vimeo iframe autoplay
					if (iframeEl.length) {
						let src = iframeEl.attr('src');
						if (src) {
							// Check if it's YouTube or Vimeo
							const isYouTube = src.indexOf('youtube.com') !== -1 || src.indexOf('youtu.be') !== -1;
							const isVimeo = src.indexOf('vimeo.com') !== -1;
							
							if (isYouTube || isVimeo) {
								try {
									// Use URL object for proper parsing
									const url = new URL(src);
									
									if (isYouTube) {
										// YouTube: add autoplay, preserve original mute setting
										url.searchParams.set('autoplay', '1');
										// Only set mute=1 if it was already in the URL (preserve original setting)
										// If mute was not in original URL, don't add it
										if (!url.searchParams.has('mute')) {
											// Don't add mute if it wasn't there originally
										} else {
											// Keep the original mute value
										}
										// Reload iframe with new src
										iframeEl.attr('src', url.toString());
									} else if (isVimeo) {
										// Vimeo: add autoplay (Vimeo doesn't need mute for autoplay)
										url.searchParams.set('autoplay', '1');
										// Reload iframe with new src
										iframeEl.attr('src', url.toString());
									}
								} catch (e) {
									// Fallback for browsers that don't support URL constructor
									// Remove existing autoplay parameter if present
									src = src.replace(/[?&]autoplay=[^&]*/g, '');
									
									if (isYouTube) {
										// Check if mute parameter exists in original URL
										const hasMute = /[?&]mute=/.test(src);
										// Add autoplay=1
										const separator = src.indexOf('?') === -1 ? '?' : '&';
										src += separator + 'autoplay=1';
										// Only add mute if it was already there (preserve original setting)
										// Don't add mute if it wasn't in original URL
									} else if (isVimeo) {
										// Vimeo: just add autoplay=1
										const separator = src.indexOf('?') === -1 ? '?' : '&';
										src += separator + 'autoplay=1';
									}
									
									// Update src without clearing first
									iframeEl.attr('src', src);
								}
							}
						}
					}
					
					// Remove overlay after a small delay to ensure video starts
					setTimeout(function() {
						overlay.fadeOut(300, function() {
							$(this).remove();
						});
					}, 200);
				});
			}

			// Handle popup button clicks (for display_type = 'popup')
			const popupBtn = wrapper.find('.entro-all-video-popup');
			if (popupBtn.length) {
				// If the global popup (from pro plugin) exists, let eel-popup.js handle it
				// to avoid transform/motion effect containment issues with position:fixed
				const globalPopup = document.querySelector('.entro-all-video-popup-wrap');
				if (globalPopup) {
					// Global popup exists — do not bind per-widget handler.
					// The pro plugin's eel-popup.js will handle it since it's already in body.
				} else {
					// No global popup — use per-widget overlay, moved to body
					const popupOverlay = wrapper.find('.eel-video-popup-overlay');
					if (popupOverlay.length && !popupOverlay.data('moved-to-body')) {
						popupOverlay.data('moved-to-body', true);
						popupOverlay.appendTo('body');
					}

					// Remove existing handler to prevent duplicate
					popupBtn.off('click.eelVideoPopup');
					popupBtn.on('click.eelVideoPopup', function(e) {
						e.preventDefault();
						e.stopPropagation();
						const $btn = $(this);
						const videoType = $btn.data('video-type');
						const videoUrl = $btn.data('video-src');
						const popupId = $btn.data('popup-id');
						const lightboxOverlay = $('#' + popupId);
						const animation = lightboxOverlay.data('animation') || '';

						if (videoType && videoUrl && popupId) {
							// Check if lightbox is already open to prevent duplicate
							if (lightboxOverlay.hasClass('active')) {
								return;
							}
							openLightbox(videoType, videoUrl, popupId, animation);
						}
					});
				}
			}
		});

	});

	//  Single Page Navigation
	document.addEventListener('DOMContentLoaded', function () {
		const navWrappers = document.querySelectorAll('.eel-single-nav-list');
		if (!navWrappers.length) return;

		navWrappers.forEach(function (wrapper) {

			const navLinks = wrapper.querySelectorAll('.eel-nav-item a');
			const navItems = wrapper.querySelectorAll('.eel-nav-item');

			if (!navLinks.length) return;

			wrapper.addEventListener('click', function (e) {

				const link = e.target.closest('.eel-nav-item a');
				if (!link || !wrapper.contains(link)) return;

				const href = link.getAttribute('href');

				if (!href || href === '#' || !href.startsWith('#') || href.length === 1) {
					return;
				}

				const target = document.querySelector(href);
				if (!target) return;

				e.preventDefault();

				target.scrollIntoView({
					behavior: 'smooth',
					block: 'start'
				});
			});

			/* ========= SCROLL → ACTIVE CHANGE ========= */
			const sectionsMap = new Map();

			navLinks.forEach(link => {
				const href = link.getAttribute('href');

				if (!href || href === '#' || !href.startsWith('#') || href.length === 1) {
					return;
				}

				const section = document.querySelector(href);
				if (section) {
					sectionsMap.set(section, href);
				}
			});

			function setActive(href) {
				navItems.forEach(item => item.classList.remove('active'));

				navLinks.forEach(link => {
					if (link.getAttribute('href') === href) {
						link.closest('.eel-nav-item')?.classList.add('active');
					}
				});
			}

			const observer = new IntersectionObserver(
				(entries) => {
					entries.forEach(entry => {
						if (entry.isIntersecting) {
							const id = sectionsMap.get(entry.target);
							if (id) setActive(id);
						}
					});
				},
				{
					root: null,
					rootMargin: '-40% 0px -40% 0px',
					threshold: 0.1
				}
			);

			sectionsMap.forEach((id, section) => {
				observer.observe(section);
			});

		});

	});

	// Sticky Navigation single page

	function applyStickyToContainer($container) {
		if ($container.find('.elementor-widget-eel-single-nav').length === 0) {
			return;
		}

		$container.addClass('eel-single-nav-sticky-enabled');
	}

	/* ===== Frontend ===== */
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/container',
			function ($scope) {
				applyStickyToContainer($scope);
			}
		);
	});

	/* ===== Editor ===== */
	if (window.elementor && elementor.isEditMode()) {
		elementor.hooks.addAction('editor:init', function () {
			elementorFrontend.hooks.addAction(
				'frontend/element_ready/container',
				function ($scope) {
					applyStickyToContainer($scope);
				}
			);
		});
	}

	// Mobile Menu Sidebar
	if ($('.sidebar-on-mobile-single-nav').length) {		
		// Sidebar toggle
		$('.eel-nav-menu-icon').on('click', function(e){
			e.stopPropagation();
			$('.sidebar-on-mobile-single-nav').toggleClass('easyel-open');
			$('body').toggleClass('sidebar-on-mobile-single-nav-active');
		});
		// Click outside to close sidebar
		$(document).on('click', function(){
			$('.sidebar-on-mobile-single-nav').removeClass('easyel-open');
			$('body').removeClass('sidebar-on-mobile-single-nav-active');			
			$('.sidebar-on-mobile-single-nav ul.sub-menu').slideUp(300);
			$('.sidebar-on-mobile-single-nav .sub-arrow').removeClass('active').attr('aria-expanded', false);
		});

		$('.sidebar-on-mobile-single-nav, .eel-nav-menu').on('click', 'li:not(.menu-item-has-children) a', function(e){
			$('.sidebar-on-mobile-single-nav li a, .eel-nav-menu li a').removeClass('eel-active');
			$(this).addClass('eel-active');
			$('.sidebar-on-mobile-single-nav').removeClass('easyel-open');
			$('body').removeClass('sidebar-on-mobile-single-nav-active');
			$('body').removeClass('sidebar-on-mobile-active');
		});

		// Prevent closing when clicking inside sidebar
		$('.sidebar-on-mobile-single-nav').on('click', function(e){
			e.stopPropagation();
		});		
	}

	jQuery(document).ready(function($){
		var $allSidebars = $('.sidebar-on-mobile-mega-nav');
		var $body = $('body');

		if(!$allSidebars.length) return;

		/* ========== Inject submenu icon ========== */
		$allSidebars.find('li.menu-item-has-children').each(function(){
			if(!$(this).children('.submenu-parent-icon').length){
				var svgIcon = '<span class="submenu-parent-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M201.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 338.7 54.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg></span>';
				$(this).prepend(svgIcon);
			}
		});

		// Open sidebar — match hamburger to its own sidebar via data attributes
		$(document).on('click', '.eel-mega-menu-icon-mobile', function(e){
			e.preventDefault();
			e.stopPropagation();
			var targetId = $(this).attr('data-mega-target');
			var $sidebar;
			if (targetId) {
				$sidebar = $('.sidebar-on-mobile-mega-nav[data-mega-id="' + targetId + '"]');
			}
			// Fallback for old markup without data attributes
			if (!$sidebar || !$sidebar.length) {
				$sidebar = $allSidebars.first();
			}
			$allSidebars.not($sidebar).removeClass('easyel-open');
			$sidebar.addClass('easyel-open');
			$body.addClass('sidebar-on-mobile-mega-nav-active');
		});

		// Close sidebar (icon)
		$(document).on('click', '.eel-mega-menu-icon-close', function(e){
			e.preventDefault();
			e.stopPropagation();
			$(this).closest('.sidebar-on-mobile-mega-nav').removeClass('easyel-open');
			$body.removeClass('sidebar-on-mobile-mega-nav-active');
		});

		// Click outside sidebar (overlay)
		$(document).on('click', function(){
			$allSidebars.removeClass('easyel-open');
			$body.removeClass('sidebar-on-mobile-mega-nav-active');
		});

		// Prevent sidebar clicks from closing
		$(document).on('click', '.sidebar-on-mobile-mega-nav', function(e){
			if (!$(e.target).closest('.eel-mega-menu-icon-close').length) {
				e.stopPropagation();
			}
		});

		/* ========== Submenu toggle ========== */
		$(document).on('click', '.sidebar-on-mobile-mega-nav .submenu-parent-icon', function(e){
			e.preventDefault();
			e.stopPropagation();

			var $li = $(this).closest('li.menu-item-has-children');
			var $submenu = $li.children('ul.sub-menu');

			// close other submenus
			$li.siblings('li.menu-item-has-children')
				.removeClass('eel-sub-open')
				.children('ul.sub-menu').slideUp(300);

			$li.siblings('li.menu-item-has-children')
				.children('.submenu-parent-icon').removeClass('active');

			// toggle current
			$submenu.slideToggle(300);
			$li.toggleClass('eel-sub-open');
			$(this).toggleClass('active');
		});
	});

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/eel-vertical-navigation.default', function($scope) {
			
			var $sidebar = $scope.find('.sidebar-on-mobile-vertical-nav');
			var $body = jQuery('body');

			if($sidebar.length) {
				$sidebar.find('li.menu-item-has-children').each(function(){
					if(!jQuery(this).children('.submenu-parent-icon').length){
						var svgIcon = '<span class="submenu-parent-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M201.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 338.7 54.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg></span>';
						jQuery(this).prepend(svgIcon);
					}
				});

				$scope.on('click', '.eel-vertical-menu-icon-mobile', function(e){
					e.preventDefault();
					e.stopPropagation();
					$sidebar.addClass('easyel-open');
					$body.addClass('sidebar-on-mobile-vertical-nav-active');
				});

				$scope.on('click', '.eel-vertical-menu-icon-close', function(e){
					e.preventDefault();
					e.stopPropagation();
					closeSidebar();
				});

				jQuery(document).on('click', function(e){
					if (!jQuery(e.target).closest('.sidebar-on-mobile-vertical-nav, .eel-vertical-menu-icon-mobile').length) {
						closeSidebar();
					}
				});

				function closeSidebar() {
					$sidebar.removeClass('easyel-open');
					$body.removeClass('sidebar-on-mobile-vertical-nav-active');
				}

				/* ========== Submenu Toggle (Accordion) ========== */
				$sidebar.find('.submenu-parent-icon').off('click').on('click', function(e){
					e.preventDefault();
					e.stopPropagation();

					var $this = jQuery(this);
					var $li = $this.closest('li.menu-item-has-children');
					var $submenu = $li.children('ul.sub-menu');
					$li.siblings('li.menu-item-has-children').each(function(){
						var $sibling = jQuery(this);
						$sibling.removeClass('eel-sub-open is-open-vertical');
						$sibling.children('ul.sub-menu').slideUp(300);
						$sibling.children('.submenu-parent-icon').removeClass('active');
						$sibling.find('> a').removeClass('eel-vertical-open');
					});
					if ($li.hasClass('eel-sub-open')) {
						$submenu.slideUp(300);
						$li.removeClass('eel-sub-open is-open-vertical');
						$this.removeClass('active');
						$li.find('> a').removeClass('eel-vertical-open');
					} else {
						$submenu.slideDown(300);
						$li.addClass('eel-sub-open is-open-vertical');
						$this.addClass('active');
						$li.find('> a').addClass('eel-vertical-open');
					}
				});
			}
		});
	});

})(jQuery);

(function ($) {
    function eelMegaNavigationInit() {
        $('.elementor-widget-eel-mega-navigation')
        .parent()
        .addClass('parant-eel-mega-static');
    }
    // Frontend
    $(window).on('load', function () {
        eelMegaNavigationInit();
    });

    // Elementor Editor
    $(window).on('elementor/frontend/init', function () {
        if (elementorFrontend.isEditMode()) {
            eelMegaNavigationInit();
        }
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/eel-mega-navigation.default',
            eelMegaNavigationInit
        );
    });

})(jQuery);



(function($){

    let eelObserverAttached = false;

    function updateMegaMenuActive() {
        const $mega = $('.easyel--elementor-template-mega-menu');
        if (!$mega.length) return;

        const $parent = $mega.parent();

        if ($mega.hasClass('easyel-mega--current')) {
            $parent.addClass('easyel-mega--current-active menu-item-has-children');
        } else {
            $parent.removeClass('easyel-mega--current-active menu-item-has-children');
        }
    }

    function updateElementorPositions() {
        if ($('.easyel-mega--current-active').length) {
            $('header .elementor-element').css('position', 'static');
            $('.easyel-mega--current-active .elementor-element').css('position', '');
        } else {
            $('header .elementor-element').css('position', '');
        }
    }

    function attachObserverOnce() {
        if (eelObserverAttached) return;
        eelObserverAttached = true;

        const observer = new MutationObserver(() => {
            updateMegaMenuActive();
            updateElementorPositions();
        });

        observer.observe(document.body, {
            attributes: true,
            subtree: true,
            attributeFilter: ['class']
        });
    }

    function initMegaMenuJS(scope) {
        if (!scope.find('.elementor-widget-eel-navigation-menu').length) return;

        updateMegaMenuActive();
        updateElementorPositions();
        attachObserverOnce();
    }

    // Frontend
    $(window).on('load', function(){
        initMegaMenuJS($(document.body));
    });

    // Elementor Editor
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/eel-navigation-menu.default',
            function($scope){
                initMegaMenuJS($scope);
            }
        );
    });


	// Image Comparison
	var eelImageBeforeAfter = function ($scope, $) {
		var $container = $scope.find(".eel_comparison-container");
		if (!$container.length) {
			return;
		}
		$container.each(function () {
			var $this = $(this);
			var offset      = $this.data("offset") || 0.5;
			var orientation = $this.data("orientation") || "horizontal";
			var before_text = $this.data("before_label") || "Before";
			var after_text  = $this.data("after_label") || "After";
			var initeel_comparison = function() {
				if ($.isFunction($.fn.eel_comparison)) {
					$this.eel_comparison({
						default_offset_pct: offset,
						orientation: orientation,
						before_label: before_text,
						after_label: after_text,
						no_overlay: false,
						move_slider_on_hover: false,
						click_to_move: true
					});
				}
			};
			if (document.readyState === 'complete') {
				initeel_comparison();
			} else {
				$(window).on('load', initeel_comparison);
			}
		});
	};
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/eel-image-before-after.default', eelImageBeforeAfter);
	});


	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/eel-vertical-navigation.default', function($scope) {
			const menuContainer = $scope.find('.eel-vertical-verticalmenu.eel_click');
			if (menuContainer.length) {
				const icons = $scope.find('.eel-vertical-menu-area .submenu-parent-icon');

				icons.on('click', function(e) {
					e.preventDefault();
					e.stopPropagation();

					const $this = jQuery(this);
					const parentLi = $this.closest('li');
					const subMenu = parentLi.find('> .sub-menu.eel_click');
					const parentLink = $this.closest('a');

					if (subMenu.length === 0) return;

					if (parentLi.hasClass('is-open-vertical')) {
						subMenu.css('max-height', subMenu[0].scrollHeight + 'px');
						setTimeout(() => {
							subMenu.css({
								'max-height': '0',
								'opacity': '0'
							});
						}, 10);

						parentLi.removeClass('is-open-vertical');
						parentLink.removeClass('eel-vertical-open');
					} else {
						const siblings = parentLi.siblings('.is-open-vertical');
						siblings.each(function() {
							const siblingSub = jQuery(this).find('> .sub-menu.eel_click');
							siblingSub.css({
								'max-height': '0',
								'opacity': '0'
							});
							jQuery(this).removeClass('is-open-vertical');
							jQuery(this).find('> a').removeClass('eel-vertical-open');
						});

						parentLi.addClass('is-open-vertical');
						parentLink.addClass('eel-vertical-open');
						subMenu.css({
							'opacity': '1',
							'max-height': subMenu[0].scrollHeight + 'px'
						});

						subMenu.one('transitionend', function() {
							if (parentLi.hasClass('is-open-vertical')) {
								subMenu.css('max-height', 'none');
							}
						});
					}
				});
			}
		});
	});

})(jQuery);



