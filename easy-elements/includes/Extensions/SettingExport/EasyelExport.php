<?php
namespace Easyel\EasyElements\Extensions\SettingExport;
/**
 * Easy Export - Export Elementor Pages as JSON
 *
 * @package EasyElements
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class EasyelExport {

	private static $instance = null;

	private function __construct() {

        if ( function_exists( 'easy_element_is_enabled' ) &&  ! easy_element_is_enabled( 'enable_postpage_export' ) ) {
            return;
        }

		add_action( 'admin_bar_menu', [ $this, 'easyel_add_export_button' ], 100 );

		add_action( 'admin_action_easyel_export_elementor', [ $this, 'easyel_export_elementor_json' ] );

        add_filter( 'post_row_actions', [ $this, 'easyel_add_export_link'], 10, 2 );
        add_filter( 'page_row_actions', [ $this,'easyel_add_export_link'], 10, 2 );
	}

	public static function get_instance() : self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

    function easyel_add_export_link( $actions, $post ) {

        if ( ! did_action( 'elementor/loaded' ) ) {
            return $actions;
        }

        if ( 'elementor_library' === $post->post_type ) {
			return $actions;
		}

        $elementor_data = get_post_meta( $post->ID, '_elementor_data', true );
        if ( empty( $elementor_data ) ) {
            return $actions;
        }

        $export_url = wp_nonce_url(
            add_query_arg(
                [
                    'action' => 'easyel_export_elementor',
                    'post'   => $post->ID,
                ],
                admin_url( 'admin.php' )
            ),
            'easyel_export_nonce_' . $post->ID
        );

        $actions['easy_export'] = '<a href="' . esc_url( $export_url ) . '">' . __( 'Easy Export', 'easy-elements' ) . '</a>';

        return $actions;
    }

	/**
	 * Add Easy Export button on the admin bar if Elementor data exists
	 */
	public function easyel_add_export_button( $admin_bar ) {
		global $post;

		if ( ! is_admin() || ! isset( $post->ID ) ) {
			return;
		}

		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		$elementor_data = get_post_meta( $post->ID, '_elementor_data', true );
		if ( empty( $elementor_data ) ) {
			return;
		}

		$export_url = wp_nonce_url(
			add_query_arg(
				[
					'action' => 'easyel_export_elementor',
					'post'   => $post->ID,
				],
				admin_url( 'admin.php' )
			),
			'easyel_export_nonce_' . $post->ID
		);

		$admin_bar->add_node( [
			'id'    => 'easyel-export',
			'title' => __( 'Easy Export', 'easy-elements' ),
			'href'  => esc_url( $export_url ),
			'meta'  => [
				'title' => __( 'Export Elementor JSON', 'easy-elements' ),
			],
		] );
	}

	/**
	 * Handle Elementor JSON export
	 */
	public function easyel_export_elementor_json() {
        $post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
        $nonce   = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

        if ( ! $post_id || ! wp_verify_nonce( $nonce, 'easyel_export_nonce_' . $post_id ) ) {
            wp_die( esc_html__( 'Invalid request.', 'easy-elements' ) );
        }

        $elementor_data = get_post_meta( $post_id, '_elementor_data', true );

        if ( empty( $elementor_data ) ) {
            wp_die( esc_html__( 'No Elementor data found for this post.', 'easy-elements' ) );
        }

        $elements =  $elementor_data;

        $elements = json_decode( $elementor_data, true ); 
        if ( ! is_array( $elements ) ) {
            wp_die( 'Invalid Elementor data format.' );
        }

        $export_array = [
            'content'       => $elements, 
            'type'          => 'container', 
            'title'         => get_the_title( $post_id ),
            'version'       => '1.0',
            'page_settings' => [],
        ];

        $json_data = wp_json_encode( $export_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

        $filename = sanitize_title( get_the_title( $post_id ) ) . '-easy-export.json';

        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $json_data;
        exit;
    }


}