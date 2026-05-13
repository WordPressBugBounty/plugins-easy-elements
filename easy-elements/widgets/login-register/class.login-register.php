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

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] )) , 'easy_elements_nonce' ) ) {
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

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] )), 'easy_elements_nonce' ) ) {
            return wp_send_json_error( ['msg' => 'Security failed!'] );
        }

        $custom_meta = !empty($_POST['custom_meta'])
        ? map_deep( wp_unslash( $_POST['custom_meta'] ), 'sanitize_text_field' )
        : [];

        $consent     = !empty($_POST['consent']) ? 'yes' : 'no';

        $user_data = [
           'user_login' => ! empty( $_POST['user_login'] ) 
            ? sanitize_user( wp_unslash( $_POST['user_login'] ), true ) 
            : '',
            'user_email'    => !empty($_POST['user_email']) ? sanitize_email( wp_unslash($_POST['user_email']) ) : '',
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
            'user_pass'     => !empty($_POST['user_pass']) ? wp_unslash($_POST['user_pass']) : '',
            'role'          => !empty($_POST['role']) && array_key_exists( sanitize_text_field( wp_unslash($_POST['role']) ), wp_roles()->get_names() ) ? sanitize_text_field( wp_unslash($_POST['role']) ) : 'subscriber',
            'first_name'    => !empty($_POST['first_name']) ? sanitize_text_field( wp_unslash($_POST['first_name']) ) : '',
            'last_name'     => !empty($_POST['last_name']) ? sanitize_text_field( wp_unslash($_POST['last_name']) ) : '',
            'display_name'  => !empty($_POST['display_name']) ? sanitize_text_field( wp_unslash($_POST['display_name']) ) : '',
            'user_nicename' => !empty($_POST['user_nicename']) ? sanitize_text_field( wp_unslash($_POST['user_nicename']) ) : '',
            'nickname'      => !empty($_POST['nickname']) ? sanitize_text_field( wp_unslash($_POST['nickname']) ) : '',
            'user_url'      => !empty($_POST['user_url']) ? esc_url_raw( wp_unslash($_POST['user_url']) ) : '',
            'description'   => !empty($_POST['description']) ? sanitize_textarea_field( wp_unslash($_POST['description']) ) : '',
        ];

        $auto_login = !empty($_POST['auto_login']) ? 'yes' : 'no';

        if ( empty( $user_data['user_login'] ) ) {
            return wp_send_json_error( ['msg' => 'Username is required.'] );
        }
        if ( empty( $user_data['user_pass'] ) ) {
            return wp_send_json_error( ['msg' => 'Password is required.'] );
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

}
new Easyel_Login_Register();