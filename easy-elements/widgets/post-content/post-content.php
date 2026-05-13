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
			echo '<div class="easyel-editor-placeholder" style="padding:10px;border:1px dashed #ccc;">
				Post content will appear on frontend.
			</div>';
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
				$blocks = parse_blocks( $raw_content );
				if ( ! empty( $blocks ) ) {
					$content = '';
					foreach ( $blocks as $block ) {
						$content .= render_block( $block );
					}
				}
			} else {

				// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				$content = apply_filters( 'the_content', $raw_content );
			}
		}

		if ( empty( $content ) ) {
			echo '<div class="easyel-editor-placeholder" style="padding:10px;border:1px dashed #ccc;">
				Post content will appear here.
			</div>';
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