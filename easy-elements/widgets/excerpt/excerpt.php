<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Free_Excerpt_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'eel-excerpt';
	}

	public function get_title() {
		return __( 'Post Excerpt', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-post-excerpt';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
        return [ 'excerpt', 'content', 'link', 'text', 'post-excerpt' ];
    }

	public function get_style_depends() {
        return [
            'eel-excerpt',
        ];
    }

	protected function register_controls() {

	    $this->start_controls_section(
	        'content_section',
	        [
	            'label' => esc_html__('Excerpt Settings', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_CONTENT,	
	        ]
	    );

		$this->add_control(
			'excerpt_length',
			[
				'label' => esc_html__('Excerpt Length', 'easy-elements'),
				'type' => Controls_Manager::NUMBER,
				'default' => 55,
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' => esc_html__('Excerpt Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .eel-excerpt',
			]
		);

		// Read More Button 
		$this->add_control(
			'display_button',
			[
				'label' => esc_html__('Read More Enable', 'easy-elements'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'easy-elements'),
				'label_off' => esc_html__('Hide', 'easy-elements'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'read_more_text',
			[
				'label' => esc_html__('Read More', 'easy-elements'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Read More', 'easy-elements'),
				'condition' => [
					'display_button' => 'yes',
				],
			]
		);

		// Start Button Style Tabs
		$this->start_controls_tabs('button_style_tabs', [
				'condition' => ['display_button' => 'yes'],
			]);
			// Normal State Tab
			$this->start_controls_tab(
				'button_normal_tab',
				[
					'label' => esc_html__('Normal', 'easy-elements'),
				]
			 );

				$this->add_control(
					'button_color',
					[
						'label' => esc_html__('Button Color', 'easy-elements'),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .eel-excerpt-readmore' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'background_buttpm',
						'types' => [ 'classic', 'gradient', 'video' ],
						'selector' => '{{WRAPPER}} .eel-excerpt-readmore',
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					[
						'name' => 'button_border',
						'selector' => '{{WRAPPER}} .eel-excerpt-readmore',
					]
				);
			$this->end_controls_tab();

			// Hover State Tab
			$this->start_controls_tab(
				'button_hover_tab',
				[
					'label' => esc_html__('Hover', 'easy-elements'),
				]
			 );
				$this->add_control(
					'button_hover_color',
					[
						'label' => esc_html__('Button Hover Color', 'easy-elements'),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .eel-excerpt-readmore:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'button_hover_background',
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .eel-excerpt-readmore:hover',
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					[
						'name' => 'button_hover_border',
						'selector' => '{{WRAPPER}} .eel-excerpt-readmore:hover',
					]
				);
			$this->end_controls_tab();

		$this->end_controls_tabs();

		// Add Padding control for the button
		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__('Button Padding', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eel-excerpt-readmore' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'display_button' => 'yes',
				],
			]
		);

		// Add Margin control for the button
		$this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__('Button Margin', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eel-excerpt-readmore' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'display_button' => 'yes',
				],
			]
		);		
	
		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$excerpt_length = !empty($settings['excerpt_length']) ? intval($settings['excerpt_length']) : 55;
		$read_more_text = !empty($settings['read_more_text']) ? $settings['read_more_text'] : esc_html__('Read More', 'easy-elements');
		$display_button = !isset($settings['display_button']) || $settings['display_button'] === 'yes';

		$post_id = function_exists('easyel_get_prepared_post_id') 
			? easyel_get_prepared_post_id() 
			: get_the_ID();

		$post_obj = get_post( $post_id );
		if ( ! $post_obj instanceof \WP_Post ) {
			echo '<p>' . esc_html__('No valid post found.', 'easy-elements') . '</p>';
			return;
		}

		global $post;
		$original_post = $post;
		$post = $post_obj;
		setup_postdata( $post );

		$excerpt = get_the_excerpt();
		$post_url = get_permalink();

		$excerpt = wp_trim_words( $excerpt, $excerpt_length, '...' );

		?>
		<div class="eel-excerpt">
			<div class="eel-excerpt-text"><?php echo esc_html($excerpt); ?></div>
			<?php if ( !empty($read_more_text) && $display_button ) : ?>
				<a href="<?php echo esc_url($post_url); ?>" class="eel-excerpt-readmore"><?php echo esc_html($read_more_text); ?></a>
			<?php endif; ?>
		</div>
		<?php

		wp_reset_postdata();
		$post = $original_post;
	}

}
