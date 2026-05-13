<?php
namespace Easyel\EasyElements\Extensions\VisibilityControl;
use Easyel\EasyElements\Extensions\ExtensionHook\HookControl;
if ( ! defined( 'ABSPATH' ) ) exit;


class VisibilityModule {

	private static $instance = null;

	private $prefix = 'easy_';

	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {

		add_action('elementor/element/common/_section_style/after_section_end', [ $this, 'easy_register_section'] );
		add_action('elementor/element/section/section_advanced/after_section_end', [ $this, 'easy_register_section'] );
		add_action('elementor/element/common/easy_visibility_section/before_section_end', [ $this, 'easy_register_controls'], 10, 2 );
		add_action('elementor/element/section/easy_visibility_section/before_section_end', [ $this, 'easy_register_controls'], 10, 2 );

		add_action('elementor/element/container/section_layout/after_section_end', [ $this, 'easy_register_section'] );

		add_action('elementor/element/container/easy_visibility_section/before_section_end', [ $this, 'easy_register_controls'], 10, 2 );
	
	}

	public function easy_register_section( $element ) {
		if ( function_exists( 'easy_element_is_enabled' ) &&  easy_element_is_enabled( 'enable_visibility_control' ) || ! class_exists( 'Easy_Elements_Pro' ) ) { 
			$element->start_controls_section(
				'easy_visibility_section',
				[
					'label' => EASY_EXTENSION_BADGE . __( 'Visibility Control', 'easy-elements' ),
					'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
				]
			);

			$element->end_controls_section();
		}
	}

	/**
	 * Register visibility controls in Elementor panel
	 */
	public function easy_register_controls( $element, $args ) {

		if ( ! class_exists( 'Easy_Elements_Pro' ) ) {
			
			HookControl::register_controls( $element ,"easyel_visibility_control_sec");
		} else {
			if ( did_action( 'plugins_loaded' ) &&  function_exists( 'easy_element_is_enabled' ) &&  easy_element_is_enabled( 'enable_visibility_control' ) ) {
			$pro_version = easyel_get_pro_clean_version();
			if (
				$pro_version &&
				version_compare( $pro_version, '1.0.8', '>=' )
			) {
					if( class_exists( '\EasyElements_Elementor\Pro\Extension\VisibilityControl\VisibilityControlPro' ) ) {
						\EasyElements_Elementor\Pro\Extension\VisibilityControl\VisibilityControlPro::get_instance()->easy_add_visibility_section_control( $element, $args );
					}
					
        		} else {
					\Easy_Visibility_Module_Pro::easy_add_visibility_section_control( $element, $args );
				}
				
			}
		}
	}
}