<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;

defined('ABSPATH') || die();

class Easyel_Counter_Widget extends \Elementor\Widget_Base {
	

	public function get_name() {
		return 'eel-counter';
	}

	public function get_title() {
		return __( 'Counter', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-counter';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
      return [ 'counter', 'funfact', 'counterup', 'count', 'text' ];
	}

	public function get_style_depends() {
        return [
            'eel-counter',
        ];
    }

	public function get_script_depends() {
        return [
            'eel-counter',
        ];
    }

	protected function register_controls()
	{
		$this->start_controls_section(
			'section_counter',
			[
				'label' => esc_html__('Counter', 'easy-elements'),
			]
		);	

		$this->add_control(
			'number',
			[
				'label' => esc_html__('Ending Number', 'easy-elements'),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'dynamic' => [
					'active' => true, 
				],
			]
		);

		$this->add_control(
			'prefix',
			[
				'label' => esc_html__('Number Prefix', 'easy-elements'),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true, 
				],
			]
		);

		$this->add_control(
			'suffix',
			[
				'label' => esc_html__('Number Suffix', 'easy-elements'),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true, 
				],
			]
		);

		$this->add_control(
			'duration',
			[
				'label' => esc_html__('Animation Duration (ms)', 'easy-elements'),
				'type' => Controls_Manager::NUMBER,
				'default' => 1000,
				'placeholder' => 'Suffix',
			]
		);

		$this->add_control(
			'format',
			[
				'label' => __('Separator', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
						'default'   => __('Default', 'easy-elements'),
						'comma'   => __('Comma', 'easy-elements'),
						'dot'       => __('Dot', 'easy-elements'),
						'space'     => __('Space', 'easy-elements'),
						'underline' => __('Underline', 'easy-elements'),
				],
			]
		);

		$this->add_control(
			'cnt-icon-enable',
			[
				'label' => esc_html__( 'Icon Show', 'easy-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => 'no',	
			]
		);  

		$this->add_control(
			'cnt-icon',
			[
				'label' => esc_html__( 'Icon', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-layer-group',
					'library' => 'fa-solid',
				],
				'condition' => [
					'cnt-icon-enable' => 'yes',
				]
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__('Title', 'easy-elements'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Label', 'easy-elements'),
				'default' => esc_html__('Easy Elements', 'easy-elements'),
				'label_block' => 'true',
				'dynamic' => [
					'active' => true, 
				],
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__('Select Title Tag', 'easy-elements'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'span',
				'options' => [
					'h1' => esc_html__('H1', 'easy-elements'),
					'h2' => esc_html__('H2', 'easy-elements'),
					'h3' => esc_html__('H3', 'easy-elements'),
					'h4' => esc_html__('H4', 'easy-elements'),
					'h5' => esc_html__('H5', 'easy-elements'),
					'h6' => esc_html__('H6', 'easy-elements'),
					'p' => esc_html__('P', 'easy-elements'),
					'div' => esc_html__('div', 'easy-elements'),
					'span' => esc_html__('span', 'easy-elements'),
				],
			]
		);
		$this->end_controls_section();	
		
		$this->start_controls_section(
			'counter_styles',
			[
				'label' => esc_html__('Counter', 'easy-elements'),
				'tab' => Controls_Manager::TAB_STYLE,			
			]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label' => esc_html__( 'Content Alignment', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'easy-elements' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'easy-elements' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'easy-elements' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .eel-cnt-number-wrap' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label' => esc_html__( 'Content Gap', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-title' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sub_pre_gap',
			[
				'label' => esc_html__( 'Prefix Suffix Gap', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-number-wrap' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name'     => 'prefix',
							'operator' => '!=',
							'value'    => '',
						],
						[
							'name'     => 'suffix',
							'operator' => '!=',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'wrap_df_hrzn_align',
			[
				'label' => esc_html__( 'Horizontal Alignment', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'easy-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'easy-elements' ),
						'icon' => 'eicon-h-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'easy-elements' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-wrap' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'cnt-icon-enable!' => 'yes',
				]
			]
		);	

		$this->add_responsive_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'separator' => 'before',
				'default' => 'top',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'easy-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' => esc_html__( 'Top', 'easy-elements' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'easy-elements' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'easy-elements' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'toggle' => true,
				'prefix_class' => 'eel-cnt-icon-pos-',
				'condition' => [
					'cnt-icon-enable' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'wrap_vrt_lr_align',
			[
				'label' => esc_html__( 'Vertical Alignment', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Top', 'easy-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'easy-elements' ),
						'icon' => 'eicon-h-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Bottom', 'easy-elements' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-wrap' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'icon_position' => [ 'left', 'right' ],
					'cnt-icon-enable' => 'yes',
				],
			]
		);
				
		$this->add_responsive_control(
			'wrap_hrzn_tb_alig',
			[
				'label' => esc_html__( 'Horizontal Alignment', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'easy-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'easy-elements' ),
						'icon' => 'eicon-h-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'easy-elements' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-wrap' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'icon_position' => [ 'top', 'bottom' ],
					'cnt-icon-enable' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'wrap_hrzn_lr_align',
			[
				'label' => esc_html__( 'Horizontal Alignment', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'easy-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'easy-elements' ),
						'icon' => 'eicon-h-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'easy-elements' ),
						'icon' => 'eicon-h-align-right',
					],
					'space-between' => [
						'title' => esc_html__( 'Stretch', 'easy-elements' ),
						'icon' => 'eicon-grow',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-wrap' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'icon_position' => [ 'left', 'right' ],
					'cnt-icon-enable' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_gap',
			[
				'label' => esc_html__( 'Icon Gap', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-wrap' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'cnt-icon-enable' => 'yes',
				]
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_number',
			[
				'label' => esc_html__('Number', 'easy-elements'),
				'tab' => Controls_Manager::TAB_STYLE,				
				'condition' => [
					'number!' => '' 
				]
			]
		);

		$this->add_control(
			'number_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-number-wrap' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__('Typography', 'easy-elements'),
				'name' => 'number_typography',
				'selector' => '{{WRAPPER}} .eel-cnt-number-wrap',		
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'number_stroke',
				'selector' => '{{WRAPPER}} .eel-cnt-number-wrap',
			]
		);	

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'number_text_shadow',
				'label'    => esc_html__('Text Shadow', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-cnt-number-wrap',
			]
		);		
		$this->end_controls_section();	

		$this->start_controls_section(
			'prefix_styles',
			[
				'label' => esc_html__('Prefix', 'easy-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'prefix!' => '',
				]
			]
		);
		$this->add_control(
			'prefix_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-prefix' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__('Typography', 'easy-elements'),
				'name' => 'prefix_typography',
				'selector' => '{{WRAPPER}} .eel-cnt-prefix',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'prefix_text_shadow',
				'label'    => esc_html__('Text Shadow', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-cnt-prefix',
			]
		);		
		$this->end_controls_section();

		$this->start_controls_section(
			'suffix_styles',
			[
				'label' => esc_html__('Suffix', 'easy-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'suffix!' => '',
				]
			]
		);

		$this->add_control(
			'suffix_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-suffix' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__('Typography', 'easy-elements'),
				'name' => 'suffix_typography',
				'selector' => '{{WRAPPER}} .eel-cnt-suffix',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'suffix_text_shadow',
				'label'    => esc_html__('Text Shadow', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-cnt-suffix',
			]
		);	
		$this->end_controls_section();

		$this->start_controls_section(
			'title_styles',
			[
				'label' => esc_html__('Title', 'easy-elements'),
				'tab' => Controls_Manager::TAB_STYLE,				
				'condition' => [
					'title!' => '',
				]
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__('Typography', 'easy-elements'),
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .eel-cnt-title',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'title_text_shadow',
				'label'    => esc_html__('Text Shadow', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-cnt-title',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'icon_style',
			[
				'label' => esc_html__('Icon', 'easy-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'cnt-icon-enable' => 'yes',
				]
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-cnt-icon svg path' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-cnt-icon i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_bgcolor',
			[
				'label' => esc_html__('Background', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-icon' => 'background: {{VALUE}};',
				],	
			]
		);

		$this->add_responsive_control(
			'icon_box_size',
			[
				'label' => esc_html__( 'Size', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 0,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-icon' => 'width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 0,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-icon svg' => 'width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .eel-cnt-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

      $this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-cnt-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings   = $this->get_settings_for_display();
		$number     = !empty($settings['number']) ? $settings['number'] : 0;
		$prefix     = !empty($settings['prefix']) ? $settings['prefix'] : '';
		$suffix     = !empty($settings['suffix']) ? $settings['suffix'] : '';
		$title      = !empty($settings['title'])  ? $settings['title']  : '';
		$title_tag  = !empty($settings['title_tag']) ? $settings['title_tag'] : 'h3';

		$duration   = !empty($settings['duration']) ? intval($settings['duration']) : 1000;
		$format     = !empty($settings['format']) ? $settings['format'] : 'default';

		$icon_enabled = !empty($settings['cnt-icon-enable']) && $settings['cnt-icon-enable'] === 'yes';
		$icon         = $icon_enabled && !empty($settings['cnt-icon']['value']) ? $settings['cnt-icon'] : false;

		// Add inline editing
		$this->add_inline_editing_attributes('prefix', 'basic');
		$this->add_render_attribute('prefix', 'class', 'eel-cnt-prefix');

		$this->add_inline_editing_attributes('suffix', 'basic');
		$this->add_render_attribute('suffix', 'class', 'eel-cnt-suffix');
    ?>
    	<div class="eel-cnt-wrap">

        <?php if ($icon) : ?>
            <div class="eel-cnt-icon">
                <?php \Elementor\Icons_Manager::render_icon($icon, [ 'aria-hidden' => 'true' ]); ?>
            </div>
        <?php endif; ?>

        <div class="eel-cnt-content">

            <?php if ($number !== '') : ?>
                <div class="eel-cnt-number-wrap">

                    <?php if ($prefix !== '') : 
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe, output contains trusted HTML attributes. ?>
                        <span <?php echo $this->print_render_attribute_string('prefix'); ?>>
                            <?php echo esc_html($prefix); ?>
                        </span>
                    <?php endif; ?>

                    <span class="eel-cnt-number eel-counter" 
                          data-count="<?php echo esc_attr($number); ?>"
                          data-duration="<?php echo esc_attr($duration); ?>"
                          data-format="<?php echo esc_attr($format); ?>">
                        0
                    </span>

                    <?php if ($suffix !== '') : 
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe, output contains trusted HTML attributes. ?>
                        <span <?php echo $this->print_render_attribute_string('suffix'); ?>>
                            <?php echo esc_html($suffix); ?>
                        </span>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

            <?php if ($title !== '') : ?>
                <<?php echo esc_attr($title_tag); ?> class="eel-cnt-title">
                    <?php echo wp_kses_post($title); ?>
                </<?php echo esc_attr($title_tag); ?>>
            <?php endif; ?>

        </div>
    	</div>
   	<?php
	}	
}
