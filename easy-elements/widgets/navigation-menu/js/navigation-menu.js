( function( $ ) {
	// Mobile Menu Sidebar
	var $allNavSidebars = $('.sidebar-on-mobile');
	if ($allNavSidebars.length) {
		// Move sidebars to <body> so parent transforms (Elementor Motion Effects, etc.)
		// don't break the position:fixed sidebar.
		$allNavSidebars.each(function () {
			var $s = $(this);
			if ( ! $s.parent().is('body') ) {
				$s.appendTo('body');
			}
		});
		// Add icon inside each .sub-arrow dynamically
		$allNavSidebars.find('ul > .menu-item-has-children a, ul > li:has(.easyel--elementor-template-mega-menu) > a').each(function(){
			var $a = $(this);
			var href = $a.attr('href');

			if (href === '#' || href === '' || href === 'javascript:void(0)') {
				$a.addClass('easyel-hash');
			}

			$a.append('<i class="unicon-chevron-down"></i>');
		});

		// Sidebar toggle — match hamburger to its own sidebar via data attributes
		$(document).on('click', '.eel-nav-menu-icon', function(e){
			e.stopPropagation();
			var targetId = $(this).attr('data-nav-target');
			var $sidebar;
			if (targetId) {
				$sidebar = $('.sidebar-on-mobile[data-nav-id="' + targetId + '"]');
			}
			// Fallback for old markup without data attributes
			if (!$sidebar || !$sidebar.length) {
				$sidebar = $allNavSidebars.first();
			}
			$allNavSidebars.not($sidebar).removeClass('easyel-open');
			$sidebar.toggleClass('easyel-open');
			$('body').toggleClass('sidebar-on-mobile-active');
		});

		// Submenu toggle on click of .sub-arrow OR .easyel-hash link
		$(document).on('click', '.sidebar-on-mobile .unicon-chevron-down, .sidebar-on-mobile a.easyel-hash', function(e){
			e.preventDefault();
			e.stopPropagation();

			var $parentLi = $(this).closest('li.menu-item-has-children');
			var $submenu = $parentLi.children('ul.sub-menu');

			// Toggle current submenu
			$submenu.stop(true, true).slideToggle(300);

			// Close other submenus at same level
			$parentLi.siblings('li.menu-item-has-children').children('ul.sub-menu').slideUp(300);

			// Toggle aria-expanded
			var expanded = $(this).attr('aria-expanded') === 'true';
			$(this).attr('aria-expanded', !expanded);

			// Toggle active class for icon rotation
			$(this).toggleClass('active');
		});

		// Click outside to close sidebar
		$(document).on('click', function(){
			$allNavSidebars.removeClass('easyel-open');
			$('body').removeClass('sidebar-on-mobile-active');
			$allNavSidebars.find('ul.sub-menu').slideUp(300);
			$allNavSidebars.find('.sub-arrow').removeClass('active').attr('aria-expanded', false);
		});

		$(document).on('click', '.sidebar-on-mobile li:not(.menu-item-has-children) a, .eel-nav-menu li:not(.menu-item-has-children) a', function(e){
			$('.sidebar-on-mobile li a, .eel-nav-menu li a').removeClass('eel-active');
			$(this).addClass('eel-active');
			$allNavSidebars.removeClass('easyel-open');
			$('body').removeClass('sidebar-on-mobile-active');
		});

		// Prevent closing when clicking inside sidebar
		$(document).on('click', '.sidebar-on-mobile', function(e){
			if (!$(e.target).closest('.eel-nav-menu-icon').length) {
				e.stopPropagation();
			}
		});
	}
	
	$( window ).on( 'elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/eel-navigation-menu.default' );
	});

} )( jQuery ); 


( function($){
    var EasyMenuIconHandler = function( $scope ){
        var $menu = $scope.find('.eel-nav-menu__layout-vertical');
        if (!$menu.length) return;
        var icon = $menu.data('vertical-icon');
        if (!icon) return;
        $menu.find('li > a.eel-menu-item').each(function() {
            if (!$(this).find('.eel-menu-dynamic-icon').length) {
                $(this).prepend('<i class="eel-menu-dynamic-icon ' + icon + '"></i>');
            }
        });
    };

    // Elementor Frontend + Editor
    $(window).on('elementor/frontend/init', function(){
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/eel-navigation-menu.default',
            EasyMenuIconHandler
        );
    });

	document.addEventListener('DOMContentLoaded', function () {
		const toggle = document.querySelector('.eel-nav-menu__toggle');
		const menu   = document.querySelector('.eel-nav-menu');

		if (!toggle || !menu) return;

		toggle.addEventListener('click', function () {
			menu.classList.toggle('is-open');
		});	
	});

})(jQuery);
