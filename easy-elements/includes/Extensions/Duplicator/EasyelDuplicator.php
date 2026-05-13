<?php
namespace Easyel\EasyElements\Extensions\Duplicator;
/**
 * Easy Duplicator - Duplicate Any Post Type as Draft
 *
 * @package EasyElements
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class EasyelDuplicator {

	/**
	 * Holds the singleton instance
	 *
	 * @var Easy_Duplicator|null
	 */
	private static $instance = null;

	/**
	 * Constructor
	 */
	private function __construct() {
        
        $tab_slug = 'extensions';
        $extensions_settings = get_option('easy_element_' . $tab_slug, [] );

        $enable_post_duplicator = isset( $extensions_settings['enable_post_duplicator'] ) ? $extensions_settings['enable_post_duplicator'] : 0;

        if(  (int) $enable_post_duplicator !== 1 ) {
            return;
        }

		add_filter( 'post_row_actions', [ $this, 'easyel_add_duplicate_link' ], 10, 2 );
		add_filter( 'page_row_actions', [ $this, 'easyel_add_duplicate_link' ], 10, 2 );
		add_action( 'admin_action_easyel_duplicate_post', [ $this, 'easyel_duplicate_post_as_draft' ] );
	}

	/**
	 * Get instance
	 */
	public static function get_instance() : self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Add "Duplicate" action link in post rows
	 */
	public function easyel_add_duplicate_link( $actions, $post ) {

		if ( in_array( $post->post_status, [ 'auto-draft', 'inherit' ], true ) ) {
			return $actions;
		}

		if ( current_user_can( 'edit_post', $post->ID ) ) {
			$url = wp_nonce_url(
				add_query_arg(
					[
						'action' => 'easyel_duplicate_post',
						'post'   => $post->ID,
					],
					admin_url( 'admin.php' )
				),
				'easyel_duplicate_nonce_' . $post->ID
			);

			$actions['easyel_duplicate'] = sprintf(
				'<a href="%s" title="%s">%s</a>',
				esc_url( $url ),
				esc_attr__( 'Duplicate this item', 'easy-elements' ),
				esc_html__( 'Easy Clone', 'easy-elements' )
			);
		}

		return $actions;
	}

	/**
	 * Duplicate post as draft
	 */
	public function easyel_duplicate_post_as_draft() {

		$post_id = isset($_GET['post']) ? absint($_GET['post']) : 0;
		$nonce   = isset($_GET['_wpnonce']) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( ! $post_id || ! wp_verify_nonce($nonce, 'easyel_duplicate_nonce_' . $post_id) ) {
			wp_die('Invalid request.');
		}

		$original = get_post($post_id);

		if ( ! $original || 'trash' === $original->post_status ) {
			wp_die('Post not found.');
		}

		$new_post_data = [
			'post_title'   => $original->post_title . ' (Copy)',
			'post_content' => $original->post_content,
			'post_status'  => 'draft',
			'post_type'    => $original->post_type,
			'post_author'  => get_current_user_id(),
			'post_excerpt' => $original->post_excerpt,
			'post_parent'  => $original->post_parent,
			'menu_order'   => $original->menu_order,
		];

		$new_post_id = wp_insert_post( $new_post_data );

		if ( is_wp_error( $new_post_id ) ) {
			wp_die('Error duplicating post.');
		}

		$taxonomies = get_object_taxonomies($original->post_type);
		foreach ($taxonomies as $taxonomy) {
			$terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'ids']);
			wp_set_object_terms($new_post_id, $terms, $taxonomy);
		}

		$meta = get_post_meta($post_id);
		foreach ($meta as $key => $values) {

			if ($key === '_wp_old_slug') {
				continue;
			}

			if (in_array($key, ['_elementor_data', '_elementor_controls_usage'], true)) {
				$raw_value = get_post_meta($post_id, $key, true);
				update_post_meta($new_post_id, $key, wp_slash($raw_value));
				continue;
			}

			foreach ($values as $value) {
				add_post_meta($new_post_id, $key, wp_slash(maybe_unserialize($value)));
			}
		}

		if ( class_exists('\Elementor\Plugin') ) {

			delete_post_meta($new_post_id, '_elementor_css');

			try {
				$css_file = new \Elementor\Core\Files\CSS\Post( $new_post_id );
				$css_file->update();
			} catch ( \Exception $e ) {

			}
		}

		clean_post_cache( $new_post_id );

		// Redirect
		wp_safe_redirect(
			add_query_arg(
				[
					'post_type'  => $original->post_type,
					'duplicated' => 'true',
				],
				admin_url('edit.php')
			)
		);

		exit;
	}
}
