<?php
namespace Easyel\EasyElements\Extensions\LiveCopy;
use Easyel\EasyElements\Extensions\ExtensionHook\HookControl;
if ( ! defined( 'ABSPATH' ) ) exit;
class CopyPaste {

    /**
     * Singleton instance
     * @var CopyPaste|null
     */
    protected static $instance = null;

    /**
     * Constructor
     */
    private function __construct() {

        add_action( 'init', [ $this, 'easyel_init' ] );
    }

    /**
     * Get instance
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize actions
     */
    public function easyel_init() {

        add_action('elementor/element/common/_section_style/after_section_end', [ $this, 'easy_live_copy_register_section'] );
        add_action('elementor/element/section/section_advanced/after_section_end', [ $this, 'easy_live_copy_register_section'] );

        add_action('elementor/element/common/easy_live_copy_section/before_section_end', [ $this, 'easy_live_copy_register_controls'], 10, 2 );
        add_action('elementor/element/section/easy_live_copy_section/before_section_end', [ $this, 'easy_live_copy_register_controls'], 10, 2 );

        add_action('elementor/element/container/section_layout/after_section_end', [ $this, 'easy_live_copy_register_section'] );

        add_action('elementor/element/container/easy_live_copy_section/before_section_end', [ $this, 'easy_live_copy_register_controls'], 10, 2 );

    }

    public function easy_live_copy_register_section( $element ) {

        $enable_live_copy = get_option( 'easy_live_copy_btn_enable', true );

        if(  ( int ) $enable_live_copy !== 1 && easyel_premium_addon_active() ) {
            return;
        }

        if ( function_exists( 'easyel_element_is_enabled' ) &&  easyel_element_is_enabled( 'enable_live_copy_paste' ) || ! easyel_premium_addon_active() ) { 

            $element->start_controls_section(
                'easy_live_copy_section',
                [
                    'label' => EASYEL_EXTENSION_BADGE . __( 'Live Copy Paste', 'easy-elements' ),
                    'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
                ]
            );
    
            $element->end_controls_section();
        }
    }

    /**
     * Register visibility controls in Elementor panel
    */
    public function easy_live_copy_register_controls( $element, $args ) {
        if(  ! easyel_premium_addon_active() ) {

            HookControl::register_controls( $element, "live_copy_promo_key" );
        } else {

            $pro_version = easyel_get_pro_clean_version();

            if (
                $pro_version &&
                version_compare( $pro_version, '1.0.8', '>=' )
            ) {
                if ( did_action( 'plugins_loaded' ) && function_exists( 'easyel_element_is_enabled' ) &&  easyel_element_is_enabled( 'enable_live_copy_paste' ) && class_exists( '\EasyElements_Elementor\Pro\Extension\CopyPaste\LiveCopyPro' ) ) {
                    $instance = \EasyElements_Elementor\Pro\Extension\CopyPaste\LiveCopyPro::get_instance();
                    $instance->easy_live_copy_register_controls_pro( $element, $args );
                }
        	} else {
                if ( did_action( 'plugins_loaded' ) && function_exists( 'easyel_element_is_enabled' ) &&  easyel_element_is_enabled( 'enable_live_copy_paste' ) && class_exists( 'Easy_Live_Copy_Paste_Pro' ) ) {
                    \Easy_Live_Copy_Paste_Pro::easy_live_copy_register_controls_pro( $element, $args );
                }
            }
           
        }
    } 
}