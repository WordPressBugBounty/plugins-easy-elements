<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Easyel_Elements_Elementor_Extension {

	const VERSION = EASYELEMENTS_VER;

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {

		// Load AJAX handlers
		require_once EASYELEMENTS_DIR_PATH . 'widgets/login-register/class.login-register.php';

		add_action( 'init', [ $this, 'init' ] );
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_categories' ] );

		add_action( 'admin_notices', [ $this, 'easyel_admin_promo_notice' ] , 0 );
		add_action( 'admin_init', [ $this, 'easyel_admin_promo_notice_dismiss_handler' ] );

		 add_filter( 'plugin_row_meta', [ $this, 'easyel_plugin_row_meta' ], 10, 2 ); 

		add_filter('plugin_action_links_'.EASYELEMENTS_PATH,[ $this,'easyel_setting_page_link' ] );

		add_action( 'plugins_loaded', [ $this, "easyel_plugin_loaded" ] );

		register_activation_hook( EASYELEMENTS_PATH, [ __CLASS__, 'easy_elements_activate' ] );

		add_action( 'admin_init', [ __CLASS__, 'easyel_maybe_upgrade' ] );

		add_action('elementor/controls/register', function( $controls_manager ) {
			$controls_manager->register(
				new \Easyel\EasyElements\Utils\SearchSelect2()
			);
		});

		\Easyel\EasyElements\Extensions\WrapperLink\EasyelWrapperLink::get_instance();

	}

	public static function easyel_maybe_upgrade() {

		$stored_version = get_option( 'easyel_plugin_version', '0' );

		if ( version_compare( $stored_version, '1.3.1', '<' ) ) {

			self::easyel_upgrade_130();

			update_option( 'easyel_plugin_version', '1.3.1' );
		}
	}

	private static function easyel_upgrade_130() {

		$option_key_extension = 'easy_element_extensions';
		$settings = get_option( $option_key_extension, [] );

		$new_defaults = [
			'enable_wrapper_link'     => 1,
			'enable_post_duplicator'  => 1,
			'enable_dynamic_content'  => 1,
			'enable_megamenu_builder' => 1,
		];

		foreach ( $new_defaults as $key => $default ) {
			if ( ! isset( $settings[ $key ] ) ) {
				$settings[ $key ] = $default;
			}
		}

		update_option( $option_key_extension, $settings );
	}

	public static function easy_elements_activate() {

		if ( class_exists( '\Easyel\EasyElements\Admin\Admin_Settings' ) ) {

			$option_key_extension = 'easy_element_extensions';
			$settings = get_option( $option_key_extension, [] );

			$include_extension = array(
				"enable_wrapper_link",
				"enable_post_duplicator",
				"enable_dynamic_content",
				"enable_megamenu_builder",
			);

			$fields2 = [];

			if ( function_exists('easyel_get_extension_fields') ) {
				$fields2 = easyel_get_extension_fields();
			}

			foreach ( $fields2 as $key => $data ) {

				if ( array_key_exists( $key, $settings ) ) {
					continue;
				}

				$settings[$key] = in_array( $key, $include_extension, true ) ? 1 : 0;
			}

			update_option( $option_key_extension, $settings );

			$include_widget = array(
				"easy_table",
				"bmi_calculator",
				"single_navigation_menu",
			);

			$available_elements = \Easyel\EasyElements\Admin\Admin_Settings::get_instance()->easyel_elements_get_available_widgets();
			$tab = 'widget';

			foreach ( $available_elements as $key => $widget ) {

				if ( isset( $widget['tab'] ) && $widget['tab'] === $tab ) {

					$option_name = 'easy_element_' . $tab . '_' . $key;

					$existing_value = get_option( $option_name, null );

					if ( $existing_value !== null ) {
						continue; 
					}

					if ( in_array( $key, $include_widget, true ) ) {
						add_option( $option_name, '0' );
					}
				}
			}
		}

		flush_rewrite_rules();
	}

	public function easyel_admin_promo_notice() {

		// Already dismissed
		if ( get_option( 'easyel_admin_promo_notice_dismissed2' ) ) {
			return;
		}

		$dismiss_url = wp_nonce_url(
			add_query_arg(
				[ 'easyel_promo_notice_dismiss' => '1' ],
				admin_url()
			),
			'easyel_promo_notice_dismiss_nonce'
		);
		?>

		<div class="notice notice-info easyel-promo-notice">
			<div class="easyel-promo-content">
				<div class="easyel-promo-left">
					<span class="orange">Exclusive Deals Available</span> • <span></span> Discount Up To 
					<span style="color:#ff7dcb;">60%</span> OFF
					<a class="easyel-promo-btn" href="https://wpeasyelements.com/pricing/" target="_blank">
						Grab the Deal →
					</a>

				</div>
				<div class="easyel-promo-right">
					<a href="<?php echo esc_url( $dismiss_url ); ?>" style="text-decoration:none;">
						✕ Dismiss
					</a>
				</div>
			</div>
		</div>

		<?php
	}

	function easyel_plugin_row_meta( $meta, $plugin_file ) {
        if ( basename( EASYELEMENTS_PATH) !== basename( $plugin_file ) ) {
			return $meta;
		}
        
        $meta[] = '<a href="https://wpeasyelements.com/docs/" target="_blank">' . esc_html__('Documentation', 'easy-elements' ) . '</a>';
        $meta[] = '<a href="https://themewant.com/support/" target="_blank">' . esc_html__('Support', 'easy-elements') . '</a>';
        if ( !file_exists( WP_PLUGIN_DIR . '/' . 'easy-elements-pro/easy-elements-pro.php' ) ) {
            $meta[] = '<a href="https://wpeasyelements.com/" style="color:#ff7a00; font-weight: bold;" target="_blank">' . esc_html__('Upgrade to Pro', 'easy-elements') . '</a>';
        }
		$meta[] = '<a href="https://wordpress.org/support/plugin/easy-elements/reviews/#new-post" target="_blank">' . esc_html__(' Rate the plugin ★★★★★', 'easy-elements' ) . '</a>';
        return $meta;
    }

	public function easyel_admin_promo_notice_dismiss_handler() {

		if ( ! is_admin() ) {
			return;
		}

		if ( empty( $_GET['easyel_promo_notice_dismiss'] ) ) {
			return;
		}

		$nonce = isset( $_GET['_wpnonce'] )
			? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) )
			: '';

		if ( ! wp_verify_nonce( $nonce, 'easyel_promo_notice_dismiss_nonce' ) ) {
			return;
		}

		update_option( 'easyel_admin_promo_notice_dismissed2', 1 );

		wp_safe_redirect(
			remove_query_arg( [ 'easyel_promo_notice_dismiss', '_wpnonce' ] )
		);
		exit;
	}

	public function init() {
		// Safety checks

		add_filter( 'body_class', [ $this, "easy_add_extra_body_class" ] );
		add_action( 'easy_header', [ $this, 'easyel_before_content_container_hfe'], 20 );
		add_action( 'easy_footer', [ $this, 'easyel_after_content_container_hfe' ], 5 );

		add_action( 'admin_notices', [ $this, 'easyelements_admin_notice_missing_elementor' ] );
	}

	public function easyel_plugin_loaded () {

		\Easyel\EasyElements\Utils\Enqueue::get_instance();
		\Easyel\EasyElements\Utils\QueryHelper::get_instance();

		if( is_admin() ) {
			\Easyel\EasyElements\Admin\Admin_Settings::get_instance();	
		}

		if ( did_action( 'elementor/loaded' ) ) {
			\Easyel\EasyElements\Template_Library\Easyel_Templates_Library::get_instance();
			\Easyel\EasyElements\Header_Footer_Builder\Classes\Easy_Header_Footer_Elementor::get_instance();
		}

		\Easyel\EasyElements\Starter_Template\Easyel_Starter_Template::get_instance();

		\Easyel\EasyElements\Theme_Builder\Easyel_Theme_Builder_CPT::get_instance();

		\Easyel\EasyElements\Theme_Builder\Easyel_Theme_Builder_Front::get_instance();

		\Easyel\EasyElements\PostType\OffcanvasPostType::get_instance();
		\Easyel\EasyElements\CustomCode\Easyel_Custom_Code::get_instance();
		\Easyel\EasyElements\Popup_Builder\Easyel_Popup_Builder::get_instance();
		\Easyel\EasyElements\Extensions\ExtensionHook\ExtensionModule::get_instance();
		\Easyel\EasyElements\Extensions\CustomCss\EasyelCustomCss::get_instance()->init();
		\Easyel\EasyElements\Extensions\Duplicator\EasyelDuplicator::get_instance();
		\Easyel\EasyElements\Extensions\SettingExport\EasyelExport::get_instance();
		\Easyel\EasyElements\Extensions\LiveCopy\CopyPaste::get_instance();
		\Easyel\EasyElements\Extensions\VisibilityControl\VisibilityModule::get_instance();
		\Easyel\EasyElements\Extensions\Jarallax\JaralaxControl::get_instance()->init();
		\Easyel\EasyElements\Extensions\Jarallax\GlobalJarallax::get_instance();
		\Easyel\EasyElements\Extensions\ProgressBar\ReadingProgressBar::get_instance();
		\Easyel\EasyElements\Extensions\ScrollTop\ScrollTop::get_instance();

		if ( function_exists( 'easyel_element_is_enabled' ) && easyel_element_is_enabled( 'enable_preloader' ) ) {
			require_once EASYELEMENTS_DIR_PATH . 'includes/Extensions/Preloader/preloader.php';
			\Easyel\EasyElements\Extensions\Preloader\EasyelPreloader::get_instance();
		}


		if ( ! class_exists( '\Easyel\Vendor\Appsero\Client' ) ) {
			return;
		}

		\Easyel\EasyElements\Tracking\Appsero_Tracker::instance();
	}

	public function easyelements_admin_notice_missing_elementor() {

		$elementor = 'elementor/elementor.php';

		if ( current_user_can( 'activate_plugins' ) && isset( $_GET['activate'], $_GET['_wpnonce'] ) ) {

			$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

			if ( wp_verify_nonce( $nonce, 'activate-plugin_' . $elementor ) ) {
				unset( $_GET['activate'] );
			}
		}

		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$elementor = 'elementor/elementor.php';
		$elementor_path = WP_PLUGIN_DIR . '/' . $elementor;

		$is_installed = file_exists( $elementor_path );
		$is_active    = function_exists( 'is_plugin_active' ) && is_plugin_active( $elementor );

		if ( $is_active ) {
			return;
		}

		if ( $is_installed ) {
			$action_url = wp_nonce_url(
				self_admin_url( 'plugins.php?action=activate&plugin=' . $elementor ),
				'activate-plugin_' . $elementor
			);
			$button_label = esc_html__( 'Activate Elementor', 'easy-elements' );
		} else {
			$action_url = wp_nonce_url(
				self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ),
				'install-plugin_elementor'
			);
			$button_label = esc_html__( 'Install Elementor', 'easy-elements' );
		}

		wp_enqueue_style(
			'easyel-missing-elementor-notice',
			EASYELEMENTS_DIR_URL . 'assets/css/admin/missing-elementor-notice.css',
			[],
			self::VERSION
		);
		?>
		<div class="easyel-missing-elementor-notice" role="alert">
			<span class="easyel-missing-elementor-notice__icon" aria-hidden="true">!</span>
			<div class="easyel-missing-elementor-notice__text">
				<?php
				printf(
					/* translators: 1: Plugin name (Easy Elements), 2: Required plugin name (Elementor) */
					esc_html__( '%1$s requires %2$s plugin to be installed and activated.', 'easy-elements' ),
					'<strong>' . esc_html__( 'Easy Elements', 'easy-elements' ) . '</strong>',
					'<strong>' . esc_html__( 'Elementor', 'easy-elements' ) . '</strong>'
				);
				?>
			</div>
			<a href="<?php echo esc_url( $action_url ); ?>" class="easyel-missing-elementor-notice__button">
				<span class="dashicons dashicons-admin-network" aria-hidden="true"></span>
				<?php echo esc_html( $button_label ); ?>
			</a>
		</div>
		<?php
	}

	// Container Full site
	public function easyel_before_content_container_hfe() {
		if ( is_admin() ) return;
		if ( function_exists( 'elementor_theme_do_location' ) && \Elementor\Plugin::$instance->preview->is_preview() ) {
			return;
		}
		echo '<div class="easyel-content-container">';
	}

	/**
	 * Close container before HFE Footer
	 */
	function easyel_after_content_container_hfe() {
		if ( is_admin() ) return;
		if ( function_exists( 'elementor_theme_do_location' ) && \Elementor\Plugin::$instance->preview->is_preview() ) {
			return;
		}
		echo '</div>';
	}

	public function easy_add_extra_body_class( $classes ) {
		if ( is_plugin_active( 'easy-elements/easy-elements.php' ) ) {
			$classes[] = 'eel-easy-elements';
		}

		return $classes;
	}

	public function add_elementor_categories( $elements_manager ) {

		$new_categories = [
			'easyelements_category' => [
				'title' => __( 'Easy Elements', 'easy-elements' ),
				'icon'  => 'fa fa-plug',
			],
			'easyelements_post_category' => [
				'title' => __( 'Easy Post Type Elements', 'easy-elements' ),
				'icon'  => 'fa fa-file-alt',
			],
			'easyelements_header_footer_category' => [
				'title' => __( 'Easy Header Footer Elements', 'easy-elements' ),
				'icon'  => 'fa fa-header',
			],
			'easyelements_category_form' => [
				'title' => __( 'Easy Elements Form', 'easy-elements' ),
				'icon'  => 'fa fa-plug',
			],
			'easyelements_category_pro' => [
				'title' => __( 'Easy Elements Pro', 'easy-elements' ),
				'icon'  => 'fa fa-plug',
			],
		];

		$existing = $elements_manager->get_categories();
		$final_list = [];

		foreach ( $existing as $key => $cat ) {
			$final_list[ $key ] = $cat;

			if ( $key === 'layout' ) {
				foreach ( $new_categories as $new_key => $new_cat ) {
					$final_list[ $new_key ] = $new_cat;
				}
			}
		}

		$apply_categories = function( $cats ) {
			$this->categories = $cats;
		};

		$apply_categories->call( $elements_manager, $final_list );
	}

	public function init_widgets() {
		$widgets_manager = \Elementor\Plugin::instance()->widgets_manager;

		$widgets = [
			'site_logo' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Site_Logo_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/site-logo/site-logo.php',
				'tab'   => 'widget'
			],
			'heading' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Heading_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/heading/heading.php',
				'tab'   => 'widget'
			],
			'clients_logo' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Clients_Logo__Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/clients-logo-grid/logo.php',
				'tab'   => 'widget'
			],
			'icon_box' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Icon_Box__Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/icon-box/icon.php',
				'tab'   => 'widget'
			],
			'simple_tab' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Tab_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/tab/tab.php',
				'tab'   => 'widget'
			],
			'testimonials' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Testimonials__Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/testimonials-grid/testimonials.php',
				'tab'   => 'widget'
			],
			'team_grid' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Team_Grid__Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/team-grid/team-grid.php',
				'tab'   => 'widget'
			],
			'search' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Search_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/search/search.php',
				'tab'   => 'widget'
			],
			'contact_box' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Contact_Box__Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/contact-box/contact.php',
				'tab'   => 'widget'
			],
			'faq' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_FAQ_Accordion_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/faq/faq.php',
				'tab'   => 'widget'
			],
			'blog_grid' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Blog_Grid__Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/blog-grid/blog-grid.php',
				'tab'   => 'widget'
			],
			'video' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Video_Popup_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/video/video.php',
				'tab'   => 'widget'
			],
			'pricing_table' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Pricing_Table_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/pricing-table/pricing.php',
				'tab'   => 'widget'
			],
			'service_list' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Service_List_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/service-list/service-list.php',
				'tab'   => 'widget'
			],
			'navigation_menu' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Navigation_Menu_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/navigation-menu/navigation-menu.php',
				'tab'   => 'widget'
			],
			'page_title' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Page_Title_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/page-title/page-title.php',
				'tab'   => 'widget'
			],
			'button' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Button_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/button/button.php',
				'tab'   => 'widget'
			],
			'copyright' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Copyright_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/copyright/copyright.php',
				'tab'   => 'widget'
			],
			'process_grid' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Process_Grid_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/process-grid/process-grid.php',
				'tab'   => 'widget'
			],
			'process_list' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Process_lists_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/process-list/process-list.php',
				'tab'   => 'widget'
			],
			'social_share' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Social_Share_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/social-share/social-share.php',
				'tab'   => 'widget'
			],
			'social_icon' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Social_Icon_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/social-icon/social.php',
				'tab'   => 'widget'
			],
			'breadcrumb' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Breadcrumb_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/breadcrumb/breadcrumb.php',
				'tab'   => 'widget'
			],
			'domain_search' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Domain_Search_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/domain-search/domain-search.php',
				'tab'   => 'widget'
			],
			'image_comparison' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Before_After_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/image-comparison/img-before-after.php',
				'tab'   => 'widget'
			],
			'easy_offcanvas' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Offcanvas_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/offcanvas/offcanvas.php',
				'tab'   => 'widget'
			],
			'easy_table' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Table_Elementor_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/table/table-normal.php',
				'tab'   => 'widget'
			],
			'easy_scroll_to_top' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Scroll_To_Top_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/scroll-to-top/scroll.php',
				'tab'   => 'widget'
			],
			'easy_cf7' => [
				'class' => '\Easyel\EasyElements\Widgets\easyel__CF7_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/cf7/contact-cf7.php',
				'tab'   => 'widget'
			],
			'easy_gallery' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel__Gallery_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/gallery/gallery.php',
				'tab'   => 'widget'
			],
			'easy_progress' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Progress_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/progress/progress.php',
				'tab'   => 'widget'
			],			
			'counter' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Counter_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/counter/counter.php',
				'tab'   => 'widget'
			],
			'countdown' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Count_Down_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/countdown/countdown.php',
				'tab'   => 'widget'
			],
			'excerpt' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Free_Excerpt_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/excerpt/excerpt.php',
				'tab'   => 'widget'
			],
			'post_title' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Free_Post_Title_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/post-title/post-title.php',
				'tab'   => 'widget'
			],
			'post_content' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Free_Post_content_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/post-content/post-content.php',
				'tab'   => 'widget'
			],
			'featured_image' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_free_Featured_Image_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/featured-image/featured-image.php',
				'tab'   => 'widget'
			],		
			'post_meta' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Free_Post_Meta_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/post-meta/post-meta.php',
				'tab'   => 'widget'
			],
			'post_pagination' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Free_Post_Pagination_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/post-pagination/post-pagination.php',
				'tab'   => 'widget'
			],
			'post_comments' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Free_Post_Comments_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/post-comments/post-comments.php',
				'tab'   => 'widget'
			],
			'post_tags' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Free_Post_Tags_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/post-tag/post-tag.php',
				'tab'   => 'widget'
			],
			'post_author' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Free_Post_Author_Info_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/post-author/post-author.php',
				'tab'   => 'widget'
			],
			'archive_post' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Free_Archive_Post__Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/archive-post/archive-post.php',
				'tab'   => 'widget',
			],
			'login_register' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Login_Register_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/login-register/login-register.php',
				'tab'   => 'widget',
			],
			'feature_list' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Feature_List__Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/feature-list/feature-list.php',
				'tab'   => 'widget',
			],
			'single_navigation_menu' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Single_Nav_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/single-nav/single-nav.php',
				'tab'   => 'widget',
			],
			'vertical_navigation_menu' => [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_vertical_Menu_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/vertical-menu/vertical-menu-widget.php',
				'tab'   => 'widget',
			]
		];

		// WooCommerce-dependent widgets — only register when WooCommerce is active.
		if ( class_exists( 'WooCommerce' ) ) {
			$widgets['product_grid_lite'] = [
				'class' => '\Easyel\EasyElements\Widgets\Easyel_Product_Grid_Lite_Widget',
				'file'  => EASYELEMENTS_DIR_PATH . '/widgets/product-grid-lite/product-grid-lite.php',
				'tab'   => 'widget',
			];

			// Skip if an older version of Easy Elements Pro still bundles the Mini Cart widget.
			$legacy_pro_mini_cart = defined( 'EASYELEMENTS_PRO_PATH' )
				&& file_exists( rtrim( EASYELEMENTS_PRO_PATH, '/\\' ) . '/widgets/mini-cart/mini-cart.php' );

			if ( ! $legacy_pro_mini_cart ) {
				$widgets['mini_cart'] = [
					'class' => '\Easyel\EasyElements\Widgets\Easyel_Free_Mini_Cart_Widget',
					'file'  => EASYELEMENTS_DIR_PATH . '/widgets/mini-cart/mini-cart.php',
					'tab'   => 'widget',
				];
			}
		}

		foreach ( $widgets as $key => $data ) {
			$option_name = 'easy_element_' . $data['tab'] . '_' . $key;
			$enabled = get_option( $option_name, '1' );

			if ( 1 !== ( int ) $enabled ) {
				continue; // Skip disabled widgets
			}

			if ( file_exists( $data['file'] ) ) {
				require_once $data['file'];

				if ( class_exists( $data['class'] ) ) {
					$widgets_manager->register( new $data['class']() );
				}
			}
		}
	}

	public function easyel_setting_page_link( $links ) {
		$action_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'admin.php?page=easy-elements-dashboard' ) ),
			esc_html__( 'Settings', 'easy-elements' )
		);
		array_push( $links, $action_link );
		return $links;
	}

}

Easyel_Elements_Elementor_Extension::instance();