<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Offcanvas_Widget extends Widget_Base {

	public function get_name() {
		return 'eel-offcanvas';
	}

	public function get_title() {
		return __( 'Offcanvas', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-canvas';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
        return [ 'offcanvas', 'text', 'link', 'image' ];
    }

	public function get_style_depends() {
        return [
            'eel-offcanvas',
        ];
    }

	public function get_script_depends() {
        return [
            'eel-offcanvas',
        ];
    }

	protected function register_controls() {
		$this->start_controls_section(
			'section_offcanvas',
			[
				'label' => __( 'Offcanvas Settings', 'easy-elements' ),
			]
		);

		$this->add_control(
			'offcanvas_layout',
			[
				'label'   => __( 'Offcanvas Layout', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'classic',
				'options' => [
					'classic'  => __( 'Classic', 'easy-elements' ),
					'modern' => __( 'Modern', 'easy-elements' ),
				],
			]
		);

		$this->add_control(
			'menu_text',
			[
				'label'   => __( 'Canvas Menu', 'easy-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Menu', 'easy-elements' ),
			]
		);

		$this->add_control(
			'btn_icon',
			[
				'label' => esc_html__('Menu Icon', 'easy-elements'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => '',
					'library' => '',
				],
			]
		);

		$this->add_control(
			'position_offcanvas',
			[
				'label'   => __( 'Open Position', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'eel-offcanvas-right',
				'options' => [
					'eel-offcanvas-left'  => __( 'Left', 'easy-elements' ),
					'eel-offcanvas-right' => __( 'Right', 'easy-elements' ),
				],
				'condition' => [
					'offcanvas_layout' => 'classic',
				],
			]
		);

		$this->add_responsive_control(
			'offcanvas_width',
			[
				'label'        => __( 'Canvas Width', 'easy-elements' ),
				'type'         => \Elementor\Controls_Manager::SLIDER,
				'size_units'   => [ 'px', '%' ],
				'range'        => [
					'px' => [
						'min' => 200,
						'max' => 800,
					],
					'%' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default'      => [
					'unit' => 'px',
					'size' => 380,
				],
				'selectors'    => [
					'.eel-offcanvas' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'offcanvas_layout' => ['classic', 'modern'],
				],
			]
		);

		$this->add_control(
            'content_template',
            [
                'label' => esc_html__( 'Select Template', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_eligible_canvas_templates(),
            ]
        );

		$this->add_control(
			'edit_template_button',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => sprintf(
					'<a href="%s" class="elementor-button elementor-button-primary" target="_blank">%s</a>
					<p style="margin-top:5px; font-size:13px; color:#555;">%s</p>',
					admin_url( 'edit.php?post_type=easy-offcanvas' ),
					esc_html__( 'Edit Offcanvas Templates', 'easy-elements' ),
					esc_html__( 'All your offcanvas content can be managed here in the Easy Offcanvas post type.', 'easy-elements' )
				),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'close_icon',
			[
				'label' => esc_html__('Close Icon', 'easy-elements'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => '',
					'library' => '',
				],
			]
		);

		$this->add_control(
		'need_blur_effect',
			[
				'label' => esc_html__( 'Enable Blur', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$this->end_controls_section();

		// Style 
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style', 'easy-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		 );
			$this->add_control(
				'open_icon_options',
				[
					'label' => esc_html__( 'Opener Icon', 'easy-elements' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'after',
				]
		 	);
			$this->add_control(
				'offcanvas_opener_text_color',
				[
					'label' => esc_html__( 'Text Color', 'easy-elements' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .eel-offcanvas-toggle-text .eel-icon-text' => 'color: {{VALUE}};',
					],
					'condition' => [
						'menu_text!' => '',
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'offcanvas_opener_text_typography',
					'label' => esc_html__('Typography', 'easy-elements' ),
					'selector' => '{{WRAPPER}} .eel-offcanvas-toggle-text .eel-icon-text',
					'separator' => 'after',
					'condition' => [
						'menu_text!' => '',
					],
				]
			); 
			$this->add_control(
				'offcanvas_opener_icon_color',
				[
					'label' => esc_html__( 'Hamburger Color', 'easy-elements' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .eel-offcanvas-toggle-text svg' => 'fill: {{VALUE}};',
						'{{WRAPPER}} .eel-offcanvas-toggle-text i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .eel-icon-menu' => 'border-bottom-color: {{VALUE}};',
					],
					'condition' => [
						'offcanvas_layout' => 'classic',
					],
				]
			);
			$this->add_control(
				'offcanvas_opener_icon_humburger_color',
				[
					'label' => esc_html__( 'Hamburger Color', 'easy-elements' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.modern.eel-offcanvas-wrapper label span' => 'background: {{VALUE}} !important;',

					],
					'condition' => [
						'offcanvas_layout' => 'modern',
					],
				]
			);
			$this->add_responsive_control(
				'offcanvas_opener_icon_size',
				[
					'label'        => __( 'Hamburger Size', 'easy-elements' ),
					'type'         => \Elementor\Controls_Manager::SLIDER,
					'size_units'   => [ 'px', '%' ],
					'selectors'    => [
						'{{WRAPPER}} .eel-offcanvas-toggle-text svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
						'{{WRAPPER}} .eel-offcanvas-toggle-text i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					],
					'condition' => [
						'offcanvas_layout' => 'classic',
					],
				]
			);
			$this->add_responsive_control(
				'offcanvas_opener_icon_size_morden',
				[
					'label'        => __( 'Hamburger Size', 'easy-elements' ),
					'type'         => \Elementor\Controls_Manager::SLIDER,
					'size_units'   => [ 'px', '%' ],
					'selectors'    => [
						'{{WRAPPER}} .modern.eel-offcanvas-wrapper label' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'offcanvas_layout' => 'modern',
					],
				]
			);
			$this->add_control(
				'closing_icon_options',
				[
					'label' => esc_html__( 'Closing Icon', 'easy-elements' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'after',
				]
		 	);

			$this->add_control(
				'offcanvas_closing_icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'easy-elements' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.eel-offcanvas-close' => 'color: {{VALUE}} !important;',
					],
					'condition' => [
						'offcanvas_layout' => 'classic',
					],
				]
			);

			$this->add_control(
				'offcanvas_closing_icon_morden_color',
				[
					'label' => esc_html__( 'Icon Color', 'easy-elements' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.eel-modern-close-toggle span, .modern.eel-offcanvas-wrapper label.eel-modern-close-toggle span' => 'background: {{VALUE}} !important;',
					],
					'condition' => [
						'offcanvas_layout' => 'modern',
					],
				]
			);

			$this->add_responsive_control(
				'offcanvas_closing_icon_size',
				[
					'label'        => __( 'Icon Size', 'easy-elements' ),
					'type'         => \Elementor\Controls_Manager::SLIDER,
					'size_units'   => [ 'px', '%' ],
					'selectors'    => [
						'.eel-offcanvas-close' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					],
					'condition' => [
						'offcanvas_layout' => 'classic',
					],
				]
			);
			$this->add_control(
				'offcanvas_item',
				[
					'label' => esc_html__( 'OffCanvas Item', 'easy-elements' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'after',
				]
		 	);
			$this->add_control(
				'offcanvas_bg',
				[
					'label' => esc_html__( 'OffCanvas Background', 'easy-elements' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'body .eel-offcanvas' => 'background: {{VALUE}} !important;',
					],
				]
			);
			$this->add_responsive_control(
				'offcanvas_padding',
				[
					'label' => esc_html__('OffCanvas Padding', 'easy-elements'),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'body .eel-offcanvas' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);
		$this->end_controls_section();
	}

	private function get_eligible_canvas_templates() {
        $templates = [];

        $posts = get_posts([
            'post_type' => 'easy-offcanvas',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);

        foreach ( $posts as $post ) {
            $templates[ $post->ID ] = $post->post_title;
        }

        return $templates;
    }

	protected function render() {
		$settings  = $this->get_settings_for_display();
		$position  = $settings['position_offcanvas'];
		$btn_text  = ! empty( $settings['menu_text'] ) ? $settings['menu_text'] :  '';
		$content_template = ! empty( $settings['content_template'] ) ? $settings['content_template'] : '';
		$blur = ( isset( $settings['need_blur_effect'] ) && $settings['need_blur_effect'] === 'yes' ) ? 'eel-blur-effect' : '';
		$unique_id = 'eel-offcanvas-' . $this->get_id();
		?>		

		<div class="eel-offcanvas-wrapper <?php echo esc_attr($settings['offcanvas_layout']); ?>">
			<div class="eel-offcanvas-toggle" data-target="#<?php echo esc_attr($unique_id); ?>">
				<span class="eel-offcanvas-toggle-text">
					<?php if (!empty($btn_text)) : ?> 
						<em class="eel-icon-text"><?php echo esc_html($btn_text); ?></em>
					<?php endif; ?>

					<?php if ($settings['offcanvas_layout'] === 'classic') : ?>
						<?php
						if (!empty($settings['btn_icon']['value'])) {
							\Elementor\Icons_Manager::render_icon($settings['btn_icon'], ['aria-hidden' => 'true']);
						} else { ?>
							<div class="eel-icon-menu-wrap">
								<em class="eel-icon-menu"></em>
								<em class="eel-icon-menu"></em>
							</div>
						<?php } ?>
					<?php else : ?>
						<label>						
							<span></span><span></span><span></span>
						</label>
					<?php endif; ?>
				</span>
			</div>			

			<?php
			if (!\Elementor\Plugin::$instance->editor->is_edit_mode()) {
				add_action('wp_footer', function() use ($settings, $unique_id, $blur) { ?>
					<div id="<?php echo esc_attr($unique_id); ?>" class="eel-offcanvas <?php echo esc_attr($blur . ' ' . $settings['position_offcanvas'] . ' ' . $settings['offcanvas_layout']); ?>">
						<div class="eel-offcanvas-overlay"></div>
						<div class="eel-offcanvas-panel">
							<?php if ($settings['offcanvas_layout'] === 'classic') { ?>
								<span class="eel-offcanvas-close eel-offcanvas-toggle" data-target="#<?php echo esc_attr($unique_id); ?>">
									<?php
									if (!empty($settings['close_icon']['value'])) {
										\Elementor\Icons_Manager::render_icon($settings['close_icon'], ['aria-hidden' => 'true']);
									} else {
										echo '<i class="unicon-close"></i>';
									}
									?>
								</span>
							<?php } else { ?>
								<span class="eel-offcanvas-close eel-offcanvas-toggle eel-modern-close" data-target="#<?php echo esc_attr($unique_id); ?>">
									<label class="eel-modern-close-toggle">						
										<span></span><span></span><span></span>
									</label>
								</span>
							<?php } ?>

							<div class="eel-offcanvas-content">
								<?php
								if (!empty($settings['content_template'])) {
									// phpcs:ignore WordPress.Security.EscapeOutput
									echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($settings['content_template']);
								}
								?>
							</div>
						</div>
					</div>
				<?php });
			}
			?>
		</div>
		<?php
	}
}