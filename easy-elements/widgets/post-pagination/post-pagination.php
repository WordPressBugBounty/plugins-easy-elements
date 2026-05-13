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

class Easyel_Free_Post_Pagination_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'eel-post-pagination';
	}

	public function get_title() {
		return __( 'Post Pagination', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-pagination';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
		return [ 'pagination', 'posts', 'next', 'prev', 'page', 'blog' ];
	}

	public function get_style_depends() {
        return [
            'eel-post-pagination',
        ];
    }


	protected function register_controls() {
		// Content Tab
		$this->start_controls_section(
			'pagination_content',
			[
				'label' => __( 'Pagination', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_prev_next',
			[
				'label' => __( 'Show Prev/Next', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'easy-elements' ),
				'label_off' => __( 'No', 'easy-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'prev_label',
			[
				'label' => __( 'Previous Label', 'easy-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Previous', 'easy-elements' ),
				'condition' => [ 'show_prev_next' => 'yes' ],
			]
		);

		$this->add_control(
			'next_label',
			[
				'label' => __( 'Next Label', 'easy-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Next', 'easy-elements' ),
				'condition' => [ 'show_prev_next' => 'yes' ],
			]
		);

		$this->end_controls_section();

		// Style Tab
		$this->start_controls_section(
			'pagination_style',
			[
				'label' => __( 'Pagination', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label' => __( 'Label Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-nav-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Label Typography', 'easy-elements' ),
				'selector' => '{{WRAPPER}} .eel-nav-label, {{WRAPPER}} .eel-nav-label i',
			]
		);


		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-nav-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title Typography', 'easy-elements' ),
				'selector' => '{{WRAPPER}} .eel-nav-title',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Get the proper post ID (editor or frontend)
		$post_id = function_exists('easyel_get_prepared_post_id') 
				? easyel_get_prepared_post_id() 
				: get_the_ID();

		if ( ! $post_id ) {
			return; // No post to show
		}

		$post_obj = get_post( $post_id );
		if ( ! $post_obj instanceof \WP_Post ) {
			return;
		}

		global $post;
		$original_post = $post;
		$post = $post_obj;
		setup_postdata( $post );

		echo '<nav class="eel-pagination" aria-label="Post Navigation"><ul>';

		if ( ($settings['show_prev_next'] ?? '') === 'yes' ) {

			// Previous post link
			$prev = get_previous_post_link(
				'<li>%link</li>',
				'<span class="eel-nav-label"><i class="unicon-arrow-left"></i> ' 
				. esc_html( $settings['prev_label'] ?? 'Previous' ) 
				. '</span><span class="eel-nav-title">%title</span>'
			);

			// Next post link
			$next = get_next_post_link(
				'<li>%link</li>',
				'<span class="eel-nav-label">' 
				. esc_html( $settings['next_label'] ?? 'Next' ) 
				. ' <i class="unicon-arrow-right"></i></span><span class="eel-nav-title">%title</span>'
			);

			if ( $prev ) echo wp_kses_post( $prev );
			if ( $next ) echo wp_kses_post( $next );
		}

		echo '</ul></nav>';

		wp_reset_postdata();
		$post = $original_post;
	}
} 