<?php
namespace Easyel\EasyElements\CustomCode;
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Easyel_Custom_Code {

	private static $instance = null;

	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {

		if( is_admin() && ! defined( 'EASY_ELEMENTS_PRO_ACTIVE' ) ) {
			add_action( 'admin_menu', [ $this, 'easyel_custom_code_menu' ], 20 );
			add_action( 'admin_enqueue_scripts', [ $this, 'easy_custom_code_assets' ] );
		}
	}

	public function easy_custom_code_assets( $hook ) {
		if ( strpos( $hook, 'easy-custom-code' ) === false ) {
			return;
		}

		wp_enqueue_style(
			'easyel-custom-code-style',
			EASYELEMENTS_DIR_URL . 'includes/CustomCode/css/custom-code.css',
			[],
			EASYELEMENTS_VER
		);
	}

	public function easyel_custom_code_menu() {
		add_submenu_page(
			'easy-elements-dashboard',
			__( 'Custom Code', 'easy-elements' ),
			__( 'Custom Code', 'easy-elements' ),
			'manage_options',
			'easy-custom-code',
			[ $this, 'easyel_custom_code_page' ]
		);
	}

	public function easyel_custom_code_page() {
		?>
		<div class="easyel-custom-code-page">
			<div class="easyel-banner">
				<div class="easyel-banner-content">
					<h1><?php esc_html_e( 'Custom Code Manager', 'easy-elements' ); ?></h1>
					<p><?php esc_html_e( 'Add custom JS Code where it appears on your site!', 'easy-elements' ); ?></p>
					<a href="https://wpeasyelements.com/pricing/" target="_blank" class="easyel-upgrade-btn">
						<?php esc_html_e( 'Upgrade to Pro', 'easy-elements' ); ?>
					</a>
				</div>
			</div>

			<div class="easyel-features">
				<h2><?php esc_html_e( 'What You Can Do with Custom Code', 'easy-elements' ); ?></h2>
				<ul class="easyel-feature-table">
					<li><?php echo esc_html__("Add Custom JS Code","easy-elements"); ?></li>
					<li><?php echo esc_html__("Code Apply Header / Footer Position","easy-elements"); ?></li>
					<li><?php echo esc_html__("Apply Code Globally (All Pages)","easy-elements"); ?></li>
					<li><?php echo esc_html__("Apply Code Archive Page","easy-elements"); ?></li>
					<li><?php echo esc_html__("Apply Code Singular Page","easy-elements"); ?></li>
					<li><?php echo esc_html__("Apply Code WooCommerce Specific Page","easy-elements"); ?></li>
				</ul>
				
			</div>
		</div>
		<?php
	}
}