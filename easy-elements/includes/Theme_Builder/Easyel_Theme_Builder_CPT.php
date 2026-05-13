<?php
namespace Easyel\EasyElements\Theme_Builder;
/**
 * Easy Theme Builder - Custom Post Type Registration (Singleton)
 */
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


class Easyel_Theme_Builder_CPT {

    /**
     * Holds the singular instance
     *
     * @var Easyel_Theme_Builder_CPT|null
     */
    private static $instance = null;

    const EASYEL_BUILDER_CPT = 'easy_theme_builder';
    const EASY_TAB_BASE      = 'edit.php?post_type=easy_theme_builder';

    /**
     * Get instance
     *
     * @return Easyel_Theme_Builder_CPT
     */
    public static function get_instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor is private to force singleton
     */
    private function __construct() {

        add_action( 'init', array( $this, 'easyel_theme_builder_post_type' ) );
        add_action( 'admin_menu', array( $this,  'easyel_theme_builder_templates_menu' ), 20 ) ;
        add_filter( 'views_edit-' . self::EASYEL_BUILDER_CPT, [ $this, 'easyel_builder_filter_markup'] );
        add_action('admin_footer', array( $this, "easyel_add_new_id_added_func") );

        add_action( 'admin_enqueue_scripts', [ $this, 'easy_builder_enqueue_assets' ] );

        add_action( 'current_screen', function () {
            $current_screen = get_current_screen();
            if ( ! $current_screen || ! strstr( $current_screen->post_type, 'easy_theme_builder' ) ) {
                return;
            }

            add_action( 'in_admin_footer', [ $this, 'easy_add_new_template_template'], 10, 2 );
        } );

        add_action('wp_ajax_easyel_save_template_conditions', [ $this, "easyel_ajax_save_builder" ] );

        add_filter( 'manage_easy_theme_builder_posts_columns', [ $this, "easy_theme_builder_posts_columns" ] );
        add_filter( 'manage_edit-easy_theme_builder_sortable_columns', [ $this, "easy_theme_builder_sortable_columns"] );
        add_action( 'manage_easy_theme_builder_posts_custom_column', [ $this, "easy_theme_builder_posts_custom_column"], 10, 2 );
        add_action( 'pre_get_posts', [ $this, 'easyel_filter_easy_theme_builder_by_type' ] );

        // archive 
        add_action('wp_ajax_easyel_get_archives', [ $this, "easyel_get_archives_func" ] ); 
        add_action('wp_ajax_easyel_get_singulars', [ $this, "easyel_get_singulars_func" ] ); 

        /* get builder ajax data..*/ 
        add_action('wp_ajax_easyel_get_builder', [ $this, 'easyel_ajax_get_builder' ] );

        add_action('wp_ajax_easyel_update_builder', [ $this, 'easyel_update_builder_callback'] );

        add_action('wp_ajax_easyel_get_child_sub_options', [ $this, 'easyel_get_child_sub_options' ] );

    }

    public function easyel_get_term_hierarchy_label( $term, $taxonomy ) {
        $label = $term->name;

        while ( $term->parent ) {
            $term = get_term( $term->parent, $taxonomy );
            if ( is_wp_error( $term ) || ! $term ) {
                break;
            }
            $label = $term->name . ' > ' . $label;
        }

        return $label;
    }

    function easyel_get_child_sub_options() {

        check_ajax_referer( 'easyel_ajax_nonce', 'nonce' );

        $type   = isset($_POST['type']) ? sanitize_text_field( wp_unslash($_POST['type']) ) : '';
        $search = isset($_POST['search']) ? sanitize_text_field( wp_unslash($_POST['search']) ) : '';

        $items = [];

        $child_options = easyel_theme_builder_child_options();
        $no_child_sub  = $child_options['no_child_sub'] ?? [];

        // Early exit if no child needed
        if ( in_array( $type, $no_child_sub, true ) ) {
            wp_send_json( [ 'results' => [] ] );
        }

        /* ---------------------------------------
        * Dynamic *_by_author 
        * --------------------------------------- */
        if ( str_ends_with( $type, '_by_author' ) || $type === 'author' || $type === 'by_author' ) {

            $authors = get_users([
                'search'         => '*' . esc_attr( $search ) . '*',
                'search_columns' => [ 'display_name', 'user_login' ],
            ]);

            foreach ( $authors as $author ) {
                $items[] = [
                    'id'   => $author->ID,
                    'text' => $author->display_name,
                ];
            }

            wp_send_json( [ 'results' => $items ] );
        }

        /* -------------------------------------------------
        * Category based (post categories)
        * ------------------------------------------------- */
        if ( in_array( $type, ['category','child_of_category','any_child_of_category','in_category','in_category_children'], true ) ) {

            $terms = get_terms([
                'taxonomy'   => 'category',
                'hide_empty' => false,
                'search'     => $search,
            ]);

            if ( ! is_wp_error($terms) ) {
                foreach ( $terms as $term ) {
                    $items[] = [
                        'id'   => $term->term_id,
                        'text' => $this->easyel_get_term_hierarchy_label( $term, 'category' ),
                    ];
                }
            }
        }

        /* -------------------------------------------------
        * Dynamic CPT taxonomies (archive rules)
        * ------------------------------------------------- */
        $archive_taxonomies = easyel_get_hierarchical_taxonomies_by_post_type();

        foreach ( $archive_taxonomies as $tax_slug => $data ) {

            if (
                $type === $tax_slug ||
                $type === 'child_of_' . $tax_slug ||
                $type === 'any_child_of_' . $tax_slug
            ) {

                $terms = get_terms([
                    'taxonomy'   => $tax_slug,
                    'hide_empty' => false,
                    'search'     => $search,
                ]);

                if ( ! is_wp_error($terms) ) {
                    foreach ( $terms as $term ) {
                        $items[] = [
                            'id'   => $term->term_id,
                            'text' => $this->easyel_get_term_hierarchy_label( $term, $tax_slug ),
                        ];
                    }
                }

                wp_send_json( [ 'results' => $items ] );
            }
        }

        /* -------------------------------------------------
        * Pages
        * ------------------------------------------------- */
        if ( in_array( $type, ['page','child_of','any_child_of'], true ) ) {

            $pages = get_pages([
                'search' => $search,
            ]);

            foreach ( $pages as $page ) {
                $items[] = [
                    'id'   => $page->ID,
                    'text' => $page->post_title,
                ];
            }
        }

        /* ---------------------------------------
        * Dynamic post_type handler (ONE PLACE)
        * --------------------------------------- */
        if ( post_type_exists( $type ) ) {

            $posts = get_posts([
                'post_type'      => $type,
                'posts_per_page' => 20,
                's'              => $search,
            ]);

            foreach ( $posts as $post ) {
                $items[] = [
                    'id'   => $post->ID,
                    'text' => $post->post_title,
                ];
            }

            wp_send_json( [ 'results' => $items ] );
        }

        /* -------------------------------------------------
        * Products & Product taxonomies
        * ------------------------------------------------- */
        if ( in_array( $type, ['product','product_cat','product_tag','product_brand','in_post_tag','post_tag'], true ) ) {

            // Products
            $taxonomy = $type;
            if ( in_array( $type, ['in_post_tag','post_tag'], true ) ) {
                $taxonomy = 'post_tag';
            }

            if ( taxonomy_exists( $taxonomy ) ) {

                $terms = get_terms([
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                    'search'     => $search,
                ]);

                if ( ! is_wp_error($terms) ) {
                    foreach ( $terms as $term ) {
                        $items[] = [
                            'id'   => $term->term_id,
                            'text' => $this->easyel_get_term_hierarchy_label( $term, $taxonomy ),
                        ];
                    }
                }
            }
        }

        /* -------------------------------------------------
        * Dynamic CPT taxonomies (singular in_ rules)
        * ------------------------------------------------- */

        foreach ( $archive_taxonomies as $tax_slug => $data ) {

            if (
                $type === 'in_' . $tax_slug ||
                $type === 'in_' . $tax_slug . '_children'
            ) {

                if ( taxonomy_exists( $tax_slug ) ) {

                    $terms = get_terms([
                        'taxonomy'   => $tax_slug,
                        'hide_empty' => false,
                        'search'     => $search,
                    ]);

                    if ( ! is_wp_error( $terms ) ) {
                        foreach ( $terms as $term ) {
                            $items[] = [
                                'id'   => $term->term_id,
                                'text' => $this->easyel_get_term_hierarchy_label( $term, $tax_slug ),
                            ];
                        }
                    }
                }

                wp_send_json([
                    'results' => $items,
                ]);
            }
        }

        /* -------------------------------------------------
        * Final response for Select2
        * ------------------------------------------------- */
        wp_send_json([
            'results' => $items,
        ]);
    }

    /**
     * Enqueue admin scripts and styles.
     *
     * @param string $hook The current admin page.
     */
    public function easy_builder_enqueue_assets( $hook ) {

        $assetsUrl = 'includes/Theme_Builder/assets/';

        wp_enqueue_style(
            'easyel-builder-style',
            EASYELEMENTS_DIR_URL . $assetsUrl. 'css/easy-modal-css.css',
            [],
            EASYELEMENTS_VER
        );

        wp_enqueue_style(
            'easyel-select2-css',
            EASYELEMENTS_DIR_URL . $assetsUrl. 'css/select2.min.css',
            [],
            EASYELEMENTS_VER
        );

            $child_options = function_exists('easyel_theme_builder_child_options') 
        ? easyel_theme_builder_child_options() 
        : ['needs_child_sub' => [], 'no_child_sub' => []];

        

        wp_enqueue_script(
            'easyel-select2-js',
            EASYELEMENTS_DIR_URL . $assetsUrl. 'js/select2.min.js',
            [ 'jquery' ],
            EASYELEMENTS_VER,
            true
        );

        wp_enqueue_script(
            'easyel-builder-script',
            EASYELEMENTS_DIR_URL  . $assetsUrl. 'js/modal-popup.js',
            [ 'jquery' ],
            EASYELEMENTS_VER,
            true
        );

        wp_localize_script(
            'easyel-builder-script',
            'easyel_builder_obj',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'easyel_ajax_nonce' ),
                'admin_url' => admin_url(),
                'needs_child_sub' => $child_options['needs_child_sub'] ?? [],
                'no_child_sub'   => $child_options['no_child_sub'] ?? [],
            ]
        );
    }

    function easyel_theme_builder_templates_menu() {
        // Archive Templates
        add_submenu_page(
            'easy-elements-dashboard',
            __('Theme Builder', 'easy-elements'),
            __('Theme Builder', 'easy-elements'),
            'manage_options',
            'edit.php?post_type=easy_theme_builder'
        );
    }
    /**
     * Register Custom Post Type
     */
    public function easyel_theme_builder_post_type() {

        $labels = array(
            'name'                  => _x( 'Theme Templates', 'Post Type General Name', 'easy-elements' ),
            'singular_name'         => _x( 'Theme Template', 'Post Type Singular Name', 'easy-elements' ),
            'menu_name'             => __( 'Easy Theme Builder', 'easy-elements' ),
            'name_admin_bar'        => __( 'Theme Template', 'easy-elements' ),
            'archives'              => __( 'Template Archives', 'easy-elements' ),
            'attributes'            => __( 'Template Attributes', 'easy-elements' ),
            'all_items'             => __( 'All Templates', 'easy-elements' ),
            'add_new_item'          => __( 'Add New Template', 'easy-elements' ),
            'add_new'               => __( 'Add New', 'easy-elements' ),
            'new_item'              => __( 'New Template', 'easy-elements' ),
            'edit_item'             => __( 'Edit Template', 'easy-elements' ),
            'update_item'           => __( 'Update Template', 'easy-elements' ),
            'view_item'             => __( 'View Template', 'easy-elements' ),
            'view_items'            => __( 'View Templates', 'easy-elements' ),
            'search_items'          => __( 'Search Template', 'easy-elements' ),
        );

        $args = array(
            'label'                 => __( 'Theme Builder', 'easy-elements' ),
            'description'           => __( 'Custom theme builder templates.', 'easy-elements' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'elementor' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => false,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-layout',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'rewrite'            => [
                'slug'       => 'easy_theme_builder',
                'pages'      => false,
                'with_front' => true,
                'feeds'      => false,
            ],
            'show_in_rest'          => true,
        );

        register_post_type( self::EASYEL_BUILDER_CPT, $args );
        $this->flush_rewrite_rules();
    }

    public function flush_rewrite_rules() {
        if ( get_option( 'easyelements_permalinks_flushed', 'no' ) !== 'yes' ) {
            flush_rewrite_rules();
            update_option( 'easyelements_permalinks_flushed', 'yes' );
        }
    }

        /**
     * Add Custom Tabs Above Table
     */
    public function easyel_builder_filter_markup( $views ) {

        global $typenow;

        if ( $typenow === 'easy_theme_builder' ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is a read-only filter, no action performed.
            $current = isset($_GET['easy_etb_type']) ? sanitize_key( wp_unslash($_GET['easy_etb_type']) ) : '';
            $base_url = admin_url( 'edit.php?post_type=easy_theme_builder' );

            ob_start();
            ?>
            <div class="easyel-builder-tabs">
                <a href="<?php echo esc_url( $base_url ); ?>" class="easyel-tab <?php echo $current=='' ? 'active' : ''; ?>">
                    <?php esc_html_e( 'All', 'easy-elements' ); ?>
                </a>
                <a href="<?php echo esc_url( $base_url . '&easy_etb_type=archive' ); ?>" class="easyel-tab <?php echo $current=='archive' ? 'active' : ''; ?>">
                    <?php esc_html_e( 'Archive', 'easy-elements' ); ?>
                </a>
                <a href="<?php echo esc_url( $base_url . '&easy_etb_type=single' ); ?>" class="easyel-tab <?php echo $current=='single' ? 'active' : ''; ?>">
                    <?php esc_html_e( 'Single', 'easy-elements' ); ?>
                </a>
            </div>
            <?php
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe because output is intended HTML.
            echo ob_get_clean();
        }

        return $views;
    }

    public static function easyel_template_type() {
        $template_types = apply_filters(
            'easyelements/theme-builder/template-types',
            [
                'all'     => esc_html__( 'All', 'easy-elements' ),
                'single'  => esc_html__( 'single', 'easy-elements' ),
                'archive' => esc_html__( 'Archive', 'easy-elements' ),
            ]
        );

        return $template_types;
    }

    // new id added
    public function easyel_add_new_id_added_func() {
        $screen = get_current_screen();
        if ( $screen && $screen->id === 'edit-easy_theme_builder' ) {   

            $handle = 'easyel-theme-builder-admin-inline-js';
            wp_register_script( $handle, false, [ 'jquery' ], EASYELEMENTS_VER, true );
            wp_enqueue_script( $handle );

            $inline_js = "
                jQuery(document).ready(function($) {
                    $('.page-title-action')
                        .attr('id', 'easyel-theme-builder-add-template')
                        .attr('href', '#');
                });
            ";
            wp_add_inline_script( $handle, $inline_js );

        ?>
        
        <!-- Modal Overlay -->
        <div id="easyel-template-modal-edit" class="easyel-modal-overlay-edit easyel-edit-template-condition">
            <div class="easyel-modal-content-edit">
                <div class="easyel-template-error-message"></div>
                <span class="easyel-close">&times;</span>
                <div class="easyel-choose-template">
                    <h2 class="easyel-choose-template">Edit Template Type</h2>
                    <div class="easyel-template-type">
                        <select class="easyel-builder-tmpl-type" name="easyel_builder_tmpl_type">
                            <option value="">Select Template Type</option>
                            <option value="archive">Archive</option>
                            <option value="single">Single</option>
                        </select>
                    </div>
                    <div class="easyel-template-type">
                        <input type="text" name="easyel_builder_template_name" class="easyel-builder-template-name" placeholder="Enter template Name"/>
                    </div>
                </div>
                <h2> Edit Template Elements Condition</h2>
                <p>Where do you want to display your template?</p>

                <div id="easyel-conditions-wrapper-edit" class="easyel-conditions-wrapper-edit">
                    <div class="easyel-condition-row-edit">
                        <select class="easyel-include-type">
                            <option value="include">Include</option>
                            <option value="exclude">Exclude</option>
                        </select>
                        <select class="easyel-condition-main">
                            <option value="archives">Archives</option>
                            <option value="singular">Singular</option>
                        </select>
                        <select class="easyel-condition-sub">
                            <option value="all">All Archives</option>
                        </select>
                        <select class="easyel-condition-child-sub">
                            <option value="child_sub_all">All</option>
                        </select>
                        <span class="easyel-remove-row">&times;</span>
                    </div>
                </div>

                <button type="button" id="easyel-add-condition-edit">+ Add Condition</button>
                <div class="easyel-modal-footer">
                    <button class="easyel-cancel-btn">Cancel</button>
                    <button class="easyel-edit-template">Update</button>
                    <a href="#" id="easyel-edit-with-elementor" class="easyel-edit-with-elementor">Edit With Eleemntor</a>
                </div>
            </div>
        </div>
        <?php
        }
    }

    public function easy_add_new_template_template() {
        $screen = get_current_screen();
        
        ob_start();
        require_once EASYELEMENTS_DIR_PATH . '/includes/Theme_Builder/popup-content.php';
        $template = ob_get_clean();
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe because output is intended HTML.
        echo $template;
    }

    /*builder ajax*/
    public function easyel_ajax_save_builder() {
        // Check nonce
        check_ajax_referer('easyel_ajax_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied', 'easy-elements'));
        }
        
        if( isset($_POST['conditions'], $_POST['template_type'], $_POST['template_name']) ) {

                
            $template_type = isset($_POST['template_type']) ? sanitize_text_field( wp_unslash($_POST['template_type']) ) : '';
            $template_name = isset($_POST['template_name']) ? sanitize_text_field( wp_unslash($_POST['template_name']) ) : '';

            $conditions = isset($_POST['conditions']) 
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            ? wp_json_encode( easyel_sanitize_conditions_array($_POST['conditions']) )
            : '';

            if ( empty( $template_type ) ) {
                wp_send_json_error( [
                    'message' => __( 'Please select a template type.', 'easy-elements' )
                ] );
            }

            //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Slow query with meta_query is intentional.
            $existing_posts = get_posts( [
                'post_type'  => 'easy_theme_builder',
                'post_status'=> 'publish',
                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Slow meta_query intentional
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key'   => 'easyel_template_type',
                        'value' => $template_type,
                    ],
                    [
                        'key'   => 'easyel_conditions',
                        'value' => $conditions,
                    ]
                ]
            ] );

            if(!empty($existing_posts)) {
                wp_send_json_error([
                    'message' => 'A template with the same type and conditions already exists!'
                ]);
            }


            $new_post = [
                'post_title'    => $template_name,
                'post_status'   => 'publish',
                'post_type'     => 'easy_theme_builder',
                'meta_input'    => [
                    'easyel_template_type' => $template_type,
                    'easyel_conditions'    => $conditions
                ]
            ];

            $post_id = wp_insert_post($new_post);

            if( $post_id && !is_wp_error( $post_id ) ){

                update_post_meta($post_id, '_wp_page_template', 'elementor_header_footer'); // Elementor Full Width
                update_post_meta($post_id, '_elementor_template_type', 'wp-page');
                update_post_meta($post_id, '_elementor_edit_mode', 'builder');

                $edit_url = add_query_arg(
                    [
                        'post'   => $post_id,
                        'action' => 'elementor'
                    ],
                    admin_url( 'post.php' )
                );

                wp_send_json_success([
                    'message'   => 'Template saved & published successfully!',
                    'post_id'   => $post_id,
                    'edit_url'  => $edit_url
                ]);

            } else {
                wp_send_json_error( ['message' => 'Failed to create template post.'] );
            }

        } else {
            wp_send_json_error(['message' => 'Missing required data!']);
        }
    }

    public function easy_theme_builder_posts_columns( $columns ) {
        $new_columns = [];

        foreach ( $columns as $key => $value ) {
            
            if ( 'title' === $key ) {
                $new_columns[ $key ] = $value;
                $new_columns['template_type'] = __( 'Template Type', 'easy-elements' );
                
            } elseif ( 'date' === $key ) {
                continue;
            } else {
                $new_columns[ $key ] = $value;
            }
        }

        $new_columns['display_conditions'] = __( 'Display Conditions', 'easy-elements' );
        
        $new_columns['date'] = $columns['date'];

        return $new_columns;
    }

    public function easy_theme_builder_sortable_columns ( $columns ) {
        $columns['template_type'] = 'template_type';
        return $columns;
    }

    public function easy_theme_builder_posts_custom_column( $column, $post_id ) {
        if ( $column === 'template_type' ) {
            $type = get_post_meta( $post_id, 'easyel_template_type', true );
            echo esc_html( ucfirst( $type ) );
        }

        if ( $column === 'display_conditions' ) {
            $conditions = get_post_meta( $post_id, 'easyel_conditions', true );

            if ( is_array( $conditions ) ) {
                $decoded = $conditions; 
            } else {
                $decoded = json_decode( $conditions, true );
            }

            if ( is_array( $decoded ) ) {
                foreach ( $decoded as $cond ) {
                    if ( ! is_array( $cond ) ) continue;

                    $include_type = $cond['include'] ?? 'include';
                    $main         = $cond['main'] ?? '';
                    $sub          = $cond['sub'] ?? '';
                    $child_sub    = $cond['child_sub'] ?? '';

                    $child_sub_label = '';

                    $pro_version = easyel_get_pro_clean_version();

                    if (
                            $pro_version &&
                            version_compare( $pro_version, '1.0.8', '>=' )
                        ) {
                        if ( did_action( 'plugins_loaded' ) && class_exists( '\EasyElements_Elementor\Pro\ThemeBuilder\ThemeBuilderPro' ) ) {

                            $instance = \EasyElements_Elementor\Pro\ThemeBuilder\ThemeBuilderPro::get_instance();

                            if ( $instance && method_exists( $instance, 'easyel_get_label_from_child_sub' ) ) {
                                $child_sub_label = $instance->easyel_get_label_from_child_sub( $sub, $child_sub );
                            }
                        }

                    } else {
                        if ( did_action( 'plugins_loaded' ) && class_exists( '\EasyEL_Free_Pro_Unlock' ) ) {

                            $instance = \EasyEL_Free_Pro_Unlock::instance();

                            if ( $instance && method_exists( $instance, 'easyel_get_label_from_child_sub' ) ) {
                                $child_sub_label = $instance->easyel_get_label_from_child_sub( $sub, $child_sub );
                            }
                        }
                    }

                    $style = ( 'exclude' === $include_type ) ? 'style="color: #d63638;"' : '';

                    echo '<div ' . esc_attr( $style ) . '>';
                    echo '<strong>' . esc_html( ucfirst( $include_type ) ) . '</strong> : ';
                    echo esc_html( $main ) . ' → ' . esc_html( $sub );

                    if ( ! empty( $child_sub_label ) ) {
                        echo ' → ' . esc_html( $child_sub_label );
                    }

                    echo '</div>';
                }
            }

        }

    }

    public function easyel_filter_easy_theme_builder_by_type( $query ) {
        if ( is_admin() && $query->is_main_query() && $query->get('post_type') === 'easy_theme_builder' ) {
            
            // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
            if ( isset( $_GET['easy_etb_type'] ) 
                // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
                && in_array( $_GET['easy_etb_type'], ['single','archive'], true ) 
            ) {
                $query->set( 'meta_query', [
                    [
                        'key'   => 'easyel_template_type', 
                        // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
                        'value' => sanitize_text_field( wp_unslash( $_GET['easy_etb_type'] ) ),
                    ]
                ]);
            }

        }
    }

    public function easyel_get_archives_func() {

        /**
         * --------------------------------------------------
         * Base archive structure
         * --------------------------------------------------
         */
        $archives = [
            'core'             => [],
            'posts_archive'    => [],
            'products_archive' => [],
            'dynamic'          => [], 
        ];

        /**
         * --------------------------------------------------
         * Core Archives
         * --------------------------------------------------
         */
        $archives['core'][] = [
            'value' => 'index',
            'label' => __('All Archives', 'easy-elements'),
            'pro'   => false,
            'group' => 'Core'
        ];

        $archives['core'][] = [
            'value' => 'author',
            'label' => __('Author Archive [Pro]', 'easy-elements'),
            'pro'   => true,
            'group' => 'Core'
        ];

        $archives['core'][] = [
            'value' => 'search',
            'label' => __('Search Results [Pro]', 'easy-elements'),
            'pro'   => true,
            'group' => 'Core'
        ];

        $archives['core'][] = [
            'value' => 'date',
            'label' => __('Date Archive [Pro]', 'easy-elements'),
            'pro'   => true,
            'group' => 'Core'
        ];

        /**
         * --------------------------------------------------
         * Default Posts Archive (Handled separately)
         * --------------------------------------------------
         */
        $archives['posts_archive'][] = [
            'value' => 'post_archive',
            'label' => __('Posts Archive [Pro]', 'easy-elements'),
            'pro'   => true,
            'group' => 'Posts'
        ];

        $archives['posts_archive'][] = [
            'value' => 'category',
            'label' => __('Categories [Pro]', 'easy-elements'),
            'pro'   => true,
            'group' => 'Posts'
        ];

        $archives['posts_archive'][] = [
            'value' => 'child_of_category',
            'label' => __('Direct Child Category of [Pro]', 'easy-elements'),
            'pro'   => true,
            'group' => 'Posts'
        ];

        $archives['posts_archive'][] = [
            'value' => 'any_child_of_category',
            'label' => __('Any Child Category of [Pro]', 'easy-elements'),
            'pro'   => true,
            'group' => 'Posts'
        ];

        // Post Tags (only if tags exist)
        $tags = get_terms([
            'taxonomy'   => 'post_tag',
            'hide_empty' => false
        ]);

        if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
            $archives['posts_archive'][] = [
                'value' => 'post_tag',
                'label' => __('Tags [Pro]', 'easy-elements'),
                'pro'   => true,
                'group' => 'Posts'
            ];
        }

        /**
         * --------------------------------------------------
         * Dynamic Custom Post Types & Taxonomies
         * --------------------------------------------------
         */

        $post_types = easyel_get_valid_archive_post_types();

        unset( $post_types['attachment'], $post_types['post'], $post_types['product'] );

        foreach ( $post_types as $post_type => $pt ) {

            $group_label = sprintf( '%s Archive', $pt->label );

            if ( ! isset( $archives['dynamic'][ $group_label ] ) ) {
                $archives['dynamic'][ $group_label ] = [];
            }

            /**
             * Post Type Archive
             */
            if ( $pt->has_archive ) {
                $archives['dynamic'][ $group_label ][] = [
                    'value' => $post_type."_archive",
                    // translators: %s will be replaced with the post type label (e.g., 'Portfolio')
                    'label' => sprintf( '%s Archive [Pro]', $pt->label ),
                    'pro'   => true,
                ];
            }

            /**
             * Taxonomy Archives
             */
            $taxonomies = get_object_taxonomies( $post_type, 'objects' );
            $taxonomies = wp_filter_object_list(
                $taxonomies,
                [
                    'public'            => true,
                    'show_in_nav_menus' => true,
                ]
            );

            foreach ( $taxonomies as $tax ) {

                if ( $tax->name === 'post_format' ) {
                    continue;
                }

                // Normal taxonomy
                $archives['dynamic'][ $group_label ][] = [
                    'value' => $tax->name,
                    // translators: %s will be replaced with the taxonomy label (e.g., 'Category')
                    'label' => sprintf( '%s [Pro]', $tax->label ),
                    'pro'   => true,
                ];

                // Hierarchical taxonomy rules
                if ( is_taxonomy_hierarchical( $tax->name ) ) {

                    $archives['dynamic'][ $group_label ][] = [
                        'value' => 'child_of_' . $tax->name,
                        // translators: %s will be replaced with the taxonomy label (e.g., 'Category')
                        'label' => sprintf('Direct child %s  [Pro]',$tax->label),
                        'pro' => true,
                    ];

                    $archives['dynamic'][ $group_label ][] = [
                        'value' => 'any_child_of_' . $tax->name,
                        // translators: %s will be replaced with the taxonomy label (e.g., 'Category')
                        'label' => sprintf('Any child %s [Pro]',$tax->label),
                        'pro' => true,
                    ];
                }
            }
        }

        /**
         * --------------------------------------------------
         * WooCommerce Product Archives
         * --------------------------------------------------
         */
        if ( class_exists( 'WooCommerce' ) ) {

            $archives['products_archive'][] = [
                'value' => 'all_product_archive',
                'label' => __('All Product Archives [Pro]', 'easy-elements'),
                'pro'   => true,
                'group' => 'Products'
            ];

            $woo_taxonomies = [
                'shop_page',
                'product_search',
                'product_brand',
                'product_cat',
                'product_tag'
            ];

            foreach ( $woo_taxonomies as $tax ) {

                switch ( $tax ) {
                    case 'shop_page':
                        $label = __('Shop Page [Pro]', 'easy-elements');
                        break;

                    case 'product_search':
                        $label = __('Search Results [Pro]', 'easy-elements');
                        break;

                    default:
                        $taxonomy_obj = get_taxonomy( $tax );
                        $label = $taxonomy_obj
                            ? 'Product ' . $taxonomy_obj->labels->singular_name . ' [Pro]'
                            : '';
                }

                if ( $label ) {
                    $archives['products_archive'][] = [
                        'value' => $tax,
                        'label' => $label,
                        'pro'   => true,
                        'group' => 'Products'
                    ];
                }
            }
        }

        /**
         * --------------------------------------------------
         * Final Filter & Response
         * --------------------------------------------------
         */
        $archives = apply_filters( 'easyel_archives_data', $archives );

        wp_send_json_success( $archives );
    }

    public function easyel_get_singulars_func() {

        $singulars = [];

        $singulars[] = [
            'value' => 'all',
            'label' => __('All Singular','easy-elements'),
            'pro'   => false,
            'group' => null
        ];

        $singulars[] = [
            'value' => 'front_page',
            'label' => __('Front Page [Pro]','easy-elements'),
            'pro'   => true,
            'group' => null
        ];

        $post_items = [
            ['value'=>'post','label'=>__('Posts','easy-elements'),'pro'=>false],
            ['value'=>'in_category','label'=>__('In Category [Pro]','easy-elements'),'pro'=>true],
            ['value'=>'in_category_children','label'=>__('In child Categories [Pro]','easy-elements'),'pro'=>true],
            ['value'=>'in_post_tag','label'=>__('In Tag [Pro]','easy-elements'),'pro'=>true],
            ['value'=>'post_by_author','label'=>__('Posts By Author [Pro]','easy-elements'),'pro'=>true],
        ];

        foreach( $post_items as $item ){
            $item['group'] = 'Posts';
            $singulars[] = $item;
        }

        // --------------------
        // CUSTOM POST TYPES (DYNAMIC)
        // --------------------
        $singular_post_types = easyel_get_valid_archive_post_types();

        $excluded = ['post', 'page', 'attachment'];

        foreach ( $singular_post_types as $post_type => $obj ) {

            if ( in_array( $post_type, $excluded, true ) ) {
                continue;
            }

            $label = $obj->labels->singular_name;

            // Main singular
            $singulars[] = [
                'value' => $post_type,
                // translators: %s will be replaced with the placeholder text 'All Singular'
                'label' => sprintf( __( '%s [Pro]', 'easy-elements' ), $label ),
                'group' => $label,
                'pro'   => true,
            ];

            // ---------- TAXONOMY CONDITIONS ----------
            $taxonomies = get_object_taxonomies( $post_type, 'objects' );

            foreach ( $taxonomies as $tax_slug => $tax ) {

                if (
                    ! $tax->public ||
                    ! $tax->show_ui ||
                    ! $tax->query_var
                ) {
                    continue;
                }

                // In taxonomy
                $singulars[] = [
                    'value' => 'in_' . $tax_slug,
                    'label' => sprintf(
                        // translators: %s will be replaced with the singular name of the taxonomy (e.g., 'Category')
                        __( 'In %s [Pro]', 'easy-elements' ),
                        $tax->labels->singular_name
                    ),
                    'group' => $label,
                    'pro'   => true,
                ];

                // In taxonomy children (only if hierarchical)
                if ( $tax->hierarchical ) {
                    $singulars[] = [
                        'value' => 'in_' . $tax_slug . '_children',
                        'label' => sprintf(
                            // translators: %s will be replaced with the plural name of the taxonomy (e.g., 'Categories')
                            __( 'In child %s [Pro]', 'easy-elements' ),
                            $tax->labels->name
                        ),
                        'group' => $label,
                        'pro'   => true,
                    ];
                }
            }
        
            // By author
            $singulars[] = [
                'value' => $post_type . '_by_author',
                // translators: %s will be replaced with the singular label of the post type (e.g., 'Post')
                'label' => sprintf( __( '%s By Author [Pro]', 'easy-elements' ), $label ),
                'group' => $label,
                'pro'   => true,
            ];

        }

        $page_items = [
            ['value'=>'page','label'=>__('Pages [Pro]','easy-elements'),'pro'=>true],
            ['value'=>'page_by_author','label'=>__('Pages By Author [Pro]','easy-elements'),'pro'=>true],
        ];

        foreach( $page_items as $item ){
            $item['group'] = 'Page';
            $singulars[] = $item;
        }

        $others = [
            ['value'=>'child_of','label'=>__('Direct Child Of [Pro]','easy-elements'),'pro'=>true],
            ['value'=>'any_child_of','label'=>__('Any Child Of [Pro]','easy-elements'),'pro'=>true],
            ['value'=>'by_author','label'=>__('By Author [Pro]','easy-elements'),'pro'=>true],
            ['value'=>'not_found404','label'=>__('404 Page [Pro]','easy-elements'),'pro'=>true ],
        ];

        foreach( $others as $item ){
            $item['group'] = null;
            $singulars[] = $item;
        }

        /**
         * Filter: easyel_singulars_data
         * Allows modification of singulars array (e.g., to make Pro items free)
         *
         * @param array $singulars Array of singulars
         */
        $singulars = apply_filters( 'easyel_singulars_data', $singulars );

        wp_send_json_success( $singulars );

    }


    /*Ajax modal edit popup*/
    public function easyel_ajax_get_builder() {
        check_ajax_referer('easyel_ajax_nonce', 'nonce');

        if ( ! current_user_can('manage_options') ) {
            wp_send_json_error(['message' => __('Permission denied', 'easy-elements')]);
        }

        if ( empty($_POST['post_id']) ) {
            wp_send_json_error(['message' => __('Missing post ID!', 'easy-elements')]);
        }

        $post_id = intval($_POST['post_id']);
        $post    = get_post($post_id);

        if ( ! $post || $post->post_type !== 'easy_theme_builder' ) {
            wp_send_json_error(['message' => __('Invalid post!', 'easy-elements')]);
        }

        $template_type = get_post_meta($post_id, 'easyel_template_type', true);
        $conditions    = get_post_meta($post_id, 'easyel_conditions', true);
        $conditions    = !empty($conditions) ? json_decode($conditions, true) : [];

        wp_send_json_success([
            'post_id'       => $post_id,
            'template_name' => $post->post_title,
            'template_type' => $template_type,
            'conditions'    => $conditions,
        ]);
    }

    function easyel_update_builder_callback() {
        check_ajax_referer('easyel_ajax_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Permission denied']);
        }

        $post_id       = isset($_POST['post_id']) ? absint(wp_unslash($_POST['post_id'])) : 0;
        $template_name = isset($_POST['template_name']) ? sanitize_text_field(wp_unslash($_POST['template_name'])) : '';
        $template_type = isset($_POST['template_type']) ? sanitize_text_field(wp_unslash($_POST['template_type'])) : '';
        $conditions = isset($_POST['conditions'])
            ? map_deep( wp_unslash( $_POST['conditions'] ), 'sanitize_text_field' ) : [];

        if ( !$post_id ) {
            wp_send_json_error(['message' => 'Invalid post ID']);
        }

        if ( empty( $template_type ) ) {
            wp_send_json_error( [
                'message' => __( 'Please select a template type.', 'easy-elements' )
            ] );
        }

        $easyel_conditions = wp_json_encode($conditions);

        $existing_posts = get_posts( [
            'post_type'   => 'easy_theme_builder',
            'post_status' => 'publish',
            'fields'      => 'ids',
            // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- intentional for this use case
            'exclude'     => [ $post_id ],
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Slow meta_query intentional
            'meta_query'  => [
                'relation' => 'AND',
                [
                    'key'   => 'easyel_template_type',
                    'value' => $template_type,
                ],
                [
                    'key'   => 'easyel_conditions',
                    'value' => $easyel_conditions, // already sanitized above
                ],
            ],
        ] );

        $existing_posts = array_filter( $existing_posts, function( $post ) use ( $post_id ) {
            return $post->ID != $post_id;
        });

        if ( !empty( $existing_posts ) ) {
            wp_send_json_error([
                'message' => 'A template with the same type and conditions already exists!'
            ]);
        }

        wp_update_post([
            'ID'         => $post_id,
            'post_title' => $template_name,
        ]);


        // Update meta
        update_post_meta( $post_id, 'easyel_template_type', $template_type );
        update_post_meta( $post_id, 'easyel_conditions',  $easyel_conditions );  // already sanitized above

        wp_send_json_success( [ 'message' => 'Template updated successfully!'] );
    }

}