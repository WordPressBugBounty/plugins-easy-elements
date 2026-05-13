<?php
namespace Easyel\EasyElements\Template_Library;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Easyel_Templates_Library {

	private $assets_url;
	private $rest_url;

	const SITE_URL  = 'https://wpeasyelements.com/demotemplate/';

	protected static $instance = null;

	public function __construct() {

		// get current module's url.
		$this->assets_url = EASYELEMENTS_DIR_URL . 'includes/Template_Library/assets/';

        // get current module's url.
		$this->rest_url = self::SITE_URL . '/wp-json/rtTemplates/v1/templates';
		
		// print variables on footer.
		add_action( 'elementor/editor/footer', array( $this, 'easy_editor_footer_script' ) );

		// enqueue editor js for elementor.
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'easy_editor_scripts' ), 1 );

		// enqueue editor css.
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'easy_editor_styles' ) );

		// enqueue modal's preview css.
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'easy_preview_styles' ) );

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

	public function easy_editor_scripts() {
		wp_enqueue_script( 'easy-elements-template-library-script', $this->assets_url . 'js/easy-template-library.js', array( 'jquery', 'wp-element' ), EASYELEMENTS_VER, true );

		wp_localize_script('easy-elements-template-library-script', 'rtElementsTemplatesajax', [
			'previewBaseUrl' => "https://wpeasyelements.com/demotemplate/", 
			'ajaxUrl' => admin_url('admin-ajax.php'),
		]);

		wp_enqueue_script( 'easy-elements-template-library-isotope-script', $this->assets_url . 'js/isotope.pkgd.min.js', array( 'jquery', 'wp-element' ), EASYELEMENTS_VER, true );
    }

	public function easy_editor_styles() {
		wp_enqueue_style( 'easy-elements-template-library-style', $this->assets_url . 'css/easy-elements-template-library.min.css', array(), EASYELEMENTS_VER );
	}

	public function easy_preview_styles() {
		wp_enqueue_style( 'easy-elements-template-library-preview-style', $this->assets_url . 'css/preview.css', array(), EASYELEMENTS_VER );
	}

	public function easy_editor_footer_script() { 

		$license_status = 'invalid';

		$pro_version = easyel_get_pro_clean_version();

		if (
			$pro_version &&
			version_compare( $pro_version, '1.0.8', '>=' )
		) {

			if (
				did_action( 'plugins_loaded' ) &&
				class_exists( '\EasyElements_Elementor\Pro\Licenses\EasyelLicense' ) 
			) {
				$manager = \EasyElements_Elementor\Pro\Licenses\EasyelLicense::get_instance();

				if ( method_exists( $manager, 'check_license_validity' ) ) {
					$license_status = $manager->check_license_validity();
				}
			}
                            
        } else {
			if ( did_action( 'plugins_loaded' ) && class_exists( '\Easyel_License_Manager' ) ) {
				$license_manager = new \Easyel_License_Manager();

				if ( $license_manager && method_exists( $license_manager, 'check_license_validity' ) ) {
					$license_status = $license_manager->check_license_validity();
				}
			} 
		}

		$is_pro = class_exists('Easy_Elements_Pro');
		$license_page_url = admin_url('admin.php?page=easy-elements-dashboard#activate_license');
		
		?>
		<script type="text/javascript">

            var rtElementsTemplatesManager = {
                "activeTab": "sections",
                "nonce": "<?php echo esc_attr(wp_create_nonce( 'wp_rest' )); ?>",
                "buttonIcon": "<?php echo  esc_url( $this->assets_url . 'img/easy-template-logo.png' ); ?>",
                "logoUrl": "<?php echo  esc_url( $this->assets_url . 'img/easy-template-logo-sm2.webp' ); ?>",
                "headerLogoUrl": "<?php echo  esc_url( $this->assets_url . 'img/easy-template-logo.png' ); ?>",
                "bannerAdUrl": "<?php echo  esc_url( $this->assets_url . 'img/easy-templates-banner-ad2.webp' ); ?>",
                "apiUrl": "<?php echo esc_url( $this->rest_url); ?>",
                "thumbnailPlaceholderUrl": "<?php echo  esc_url( $this->assets_url . 'img/about-img.jpg' ); ?>",
                "templatesContainer": document.querySelector('#rtElementsTemplatesLibrary #elementor-template-library-templates-container'),
				"licenseStatus": "<?php echo esc_js($license_status); ?>",
				"proActive": "<?php echo $is_pro ? true : false; ?>",
				"licensePageUrl": "<?php echo esc_url($license_page_url); ?>" 
            };

		</script> 
		<?php
	}
}