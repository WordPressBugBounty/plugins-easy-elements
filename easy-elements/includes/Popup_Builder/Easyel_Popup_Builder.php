<?php
namespace Easyel\EasyElements\Popup_Builder;
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Easyel_Popup_Builder {

	private static $instance = null;

	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {

		if( is_admin() && ! defined( 'EASY_ELEMENTS_PRO_ACTIVE' ) ) {
			add_action( 'admin_menu', [ $this, 'easyel_popup_builder_menu' ], 20 );
			add_action( 'admin_enqueue_scripts', [ $this, 'easyel_popup_builder_assets' ] );
		}
	}

	public function easyel_popup_builder_assets( $hook ) {
		if ( strpos( $hook, 'easy-popup-builder' ) === false ) {
			return;
		}

		wp_enqueue_style(
			'easyel-popup-builder-style',
			EASYELEMENTS_DIR_URL . 'includes/Popup_Builder/css/popup-builder.css',
			[],
			EASYELEMENTS_VER
		);
	}

	public function easyel_popup_builder_menu() {
		add_submenu_page(
			'easy-elements-dashboard',
			__( 'Popup Builder', 'easy-elements' ),
			__( 'Popup Builder', 'easy-elements' ),
			'manage_options',
			'easy-popup-builder',
			[ $this, 'easyel_popup_builder_page' ]
		);
	}

	public function easyel_popup_builder_page() {
		?>
		<div class="easyel-custom-code-page">
			<div class="easyel-banner">
				<div class="easyel-banner-content">
					<h1><?php esc_html_e( 'Popup builder', 'easy-elements' ); ?></h1>
					<p><?php esc_html_e( 'Popup builder where it appears on your site!', 'easy-elements' ); ?></p>
					<a href="https://wpeasyelements.com/pricing/" target="_blank" class="easyel-upgrade-btn">
						<?php esc_html_e( 'Upgrade to Pro', 'easy-elements' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}
}