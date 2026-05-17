<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Easyel_Login_Register {
    public function __construct() {
        add_action( 'wp_ajax_eel_login', [$this, 'easyel_handle_login'] );
        add_action( 'wp_ajax_nopriv_eel_login', [$this, 'easyel_handle_login'] );
        add_action( 'wp_ajax_eel_register', [$this, 'easyel_handle_register'] );
        add_action( 'wp_ajax_nopriv_eel_register', [$this, 'easyel_handle_register'] );
    }
    
    /**
	 * Handle login form submission
	 */
	public function easyel_handle_login() {

        $posted_nonce = '';
        if ( ! empty( $_POST['eel_login_nonce'] ) ) {
            $posted_nonce = sanitize_text_field( wp_unslash( $_POST['eel_login_nonce'] ) );
        } elseif ( ! empty( $_POST['nonce'] ) ) {
            $posted_nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
        }
        if ( ! $posted_nonce || ! wp_verify_nonce( $posted_nonce, 'easy_elements_nonce' ) ) {
            return wp_send_json_error( ['msg' => 'security failed!'] );
        }

        $user_login = !empty($_POST['user']) ? sanitize_user( wp_unslash( $_POST['user'] ) ) : '';
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
        $user_pass  = !empty($_POST['pwd']) ? wp_unslash( $_POST['pwd'] ) : '';
        $remember   = !empty($_POST['remember']);

        

        $user = wp_authenticate( $user_login, $user_pass );

        if ( is_wp_error( $user ) ) {
            wp_send_json_error( ['msg' => $user->get_error_message()] );
        }

        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID, $remember, is_ssl() );

        wp_send_json_success();
	}

    /**
	 * Handle registration form submission
	 */
    public function easyel_handle_register() {

        $posted_nonce = '';
        if ( ! empty( $_POST['eel_register_nonce'] ) ) {
            $posted_nonce = sanitize_text_field( wp_unslash( $_POST['eel_register_nonce'] ) );
        } elseif ( ! empty( $_POST['nonce'] ) ) {
            $posted_nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
        }
        if ( ! $posted_nonce || ! wp_verify_nonce( $posted_nonce, 'easy_elements_nonce' ) ) {
            return wp_send_json_error( ['msg' => 'Security failed!'] );
        }

        if ( ! get_option( 'users_can_register' ) ) {
            return wp_send_json_error( ['msg' => 'User registration is currently disabled.'] );
        }

        $custom_meta = !empty($_POST['custom_meta'])
        ? map_deep( wp_unslash( $_POST['custom_meta'] ), 'sanitize_text_field' )
        : [];

        $consent     = !empty($_POST['consent']) ? 'yes' : 'no';

        $safe_role = $this->easyel_get_safe_registration_role();

        $user_data = [
           'user_login' => ! empty( $_POST['user_login'] )
            ? sanitize_user( wp_unslash( $_POST['user_login'] ), true )
            : '',
            'user_email'    => !empty($_POST['user_email']) ? sanitize_email( wp_unslash($_POST['user_email']) ) : '',
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
            'user_pass'     => !empty($_POST['user_pass']) ? wp_unslash($_POST['user_pass']) : '',
            'role'          => $safe_role,
            'first_name'    => !empty($_POST['first_name']) ? sanitize_text_field( wp_unslash($_POST['first_name']) ) : '',
            'last_name'     => !empty($_POST['last_name']) ? sanitize_text_field( wp_unslash($_POST['last_name']) ) : '',
            'display_name'  => !empty($_POST['display_name']) ? sanitize_text_field( wp_unslash($_POST['display_name']) ) : '',
            'user_nicename' => !empty($_POST['user_nicename']) ? sanitize_text_field( wp_unslash($_POST['user_nicename']) ) : '',
            'nickname'      => !empty($_POST['nickname']) ? sanitize_text_field( wp_unslash($_POST['nickname']) ) : '',
            'user_url'      => !empty($_POST['user_url']) ? esc_url_raw( wp_unslash($_POST['user_url']) ) : '',
            'description'   => !empty($_POST['description']) ? sanitize_textarea_field( wp_unslash($_POST['description']) ) : '',
        ];

        $auto_login_raw = isset( $_POST['auto_login'] ) ? sanitize_text_field( wp_unslash( $_POST['auto_login'] ) ) : 'no';
        if ( ! in_array( $auto_login_raw, [ 'yes', 'no' ], true ) ) {
            return wp_send_json_error( ['msg' => 'Registration failed: invalid request.'] );
        }
        $auto_login = $auto_login_raw;

        if ( empty( $user_data['user_login'] ) ) {
            return wp_send_json_error( ['msg' => 'Username is required.'] );
        }
        if ( ! validate_username( $user_data['user_login'] ) ) {
            return wp_send_json_error( ['msg' => 'Invalid username.'] );
        }
        if ( username_exists( $user_data['user_login'] ) ) {
            return wp_send_json_error( ['msg' => 'Username already exists.'] );
        }

        if ( empty( $user_data['user_email'] ) || ! is_email( $user_data['user_email'] ) ) {
            return wp_send_json_error( ['msg' => 'Invalid email address.'] );
        }
        if ( email_exists( $user_data['user_email'] ) ) {
            return wp_send_json_error( ['msg' => 'Email already exists.'] );
        }

        if ( empty( $user_data['user_pass'] ) ) {
            return wp_send_json_error( ['msg' => 'Password is required.'] );
        }
        if ( strlen( $user_data['user_pass'] ) < 8 ) {
            return wp_send_json_error( ['msg' => 'Password must be at least 8 characters.'] );
        }

        $user_id = wp_insert_user( $user_data );

        if ( is_wp_error( $user_id ) ) {
            return wp_send_json_error( ['msg' => $user_id->get_error_message()] );
        }

        // Save meta
        foreach ( $custom_meta as $key => $value ) {
            update_user_meta( $user_id, $key, $value );
        }

        update_user_meta( $user_id, 'consent', $consent );

        $msg = 'User created successfully';

        // Auto login
        if ( $auto_login === 'yes' ) {
            wp_set_current_user( $user_id );
            wp_set_auth_cookie( $user_id, true, is_ssl() );
        }

        return wp_send_json_success( ['msg' => $msg] );
    }

    /**
     * Resolve a safe role for front-end registration.
     *
     * The role is never read from $_POST. It defaults to the site's configured
     * default registration role and can be overridden server-side via the
     * 'easyel_registration_role' filter. Any role that holds a privileged
     * capability is rejected and replaced with 'subscriber'.
     */
    private function easyel_get_safe_registration_role() {
        $role = get_option( 'default_role', 'subscriber' );

        $role = apply_filters( 'easyel_registration_role', $role );

        if ( ! is_string( $role ) || $role === '' ) {
            return 'subscriber';
        }

        $role_obj = get_role( $role );
        if ( ! $role_obj ) {
            return 'subscriber';
        }

        $blocked_caps = [
            'manage_options',
            'promote_users',
            'edit_users',
            'create_users',
            'delete_users',
            'edit_others_posts',
            'publish_posts',
            'edit_published_posts',
            'edit_theme_options',
            'install_plugins',
            'activate_plugins',
            'unfiltered_html',
        ];
        foreach ( $blocked_caps as $cap ) {
            if ( ! empty( $role_obj->capabilities[ $cap ] ) ) {
                return 'subscriber';
            }
        }

        return $role;
    }

}
new Easyel_Login_Register();