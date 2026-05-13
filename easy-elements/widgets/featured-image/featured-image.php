<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_free_Featured_Image_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-featured-image';
	}

	public function get_title() {
		return __( 'Featured Image', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-image-carousel';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}
	public function get_keywords() {
        return [ 'image', 'thumbnail', 'link', 'featured' ];
    }

	public function get_style_depends() {
        return [
            'eel-featured-image',
        ];
    }

	protected function register_controls() {

	    $this->start_controls_section(
	        'content_section',
	        [
	            'label' => esc_html__('Featured Image Settings', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_CONTENT,
	        ]
	    );

		$this->add_responsive_control(
			'featured_image_width',
			[
				'label' => esc_html__('Width', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-featured-image-inner img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'featured_image_height',
			[
				'label' => esc_html__('Height', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'%' => [
						'min' => 100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-featured-image-inner img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => esc_html__('Border Radius', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'.eel-featured-image-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		// Image Size Control
		$this->add_control(
			'image_size',
			[
				'label' => esc_html__('Image Size', 'easy-elements'),
				'type' => Controls_Manager::SELECT,
				'default' => 'full',
				'options' => [
					'thumbnail' => esc_html__('Thumbnail', 'easy-elements'),
					'medium' => esc_html__('Medium', 'easy-elements'),
					'large' => esc_html__('Large', 'easy-elements'),
					'full' => esc_html__('Full', 'easy-elements'),
				],
			]
		);

		// Add show/hide image link control
		$this->add_control(
			'show_image_link',
			[
				'label' => esc_html__('Show Image Link', 'easy-elements'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'easy-elements'),
				'label_off' => esc_html__('Hide', 'easy-elements'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$image_size = $settings['image_size'] ?? 'full';
		$editor_mode = \Elementor\Plugin::instance()->editor->is_edit_mode();
		$wrapper_classes = 'eel-featured-image-wrapper' . ( $editor_mode ? ' ' : '' );

		$post_id = function_exists('easyel_get_prepared_post_id') 
			? easyel_get_prepared_post_id() 
			: ( isset($GLOBALS['post']->ID) ? $GLOBALS['post']->ID : 0 );
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( !$post_id && isset($_GET['preview_id']) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_id = intval(sanitize_text_field(wp_unslash($_GET['preview_id'])));
		}

		$image_url = '';
		$image_alt = '';
		$post_link = '';

		if ( $post_id && has_post_thumbnail( $post_id ) ) {
			$image_url = get_the_post_thumbnail_url( $post_id, $image_size );
			$image_alt = get_the_title( $post_id );
			$post_link = get_permalink( $post_id );
		}

		?>
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
			<div class="eel-featured-image-inner">
				<?php if ( !empty($image_url) ) : ?>
					<?php if ( ($settings['show_image_link'] ?? '') === 'yes' && !empty($post_link) ) : ?>
						<a href="<?php echo esc_url( $post_link ); ?>" class="eel-featured-image-link">
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ?: __('Featured Image', 'easy-elements') ); ?>" class="eel-featured-image-img" loading="lazy" />
						</a>
					<?php else : ?>
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ?: __('Featured Image', 'easy-elements') ); ?>" class="eel-featured-image-img" loading="lazy" />
					<?php endif; ?>
				<?php elseif ( $editor_mode ) : ?>
					<div class="eel-featured-image-placeholder">
						<svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" class="eel-featured-image-placeholder-icon">
							<defs>
								<linearGradient id="imgGradient" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
									<stop stop-color="#7fd7ff"/>
									<stop offset="1" stop-color="#0073e6"/>
								</linearGradient>
							</defs>
							<rect x="8" y="14" width="48" height="36" rx="5" fill="#f0f6ff" stroke="url(#imgGradient)" stroke-width="2.5"/>
							<circle cx="22" cy="28" r="5" fill="#b3d4fc" />
							<path d="M14 44L26 32L36 42L44 34L52 42" stroke="#b3d4fc" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<div class="eel-featured-image-placeholder-title"><?php esc_html_e('No Featured Image', 'easy-elements'); ?></div>
						<div class="eel-featured-image-placeholder-desc"><?php esc_html_e('Please add a featured image to display here.', 'easy-elements'); ?></div>
						<a href="#" class="eel-featured-image-placeholder-btn" tabindex="-1"><?php esc_html_e('Set Featured Image', 'easy-elements'); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

}
