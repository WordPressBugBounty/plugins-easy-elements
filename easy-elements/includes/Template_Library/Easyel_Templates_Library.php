<?php
namespace Easyel\EasyElements\Template_Library;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Easyel_Templates_Library {

	private $assets_url;
	private $rest_url;

	const SITE_URL  = 'https://wpeasyelements.com/demotemplate/';

	protected static $instance = null;

	public function __construct() {

		$this->assets_url = EASYELEMENTS_DIR_URL . 'includes/Template_Library/assets/';

		$this->rest_url = self::SITE_URL . '/wp-json/rtTemplates/v1/templates';
		
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'easy_editor_scripts' ), 1 );

		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'easy_editor_styles' ) );

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

		$config = array(
			'activeTab'               => 'sections',
			'nonce'                   => wp_create_nonce( 'wp_rest' ),
			'buttonIcon'              => $this->assets_url . 'img/easy-template-logo.png',
			'logoUrl'                 => $this->assets_url . 'img/easy-template-logo-sm2.webp',
			'headerLogoUrl'           => $this->assets_url . 'img/easy-template-logo.png',
			'bannerAdUrl'             => $this->assets_url . 'img/easy-templates-banner-ad2.webp',
			'apiUrl'                  => $this->rest_url,
			'thumbnailPlaceholderUrl' => $this->assets_url . 'img/about-img.jpg',
			'canInsertPro'            => false,
			'proCta'                  => array(
				'title'   => __( 'Premium template', 'easy-elements' ),
				'btnText' => __( 'Get Pro', 'easy-elements' ),
				'btnUrl'  => 'https://wpeasyelements.com/pricing/',
			),
		);

		$config = (array) apply_filters( 'easyel_template_library_config', $config );

		wp_add_inline_script(
			'easy-elements-template-library-script',
			'var rtElementsTemplatesManager = ' . wp_json_encode( $config ) . ';',
			'before'
		);
    }

	public function easy_editor_styles() {
		wp_enqueue_style( 'easy-elements-template-library-style', $this->assets_url . 'css/easy-elements-template-library.min.css', array(), EASYELEMENTS_VER );
	}

	public function easy_preview_styles() {
		wp_enqueue_style( 'easy-elements-template-library-preview-style', $this->assets_url . 'css/preview.css', array(), EASYELEMENTS_VER );
	}

}