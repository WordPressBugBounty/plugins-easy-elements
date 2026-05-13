<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;


class Easyel_Free_Post_Author_Info_Widget extends Widget_Base {
	public function get_name() {
		return 'eel-post-author-info';
	}

	public function get_title() {
		return __( 'Post Author Info', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-author';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
		return [ 'author', 'meta', 'post', 'blog', 'text' ];
	}

	public function get_style_depends() {
        return [
            'eel-post-author-info',
        ];
    }


	protected function register_controls() {

		$this->start_controls_section(
			'author_content',
			[
				'label' => __( 'Author Info', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_avatar',
			[
				'label' => __( 'Show Avatar', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'easy-elements' ),
				'label_off' => __( 'No', 'easy-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_bio',
			[
				'label' => __( 'Show Bio', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'easy-elements' ),
				'label_off' => __( 'No', 'easy-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_post_count',
			[
				'label' => __( 'Show Total Posts', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'easy-elements' ),
				'label_off' => __( 'No', 'easy-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'count_middle_space',
			[
				'label' => __( 'Spacing', 'easy-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-author-info .eel-author-description' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

		// Style Name
		$this->start_controls_section(
			'author_style',
			[
				'label' => __( 'Name', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_typography',
				'selector' => '{{WRAPPER}} .eel-author-info .eel-author-name',
			]
		);

		$this->add_control(
			'author_text_color',
			[
				'label' => __( 'Text Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-author-info .eel-author-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'author_margin',
			[
				'label' => __( 'Margin', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eel-author-info .eel-author-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();


		// Style Description
		$this->start_controls_section(
			'bio_style',
			[
				'label' => __( 'Biographical Info', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bio_typography',
				'selector' => '{{WRAPPER}} .eel-author-info .eel-author-bio',
			]
		);

		$this->add_control(
			'bio_text_color',
			[
				'label' => __( 'Text Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-author-info .eel-author-bio' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'bio_margin',
			[
				'label' => __( 'Margin', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eel-author-info .eel-author-bio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		// Style Count
		$this->start_controls_section(
			'count_style',
			[
				'label' => __( 'Post Count', 'easy-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'count_typography',
				'selector' => '{{WRAPPER}} .eel-author-post-count',
			]
		);

		$this->add_control(
			'count_text_color',
			[
				'label' => __( 'Text Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-author-post-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'count_margin',
			[
				'label' => __( 'Margin', 'easy-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eel-author-post-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

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

		$author_id   = $post->post_author;
		$author_name = get_the_author_meta( 'display_name', $author_id );

		echo '<div class="eel-author-info">';

		echo '<div class="eel-author-image">';
		if ( 'yes' === $settings['show_avatar'] ) {
			echo get_avatar( $author_id, 96, '', $author_name, ['class' => 'eel-author-avatar'] );
		}
		echo '</div>';

		echo '<div class="eel-author-description">';
		
		echo '<h4 class="eel-author-name">' . esc_html( $author_name ) . '</h4>';

		if ( 'yes' === $settings['show_bio'] ) {
			$bio = get_the_author_meta('description', $author_id);
			if ( $bio ) {
				echo '<p class="eel-author-bio">' . esc_html( $bio ) . '</p>';
			}
		}

		// Total posts
		if ( 'yes' === $settings['show_post_count'] ) {
			$post_count = (int) count_user_posts( $author_id, 'post' );
			echo '<span class="eel-author-post-count">' .
				/* translators: %d: number of posts */
				esc_html( sprintf( _n( '%d post', '%d posts', $post_count, 'easy-elements' ), $post_count ) ) .
				'</span>';
		}

		echo '</div>'; 
		echo '</div>'; 

		wp_reset_postdata();
		$post = $original_post;
	}

}
