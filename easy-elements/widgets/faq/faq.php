<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_FAQ_Accordion_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-faq-accordion';
	}

	public function get_title() {
		return esc_html__( 'Accordion', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-faq-1';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
		return [ 'faq', 'accordion', 'question', 'answer', 'text' ];
	}

	public function get_style_depends() {
        return [
            'eel-faq-accordion',
        ];
    }

	public function get_script_depends() {
        return [
            'eel-faq-accordion',
        ];
    }


	protected function register_controls() {
		$this->start_controls_section(
			'section_faq_content',
			[ 'label' => esc_html__( 'FAQ Items', 'easy-elements' ) ]
		);

		$repeater = new Repeater();

		$repeater->add_control( 'title', [
			'label' => esc_html__( 'Question', 'easy-elements' ),
			'type' => Controls_Manager::TEXT,
			'label_block' => true,
			'default' => esc_html__( 'What is Easy Elements?', 'easy-elements' ),
		] );

		$repeater->add_control( 'description', [
			'label' => esc_html__( 'Answer', 'easy-elements' ),
			'type' => Controls_Manager::TEXTAREA,
			'default' => esc_html__( 'Easy Elements is a best plugin for Elementor.', 'easy-elements' ),
		] );

		$repeater->add_control( 'active_toggle', [
			'label' => esc_html__( 'Active by Default', 'easy-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			'label_off' => esc_html__( 'No', 'easy-elements' ),
			'return_value' => 'yes',
			'default' => 'no',
		] );

		$this->add_control( 'faq_items', [
		    'label' => esc_html__( 'FAQ List', 'easy-elements' ),
		    'type' => Controls_Manager::REPEATER,
		    'fields' => $repeater->get_controls(),
		    'default' => [
		        [
		            'title'       => esc_html__( 'What is Easy Elements?', 'easy-elements' ),
		            'description' => esc_html__( 'Easy Elements is a custom Elementor addon plugin that offers useful widgets.', 'easy-elements' ),
		        ],
		        [
		            'title'       => esc_html__( 'Does it work with Elementor Free?', 'easy-elements' ),
		            'description' => esc_html__( 'Yes, it fully supports Elementor Free version without requiring Elementor Pro.', 'easy-elements' ),
		        ],
		        [
		            'title'       => esc_html__( 'How to install Easy Elements?', 'easy-elements' ),
		            'description' => esc_html__( 'Upload the plugin via WordPress Dashboard or FTP and activate it.', 'easy-elements' ),
		        ],
		        [
		            'title'       => esc_html__( 'What widgets are included?', 'easy-elements' ),
		            'description' => esc_html__( 'Widgets include logo grid, testimonials, CTA sections, pricing tables, and more.', 'easy-elements' ),
		        ],
		        [
		            'title'       => esc_html__( 'How do I get support or updates?', 'easy-elements' ),
		            'description' => esc_html__( 'Support and updates are available via the official website or marketplace.', 'easy-elements' ),
		        ],
		    ],
		    'title_field' => '{{{ title }}}',
		] );

		$this->add_control( 'title_tag', [
			'label' => esc_html__( 'Title HTML Tag', 'easy-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'h4',
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
		] );
		
		$this->add_control( 'icon_open', [
			'label' => esc_html__( 'Open Icon', 'easy-elements' ),
			'type' => Controls_Manager::ICONS,
			'default' => [ '' ],
		] );

		$this->add_control( 'icon_close', [
			'label' => esc_html__( 'Close Icon', 'easy-elements' ),
			'type' => Controls_Manager::ICONS,
			'default' => [ '' ],
		] );

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Position', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'row-reverse' => [
						'title' => esc_html__( 'Left', 'easy-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'row' => [
						'title' => esc_html__( 'Right', 'easy-elements' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'row',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .eel-faq-question' => 'flex-direction: {{VALUE}}; justify-content: left;',
				],
			]
		);

		$this->add_control( 'open_all_toggle', [
			'label' => esc_html__( 'Open All FAQs by Default', 'easy-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			'label_off' => esc_html__( 'No', 'easy-elements' ),
			'return_value' => 'yes',
			'default' => 'no',
		] );

		$this->add_control( 'enable_sticky', [
			'label' => esc_html__( 'Enable Sticky', 'easy-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			'label_off' => esc_html__( 'No', 'easy-elements' ),
			'return_value' => 'yes',
			'default' => 'no',
			'condition' => [
				'open_all_toggle' => 'yes',
			],
		] );

		$this->add_control( 'enable_faq_schema', [
			'label' => esc_html__( 'Enable FAQ Schema', 'easy-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			'label_off' => esc_html__( 'No', 'easy-elements' ),
			'return_value' => 'yes',
			'default' => 'no',
		] );

		$this->end_controls_section();

		$this->start_controls_section(
			'item_box_style_section',
			[
				'label' => esc_html__( 'Items', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);		

		$this->start_controls_tabs( 'item_style_tabs' );

		$this->start_controls_tab(
			'item_style_normal',
			[
				'label' => esc_html__( 'Normal', 'easy-elements' ),
			]
		);

		$this->add_group_control(
		    Group_Control_Background::get_type(),
		    [
		        'name' => 'faq_background',
		        'label' => esc_html__( 'Background Type', 'easy-elements' ),
		        'types' => [ 'classic', 'gradient' ],
		        'selector' => '{{WRAPPER}} .eel-faq-item',
		    ]
		);

		// Border Radius
		$this->add_responsive_control( 'item_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .eel-faq-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		// Border
		$this->add_group_control( \Elementor\Group_Control_Border::get_type(), [
			'name'     => 'item_border',
			'label'    => esc_html__( 'Border', 'easy-elements' ),
			'selector' => '{{WRAPPER}} .eel-faq-item',
		] );		

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__('Padding', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .eel-faq-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Shadow
		$this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [
			'name'     => 'item_shadow',
			'label'    => esc_html__( 'Box Shadow', 'easy-elements' ),
			'selector' => '{{WRAPPER}} .eel-faq-item',
		] );

		$this->add_responsive_control(
			'item_margin',
			[
				'label' => esc_html__('Items Space', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .eel-faq-accordion' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);	
		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_style_hover',
			[
				'label' => esc_html__( 'Hover', 'easy-elements' ),
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'item_bg_hover',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-faq-item:hover',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'item_border_hover',
				'selector' => '{{WRAPPER}} .eel-faq-item:hover',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_shadow_hover',
				'selector' => '{{WRAPPER}} .eel-faq-item:hover',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_style_active',
			[
				'label' => esc_html__( 'Active', 'easy-elements' ),
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'item_bg_active',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-faq-item.active',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'item_border_active',
				'selector' => '{{WRAPPER}} .eel-faq-item.active',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_shadow_active',
				'selector' => '{{WRAPPER}} .eel-faq-item.active',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style_section',
			[
				'label' => esc_html__( 'Title', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'easy-elements' ),
			'selector' => '{{WRAPPER}} .eel-faq-title',
		] );

		$this->start_controls_tabs( 'title_style_tabs' );

		$this->start_controls_tab(
			'title_style_normal',
			[
				'label' => esc_html__( 'Normal', 'easy-elements' ),
			]
		);

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'title_bg_color', [
			'label'     => esc_html__( 'Background Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-question' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'title_border',
				'selector' => '{{WRAPPER}} .eel-faq-question',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'title_shadow',
				'selector' => '{{WRAPPER}} .eel-faq-question',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_style_hover',
			[
				'label' => esc_html__( 'Hover', 'easy-elements' ),
			]
		);

		$this->add_control( 'title_color_hover', [
			'label'     => esc_html__( 'Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item:hover .eel-faq-title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'title_bg_color_hover', [
			'label'     => esc_html__( 'Background Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item:hover .eel-faq-question' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'title_border_color_hover', [
			'label'     => esc_html__( 'Border Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item:hover .eel-faq-question' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'title_shadow_hover',
				'selector' => '{{WRAPPER}} .eel-faq-item:hover .eel-faq-question',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_style_active',
			[
				'label' => esc_html__( 'Active', 'easy-elements' ),
			]
		);

		$this->add_control( 'title_color_active', [
			'label'     => esc_html__( 'Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item.active .eel-faq-title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'title_bg_color_active', [
			'label'     => esc_html__( 'Background Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item.active .eel-faq-question' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'title_border_color_active', [
			'label'     => esc_html__( 'Border Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item.active .eel-faq-question' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'title_shadow_active',
				'selector' => '{{WRAPPER}} .eel-faq-item.active .eel-faq-question',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'title_style_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control( 'title_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .eel-faq-question' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'title_padding', [
			'label'      => esc_html__( 'Padding', 'easy-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .eel-faq-question' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		// ===== Description Style =====
		$this->start_controls_section(
			'description_style_section',
			[
				'label' => esc_html__( 'Description', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
			'name'     => 'description_typography',
			'label'    => esc_html__( 'Typography', 'easy-elements' ),
			'selector' => '{{WRAPPER}} .eel-faq-answer',
		] );

		$this->start_controls_tabs( 'description_style_tabs' );

		$this->start_controls_tab(
			'description_style_normal',
			[
				'label' => esc_html__( 'Normal', 'easy-elements' ),
			]
		);

		$this->add_control( 'description_color', [
			'label'     => esc_html__( 'Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-answer' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'description_bg_color', [
			'label'     => esc_html__( 'Background Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-answer' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'description_border',
				'selector' => '{{WRAPPER}} .eel-faq-answer',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'description_style_hover',
			[
				'label' => esc_html__( 'Hover', 'easy-elements' ),
			]
		);

		$this->add_control( 'description_color_hover', [
			'label'     => esc_html__( 'Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item:hover .eel-faq-answer' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'description_bg_color_hover', [
			'label'     => esc_html__( 'Background Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item:hover .eel-faq-answer' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'description_border_color_hover', [
			'label'     => esc_html__( 'Border Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item:hover .eel-faq-answer' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'description_style_active',
			[
				'label' => esc_html__( 'Active', 'easy-elements' ),
			]
		);

		$this->add_control( 'description_color_active', [
			'label'     => esc_html__( 'Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item.active .eel-faq-answer' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'description_bg_color_active', [
			'label'     => esc_html__( 'Background Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item.active .eel-faq-answer' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'description_border_color_active', [
			'label'     => esc_html__( 'Border Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item.active .eel-faq-answer' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'description_style_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control( 'description_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .eel-faq-answer' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'description_padding', [
			'label'      => esc_html__( 'Padding', 'easy-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .eel-faq-answer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => esc_html__( 'Accordion Icon', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'icon_style_tabs' );

		$this->start_controls_tab(
			'icon_style_normal',
			[
				'label' => esc_html__( 'Normal', 'easy-elements' ),
			]
		);

		$this->add_control( 'icon_color', [
			'label'     => esc_html__( 'Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-icon, {{WRAPPER}} .eel-faq-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
			],
		] );

		$this->add_control( 
			'icon_bg_color', 
			[
				'label'     => esc_html__( 'Background', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-faq-icon' => 'background: {{VALUE}};',
				],
			] 
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eel-faq-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_box_size',
			[
				'label' => esc_html__( 'Box Size', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
            '{{WRAPPER}} .eel-faq-icon' => implode(
               '',
						[
							'min-width: {{SIZE}}{{UNIT}};',
							'min-height: {{SIZE}}{{UNIT}};',
							'width: {{SIZE}}{{UNIT}};',
							'height: {{SIZE}}{{UNIT}};',
							'line-height: {{SIZE}}{{UNIT}};',
						]
					),
				]
			]
		);

		$this->add_responsive_control(
			'icon_position_y',
			[
				'label' => esc_html__( 'Vertical Position', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .eel-faq-icon' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_group_control( \Elementor\Group_Control_Border::get_type(), [
			'name'     => 'icon_border',
			'label'    => esc_html__( 'Border', 'easy-elements' ),
			'selector' => '{{WRAPPER}} .eel-faq-icon',
		] );	

		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => esc_html__('Border Radius', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .eel-faq-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_style_hover',
			[
				'label' => esc_html__( 'Hover', 'easy-elements' ),
			]
		);
		$this->add_control( 'icon_color_hover', [
			'label'     => esc_html__( 'Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item:hover .eel-faq-icon, {{WRAPPER}} .eel-faq-item:hover .eel-faq-icon svg path' => 'color: {{VALUE}}; fill: {{VALUE}};',
			],
		] );

		$this->add_control( 
			'icon_hover_bg_color', 
			[
				'label'     => esc_html__( 'Background', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-faq-item:hover .eel-faq-icon' => 'background: {{VALUE}};',
				],
			] 
		);

		$this->add_group_control( \Elementor\Group_Control_Border::get_type(), [
			'name'     => 'icon_hover_border',
			'label'    => esc_html__( 'Border', 'easy-elements' ),
			'selector' => '{{WRAPPER}} .eel-faq-item:hover .eel-faq-icon',
		] );	
		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_style_active',
			[
				'label' => esc_html__( 'Active', 'easy-elements' ),
			]
		);

		$this->add_control( 'icon_color_active', [
			'label'     => esc_html__( 'Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-item.active .eel-faq-icon, {{WRAPPER}} .eel-faq-item.active .eel-faq-icon svg path' => 'color: {{VALUE}}; fill: {{VALUE}};',
			],
		] );

		$this->add_control( 
			'icon_active_bg_color', 
			[
				'label'     => esc_html__( 'Background', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-faq-item.active .eel-faq-icon' => 'background: {{VALUE}};',
				],
			] 
		);

		$this->add_group_control( \Elementor\Group_Control_Border::get_type(), [
			'name'     => 'icon_active_border',
			'label'    => esc_html__( 'Border', 'easy-elements' ),
			'selector' => '{{WRAPPER}} .eel-faq-item.active .eel-faq-icon',
		] );	

		$this->add_responsive_control(
			'icon_position_y_active',
			[
				'label' => esc_html__( 'Vertical Position', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .eel-faq-item.active .eel-faq-icon' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}


	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( empty( $settings['faq_items'] ) ) return;

		$title_tag = tag_escape( $settings['title_tag'] ?? 'h4' );
		$open_all_class = ( $settings['open_all_toggle'] === 'yes' ) ? 'eel-faq-open-all' : '';
		$enable_sticky = ( $settings['enable_sticky'] === 'yes' ) ? 'eel-faq-sticky' : '';
		

		?>

		<div class="eel-faq-accordion <?php echo esc_attr( $open_all_class ); ?> <?php echo esc_attr( $enable_sticky ); ?>" id="<?php echo esc_attr( $this->get_id() ); ?>">
			<?php foreach ( $settings['faq_items'] as $index => $item ) :
				$is_active = ( ! empty( $item['active_toggle'] ) && $item['active_toggle'] === 'yes' ) ? 'active' : '';
				$title = isset( $item['title'] ) ? $item['title'] : '';
				$description = isset( $item['description'] ) ? $item['description'] : '';
				$icon_open = isset( $settings['icon_open'] ) ? $settings['icon_open'] : array();
				$icon_close = isset( $settings['icon_close'] ) ? $settings['icon_close'] : array();
				?>
				<div class="eel-faq-item <?php echo esc_attr( $is_active ); ?>">
					<div class="eel-faq-question">
						<<?php echo esc_html( $title_tag ); ?> class="eel-faq-title" tabindex="0">
							<?php echo esc_html( $title ); ?>
						</<?php echo esc_html( $title_tag ); ?>>
						<span class="eel-faq-icon eel-faq-icon-open">
							<?php
								if ( ! empty( $icon_open ) && ! empty( $icon_open['value'] ) ) {
									Icons_Manager::render_icon( $icon_open, [ 'aria-hidden' => 'true' ] );
								} else {
									echo '<i class="unicon-close" aria-hidden="true"></i>';
								}
							?>
						</span>
						<span class="eel-faq-icon eel-faq-icon-close">
							<?php
							if ( ! empty( $icon_close ) && ! empty( $icon_close['value'] ) ) {
								Icons_Manager::render_icon( $icon_close, [ 'aria-hidden' => 'true' ] );
							} else {
								echo '<i class="unicon-add" aria-hidden="true"></i>';
							}
							?>
						</span>
					</div>
					<div class="eel-faq-answer">
						<?php echo wp_kses_post( $description ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}