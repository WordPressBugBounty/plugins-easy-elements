<?php
/**
 * EE_HFE_Default_Compat setup
 */

namespace EASY_EHF\Themes;
use Easyel\EasyElements\Header_Footer_Builder\Classes\Easy_Header_Footer_Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * theme compatibility.
 */
class EE_HFE_Default_Compat {

	/**
	 *  Initiator
	 */
	public function __construct() {
		add_action( 'wp', [ $this, 'init_wp_hooks' ] );
	}

	/**
	 * Run all the Actions / Filters.
	 */
	public function init_wp_hooks() {
		if ( easyel_easy_header_enabled() ) {
			// Replace header.php template.
			add_action( 'get_header', [ $this, 'easy_override_header' ] );

			// Display HFE's header in the replaced header.
			add_action( 'easy_header', 'easyel_hfe_render_header' );
		}

		if ( easyel_easy_header_enabled() && easyel_is_before_header_enabled() ) {
			add_action( 'easy_header_before', [ Easy_Header_Footer_Elementor::class, 'get_before_header_content' ], 20 );
		}

		if ( easyel_easy_footer_enabled() || easyel_hfe_is_before_footer_enabled() ) {
			// Replace footer.php template.
			add_action( 'get_footer', [ $this, 'easy_override_footer' ] );
		}

		if ( easyel_easy_footer_enabled() ) {
			// Display HFE's footer in the replaced header.
			add_action( 'easy_footer', 'easyel_hfe_render_footer' );
		}

		if ( easyel_hfe_is_before_footer_enabled() ) {
			add_action( 'easy_footer_before', [ Easy_Header_Footer_Elementor::class, 'get_before_footer_content' ] );
		}
	}

	/**
	 * Function for overriding the header in the elmentor way.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public function easy_override_header() {
		require EASYELEMENTS_DIR_PATH . 'includes/Header_Footer_Builder/compat/theme/easy-header.php';
		$templates   = [];
		$templates[] = 'header.php';
		// Avoid running wp_head hooks again.
		remove_all_actions( 'wp_head' );
		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}

	/**
	 * Function for overriding the footer in the elmentor way.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public function easy_override_footer() {
		require EASYELEMENTS_DIR_PATH . 'includes/Header_Footer_Builder/compat/theme/easy-footer.php';
		$templates   = [];
		$templates[] = 'footer.php';
		// Avoid running wp_footer hooks again.
		remove_all_actions( 'wp_footer' );
		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}

}

new EE_HFE_Default_Compat();
