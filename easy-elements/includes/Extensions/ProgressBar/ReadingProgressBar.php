<?php
namespace Easyel\EasyElements\Extensions\ProgressBar;
/**
 * Easy ReadingProgressBar
 *
 * @package EasyElements
 */

if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;

class ReadingProgressBar {

	/**
	 * Holds the singleton instance
	 *
	 * @var ReadingProgressBar|null
	 */
	private static $instance = null;

	/**
	 * Constructor
	 */
	private function __construct() {

        $tab_slug = 'extensions';
        $extensions_settings = get_option('easy_element_' . $tab_slug, [] );

        $enable_reading_progress_bar = isset( $extensions_settings['enable_reading_progress_bar'] ) ? $extensions_settings['enable_reading_progress_bar'] : 0;

        if(  (int) $enable_reading_progress_bar !== 1 ) {
            return;
        }

		add_action( 'wp_footer', [ $this, 'easyel_render_progressbar_html'] );
		add_action( 'wp_enqueue_scripts', [ $this, "easy_progressbar_scripts" ] );

	}

	/**
	 * Get instance
	 */
	public static function get_instance() : self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function easy_progressbar_scripts() {

		if ( ! $this->should_display_progressbar() ) {
			return;
		}

		wp_enqueue_style(
			'easy-progress-bar',
			EASYELEMENTS_DIR_URL . 'includes/Extensions/ProgressBar/assets/css/progress-bar.css',
			[],
			EASYELEMENTS_VER
		);

		$settings = get_option( 'easyel_reading_progressbar_settings', [] );
		$height   = !empty( $settings['reading_progressbar_height'] ) ? absint( $settings['reading_progressbar_height'] ) : 5;
		$color    = !empty( $settings['reading_progressbar_color'] ) ? sanitize_hex_color( $settings['reading_progressbar_color'] ) : '#7400c1';
		$position = isset( $settings['reading_progressbar_position'] ) ? $settings['reading_progressbar_position'] : 'top';
		$pos_css  = ( $position === 'bottom' ) ? 'bottom:0; top:auto;' : 'top:0; bottom:auto;';

		$custom_css = "
			.easyel-progress-wrapper { height: {$height}px; {$pos_css} }
			.easyel-reading-progress-bar { height: {$height}px; --easyel-progress-color: {$color}; }
		";

		wp_add_inline_style( 'easy-progress-bar', $custom_css );

		wp_enqueue_script(
			'easyel-reading-progress-script',
			EASYELEMENTS_DIR_URL . 'includes/Extensions/ProgressBar/assets/js/progress-bar.js',
			[ 'jquery' ],
			EASYELEMENTS_VER,
			true
		);
	}

	private function should_display_progressbar() {
		$settings = get_option( 'easyel_reading_progressbar_settings', [] );
		if ( empty( $settings ) ) {
			return false;
		}

		$display_on = isset( $settings['reading_progressbar_display'] ) ? (array) $settings['reading_progressbar_display'] : [];

		if ( empty( $display_on ) ) {
			return false;
		}

		$should_show = false;

		if ( in_array( 'global', $display_on, true ) ) {
			$should_show = true;
		}

		if ( ! $should_show && in_array( 'posts', $display_on, true ) && is_singular( 'post' ) ) {
			$should_show = true;
		}

		if ( ! $should_show && in_array( 'pages', $display_on, true ) && is_page() ) {
			$should_show = true;
		}

		if ( ! $should_show && in_array( 'specific_page', $display_on, true ) ) {
			$specific_pages = isset( $settings['reading_progressbar_specific_page'] ) ? (array) $settings['reading_progressbar_specific_page'] : [];
			if ( ! empty( $specific_pages ) && is_singular() && in_array( get_the_ID(), array_map( 'intval', $specific_pages ), true ) ) {
				$should_show = true;
			}
		}

		return apply_filters( 'easyel/reading_progressbar_should_show', $should_show, $settings, $display_on );
	}

	public function easyel_render_progressbar_html() {

		if ( $this->should_display_progressbar() ) {
			$this->render_progressbar_html();
		}
	}

	private function render_progressbar_html() {

		echo '<div class="easyel-progress-wrapper">
			<progress id="easyel-reading-progress-bar"
				class="easyel-reading-progress-bar"
				value="0"
				max="100"></progress>
		</div>';
	}

}
