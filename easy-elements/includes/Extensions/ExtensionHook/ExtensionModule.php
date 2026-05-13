<?php 
namespace Easyel\EasyElements\Extensions\ExtensionHook;
use Easyel\EasyElements\Extensions\ExtensionHook\HookControl;

if ( ! defined( 'ABSPATH' ) ) exit;

class ExtensionModule {

	private static $instance = null;

	private $prefix = 'easy_';

	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action(
			'elementor/element/container/section_layout/after_section_end', 
			array( $this, 'register_extension_sections' ),
			10,
			2
		);

	}

	/**
	 * Register multiple extension sections in a DRY way
	 */
	public function register_extension_sections( $element, $args ) {

		$sections = [
			'scroll_trigger' => [
				'label'       => __('ScrollTrigger', 'easy-elements'),
				'promo_key'   => 'scroll_trigger_html_promo',
			],
			'cursor_move' => [
				'label'       => __('Cursor Move Effect', 'easy-elements'),
				'promo_key'   => 'scroll_cursor_move_html_promo',
			],
			'sticky_elements' => [
				'label'       => __('Sticky Elements', 'easy-elements'),
				'promo_key'   => 'sticky_elements_html_promo',
				
			],
			'background_parallax' => [
				'label'       => __('Background Parallax', 'easy-elements'),
				'promo_key'   => 'background_parallax_html_promo',
			],
			'cursor_hover' => [
				'label'       => __('Cursor Hover Effect', 'easy-elements'),
				'promo_key'   => 'cursor_move_hover_html_promo',
			],
		];

		foreach( $sections as $id => $section ) {

			if ( in_array( $id, [ 'cursor_hover', 'cursor_move','scroll_trigger','background_parallax','sticky_elements' ], true ) && class_exists( 'Easy_Elements_Pro' ) ) {
				continue;
			}

			$element->start_controls_section(
				"id_{$id}_section",
				[
					'label' => EASY_EXTENSION_BADGE . $section['label'],
					'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
				]
			);

			if ( ! class_exists( 'Easy_Elements_Pro' ) ) {
				HookControl::register_controls( $element, $section['promo_key'] );
			} 

			$element->end_controls_section();
		}

	}
}