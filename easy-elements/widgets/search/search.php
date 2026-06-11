<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Search_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-search';
	}

	public function get_title() {
		return __( 'Search', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-search';
	}

	public function get_categories() {
		return [ 'easyelements_header_footer_category' ];
	}

	public function get_keywords() {
        return [ 'search', 'input', 'field', 'click', 'text' ];
    }

	public function get_style_depends() {
        return [
            'eel-search',
        ];
    }

	public function get_script_depends() {
        return [
            'eel-search',
        ];
    }


	protected function register_controls() {

		/* -------------------------------------------------------------------
		 * CONTENT TAB — Search Settings
		 * ----------------------------------------------------------------- */
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Search Settings', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'select_style',
			[
				'label'   => esc_html__( 'Search Skin', 'easy-elements' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Search Popup', 'easy-elements' ),
					'2' => esc_html__( 'Search Fields', 'easy-elements' ),
				],
			]
		);

		$this->add_control(
			'search_top_title',
			[
				'label'     => esc_html__( 'Search Title', 'easy-elements' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => [
					'select_style' => '1',
				],
			]
		);

		$this->add_control(
			'search_placeholder',
			[
				'label'   => esc_html__( 'Search Placeholder', 'easy-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Type keywords here...', 'easy-elements' ),
			]
		);

		$this->add_control(
			'open_icon',
			[
				'label'   => esc_html__( 'Search Icon', 'easy-elements' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-search',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'close_icon',
			[
				'label'     => esc_html__( 'Close Icon', 'easy-elements' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-arrow-up',
					'library' => 'fa-solid',
				],
				'condition' => [
					'select_style' => '1',
				],
			]
		);

		$this->end_controls_section();


		/* -------------------------------------------------------------------
		 * STYLE TAB — Search Icon
		 * ----------------------------------------------------------------- */
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Search Icon', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'search_icon_size',
			[
				'label'     => esc_html__( 'Icon Size (px)', 'easy-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-search-open-btn i, {{WRAPPER}} .eel-search-open-btn svg'     => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-search-submit-btn i, {{WRAPPER}} .eel-search-submit-btn svg' => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_colors',
			[
				'label'     => esc_html__( 'Icon Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-search-open-btn i'                                       => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-search-open-btn svg, {{WRAPPER}} .eel-search-open-btn svg path' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-search-style-2 .eel-search-submit-btn svg'               => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-search-style-2 .eel-search-submit-btn svg path'          => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_icon_vertical_position',
			[
				'label'     => esc_html__( 'Icon Vertical Position (px)', 'easy-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => -50,
						'max' => 50,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .eel-search-open-btn' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'select_style' => '1',
				],
			]
		);

		$this->add_control(
			'search_icon_position_side',
			[
				'label'                => esc_html__( 'Icon Position', 'easy-elements' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'left'  => [
						'title' => esc_html__( 'Left', 'easy-elements' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'easy-elements' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'              => 'right',
				'toggle'               => false,
				// Anchor the icon to the chosen side immediately (resetting the
				// opposite side) so picking "Left" moves it without needing an
				// offset value. The offset sliders below only fine-tune it.
				'selectors_dictionary' => [
					'left'  => 'right: auto; left: 0;',
					'right' => 'left: auto;',
				],
				'selectors'            => [
					'{{WRAPPER}} .eel-search-style-2 .eel-search-submit-btn' => '{{VALUE}}',
				],
				'condition'            => [
					'select_style' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'search_icon_horizontal_position',
			[
				'label'      => esc_html__( 'Offset From Right', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => -50,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-search-style-2 .eel-search-submit-btn' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				],
				'condition'  => [
					'select_style'              => '2',
					'search_icon_position_side' => 'right',
				],
			]
		);

		$this->add_responsive_control(
			'search_icon_horizontal_position_left',
			[
				'label'      => esc_html__( 'Offset From Left', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => -50,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-search-style-2 .eel-search-submit-btn' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				],
				'condition'  => [
					'select_style'              => '2',
					'search_icon_position_side' => 'left',
				],
			]
		);

		$this->end_controls_section();


		/* -------------------------------------------------------------------
		 * STYLE TAB — Input Field
		 * ----------------------------------------------------------------- */
		$this->start_controls_section(
			'section_input_style',
			[
				'label' => esc_html__( 'Input Field', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'input_typography',
				'label'    => esc_html__( 'Typography', 'easy-elements' ),
				'selector' => '{{WRAPPER}} .eel-search-content .eel-search-field, {{WRAPPER}} .eel-search-style-2 .eel-search-field',
			]
		);

		$this->add_control(
			'input_text_color',
			[
				'label'     => esc_html__( 'Input Text Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-search-content .eel-search-field' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-search-style-2 .eel-search-field' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label'     => esc_html__( 'Placeholder Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-search-content .eel-search-field::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-search-style-2 .eel-search-field::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_bg_color',
			[
				'label'     => esc_html__( 'Input Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-search-content .eel-search-field' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eel-search-style-2 .eel-search-field' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label'      => esc_html__( 'Padding', 'easy-elements' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .eel-search-content .eel-search-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eel-search-style-2 .eel-search-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_height',
			[
				'label'      => esc_html__( 'Height', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-search-content .eel-search-field' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-search-style-2 .eel-search-field' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_input_field_width',
			[
				'label'      => esc_html__( 'Input Field Width', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-search-style-2 .eel-search-field' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'select_style' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'input_field_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .eel-search-style-2 .eel-search-field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'select_style' => '2',
				],
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label'     => esc_html__( 'Input Border Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-search-style-2 .eel-search-field' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'select_style' => '2',
				],
			]
		);

		$this->add_control(
			'input_focus_border_color',
			[
				'label'     => esc_html__( 'Border Color (Focus)', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-search-style-2 .eel-search-field:focus' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'select_style' => '2',
				],
			]
		);

		$this->end_controls_section();


		/* -------------------------------------------------------------------
		 * STYLE TAB — Submit Button
		 * ----------------------------------------------------------------- */
		$this->start_controls_section(
			'section_submit_style',
			[
				'label' => esc_html__( 'Submit Button', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'submit_btn_icon_color',
			[
				'label'     => esc_html__( 'Submit Icon Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-search-content .eel-search-submit'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-search-content .eel-search-submit svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'select_style' => '1',
				],
			]
		);

		$this->add_control(
			'submit_btn_icon_h_color',
			[
				'label'     => esc_html__( 'Submit Icon Hover Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-search-content .eel-search-submit:hover'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-search-content .eel-search-submit:hover svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'select_style' => '1',
				],
			]
		);

		$this->add_control(
			'submit_button_color',
			[
				'label'     => esc_html__( 'Submit Icon Background', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-search-content .eel-search-submit'      => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eel-search-style-2 .eel-search-submit-btn' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'select_style' => [ '1', '2' ],
				],
			]
		);

		$this->add_control(
			'submit_button_hover_bgcolor',
			[
				'label'     => esc_html__( 'Submit Icon Hover Background', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-search-content .eel-search-submit:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'select_style' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'submit_button_padding',
			[
				'label'      => esc_html__( 'Padding', 'easy-elements' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .eel-search-content .eel-search-submit'     => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eel-search-style-2 .eel-search-submit-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		/* -------------------------------------------------------------------
		 * STYLE TAB — Search Popup (Skin: Search Popup only)
		 * ----------------------------------------------------------------- */
		$this->start_controls_section(
			'section_popup_style',
			[
				'label'     => esc_html__( 'Search Popup', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'select_style' => '1',
				],
			]
		);

		$this->add_control(
			'overlay_background',
			[
				'label'     => esc_html__( 'Overlay Background', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .eel-search-lightbox, {{WRAPPER}} .eel-search-overlay' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'popup_title_heading',
			[
				'label'     => esc_html__( 'Title', 'easy-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'popup_title_color',
			[
				'label'     => esc_html__( 'Title Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-search-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'popup_title_typography',
				'label'    => esc_html__( 'Title Typography', 'easy-elements' ),
				'selector' => '{{WRAPPER}} .eel-search-title',
			]
		);

		$this->add_control(
			'close_icon_heading',
			[
				'label'     => esc_html__( 'Close Icon', 'easy-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'close_icon_color',
			[
				'label'     => esc_html__( 'Close Icon Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-search-close-btn i'                                          => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-search-close-btn svg, {{WRAPPER}} .eel-search-close-btn svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'     => esc_html__( 'Close Icon Size (px)', 'easy-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-search-close-btn i, {{WRAPPER}} .eel-search-close-btn svg' => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$style	  = $settings['select_style'];

		if ( $style == '2' ) : ?>
			<div class="eel-search-style-<?php echo esc_attr( $style ); ?>">
				<form role="search" method="get" class="eel-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<input type="search" class="eel-search-field" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ?? 'Search..' ); ?>" value="" name="s" />
					<button type="submit" class="eel-search-submit-btn" aria-label="Submit Search">
						<?php
						if( $settings['open_icon']['value'] ) :
							\Elementor\Icons_Manager::render_icon( $settings['open_icon'], [ 'aria-hidden' => 'true' ] );
						else : ?>
							<i class="eel-absl unicon-search"></i>
							<?php
						endif; ?>
					</button>
				</form>
			</div>
	    	<?php
		else: ?>
			<a href="#" role="button" class="eel-search-open-btn" aria-label="<?php esc_attr_e('Open Search', 'easy-elements'); ?>">
				<?php \Elementor\Icons_Manager::render_icon( $settings['open_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			</a>

			<div class="eel-search-lightbox">
				<div class="eel-search-overlay">
						<a href="#" role="button" class="eel-search-close-btn" aria-label="<?php esc_attr_e('Close Search', 'easy-elements'); ?>">
							<?php \Elementor\Icons_Manager::render_icon( $settings['close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</a>
				</div>
				<div class="eel-search-content">
					<div class="eel-search-title">
						<?php echo esc_attr( $settings['search_top_title'] ?: __( 'What are you looking for?', 'easy-elements' ) ); ?>
					</div>
						<form role="search" method="get" class="eel-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<input type="search" class="eel-search-field" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ?? 'Type keywords here...' ); ?>" value="" name="s" />
							<button type="submit" class="eel-search-submit" aria-label="Submit Search">
								<i class="eel-absl unicon-search"></i>
							</button>
						</form>
				</div>
			</div>
			<?php
		endif;
	}
}
