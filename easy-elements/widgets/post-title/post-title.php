<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Easyel_Free_Post_Title_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-post-title';
	}

	public function get_title() {
		return __( 'Post Title', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-post-title';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
        return [ 'post', 'title', 'link', 'click', 'text', 'heading' ];
    }

	public function get_style_depends() {
        return [
            'eel-post-title',
        ];
    }


	protected function register_controls() {

	    $this->start_controls_section(
	        'content_section',
	        [
	            'label' => esc_html__('Post Title Settings', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_CONTENT,
	        ]
	    );

		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__('HTML Tag', 'easy-elements'),
				'type' => Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
			]
		);
		
		$this->add_control(
			'link_to_post',
			[
				'label' => esc_html__('Link to Post', 'easy-elements'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'easy-elements'),
				'label_off' => esc_html__('No', 'easy-elements'),
				'default' => '',
			]
		);

		$this->add_control(
			'align',
			[
				'label' => esc_html__('Alignment', 'easy-elements'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'easy-elements'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'easy-elements'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'easy-elements'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'toggle' => true,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__('Title Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-post-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .eel-post-title',
			]
		);
		$this->add_responsive_control(
			'margin',
			[
				'label' => esc_html__('Margin', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eel-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		$tag      = $settings['title_tag'] ?? 'h1';
		$align    = $settings['align'] ?? 'left';
		$link     = ! empty( $settings['link_to_post'] );
		$post_id = null;
		$post_id = function_exists('easyel_get_prepared_post_id')
       ? easyel_get_prepared_post_id()
       : null;
			

		if ( ! $post_id ) {
			return;
		}

		$post_obj = get_post( $post_id );
		if ( ! $post_obj instanceof \WP_Post ) {
			return;
		}

		global $post;
		$original_post = $post;

		$post = $post_obj;
		setup_postdata( $post );

		$title    = get_the_title();
		$post_url = get_permalink();
		?>

		<<?php echo esc_attr( $tag ); ?>
			class="eel-post-title"
			style="text-align:<?php echo esc_attr( $align ); ?>;">

			<?php if ( $link ) : ?>
				<a href="<?php echo esc_url( $post_url ); ?>">
					<?php echo esc_html( $title ); ?>
				</a>
			<?php else : ?>
				<?php echo esc_html( $title ); ?>
			<?php endif; ?>

		</<?php echo esc_attr( $tag ); ?>>

		<?php

		$post = $original_post;
		wp_reset_postdata();
	}



}
