<?php
namespace Easyel\EasyElements\Starter_Template;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Easyel_Starter_Template {

	private $required_plugins;
	private $recommeneded_theme;
	protected static $instance = null;

	const API_ENDPOINT   = 'https://wpeasyelements.com/demotemplate/wp-json/rtTemplates/v1/starter-templates?package=free';
	const TRANSIENT_KEY  = 'easyel_starter_templates_cache_v3';

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'templates_css' ) );
		add_action( 'in_admin_header', array( $this, 'suppress_admin_notices' ), 1 );
		add_filter( 'admin_body_class', array( $this, 'add_body_class' ) );

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
		if ( $this->is_starter_screen() && $this->is_elementor_active() ) {
			$classes .= ' easyel-starter-fullscreen';
		}
		return $classes;
	}

	private function is_starter_screen() {
		$screen = get_current_screen();
		return $screen && 'easy-elements_page_starter-templates-dashboard' === $screen->id;
	}

	private function is_elementor_active() {
		if ( did_action( 'elementor/loaded' ) ) {
			return true;
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( 'elementor/elementor.php' );
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

		if ( ! $this->is_elementor_active() ) {
			remove_submenu_page( 'easy-elements-dashboard', 'starter-templates-dashboard' );
		}
	}

	public function templates_css() {
		$screen = get_current_screen();

		if($screen->id !== 'easy-elements_page_starter-templates-dashboard') {
			return;
		}

		$css_path = EASYELEMENTS_DIR_PATH . 'includes/Starter_Template/assets/css/starter-templates.css';
		$css_ver  = file_exists( $css_path ) ? filemtime( $css_path ) : EASYELEMENTS_VER;

		wp_enqueue_style(
			'easy-elements-starter-templates-css',
			EASYELEMENTS_DIR_URL . 'includes/Starter_Template/assets/css/starter-templates.css',
			[],
			$css_ver
		);

		// Library JS only matters when Elementor is active; the notice page
		// is static and doesn't need it.
		if ( ! $this->is_elementor_active() ) {
			return;
		}

		$js_path = EASYELEMENTS_DIR_PATH . 'includes/Starter_Template/assets/js/starter-templates.js';
		$js_ver  = file_exists( $js_path ) ? filemtime( $js_path ) : EASYELEMENTS_VER;

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
		$js_config = [
			'previewBaseUrl' => 'https://wpeasyelements.com/demotemplate/',
			'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
			'nonce'          => wp_create_nonce( 'easyel_starter_templates_nonce' ),
			'proUrl'         => $this->get_pro_upgrade_url(),
		];

		wp_localize_script( 'easy-elements-starter-templates-js', 'easyElementsStarterTemplatesajax', $js_config );
	}

	public function import_files() {
		return $this->get_all();
	}

	/**
	 * Marketing URL shown for premium templates. The free plugin cannot import
	 * premium templates (it never receives their import data) — this link only
	 * tells users where the premium add-on is available. Filterable so the Pro
	 * add-on or site owners can point it elsewhere.
	 */
	public function get_pro_upgrade_url() {
		return apply_filters( 'easyel_starter_templates_pro_url', 'https://wpeasyelements.com/pricing/' );
	}

	/**
	 * External URL for the "More Premium Templates" button. The free plugin
	 * only ships free templates; this link points users to the full premium
	 * collection on the product website. Filterable.
	 */
	public function get_premium_templates_url() {
		return apply_filters( 'easyel_premium_templates_url', 'https://wpeasyelements.com/demotemplate/templates/' );
	}

	/**
	 * Public normalization helper for add-ons.
	 *
	 * The Easy Elements Pro add-on uses this to shape its own (premium)
	 * template data into the exact structure the library expects, so premium
	 * templates it injects via the `easyel_starter_templates` filter render
	 * and filter just like the bundled free ones. This is pure data
	 * transformation — it does no fetching, importing, or access control.
	 *
	 * @param array $raw_items Raw template items (API shape: title, xml, kit, …).
	 * @param array $api_tree  Raw categories tree, used for category aliasing.
	 * @return array Normalized template entries.
	 */
	public function build_template_entries( array $raw_items, array $api_tree = array() ) {
		$alias_map = $this->build_child_alias_map( $api_tree );
		return $this->normalize_api_data( $raw_items, $alias_map );
	}

	public function get_all() {
		$payload = $this->get_cached_payload();
		$all     = is_array( $payload['templates'] ) ? $payload['templates'] : array();

		return apply_filters( 'easyel_starter_templates', $all );
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

		// The free plugin fetches and shows ONLY free templates (the endpoint is
		// already scoped with ?package=free). It has no awareness of premium
		// templates at all. The Easy Elements Pro add-on (distributed separately)
		// extends this page — it injects its own premium templates into the
		// library via the `easyel_starter_templates` filter and owns everything
		// premium-related (the type filter, badges, import).
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
				'id'                              => isset( $item['id'] ) ? (string) $item['id'] : '',
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

	/**
	 * Drop categories the current library has no templates for.
	 *
	 * The API returns the full category tree (every category that exists across
	 * the whole template collection), but a given install only has a subset of
	 * templates — the free bundle alone, or free + whatever the Easy Elements
	 * Pro add-on injects via the `easyel_starter_templates` filter. We collect
	 * every category slug present on the available templates and keep only the
	 * tree entries that match: leaf categories whose slug is available, and
	 * parent categories that still have at least one available child. This is
	 * why the filter "grows" automatically when Pro is active — Pro's templates
	 * carry the previously-empty category slugs, so they stop being filtered out.
	 *
	 * Some templates are tagged with a broad top-level category that has no
	 * sub-category of its own with templates (e.g. "Construction"). Rather than
	 * surface it as a separate top-level chip, we relocate it as a sub-item under
	 * a more appropriate parent group (Construction → Business). The mapping is
	 * filterable via `easyel_starter_templates_category_reparent`.
	 *
	 * @param array $tree      Normalized category tree (see normalize_api_tree()).
	 * @param array $templates Normalized templates currently in the library.
	 * @return array Tree containing only categories that have templates.
	 */
	private function filter_tree_to_available( array $tree, array $templates ) {
		if ( empty( $tree ) || empty( $templates ) ) {
			return array();
		}

		$available = array();
		foreach ( $templates as $template ) {
			foreach ( (array) ( $template['categories'] ?? array() ) as $slug ) {
				if ( '' !== $slug ) {
					$available[ $slug ] = true;
				}
			}
		}

		// orphan top-level category slug => target parent group slug it should
		// be nested under as a sub-item.
		$reparent = apply_filters(
			'easyel_starter_templates_category_reparent',
			array( 'construction' => 'business' )
		);

		$out           = array();
		$index_by_slug = array();   // slug => position in $out (for child injection)
		$orphans       = array();   // target parent slug => [ child entry, ... ]

		foreach ( $tree as $cat ) {
			$slug = $cat['slug'];

			if ( ! empty( $cat['children'] ) ) {
				$children = array();
				foreach ( $cat['children'] as $child ) {
					if ( isset( $available[ $child['slug'] ] ) ) {
						$children[] = $child;
					}
				}
				if ( ! empty( $children ) ) {
					// At least one sub-category has templates: keep the group.
					// When templates are tagged directly on the broad parent
					// (e.g. just "business"), prepend the parent itself as the
					// FIRST selectable sub-item so users can filter by it on its
					// own — not only via the group header's "select all".
					if ( isset( $available[ $slug ] ) ) {
						array_unshift( $children, array(
							'slug'  => $slug,
							'label' => $cat['label'],
							'count' => isset( $cat['count'] ) ? (int) $cat['count'] : 0,
						) );
					}
					$cat['children']        = $children;
					$index_by_slug[ $slug ] = count( $out );
					$out[]                  = $cat;
				} elseif ( isset( $available[ $cat['slug'] ] ) ) {
					// No sub-category has templates, but templates ARE tagged with
					// the broad parent itself (e.g. "Construction"). Relocate it as
					// a sub-item under its mapped parent group instead of showing a
					// standalone chip or an empty dropdown.
					$this->collect_or_keep_orphan( $cat, $reparent, $orphans, $out, $index_by_slug );
				}
			} elseif ( isset( $available[ $cat['slug'] ] ) ) {
				// Standalone leaf category. Honour the reparent map too, so a
				// configured leaf can also be nested under a group.
				if ( isset( $reparent[ $slug ] ) ) {
					$this->collect_or_keep_orphan( $cat, $reparent, $orphans, $out, $index_by_slug );
				} else {
					$index_by_slug[ $slug ] = count( $out );
					$out[]                  = $cat;
				}
			}
		}

		// Inject relocated orphans into their target groups (or fall back to a
		// standalone chip when the target group isn't present in this library).
		foreach ( $orphans as $target => $kids ) {
			if ( isset( $index_by_slug[ $target ] ) ) {
				$pos = $index_by_slug[ $target ];
				foreach ( $kids as $kid ) {
					$out[ $pos ]['children'][] = $kid;
				}
			} else {
				foreach ( $kids as $kid ) {
					$out[] = array(
						'slug'     => $kid['slug'],
						'label'    => $kid['label'],
						'count'    => $kid['count'],
						'children' => array(),
					);
				}
			}
		}

		return $out;
	}

	/**
	 * Queue an orphan category for relocation under its mapped parent group, or
	 * keep it as a standalone chip when it has no mapping. Used by
	 * filter_tree_to_available() for both broad parents with no available
	 * sub-category and configured leaf categories.
	 *
	 * @param array $cat           The category entry (slug, label, count).
	 * @param array $reparent      orphan slug => target parent slug map.
	 * @param array $orphans       Accumulator: target slug => [ child entries ].
	 * @param array $out           Output tree (modified when kept standalone).
	 * @param array $index_by_slug slug => position in $out (kept in sync).
	 */
	private function collect_or_keep_orphan( array $cat, array $reparent, array &$orphans, array &$out, array &$index_by_slug ) {
		$slug  = $cat['slug'];
		$child = array(
			'slug'  => $slug,
			'label' => $cat['label'],
			'count' => isset( $cat['count'] ) ? (int) $cat['count'] : 0,
		);

		if ( isset( $reparent[ $slug ] ) ) {
			$orphans[ $reparent[ $slug ] ][] = $child;
			return;
		}

		// No mapping: keep it reachable as a single selectable chip.
		$index_by_slug[ $slug ] = count( $out );
		$out[]                  = array(
			'slug'     => $slug,
			'label'    => $cat['label'],
			'count'    => $child['count'],
			'children' => array(),
		);
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
		);
	}

	/**
	 * Locate the wordpress-importer plugin file (if installed) and report
	 * whether it is currently active. We never install or activate it
	 * ourselves — the user must do that explicitly from the Required
	 * Plugins step in the modal.
	 */
	public static function get_wordpress_importer_status() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$slug        = 'wordpress-importer';
		$plugin_file = 'wordpress-importer/wordpress-importer.php';
		$installed   = file_exists( WP_PLUGIN_DIR . '/' . $plugin_file );

		if ( ! $installed ) {
			foreach ( array_keys( get_plugins() ) as $ph ) {
				if ( dirname( $ph ) === $slug || basename( $ph ) === 'wordpress-importer.php' ) {
					$plugin_file = $ph;
					$installed   = true;
					break;
				}
			}
		}

		return array(
			'installed'   => $installed,
			'active'      => $installed ? is_plugin_active( $plugin_file ) : false,
			'plugin_file' => $plugin_file,
		);
	}

	/**
	 * Rendered in place of the Starter Templates library when the Elementor
	 * plugin is not installed/activated. Replaces WordPress's default
	 * "Sorry, you are not allowed to access this page." with a friendly,
	 * actionable notice that mirrors the Animation Addons style.
	 */
	private function render_elementor_required_page() {
		$elementor_file = 'elementor/elementor.php';
		$is_installed   = file_exists( WP_PLUGIN_DIR . '/' . $elementor_file );

		if ( $is_installed ) {
			$action_url = wp_nonce_url(
				self_admin_url( 'plugins.php?action=activate&plugin=' . $elementor_file ),
				'activate-plugin_' . $elementor_file
			);
			$button_label = esc_html__( 'Activate', 'easy-elements' );
		} else {
			$action_url = wp_nonce_url(
				self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ),
				'install-plugin_elementor'
			);
			$button_label = esc_html__( 'Install Elementor', 'easy-elements' );
		}

		$dashboard_url = admin_url( 'admin.php?page=easy-elements-dashboard' );
		?>
		<div class="wrap easyel-elementor-required-wrap">
			<div class="easyel-elementor-required-notice" role="alert">
				<span class="easyel-elementor-required-notice__icon" aria-hidden="true">!</span>
				<div class="easyel-elementor-required-notice__text">
					<?php
					printf(
						/* translators: 1: Plugin feature name, 2: Required plugin name */
						esc_html__( '%1$s requires %2$s plugin to be installed and activated.', 'easy-elements' ),
						'<strong>' . esc_html__( 'Easy Elements Starter Templates', 'easy-elements' ) . '</strong>',
						'<strong>' . esc_html__( 'Elementor', 'easy-elements' ) . '</strong>'
					);
					?>
				</div>
				<a href="<?php echo esc_url( $action_url ); ?>" class="easyel-elementor-required-notice__button">
					<span class="dashicons dashicons-admin-network" aria-hidden="true"></span>
					<?php echo esc_html( $button_label ); ?>
				</a>
			</div>

			<div class="easyel-elementor-required-body">
				<h2><?php esc_html_e( 'Elementor is required for Starter Templates', 'easy-elements' ); ?></h2>
				<p>
					<?php esc_html_e( 'The Easy Elements Starter Templates library lets you import full ready-made websites in one click. To browse and import any template, you need the Elementor plugin installed and activated first.', 'easy-elements' ); ?>
				</p>
				<p>
					<?php
					if ( $is_installed ) {
						esc_html_e( 'Elementor is already installed — just activate it using the button above to continue.', 'easy-elements' );
					} else {
						esc_html_e( 'Click "Install Elementor" above to install the free Elementor plugin from WordPress.org.', 'easy-elements' );
					}
					?>
				</p>
				<a href="<?php echo esc_url( $dashboard_url ); ?>" class="easyel-back-link">
					&larr; <?php esc_html_e( 'Back to Easy Elements Dashboard', 'easy-elements' ); ?>
				</a>
			</div>
		</div>
		<?php
	}

	// Starter Templates Page HTML
	public function templates_page_html() {

		if ( ! $this->is_elementor_active() ) {
			$this->render_elementor_required_page();
			return;
		}

		$templates = $this->import_files();

		$pro_upgrade_url       = $this->get_pro_upgrade_url();
		$premium_templates_url = $this->get_premium_templates_url();

		// Show only categories that actually have templates in the library.
		// $templates already reflects what is available: the free plugin ships
		// only free templates, while the Easy Elements Pro add-on injects its
		// premium templates via the `easyel_starter_templates` filter. So when
		// Pro is active its categories light up automatically; without it, empty
		// categories (free templates we don't have) are hidden from the filter.
		$category_tree = $this->filter_tree_to_available( $this->get_category_tree(), $templates );

		$is_activated_plugins = true;

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

					<?php
					// Premium area. By default the free plugin only invites users to
					// explore the premium collection on the product site. The Easy
					// Elements Pro add-on replaces this with the All/Free/Pro type
					// filter (and injects its premium templates into the grid).
					$more_premium_btn = '<a href="' . esc_url( $premium_templates_url ) . '" target="_blank" rel="noopener noreferrer" class="easyel-more-premium-btn">'
						. '<span class="dashicons dashicons-star-filled" aria-hidden="true"></span>'
						. '<span>' . esc_html__( 'More Premium Templates', 'easy-elements' ) . '</span></a>';
					echo apply_filters( 'easyel_starter_templates_topbar_premium', $more_premium_btn ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted markup / add-on HTML.
					?>
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
						$template_id   = isset( $template['id'] ) ? (string) $template['id'] : '';
						// Generic type tag used by the topbar filter (default "free").
						// Add-ons that inject their own templates set this themselves.
						$template_type = ! empty( $template['type'] ) ? sanitize_html_class( $template['type'] ) : 'free';
						// Importable only when import data is actually present.
						$importable = ! empty( $import_file_url );

						// Preview-button data attributes. The Pro add-on can enrich these
						// (e.g. make a licensed premium template importable straight from
						// the preview modal) via the filter below. The free plugin never
						// sets a premium import action/nonce — those live in Pro.
						$preview_attrs = array(
							'preview-url'                     => $preview_url,
							'template-name'                   => $title,
							'template-id'                     => $template_id,
							'import-file-url'                 => $import_file_url,
							'import-file-kit-url'             => $import_file_kit_url,
							'import-file-easyel-settings-url' => $import_file_easyel_settings_url,
							'import-file-form-url'            => $import_file_form_url,
							'importable'                      => $importable ? 'true' : 'false',
							'coming-soon'                     => 'false',
							'pro-url'                         => $pro_upgrade_url,
							'default-homepage'                => $default_homepage,
							'import-action'                   => '',
							'import-nonce'                    => '',
							// Optional label for the non-importable preview CTA. Add-ons
							// can override it (e.g. "Activate License") to match the URL.
							'cta-label'                       => '',
						);
						$preview_attrs = apply_filters( 'easyel_starter_template_preview_attrs', $preview_attrs, $template );
						?>
						<div class="easyel-item" data-category="<?php echo esc_attr( $template_cats ) ?>" data-title="<?php echo esc_attr( $title ) ?>" data-type="<?php echo esc_attr( $template_type ); ?>">
							<div class="easyel-card">
								<div class="easyel-thumb">
									<img src="<?php echo esc_url($thumb_url) ?>" class="easyel-thumbnail"/>
									<?php
									// Extension point: add-ons can place a ribbon/badge on a
									// card (e.g. Easy Elements Pro tags its templates "PRO").
									echo apply_filters( 'easyel_starter_template_badge_html', '', $template ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted add-on HTML.
									?>
									<button class="easyel-fav-btn" data-template-name="<?php echo esc_attr( $title ) ?>" title="<?php echo esc_attr__( 'Add to Favourites', 'easy-elements' ); ?>">
										<span class="dashicons dashicons-heart"></span>
									</button>
									<div class="easyel-overlay">
										<div class="easyel-action-btns">
											<button class="easyel-preview-btn"<?php foreach ( $preview_attrs as $attr_key => $attr_val ) { echo ' data-' . esc_attr( $attr_key ) . '="' . esc_attr( $attr_val ) . '"'; } ?>>
												<span class="dashicons dashicons-visibility"></span> <?php echo esc_html__( 'Preview', 'easy-elements' ); ?>
											</button>
											<?php if ( $importable ) : ?>
											<a href="#"
												data-import-file-url="<?php echo esc_url( $import_file_url )?>"
												data-import-file-kit-url="<?php echo esc_url( $import_file_kit_url )?>"
												data-import-file-easyel-settings-url="<?php echo esc_url( $import_file_easyel_settings_url )?>"
												data-import-file-form-url="<?php echo esc_url( $import_file_form_url )?>"
												data-default-homepage="<?php echo esc_attr( $default_homepage )?>"
												class="easyel-import-btn"><span class="dashicons dashicons-download"></span> <?php echo esc_html__( 'Import', 'easy-elements' ); ?></a>
											<?php endif; ?>
											<?php
											// Extension point: add-ons (Easy Elements Pro) append their
											// own CTA — e.g. a license-gated import button — for the
											// templates they inject. Free templates need nothing here.
											echo apply_filters( 'easyel_starter_template_extra_cta_html', '', $template ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted add-on HTML.
											?>
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

                                                   
                                                    $plugin_file = '';
                                                    $is_active = false;
                                                    
                                                    foreach ( array_keys( $all_plugins ) as $ph ) {
                                                        if ( dirname( $ph ) === $plugin['slug'] ) {
                                                            $plugin_file = $ph;
                                                            break;
                                                        }
                                                    }
                                                    

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
													<li class="easyel-plugin-item" data-slug="<?php echo esc_attr($plugin['slug']); ?>" data-installed="<?php echo !empty($plugin_file) ? 'true' : 'false'; ?>" data-active="<?php echo $is_active ? 'true' : 'false'; ?>">
														<span class="easyel-importer-step-dot"></span>

														<?php echo esc_html( $plugin['name'] ); ?>

														<?php if ( $is_active ) : ?>
															<span class="easyel-importer-step-status active"><?php echo esc_html__( 'Active', 'easy-elements' ); ?></span>
														<?php elseif ( empty( $plugin_file ) ) : ?>
															<span class="easyel-importer-step-status inactive"><?php echo esc_html__( 'Not Installed', 'easy-elements' ); ?></span>
															<button type="button" class="easyel-plugin-activate-btn"><?php echo esc_html__( 'Install', 'easy-elements' ); ?></button>
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
											<li class="easyel-theme-item" data-slug="<?php echo esc_attr( $this->recommeneded_theme['slug'] ); ?>" data-installed="<?php echo $is_theme_installed ? 'true' : 'false'; ?>" data-active="<?php echo $is_theme_active ? 'true' : 'false'; ?>">
												<input type="checkbox" name="recommened-theme" value="<?php echo esc_attr( $this->recommeneded_theme['slug'] )?>" data-installed="<?php echo $is_theme_installed ? 'true' : 'false'; ?>" <?php checked( $is_theme_active ); ?>>
												<?php echo esc_html( $this->recommeneded_theme['name'] ); ?>
                                                <?php if ( $is_theme_active ) : ?>
												    <span class="easyel-importer-step-status active"><?php echo esc_html__( 'Active', 'easy-elements' ); ?></span>
                                                <?php elseif ( ! $is_theme_installed ) : ?>
                                                    <span class="easyel-importer-step-status inactive"><?php echo esc_html__( 'Not Installed', 'easy-elements' ); ?></span>
                                                    <button type="button" class="easyel-theme-activate-btn"><?php echo esc_html__( 'Install', 'easy-elements' ); ?></button>
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

        if ( empty( $slug ) ) {
            wp_send_json_error( array( 'message' => __( 'Plugin slug is missing.', 'easy-elements' ) ) );
        }

        // Only allow installing required plugins from the official WordPress.org
        // directory. We never accept an arbitrary ZIP URL from the client.
        $allowed_slugs = wp_list_pluck( $this->required_plugins, 'slug' );
        if ( ! in_array( $slug, $allowed_slugs, true ) ) {
            wp_send_json_error( array( 'message' => __( 'Plugin is not in the allowed list.', 'easy-elements' ) ) );
        }

        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

        $skin = new \WP_Ajax_Upgrader_Skin();
        $upgrader = new \Plugin_Upgrader( $skin );

        $api = plugins_api( 'plugin_information', array( 'slug' => $slug, 'fields' => array( 'sections' => false ) ) );
        if ( is_wp_error( $api ) ) {
            wp_send_json_error( array( 'message' => $api->get_error_message() ) );
        }
        $result = $upgrader->install( $api->download_link );

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

        if ( empty( $slug ) ) {
            wp_send_json_error( array( 'message' => __( 'Theme slug is missing.', 'easy-elements' ) ) );
        }

        // Only allow installing the recommended theme from the WordPress.org
        // directory. We never accept an arbitrary ZIP URL from the client.
        if ( empty( $this->recommeneded_theme['slug'] ) || $slug !== $this->recommeneded_theme['slug'] ) {
            wp_send_json_error( array( 'message' => __( 'Theme is not in the allowed list.', 'easy-elements' ) ) );
        }

        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/theme.php';

        $skin = new \WP_Ajax_Upgrader_Skin();
        $upgrader = new \Theme_Upgrader( $skin );

        $api = themes_api( 'theme_information', array( 'slug' => $slug, 'fields' => array( 'sections' => false ) ) );
        if ( is_wp_error( $api ) ) {
            wp_send_json_error( array( 'message' => $api->get_error_message() ) );
        }
        $result = $upgrader->install( $api->download_link );

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

        // Only allow activating the explicitly recommended theme. We never
        // accept an arbitrary slug from the client — that would let an
        // authenticated request switch the site theme to any installed theme.
        if ( empty( $this->recommeneded_theme['slug'] ) || $slug !== $this->recommeneded_theme['slug'] ) {
            wp_send_json_error( array( 'message' => __( 'Theme is not in the allowed list.', 'easy-elements' ) ) );
        }

        $theme = wp_get_theme( $slug );
        if ( ! $theme->exists() ) {
            wp_send_json_error( array( 'message' => __( 'Theme is not installed.', 'easy-elements' ) ) );
        }

        switch_theme( $slug );

        wp_send_json_success( array( 'message' => __( 'Theme activated successfully.', 'easy-elements' ) ) );
    }

    public function import_content() {
        check_ajax_referer( 'easyel_starter_templates_nonce', 'security' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'easy-elements' ) ) );
        }

        $args = array(
            'import_file_url'                 => isset( $_POST['import_file_url'] ) ? esc_url_raw( wp_unslash( $_POST['import_file_url'] ) ) : '',
            'import_file_kit_url'             => isset( $_POST['import_file_kit_url'] ) ? esc_url_raw( wp_unslash( $_POST['import_file_kit_url'] ) ) : '',
            'import_file_easyel_settings_url' => isset( $_POST['import_file_easyel_settings_url'] ) ? esc_url_raw( wp_unslash( $_POST['import_file_easyel_settings_url'] ) ) : '',
            'import_file_form_url'            => isset( $_POST['import_file_form_url'] ) ? esc_url_raw( wp_unslash( $_POST['import_file_form_url'] ) ) : '',
            'default_homepage'                => isset( $_POST['default_homepage'] ) ? sanitize_text_field( wp_unslash( $_POST['default_homepage'] ) ) : '',
        );

        $result = $this->run_import( $args );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array(
                'message' => $result->get_error_message(),
                'context' => $result->get_error_code(),
            ) );
        }

        if ( true === $result ) {
            wp_send_json_success( array( 'message' => __( 'Content imported successfully.', 'easy-elements' ) ) );
        }

        wp_send_json_error( array( 'message' => __( 'Content import failed.', 'easy-elements' ) ) );
    }

    /**
     * Neutral, reusable import engine. Downloads and processes the four demo
     * files (XML content, Elementor kit, Easy Elements settings, BoldForm form)
     * for ANY template, then assigns the menu/homepage and clears caches.
     *
     * This method contains NO access control, NO nonce check and NO license
     * logic — callers (the free `import_content()` AJAX, and the Easy Elements
     * Pro add-on's own license-gated AJAX endpoint) are responsible for those.
     * It returns a bool result and sends no JSON, so it is safe to reuse.
     *
     * @param array $args import_file_url, import_file_kit_url,
     *                     import_file_easyel_settings_url, import_file_form_url,
     *                     default_homepage.
     * @return bool|\WP_Error true on success, false on import failure, or a
     *                        WP_Error for missing args / inactive WP Importer.
     */
    public function run_import( array $args ) {
        $import_file_url                 = $args['import_file_url'] ?? '';
        $import_file_kit_url             = $args['import_file_kit_url'] ?? '';
        $import_file_easyel_settings_url = $args['import_file_easyel_settings_url'] ?? '';
        $import_file_form_url            = $args['import_file_form_url'] ?? '';
        $default_homepage                = $args['default_homepage'] ?? '';

        if ( empty( $import_file_url ) ) {
            return new \WP_Error( 'missing_import_url', __( 'Import file URL is missing.', 'easy-elements' ) );
        }
        if ( empty( $import_file_kit_url ) ) {
            return new \WP_Error( 'missing_kit_url', __( 'Import kit file URL is missing.', 'easy-elements' ) );
        }
        if ( empty( $import_file_easyel_settings_url ) ) {
            return new \WP_Error( 'missing_settings_url', __( 'Import Easy Elements settings file URL is missing.', 'easy-elements' ) );
        }
        if ( empty( $import_file_form_url ) ) {
            return new \WP_Error( 'missing_form_url', __( 'Import form file URL is missing.', 'easy-elements' ) );
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

        // The WordPress Importer plugin must already be installed and active.
        // We never install or activate it ourselves — the user does that
        // explicitly via the Required Plugins step in the modal.
        $wp_importer_status = self::get_wordpress_importer_status();
        if ( ! $wp_importer_status['active'] ) {
            $this->restore_import_counting();
            if ( ob_get_level() > 0 ) {
                ob_end_clean();
            }
            return new \WP_Error(
                'wordpress_importer_inactive',
                __( 'The WordPress Importer plugin must be installed and activated before importing. Please activate it from the Required Plugins list and try again.', 'easy-elements' )
            );
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        // import core plugin settings
        $easyel_settings_imported = $this->process_easyel_settings_import( $import_file_easyel_settings_url );
        $kit_imported  = $this->process_kit_import( $import_file_kit_url );
        $xml_imported  = $this->process_xml_import( $import_file_url );
        $form_imported = $this->process_form_import( $import_file_form_url );

        $ok = ( $kit_imported && $xml_imported && $easyel_settings_imported && $form_imported );

        if ( $ok ) {
            $this->assign_menu();
            $this->assign_default_homepage( $default_homepage );
            $this->clear_elementor_cache();
        }

        $this->restore_import_counting();

        // Discard any buffered output (warnings, notices) to keep JSON response clean.
        if ( ob_get_level() > 0 ) {
            ob_end_clean();
        }

        return $ok;
    }

    /**
     * Re-enable cache invalidation and term/comment counting after an import.
     */
    private function restore_import_counting() {
        if ( function_exists( 'wp_suspend_cache_invalidation' ) ) {
            wp_suspend_cache_invalidation( false );
        }
        if ( function_exists( 'wp_defer_term_counting' ) ) {
            wp_defer_term_counting( false );
        }
        if ( function_exists( 'wp_defer_comment_counting' ) ) {
            wp_defer_comment_counting( false );
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

        require_once ABSPATH . 'wp-admin/includes/import.php';
        if ( ! class_exists( 'WP_Importer' ) ) {
            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            if ( file_exists( $class_wp_importer ) ) {
                require_once $class_wp_importer;
            }
        }

        $wp_importer_status = self::get_wordpress_importer_status();
        if ( ! $wp_importer_status['active'] ) {
            wp_delete_file( $tmp_file );
            return false;
        }

        $wp_importer_dir = WP_PLUGIN_DIR . '/' . dirname( $wp_importer_status['plugin_file'] );
        if ( ! is_dir( $wp_importer_dir ) ) {
            wp_delete_file( $tmp_file );
            return false;
        }

        // Load compat shims
        if ( file_exists( $wp_importer_dir . '/compat.php' ) ) {
            require_once $wp_importer_dir . '/compat.php';
        }

       
        if ( ! class_exists( 'WordPress\\XML\\XMLProcessor' ) && file_exists( $wp_importer_dir . '/php-toolkit/load.php' ) ) {
            require_once $wp_importer_dir . '/php-toolkit/load.php';
        }

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
        $importer->fetch_attachments = false;
     
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
        $importer->options = apply_filters( 'wp_import_options', array( 'rewrite_urls' => false ) );


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
        // Return false (rather than emitting JSON) so the neutral run_import()
        // engine can decide how to report failure to its caller.
        if ( ! did_action( 'elementor/loaded' ) ) {
             return false;
        }

        $tmp_file = download_url( $url );

        if ( is_wp_error( $tmp_file ) ) {
            return false;
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

		if ( empty( $file ) || $file === false ) {
			return;
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';

		$tmp_file = download_url( $file );
		if ( is_wp_error( $tmp_file ) ) {
			return;
		}

		$content = file_get_contents( $tmp_file );
		wp_delete_file( $tmp_file );

		if ( empty( $content ) ) {
			return;
		}

		$settings = json_decode( $content, true );

		if ( ! is_array( $settings ) ) {
			return;
		}

		$easyel_allowed_prefixes = [ 'easy_element_', 'easyel_' ];

		foreach ( $settings as $option_name => $option_value ) {
			if ( ! is_string( $option_name ) || '' === $option_name ) {
				continue;
			}

			$easyel_is_allowed = false;
			foreach ( $easyel_allowed_prefixes as $easyel_prefix ) {
				if ( 0 === strpos( $option_name, $easyel_prefix ) ) {
					$easyel_is_allowed = true;
					break;
				}
			}

			if ( ! $easyel_is_allowed ) {
				continue;
			}

			update_option( $option_name, $option_value );
		}

		return true;
    }

	// Import form
    public function process_form_import( $url ) {

		if ( empty( $url ) ) {
			return false;
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// BoldForm Lite must already be installed and activated by the user
		// (via the Required Plugins step). We never activate it here.
		if ( ! is_plugin_active( 'boldform-lite/boldform-lite.php' )
			|| ! class_exists( 'BoldForm_Lite' )
			|| ! class_exists( 'BoldForm_Lite_Export_Import' )
		) {
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
