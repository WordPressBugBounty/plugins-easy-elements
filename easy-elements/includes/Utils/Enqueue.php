<?php
namespace Easyel\EasyElements\Utils;
if ( ! defined( 'ABSPATH' ) ) exit;

class Enqueue {

    private static $instance = null;
    const VERSION = EASYELEMENTS_VER;
    public static function get_instance() {
        if ( ! self::$instance ) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        // Assets
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ], 99 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ] );
        add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );

        add_filter( 'script_loader_tag', [ $this, 'easyel_add_defer_attribute_free' ], 10, 2 );

        add_action( 'wp_enqueue_scripts', [ $this, 'easyel_register_free_all_widget_assets'] );
        add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'easyel_register_free_all_widget_assets'] );

    }


    public function enqueue_frontend_assets() {
		$dir = EASYELEMENTS_DIR_URL;
		if ( is_admin() ) return;
		if ( ! class_exists( 'Elementor\Plugin' ) ) return;
		wp_enqueue_style( 'swiper', ELEMENTOR_ASSETS_URL . 'lib/swiper/css/swiper-bundle.min.css', [], '8.0.7' );
		wp_enqueue_style( 'easy-hfe-elementor', EASYELEMENTS_ASSETS_URL . 'header-footer/css/easy-hfe-elementor.css', [], self::VERSION  );
        wp_enqueue_style( 'e-e-easy-custom-icons', $dir . 'assets/simplelineicons/css/simple-line-icons.css', [], self::VERSION );

		wp_enqueue_style( 'eel-elements-plugins', $dir . 'assets/css/plugins.min.css', [], self::VERSION );		
		wp_enqueue_script( 'swiper', ELEMENTOR_ASSETS_URL . 'lib/swiper/swiper-bundle.min.js', [ 'jquery' ], '8.0.7', true );
		wp_enqueue_script( 'jarallax', $dir . 'assets/js/jarallax.js', [ 'jquery' ], '2.2.1', true );
		wp_enqueue_script( 'eel-custom-js', $dir . 'assets/js/custom.js', [ 'jquery' ], self::VERSION, true );		
		wp_enqueue_script( 'easy-wrapper-link', $dir . 'assets/js/easy-wrapper-link.js', [ 'jquery' ], self::VERSION, true );	

        /**
         *  Dynamic scrollbar CSS variables
         */
        $options = get_option( 'easyel_scroll_smoother_settings', [] );

        $width     = ! empty( $options['smooth_scroll_width'] ) ? intval( $options['smooth_scroll_width'] ) . 'px' : '3px';
        $track     = ! empty( $options['smooth_scroll_normal_color'] ) ? $options['smooth_scroll_normal_color'] : '#dddddd';
        $thumb     = ! empty( $options['smooth_scroll_highlight_color'] ) ? $options['smooth_scroll_highlight_color'] : 'var(--e-global-color-primary)';

        $dynamic_css = "
            :root {
                --easyel-scrollbar-width: {$width};
                --easyel-scrollbar-track: {$track};
                --easyel-scrollbar-thumb: {$thumb};
            }
        ";

        wp_add_inline_style( 'eel-elements-plugins', $dynamic_css );
        
        
	}


	public function enqueue_admin_styles() {
		global $pagenow;
		$screen = get_current_screen();
		$dir = EASYELEMENTS_DIR_URL;
		wp_enqueue_style( 'e-e-elements-admin', $dir . 'assets/css/admin/admin.min.css', [], self::VERSION );
		wp_enqueue_style( 'e-e-easy-custom-icons', $dir . 'assets/simplelineicons/css/simple-line-icons.css', [], self::VERSION );
		wp_enqueue_style( 'e-e-easy-icons-font', $dir . 'includes/Admin/icons/css/easy-icons.css', [], self::VERSION );
		wp_enqueue_style('e-e-admin-fonts-inter','https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',false, self::VERSION );

		if ( ( 'ee-elementor-hf' == $screen->id && ( 'post.php' == $pagenow || 'post-new.php' == $pagenow ) ) || ( 'edit.php' == $pagenow && 'edit-ee-elementor-hf' == $screen->id ) ) {
			wp_enqueue_script( 'easy-ehf-admin-script',  $dir . 'assets/header-footer/js/ee-ehf-admin.js', [ 'jquery', 'updates' ], self::VERSION, true );

		}
	}

    public function enqueue_editor_scripts() {
		wp_enqueue_style( 'eel-elements-editor', EASYELEMENTS_DIR_URL . 'assets/css/editor.min.css', [], self::VERSION );
		wp_enqueue_style( 'e-e-easy-custom-icons', EASYELEMENTS_DIR_URL . 'includes/Admin/icons/css/easy-icons.css', [], self::VERSION );
	}
    

    
    /**
     * ------------------------------------------------------------
     * Add defer attribute
     * ------------------------------------------------------------
     */
    function easyel_add_defer_attribute_free( $tag, $handle ) {

        $defer_handles = [
            'swiper',
            'jarallax',
            'eel-custom-js',
            'easy-wrapper-link',
            'eel-countdown-script',
            'eel-counter-script',
            'eel-faq-accordion-script',
            'eel-gallery-script',
            'eel-login-register-script',
            'eel-navigation-menu-script',
            'eel-offcanvas-script',
            'eel-scroll-to-top-script',
            'eel-search-script',
            'eel-tab-script',
            'eel-table-script',
            'eel-team-grid-script',
            'eel-testimonials-script',
            'eel-image-before-after-script',
        ];

        if ( in_array( $handle, $defer_handles, true ) ) {
            return str_replace( ' src', ' defer src', $tag );
        }

        return $tag;
    }

    public function easyel_register_free_all_widget_assets() {

        $widgets_assets = $this->easyel_enqueue_widget_assets();

        $single_archive_widget = array(
            'eel-featured-image',
            'eel-post-content',
            'eel-excerpt',
            "eel-post-author-info",
            'eel-post-comments',
            'eel-post-meta',
            'eel-post-pagination',
            'eel-post-tags',
            'eel-post-title',
            "eel-breadcrumb",
            "eel-navigation-menu",
            "eel-site-logo",
        );

        foreach ( $widgets_assets as $widget => $assets ) {

            /**
             * CSS register
             */
            if ( ! empty( $assets['css'] ) ) {
                foreach ( $assets['css'] as $css ) {

                    $handle = $widget;
                    $path   = EASYELEMENTS_DIR_PATH . $css;
                    $url    = EASYELEMENTS_DIR_URL . $css;

                    if ( ! file_exists( $path ) ) {
                        continue;
                    }

                    $version = filemtime( $path );

                    if ( is_singular( 'post' ) && in_array( $handle, $single_archive_widget, true ) ) {

                        wp_enqueue_style(
                            $handle,
                            $url,
                            [],
                            $version
                        );

                    } else {

                        wp_register_style(
                            $handle,
                            $url,
                            [],
                            $version
                        );
                    }
                }
            }

            /**
             * JS register
             */
            if ( ! empty( $assets['js'] ) ) {
                foreach ( $assets['js'] as $js ) {

                    $handle = $widget;
                    $path   = EASYELEMENTS_DIR_PATH . $js;

                    if ( file_exists( $path ) ) {
                        wp_register_script(
                            $handle,
                            EASYELEMENTS_DIR_URL . $js,
                            [ 'jquery' ],
                            filemtime( $path ),
                            true
                        );

                        if ( $handle === 'eel-login-register' ) {
                            wp_localize_script( $handle, 'eelLoginRegister', [
                                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                                'nonce'   => wp_create_nonce( 'easy_elements_nonce' ),
                            ] );
                        }
                    }
                }
            }
        }

    }

    function easyel_enqueue_widget_assets() {
        if ( ! class_exists( '\Elementor\Plugin' ) ) return;

        $widgets_assets = [
            'eel-archive-post' => [
                'css' => ['widgets/archive-post/css/archive-post.min.css'],
            ],
            'eel-blog-grid' => [
                'css' => ['widgets/blog-grid/css/blog-grid.min.css'],
            ],
            'eel-breadcrumb' => [
                'css' => ['widgets/breadcrumb/css/breadcrumb.min.css'],
            ],
            'eel-button' => [
                'css' => ['widgets/button/css/button.min.css'],
            ],
            'eel-copyright' => [
                'css' => ['widgets/copyright/css/copyright.min.css'],
            ],
            'eel-cf7' => [
                'css' => ['widgets/cf7/css/cf7.min.css'],
            ],
            'eel-clients-logo-grid' => [
                'css' => ['widgets/clients-logo-grid/css/clients-logo.min.css'],
            ],
            'eel-contact-box' => [
                'css' => ['widgets/contact-box/css/contact-box.min.css'],
            ],
            'eel-countdown' => [
                'css' => ['widgets/countdown/css/countdown.min.css'],
                'js'  => ['widgets/countdown/js/countdown.js'],
            ],
            'eel-counter' => [
                'css' => ['widgets/counter/css/counter.min.css'],
                'js'  => ['widgets/counter/js/counter.js'],
            ],
            'eel-domain-search' => [
                'css' => ['widgets/domain-search/css/domain-search.min.css'],
            ],
            'eel-excerpt' => [
                'css' => ['widgets/excerpt/css/excerpt.min.css'],
            ],
            'eel-faq-accordion' => [
                'css' => ['widgets/faq/css/faq.min.css'],
                'js'  => ['widgets/faq/js/faq.js'],
            ],
            'eel-feature-list' => [
                'css' => ['widgets/feature-list/css/feature-list.min.css'],
            ],
            'eel-featured-image' => [
                'css' => ['widgets/featured-image/css/featured-image.min.css'],
            ],
            'eel-gallery' => [
                'css' => ['widgets/gallery/css/gallery.min.css'],
                'js'  => ['widgets/gallery/js/simple-gallery.js'],
            ],
            'eel-heading' => [
                'css' => ['widgets/heading/css/heading.min.css'],
            ],
            'eel-icon-box' => [
                'css' => ['widgets/icon-box/css/icon-box.min.css'],
            ],
            'eel-login-register' => [
                'css' => ['widgets/login-register/css/login-register.min.css'],
                'js'  => ['widgets/login-register/js/login-register.js'],
            ],
            'eel-navigation-menu' => [
                'css' => ['widgets/navigation-menu/css/navigation-menu.min.css'],
                'js'  => [
                    'widgets/navigation-menu/js/navigation-menu.min.js',
                ],
            ],
            'eel-offcanvas' => [
                'css' => ['widgets/offcanvas/css/offcanvas.min.css'],
                'js'  => ['widgets/offcanvas/js/offcanvas.js'],
            ],
            'eel-page-title' => [
                'css' => ['widgets/page-title/css/page-title.min.css'],
            ],
            'eel-post-author-info' => [
                'css' => ['widgets/post-author/css/post-author.min.css'],
            ],
            'eel-post-comments' => [
                'css' => ['widgets/post-comments/css/post-comments.min.css'],
            ],
            'eel-post-content' => [
                'css' => ['widgets/post-content/css/post-content.min.css'],
            ],
            'eel-post-meta' => [
                'css' => ['widgets/post-meta/css/post-meta.min.css'],
            ],
            'eel-post-pagination' => [
                'css' => ['widgets/post-pagination/css/post-pagination.min.css'],
            ],
            'eel-post-tags' => [
                'css' => ['widgets/post-tag/css/post-tag.min.css'],
            ],
            'eel-post-title' => [
                'css' => ['widgets/post-title/css/post-title.min.css'],
            ],
            'eel-pricing' => [
                'css' => ['widgets/pricing-table/css/pricing.min.css'],
            ],
            'eel-process' => [
                'css' => ['widgets/process-grid/css/process.min.css'],
            ],
            'eel-process-list' => [
                'css' => ['widgets/process-list/css/process-list.min.css'],
            ],
            'easyel-progress-bar' => [
                'css' => ['widgets/progress/css/progress.min.css'],
            ],
            'eel-scroll-to-top' => [
                'css' => ['widgets/scroll-to-top/css/scroll.min.css'],
                'js'  => ['widgets/scroll-to-top/js/scroll.js'],
            ],
            'eel-search' => [
                'css' => ['widgets/search/css/search.min.css'],
                'js'  => ['widgets/search/js/search.js'],
            ],
            'eel-service-list' => [
                'css' => ['widgets/service-list/css/service-list.min.css'],
            ],
            'eel-site-logo' => [
                'css' => ['widgets/site-logo/css/logo.min.css'],
            ],
            'eel-social-icon' => [
                'css' => ['widgets/social-icon/css/social.min.css'],
            ],
            'eel-social-share' => [
                'css' => ['widgets/social-share/css/social-share.min.css'],
            ],
            'eel-tab' => [
                'css' => ['widgets/tab/css/tab.min.css'],
                'js'  => ['widgets/tab/js/tab.js'],
            ],
            'eel-table' => [
                'css' => ['widgets/table/css/table.min.css'],
                'js'  => ['widgets/table/js/table.js'],
            ],
            'eel-team-grid' => [
                'css' => ['widgets/team-grid/css/team-grid.min.css'],
                'js'  => ['widgets/team-grid/js/team.js'],
            ],
            'eel-testimonials' => [
                'css' => ['widgets/testimonials-grid/css/testimonials.min.css'],
                'js'  => ['widgets/testimonials-grid/js/testimonial.js'],
            ],
            'eel-video-popup' => [
                'css' => ['widgets/video/css/video.min.css'],
            ],            
            'eel-single-nav' => [
                'css' => ['widgets/single-nav/css/single-nav.min.css'],
            ],
            'eel-vertical-navigation' => [
                'css' => ['widgets/vertical-menu/css/vertical-menu.min.css'],
            ],
            'eel-image-before-after' => [
                'css' => ['widgets/image-comparison/css/image-before-after.min.css'],
                'js'  => ['widgets/image-comparison/js/event.move.js'],
            ],
            'eel-product-grid-lite' => [
                'css' => ['widgets/product-grid-lite/css/product-grid-lite.min.css'],
            ],
            'eel-mini-cart' => [
                'css' => ['widgets/mini-cart/css/mini-cart.min.css'],
            ],
        ];
        return $widgets_assets;
    }
}