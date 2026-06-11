<?php
namespace Easyel\EasyElements\Admin;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin_Settings {

    // Singleton instance
    private static $instance = null;

    private function __construct() {
        // Hooks
        
        // Custom Font Load Free

        add_action( 'admin_menu', array( $this, 'easyel_elements_settings_menu' )  );
        add_action( 'admin_menu', array( $this, 'easyel_elements_settings_menu2' ) ,999 );
        add_action( 'admin_enqueue_scripts', array( $this, 'easyel_enqueue_upgrade_menu_assets' ) );
        add_action( 'admin_init', array( $this, 'easyel_elements_register_settings' ) );

        add_action( 'easyel_smooth_scroller_popup', array( $this, 'easyel_smooth_scroller_popup_callback' ), 5 );
        add_action( 'easyel_reading_progress_bar_popup', array( $this, 'easyel_reading_progressbar_popup_callback' ), 6 );
        add_action( 'easyel_scroll_top_popup', array( $this, 'easyel_scroll_top_popup_callback' ), 7 );
        add_action( 'easyel_preloader_popup', array( $this, 'easyel_preloader_popup_callback' ), 8 );

        add_action( "admin_footer", array( $this,"easyel_settings_toggle_popup_modal" ) );

        $this->easyel_settings_ajax();
       
    }

    public function easyel_settings_ajax( ) {
        
        // AJAX handler for saving minify js option

        add_action('wp_ajax_easy_elements_save_widget_setting', array( $this, "easy_elements_save_widget_setting" ) );
        add_action('wp_ajax_easy_elements_bulk_action', array( $this, 'easy_elements_bulk_action') );
       
        add_action('wp_ajax_easy_elements_save_global_extensions', array( $this, 'easy_elements_save_global_extensions') ) ;
        add_action('wp_ajax_easy_elements_save_global_extensions_bulk', array( $this, 'easy_elements_save_global_extensions_bulk') ) ;

        add_action('wp_ajax_easy_elements_bulk_group_action', [$this, 'easy_elements_bulk_group_action']);

        add_action('admin_enqueue_scripts', array( $this, 'easyel_elements_enqueue_admin_assets') );
        add_action( 'admin_head', array( $this, 'easyel_hide_admin_notices' ) );

        add_action('wp_ajax_easyel_save_user_data', array( $this, 'easyel_save_user_data_callback') );

        add_action('wp_ajax_save_smooth_scroll_settings', array( $this, 'save_smooth_scroll_settings'));
        add_action('wp_ajax_nopriv_save_smooth_scroll_settings', array( $this, 'save_smooth_scroll_settings') );

        add_action('wp_ajax_save_reading_progressbar_settings', array( $this, 'save_reading_progressbar_settings'));
        add_action('wp_ajax_nopriv_save_reading_progressbar_settings', array( $this, 'save_reading_progressbar_settings') );

        add_action('wp_ajax_save_scroll_top_settings', array( $this, 'save_scroll_top_settings'));
        add_action('wp_ajax_nopriv_save_scroll_top_settings', array( $this, 'save_scroll_top_settings') );

        add_action('wp_ajax_easyel_save_preloader_settings', array( $this, 'easyel_save_preloader_settings' ) );

    }

    // Singleton get_instance
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function easyel_save_user_data_callback() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( ['msg' => 'Unauthorized'], 403 );
        }

        check_ajax_referer('easy_elements_nonce', 'security');

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $posted = $_POST['settings'] ?? [];
        $settings = map_deep( wp_unslash( $posted ), 'sanitize_text_field' );

        update_option( 'easy_element_user_data', $settings );

        wp_send_json_success(['message' => 'Saved successfully!']);
    }

    public function easyel_elements_settings_menu() {

        // Main Menu
        add_menu_page(
            __('Easy Elements', 'easy-elements'),
            __('Easy Elements', 'easy-elements'),
            'manage_options',
            'easy-elements-dashboard',
            array( $this, 'easyel_elements_settings_callback' ),
            'dashicons-admin-generic',
            59
        );

        global $submenu;

        // Main slug
        $slug = 'easy-elements-dashboard';

        $submenu[$slug][] = [ __('Overview', 'easy-elements'), 'manage_options', 'admin.php?page='.$slug.'#overview' ];
        $submenu[$slug][] = [ __('Widgets', 'easy-elements'), 'manage_options', 'admin.php?page='.$slug.'#widget' ];
        $submenu[$slug][] = [ __('All Extensions', 'easy-elements'), 'manage_options', 'admin.php?page='.$slug.'#extensions' ];

        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        if ( did_action( 'elementor/loaded' ) || is_plugin_active( 'elementor/elementor.php' ) ) {
            add_submenu_page(
                'easy-elements-dashboard',
                __('Header & Footer', 'easy-elements'),
                __('Header & Footer', 'easy-elements'),
                'manage_options',
                'edit.php?post_type=ee-elementor-hf'
            );
        }

        // Upload Custom Fonts Submenu
        add_submenu_page(
            'easy-elements-dashboard',
            __('Upload Custom Fonts', 'easy-elements'),
            __('Upload Custom Fonts', 'easy-elements'),
            'manage_options',
            'easyel-custom-fonts',
            array( $this, 'easyel_custom_fonts_page_html' )
        );

        // Off Canvas Submenu
        add_submenu_page(
            'easy-elements-dashboard',                 
            __('Off Canvas', 'easy-elements'),         
            __('Off Canvas', 'easy-elements'),         
            'manage_options',                          
            'edit.php?post_type=easy-offcanvas'
        );

        $flush_needed = get_option( 'easyel_flush_rewrite_rules', true );

        if ( $flush_needed ) {
            flush_rewrite_rules(); // Flush permalink
            update_option( 'easyel_flush_rewrite_rules', false ); // Mark as flushed
        }


    }

    public function easyel_settings_toggle_popup_modal( ) { ?>

        <div id="easyel-admin-toggle-pro-upgrade-modal" class="easyel-admin-toggle-pro-modal" style="display:none;">
            <div class="easyel-admin-toggle-pro-modal-overlay"></div>

            <div class="easyel-admin-toggle-pro-modal-content">
                <button class="easyel-admin-toggle-pro-modal-close">×</button>

                <span class="easyel-admin-toggle-pro-badge">PRO</span>

                <h2>Unlock the PRO Features 🚀</h2>

                <p>
                    This widget is available in <strong>Easy Elements Pro</strong>.
                    Upgrade now to unlock advanced widgets, premium features, and priority support.
                </p>

                <ul class="easyel-admin-toggle-pro-feature-list">
                    <li> ✔ Advanced Elementor Widgets </li>
                    <li> ✔ Premium Effects & Controls </li>
                    <li> ✔ Faster Performance </li>
                    <li> ✔ Priority Support </li>
                </ul>

                <div class="easyel-admin-toggle-pro-actions">
                    <a href="https://wpeasyelements.com/pricing/"
                    target="_blank"
                    class="easyel-admin-toggle-pro-upgrade-btn">
                        Upgrade to Pro
                    </a>

                    <button class="easyel-admin-toggle-pro-modal-cancel">
                        Maybe Later
                    </button>
                </div>
            </div>
        </div>


    <?php }

    public function easyel_elements_settings_menu2() {

        global $submenu;

        // Main slug
        $slug = 'easy-elements-dashboard';

        $submenu[ $slug ][] = [
            __( 'Upgrade to Pro', 'easy-elements' ),
            'manage_options',
            'https://wpeasyelements.com/pricing/',
            '',
            '',
        ];
    }

    public function easyel_enqueue_upgrade_menu_assets() {

        wp_enqueue_style(
            'easy-elements-upgrade-menu',
            EASYELEMENTS_DIR_URL . 'assets/css/admin/upgrade-menu.css',
            array(),
            EASYELEMENTS_VER
        );

        wp_enqueue_script(
            'easy-elements-upgrade-menu',
            EASYELEMENTS_DIR_URL . 'assets/js/admin-upgrade-menu.js',
            array( 'jquery' ),
            EASYELEMENTS_VER,
            true
        );
    }

    public function easyel_elements_register_settings() {
        register_setting( 'easyel_elements_extensions_group', 'easyel_enable_js_animation', [
            'sanitize_callback' => 'absint',
            'default' => 0,
        ] );

        // Cursor setting register
        register_setting( 'easyel_elements_extensions_group', 'easyel_enable_cursor', [
            'sanitize_callback' => 'absint',
            'default' => 0,
        ] );
    }

    public function easy_elements_bulk_group_action() {

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }

        check_ajax_referer('easy_elements_bulk_action_nonce', 'nonce');

        $group = isset($_POST['group']) ? sanitize_text_field(wp_unslash($_POST['group'])) : '';
        $tab   = isset($_POST['tab']) ? sanitize_text_field(wp_unslash($_POST['tab'])) : 'widget';
        $status = isset($_POST['status']) && $_POST['status'] == 1 ? '1' : '0';
      
        $available_elements = $this->easyel_elements_get_available_widgets();
        $updated_count = 0;

        $is_pro_active = easyel_premium_addon_active();

        foreach ($available_elements as $key => $widget) {

            if (!isset($widget['tab']) || $widget['tab'] !== $tab) {
                continue;
            }

            $group_slug = strtolower(trim($group));

            $widget_group = isset( $widget['group'] ) ? $widget['group'] : 'General Widgets';

            $widget_group_slug = strtolower(trim(str_replace(' ', '-', $widget_group ) ) );

            if ($widget_group_slug !== $group_slug) {
                continue; 
            }

            $option_name = 'easy_element_' . $tab . '_' . $key;

            if (!$is_pro_active && isset($widget['is_pro']) && $widget['is_pro']) {
                update_option($option_name, '0');
            } else {
                update_option($option_name, $status);
            }

            $updated_count++;
        }

        wp_send_json_success([
            'message'       => sprintf('%d widgets %s successfully', $updated_count, $status ? 'activated' : 'deactivated'),
            'count'         => $updated_count,
            'is_pro_active' => $is_pro_active,
            'status'        => $status
        ]);
    }

    public function easy_elements_save_global_extensions_bulk() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Unauthorized', 'easy-elements' ) );
        }

        check_ajax_referer( 'easy_elements_widget_settings_nonce', 'nonce' );

        $tab        = isset( $_POST['tab'] ) ? sanitize_text_field( wp_unslash( $_POST['tab'] ) ) : 'extensions';
        $keys       = isset( $_POST['keys'] ) ? array_map( 'sanitize_text_field', (array) wp_unslash( $_POST['keys'] ) ) : [];
        $status     = isset( $_POST['status'] ) ? intval( wp_unslash( $_POST['status'] ) ) : 0;
        $group_slug = isset( $_POST['group'] ) ? sanitize_text_field( wp_unslash( $_POST['group'] ) ) : '';

        if ( empty( $keys ) ) {
            wp_send_json_error( [ 'message' => __( 'No keys provided', 'easy-elements' ) ] );
        }

        $settings = get_option( 'easy_element_' . $tab, [] );
        
        foreach ( $keys as $key ) {
            $key = sanitize_text_field( $key );
            $settings[ $key ] = $status;
        }

        update_option( 'easy_element_' . $tab, $settings );

        if ( $group_slug ) {
            update_option( 'easy_element_group_' . $group_slug, $status );
        }

        wp_send_json_success( [ 'message' => __( 'Bulk settings updated', 'easy-elements' ) ] );
    }

    public function easy_elements_save_global_extensions() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }

        check_ajax_referer('easy_elements_widget_settings_nonce', 'nonce');

        $tab        = isset( $_POST['tab'] ) ? sanitize_text_field( wp_unslash( $_POST['tab'] ) ) : 'extensions';
        $key        = isset($_POST['key']) ? sanitize_text_field( wp_unslash( $_POST['key'] )) : '';
        $status     = isset( $_POST['status'] ) ? intval( wp_unslash( $_POST['status'] ) ) : 0;

        if (!$key) {
            wp_send_json_error(['message' => __('Invalid key', 'easy-elements')]);
        }

        if ( function_exists( 'easyel_get_extension_fields' ) ) {
            $extension_fields = easyel_get_extension_fields();
            if (
                isset( $extension_fields[ $key ] )
                && ! empty( $extension_fields[ $key ]['is_pro'] )
                && ! easyel_premium_addon_active()
            ) {
                wp_send_json_error( [
                    'message'        => '',
                    'requires_addon' => true,
                ] );
            }
        }

        $settings = get_option('easy_element_' . $tab, []);
        $settings[$key] = $status;

        update_option('easy_element_' . $tab, $settings);

        wp_send_json_success(['message' => __('Settings updated', 'easy-elements')]);
    }


    // AJAX handler for saving individual widget settings
  
    public function easy_elements_save_widget_setting() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }
        check_ajax_referer('easy_elements_widget_settings_nonce', 'nonce');

        $widget_key = isset($_POST['widget_key']) ? sanitize_text_field( wp_unslash($_POST['widget_key'] ) ) : '';
        $status = isset($_POST['status']) && $_POST['status'] === '1' ? '1' : '0';
        $tab_slug   = isset($_POST['tab']) ? sanitize_text_field( wp_unslash( $_POST['tab'] ) ) : 'widget';

        if ( empty( $widget_key ) ) {
            wp_send_json_error(['message' => __('Invalid widget key', 'easy-elements')]);
        }

        $available_elements = $this->easyel_elements_get_available_widgets();
        if (
            isset( $available_elements[ $widget_key ] )
            && ! empty( $available_elements[ $widget_key ]['is_pro'] )
            && ! easyel_premium_addon_active()
        ) {
            wp_send_json_error( [
                'message'        => '',
                'requires_addon' => true,
            ] );
        }

        $option_name = 'easy_element_' . $tab_slug . '_' . $widget_key;
        update_option($option_name, $status);

        wp_send_json_success([
            'message' => __('Widget setting updated successfully', 'easy-elements'),
            'status'  => $status,
        ]);
    }

    // AJAX handler for bulk actions
    public function easy_elements_bulk_action() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }
        check_ajax_referer('easy_elements_bulk_action_nonce', 'nonce');
        
        $bulk_action = isset($_POST['bulk_action']) ? sanitize_text_field(wp_unslash($_POST['bulk_action'])) : '';
        $tab = isset($_POST['tab']) ? sanitize_text_field( wp_unslash( $_POST['tab'] ) ) : 'widget';
        $status = $bulk_action === 'activate_all' ? '1' : '0';
        
        $available_elements = $this->easyel_elements_get_available_widgets();
        $updated_count = 0;

        $is_pro_active = easyel_premium_addon_active();

        foreach ($available_elements as $key => $widget) {
            if (isset($widget['tab']) && $widget['tab'] === $tab) {
                $option_name = 'easy_element_' . $tab . '_' . $key;

                if (!$is_pro_active && isset($widget['is_pro']) && $widget['is_pro']) {
                     update_option($option_name, '0'); 
                 } else {
                    $status = $bulk_action === 'activate_all' ? '1' : '0';
                    update_option($option_name, $status);
                }
            }
        }
        
        wp_send_json_success([
            'message' => sprintf('%d widgets %s successfully', $updated_count, $status ? 'activated' : 'deactivated'),
            'count' => $updated_count,
            'is_pro_active' => $is_pro_active
        ]);
    }


    public function easyel_elements_settings_callback() {
        $available_elements = $this->easyel_elements_get_available_widgets();

        $easyel_tabs = [
            'overview'   => __('Overview', 'easy-elements'),
            'widget'     => __('All Widgets', 'easy-elements'),
            'extensions' => __('All Extensions', 'easy-elements'),
            'advsettings' => __('Advanced Settings', 'easy-elements'),
            'userData' => __('API Settings', 'easy-elements'),
        ];

        // Extensions (e.g. the companion Pro plugin) can append tabs here.
        $easyel_tabs = (array) apply_filters( 'easyel_all_tab_list', $easyel_tabs );

        ?>
        <div class="easyel-plugin-main-settings-page">
            <div class="easyel-saving-indicator">
                Saving...
            </div>
            <div class="easyel-overview-header">
                <img src="<?php echo esc_url( EASYELEMENTS_DIR_URL . 'includes/Admin/img/easy-logo.png' ); ?>" alt="<?php echo esc_attr( 'logo' ); ?>">

            </div>
            <div class=" easyel-plugin-settings-wrapper">
                <div class="easyel-nav-tab-item">
                    <div class="easyel-nav-tab-wrapper easyel-border-radius-20">
                        <a href="#overview" class="easyel-nav-tab easyel-nav-tab-active" data-tab="overview"><i class="easyelIcon-home"></i> <?php esc_html_e ('Overview','easy-elements'); ?></a>
                        <a href="#widget" class="easyel-nav-tab" data-tab="widget"><i class="easyelIcon-widgets"></i><?php esc_html_e('All Widgets','easy-elements'); ?></a>
                        <a href="#extensions" class="easyel-nav-tab" data-tab="extensions"><i class="easyelIcon-extension"></i><?php esc_html_e('All Extensions','easy-elements'); ?></a>
                        <a href="#userData" class="easyel-nav-tab" data-tab="userData"><i class="easyelIcon-setting"></i><?php esc_html_e('API Settings','easy-elements'); ?></a>
                        <?php
                        
                        if ( ! function_exists( 'is_plugin_active' ) ) {
                            require_once ABSPATH . 'wp-admin/includes/plugin.php';
                        }
                        $easyel_elementor_active = did_action( 'elementor/loaded' ) || is_plugin_active( 'elementor/elementor.php' );
                        if ( $easyel_elementor_active ) : ?>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=starter-templates-dashboard' ) ); ?>" class="easyel-nav-tab"><i class="easyelIcon-monitor"></i><?php esc_html_e('Starter Templates','easy-elements'); ?></a>
                        <?php endif; ?>
                        <?php
                        /**
                         * Render extra nav-tab links.
                         *
                         * Free plugin renders nothing; the companion Pro plugin
                         * hooks in to draw its Activate License tab link.
                         */
                        do_action( 'easyel_render_settings_extra_tabs' );
                        ?>
                        <div class="easyel-tab-pro-link">
                            <a href="https://wpeasyelements.com/pricing/" class="easyel-nav-tab-pro" target="_blank">
                                <i class="easyelIcon-crown"></i>
                                <?php esc_html_e('Go Premium','easy-elements'); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Status Messages -->
                <div id="bulk-action-message" class="notice" style="display: none;"></div>

                <!-- Tab Content -->
                <div id="easyel-tab-content">
                    <?php foreach ( $easyel_tabs as $tab_slug => $tab_label ) : ?>
                        <div id="tab-<?php echo esc_attr($tab_slug); ?>" 
                            class="easyel-tab-panel easyel-border-radius-20 <?php echo esc_attr($tab_slug); ?>" 
                            style="<?php echo $tab_slug === 'overview' ? '' : 'display:none;'; ?>">

                            <?php 
                            if ( $tab_slug === 'widget' ) : ?>
                                <div class="easyel-addon-search easyel-dflex easyel-justify-between">
                                    <div class="easyel-widget-filter">
                                        <h1 class="easyel-dashboard-heading"><?php esc_html_e('Widgets','easy-elements');?></h1>
                                        <div class="easyel-widget-filter-button">
                                            <button type="button" id="easyel_all" class="easyel-action-btn active" data-filter="easyel_all"><?php esc_html_e('All', 'easy-elements'); ?></button>
                                            <button type="button" id="easyel_free" class="easyel-action-btn" data-filter="easyel_free"><?php esc_html_e('Free', 'easy-elements'); ?></button>
                                            <button type="button" id="easyel_pro" class="easyel-action-btn" data-filter="easyel_pro"><?php esc_html_e('Pro', 'easy-elements'); ?></button>
                                        </div>
                                    </div>
                                    <div class="easyel-widget-search-enable">
                                        <div class="easyel-widget-activeDeactivate-button">
                                            <button type="button" id="activate-all-btn"><?php esc_html_e('Activate All', 'easy-elements'); ?></button>
                                            <button type="button" id="deactivate-all-btn"><?php esc_html_e('Deactivate All', 'easy-elements'); ?></button>
                                        </div>
                                        <?php if ( $tab_slug === 'widget' ) { ?>
                                            <input type="text" id="element-search" placeholder="<?php esc_attr_e('Search widgets...', 'easy-elements'); ?>">
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php
                          
                            $tab_slug_safe = preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $tab_slug );
                            $tabs_root  = realpath( EASYELEMENTS_DIR_PATH . 'includes/Admin/settingstab' );
                            $tab_target = ( '' !== $tab_slug_safe && false !== $tabs_root )
                                ? realpath( $tabs_root . DIRECTORY_SEPARATOR . 'tab-' . $tab_slug_safe . '.php' )
                                : false;

                            if ( false !== $tab_target
                                && false !== $tabs_root
                                && 0 === strpos( $tab_target, $tabs_root . DIRECTORY_SEPARATOR )
                                && substr( $tab_target, -4 ) === '.php'
                            ) {
                                include $tab_target;
                            }

                            if ( $tab_slug === 'activate_license' ) {
                                /**
                                 * Render the Activate License panel.
                                 *
                                 * Free plugin renders nothing; the companion Pro plugin
                                 * hooks in to draw its own license UI.
                                 */
                                do_action( 'easyel_render_license_page' );
                            }

                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <!-- JavaScript functionality is handled by admin.js -->
        <?php
    }

    ////////****************** AJAX Toggle ********************************

    public function easyel_elements_enqueue_admin_assets($hook) {

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

        wp_enqueue_script(
            'easy-elements-select2-js',
            EASYELEMENTS_DIR_URL . 'assets/js/select2.min.js',
            ['jquery'],
            '1.0.0',
            true
        );
        if (strpos($hook, 'easy-elements') === false) {
            return;
        }

        wp_enqueue_media();
        
        // Enqueue admin JavaScript
        wp_enqueue_script(
            'easy-elements-admin',
            EASYELEMENTS_DIR_URL . 'assets/js/admin.js',
            ['jquery'],
            EASYELEMENTS_VER,
            true
        );

       

        

        // Localize script with all necessary data
        wp_localize_script('easy-elements-admin', 'easyElementsData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('easy_elements_nonce'),
            'widget_settings_nonce' => wp_create_nonce('easy_elements_widget_settings_nonce'),
            'bulk_action_nonce' => wp_create_nonce('easy_elements_bulk_action_nonce'),
            'advance_settings_nonce' => wp_create_nonce('easy_elements_save_advance_settings_nonce'),
            'js_animation_nonce' => wp_create_nonce('easyel_js_animation_nonce'),
            'strings' => [
                'confirm_activate_all' => __('Are you sure you want to activate all widgets?', 'easy-elements'),
                'confirm_deactivate_all' => __('Are you sure you want to deactivate all widgets?', 'easy-elements'),
                'processing' => __('Processing...', 'easy-elements'),
                'saving' => __('Saving...', 'easy-elements'),
                'saved' => __('Saved!', 'easy-elements'),
                'error' => __('Error!', 'easy-elements'),
                'updated' => __('Updated!', 'easy-elements'),
            ]
        ]);
    }

    function easyel_elements_get_available_widgets() {
        $widgets = [           
            'heading' => [
                'icon'        => 'easyelIcon-heading',
                'title'       => 'Heading',
                'description' => 'Add customizable headings with style options.',
                'demo_url'    => 'https://wpeasyelements.com/heading/',
                'docx_url'    => 'https://wpeasyelements.com/docs/heading/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'clients_logo' => [
                'icon'        => 'easyelIcon-clients-logo-grid',
                'title'       => 'Clients Logo Grid',
                'description' => 'Showcase client logos in a neat grid layout.',
                'demo_url'    => 'https://wpeasyelements.com/clients-logo-grid/',
                'docx_url'    => 'https://wpeasyelements.com/docs/clients-logo-grid',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'clients_logo_slider' => [
                'icon'        => 'easyelIcon-clients-logo-slider',
                'title'       => 'Clients Logo Slider',
                'description' => 'Display client logos in a slider format.',
                'demo_url'    => 'https://wpeasyelements.com/clients-logo-slider/',
                'docx_url'    => 'https://wpeasyelements.com/docs/clients-logo-slider/',
                'is_pro'      => true,
                'group'       => 'Creative Widgets',
                'tab' => 'widget',
            ],
            'simple_tab' => [
                'icon'        => 'easyelIcon-tab',
                'title'       => 'Tab',
                'description' => 'Add simple tab content.',
                'demo_url'    => 'https://wpeasyelements.com/tab',
                'docx_url'    => 'https://wpeasyelements.com/docs/tab',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'tab_advance' => [
                'icon'        => 'easyelIcon-tab',
                'title'       => 'Advanced Tab',
                'description' => 'Create advanced tab content.',
                'demo_url'    => 'https://wpeasyelements.com/advanced-tab/',
                'docx_url'    => 'https://wpeasyelements.com/docs/advanced-tab/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'testimonials' => [
                'icon'        => 'easyelIcon-testimonials-grid',
                'title'       => 'Testimonials Grid',
                'description' => 'Show testimonials in a grid format.',
                'demo_url'    => 'https://wpeasyelements.com/testimonials-grid',
                'docx_url'    => 'https://wpeasyelements.com/docs/testimonials-grid',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'testimonials_slider' => [
                'icon'        => 'easyelIcon-testimonials-slider',
                'title'       => 'Testimonials Slider',
                'description' => 'Display testimonials in a slider format.',
                'demo_url'    => 'https://wpeasyelements.com/testimonials-slider/',
                'docx_url'    => 'https://wpeasyelements.com/docs/testimonials-slider/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'image_carousel' => [
                'icon'        => 'easyelIcon-image-carousel',
                'title'       => 'Image Carousel',
                'description' => 'Create an image slider with multiple images.',
                'demo_url'    => 'https://wpeasyelements.com/image-carousel/',
                'docx_url'    => 'https://wpeasyelements.com/docs/image-carousel',
                'is_pro'      => true,
                'tab' => 'widget',
            ],            
            'image_reveal' => [
                'icon'        => 'easyelIcon-marquee-logo',
                'title'       => 'Reveal Image',
                'description' => 'reveal Image Here',
                'demo_url'    => 'https://wpeasyelements.com/reveal-image/',
                'docx_url'    => 'https://wpeasyelements.com/docs/reveal-image/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'rotate_text' => [
                'icon'        => 'easyelIcon-heading',
                'title'       => 'Rotate Text',
                'description' => 'rotate text here',
                'demo_url'    => 'https://wpeasyelements.com/rotate-text/',
                'docx_url'    => 'https://wpeasyelements.com/docs/rotate-text/',
                'is_pro'      => true,
                'tab'         => 'widget',
            ],
            'icon_box' => [
                'icon'        => 'easyelIcon-iconbox',
                'title'       => 'Info Box',
                'description' => 'Display content with an icon.',
                'demo_url'    => 'https://wpeasyelements.com/info-box',
                'docx_url'    => 'https://wpeasyelements.com/docs/info-box',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'process_grid' => [
                'icon'        => 'easyelIcon-process-grid',
                'title'       => 'Process Grid',
                'description' => 'Show process steps in a grid format.',
                'demo_url'    => 'https://wpeasyelements.com/process-grid',
                'docx_url'    => 'https://wpeasyelements.com/docs/process-grid',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'process_slider' => [
                'icon'        => 'easyelIcon-process-slider',
                'title'       => 'Process Slider',
                'description' => 'Show process steps in a slider.',
                'demo_url'    => 'https://wpeasyelements.com/process-slider/',
                'docx_url'    => 'https://wpeasyelements.com/docs/process-slider/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'team_grid' => [
                'icon'        => 'easyelIcon-team-grid',
                'title'       => 'Team Grid',
                'description' => 'Display team members in a grid format.',
                'demo_url'    => 'https://wpeasyelements.com/team-grid',
                'docx_url'    => 'https://wpeasyelements.com/docs/team-grid',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'team_slider' => [
                'icon'        => 'easyelIcon-team-slider',
                'title'       => 'Team Slider',
                'description' => 'Showcase team members in a slider format.',
                'demo_url'    => 'https://wpeasyelements.com/team-slider',
                'docx_url'    => 'https://wpeasyelements.com/docs/team-slider',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'image_comparison' => [
                'icon'        => 'easyelIcon-image-carousel',
                'title'       => 'Image Comparison',
                'description' => 'Create an Image Comparison.',
                'demo_url'    => 'https://wpeasyelements.com/image-comparison/',
                'docx_url'    => 'https://wpeasyelements.com/docs/image-comparison/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'contact_box' => [
                'icon'        => 'easyelIcon-contact-box',
                'title'       => 'Contact Box',
                'description' => 'Contact Box.',
                'demo_url'    => 'https://wpeasyelements.com/contact-box',
                'docx_url'    => 'https://wpeasyelements.com/docs/contact-box',
                'is_pro'      => false,
                'tab' => 'widget',
            ],        
            'icon_box_slider' => [
                'icon'        => 'easyelIcon-icon-box-slider',
                'title'       => 'Info Box Slider',
                'description' => 'Info Box Slider.',
                'demo_url'    => 'https://wpeasyelements.com/info-box-slider/',
                'docx_url'    => 'https://wpeasyelements.com/docs/info-box-slider/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],  
            'timeline_slider' => [
                'icon'        => 'easyelIcon-timeline-slider',    
                'title'       => 'Timeline Slider',
                'description' => 'Timeline Slider.',
                'demo_url'    => 'https://wpeasyelements.com/timeline-slider/',
                'docx_url'    => 'https://wpeasyelements.com/docs/timeline-slider',
                'is_pro'      => true,
                'group'       => 'Creative Widgets',
                'tab' => 'widget',
            ],      
            'faq' => [
                'icon'        => 'easyelIcon-faq-1',
                'title'       => 'FAQ',
                'description' => 'FAQ.',
                'demo_url'    => 'https://wpeasyelements.com/faq',
                'docx_url'    => 'https://wpeasyelements.com/docs/faq',
                'is_pro'      => false,
                'tab' => 'widget',
            ],       
            'blog_grid' => [
                'icon'        => 'easyelIcon-post-grid',
                'title'       => 'Post Grid',
                'description' => 'Post.',
                'demo_url'    => 'https://wpeasyelements.com/post-grid',
                'docx_url'    => 'https://wpeasyelements.com/docs/post-grid',
                'is_pro'      => false,
                'tab' => 'widget',
            ],        
            'post_slider' => [
                'icon'        => 'easyelIcon-post-slider',
                'title'       => 'Post Slider',
                'description' => 'Post Slider.',
                'demo_url'    => 'https://wpeasyelements.com/post-slider/',
                'docx_url'    => 'https://wpeasyelements.com/docs/post-slider/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'post_list' => [
                'icon'        => 'easyelIcon-post-grid',
                'title'       => 'Post List',
                'description' => 'Post List.',
                'demo_url'    => 'https://wpeasyelements.com/post-list/',
                'docx_url'    => 'https://wpeasyelements.com/docs/post-list/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],  
            'easy_cf7' => [
                'icon'        => 'easyelIcon-contact-form',
                'title'       => 'Contact Form 7',
                'description' => 'Contact form 7.',
                'demo_url'    => 'https://wpeasyelements.com/contact-form-7',
                'docx_url'    => 'https://wpeasyelements.com/docs/contact-form-7',
                'is_pro'      => false,
                'group'       => 'Form Widgets',
                'tab' => 'widget',
            ],      
            'video' => [
                'icon'        => 'easyelIcon-video',
                'title'       => 'Video',
                'description' => 'Video.',
                'demo_url'    => 'https://wpeasyelements.com/video',
                'docx_url'    => 'https://wpeasyelements.com/docs/video',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'pricing_table' => [
                'icon'        => 'easyelIcon-pricing-table',
                'title'       => 'Pricing Table',
                'description' => 'Pricing Table.',
                'demo_url'    => 'https://wpeasyelements.com/pricing-table',
                'docx_url'    => 'https://wpeasyelements.com/docs/pricing-table',
                'is_pro'      => false,
                'group'       => 'Marketing Widgets',
                'tab' => 'widget',
            ],                               
            'pricing_list' => [
                'icon'        => 'easyelIcon-pricing-list',
                'title'       => 'Pricing List',
                'description' => 'Pricing List.',
                'demo_url'    => 'https://wpeasyelements.com/pricing-list/',
                'docx_url'    => 'https://wpeasyelements.com/docs/pricing-list/',
                'is_pro'      => true,
                'group'       => 'Marketing Widgets',
                'tab' => 'widget',
            ],
            'pricing_tab' => [
                'icon'        => 'easyelIcon-pricing-table',
                'title'       => 'Pricing Tab',
                'description' => 'Pricing Tab.',
                'demo_url'    => 'https://wpeasyelements.com/pricing-tab/',
                'docx_url'    => 'https://wpeasyelements.com/docs/pricing-tab/',
                'is_pro'      => true,
                'group'       => 'Marketing Widgets',
                'tab' => 'widget',
            ],         
            'service_list' => [
                'icon'        => 'easyelIcon-service-list',
                'title'       => 'Service List',
                'description' => 'Service List.',
                'demo_url'    => 'https://wpeasyelements.com/service-list',
                'docx_url'    => 'https://wpeasyelements.com/docs/service-list/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],      
            'advance_service' => [
                'icon'        => 'easyelIcon-service-list',
                'title'       => 'Advance Service',
                'description' => 'Advance Service.',
                'demo_url'    => 'https://wpeasyelements.com/advance-service',
                'docx_url'    => 'https://wpeasyelements.com/docs/advance-service/',
                'is_pro'      => true,
                'tab'         => 'widget',
            ],
            'service_card' => [
                'icon'        => 'easyelIcon-service-list',
                'title'       => 'Service Card',
                'description' => 'Service Card.',
                'demo_url'    => 'https://wpeasyelements.com/service-card',
                'docx_url'    => 'https://wpeasyelements.com/docs/service-card/',
                'is_pro'      => true,
                'tab'         => 'widget',
            ],
            'feature_list' => [
                'icon'        => 'easyelIcon-service-list',
                'title'       => 'Feature List',
                'description' => 'Feature List.',
                'demo_url'    => 'https://wpeasyelements.com/features-list',
                'docx_url'    => 'https://wpeasyelements.com/docs/features-list/',
                'is_pro'      => false,
                'tab'         => 'widget',
            ],
            'process_list' => [
                'icon'        => 'easyelIcon-process-list',
                'title'       => 'Process List',
                'description' => 'Process List.',
                'demo_url'    => 'https://wpeasyelements.com/process-list/',
                'docx_url'    => 'https://wpeasyelements.com/docs/process-list/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'marquee_logo' => [
                'icon'        => 'easyelIcon-marquee-logo',
                'title'       => 'Marquee',
                'description' => 'Marquee.',
                'demo_url'    => 'https://wpeasyelements.com/marquee/',
                'docx_url'    => 'https://wpeasyelements.com/docs/marquee/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'button' => [
                'icon'        => 'easyelIcon-button',
                'title'       => 'Button',
                'description' => 'Button.',
                'demo_url'    => 'https://wpeasyelements.com/button/',
                'docx_url'    => 'https://wpeasyelements.com/docs/button',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'copyright' => [
                'icon'        => 'easyelIcon-copyright',
                'title'       => 'Copyright',
                'description' => 'Display copyright text with auto-updating year for the footer.',
                'demo_url'    => 'https://wpeasyelements.com/copyright/',
                'docx_url'    => 'https://wpeasyelements.com/docs/copyright',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'social_share' => [
                'icon'        => 'easyelIcon-social-share',
                'title'       => 'Social Share',
                'description' => 'Social Share.',
                'demo_url'    => 'https://wpeasyelements.com/docs/social-share/',
                'docx_url'    => 'https://wpeasyelements.com/docs/social-share/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'social_icon' => [
                'icon'        => 'easyelIcon-social-icons',
                'title'       => 'Social Icon',
                'description' => 'Social Icon.',
                'demo_url'    => 'https://wpeasyelements.com/social-icon',
                'docx_url'    => 'https://wpeasyelements.com/docs/social-icon',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'breadcrumb' => [
                'icon'        => 'easyelIcon-breadcumb',
                'title'       => 'Breadcrumb',
                'description' => 'Breadcrumb.',
                'demo_url'    => 'https://wpeasyelements.com/breadcrumb',
                'docx_url'    => 'https://wpeasyelements.com/docs/breadcrumb',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'easy_slider' => [
                'icon'        => 'easyelIcon-slider',
                'title'       => 'Slider',
                'description' => 'Slider.',
                'demo_url'    => 'https://wpeasyelements.com/slider',
                'docx_url'    => 'https://wpeasyelements.com/docs/slider',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'image_accordion' => [
                'icon'        => 'easyelIcon-image-accordion',
                'title'       => 'Image Accordion',
                'description' => 'Image Accordion',
                'demo_url'    => 'https://wpeasyelements.com/image-accordion/',
                'docx_url'    => 'https://wpeasyelements.com/docs/image-accordion/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'domain_search' => [
                'icon'        => 'easyelIcon-domain-search',
                'title'       => 'Domain Search',
                'description' => 'Domain Search.',
                'demo_url'    => 'https://wpeasyelements.com/domain-search/',
                'docx_url'    => 'https://wpeasyelements.com/docs/domain-search/',
                'is_pro'      => false,
                'group'       => 'Form Widgets',
                'tab' => 'widget',
            ],
            'featured_project' => [
                'icon'        => 'easyelIcon-custom-projects',    
                'title'       => 'Custom Projects',
                'description' => 'Custom Projects.',
                'demo_url'    => '#',
                'docx_url'    => '#',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'advance_button' => [
                'icon'        => 'easyelIcon-button',    
                'title'       => 'Advance Button',
                'description' => 'Advance Button.',
                'demo_url'    => 'https://wpeasyelements.com/advance-button/',
                'docx_url'    => 'https://wpeasyelements.com/docs/advance-button',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'hr_image_scroll' => [
                'icon'        => 'easyelIcon-image-horizontal-scroll',    
                'title'       => 'Horizontal Scroll',
                'description' => 'Horizontal Scroll.',
                'demo_url'    => 'https://wpeasyelements.com/horizontal-scroll/',
                'docx_url'    => 'https://wpeasyelements.com/docs/horizontal-scroll/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'scrolltrigger_title' => [
                'icon'        => 'easyelIcon-title-scrolltrigger',    
                'title'       => 'Content ScrollTrigger',
                'description' => 'Content ScrollTrigger.',
                'demo_url'    => 'https://wpeasyelements.com/horizontal-scroll/',
                'docx_url'    => 'https://wpeasyelements.com/horizontal-scroll/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],            
            'Flip_Box' => [
                'icon'        => 'easyelIcon-flip-box',    
                'title'       => 'Flip Box',
                'description' => 'Flip Box.',
                'demo_url'    => 'https://wpeasyelements.com/flip-box',
                'docx_url'    => 'https://wpeasyelements.com/docs/flip-box',
                'is_pro'      => true,
                'group'       => 'Creative Widgets',
                'tab' => 'widget',
            ],         
            'easy_offcanvas' => [
                'icon'        => 'easyelIcon-canvas',    
                'title'       => 'Offcanvas',
                'description' => 'Offcanvas.',
                'demo_url'    => 'https://wpeasyelements.com/offcanvas',
                'docx_url'    => 'https://wpeasyelements.com/docs/offcanvas',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget',
                'tab' => 'widget',
            ],
            'site_logo' => [
                'icon'        => 'easyelIcon-site-logo',
                'title'       => 'Site Logo',
                'description' => 'Display your website logo easily with this widget.',
                'demo_url'    => 'https://wpeasyelements.com/site-logo/',
                'docx_url'    => 'https://wpeasyelements.com/docs/site-logo/',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget',
                'tab' => 'widget',
            ],
            'search' => [
                'icon'        => 'easyelIcon-search',
                'title'       => 'Simple Search',
                'description' => 'Search All Content.',
                'demo_url'    => 'https://wpeasyelements.com/search',
                'docx_url'    => 'https://wpeasyelements.com/docs/search',
                'is_pro'      => false,
                'group'       => 'Form Widgets',
                'tab' => 'widget',
            ],
            'navigation_menu' => [
                'icon'        => 'easyelIcon-navigation',
                'title'       => 'Navigation Menu',
                'description' => 'Navigation Menu.',
                'demo_url'    => 'https://wpeasyelements.com/',
                'docx_url'    => '#',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget',
                'tab' => 'widget',
            ],
            'single_navigation_menu' => [
                'icon'        => 'easyelIcon-navigation',
                'title'       => 'Single Navigation Menu',
                'description' => 'SingleNavigation Menu.',
                'demo_url'    => 'https://wpeasyelements.com/single-navigation/',
                'docx_url'    => 'https://www.youtube.com/watch?v=d-lz-EfK9eA',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget',
                'tab' => 'widget',
            ],
            'mega_menu_widget' => [
                'icon'        => 'easyelIcon-navigation',
                'title'       => 'Mega Menu',
                'description' => 'Mega Menu.',
                'demo_url'    => 'https://wpeasyelements.com/megamenu-builder/',
                'docx_url'    => 'https://wpeasyelements.com/docs/megamenu-builder/',
                'is_pro'      => true,
                'group'       => 'Header & Footer Widget',
                'tab' => 'widget',
            ],
            'vertical_navigation_menu' => [
                'icon'        => 'easyelIcon-navigation',
                'title'       => 'Vertical Menu',
                'description' => 'Vertical Menu.',
                'demo_url'    => 'https://wpeasyelements.com/vertical-menu/',
                'docx_url'    => 'https://wpeasyelements.com/docs/vertical-menu/',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget',
                'tab' => 'widget',
            ],
            'page_title' => [
                'icon'        => 'easyelIcon-page-title',
                'title'       => 'Page Title',
                'description' => 'Page Title.',
                'demo_url'    => 'https://wpeasyelements.com/docs/page-title/',
                'docx_url'    => 'https://wpeasyelements.com/docs/page-title/',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget',
                'tab' => 'widget',
            ],
            'post_tags' => [
                'icon'        => 'easyelIcon-post-tag',    
                'title'       => 'Current Post Tags',
                'description' => 'Current Post Tags.',
                'demo_url'    => 'https://wpeasyelements.com/maximize-value-from-your-marketing-subscription/#single__link',
                'docx_url'    => 'https://wpeasyelements.com/maximize-value-from-your-marketing-subscription/',
                'is_pro'      => false,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'post_author' => [
                'icon'        => 'easyelIcon-author',    
                'title'       => 'Post Author Info',
                'description' => 'Post Author Info.',
                'demo_url'    => 'https://wpeasyelements.com/maximize-value-from-your-marketing-subscription/#single__link',
                'docx_url'    => 'https://wpeasyelements.com/maximize-value-from-your-marketing-subscription/',
                'is_pro'      => false,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'post_title' => [
                'icon'        => 'easyelIcon-post-title',
                'title'       => 'Post Title',
                'description' => 'Post Title.',
                'demo_url'    => 'https://wpeasyelements.com/docs/post-title/',
                'docx_url'    => 'https://wpeasyelements.com/docs/post-title/',
                'is_pro'      => false,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'post_content' => [
                'icon'        => 'easyelIcon-post-content',
                'title'       => 'Post Content',
                'description' => 'Post Content.',
                'demo_url'    => 'https://wpeasyelements.com/maximize-value-from-your-marketing-subscription/',
                'docx_url'    => 'https://wpeasyelements.com/maximize-value-from-your-marketing-subscription/',
                'is_pro'      => false,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],        
            'excerpt' => [
                'icon'        => 'easyelIcon-post-excerpt',
                'title'       => 'Post Excerpt',
                'description' => 'Post Excerpt.',
                'demo_url'    => 'https://wpeasyelements.com/docs/post-excerpt/',
                'docx_url'    => 'https://wpeasyelements.com/docs/post-excerpt/',
                'is_pro'      => false,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],  
            'related_post' => [
                'icon'        => 'easyelIcon-related-post',
                'title'       => 'Related Post',
                'description' => 'Related Post.',
                'demo_url'    => 'https://wpeasyelements.com/docs/related-post/',
                'docx_url'    => 'https://wpeasyelements.com/docs/related-post/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ], 
            'post_pagination' => [
                'icon'        => 'easyelIcon-pagination',
                'title'       => 'Post Pagination',
                'description' => 'Post Pagination.',
                'demo_url'    => 'https://wpeasyelements.com/docs/post-pagination/',
                'docx_url'    => 'https://wpeasyelements.com/docs/post-pagination/',
                'is_pro'      => false,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'post_meta' => [
                'icon'        => 'easyelIcon-meta',
                'title'       => 'Post Meta',
                'description' => 'Post Meta.',
                'demo_url'    => 'https://wpeasyelements.com/docs/post-meta/',
                'docx_url'    => 'https://wpeasyelements.com/docs/post-meta/',
                'is_pro'      => false,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'post_comments' => [
                'icon'        => 'easyelIcon-comments',
                'title'       => 'Post Comments',
                'description' => 'Post Comments.',
                'demo_url'    => 'https://wpeasyelements.com/maximize-value-from-your-marketing-subscription/#single__link',
                'docx_url'    => 'https://wpeasyelements.com/maximize-value-from-your-marketing-subscription/#single__link',
                'is_pro'      => false,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'featured_image' => [
                'icon'        => 'easyelIcon-image-carousel',
                'title'       => 'Featured Image',
                'description' => 'Featured Image.',
                'demo_url'    => 'https://wpeasyelements.com/docs/featured-image/',
                'docx_url'    => 'https://wpeasyelements.com/docs/featured-image/',
                'is_pro'      => false,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'easy_scroll_to_top' => [
                'icon'        => 'easyelIcon-scroll-top',
                'title'       => 'Scroll Top',
                'description' => 'Scroll Top.',
                'demo_url'    => 'https://wpeasyelements.com/docs/scroll-to-top/',
                'docx_url'    => 'https://wpeasyelements.com/docs/scroll-to-top/',
                'is_pro'      => false,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ], 
            'easy_table' => [
                'icon'        => 'easyelIcon-clients-logo-grid',
                'title'       => 'Table',
                'description' => 'Table.',
                'demo_url'    => 'https://wpeasyelements.com/table',
                'docx_url'    => 'https://wpeasyelements.com/docs/table',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'advance_image' => [
                'icon'        => 'easyelIcon-image-carousel',
                'title'       => 'Advance Image',
                'description' => 'Advance Image Here.',
                'demo_url'    => 'https://wpeasyelements.com/advance-image/',
                'docx_url'    => 'https://wpeasyelements.com/docs/advance-image/',
                'is_pro'      => true,
                'group'       => 'Animations',
                'tab' => 'widget',
            ],
            'bmi_calculator' => [
                'icon'        => 'easyelIcon-calculator',
                'title'       => 'BMI Calculator',
                'description' => 'BMI Calculator.',
                'demo_url'    => 'https://wpeasyelements.com/bmi-calculator/',
                'docx_url'    => 'https://wpeasyelements.com/docs/bmi-calculator/',
                'is_pro'      => true,
                'group'       => 'Creative Widgets',
                'tab' => 'widget',
            ],                        
            'typewriter' => [
                'icon'        => 'easyelIcon-heading',
                'title'       => 'Typewriter',
                'description' => 'Animated typewriter text effect.',
                'demo_url'    => 'https://wpeasyelements.com/typewriter/',
                'docx_url'    => 'https://wpeasyelements.com/docs/typewriter/',
                'is_pro'      => true,
                'group'       => 'Animations',
                'tab' => 'widget',
            ],
            'animated_title' => [
                'icon'        => 'easyelIcon-animated-title',
                'title'       => 'Animated Title',
                'description' => 'Animated title text effect.',
                'demo_url'    => 'https://wpeasyelements.com/animated-title/',
                'docx_url'    => 'https://wpeasyelements.com/docs/',
                'is_pro'      => true,
                'group'       => 'Animations',
                'tab' => 'widget',
            ],
            'easytext_animation' => [
                'icon'        => 'easyelIcon-heading',
                'title'       => 'Animated Text',
                'description' => 'Animated text animation effect.',
                'demo_url'    => 'https://wpeasyelements.com/text-animation/',
                'docx_url'    => 'https://wpeasyelements.com/docs/text-animation/',
                'is_pro'      => true,
                'group'       => 'Animations',
                'tab' => 'widget',
            ],
            'easy_feature' => [
                'icon'        => 'easyelIcon-marquee-logo',
                'title'       => 'Hover Image Box',
                'description' => 'Hover image box content.',
                'demo_url'    => 'https://wpeasyelements.com/hover-image-box/',
                'docx_url'    => 'https://wpeasyelements.com/docs/hover-image-box/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],        
            'easy_gallery' => [
                'icon'        => 'easyelIcon-marquee-logo',
                'title'       => 'Simple Gallery',
                'description' => 'Gallery',
                'demo_url'    => 'https://wpeasyelements.com/simple-gallery/',
                'docx_url'    => 'https://wpeasyelements.com/docs/simple-gallery/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'image_gallery_filter' => [
                'icon'        => 'easyelIcon-filterable-gallery',
                'title'       => 'Filterable Gallery',
                'description' => 'filterable Gallery',
                'demo_url'    => 'https://wpeasyelements.com/gallery-filter/',
                'docx_url'    => 'https://wpeasyelements.com/gallery-filter/',
                'is_pro'      => true,
                'group'       => 'Creative Widgets',
                'tab' => 'widget',
            ],
            'masonry_gallery' => [
                'icon'        => 'easyelIcon-filterable-gallery',
                'title'       => 'Masonry Gallery',
                'description' => 'Masonry Gallery',
                'demo_url'    => 'https://wpeasyelements.com/masonry-gallery/',
                'docx_url'    => 'https://wpeasyelements.com/masonry-gallery/',
                'is_pro'      => true,
                'group'       => 'Creative Widgets',
                'tab' => 'widget',
            ],
            'portfolio_pro' => [
                'icon'        => 'easyelIcon-marquee-logo',
                'title'       => 'Portfolio',
                'description' => 'Portfolio',
                'demo_url'    => 'https://wpeasyelements.com/portfolio/',
                'docx_url'    => 'https://wpeasyelements.com/docs/portfolio/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'portfolio_filter' => [
                'icon'        => 'easyelIcon-marquee-logo',
                'title'       => 'Portfolio Filter',
                'description' => 'Portfolio filter',
                'demo_url'    => 'https://wpeasyelements.com/portfolio-filter/',
                'docx_url'    => 'https://wpeasyelements.com/docs/portfolio-filter/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'protected_content' => [
                'icon'        => 'easyelIcon-protected-content',
                'title'       => 'Protected Content',
                'description' => 'This Widget is protected content',
                'demo_url'    => 'https://wpeasyelements.com/protected-contents/',
                'docx_url'    => 'https://wpeasyelements.com/docs/protected-contents/',
                'is_pro'      => true,
                'group'       => 'Creative Widgets',
                'tab' => 'widget',
            ],
            'advanced_search' => [
                'icon'        => 'easyelIcon-advance-search',
                'title'       => 'Advanced Search',
                'description' => 'This Widget is advanced search',
                'demo_url'    => 'https://wpeasyelements.com/advanced-search/',
                'docx_url'    => 'https://wpeasyelements.com/docs/advanced-search/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'enable_image_hover_effect' => [
                'icon'        => 'easyelIcon-marquee-logo',
                'title'       => 'Image Hover Effect',
                'description' => 'This Widget is image hover effect',
                'demo_url'    => 'https://wpeasyelements.com/image-hover-effect/',
                'docx_url'    => 'https://wpeasyelements.com/docs/image-hover-effect/',
                'is_pro'      => true,
                'group'       => 'Creative Widgets',
                'tab' => 'widget',
            ],
            'image_hotspot' => [
                'icon'        => 'easyelIcon-marquee-logo',
                'title'       => 'image Hotspot',
                'description' => 'This Widget is Image Hotspot',
                'demo_url'    => 'https://wpeasyelements.com/image-hotspot/',
                'docx_url'    => 'https://wpeasyelements.com/docs/image-hotspot/',
                'is_pro'      => true,
                'group'       => 'Creative Widgets',
                'tab' => 'widget',
            ],
            'hr_image_scroll' => [
                'icon'        => 'easyelIcon-image-horizontal-scroll',
                'title'       => 'ScrollTrigger',
                'description' => 'This Widget is image hover effect',
                'demo_url'    => 'https://wpeasyelements.com/horizontal-scroll/',
                'docx_url'    => 'https://wpeasyelements.com/docs/horizontal-scroll/',
                'is_pro'      => true,
                'group'       => 'Animations',
                'tab' => 'widget',
            ],
            'archive_post' => [
                'icon'        => 'easyelIcon-post-grid',
                'title'       => 'Archive Post',
                'description' => 'This Widget is archive post widget',
                'demo_url'    => 'https://wpeasyelements.com/post-grid/',
                'docx_url'    => 'https://wpeasyelements.com/docs/post-grid/',
                'is_pro'      => false,
                'tab' => 'widget',
             ],
            'category_list' => [
                'icon'        => 'easyelIcon-post-grid',
                'title'       => 'Category List',
                'description' => 'Show categories or any taxonomy in a list or grid layout.',
                'demo_url'    => 'https://wpeasyelements.com/',
                'docx_url'    => 'https://wpeasyelements.com/docs/',
                'is_pro'      => false,
                'tab' => 'widget',
             ],
            'counter' => [
                'icon'        => 'easyelIcon-counter',
                'title'       => 'Counter',
                'description' => 'This is a widget that counts up.',
                'demo_url'    => 'https://wpeasyelements.com/counter/',
                'docx_url'    => 'https://wpeasyelements.com/docs/counter/',
                'is_pro'      => false,
                'group'       => 'Creative Widgets',
                'tab'         => 'widget',
            ],    
            'countdown' => [
                'icon'        => 'easyelIcon-countdown',
                'title'       => 'Countdown',
                'description' => 'This is a countdown widget.',
                'demo_url'    => 'https://wpeasyelements.com/countdown/',
                'docx_url'    => 'https://wpeasyelements.com/docs/countdown/',
                'is_pro'      => false,
                'group'       => 'Creative Widgets',
                'tab'         => 'widget',
            ],
            'easy_progress' => [
                'icon'        => 'easyelIcon-progress-bar',
                'title'       => 'Progress Bar',
                'description' => 'This is a Progress bar widget.',
                'demo_url'    => 'https://wpeasyelements.com/progressbar/',
                'docx_url'    => 'https://wpeasyelements.com/docs/progressbar/',
                'is_pro'      => false,
                'group'       => 'Creative Widgets',
                'tab'         => 'widget',
            ],            
            'facebook_feed' => [
                'icon'        => 'easyelIcon-facebook-feed',
                'title'       => 'Facebook Feed',
                'description' => 'This Widget is facebook feeds',
                'demo_url'    => 'https://wpeasyelements.com/facebook-feed/',
                'docx_url'    => 'https://wpeasyelements.com/docs/facebook-feed/',
                'is_pro'      => true,
                'group'       => 'Social Media Feeds',
                'tab' => 'widget',
            ],
            'instagram_feed' => [
                'icon'        => 'easyelIcon-instagram-feed',
                'title'       => 'Instagram Feed',
                'description' => 'This Widget is instagram feeds',
                'demo_url'    => 'https://wpeasyelements.com/instagram-feed/',
                'docx_url'    => 'https://wpeasyelements.com/docs/instagram-feed/',
                'is_pro'      => true,
                'group'       => 'Social Media Feeds',
                'tab' => 'widget',
            ],
            'login_register' => [
                'icon'        => 'easyelIcon-login',
                'title'       => 'Login / Register',
                'description' => 'This Widget is login and register form',
                'demo_url'    => 'https://wpeasyelements.com/register/',
                'docx_url'    => 'https://wpeasyelements.com/docs/login-register/',
                'is_pro'      => false,
                'group'       => 'Form Widgets',
                'tab' => 'widget',
            ], 
            'table_of_content' => [
                'icon'        => 'easyelIcon-pricing-list',
                'title'       => 'Table Of Content',
                'description' => 'This is a widget that creates a table of content.',
                'demo_url'    => 'https://wpeasyelements.com/table-of-content/',
                'docx_url'    => 'https://wpeasyelements.com/docs/table-of-content/',
                'is_pro'      => true,
                'group'       => 'Creative Widgets',
                'tab'         => 'widget',
            ],
            'product_grid_lite' => [
                'icon'        => 'easyelIcon-process-grid',
                'title'       => 'Product Grid Lite',
                'description' => 'Display WooCommerce products in a responsive grid (lite version).',
                'demo_url'    => 'https://wpeasyelements.com/product-grid-lite/',
                'docx_url'    => 'https://wpeasyelements.com/docs/product-grid-lite/',
                'is_pro'      => false,
                'group'       => 'WooCommerce Widgets',
                'tab'         => 'widget',
            ],
            'product_grid' => [
                'icon'        => 'easyelIcon-process-grid',
                'title'       => 'Product Grid',
                'description' => 'Advanced WooCommerce product grid with filter tabs, quick view, compare and wishlist.',
                'demo_url'    => 'https://wpeasyelements.com/product-grid/',
                'docx_url'    => 'https://wpeasyelements.com/docs/product-grid/',
                'is_pro'      => true,
                'group'       => 'WooCommerce Widgets',
                'tab'         => 'widget',
            ],
            'product_list' => [
                'icon'        => 'easyelIcon-process-list',
                'title'       => 'Product List',
                'description' => 'Display WooCommerce products in a list layout with details and actions.',
                'demo_url'    => 'https://wpeasyelements.com/product-list/',
                'docx_url'    => 'https://wpeasyelements.com/docs/product-list/',
                'is_pro'      => true,
                'group'       => 'WooCommerce Widgets',
                'tab'         => 'widget',
            ],
            'product_slider' => [
                'icon'        => 'easyelIcon-process-slider',
                'title'       => 'Product Slider',
                'description' => 'Showcase WooCommerce products in a responsive slider/carousel.',
                'demo_url'    => 'https://wpeasyelements.com/product-slider/',
                'docx_url'    => 'https://wpeasyelements.com/docs/product-slider/',
                'is_pro'      => true,
                'group'       => 'WooCommerce Widgets',
                'tab'         => 'widget',
            ],
            'product_categories' => [
                'icon'        => 'easyelIcon-filterable-gallery',
                'title'       => 'Product Categories',
                'description' => 'Display WooCommerce product categories in a clean grid.',
                'demo_url'    => 'https://wpeasyelements.com/product-categories/',
                'docx_url'    => 'https://wpeasyelements.com/docs/product-categories/',
                'is_pro'      => true,
                'group'       => 'WooCommerce Widgets',
                'tab'         => 'widget',
            ],
            'product_categories_slider' => [
                'icon'        => 'easyelIcon-filterable-gallery',
                'title'       => 'Product Categories Slider',
                'description' => 'Showcase WooCommerce product categories in a slider.',
                'demo_url'    => 'https://wpeasyelements.com/product-categories-slider/',
                'docx_url'    => 'https://wpeasyelements.com/docs/product-categories-slider/',
                'is_pro'      => true,
                'group'       => 'WooCommerce Widgets',
                'tab'         => 'widget',
            ],
            'product_category_tab' => [
                'icon'        => 'easyelIcon-process-grid',
                'title'       => 'Product Category Tab',
                'description' => 'Tabbed WooCommerce categories with a banner and product cards per tab.',
                'demo_url'    => 'https://wpeasyelements.com/product-category-tab/',
                'docx_url'    => 'https://wpeasyelements.com/docs/product-category-tab/',
                'is_pro'      => true,
                'group'       => 'WooCommerce Widgets',
                'tab'         => 'widget',
            ],
            'product_collections' => [
                'icon'        => 'easyelIcon-process-grid',
                'title'       => 'Product Collection',
                'description' => 'Curate WooCommerce product collections as a grid.',
                'demo_url'    => 'https://wpeasyelements.com/product-collections/',
                'docx_url'    => 'https://wpeasyelements.com/docs/product-collections/',
                'is_pro'      => true,
                'group'       => 'WooCommerce Widgets',
                'tab'         => 'widget',
            ],
            'product_collections_slider' => [
                'icon'        => 'easyelIcon-process-slider',
                'title'       => 'Product Collections Slider',
                'description' => 'Curate WooCommerce product collections as a slider.',
                'demo_url'    => 'https://wpeasyelements.com/product-collections-slider/',
                'docx_url'    => 'https://wpeasyelements.com/docs/product-collections-slider/',
                'is_pro'      => true,
                'group'       => 'WooCommerce Widgets',
                'tab'         => 'widget',
            ],
            'mini_cart' => [
                'icon'        => 'easyelIcon-canvas',
                'title'       => 'Mini Cart',
                'description' => 'Compact WooCommerce mini cart with dropdown preview.',
                'demo_url'    => 'https://wpeasyelements.com/mini-cart/',
                'docx_url'    => 'https://wpeasyelements.com/docs/mini-cart/',
                'is_pro'      => false,
                'group'       => 'WooCommerce Widgets',
                'tab'         => 'widget',
            ],
        ];

        $feature_widget_map = [
            'enable_megamenu_builder' => 'mega_menu_widget',
        ];

        //  Condition apply
        if ( function_exists( 'easyel_element_is_enabled' ) ) {
            foreach ( $feature_widget_map as $feature => $widget_key ) {
                if ( ! easyel_element_is_enabled( $feature ) ) {
                    unset( $widgets[ $widget_key ] );
                }
            }
        }

        return apply_filters( 'easyel_available_widgets', $widgets );

    }

    /**
     * Admin page HTML
     */
    function easyel_custom_fonts_page_html() {
        if ( ! current_user_can( 'manage_options' ) ) return;

        if ( ! defined( 'EASY_ELEMENTS_PRO_ACTIVE' ) || ! EASY_ELEMENTS_PRO_ACTIVE ) { ?>
        <div class="easyel-custom-font-page">
            <div class="easyel-custom-font-banner">
                <div class="easyel-custom-font-banner-content">
                    <h1><?php esc_html_e( 'Custom Fonts', 'easy-elements' ); ?></h1>

                    <p>
                        <?php esc_html_e(
                            'Upload and manage custom fonts.',
                            'easy-elements'
                        ); ?>
                    </p>
                    <p>Upload TTF, OTF, WOFF</p>
                    <a
                        href="https://wpeasyelements.com/pricing/"
                        target="_blank"
                        class="easyel-upgrade-btn-custom-font"
                    >
                        <?php esc_html_e( 'Upgrade to Pro', 'easy-elements' ); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php 
        return;
        }

         $pro_version = easyel_get_pro_clean_version();

            if (
                $pro_version &&
                version_compare( $pro_version, '1.0.8', '>=' )
            ) {
            if (
                did_action( 'plugins_loaded' ) &&
                class_exists( '\EasyElements_Elementor\Pro\CustomFont\FontUploader' ) ) {
                $manager = \EasyElements_Elementor\Pro\CustomFont\FontUploader::get_instance();

                if ( method_exists( $manager, 'easyel_pro_custom_fonts_page' ) ) {
                    $manager->easyel_pro_custom_fonts_page();
                }
            }
        } else {
             easyel_pro_custom_fonts_page();
        }

    }

    /**
     * Hide admin notices on the Easy Elements dashboard page.
     */
    function easyel_hide_admin_notices() {
        $screen = get_current_screen();

        if ( $screen && 'toplevel_page_easy-elements-dashboard' === $screen->id ) {

            $custom_css = '
                /* Hide all common notices */
                .notice,
                .updated,
                .error,
                .update-nag,
                .notice-success,
                .notice-error,
                .notice-warning {
                    display: none !important;
                }

                /* But allow EasyEL notice */
                .easyel-promo-notice {
                    display: block !important;
                }
            ';

            $handle = 'easyel-admin-hide-notices';
            wp_register_style( $handle, false, [], EASYELEMENTS_VER );
            wp_enqueue_style( $handle );
            wp_add_inline_style( $handle, $custom_css );
        }
    }

    public function easyel_smooth_scroller_popup_display() {

        $options = get_option('easyel_scroll_smoother_settings', []);


        ?>
        <div class="easyel-smooth-scroll-popup">
            <div class="ajax-search-close-icon">
                <a class="easyel-smooth-scroll-popup-close-icon rbt-round-btn" href="#">
                    <svg width="14" height="14" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.08366 1.73916L8.26116 0.916656L5.00033 4.17749L1.73949 0.916656L0.916992 1.73916L4.17783 4.99999L0.916992 8.26082L1.73949 9.08332L5.00033 5.82249L8.26116 9.08332L9.08366 8.26082L5.82283 4.99999L9.08366 1.73916Z" fill="currentColor"></path>
                    </svg>
                </a>
            </div>

            <div class="wrapper">
                <h2 class="easyel-smooth-scroll-heading"><?php echo esc_html__("Smooth Scroll Settings","easy-elements"); ?></h2>
                <form method="post" action="">
                    <div class="easyel-smooth-scroll-settings-main-wrapper">

                        <!-- Smooth Speed -->
                        <div class="easyel-smooth-scroll-item">
                            <span class="easy-field-label"><?php echo esc_html__("Speed (2.0–5):","easy-elements"); ?></span>
                            <label class="easy-field-item">
                                <input type="number" 
                                    name="smooth_scroll_speed" 
                                    min="0.1" max="5" step="0.1"
                                    value="<?php echo isset($options['smooth_scroll_speed']) ? esc_attr($options['smooth_scroll_speed']) : '2.5'; ?>">
                            </label>
                        </div>

                        <!-- Normalize Scroll -->
                        <div class="easyel-smooth-scroll-item">
                            <span class="easy-field-label"><?php echo esc_html__("Normalize Scroll","easy-elements"); ?></span>
                            <label class="easy-field-item easy-toggle-switch">
                                <input type="checkbox" 
                                    name="smooth_scroll_normalize" 
                                    value="1"
                                    <?php checked( isset($options['smooth_scroll_normalize']) ? $options['smooth_scroll_normalize'] : 0, 1 ); ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <!-- Enable on Mobile -->
                        <div class="easyel-smooth-scroll-item">
                            <span class="easy-field-label"><?php echo esc_html__("Enable on Mobile Devices","easy-elements"); ?></span>
                            <label class="easy-field-item easy-toggle-switch">
                                <input type="checkbox" 
                                    name="smooth_scroll_enable_mobile" 
                                    value="1"
                                    <?php checked( isset($options['smooth_scroll_enable_mobile']) ? $options['smooth_scroll_enable_mobile'] : 0, 1 ); ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <!-- Disable on Editor Mode -->
                        <div class="easyel-smooth-scroll-item">
                            <span class="easy-field-label"><?php echo esc_html__("Disable on Editor Mode","easy-elements"); ?></span>
                            <label class="easy-field-item easy-toggle-switch">
                                <input type="checkbox" 
                                    name="smooth_scroll_disable_editor_mode" 
                                    value="1"
                                    <?php checked( isset($options['smooth_scroll_disable_editor_mode']) ? $options['smooth_scroll_disable_editor_mode'] : 0, 1 ); ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <div class="easyel-smooth-scroll-item">
                            <span class="easy-field-label"><?php echo esc_html__("Width","easy-elements"); ?></span>
                            <label class="easy-field-item">
                                <input type="number" 
                                    name="smooth_scroll_width" 
                                    value="<?php echo isset($options['smooth_scroll_width']) ? esc_attr($options['smooth_scroll_width']) : ''; ?>">
                            </label>
                        </div>

                        <div class="easyel-smooth-scroll-item">
                            <span class="easy-field-label">
                                <?php echo esc_html__("Normal Color","easy-elements"); ?>
                            </span>
                            <label class="easy-field-item">
                                <input type="text" class="easyel-smooth-scroll-color"
                                    name="smooth_scroll_normal_color"
                                    value="<?php echo isset($options['smooth_scroll_normal_color']) ? esc_attr($options['smooth_scroll_normal_color']) : ''; ?>">
                            </label>
                        </div>

                        <div class="easyel-smooth-scroll-item">
                            <span class="easy-field-label">
                                <?php echo esc_html__("Highlight Color","easy-elements"); ?>
                            </span>
                            <label class="easy-field-item">
                                <input type="text" class="easyel-smooth-scroll-color"
                                    name="smooth_scroll_highlight_color"
                                    value="<?php echo isset($options['smooth_scroll_highlight_color']) ? esc_attr($options['smooth_scroll_highlight_color']) : ''; ?>">
                            </label>
                        </div>
                    </div>

                    <div class="easyel-smooth-scroll-save">
                        <input type="submit" 
                            class="smooth_scroll_popup_item_save" 
                            name="smooth_scroll_popup_item_save"
                            value="<?php echo esc_attr__('Save Changes', 'easy-elements'); ?>">
                    </div>
                </form>
            </div>

        </div>
        <?php
    }

    public function easyel_reading_progressbar_popup_display() { 

        $options = get_option('easyel_reading_progressbar_settings', []);

        $display_options = [
            'global'          => __( 'Entire Site', 'easy-elements' ),
            'all-posts'       => __( 'Posts Only', 'easy-elements' ),
            'all-pages'       => __( 'Pages Only', 'easy-elements' ),
            'all-pages-post'  => __( 'All Pages & Posts', 'easy-elements' ),
            'blog-page'       => __( 'Blog Page', 'easy-elements' ),
        ];

        if ( ! apply_filters( 'easyel/pro_enabled', false ) ) {
            foreach ( $display_options as $key => $label ) {
                if ( $key !== 'global' ) {
                    $display_options[ $key ] = $label . ' (Pro)';
                }
            }
        }

        $pages = get_pages();

        ?>
        <div class="easyel-reading-progressbar-popup">
            <div class="ajax-search-close-icon">
                <a class="easyel-reading-progressbar-popup-close-icon rbt-round-btn" href="#">
                    <svg width="14" height="14" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.08366 1.73916L8.26116 0.916656L5.00033 4.17749L1.73949 0.916656L0.916992 1.73916L4.17783 4.99999L0.916992 8.26082L1.73949 9.08332L5.00033 5.82249L8.26116 9.08332L9.08366 8.26082L5.82283 4.99999L9.08366 1.73916Z" fill="currentColor"></path>
                    </svg>
                </a>
            </div>

            <div class="wrapper">
                <h2 class="easyel-reading-progressbar-heading"><?php echo esc_html__("Reading Progress bar Settings","easy-elements"); ?></h2>
                <form method="post" action="">
                    <div class="easyel-reading-progressbar-settings-main-wrapper">

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php echo esc_html__( "Progressbar Position", "easy-elements" ); ?>
                            </span>

                            <label class="easy-field-item">
                                <select class="easyel-reading-progressbar-select"
                                    name="reading_progressbar_position">
                                    <option value="top"
                                        <?php selected( $options['reading_progressbar_position'] ?? 'top', 'top' ); ?>>
                                        <?php esc_html_e( 'Top', 'easy-elements' ); ?>
                                    </option>
                                    <option value="bottom"
                                        <?php selected( $options['reading_progressbar_position'] ?? 'top', 'bottom' ); ?>>
                                        <?php esc_html_e( 'Bottom', 'easy-elements' ); ?>
                                    </option>
                                </select>
                            </label>
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Display Progressbar On', 'easy-elements' ); ?>
                            </span>

                            <div class="easyel-checkbox-group">
                                <?php foreach ( $display_options as $key => $label ) : ?>
                                    <label class="easyel-checkbox-item">
                                        <input type="checkbox"
                                            name="reading_progressbar_display[]"
                                            value="<?php echo esc_attr( $key ); ?>"
                                            <?php
                                                if ( ! empty( $options['reading_progressbar_display'] ) &&
                                                    in_array( $key, (array) $options['reading_progressbar_display'], true )
                                                ) {
                                                    checked( true );
                                                }
                                            ?>
                                            <?php
                                                if ( ! apply_filters( 'easyel/pro_enabled', false ) && $key !== 'global' ) {
                                                    echo ' disabled';
                                                }
                                            ?>
                                        >
                                        <?php echo esc_html( $label ); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Specific Page Select -->
                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label"><?php esc_html_e( 'Specific Page', 'easy-elements' ); ?></span>
                            <div class="easy-field-content">
                                <select
                                    class="easyel-readingprogress-barselect2"
                                    name="reading_progressbar_specific_page[]"
                                    multiple
                                    <?php if ( ! apply_filters( 'easyel/pro_enabled', false ) ) echo 'disabled'; ?>
                                >
                                    <option value=""><?php esc_html_e( 'Select a page', 'easy-elements' ); ?></option>
                                    <?php foreach( $pages as $page ): ?>
                                        <option value="<?php echo esc_attr( $page->ID ); ?>"
                                            <?php
                                                if ( ! empty( $options['reading_progressbar_specific_page'] ) &&
                                                    in_array( $page->ID, (array) $options['reading_progressbar_specific_page'], true )
                                                ) {
                                                    echo 'selected';
                                                }
                                            ?>
                                        >
                                            <?php echo esc_html( $page->post_title ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <?php if ( ! apply_filters( 'easyel/pro_enabled', false ) ) : ?>
                                    <p class="easyel-pro-notice"><?php esc_html_e( 'Available in Pro.', 'easy-elements' ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>


                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Bar Color', 'easy-elements' ); ?>
                            </span>
                            <input type="text" class="easyel-color-picker"
                                name="reading_progressbar_color"
                                value="<?php echo esc_attr( $options['reading_progressbar_color'] ?? '' ); ?>">
                        </div>
                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Height (px)', 'easy-elements' ); ?>
                            </span>
                            <input type="number"
                                name="reading_progressbar_height"
                                min="1" max="100"
                                value="<?php echo esc_attr( $options['reading_progressbar_height'] ?? '' ); ?>">
                        </div>
                    </div>
                    <div class="easyel-reading-progressbar-save">
                        <input type="submit" 
                            class="reading_progressbar_popup_item_save" 
                            name="reading_progressbar_popup_item_save"
                            value="<?php echo esc_attr__('Save Changes', 'easy-elements'); ?>">
                    </div>
                </form>
            </div>

        </div>
        <?php
    }

    public function easyel_smooth_scroller_popup_callback() {
        $this->easyel_smooth_scroller_popup_display();
    }

    public function easyel_reading_progressbar_popup_callback() {
        $this->easyel_reading_progressbar_popup_display();
    }

    public function save_smooth_scroll_settings() {

        // Permission check
        if ( ! current_user_can('manage_options') ) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }

        check_ajax_referer('easy_elements_nonce', 'nonce');

        if ( ! isset($_POST['settings']) ) {
            wp_send_json_error(['message' => 'No settings received']);
        }

        $settings = map_deep(  wp_unslash( $_POST['settings'] ), 'sanitize_text_field');

        update_option('easyel_scroll_smoother_settings', $settings);

        wp_send_json_success(['message' => 'Saved successfully']);
    }

    public function save_reading_progressbar_settings() {

        if ( ! current_user_can('manage_options') ) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }

        check_ajax_referer('easy_elements_nonce', 'nonce');

        if ( ! isset($_POST['settings']) ) {
            wp_send_json_error(['message' => 'No settings received']);
        }

        $settings = map_deep( wp_unslash( $_POST['settings'] ), 'sanitize_text_field');

        if ( isset($settings['reading_progressbar_display']) && is_array($settings['reading_progressbar_display']) ) {
            $settings['reading_progressbar_display'] = array_map('sanitize_text_field', $settings['reading_progressbar_display']);
        }

        if ( isset($settings['reading_progressbar_specific_page']) && is_array($settings['reading_progressbar_specific_page']) ) {
            $settings['reading_progressbar_specific_page'] = array_map('absint', $settings['reading_progressbar_specific_page']); 
        } else {
            $settings['reading_progressbar_specific_page'] = []; 
        }

        update_option('easyel_reading_progressbar_settings', $settings);

        wp_send_json_success(['message' => 'Saved successfully']);
    }

    public function easyel_scroll_top_popup_callback() {
        $this->easyel_scroll_top_popup_display();
    }

    public function easyel_scroll_top_popup_display() {

        $options = get_option( 'easyel_scroll_top_settings', [] );

        ?>
        <div class="easyel-scroll-top-popup">
            <div class="ajax-search-close-icon">
                <a class="easyel-scroll-top-popup-close-icon rbt-round-btn" href="#">
                    <svg width="14" height="14" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.08366 1.73916L8.26116 0.916656L5.00033 4.17749L1.73949 0.916656L0.916992 1.73916L4.17783 4.99999L0.916992 8.26082L1.73949 9.08332L5.00033 5.82249L8.26116 9.08332L9.08366 8.26082L5.82283 4.99999L9.08366 1.73916Z" fill="currentColor"></path>
                    </svg>
                </a>
            </div>

            <div class="wrapper">
                <h2 class="easyel-scroll-top-heading"><?php echo esc_html__( 'Scroll Top Settings', 'easy-elements' ); ?></h2>
                <form method="post" action="">
                    <div class="easyel-reading-progressbar-settings-main-wrapper">

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php echo esc_html__( 'Icon Type', 'easy-elements' ); ?>
                            </span>
                            <label class="easy-field-item">
                                <select class="easyel-scroll-top-select" name="scroll_top_icon" id="scroll_top_icon_select">
                                    <option value="arrow" <?php selected( $options['scroll_top_icon'] ?? 'arrow', 'arrow' ); ?>>
                                        <?php esc_html_e( 'Arrow', 'easy-elements' ); ?>
                                    </option>
                                    <option value="chevron" <?php selected( $options['scroll_top_icon'] ?? 'arrow', 'chevron' ); ?>>
                                        <?php esc_html_e( 'Chevron', 'easy-elements' ); ?>
                                    </option>
                                    <option value="double" <?php selected( $options['scroll_top_icon'] ?? 'arrow', 'double' ); ?>>
                                        <?php esc_html_e( 'Double Arrow', 'easy-elements' ); ?>
                                    </option>
                                    <option value="custom_icon" <?php selected( $options['scroll_top_icon'] ?? 'arrow', 'custom_icon' ); ?>>
                                        <?php esc_html_e( 'Custom Icon Class', 'easy-elements' ); ?>
                                    </option>
                                    <option value="custom_image" <?php selected( $options['scroll_top_icon'] ?? 'arrow', 'custom_image' ); ?>>
                                        <?php esc_html_e( 'Custom Image', 'easy-elements' ); ?>
                                    </option>
                                </select>
                            </label>
                        </div>

                        <div class="easyel-reading-progressbar-item easyel-scroll-top-custom-icon-row" style="<?php echo ( ($options['scroll_top_icon'] ?? '') === 'custom_icon' ) ? '' : 'display:none;'; ?>">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Icon Class', 'easy-elements' ); ?>
                            </span>
                            <input type="text" name="scroll_top_custom_icon"
                                placeholder="e.g. fas fa-arrow-up"
                                value="<?php echo esc_attr( $options['scroll_top_custom_icon'] ?? '' ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item easyel-scroll-top-custom-image-row" style="<?php echo ( ($options['scroll_top_icon'] ?? '') === 'custom_image' ) ? '' : 'display:none;'; ?>">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Upload Image', 'easy-elements' ); ?>
                            </span>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <input type="hidden" name="scroll_top_custom_image" id="scroll_top_custom_image"
                                    value="<?php echo esc_attr( $options['scroll_top_custom_image'] ?? '' ); ?>">
                                <button type="button" class="button easyel-scroll-top-upload-btn">
                                    <?php esc_html_e( 'Upload', 'easy-elements' ); ?>
                                </button>
                                <button type="button" class="button easyel-scroll-top-remove-btn" style="<?php echo empty( $options['scroll_top_custom_image'] ) ? 'display:none;' : ''; ?>">
                                    <?php esc_html_e( 'Remove', 'easy-elements' ); ?>
                                </button>
                            </div>
                            <?php if ( ! empty( $options['scroll_top_custom_image'] ) ) : ?>
                                <img src="<?php echo esc_url( $options['scroll_top_custom_image'] ); ?>" class="easyel-scroll-top-image-preview" style="max-width:50px; max-height:50px; margin-top:8px;">
                            <?php else : ?>
                                <img src="" class="easyel-scroll-top-image-preview" style="max-width:50px; max-height:50px; margin-top:8px; display:none;">
                            <?php endif; ?>
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php echo esc_html__( 'Position', 'easy-elements' ); ?>
                            </span>
                            <label class="easy-field-item">
                                <select class="easyel-scroll-top-select" name="scroll_top_position">
                                    <option value="left" <?php selected( $options['scroll_top_position'] ?? 'right', 'left' ); ?>>
                                        <?php esc_html_e( 'Left', 'easy-elements' ); ?>
                                    </option>
                                    <option value="center" <?php selected( $options['scroll_top_position'] ?? 'right', 'center' ); ?>>
                                        <?php esc_html_e( 'Center', 'easy-elements' ); ?>
                                    </option>
                                    <option value="right" <?php selected( $options['scroll_top_position'] ?? 'right', 'right' ); ?>>
                                        <?php esc_html_e( 'Right', 'easy-elements' ); ?>
                                    </option>
                                </select>
                            </label>
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Background Color', 'easy-elements' ); ?>
                            </span>
                            <input type="text" class="easyel-color-picker"
                                name="scroll_top_bg_color"
                                value="<?php echo esc_attr( $options['scroll_top_bg_color'] ?? '#333333' ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Icon Color', 'easy-elements' ); ?>
                            </span>
                            <input type="text" class="easyel-color-picker"
                                name="scroll_top_icon_color"
                                value="<?php echo esc_attr( $options['scroll_top_icon_color'] ?? '#ffffff' ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Size (px)', 'easy-elements' ); ?>
                            </span>
                            <input type="number" name="scroll_top_size"
                                min="30" max="80"
                                value="<?php echo esc_attr( $options['scroll_top_size'] ?? '45' ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Border Radius (%)', 'easy-elements' ); ?>
                            </span>
                            <input type="number" name="scroll_top_radius"
                                min="0" max="50"
                                value="<?php echo esc_attr( $options['scroll_top_radius'] ?? '50' ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Border Width (px)', 'easy-elements' ); ?>
                            </span>
                            <input type="number" name="scroll_top_border_width"
                                min="0" max="10"
                                value="<?php echo esc_attr( $options['scroll_top_border_width'] ?? '0' ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Border Color', 'easy-elements' ); ?>
                            </span>
                            <input type="text" class="easyel-color-picker"
                                name="scroll_top_border_color"
                                value="<?php echo esc_attr( $options['scroll_top_border_color'] ?? '#cccccc' ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Hover Background Color', 'easy-elements' ); ?>
                            </span>
                            <input type="text" class="easyel-color-picker"
                                name="scroll_top_hover_bg_color"
                                value="<?php echo esc_attr( $options['scroll_top_hover_bg_color'] ?? '#000000' ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Hover Icon Color', 'easy-elements' ); ?>
                            </span>
                            <input type="text" class="easyel-color-picker"
                                name="scroll_top_hover_icon_color"
                                value="<?php echo esc_attr( $options['scroll_top_hover_icon_color'] ?? '#ffffff' ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Hover Border Color', 'easy-elements' ); ?>
                            </span>
                            <input type="text" class="easyel-color-picker"
                                name="scroll_top_hover_border_color"
                                value="<?php echo esc_attr( $options['scroll_top_hover_border_color'] ?? '#cccccc' ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label">
                                <?php esc_html_e( 'Show After Scroll (px)', 'easy-elements' ); ?>
                            </span>
                            <input type="number" name="scroll_top_offset"
                                min="0" max="2000"
                                value="<?php echo esc_attr( $options['scroll_top_offset'] ?? '300' ); ?>">
                        </div>

                    </div>
                    <div class="easyel-reading-progressbar-save">
                        <input type="submit"
                            class="scroll_top_popup_item_save"
                            name="scroll_top_popup_item_save"
                            value="<?php echo esc_attr__( 'Save Changes', 'easy-elements' ); ?>">
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    public function save_scroll_top_settings() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Unauthorized', 'easy-elements' ) );
        }

        check_ajax_referer( 'easy_elements_nonce', 'nonce' );

        if ( ! isset( $_POST['settings'] ) ) {
            wp_send_json_error( [ 'message' => 'No settings received' ] );
        }

        $settings = map_deep( wp_unslash( $_POST['settings'] ), 'sanitize_text_field' );

        update_option( 'easyel_scroll_top_settings', $settings );

        wp_send_json_success( [ 'message' => 'Saved successfully' ] );
    }

    /**
     * Render Preloader settings popup callback (free extension).
     */
    public function easyel_preloader_popup_callback() {
        $this->easyel_preloader_popup_display();
    }

    /**
     * Render the Preloader settings modal markup.
     */
    public function easyel_preloader_popup_display() {

        $defaults = array(
            'preloader_style'           => 'circle',
            'preloader_bg_color'        => '#ffffff',
            'preloader_color'           => '#5933ff',
            'preloader_secondary_color' => '#e0e0e0',
            'preloader_size'            => 60,
            'preloader_speed'           => 1.0,
            'preloader_min_time'        => 500,
            'preloader_fadeout_time'    => 600,
            'preloader_logo'            => '',
            'preloader_logo_width'      => 36,
            'preloader_logo_height'     => 36,
            'preloader_disable_mobile'  => 0,
        );

        $saved   = get_option( 'easyel_preloader_settings', array() );
        $options = wp_parse_args( is_array( $saved ) ? $saved : array(), $defaults );

        $styles = array(
            'circle'        => __( 'Circle Spinner', 'easy-elements' ),
            'dotted_circle' => __( 'Dotted Circle Spinner', 'easy-elements' ),
            'dots'          => __( 'Bouncing Dots', 'easy-elements' ),
            'bars'          => __( 'Audio Bars', 'easy-elements' ),
            'pulse'         => __( 'Pulse', 'easy-elements' ),
            'custom_logo'   => __( 'Custom Logo', 'easy-elements' ),
        );
        ?>
        <div class="easyel-preloader-popup">
            <div class="ajax-search-close-icon">
                <a class="easyel-preloader-popup-close-icon rbt-round-btn" href="#">
                    <svg width="14" height="14" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.08366 1.73916L8.26116 0.916656L5.00033 4.17749L1.73949 0.916656L0.916992 1.73916L4.17783 4.99999L0.916992 8.26082L1.73949 9.08332L5.00033 5.82249L8.26116 9.08332L9.08366 8.26082L5.82283 4.99999L9.08366 1.73916Z" fill="currentColor"></path>
                    </svg>
                </a>
            </div>

            <div class="wrapper">
                <h2 class="easyel-preloader-heading"><?php esc_html_e( 'Preloader Settings', 'easy-elements' ); ?></h2>
                <form method="post" action="">
                    <div class="easyel-reading-progressbar-settings-main-wrapper">

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label"><?php esc_html_e( 'Style', 'easy-elements' ); ?></span>
                            <label class="easy-field-item">
                                <select class="easyel-preloader-select" name="preloader_style">
                                    <?php foreach ( $styles as $value => $label ) : ?>
                                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $options['preloader_style'], $value ); ?>>
                                            <?php echo esc_html( $label ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>

                        <div class="easyel-reading-progressbar-item easyel-preloader-field-logo-only">
                            <span class="easy-field-label"><?php esc_html_e( 'Logo Image URL', 'easy-elements' ); ?></span>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <input type="text" class="easyel-preloader-logo-input"
                                    name="preloader_logo"
                                    placeholder="https://example.com/logo.png"
                                    value="<?php echo esc_attr( $options['preloader_logo'] ); ?>">
                                <button type="button" class="button easyel-preloader-upload-btn">
                                    <?php esc_html_e( 'Upload', 'easy-elements' ); ?>
                                </button>
                                <button type="button" class="button easyel-preloader-remove-btn" style="<?php echo empty( $options['preloader_logo'] ) ? 'display:none;' : ''; ?>">
                                    <?php esc_html_e( 'Remove', 'easy-elements' ); ?>
                                </button>
                            </div>
                        </div>

                        <div class="easyel-reading-progressbar-item easyel-preloader-field-logo-only">
                            <span class="easy-field-label"><?php esc_html_e( 'Logo Width (px)', 'easy-elements' ); ?></span>
                            <input type="number" name="preloader_logo_width"
                                min="5" max="500" step="1"
                                value="<?php echo esc_attr( $options['preloader_logo_width'] ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item easyel-preloader-field-logo-only">
                            <span class="easy-field-label"><?php esc_html_e( 'Logo Height (px)', 'easy-elements' ); ?></span>
                            <input type="number" name="preloader_logo_height"
                                min="5" max="500" step="1"
                                value="<?php echo esc_attr( $options['preloader_logo_height'] ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label"><?php esc_html_e( 'Background Color', 'easy-elements' ); ?></span>
                            <input type="text" class="easyel-color-picker"
                                name="preloader_bg_color"
                                value="<?php echo esc_attr( $options['preloader_bg_color'] ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label"><?php esc_html_e( 'Spinner Color', 'easy-elements' ); ?></span>
                            <input type="text" class="easyel-color-picker"
                                name="preloader_color"
                                value="<?php echo esc_attr( $options['preloader_color'] ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item easyel-preloader-field-secondary-only">
                            <span class="easy-field-label"><?php esc_html_e( 'Spinner Secondary Color', 'easy-elements' ); ?></span>
                            <input type="text" class="easyel-color-picker"
                                name="preloader_secondary_color"
                                value="<?php echo esc_attr( $options['preloader_secondary_color'] ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label"><?php esc_html_e( 'Size (px)', 'easy-elements' ); ?></span>
                            <input type="number" name="preloader_size"
                                min="10" max="200" step="1"
                                value="<?php echo esc_attr( $options['preloader_size'] ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label"><?php esc_html_e( 'Animation Speed (s)', 'easy-elements' ); ?></span>
                            <input type="number" name="preloader_speed"
                                min="0.1" max="5" step="0.1"
                                value="<?php echo esc_attr( $options['preloader_speed'] ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label"><?php esc_html_e( 'Minimum Display Time (ms)', 'easy-elements' ); ?></span>
                            <input type="number" name="preloader_min_time"
                                min="0" max="10000" step="50"
                                value="<?php echo esc_attr( $options['preloader_min_time'] ); ?>">
                        </div>

                        <div class="easyel-reading-progressbar-item">
                            <span class="easy-field-label"><?php esc_html_e( 'Fade Out Duration (ms)', 'easy-elements' ); ?></span>
                            <input type="number" name="preloader_fadeout_time"
                                min="0" max="3000" step="50"
                                value="<?php echo esc_attr( $options['preloader_fadeout_time'] ); ?>">
                        </div>

                        <div class="easyel-smooth-scroll-item">
                            <span class="easy-field-label"><?php esc_html_e( 'Disable on Mobile', 'easy-elements' ); ?></span>
                            <label class="easy-field-item easy-toggle-switch">
                                <input type="checkbox" name="preloader_disable_mobile" value="1"
                                    <?php checked( ! empty( $options['preloader_disable_mobile'] ), true ); ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>

                    </div>
                    <div class="easyel-reading-progressbar-save">
                        <input type="submit"
                            class="easyel_preloader_popup_item_save"
                            name="easyel_preloader_popup_item_save"
                            value="<?php echo esc_attr__( 'Save Changes', 'easy-elements' ); ?>">
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX handler to save Preloader settings.
     */
    public function easyel_save_preloader_settings() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Unauthorized', 'easy-elements' ) );
        }

        check_ajax_referer( 'easy_elements_nonce', 'nonce' );

        if ( ! isset( $_POST['settings'] ) ) {
            wp_send_json_error( array( 'message' => __( 'No settings received', 'easy-elements' ) ) );
        }

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $raw = wp_unslash( $_POST['settings'] );
        if ( ! is_array( $raw ) ) {
            $raw = array();
        }

        $allowed_styles = array( 'circle', 'dotted_circle', 'dots', 'bars', 'pulse', 'custom_logo' );

        $clean = array(
            'preloader_style'           => isset( $raw['preloader_style'] ) && in_array( $raw['preloader_style'], $allowed_styles, true )
                                            ? $raw['preloader_style']
                                            : 'circle',
            'preloader_bg_color'        => isset( $raw['preloader_bg_color'] )
                                            ? ( sanitize_hex_color( $raw['preloader_bg_color'] ) ? sanitize_hex_color( $raw['preloader_bg_color'] ) : '#ffffff' )
                                            : '#ffffff',
            'preloader_color'           => isset( $raw['preloader_color'] )
                                            ? ( sanitize_hex_color( $raw['preloader_color'] ) ? sanitize_hex_color( $raw['preloader_color'] ) : '#5933ff' )
                                            : '#5933ff',
            'preloader_secondary_color' => isset( $raw['preloader_secondary_color'] )
                                            ? ( sanitize_hex_color( $raw['preloader_secondary_color'] ) ? sanitize_hex_color( $raw['preloader_secondary_color'] ) : '#e0e0e0' )
                                            : '#e0e0e0',
            'preloader_size'            => isset( $raw['preloader_size'] ) ? max( 10, min( 200, absint( $raw['preloader_size'] ) ) ) : 60,
            'preloader_speed'           => isset( $raw['preloader_speed'] ) ? max( 0.1, min( 5, (float) $raw['preloader_speed'] ) ) : 1.0,
            'preloader_min_time'        => isset( $raw['preloader_min_time'] ) ? max( 0, min( 10000, absint( $raw['preloader_min_time'] ) ) ) : 500,
            'preloader_fadeout_time'    => isset( $raw['preloader_fadeout_time'] ) ? max( 0, min( 3000, absint( $raw['preloader_fadeout_time'] ) ) ) : 600,
            'preloader_logo'            => isset( $raw['preloader_logo'] ) ? esc_url_raw( $raw['preloader_logo'] ) : '',
            'preloader_logo_width'      => isset( $raw['preloader_logo_width'] ) ? max( 5, min( 500, absint( $raw['preloader_logo_width'] ) ) ) : 36,
            'preloader_logo_height'     => isset( $raw['preloader_logo_height'] ) ? max( 5, min( 500, absint( $raw['preloader_logo_height'] ) ) ) : 36,
            'preloader_disable_mobile'  => ! empty( $raw['preloader_disable_mobile'] ) ? 1 : 0,
        );

        update_option( 'easyel_preloader_settings', $clean );

        wp_send_json_success( array( 'message' => __( 'Saved successfully', 'easy-elements' ) ) );
    }

}

// Initialize the plugin
Admin_Settings::get_instance();