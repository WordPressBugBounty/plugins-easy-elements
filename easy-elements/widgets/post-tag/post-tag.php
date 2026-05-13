<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Free_Post_Tags_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-post-tags';
	}

	public function get_title() {
		return __( 'Current Post Tags', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-post-tag';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
		return [ 'tags', 'post tags', 'meta', 'taxonomy', 'blog', 'text' ];
	}

	public function get_style_depends() {
        return [
            'eel-post-tags',
        ];
    }


	protected function register_controls() {

		$this->start_controls_section(
			'tags_content',
			[
				'label' => __( 'Tags', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_label',
			[
				'label'        => __( 'Show Label', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements' ),
				'label_off'    => __( 'No', 'easy-elements' ),
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'label_text',
			[
				'label'       => __( 'Label Text', 'easy-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Tags:', 'easy-elements' ),
				'condition'   => [
					'show_label' => 'yes',
				],
			]
		);

		$this->add_control( 'separator', [
			'label'   => __( 'Separator', 'easy-elements' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
			'description' => __( 'e.g. , or | or leave empty', 'easy-elements' ),
		]);

		$this->end_controls_section();

		// ===================== STYLE: WRAPPER =====================
		$this->start_controls_section( 'wrapper_style', [
			'label' => __( 'Wrapper', 'easy-elements' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]);

		$this->add_responsive_control( 'wrapper_alignment', [
			'label' => __( 'Alignment', 'easy-elements' ),
			'type'  => Controls_Manager::CHOOSE,
			'options' => [
				'flex-start' => [ 'title' => __( 'Left', 'easy-elements' ), 'icon' => 'eicon-h-align-left' ],
				'center'     => [ 'title' => __( 'Center', 'easy-elements' ), 'icon' => 'eicon-h-align-center' ],
				'flex-end'   => [ 'title' => __( 'Right', 'easy-elements' ), 'icon' => 'eicon-h-align-right' ],
			],
			'selectors' => [
				'{{WRAPPER}} .eel-post-tags-widget' => 'justify-content: {{VALUE}};',
			],
		]);

		$this->add_responsive_control( 'wrapper_gap', [
			'label' => __( 'Gap', 'easy-elements' ),
			'type'  => Controls_Manager::SLIDER,
			'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
			'selectors' => [
				'{{WRAPPER}} .eel-post-tags-widget' => 'gap: {{SIZE}}{{UNIT}};',
			],
		]);

		$this->end_controls_section();

		// ===================== STYLE: TITLE LABEL =====================
		$this->start_controls_section(
			'title_style',
			[
				'label' => __( 'Title Label', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-tags-widget .eel-tags-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Margin', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eel-post-tags-widget .eel-tags-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .eel-post-tags-widget .eel-tags-label',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tags_style',
			[
				'label' => __( 'Tag', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tags_typography',
				'selector' => '{{WRAPPER}} .eel-post-tags-widget a',
			]
		);

		$this->start_controls_tabs( 'tabs_tag_style' );

			// Normal Tab
			$this->start_controls_tab(
				'tab_tag_normal',
				[
					'label' => __( 'Normal', 'easy-elements' ),
				]
			);

			$this->add_control(
				'tags_color',
				[
					'label'     => __( 'Text Color', 'easy-elements' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .eel-post-tags-widget a' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'tags__bg_color',
					'label' => __('Background', 'easy-elements'),
					'types' => ['classic', 'gradient'],
					'selector' => '{{WRAPPER}} .eel-post-tags-widget a',
				]
			);

			$this->add_group_control( Group_Control_Border::get_type(), [
				'name'     => 'tags_border',
				'selector' => '{{WRAPPER}} .eel-post-tags-widget a',
			]);

			$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
				'name'     => 'tags_box_shadow',
				'selector' => '{{WRAPPER}} .eel-post-tags-widget a',
			]);

			$this->end_controls_tab();

			// Hover Tab
			$this->start_controls_tab(
				'tab_tag_hover',
				[
					'label' => __( 'Hover', 'easy-elements' ),
				]
			);

			$this->add_control(
				'tags_hover_color',
				[
					'label'     => __( 'Text Color', 'easy-elements' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .eel-post-tags-widget a:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'tags_hover_bg_color',
					'label' => __('Background', 'easy-elements'),
					'types' => ['classic', 'gradient'],
					'selector' => '{{WRAPPER}} .eel-post-tags-widget a:hover',
				]
			);

			$this->add_group_control( Group_Control_Border::get_type(), [
				'name'     => 'tags_hover_border',
				'selector' => '{{WRAPPER}} .eel-post-tags-widget a:hover',
			]);

			$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
				'name'     => 'tags_hover_box_shadow',
				'selector' => '{{WRAPPER}} .eel-post-tags-widget a:hover',
			]);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'tags_border_radius', [
			'label'      => __( 'Border Radius', 'easy-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'separator'  => 'before',
			'selectors'  => [
				'{{WRAPPER}} .eel-post-tags-widget a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]);

		$this->add_responsive_control(
			'tags_margin',
			[
				'label' => __( 'Margin', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eel-post-tags-widget a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tags_padding',
			[
				'label' => __( 'Padding', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eel-post-tags-widget a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		// Password protected post check
		if ( post_password_required() ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$post_id = function_exists('easyel_get_prepared_post_id') 
			? easyel_get_prepared_post_id() 
			: get_the_ID();

		$post_obj = get_post( $post_id );
		if ( ! $post_obj instanceof \WP_Post ) {
			return;
		}

		// Setup global post for template functions
		global $post;
		$original_post = $post;
		$post = $post_obj;
		setup_postdata( $post );

		$tags = get_the_tags();
		if ( ! $tags || is_wp_error( $tags ) ) {
			wp_reset_postdata();
			$post = $original_post;
			return;
		}

		$separator = $settings['separator'] ?? '';

		echo '<div class="eel-post-tags-widget">';

		if ( 'yes' === ( $settings['show_label'] ?? 'no' ) && ! empty( $settings['label_text'] ) ) {
			echo '<span class="eel-tags-label">' . esc_html( $settings['label_text'] ) . '</span>';
		}

		$tag_links = [];
		foreach ( $tags as $tag ) {
			$tag_links[] = '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" rel="tag">' . esc_html( $tag->name ) . '</a>';
		}

		if ( $separator ) {
			echo wp_kses(
				implode(
					'<span class="eel-tags-sep">' . esc_html( $separator ) . '</span>',
					$tag_links
				),
				easyel_allowed_html()
			);
		} else {
			echo wp_kses(
				implode( '', $tag_links ),
				easyel_allowed_html()
			);
		}

		echo '</div>';

		// Reset global post
		wp_reset_postdata();
		$post = $original_post;
	}

}