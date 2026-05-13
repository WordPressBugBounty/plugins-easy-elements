<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

class Easyel_Count_Down_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-countdown';
	}

	public function get_title() {
		return __( 'Countdown', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-countdown';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
      return [ 'Countdown', 'timer', 'schedule', 'time', 'day', 'hour', 'minute', 'second' ];
	}

	public function get_style_depends() {
        return [
            'eel-countdown',
        ];
    }

	public function get_script_depends() {
        return [
            'eel-countdown',
        ];
    }

	protected function register_controls() {

		$this->start_controls_section(
			 'countdown_settings',
			 [
				  'label' => esc_html__('Countdown', 'easy-elements'),
			 ]
		);

		$this->add_control(
			'day_label',
			[
				'label' => esc_html__( 'Days', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Days', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your days here', 'easy-elements' ),
			]
		); 

		$this->add_control(
			'hours_label',
			[
				'label' => esc_html__( 'Hours', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Hours', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your hours here', 'easy-elements' ),
			]
		);  

		$this->add_control(
			'minute_label',
			[
				'label' => esc_html__( 'Minute', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Minutes', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your minute here', 'easy-elements' ),
			]
		); 

		$this->add_control(
			'seconds_label',
			[
				'label' => esc_html__( 'Seconds', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Seconds', 'easy-elements' ),
				'placeholder' => esc_html__( 'Type your seconds here', 'easy-elements' ),
			]
		);

		$this->add_control(
			 'target_date',
			 [
				  'label' => esc_html__('Target Date', 'easy-elements'),
				  'type' => \Elementor\Controls_Manager::DATE_TIME,
				  'picker_options' => [
						'enableTime' => true,
						'time_24hr' => true,
				  ],
				  'default' => gmdate( 'Y-m-d H:i:s', strtotime( '+1 day', current_time( 'timestamp', 1 ) ) ),
			 ]
		);  

		$this->add_control(
			'cntdwn_separator',
			[
				'label' => esc_html__( 'Separator', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'eel-cntdwn-space',
				'options' => [
					'eel-cntdwn-space' => esc_html__( 'Space', 'easy-elements' ),
					'eel-cntdwn-bullets' => esc_html__( 'bullets', 'easy-elements' ),
					'eel-cntdwn-dash'    => esc_html__( 'Dash', 'easy-elements' ),
				],
			]
		);

		$this->add_control(
			'label_down_shown',
			[
				'label' => esc_html__( 'Display Label Under Number', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'easy-elements' ),
				'label_off' => esc_html__( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => 'no',
				'selectors_dictionary' => [
					'yes' => 'block',
					'no'  => 'inline-block',
				],
				'selectors' => [
						'{{WRAPPER}} .eel-cntdwn-item span' => 'display: {{VALUE}};',
				],
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
					'{{WRAPPER}} .eel-cntdwn-item span' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'label_down_shown' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'countdown_style',
			[
				 'label' => esc_html__('Countdown', 'easy-elements'),
				 'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
	  	); 

		$this->add_responsive_control(
			'mid_gap',
			[
				'label' => esc_html__( 'Mid Gap', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-cntdwn' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'separator_positon_x',
			[
				'label' => esc_html__( 'Separator Position (Horizontal)', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['%','px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'size' => '', 
					'unit' => '%', 
				],
				'selectors' => [
					'{{WRAPPER}} .eel-cntdwn-bullets::before, {{WRAPPER}} .eel-cntdwn-bullets::after' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-cntdwn-dash::before' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'cntdwn_separator' => ['eel-cntdwn-bullets','eel-cntdwn-dash']
				]
			]
		);

	 	$this->add_control(
			'separator_color',
			[
				 'label' => esc_html__('Separator Color', 'easy-elements'),
				 'type' => \Elementor\Controls_Manager::COLOR,
				 'selectors' => [
					'{{WRAPPER}} .eel-cntdwn-bullets::before, {{WRAPPER}} .eel-cntdwn-bullets::after' =>  'background: {{VALUE}};',
					'{{WRAPPER}} .eel-cntdwn-dash::before' => 'background: {{VALUE}};',
				 ],
				'condition' => [
					'cntdwn_separator' => ['eel-cntdwn-bullets','eel-cntdwn-dash']
				]
			]
	  	); 

		$this->add_control(
			'item_bgcolor',
			[
				'label' => esc_html__('Background', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cntdwn-item' => 'background: {{VALUE}};',
				],
			]
		);	

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .eel-cntdwn-item',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .eel-cntdwn-item',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-cntdwn-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-cntdwn-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Days Style
		$this->start_controls_section(
			'days_style',
			[
				 'label' => esc_html__('Days', 'easy-elements'),
				 'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
	  	); 

	  	$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				 'name' => 'days_typography',
				 'label' => esc_html__('Typography', 'easy-elements'),
				 'selector' => '{{WRAPPER}} .eel-cntdwn-days',
			]
	  	); 

	 	$this->add_control(
			'days_color',
			[
				 'label' => esc_html__('Color', 'easy-elements'),
				 'type' => \Elementor\Controls_Manager::COLOR,
				 'selectors' => [
					  '{{WRAPPER}} .eel-cntdwn-days' => 'color: {{VALUE}};',
				 ],
			]
	  ); 
	
		$this->add_control(
			'days_label_color',
			[
				'label' => esc_html__('Label Color', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cntdwn-days-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'days_label_typography',
				'label' => esc_html__('Typography', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-cntdwn-days-label',
			]
		);
		$this->end_controls_section();
	
		// Hours Style
		$this->start_controls_section(
			'hours_style',
			[
				'label' => esc_html__('Hours', 'easy-elements'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'hours_typography',
				'label' => esc_html__('Typography', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-cntdwn-hours',
			]
		);
		
		$this->add_control(
			'hours_color',
			[
					'label' => esc_html__('Color', 'easy-elements'),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .eel-cntdwn-hours' => 'color: {{VALUE}} !important;',
					],
			]
		);
	
		$this->add_control(
			'hours_label_color',
			[
				'label' => esc_html__('Label Color', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cntdwn-hours-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'hours_label_typography',
				'label' => esc_html__('Typography', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-cntdwn-hours-label',
			]
		);
		$this->end_controls_section();
	
		// Minutes Style
		$this->start_controls_section(
			'minutes_style',
			[
				'label' => esc_html__('Minute', 'easy-elements'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'minutes_typography',
				'label' => esc_html__('Typography', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-cntdwn-minutes',
			]
		); 
		
		$this->add_control(
			'minutes_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cntdwn-minutes' => 'color: {{VALUE}};',
				],
			]
		);
	
		$this->add_control(
			'minutes_label_color',
			[
				'label' => esc_html__('Label Color', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cntdwn-minutes-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'minutes_label_typography',
				'label' => esc_html__('Typography', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-cntdwn-minutes-label',
			]
		);
		$this->end_controls_section();
 
	  // Seconds Style
	  $this->start_controls_section(
			'seconds_style',
			[
				 'label' => esc_html__('Seconds', 'easy-elements'),
				 'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
	  ); 

	  $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				 'name' => 'seconds_typography',
				 'label' => esc_html__('Typography', 'easy-elements'),
				 'selector' => '{{WRAPPER}} .eel-cntdwn-seconds',
			]
	  ); 

	  $this->add_control(
			'seconds_color',
			[
				'label' => esc_html__('Color', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cntdwn-seconds' => 'color: {{VALUE}};',
				],
			]
		); 
	
		$this->add_control(
			'seconds_label_color',
			[
				'label' => esc_html__('Label Color', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-cntdwn-seconds-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'seconds_label_typography',
				'label' => esc_html__('Typography', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-cntdwn-seconds-label',
			]
		);		
	  $this->end_controls_section();
  	}
  
	protected function render() {
		
		$settings = $this->get_settings_for_display();

		$target_date = !empty($settings['target_date']) 
			? gmdate( 'Y-m-d H:i:s', strtotime( $settings['target_date'] ) )
			: gmdate( 'Y-m-d H:i:s', strtotime( '+1 day', current_time( 'timestamp', 1 ) ) );

		// labels
		$day_label     = !empty($settings['day_label']) ? $settings['day_label'] : 'Days';
		$hours_label   = !empty($settings['hours_label']) ? $settings['hours_label'] : 'Hours';
		$minute_label  = !empty($settings['minute_label']) ? $settings['minute_label'] : 'Minutes';
		$seconds_label = !empty($settings['seconds_label']) ? $settings['seconds_label'] : 'Seconds';

		$separator	= !empty($settings['cntdwn_separator']) ? $settings['cntdwn_separator'] : 'default';
    ?>
    	<div id="eel-countdowns" data-target="<?php echo esc_attr($target_date); ?>">
        <div class="eel-cntdwn">
            <div class="eel-cntdwn-item <?php echo esc_attr($separator); ?>"><span class="eel-cntdwn-days"></span> <span class="eel-cntdwn-days-label"><?php echo wp_kses_post($day_label); ?></span></div>
            <div class="eel-cntdwn-item <?php echo esc_attr($separator); ?>"><span class="eel-cntdwn-hours"></span> <span class="eel-cntdwn-hours-label"><?php echo wp_kses_post($hours_label); ?></span></div>
            <div class="eel-cntdwn-item <?php echo esc_attr($separator); ?>"><span class="eel-cntdwn-minutes"></span> <span class="eel-cntdwn-minutes-label"><?php echo wp_kses_post($minute_label); ?></span></div>
            <div class="eel-cntdwn-item <?php echo esc_attr($separator); ?>"><span class="eel-cntdwn-seconds"></span> <span class="eel-cntdwn-seconds-label"><?php echo wp_kses_post($seconds_label); ?></span></div>
        </div>
    	</div>
   <?php
	}
}