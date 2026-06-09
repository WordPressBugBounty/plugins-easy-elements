<?php
namespace Easyel\EasyElements\Extensions\CustomCss;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Element_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EasyelCustomCss {


    private static $instance = null;

	private $prefix = 'easy_';

	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

    public static function init() {

        $tab_slug = 'extensions';
        $extensions_settings = get_option('easy_element_' . $tab_slug, [] );

        $enable_easy_custom_css = isset( $extensions_settings['enable_easy_custom_css'] ) ? $extensions_settings['enable_easy_custom_css'] : 0;

        add_action( 'elementor/element/after_section_end', [ __CLASS__, 'register_controls' ], 10, 2 );
        add_action( 'elementor/editor/after_enqueue_styles', [ __CLASS__,"easy_custom_css" ] );    
    }

    public static function easy_custom_css() {
        wp_enqueue_style(
            'easy-elements-pro-badge',
            EASYELEMENTS_DIR_URL . 'includes/Extensions/CustomCss/css/badge.css',
            [],
            EASYELEMENTS_VER
        );
    }

    public static function register_controls( Controls_Stack $element, $section_id ) {
        if ( 'section_custom_css_pro' !== $section_id ) {
            return;
        }

        if ( ! easyel_premium_addon_active() ) {
            self::easy_add_free_promo_section( $element );
        } else {
            if ( did_action( 'plugins_loaded' ) && function_exists( 'easyel_element_is_enabled' ) &&  easyel_element_is_enabled( 'enable_easy_custom_css' ) ) {

                $pro_version = easyel_get_pro_clean_version();
                
                if (
                    $pro_version &&
                    version_compare( $pro_version, '1.0.8', '>=' )
                ) {

                    if(  class_exists( '\EasyElements_Elementor\Pro\Extension\CustomCss\CustomCssPro' ) ) {
                        \EasyElements_Elementor\Pro\Extension\CustomCss\CustomCssPro::get_instance()->easy_add_custom_css_section(  $element );
                    }
      
        	    } else {
                    \Easy_Elements_Custom_CSS_Module_Pro::easy_add_custom_css_section( $element );
                }
                
            }
            
        }
    }

    public static function easy_add_free_promo_section( Controls_Stack $element ) {
        $element->start_controls_section(
            'section_easy_custom_css_free',
            [
                'label' => EASYEL_EXTENSION_BADGE . __( 'Custom CSS', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $promo_html = sprintf(
            '<div class="easy-elements-pro-badge">
                <div class="easy-elements-pro-icon">
                    <!-- SVG Lock Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <p class="easy-elements-pro-text">
                    Unlock <strong>Custom CSS</strong> and style your content instantly
                </p>
                <a href="%s" target="_blank" class="easy-elements-pro-btn">%s</a>
            </div>',
            esc_url( 'https://wpeasyelements.com' ),
            esc_html__( 'Upgrade Easy Elements', 'easy-elements' )
        );

        $element->add_control(
            'easy_custom_css_promo',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw'  => $promo_html,
            ]
        );

        $element->end_controls_section();
    }

}

