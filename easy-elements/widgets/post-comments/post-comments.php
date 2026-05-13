<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Free_Post_Comments_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-post-comments';
	}

	public function get_title() {
		return __( 'Post Comments', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-comments';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
		return [ 'comments', 'posts', 'next', 'prev', 'page', 'blog' ];
	}

	public function get_style_depends() {
        return [
            'eel-post-comments',
        ];
    }


	protected function register_controls() {
		// button style
		$this->start_controls_section(
			'comments_content',
			[
				'label' => __( 'Comments', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_comments',
			[
				'label' => __( 'Show Comments', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'easy-elements' ),
				'label_off' => __( 'No', 'easy-elements' ),
				'default' => '',
			]
		);

		$this->add_control(
			'input__color',
			[
				'label' => __( 'Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-comments-widget .comment-respond, {{WRAPPER}} .eel-post-comments-widget .title-comments, {{WRAPPER}} .eel-post-comments-widget .comment-list *' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'input__color_link',
			[
				'label' => __( 'Link Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-comments-widget .comment-respond a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();	

		$this->start_controls_section(
			'form_content_label',
			[
				'label' => __( 'Label', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_label',
			[
				'label' => __( 'Show Label', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'easy-elements' ),
				'label_off' => __( 'No', 'easy-elements' ),
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'input_label_color',
			[
				'label' => __( 'Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-comments-widget #comments .comment-form label' => 'color: {{VALUE}};',
				],
				'condition' => [ 'show_label' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'input_label_margin',[
				'label' => __( 'Margin', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eel-post-comments-widget #comments .comment-form label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [ 'show_label' => 'yes' ],
			]
		);

		// Input Color Picker
		$this->add_group_control(
    		\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'input_label_typography',
				'label' => __( 'Typography', 'easy-elements' ),
				'selector' => '{{WRAPPER}} .eel-post-comments-widget #comments .comment-form label',
				'description' => __( 'Customize the typography for form field labels', 'easy-elements' ),
				'condition' => [ 'show_label' => 'yes' ],
			]
		);
		$this->end_controls_section();	
		
		// form style 
		$this->start_controls_section(
			'form_content',
			[
				'label' => __( 'Input', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' => __( 'Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-comments-widget #comments .comment-form input:not([type="submit"]), 
					{{WRAPPER}} .eel-post-comments-widget #comments .comment-form textarea'
						=> 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'  => 'input_background',
				'label' => __( 'Background', 'easy-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' =>
					'{{WRAPPER}} .eel-post-comments-widget #comments .comment-form input:not([type="submit"]), 
					{{WRAPPER}} .eel-post-comments-widget #comments .comment-form textarea',
			]
		);

		$this->add_control(
			'input_focus_border_color',
			[
				'label' => __( 'Focus Border Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-comments-widget #comments .comment-form input:not([type="submit"]):focus, 
					{{WRAPPER}} .eel-post-comments-widget #comments .comment-form textarea:focus'
						=> 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_form_padding',
			[
				'label' => __( 'Padding', 'easy-elements' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eel-post-comments-widget #comments .comment-form input:not([type="submit"]), 
					{{WRAPPER}} .eel-post-comments-widget #comments .comment-form textarea'
						=> 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		/* ================= Border Radius ================= */
		$this->add_responsive_control(
			'input_border_radius',
			[
				'label' => __( 'Border Radius', 'easy-elements' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eel-post-comments-widget #comments .comment-form input:not([type="submit"]), 
					{{WRAPPER}} .eel-post-comments-widget #comments .comment-form textarea'
						=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		/* ================= Border ================= */
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'input_border',
				'selector' =>
					'{{WRAPPER}} .eel-post-comments-widget #comments .comment-form input:not([type="submit"]), 
					{{WRAPPER}} .eel-post-comments-widget #comments .comment-form textarea',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'comments_content_button_style',
			[
				'label' => __( 'Button', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		/* Tabs start */
		$this->start_controls_tabs( 'button_style_tabs' );

		/* ================= NORMAL TAB ================= */
		$this->start_controls_tab(
			'button_normal_tab',
			[
				'label' => __( 'Normal', 'easy-elements' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond .form-submit #submit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond .form-submit #submit' => 'background-color: {{VALUE}};',
				],
			]
		);

		/* Typography */
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .comment-respond .form-submit #submit',
			]
		);

		/* Padding */
		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .comment-respond .form-submit #submit' =>
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .comment-respond .form-submit #submit',
			]
		);

		$this->end_controls_tab();

		/* ================= HOVER TAB ================= */
		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => __( 'Hover', 'easy-elements' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond .form-submit #submit:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond .form-submit #submit:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'button_border_hover',
				'selector' => '{{WRAPPER}} .comment-respond .form-submit #submit:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

	}
	

	protected function render() {
		$settings = $this->get_settings_for_display();

		global $post;
		$original_post = $post;

		// Get post object: editor mode uses prepared post, frontend uses current post
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			if ( ($settings['show_comments'] ?? 'no') !== 'yes' ) {
				// Editor mode: show nothing if 'show_comments' is not 'yes'
				echo '<p>' . esc_html__( 'Comments are disabled in editor preview.', 'easy-elements' ) . '</p>';
				return;
			}

			$post_id = function_exists('easyel_get_prepared_post_id') 
				? easyel_get_prepared_post_id() 
				: get_the_ID();
			$post_obj = get_post( $post_id );

		} else {
			if ( ($settings['show_comments'] ?? 'no') !== 'yes' ) {
				// Frontend: comments disabled in settings
				return;
			}

			$post_obj = $post;
			if ( ! $post_obj instanceof \WP_Post ) {
				echo '<p>' . esc_html__( 'Invalid post object.', 'easy-elements' ) . '</p>';
				return;
			}
		}

		$post = $post_obj;
		setup_postdata( $post );
		$label_class = ! empty( $settings['show_label'] ) ? $settings['show_label'] : '';
		echo '<div class="eel-post-comments-widget label--' . esc_attr( $label_class ) . ' ">';

		if ( post_type_supports( $post->post_type, 'comments' ) ) {
			if ( post_password_required() ) {
				echo '<p>' . esc_html__( 'This post is password protected. Enter the password to view comments.', 'easy-elements' ) . '</p>';
			} else {
				comments_template();
			}
		} else {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<p>' . esc_html__( 'Comments are not enabled for this post type.', 'easy-elements' ) . '</p>';
			}
		}

		echo '</div>';

		wp_reset_postdata();
		$post = $original_post;

	}

} 
