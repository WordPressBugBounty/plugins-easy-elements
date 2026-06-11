<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Free_Post_content_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-post-content';
	}

	public function get_title() {
		return __( 'Post Content', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-post-content';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
        return [ 'post', 'content', 'link', 'click', 'text' ];
    }

	public function get_style_depends() {
        // Keep this list minimal: Elementor enqueues these AFTER theme CSS in the
        // preview iframe, so any heading/typography handle here will override the
        // active theme (e.g. wp-block-library-theme sets h2/h3 sizes that win and
        // make Gutenberg headings look tiny in the preview). Block library CSS is
        // force-enqueued earlier instead — see the wp_enqueue_scripts hook at the
        // bottom of this file (priority 1, runs before theme at priority 10).
        return [
            'eel-post-content',
        ];
    }


	protected function register_controls() {

	    $this->start_controls_section(
	        'content_section',
	        [
	            'label' => esc_html__('Content Style', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_CONTENT,
	        ]
	    );
		
		$this->add_control(
			'content_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .eel-post-content',
			]
		);

		$this->add_control(
			'global_heading',
			[
				'label' => esc_html__( 'Global Heading', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,	
				'separator' => 'after',				
			]
		);	

		$this->add_control(
			'global_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-content h1, {{WRAPPER}} .eel-post-content h2, {{WRAPPER}} .eel-post-content h3, {{WRAPPER}} .eel-post-content h4, {{WRAPPER}} .eel-post-content h5, {{WRAPPER}} .eel-post-content h6' => 'color: {{VALUE}};',
				],				
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'global_typography',
				'selector' => '{{WRAPPER}} .eel-post-content h1, {{WRAPPER}} .eel-post-content h2, {{WRAPPER}} .eel-post-content h3, {{WRAPPER}} .eel-post-content h4, {{WRAPPER}} .eel-post-content h5, {{WRAPPER}} .eel-post-content h6',
			]
		);

		$this->add_responsive_control(
            'global_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-content h1, {{WRAPPER}} .eel-post-content h2, {{WRAPPER}} .eel-post-content h3, {{WRAPPER}} .eel-post-content h4, {{WRAPPER}} .eel-post-content h5, {{WRAPPER}} .eel-post-content h6' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'strong_heading',
			[
				'label' => esc_html__( 'Strong Style', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,	
				'separator' => 'after',				
			]
		);

		$this->add_control(
			'strong_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-content strong' => 'color: {{VALUE}};',
				],				
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'strong_typography',
				'selector' => '{{WRAPPER}} .eel-post-content strong',
			]
		);

		$this->add_control(
			'link_heading',
			[
				'label' => esc_html__( 'Link Style', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,	
				'separator' => 'after',				
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-content a' => 'color: {{VALUE}};',
				],				
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'link_typography',
				'selector' => '{{WRAPPER}} .eel-post-content a',
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
	        'content_section_heading',
	        [
	            'label' => esc_html__('Heading Style', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_CONTENT,
	        ]
	    );

		$this->add_control(
			'h1_heading',
			[
				'label' => esc_html__( 'H1 Heading', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,				
			]
		);
		
		$this->add_control(
			'h1_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-content h1' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'h1_typography',
				'selector' => '{{WRAPPER}} .eel-post-content h1',
			]
		);

		$this->add_responsive_control(
            'h1_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-content h1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'h2_heading',
			[
				'label' => esc_html__( 'H2 Heading', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,				
			]
		);
		
		$this->add_control(
			'h2_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-content h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'h2_typography',
				'selector' => '{{WRAPPER}} .eel-post-content h2',
			]
		);

		$this->add_responsive_control(
            'h2_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-content h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		
		$this->add_control(
			'h3_heading',
			[
				'label' => esc_html__( 'H3 Heading', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,				
			]
		);

		$this->add_control(
			'h3_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-content h3' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'h3_typography',
				'selector' => '{{WRAPPER}} .eel-post-content h3',
			]
		);

		$this->add_responsive_control(
            'h3_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-content h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'h4_heading',
			[
				'label' => esc_html__( 'H4 Heading', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,				
			]
		);
		
		$this->add_control(
			'h4_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-content h4' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'h4_typography',
				'selector' => '{{WRAPPER}} .eel-post-content h4',
			]
		);

		$this->add_responsive_control(
            'h4_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-content h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'h5_heading',
			[
				'label' => esc_html__( 'H5 Heading', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,				
			]
		);

		$this->add_control(
			'h5_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-content h5' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'h5_typography',
				'selector' => '{{WRAPPER}} .eel-post-content h5',
			]
		);

		$this->add_responsive_control(
            'h5_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-content h5' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'h6_heading',
			[
				'label' => esc_html__( 'H6 Heading', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,	
				'separator' => 'after',				
			]
		);	

		$this->add_control(
			'h6_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-content h6' => 'color: {{VALUE}};',
				],				
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'h6_typography',
				'selector' => '{{WRAPPER}} .eel-post-content h6',
			]
		);

		$this->add_responsive_control(
            'h6_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-content h6' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		
		$this->end_controls_section();

		$this->start_controls_section(
	        'ul_content_section',
	        [
	            'label' => esc_html__('Others Style', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_CONTENT,
	        ]
	    );

		$this->add_control(
			'ul_heading',
			[
				'label' => esc_html__( 'UL Style', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,	
				'separator' => 'after',				
			]
		);
		$this->add_control(
			'ul_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-content ul' => 'color: {{VALUE}};',
				],				
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'ul_typography',
				'selector' => '{{WRAPPER}} .eel-post-content ul',
			]
		);

		$this->add_responsive_control(
            'ul_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-content ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'ul_padding',
            [
                'label'      => __( 'Padding', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-content ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'li_heading',
			[
				'label' => esc_html__( 'LI Style', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,	
				'separator' => 'after',				
			]
		);

		$this->add_responsive_control(
            'li_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-content ul > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'li_padding',
            [
                'label'      => __( 'Padding', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-content ul > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'p_heading',
			[
				'label' => esc_html__( 'Paragraph Style', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,	
				'separator' => 'after',				
			]
		);

		$this->add_responsive_control(
            'p__margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'p_img_heading',
			[
				'label' => esc_html__( 'Paragraph Image Style', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,	
				'separator' => 'after',				
			]
		);

		$this->add_responsive_control(
            'p_image_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-content img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();

	}

	protected function render() {

		$post_id = function_exists( 'easyel_get_prepared_post_id' )
			? easyel_get_prepared_post_id()
			: get_the_ID();

		if ( ! $post_id ) {
			return;
		}

		$plugin = \Elementor\Plugin::$instance;

		$is_editor    = $plugin->editor->is_edit_mode();
		$is_preview   = $plugin->preview->is_preview_mode();
		$is_elementor = $is_editor || $is_preview;

		// Prevent recursion
		static $rendering_post_ids = [];
		if ( in_array( $post_id, $rendering_post_ids, true ) ) {
			echo '<div class="easyel-editor-placeholder">' . esc_html__( 'Post content will appear on frontend.', 'easy-elements' ) . '</div>';
			return;
		}
		$rendering_post_ids[] = $post_id;

		$content = '';

		if ( $is_elementor ) {
			$content = $plugin->frontend->get_builder_content_for_display(
				$post_id,
				true
			);
		}

		if ( empty( $content ) ) {
			$raw_content = get_post_field( 'post_content', $post_id );

			if ( $is_editor || $is_preview ) {
				// Snapshot the global hook callbacks BEFORE render so we can
				// identify what render_block() adds for dynamic per-block layout
				// rules (.wp-container-XYZ { grid-template-columns: … } etc.).
				// In the Elementor preview iframe those rules never reach the
				// <head> via the usual path, so Gutenberg grid / columns / flex
				// groups lose their dynamically-generated CSS. We capture from
				// THREE places (block themes use wp_head, classic themes use
				// wp_footer, and WP 6.0+ also stores rules in the Style Engine
				// "block-supports" context) and inline whatever appears.
				global $wp_filter;
				$snapshot_head   = ( isset( $wp_filter['wp_head'] ) && ! empty( $wp_filter['wp_head']->callbacks ) )
					? $wp_filter['wp_head']->callbacks
					: array();
				$snapshot_footer = ( isset( $wp_filter['wp_footer'] ) && ! empty( $wp_filter['wp_footer']->callbacks ) )
					? $wp_filter['wp_footer']->callbacks
					: array();

				$blocks = parse_blocks( $raw_content );
				if ( ! empty( $blocks ) ) {
					$content = '';
					foreach ( $blocks as $block ) {
						$content .= render_block( $block );
					}
				}

				$captured = '';

				// Capture closures added during render to wp_head / wp_footer
				// — these come from wp_enqueue_block_support_styles().
				foreach ( array( 'wp_head' => $snapshot_head, 'wp_footer' => $snapshot_footer ) as $hook => $snapshot ) {
					if ( empty( $wp_filter[ $hook ] ) || empty( $wp_filter[ $hook ]->callbacks ) ) {
						continue;
					}
					foreach ( $wp_filter[ $hook ]->callbacks as $priority => $cb_group ) {
						foreach ( $cb_group as $cb_id => $cb ) {
							if ( isset( $snapshot[ $priority ][ $cb_id ] ) ) {
								continue; // existed before render — not ours.
							}
							if ( empty( $cb['function'] ) || ! is_callable( $cb['function'] ) ) {
								continue;
							}
							ob_start();
							call_user_func( $cb['function'] );
							$captured .= ob_get_clean();
							// Remove so it doesn't double-output at the real hook.
							unset( $wp_filter[ $hook ]->callbacks[ $priority ][ $cb_id ] );
						}
					}
				}

				// Capture CSS rules from the WP Style Engine "block-supports"
				// context (WP 6.0+). This is where modern layout / spacing /
				// typography block-supports stash their generated CSS.
				if ( function_exists( '\wp_style_engine_get_stylesheet_from_context' ) ) {
					$engine_css = \wp_style_engine_get_stylesheet_from_context( 'block-supports', array( 'optimize' => true ) );
					if ( ! empty( $engine_css ) ) {
						$captured .= "<style id=\"easyel-post-content-block-supports\">{$engine_css}</style>\n";
						// Clear the store so wp_enqueue_stored_styles() (which
						// runs later for the queried-post render) doesn't emit
						// the same rules again.
						if ( class_exists( '\WP_Style_Engine' ) && method_exists( '\WP_Style_Engine', 'get_store' ) ) {
							$store = \WP_Style_Engine::get_store( 'block-supports' );
							if ( $store && method_exists( $store, 'remove_all_rules' ) ) {
								$store->remove_all_rules();
							}
						}
					}
				}

				if ( '' !== $captured ) {
					// Prepend captured <style> tags so dynamic layout CSS is
					// in place before the rendered block markup.
					$content = $captured . $content;
				}
			} else {

				// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				$content = apply_filters( 'the_content', $raw_content );
			}
		}

		if ( empty( $content ) ) {
			echo '<div class="easyel-editor-placeholder">' . esc_html__( 'Post content will appear here.', 'easy-elements' ) . '</div>';
			$key = array_search( $post_id, $rendering_post_ids, true );
			if ( $key !== false ) unset( $rendering_post_ids[ $key ] );
			return;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput
		echo '<div class="eel-post-content eel-gutenberg-content">' . $content . '</div>';

		$key = array_search( $post_id, $rendering_post_ids, true );
		if ( $key !== false ) unset( $rendering_post_ids[ $key ] );
	}
}

/**
 * Detect whether the current request is the Elementor editor or its preview
 * iframe. Used by the block-asset enqueue helpers below so they only act inside
 * Elementor and leave the front end alone.
 *
 * @return bool
 */
if ( ! function_exists( 'easyel_post_content_is_elementor_preview' ) ) {
	function easyel_post_content_is_elementor_preview() {
		if ( ! class_exists( '\Elementor\Plugin' ) || ! \Elementor\Plugin::$instance ) {
			return false;
		}
		$plugin = \Elementor\Plugin::$instance;
		if ( ! isset( $plugin->preview, $plugin->editor ) ) {
			return false;
		}
		return $plugin->preview->is_preview_mode() || $plugin->editor->is_edit_mode();
	}
}

/**
 * Bundle every core block stylesheet into the single wp-block-library handle
 * when inside Elementor preview/edit. Combined with the enqueue action below,
 * this guarantees block layout CSS (columns, image, gallery, button group, …)
 * is present in the preview iframe, where lazy per-block enqueues otherwise
 * miss the <head>. No effect on the front end.
 *
 * @param bool $load_separate Default WP behavior (per-block CSS).
 * @return bool
 */
if ( ! function_exists( 'easyel_post_content_disable_separate_block_assets' ) ) {
	function easyel_post_content_disable_separate_block_assets( $load_separate ) {
		return easyel_post_content_is_elementor_preview() ? false : $load_separate;
	}
	// Note: FQN is required because this file is in a namespace; bare string
	// callbacks would be looked up in the global scope and trigger a fatal.
	add_filter( 'should_load_separate_core_block_assets', __NAMESPACE__ . '\\easyel_post_content_disable_separate_block_assets' );
}

/**
 * Force-enqueue every registered block stylesheet inside the Elementor preview
 * iframe so post content rendered by this widget keeps its Gutenberg layout.
 *
 * Background: WP 5.8+ loads per-block CSS only when has_blocks() is true on the
 * QUERIED post. The Elementor preview iframe queries the Elementor template,
 * not the post displayed by this widget, so WP skips the enqueues and the
 * widget's markup renders without layout CSS (side-by-side images stack,
 * columns collapse, galleries lose grid). On the front end the queried post
 * is the rendered post, so this issue never appears there.
 *
 * This callback walks WP_Block_Type_Registry, enqueues each block type's
 * declared style handles (style_handles / view_style_handles /
 * editor_style_handles plus the legacy singular style / editor_style), and
 * also force-enqueues the comprehensive WP block stylesheets so theme.json
 * globals and classic-theme layout classes (is-layout-flex, is-layout-flow)
 * are present. Unregistered handles fail silently in wp_enqueue_style().
 *
 * @return void
 */
if ( ! function_exists( 'easyel_post_content_enqueue_block_styles_in_preview' ) ) {
	function easyel_post_content_enqueue_block_styles_in_preview() {
		if ( ! easyel_post_content_is_elementor_preview() ) {
			return;
		}

		// Core / global block stylesheets. Unregistered handles fail silently.
		$globals = array(
			'wp-block-library',
			'wp-block-library-theme',
			'global-styles',
			'classic-theme-styles',
			'wp-block-editor-content',
		);
		foreach ( $globals as $handle ) {
			if ( wp_style_is( $handle, 'registered' ) ) {
				wp_enqueue_style( $handle );
			}
		}

		if ( ! class_exists( '\WP_Block_Type_Registry' ) ) {
			return;
		}

		// Walk every registered block type and enqueue its declared style handles.
		$registry = \WP_Block_Type_Registry::get_instance();
		foreach ( $registry->get_all_registered() as $block_type ) {
			foreach ( array( 'style_handles', 'view_style_handles', 'editor_style_handles' ) as $prop ) {
				if ( empty( $block_type->{$prop} ) || ! is_array( $block_type->{$prop} ) ) {
					continue;
				}
				foreach ( $block_type->{$prop} as $handle ) {
					if ( is_string( $handle ) && wp_style_is( $handle, 'registered' ) ) {
						wp_enqueue_style( $handle );
					}
				}
			}
			// Older WP versions used singular `style` / `editor_style` properties.
			foreach ( array( 'style', 'editor_style' ) as $prop ) {
				$value = isset( $block_type->{$prop} ) ? $block_type->{$prop} : null;
				if ( is_string( $value ) && wp_style_is( $value, 'registered' ) ) {
					wp_enqueue_style( $value );
				}
			}
		}
	}
	// Priority 1 so our enqueues run BEFORE the active theme (priority 10) —
	// theme CSS then loads after wp-block-library-theme and wins for heading
	// sizes etc., keeping Gutenberg content visually consistent with the front.
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\easyel_post_content_enqueue_block_styles_in_preview', 1 );
}