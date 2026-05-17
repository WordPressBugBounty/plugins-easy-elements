<?php
namespace Easyel\EasyElements\Starter_Template;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Easyel_Starter_Template {

	private $required_plugins;
	private $recommeneded_theme;
	protected static $instance = null;

	const API_ENDPOINT   = 'https://wpeasyelements.com/demotemplate/wp-json/rtTemplates/v1/starter-templates';
	const TRANSIENT_KEY  = 'easyel_starter_templates_cache_v2';

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'templates_css' ) );
		add_action( 'in_admin_header', array( $this, 'suppress_admin_notices' ), 1 );
		add_filter( 'admin_body_class', array( $this, 'add_body_class' ) );
		add_action( 'admin_head', array( $this, 'fullscreen_head_style' ) );

		$this->required_plugins = $this->required_plugins();
		$this->recommeneded_theme = $this->recommeneded_theme();

        add_action( 'wp_ajax_easyel_install_plugin', array( $this, 'install_plugin' ) );
        add_action( 'wp_ajax_easyel_activate_plugin', array( $this, 'activate_plugin' ) );
        add_action( 'wp_ajax_easyel_install_theme', array( $this, 'install_theme' ) );
        add_action( 'wp_ajax_easyel_activate_theme', array( $this, 'activate_theme' ) );
        add_action( 'wp_ajax_easyel_import_content', array( $this, 'import_content' ) );
        add_action( 'wp_ajax_easyel_sync_library', array( $this, 'ajax_sync_library' ) );
    }

	public function suppress_admin_notices() {
		if ( ! $this->is_starter_screen() ) {
			return;
		}
		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );
		remove_all_actions( 'user_admin_notices' );
		remove_all_actions( 'network_admin_notices' );
	}

	public function add_body_class( $classes ) {
		if ( $this->is_starter_screen() ) {
			$classes .= ' easyel-starter-fullscreen';
		}
		return $classes;
	}

	public function fullscreen_head_style() {
		if ( ! $this->is_starter_screen() ) {
			return;
		}
		echo '<style>html.wp-toolbar{padding-top:0 !important;}</style>';
	}

	private function is_starter_screen() {
		$screen = get_current_screen();
		return $screen && 'easy-elements_page_starter-templates-dashboard' === $screen->id;
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

    // Admin Menu
	public function admin_menus() {
		add_submenu_page(
			'easy-elements-dashboard',
			__('Starter Templates', 'easy-elements'),
			__('Starter Templates', 'easy-elements'),
			'manage_options',
			'starter-templates-dashboard',
			array( $this, 'templates_page_html' )
		);
	}

	public function get_license_info() {
		$has_pro        = class_exists( 'Easy_Elements_Pro' );
		$license_status = 'invalid';

		if ( $has_pro ) {
			$pro_version = function_exists( 'easyel_get_pro_clean_version' ) ? easyel_get_pro_clean_version() : '';

			if ( $pro_version && version_compare( $pro_version, '1.0.8', '>=' ) ) {
				if ( did_action( 'plugins_loaded' ) && class_exists( '\EasyElements_Elementor\Pro\Licenses\EasyelLicense' ) ) {
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
		}

		return array(
			'has_pro'              => $has_pro,
			'license_status'       => $license_status,
			'pro_upgrade_url'      => 'https://wpeasyelements.com/pricing/',
			'license_activate_url' => admin_url( 'admin.php?page=easy-elements-dashboard#activate_license' ),
		);
	}

	public function templates_css() {
		$screen = get_current_screen();

		if($screen->id !== 'easy-elements_page_starter-templates-dashboard') {
			return;
		}

		$css_path = EASYELEMENTS_DIR_PATH . 'includes/Starter_Template/assets/css/starter-templates.css';
		$js_path  = EASYELEMENTS_DIR_PATH . 'includes/Starter_Template/assets/js/starter-templates.js';
		$css_ver  = file_exists( $css_path ) ? filemtime( $css_path ) : EASYELEMENTS_VER;
		$js_ver   = file_exists( $js_path ) ? filemtime( $js_path ) : EASYELEMENTS_VER;

		wp_enqueue_style(
			'easy-elements-starter-templates-css',
			EASYELEMENTS_DIR_URL . 'includes/Starter_Template/assets/css/starter-templates.css',
			[],
			$css_ver
		);

		wp_enqueue_script(
			'easy-elements-starter-templates-isotope-js',
			EASYELEMENTS_DIR_URL . 'includes/Starter_Template/assets/js/isotope.pkgd.min.js',
			[],
			EASYELEMENTS_VER,
			true
		);
		wp_localize_script('easy-elements-starter-templates-isotope-js', 'easyElementsStarterTemplatesajax', [
			'ajaxUrl' => admin_url('admin-ajax.php'),
		]);

		wp_enqueue_script(
			'easy-elements-starter-templates-js',
			EASYELEMENTS_DIR_URL . 'includes/Starter_Template/assets/js/starter-templates.js',
			[
				'easy-elements-starter-templates-isotope-js',
				'jquery',
				'wp-element',
				'imagesloaded',
			],
			$js_ver,
			true
		);
		$license_info = $this->get_license_info();

		wp_localize_script('easy-elements-starter-templates-js', 'easyElementsStarterTemplatesajax', [
			'previewBaseUrl'     => "https://wpeasyelements.com/demotemplate/",
			'ajaxUrl'            => admin_url('admin-ajax.php'),
			'nonce'              => wp_create_nonce('easyel_starter_templates_nonce'),
			'hasPro'             => $license_info['has_pro'],
			'licenseValid'       => 'valid' === $license_info['license_status'],
			'proUpgradeUrl'      => $license_info['pro_upgrade_url'],
			'licenseActivateUrl' => $license_info['license_activate_url'],
			'i18n'               => [
				'unlockTitle'        => __( 'Unlock Premium Templates', 'easy-elements' ),
				'unlockText'         => __( 'This is a premium template. Upgrade to Pro to import and use all premium templates instantly.', 'easy-elements' ),
				'upgradeBtn'         => __( 'Upgrade to Pro', 'easy-elements' ),
				'activateLicenseBtn' => __( 'Activate License', 'easy-elements' ),
			],
		]);
	}

	public function import_files() {
		return $this->get_all();
	}

	public function get_all() {
		$payload = $this->get_cached_payload();
		return $payload['templates'];
	}

	public function get_category_tree() {
		$payload = $this->get_cached_payload();
		return $this->normalize_api_tree( $payload['categories_tree'] );
	}

	private function get_cached_payload() {
		$cached = get_transient( self::TRANSIENT_KEY );

		if ( is_array( $cached ) && isset( $cached['templates'] ) ) {
			return $cached;
		}

		$payload = $this->fetch_from_api();
		set_transient( self::TRANSIENT_KEY, $payload, DAY_IN_SECONDS );
		return $payload;
	}

	public function sync_library() {
		delete_transient( self::TRANSIENT_KEY );

		$payload = $this->fetch_from_api();
		set_transient( self::TRANSIENT_KEY, $payload, DAY_IN_SECONDS );
		return $payload['templates'];
	}

	public function ajax_sync_library() {
		check_ajax_referer( 'easyel_starter_templates_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'easy-elements' ) ) );
		}

		$templates = $this->sync_library();

		if ( empty( $templates ) ) {
			wp_send_json_error( array( 'message' => __( 'Could not fetch templates from the API.', 'easy-elements' ) ) );
		}

		wp_send_json_success( array( 'count' => count( $templates ) ) );
	}

	private function fetch_from_api() {
		$empty = array( 'templates' => array(), 'categories_tree' => array() );

		$response = wp_remote_get( self::API_ENDPOINT, array( 'timeout' => 20 ) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return $empty;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $body ) || empty( $body['starter_templates'] ) ) {
			return $empty;
		}

		$tree_raw  = isset( $body['categories_tree'] ) && is_array( $body['categories_tree'] )
			? $body['categories_tree']
			: array();
		$alias_map = $this->build_child_alias_map( $tree_raw );

		return array(
			'templates'       => $this->normalize_api_data( $body['starter_templates'], $alias_map ),
			'categories_tree' => $tree_raw,
		);
	}

	/**
	 * Build a sanitized-name → [slug,...] map from tree children.
	 *
	 * The API can return the same logical category twice — once as a top-level
	 * entry and once as a child under a parent. Cards reference one slug, the
	 * mega-menu uses the other. The alias map lets us tag each card with both.
	 */
	private function build_child_alias_map( array $api_tree ) {
		$map = array();
		foreach ( $api_tree as $parent ) {
			if ( empty( $parent['children'] ) || ! is_array( $parent['children'] ) ) {
				continue;
			}
			foreach ( $parent['children'] as $child ) {
				if ( empty( $child['slug'] ) ) {
					continue;
				}
				$slug = sanitize_title( $child['slug'] );
				$name = isset( $child['name'] ) ? (string) $child['name'] : '';
				if ( '' !== $name ) {
					$key = sanitize_title( $name );
					if ( $key && $key !== $slug ) {
						$map[ $key ][] = $slug;
					}
				}
			}
		}
		return $map;
	}

	private function normalize_api_data( array $items, array $alias_map = array() ) {
		$notice = esc_html__( 'Caution: For importing demo data please click on "Import Demo Data" button. During demo data installation please do not refresh the page.', 'easy-elements' );
		$normalized = array();

		foreach ( $items as $item ) {
			$categories       = array();
			$category_labels  = array();
			foreach ( (array) ( $item['categories'] ?? array() ) as $cat ) {
				$name = '';
				$slug = '';
				if ( is_array( $cat ) ) {
					$name = (string) ( $cat['name'] ?? '' );
					$slug = ! empty( $cat['slug'] ) ? sanitize_title( (string) $cat['slug'] ) : '';
				} else {
					$name = (string) $cat;
				}
				if ( '' === $name && '' === $slug ) {
					continue;
				}
				if ( '' === $slug ) {
					$slug = sanitize_title( $name );
				}

				$slugs    = array( $slug );
				$name_key = sanitize_title( $name );
				if ( $name_key && isset( $alias_map[ $name_key ] ) ) {
					$slugs = array_merge( $slugs, $alias_map[ $name_key ] );
				}

				foreach ( array_unique( array_filter( $slugs ) ) as $resolved_slug ) {
					$categories[] = $resolved_slug;
				}
				if ( '' !== $name ) {
					$category_labels[] = $name;
				}
			}

			$normalized[] = array(
				'import_file_name'                => $item['title'] ?? '',
				'categories'                      => array_values( array_unique( $categories ) ),
				'category_labels'                 => array_values( array_unique( $category_labels ) ),
				'default_homepage'                => 'Home',
				'import_file_url'                 => $item['xml'] ?? '',
				'import_file_kit_url'             => $item['kit'] ?? '',
				'import_file_easyel_settings_url' => $item['settings'] ?? '',
				'import_file_form_url'            => $item['form'] ?? '',
				'import_preview_image_url'        => $item['thumbnail_url'] ?? '',
				'import_notice'                   => $notice,
				'preview_url'                     => $item['live_demo_url'] ?? '',
				'is_pro'                          => (bool) ( $item['is_pro'] ?? false ),
			);
		}

		return $normalized;
	}

	/**
	 * Convert raw API tree (id, slug, name, count, children) to the shape
	 * used by the template: [ slug, label, count, children: [...] ].
	 * Drops top-level entries that duplicate a child of another parent.
	 */
	private function normalize_api_tree( array $api_tree ) {
		if ( empty( $api_tree ) ) {
			return array();
		}

		$child_keys = array();
		foreach ( $api_tree as $parent ) {
			if ( empty( $parent['children'] ) || ! is_array( $parent['children'] ) ) {
				continue;
			}
			foreach ( $parent['children'] as $child ) {
				if ( ! empty( $child['slug'] ) ) {
					$child_keys[ sanitize_title( $child['slug'] ) ] = true;
				}
				if ( ! empty( $child['name'] ) ) {
					$child_keys[ sanitize_title( $child['name'] ) ] = true;
				}
			}
		}

		$out = array();
		foreach ( $api_tree as $parent ) {
			if ( empty( $parent['slug'] ) ) {
				continue;
			}
			$parent_slug  = sanitize_title( $parent['slug'] );
			$parent_name  = isset( $parent['name'] ) ? (string) $parent['name'] : '';
			$has_children = ! empty( $parent['children'] ) && is_array( $parent['children'] );

			if ( ! $has_children ) {
				$name_key = $parent_name ? sanitize_title( $parent_name ) : '';
				if ( isset( $child_keys[ $parent_slug ] )
					|| ( $name_key && isset( $child_keys[ $name_key ] ) )
				) {
					continue;
				}
			}

			$children = array();
			if ( $has_children ) {
				foreach ( $parent['children'] as $child ) {
					if ( empty( $child['slug'] ) ) {
						continue;
					}
					$children[] = array(
						'slug'  => sanitize_title( $child['slug'] ),
						'label' => isset( $child['name'] ) ? (string) $child['name'] : '',
						'count' => isset( $child['count'] ) ? (int) $child['count'] : 0,
					);
				}
			}
			$out[] = array(
				'slug'     => $parent_slug,
				'label'    => $parent_name,
				'count'    => isset( $parent['count'] ) ? (int) $parent['count'] : 0,
				'children' => $children,
			);
		}
		return $out;
	}

	public function required_plugins() {
		$plugins = array(	 
			array(
				'name' 		=> 'Elementor',
				'slug' 		=> 'elementor',
				'required' 	=> true,
			),
			array(
				'name' 		=> 'Easy Elements',
				'slug' 		=> 'easy-elements',
				'required' 	=> true,
			),
			array(
				'name' 		=> 'BoldForm Lite',
				'slug' 		=> 'boldform-lite',
				'source' 	=> 'https://themewant.com/products/plugins/boldform-lite.zip',
				'required' 	=> true,
			),
			array(
				'name' 		=> 'Wordpress Importer',
				'slug' 		=> 'wordpress-importer',
				'required' 	=> true,
			),

		);
		return $plugins;
	}

	public function recommeneded_theme(){
		return array(
			'name' => 'Hello Elementor',
			'slug' => 'hello-elementor',
			'source' => 'https://downloads.wordpress.org/theme/hello-elementor.3.4.5.zip',
		);
	}

	public static function install_wordpress_importer() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $slug = 'wordpress-importer';
        $plugin_file = 'wordpress-importer/wordpress-importer.php';
        
        $is_installed = false;
        if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
            $is_installed = true;
        } else {
             // Fallback: scan for the plugin file if the directory name differs
             if ( ! function_exists( 'get_plugins' ) ) {
                 require_once ABSPATH . 'wp-admin/includes/plugin.php';
             }
             $all_plugins = get_plugins();
             foreach ( array_keys( $all_plugins ) as $ph ) {
                 if ( dirname( $ph ) === $slug || basename( $ph ) === 'wordpress-importer.php' ) {
                     $plugin_file = $ph;
                     $is_installed = true;
                     break;
                 }
             }
        }

        if ( ! $is_installed ) {
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

            if ( ! class_exists( 'Plugin_Upgrader' ) ) {
                return;
            }

            $api = plugins_api( 'plugin_information', array( 'slug' => $slug, 'fields' => array( 'sections' => false ) ) );

            if ( ! is_wp_error( $api ) ) {
                $skin = new \WP_Ajax_Upgrader_Skin();
                $upgrader = new \Plugin_Upgrader( $skin );
                $upgrader->install( $api->download_link );
                
                // Clear plugin cache to ensure the new plugin is recognized
                wp_cache_delete( 'plugins', 'plugins' );
            }
        }
        
        // Ensure the plugin is active if installed
        if ( $is_installed || file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
            if ( ! is_plugin_active( $plugin_file ) ) {
                activate_plugin( $plugin_file );
            }
        }
    }

	// Starter Templates Page HTML
	public function templates_page_html() {

		$templates = $this->import_files();

		$total_count = count( $templates );
		$free_count  = count( array_filter( $templates, function( $t ) { return empty( $t['is_pro'] ); } ) );
		$pro_count   = count( array_filter( $templates, function( $t ) { return ! empty( $t['is_pro'] ); } ) );

		$category_tree = $this->get_category_tree();
	
		$is_activated_plugins = true;

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

		$dashboard_url = admin_url( 'admin.php?page=easy-elements-dashboard' );
		?>
		<div class="wrap easyel-starter-wrap">
			<header class="easyel-topbar">
				<div class="easyel-topbar-left">
					<a href="<?php echo esc_url( $dashboard_url ); ?>" class="easyel-brand" aria-label="<?php echo esc_attr__( 'Easy Elements', 'easy-elements' ); ?>">
						<span class="easyel-brand-mark" aria-hidden="true">
							<svg viewBox="0 0 32 32" fill="none">
								<rect x="2" y="2" width="12" height="12" rx="3" fill="#7455ff"/>
								<rect x="18" y="2" width="12" height="12" rx="3" fill="#9d84ff"/>
								<rect x="2" y="18" width="12" height="12" rx="3" fill="#9d84ff"/>
								<rect x="18" y="18" width="12" height="12" rx="3" fill="#7455ff"/>
							</svg>
						</span>
						<span class="easyel-brand-text">
							<strong><?php echo esc_html__( 'Easy Elements', 'easy-elements' ); ?></strong>
							<span><?php echo esc_html__( 'Starter Templates', 'easy-elements' ); ?></span>
						</span>
					</a>
				</div>

				<div class="easyel-topbar-right">
					<a href="<?php echo esc_url( $dashboard_url ); ?>" class="easyel-exit-btn" title="<?php echo esc_attr__( 'Exit to Dashboard', 'easy-elements' ); ?>">
						<span class="dashicons dashicons-arrow-left-alt2" aria-hidden="true"></span>
						<span><?php echo esc_html__( 'Exit to Dashboard', 'easy-elements' ); ?></span>
					</a>

					<button type="button" class="easyel-icon-btn" id="easyel-favorites-toggle" title="<?php echo esc_attr__( 'Show favourites', 'easy-elements' ); ?>" aria-pressed="false">
						<span class="dashicons dashicons-heart" aria-hidden="true"></span>
					</button>

					<button type="button" class="easyel-icon-btn" id="easyel-sync-btn" title="<?php echo esc_attr__( 'Sync library', 'easy-elements' ); ?>">
						<span class="dashicons dashicons-update" aria-hidden="true"></span>
					</button>

					<div class="easyel-select-wrap">
						<select id="easyel-type-filter" aria-label="<?php echo esc_attr__( 'Filter by type', 'easy-elements' ); ?>">
							<option value="all"><?php /* translators: %d: total number of templates */ echo esc_html( sprintf( __( 'All (%d)', 'easy-elements' ), $total_count ) ); ?></option>
							<option value="free"><?php /* translators: %d: number of free templates */ echo esc_html( sprintf( __( 'Free (%d)', 'easy-elements' ), $free_count ) ); ?></option>
							<option value="pro"><?php /* translators: %d: number of pro templates */ echo esc_html( sprintf( __( 'Pro (%d)', 'easy-elements' ), $pro_count ) ); ?></option>
						</select>
						<span class="dashicons dashicons-arrow-down-alt2" aria-hidden="true"></span>
					</div>
				</div>
			</header>

			<section class="easyel-hero">
				<span class="easyel-hero-eyebrow"><?php echo esc_html__( 'Template Library', 'easy-elements' ); ?></span>
				<h1 class="easyel-hero-title"><?php echo esc_html__( 'Easy Starter Templates', 'easy-elements' ); ?></h1>
				<p class="easyel-hero-subtitle"><?php echo esc_html__( 'Discover beautiful, ready-to-use templates for your next project — import in one click and start customising in seconds.', 'easy-elements' ); ?></p>

				<div class="easyel-hero-search">
					<span class="dashicons dashicons-search" aria-hidden="true"></span>
					<input type="search" id="easyel-search-input" placeholder="<?php echo esc_attr__( 'What kind of website are you building?', 'easy-elements' ); ?>" autocomplete="off">
				</div>
			</section>

			<div class="easyel-grid-wrapper">
				<?php if ( ! empty( $category_tree ) ) : ?>
				<nav class="easyel-mega" aria-label="<?php echo esc_attr__( 'Template categories', 'easy-elements' ); ?>">
					<ul class="easyel-mega-list" id="easyel-mega-list">
						<?php foreach ( $category_tree as $cat ) :
							$has_children = ! empty( $cat['children'] );
							?>
							<?php if ( $has_children ) : ?>
								<li class="easyel-mega-item has-children" data-parent="<?php echo esc_attr( $cat['slug'] ); ?>">
									<button type="button" class="easyel-mega-trigger" aria-haspopup="true" aria-expanded="false">
										<span class="easyel-mega-trigger-label"><?php echo esc_html( $cat['label'] ); ?></span>
										<svg class="easyel-mega-arrow" viewBox="0 0 11 6" fill="none" aria-hidden="true">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M10.7812 1.22462L6.02812 5.78989C5.73645 6.07004 5.26355 6.07004 4.97188 5.78989L0.218757 1.22462C-0.0729189 0.944468 -0.0729189 0.490259 0.218757 0.210111C0.510432 -0.0700375 0.983331 -0.0700374 1.27501 0.210111L5.5 4.26813L9.72499 0.210111C10.0167 -0.0700371 10.4896 -0.070037 10.7812 0.210111C11.0729 0.490259 11.0729 0.944468 10.7812 1.22462Z" fill="currentColor"/>
										</svg>
										<span class="easyel-mega-trigger-active" aria-hidden="true"></span>
									</button>

									<div class="easyel-mega-panel" role="menu">
										<div class="easyel-mega-panel-head">
											<span class="easyel-mega-panel-title"><?php echo esc_html( $cat['label'] ); ?></span>
										</div>
										<ul class="easyel-mega-children">
											<?php foreach ( $cat['children'] as $child ) : ?>
												<li>
													<button
														type="button"
														class="easyel-mega-child easyel-cat-item"
														role="menuitemcheckbox"
														aria-checked="false"
														data-cat="<?php echo esc_attr( $child['slug'] ); ?>"
														data-parent="<?php echo esc_attr( $cat['slug'] ); ?>"
													>
														<span class="easyel-cat-check" aria-hidden="true">
															<span class="dashicons dashicons-yes"></span>
														</span>
														<span class="easyel-cat-name"><?php echo esc_html( $child['label'] ); ?></span>
													</button>
												</li>
											<?php endforeach; ?>
										</ul>
										<button
											type="button"
											class="easyel-mega-deselect-all"
											data-parent="<?php echo esc_attr( $cat['slug'] ); ?>"
											hidden
										>
											<span><?php esc_html_e( 'Uncheck all', 'easy-elements' ); ?></span>
										</button>
									</div>
								</li>
							<?php else : ?>
								<li class="easyel-mega-item is-leaf">
									<button
										type="button"
										class="easyel-mega-trigger easyel-mega-leaf easyel-cat-item"
										data-cat="<?php echo esc_attr( $cat['slug'] ); ?>"
									>
										<span class="easyel-mega-trigger-label"><?php echo esc_html( $cat['label'] ); ?></span>
									</button>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>

					<button type="button" class="easyel-mega-reset" id="easyel-cat-reset" hidden>
						<span class="dashicons dashicons-no-alt" aria-hidden="true"></span>
						<span class="easyel-mega-reset-label"><?php esc_html_e( 'Reset', 'easy-elements' ); ?></span>
						<span class="easyel-mega-reset-count" id="easyel-mega-reset-count">0</span>
					</button>
				</nav>
				<?php endif; ?>
				<?php
				if(!empty($templates)) {
				?>
				<div class="easyel-grid" id="easyel-importer-steps-grid" style="min-height:100px;display: grid;">
					<?php
					foreach ($templates as $template) {
						$title     = $template['import_file_name'];
						$thumb_url = $template['import_preview_image_url'];
						$preview_url = $template['preview_url'];
						$import_file_url = $template['import_file_url'];
						$import_file_kit_url = $template['import_file_kit_url'];
						$import_file_easyel_settings_url = $template['import_file_easyel_settings_url'];
						$import_file_form_url = $template['import_file_form_url'];
						$template_cats = implode(' ', $template['categories']);
						$default_homepage = $template['default_homepage'];
						$is_pro = $template['is_pro'];

						$pro_class = ( $is_pro == true ) ? 'easyel-pro-feature' : '';

						if( !class_exists( 'Easy_Elements_Pro' ) ) {
							$pro_url = 'https://wpeasyelements.com/pricing/';
							$pro_text = 'Upgrade to pro';
						}
						elseif( class_exists( 'Easy_Elements_Pro' ) && $license_status == 'invalid' ) {
							$pro_url  = admin_url( 'admin.php?page=easy-elements-dashboard#activate_license' );
							$pro_text = 'Activate License';
						}


						?>
						<div class="easyel-item <?php echo esc_attr( $pro_class ); ?>" data-category="<?php echo esc_attr( $template_cats ) ?>" data-title="<?php echo esc_attr( $title ) ?>" data-type="<?php echo $is_pro ? 'pro' : 'free'; ?>">
							<div class="easyel-card">
								<div class="easyel-thumb">
									<img src="<?php echo esc_url($thumb_url) ?>" class="easyel-thumbnail"/>
									<button class="easyel-fav-btn" data-template-name="<?php echo esc_attr( $title ) ?>" title="<?php echo esc_attr__( 'Add to Favourites', 'easy-elements' ); ?>">
										<span class="dashicons dashicons-heart"></span>
									</button>
									<div class="easyel-overlay">
										<div class="easyel-action-btns">
											<button class="easyel-preview-btn"
												data-preview-url="<?php echo esc_url( $preview_url ) ?>"
												data-template-name="<?php echo esc_attr( $title ) ?>"
												data-import-file-url="<?php echo esc_url( $import_file_url ) ?>"
												data-import-file-kit-url="<?php echo esc_url( $import_file_kit_url ) ?>"
												data-import-file-easyel-settings-url="<?php echo esc_url( $import_file_easyel_settings_url ) ?>"
												data-import-file-form-url="<?php echo esc_url( $import_file_form_url ) ?>"
												data-import-file-is-pro="<?php echo esc_attr( $is_pro ? 'true' : 'false' ) ?>"
												data-default-homepage="<?php echo esc_attr( $default_homepage ) ?>">
												<span class="dashicons dashicons-visibility"></span> <?php echo esc_html__( 'Preview', 'easy-elements' ); ?>
											</button>
											<?php
												if( ! $is_pro  || ( $is_pro && $license_status === 'valid' ) ) { ?>
													<a href="#" data-import-file-url="<?php echo esc_url( $import_file_url )?>" data-import-file-kit-url="<?php echo esc_url( $import_file_kit_url )?>" data-import-file-easyel-settings-url="<?php echo esc_url( $import_file_easyel_settings_url )?>" data-import-file-form-url="<?php echo esc_url( $import_file_form_url )?>" data-default-homepage="<?php echo esc_attr( $default_homepage )?>" class="easyel-import-btn"><span class="dashicons dashicons-download"></span> <?php echo esc_html__( 'Import', 'easy-elements' ); ?></a>
											<?php } else { ?>
											<a href="<?php echo esc_url( $pro_url )?>" class="easyel-invalid-license" target="_blank"><span class="dashicons dashicons-download"></span> <?php echo esc_html( $pro_text ); ?></a>
											<?php } ?>
										</div>
									</div>
								</div>
								<div class="easyel-meta">
									<span class="easyel-title"><?php echo esc_html($title) ?></span>
									<span class="easyel-groups"><?php echo esc_html( implode( ', ', ! empty( $template['category_labels'] ) ? $template['category_labels'] : array_map( function( $c ) { return ucwords( str_replace( '-', ' ', $c ) ); }, $template['categories'] ) ) ); ?></span>
								</div>
							</div>
						</div>
						<?php
					}
					?>
				</div>
				<div class="easyel-load-target"></div>
				<?php } ?>
				<?php
				if(empty($templates)) {
				?>
				<div class="easyel-empty-notice">
					<h3><?php echo esc_html__( 'No templates found.', 'easy-elements' )?></h3>
				</div>
				<?php } ?>
			</div>
				
			<!-- Wrapper -->
			<div id="easyel-importer-modal">
				<div class="easyel-importer-modal-overlay"></div>
				<div class="easyel-importer-modal-body">
					<button type="button" class="easyel-importer-modal-closer"><span class="dashicons dashicons-no"></span></button>
					<div class="easyel-importer-modal-content">
						<div class="easyel-importer-step easyel-importer-modal-step-1">
							<div class="easyel-importer-step-card">
								<h2 class="easyel-importer-step-title"><?php echo esc_html__( 'Required Features', 'easy-elements' ); ?></h2>
								<p class="easyel-importer-step-subtext"><?php echo esc_html__( 'Install every plugin, theme and extension listed below.', 'easy-elements' ); ?></p>

								<div class="easyel-importer-step-group-box">
									<div class="easyel-importer-step-group-head">
										<h3><?php echo esc_html__( 'Required Plugins', 'easy-elements' ); ?></h3>
										<span><?php echo count($this->required_plugins); echo esc_html__( ' Plugins', 'easy-elements' ); ?></span>
									</div>

									<ul class="easyel-importer-required-plugins-list easyel-importer-step-item-list">
										<?php 
											
											if ( ! empty( $this->required_plugins ) ) {
												if ( ! function_exists( 'is_plugin_active' ) ) {
													include_once ABSPATH . 'wp-admin/includes/plugin.php';
												}
                                                
                                                $all_plugins = get_plugins();

												foreach ( $this->required_plugins as $plugin ) {

                                                    // Find the plugin file based on the slug (folder name)
                                                    $plugin_file = '';
                                                    $is_active = false;
                                                    
                                                    foreach ( array_keys( $all_plugins ) as $ph ) {
                                                        if ( dirname( $ph ) === $plugin['slug'] ) {
                                                            $plugin_file = $ph;
                                                            break;
                                                        }
                                                    }
                                                    
                                                    // Fallback for plugins in the root directory or if slug matches filename
                                                    if ( empty( $plugin_file ) && isset( $all_plugins[ $plugin['slug'] . '.php' ] ) ) {
                                                        $plugin_file = $plugin['slug'] . '.php';
                                                    }

                                                    if ( ! empty( $plugin_file ) ) {
                                                        $is_active = is_plugin_active( $plugin_file );
                                                    }

													 if ( ! $is_active ) {
														$is_activated_plugins = false;
													 }
													?>
													<li class="easyel-plugin-item" data-slug="<?php echo esc_attr($plugin['slug']); ?>" data-source="<?php echo esc_attr(isset($plugin['source']) ? $plugin['source'] : ''); ?>" data-installed="<?php echo !empty($plugin_file) ? 'true' : 'false'; ?>" data-active="<?php echo $is_active ? 'true' : 'false'; ?>">
														<span class="easyel-importer-step-dot"></span>

														<?php echo esc_html( $plugin['name'] ); ?>

														<?php if ( $is_active ) : ?>
															<span class="easyel-importer-step-status active"><?php echo esc_html__( 'Active', 'easy-elements' ); ?></span>
														<?php else : ?>
															<span class="easyel-importer-step-status inactive"><?php echo esc_html__( 'Inactive', 'easy-elements' ); ?></span>
															<button type="button" class="easyel-plugin-activate-btn"><?php echo esc_html__( 'Activate', 'easy-elements' ); ?></button>
														<?php endif; ?>
													</li>
													<?php
												}
											}

										?>
									</ul>
								</div>

								<div class="easyel-importer-step-group-box">
									<div class="easyel-importer-step-group-head">
										<h3><?php echo esc_html__( 'Recommended Themes', 'easy-elements' ); ?></h3>
										<span><?php echo esc_html__( '1 Theme', 'easy-elements' ); ?></span>
									</div>
									<?php 
									if(!empty($this->recommeneded_theme)){
                                        $current_theme = wp_get_theme();
                                        $is_theme_active = $current_theme->get_stylesheet() === $this->recommeneded_theme['slug'];
                                        
                                        $theme_obj = wp_get_theme( $this->recommeneded_theme['slug'] );
                                        $is_theme_installed = $theme_obj->exists();
										?>
										<ul class="easyel-importer-step-item-list">
											<li class="easyel-theme-item" data-slug="<?php echo esc_attr( $this->recommeneded_theme['slug'] ); ?>" data-source="<?php echo esc_attr( $this->recommeneded_theme['source'] ); ?>" data-installed="<?php echo $is_theme_installed ? 'true' : 'false'; ?>" data-active="<?php echo $is_theme_active ? 'true' : 'false'; ?>">
												<input type="checkbox" name="recommened-theme" value="<?php echo esc_attr( $this->recommeneded_theme['slug'] )?>" data-source="<?php echo esc_attr($this->recommeneded_theme['source']); ?>" data-installed="<?php echo $is_theme_installed ? 'true' : 'false'; ?>" <?php checked( $is_theme_active ); ?>>
												<?php echo esc_html( $this->recommeneded_theme['name'] ); ?>
                                                <?php if ( $is_theme_active ) : ?>
												    <span class="easyel-importer-step-status active"><?php echo esc_html__( 'Active', 'easy-elements' ); ?></span>
                                                <?php else : ?>
                                                    <span class="easyel-importer-step-status inactive"><?php echo esc_html__( 'Inactive', 'easy-elements' ); ?></span>
                                                    <button type="button" class="easyel-theme-activate-btn"><?php echo esc_html__( 'Activate', 'easy-elements' ); ?></button>
                                                <?php endif; ?>
											</li>
										</ul>
										<?php
									}
									?>
									
								</div>
								<?php 
									if($is_activated_plugins === false) {
										?>
											<label class="easyel-importer-step-checkbox-warning">
												<?php echo esc_html__( 'Required plugins will be activated before import!', 'easy-elements' ); ?>
											</label>
										<?php
									}
								?>
								<div class="easyel-importer-step-actions">
									<button class="easyel-importer-step-btn-secondary"><?php echo esc_html__( 'Go Back', 'easy-elements' ); ?></button>
									<button class="easyel-importer-step-btn-primary easyel-importer-step-next-btn">
										<?php echo esc_html__( 'Continue to next', 'easy-elements' ); ?>
									</button>
								</div>
							</div>
						</div>
						<div class="easyel-importer-step easyel-importer-modal-step-2" style="display:none;">
							<div class="easyel-importer-step-card">
								<h2 class="easyel-importer-step-title"><?php echo esc_html__( 'Creating your website...', 'easy-elements' ); ?></h2>
								<p class="easyel-importer-step-subtext">
									<?php echo esc_html__( 'Please wait, your website is being created. It will take few minutes. Do not reload.', 'easy-elements' ); ?>
								</p>

								
								<div id="preloader-skeleton">
									<div class="skeleton-header">
										<div class="skeleton-logo"></div>
										<div class="skeleton-profile"></div>
									</div>

									<div class="skeleton-layout-container">
										
										<div class="skeleton-sidebar">
											<div class="skeleton-list-item"></div>
											<div class="skeleton-list-item"></div>
											<div class="skeleton-list-item"></div>
											<div class="skeleton-list-item medium"></div>
										</div>
										
										<div class="skeleton-main-content">
											
											<div class="skeleton-card large-top"></div>
											
											<div class="skeleton-grid-row">
												
												<div class="skeleton-card large-left"></div>
												
												<div class="skeleton-stack-right">
													<div class="skeleton-card small-right"></div>
													<div class="skeleton-card small-right"></div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="easyel-importer-step-progress-bar">
									<div class="easyel-importer-step-progress-fill" style="width:20%;"></div>
								</div>

								<p class="easyel-importer-step-progress-text"><?php echo esc_html__( '20% Completed', 'easy-elements' ); ?></p>
							</div>
						</div>
						<div class="easyel-importer-step easyel-importer-modal-step-3" style="display:none;">
							<div class="easyel-importer-step-card">
								<h2 class="easyel-importer-step-title"><?php echo esc_html__( 'Congratulations!!!', 'easy-elements' ); ?> 🎉</h2>
								<p class="easyel-importer-step-subtext"><?php echo esc_html__( 'Your website is now imported and ready to use.', 'easy-elements' ); ?></p>
								<img src="<?php echo esc_url( EASYELEMENTS_DIR_URL.'includes/Starter_Template/assets/img/success.gif'); ?>" class="easyel-importer-success-img"/>
								<a href="<?php echo esc_url(home_url()); ?>" class="easyel-importer-step-btn-primary easyel-importer-step-btn-big">
									<?php echo esc_html__( 'Visit your website', 'easy-elements' ); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Pro Lock Modal -->
			<div id="easyel-pro-lock-modal" role="dialog" aria-modal="true" aria-labelledby="easyel-pro-lock-title" style="display:none;">
				<div class="easyel-pro-lock-overlay"></div>
				<div class="easyel-pro-lock-body">
					<button type="button" class="easyel-pro-lock-closer" aria-label="<?php echo esc_attr__( 'Close', 'easy-elements' ); ?>"><span class="dashicons dashicons-no-alt"></span></button>
					<div class="easyel-pro-lock-content">
						<span class="easyel-pro-lock-icon dashicons dashicons-lock" aria-hidden="true"></span>
						<h2 class="easyel-pro-lock-title" id="easyel-pro-lock-title"><?php echo esc_html__( 'Unlock Premium Templates', 'easy-elements' ); ?></h2>
						<p class="easyel-pro-lock-text"><?php echo esc_html__( 'This is a premium template. Upgrade to Pro to import and use all premium templates instantly.', 'easy-elements' ); ?></p>
						<a href="#" class="easyel-pro-lock-cta">
							<span class="dashicons dashicons-star-filled" aria-hidden="true"></span>
							<span class="easyel-pro-lock-cta-text"><?php echo esc_html__( 'Upgrade to Pro', 'easy-elements' ); ?></span>
						</a>
					</div>
				</div>
			</div>

			<!-- Preview Modal -->
			<div id="easyel-preview-modal" style="display:none;">
				<div class="easyel-preview-modal-header">
					<button class="easyel-preview-back-btn">
						<span class="dashicons dashicons-arrow-left-alt"></span>
						<?php echo esc_html__( 'Back', 'easy-elements' ); ?>
					</button>
					<span class="easyel-preview-template-title"></span>
					<div class="easyel-preview-device-controls">
						<button class="easyel-device-btn active" data-device="desktop" title="<?php echo esc_attr__( 'Desktop', 'easy-elements' ); ?>">
							<span class="dashicons dashicons-desktop"></span>
						</button>
						<button class="easyel-device-btn" data-device="tablet" title="<?php echo esc_attr__( 'Tablet', 'easy-elements' ); ?>">
							<span class="dashicons dashicons-tablet"></span>
						</button>
						<button class="easyel-device-btn" data-device="mobile" title="<?php echo esc_attr__( 'Mobile', 'easy-elements' ); ?>">
							<span class="dashicons dashicons-smartphone"></span>
						</button>
					</div>
					<button class="easyel-preview-import-cta">
						<span class="dashicons dashicons-download"></span>
						<?php echo esc_html__( 'Import', 'easy-elements' ); ?>
					</button>
				</div>
				<div class="easyel-preview-modal-body">
					<div class="easyel-preview-iframe-container" data-device="desktop">
						<div class="easyel-preview-loader" id="easyel-preview-loader">
							<div class="easyel-preview-spinner"></div>
							<span><?php echo esc_html__( 'Loading preview…', 'easy-elements' ); ?></span>
						</div>
						<iframe id="easyel-preview-iframe" src="about:blank"></iframe>
					</div>
				</div>
			</div>

		</div>
		<?php
	}

	public function install_plugin() {
        check_ajax_referer( 'easyel_starter_templates_nonce', 'security' );
        
        if ( ! current_user_can( 'install_plugins' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'easy-elements' ) ) );
        }

        $slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
        $source = isset( $_POST['source'] ) ? esc_url_raw( wp_unslash( $_POST['source'] ) ) : '';

        if ( empty( $slug ) ) {
            wp_send_json_error( array( 'message' => __( 'Plugin slug is missing.', 'easy-elements' ) ) );
        }

        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

        $skin = new \WP_Ajax_Upgrader_Skin();
        $upgrader = new \Plugin_Upgrader( $skin );

        if ( ! empty( $source ) ) {
             $result = $upgrader->install( $source );
        } else {
            // If source is empty, use WordPress.org directory
            $api = plugins_api( 'plugin_information', array( 'slug' => $slug, 'fields' => array( 'sections' => false ) ) );
            if ( is_wp_error( $api ) ) {
                wp_send_json_error( array( 'message' => $api->get_error_message() ) );
            }
            $result = $upgrader->install( $api->download_link );
        }

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        } elseif ( is_wp_error( $skin->result ) ) {
            wp_send_json_error( array( 'message' => $skin->result->get_error_message() ) );
        } elseif ( $skin->get_errors()->get_error_code() ) {
            wp_send_json_error( array( 'message' => $skin->get_errors()->get_error_message() ) );
        } elseif ( is_null( $result ) ) {
             wp_send_json_error( array( 'message' => __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'easy-elements' ) ) );
        }

        wp_send_json_success( array( 'message' => __( 'Plugin installed successfully.', 'easy-elements' ) ) );
    }

    public function activate_plugin() {
        check_ajax_referer( 'easyel_starter_templates_nonce', 'security' );
        
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'easy-elements' ) ) );
        }

        $slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
        $init = isset( $_POST['init'] ) ? sanitize_text_field( wp_unslash( $_POST['init'] ) ) : '';

        if ( empty( $init ) ) {
             $init = $slug . '/' . $slug . '.php';
        }
        
        $plugin_path = WP_PLUGIN_DIR . '/' . $init;
        if ( ! file_exists( $plugin_path ) ) {
             // Try to find it
             if ( ! function_exists( 'get_plugins' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
             }
             $plugins = get_plugins();
             foreach ( $plugins as $file => $plugin ) {
                 if ( strpos( $file, $slug ) !== false ) {
                     $init = $file;
                     break;
                 }
             }
        }

        $result = activate_plugin( $init );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }

        wp_send_json_success( array( 'message' => __( 'Plugin activated successfully.', 'easy-elements' ) ) );
    }

    public function install_theme() {
        check_ajax_referer( 'easyel_starter_templates_nonce', 'security' );

        if ( ! current_user_can( 'install_themes' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'easy-elements' ) ) );
        }

        $slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
        $source = isset( $_POST['source'] ) ? esc_url_raw( wp_unslash( $_POST['source'] ) ) : '';

        if ( empty( $slug ) ) {
            wp_send_json_error( array( 'message' => __( 'Theme slug is missing.', 'easy-elements' ) ) );
        }

        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/theme.php';

        $skin = new \WP_Ajax_Upgrader_Skin();
        $upgrader = new \Theme_Upgrader( $skin );

        if ( ! empty( $source ) ) {
            $result = $upgrader->install( $source );
        } else {
            $api = themes_api( 'theme_information', array( 'slug' => $slug, 'fields' => array( 'sections' => false ) ) );
            if ( is_wp_error( $api ) ) {
                wp_send_json_error( array( 'message' => $api->get_error_message() ) );
            }
            $result = $upgrader->install( $api->download_link );
        }

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        } elseif ( is_wp_error( $skin->result ) ) {
            wp_send_json_error( array( 'message' => $skin->result->get_error_message() ) );
        } elseif ( $skin->get_errors()->get_error_code() ) {
            wp_send_json_error( array( 'message' => $skin->get_errors()->get_error_message() ) );
        } elseif ( is_null( $result ) ) {
            wp_send_json_error( array( 'message' => __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'easy-elements' ) ) );
        }

        wp_send_json_success( array( 'message' => __( 'Theme installed successfully.', 'easy-elements' ) ) );
    }

    public function activate_theme() {
        check_ajax_referer( 'easyel_starter_templates_nonce', 'security' );

        if ( ! current_user_can( 'switch_themes' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'easy-elements' ) ) );
        }

        $slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

        if ( empty( $slug ) ) {
            wp_send_json_error( array( 'message' => __( 'Theme slug is missing.', 'easy-elements' ) ) );
        }

        switch_theme( $slug );

        wp_send_json_success( array( 'message' => __( 'Theme activated successfully.', 'easy-elements' ) ) );
    }

    public function import_content() {
        check_ajax_referer( 'easyel_starter_templates_nonce', 'security' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'easy-elements' ) ) );
        }

		// Increase limits to prevent 500 Internal Server Errors (timeouts and memory limits) on Apache/FCGI
		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 0 ); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged
		}
		@ini_set( 'max_execution_time', '0' ); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged
		@ini_set( 'max_input_time', '0' ); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged
		wp_raise_memory_limit( 'admin' );
		ignore_user_abort( true );

		// Buffer all output to prevent PHP notices/warnings from corrupting the JSON AJAX response.
		ob_start();

		// Optimize performance by preventing cache and term counting overhead during the heavy import
		if ( function_exists( 'wp_suspend_cache_invalidation' ) ) {
			wp_suspend_cache_invalidation( true );
		}
		if ( function_exists( 'wp_defer_term_counting' ) ) {
			wp_defer_term_counting( true );
		}
		if ( function_exists( 'wp_defer_comment_counting' ) ) {
			wp_defer_comment_counting( true );
		}

        $import_file_url = isset( $_POST['import_file_url'] ) ? esc_url_raw( wp_unslash( $_POST['import_file_url'] ) ) : '';
		$import_file_kit_url = isset( $_POST['import_file_kit_url'] ) ? esc_url_raw( wp_unslash( $_POST['import_file_kit_url'] ) ) : '';
		$import_file_easyel_settings_url = isset( $_POST['import_file_easyel_settings_url'] ) ? esc_url_raw( wp_unslash( $_POST['import_file_easyel_settings_url'] ) ) : '';
		$import_file_form_url = isset( $_POST['import_file_form_url'] ) ? esc_url_raw( wp_unslash( $_POST['import_file_form_url'] ) ) : '';
		$default_homepage = isset( $_POST['default_homepage'] ) ? sanitize_text_field( wp_unslash( $_POST['default_homepage'] ) ) : '';

        if ( empty( $import_file_url ) ) {
            wp_send_json_error( array( 'message' => __( 'Import file URL is missing.', 'easy-elements' ) ) );
        }

		if ( empty( $import_file_kit_url ) ) {
            wp_send_json_error( array( 'message' => __( 'Import kit file URL is missing.', 'easy-elements' ) ) );
        }

		if ( empty( $import_file_easyel_settings_url ) ) {
            wp_send_json_error( array( 'message' => __( 'Import Easy Elements settings file URL is missing.', 'easy-elements' ) ) );
        }

		if ( empty( $import_file_form_url ) ) {
            wp_send_json_error( array( 'message' => __( 'Import form file URL is missing.', 'easy-elements' ) ) );
        }

        // Ensure wordpress-importer is installed and active before proceeding
        self::install_wordpress_importer();

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        $file_ext = strtolower( pathinfo( $import_file_url, PATHINFO_EXTENSION ) );

		// import core plugin settings
		$easyel_settings_imported = $this->process_easyel_settings_import( $import_file_easyel_settings_url );
		$kit_imported = $this->process_kit_import( $import_file_kit_url );
		$xml_imported = $this->process_xml_import( $import_file_url );
		$form_imported = $this->process_form_import( $import_file_form_url );
		if ( $kit_imported && $xml_imported && $easyel_settings_imported && $form_imported ) {
			$this->assign_menu();
			
			$activated_theme = isset( $_POST['activated_theme'] ) ? sanitize_text_field( wp_unslash( $_POST['activated_theme'] ) ) : '';
			if(!empty($activated_theme)) {
				$theme = wp_get_theme( $activated_theme );
				if ( $theme->exists() ) {
					switch_theme( $theme->get_stylesheet() );
				}
			}
			
			$this->assign_default_homepage($default_homepage);
			$this->clear_elementor_cache();

			if ( function_exists( 'wp_suspend_cache_invalidation' ) ) {
				wp_suspend_cache_invalidation( false );
			}
			if ( function_exists( 'wp_defer_term_counting' ) ) {
				wp_defer_term_counting( false );
			}
			if ( function_exists( 'wp_defer_comment_counting' ) ) {
				wp_defer_comment_counting( false );
			}

			// Discard any buffered output (warnings, notices) to keep JSON response clean.
			if ( ob_get_level() > 0 ) {
				ob_end_clean();
			}
			wp_send_json_success( array( 'message' => __( 'Content imported successfully.', 'easy-elements' ) ) );
		} else {
			if ( function_exists( 'wp_suspend_cache_invalidation' ) ) {
				wp_suspend_cache_invalidation( false );
			}
			if ( function_exists( 'wp_defer_term_counting' ) ) {
				wp_defer_term_counting( false );
			}
			if ( function_exists( 'wp_defer_comment_counting' ) ) {
				wp_defer_comment_counting( false );
			}
			// Discard any buffered output (warnings, notices) to keep JSON response clean.
			if ( ob_get_level() > 0 ) {
				ob_end_clean();
			}
			wp_send_json_error( array( 'message' => __( 'Content import failed.', 'easy-elements' ) ) );
		}
    }

    private function process_xml_import( $url ) {

        // Download the XML file to a temp path
        $tmp_file = download_url( $url );

        if ( is_wp_error( $tmp_file ) ) {
            return false;
        }


        if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
            define( 'WP_LOAD_IMPORTERS', true );
        }

        // Load WordPress admin import infrastructure
        require_once ABSPATH . 'wp-admin/includes/import.php';
        if ( ! class_exists( 'WP_Importer' ) ) {
            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            if ( file_exists( $class_wp_importer ) ) {
                require_once $class_wp_importer;
            }
        }

        // Ensure wordpress-importer is installed
        self::install_wordpress_importer();

        // Locate the installed wordpress-importer plugin directory
        $wp_importer_dir = WP_PLUGIN_DIR . '/wordpress-importer';
        if ( ! is_dir( $wp_importer_dir ) ) {
            // Fallback: scan plugin dirs for it
            if ( ! function_exists( 'get_plugins' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            foreach ( array_keys( get_plugins() ) as $plugin_file ) {
                if ( basename( $plugin_file ) === 'wordpress-importer.php' ) {
                    $wp_importer_dir = WP_PLUGIN_DIR . '/' . dirname( $plugin_file );
                    break;
                }
            }
        }

        if ( ! is_dir( $wp_importer_dir ) ) {
            wp_delete_file( $tmp_file );
            return false;
        }

        // Load compat shims
        if ( file_exists( $wp_importer_dir . '/compat.php' ) ) {
            require_once $wp_importer_dir . '/compat.php';
        }

        // Load the php-toolkit autoloader (provides WordPress\DataLiberation\URL\WPURL etc.)
        if ( ! class_exists( 'WordPress\\XML\\XMLProcessor' ) && file_exists( $wp_importer_dir . '/php-toolkit/load.php' ) ) {
            require_once $wp_importer_dir . '/php-toolkit/load.php';
        }

        // Load all XML parsers
        $parsers_dir = $wp_importer_dir . '/parsers';
        if ( is_dir( $parsers_dir ) ) {
            foreach ( array(
                'class-wxr-parser.php',
                'class-wxr-parser-simplexml.php',
                'class-wxr-parser-xml.php',
                'class-wxr-parser-regex.php',
                'class-wxr-parser-xml-processor.php',
            ) as $parser_file ) {
                $path = $parsers_dir . '/' . $parser_file;
                if ( file_exists( $path ) ) {
                    require_once $path;
                }
            }
        }

        // Load WP_Import class
        if ( ! class_exists( 'WP_Import' ) ) {
            $class_file = $wp_importer_dir . '/class-wp-import.php';
            if ( file_exists( $class_file ) ) {
                require_once $class_file;
            }
        }

        if ( ! class_exists( 'WP_Import' ) ) {
            wp_delete_file( $tmp_file );
            return false;
        }


        $importer = new \WP_Import();
        // Disable attachment fetching to prevent Apache timeout.
        // Starter templates use Elementor data where media URLs are embedded.
        // Downloading 200+ remote media files over AJAX causes fatal Apache timeouts.
        $importer->fetch_attachments = false;
        // Initialize options to prevent "Undefined array key rewrite_urls" warnings
        // when bypassing the normal import() entry point.
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
        $importer->options = apply_filters( 'wp_import_options', array( 'rewrite_urls' => false ) );

        // -------------------------------------------------------
        // KEY FIX: Do NOT call $importer->import() directly.
        // That method calls import_start() which calls die() on
        // ANY parse error — killing the entire AJAX process on
        // Apache and causing the 500 Internal Server Error.
        // Instead, we call parse() and process steps manually
        // so we can handle errors gracefully.
        // -------------------------------------------------------

        // 1. Parse the WXR file
        ob_start();
        $import_data = $importer->parse( $tmp_file );
        ob_end_clean();

        if ( is_wp_error( $import_data ) ) {
            wp_delete_file( $tmp_file );
            return false;
        }

        // 2. Populate importer with parsed data
        $importer->get_authors_from_import( $import_data );
        $importer->posts      = $import_data['posts'] ?? array();
        $importer->terms      = $import_data['terms'] ?? array();
        $importer->categories = $import_data['categories'] ?? array();
        $importer->tags       = $import_data['tags'] ?? array();
        $importer->base_url   = esc_url( $import_data['base_url'] ?? '' );

        // 3. Process all data — these methods never call die()
        wp_suspend_cache_invalidation( true );
        wp_defer_term_counting( true );
        wp_defer_comment_counting( true );

        ob_start();
        $importer->process_categories();
        $importer->process_tags();
        $importer->process_terms();
        $importer->process_posts();
        ob_end_clean();

        wp_suspend_cache_invalidation( false );
        wp_defer_term_counting( false );
        wp_defer_comment_counting( false );

        // 4. Backfill and cleanup (safe methods, no die())
        if ( method_exists( $importer, 'backfill_parents' ) ) {
            $importer->backfill_parents();
        }
        if ( method_exists( $importer, 'backfill_attachment_urls' ) ) {
            $importer->backfill_attachment_urls();
        }
        if ( method_exists( $importer, 'remap_featured_images' ) ) {
            $importer->remap_featured_images();
        }

        wp_cache_flush();
        wp_delete_file( $tmp_file );
        return true;
    }

    private function clear_elementor_cache() {
        if ( class_exists( '\Elementor\Plugin' ) ) {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
        }
    }

    private function process_kit_import( $url ) {
        if ( ! did_action( 'elementor/loaded' ) ) {
             wp_send_json_error( array( 'message' => __( 'Elementor is not loaded.', 'easy-elements' ) ) );
        }

        $tmp_file = download_url( $url );

        if ( is_wp_error( $tmp_file ) ) {
            wp_send_json_error( array( 'message' => $tmp_file->get_error_message() ) );
        }

        try {
            $import_export_module = \Elementor\Plugin::$instance->app->get_component( 'import-export' );
            
            // 1. Upload Kit
            $upload_result = $import_export_module->upload_kit( $tmp_file, 'local' );
            
            $session_id = $upload_result['session'];
            
            // 2. Import Kit (default settings include everything)
            $import_result = $import_export_module->import_kit( $session_id, [], false );

            wp_delete_file( $tmp_file );

            $this->assign_menu();
            $this->clear_elementor_cache();
            
            return true;

        } catch ( \Exception $e ) {
            wp_delete_file( $tmp_file );
            return false;
        } catch ( \Error $e ) {
            wp_delete_file( $tmp_file );
            return false;
        }
    }

    private function repair_kit_zip( $zip_file ) {
        if ( ! class_exists( 'ZipArchive' ) ) {
            return;
        }

        $zip = new \ZipArchive();
        if ( $zip->open( $zip_file ) === true ) {
            
            for( $i = 0; $i < $zip->numFiles; $i++ ) {
                $filename = $zip->getNameIndex( $i );
                
                // Check if it is a JSON file in ee-elementor-hf
                if ( strpos( $filename, 'content/ee-elementor-hf/' ) !== false && substr( $filename, -5 ) === '.json' ) {
                    $json = $zip->getFromIndex( $i );
                    $data = json_decode( $json, true );

                    if ( isset( $data['metadata'] ) ) {
                        $changed = false;
                        $meta_keys = [ 'ehf_target_include_locations', 'ehf_target_exclude_locations', 'ehf_target_user_roles' ];
                        
                        foreach ( $meta_keys as $key ) {
                            if ( isset( $data['metadata'][$key] ) && is_string( $data['metadata'][$key] ) ) {
                                // Attempt to unserialize
                                $unserialized = @unserialize( $data['metadata'][$key] );
                                if ( $unserialized !== false || $data['metadata'][$key] === 'b:0;' ) {
                                     $data['metadata'][$key] = $unserialized;
                                     $changed = true;
                                }
                            }
                        }

                        if ( $changed ) {
                            $zip->addFromString( $filename, json_encode( $data ) );
                        }
                    }
                }
            }
            $zip->close();
        }
    }

	// Import settings
    public function process_easyel_settings_import($file) {

		if($file === false) {
			return;
		}
        $content = file_get_contents($file);
        $settings = json_decode($content, true);

        // Save all imported settings
		if ( ! is_array($settings) ) {
            return;
        }

        foreach ($settings as $option_name => $option_value) {
            update_option($option_name, $option_value);
        }

		return true;
    }

	// Import form
    public function process_form_import( $url ) {

		if ( empty( $url ) ) {
			return false;
		}

		if ( ! class_exists( 'BoldForm_Lite_Activator' ) ) {
			return false;
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';

		$tmp_file = download_url( $url );
		if ( is_wp_error( $tmp_file ) ) {
			return false;
		}

		$content = file_get_contents( $tmp_file );
		wp_delete_file( $tmp_file );

		if ( empty( $content ) ) {
			return false;
		}

		$data = json_decode( $content, true );

		if ( ! is_array( $data ) || empty( $data['plugin'] ) || 'boldform-lite' !== $data['plugin'] ) {
			return false;
		}

		\BoldForm_Lite_Activator::activate();

		if ( ! class_exists( 'BoldForm_Lite' ) || ! class_exists( 'BoldForm_Lite_Export_Import' ) ) {
			return false;
		}

		$export_import = \BoldForm_Lite::get_instance()->get_export_import();

		if ( ! $export_import instanceof \BoldForm_Lite_Export_Import ) {
			return false;
		}

		try {
			$method = new \ReflectionMethod( $export_import, 'import_data' );
			$method->setAccessible( true );
			$method->invoke( $export_import, $data );
		} catch ( \ReflectionException $e ) {
			return false;
		}

		return true;
    }
	
	private function assign_menu() {
        $locations = get_theme_mod( 'nav_menu_locations' );
        $menus     = wp_get_nav_menus();

        if ( ! empty( $menus ) ) {
            foreach ( $menus as $menu ) {
                if ( is_object( $menu ) && ! empty( $menu->term_id ) ) {
                    if( empty($locations) ) $locations = array();
                    
                   // Get all registered locations
                   $registered_locations = get_registered_nav_menus();
                   
                   foreach($registered_locations as $location_slug => $location_name) {
                       // Try to match 'primary', 'main', 'header'
                       if( stripos($location_slug, 'primary') !== false || stripos($location_slug, 'main') !== false || stripos($location_slug, 'header') !== false ) {
                            if( !isset($locations[$location_slug]) || $locations[$location_slug] == 0 ) {
                                $locations[$location_slug] = $menu->term_id;
                            }
                       }
                   }
                   
                   // Fallback for 'menu-1' (Hello Elementor)
                   if( isset($registered_locations['menu-1']) && (!isset($locations['menu-1']) || $locations['menu-1'] == 0) ) {
                       $locations['menu-1'] = $menu->term_id;
                   }
                }
            }
            set_theme_mod( 'nav_menu_locations', $locations );
        }
    }

	// Assign default homepage
	public function assign_default_homepage($page_title) {

		if($page_title === false || $page_title === '') {
			return;
		}

		$page = null;
		$query = new \WP_Query( array(
			'post_type'              => 'page',
			'title'                  => $page_title,
			'post_status'            => 'all',
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'orderby' => array(
				'date' => 'ASC',
				'ID'   => 'ASC'
			),
			'order'                  => 'ASC',
		) );
		
		if ( ! empty( $query->post ) ) {
			$page = $query->post;
		}

		if($page) {
			update_option('page_on_front', $page->ID);
			update_option('show_on_front', 'page');
		}

		$blog_page = null;
		$blog_query = new \WP_Query( array(
			'post_type'              => 'page',
			'title'                  => 'blog',
			'post_status'            => 'all',
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'orderby' => array(
				'date' => 'ASC',
				'ID'   => 'ASC'
			),
			'order'                  => 'ASC',
		) );

		if ( ! empty( $blog_query->post ) ) {
			$blog_page = $blog_query->post;
		}

		if($blog_page) {
			update_option('page_for_posts', $blog_page->ID);
		}
	}

}
