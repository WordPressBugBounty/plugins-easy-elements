<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Heading_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eel-heading';
	}

	public function get_title() {
		return __( 'Heading', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-heading';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}
	public function get_keywords() {
        return [ 'heading', 'title', 'link', 'click', 'text' ];
    }

	public function get_style_depends() {
        return [
            'eel-heading',
        ];
    }


	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__('Heading Settings', 'easy-elements'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__('Heading Text', 'easy-elements'),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__('Heading Here', 'easy-elements'),
				'label_block' => true,
				'description' => esc_html__('You can highlight part of the text using double curly brackets. Example: Heading {{Here}} will highlight "Here".', 'easy-elements'),
			]
		);

		$this->add_control(
		    'show_border_title',
		    [
		        'label' => esc_html__( 'Show Title Side Border', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SWITCHER,
		        'label_on' => esc_html__( 'On', 'easy-elements' ),
		        'label_off' => esc_html__( 'Off', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => '',
		        'description' => esc_html__( 'Adds a colored vertical bar on the left or right side of the title.', 'easy-elements' ),
		    ]
		);

		$this->add_control(
			'show_gradient_title',
			[
				'label' => esc_html__( 'Enable Gradient Text', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'easy-elements' ),
				'label_off' => esc_html__( 'Off', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => '',
				'description' => esc_html__( 'Fill the title text with a gradient. Configure colors in Style > Heading.', 'easy-elements' ),
			]
		);

		$this->add_control(
		    'border_position',
		    [
		        'label' => esc_html__( 'Border Position', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SELECT,
		        'default' => 'eel_title_start',
		        'options' => [
		            'eel_title_start'      => esc_html__( 'Left of Title (text width)', 'easy-elements' ),
		            'eel_title_end'        => esc_html__( 'Right of Title (text width)', 'easy-elements' ),
		            'eel_full_title_start' => esc_html__( 'Left of Title (full width)', 'easy-elements' ),
		            'eel_full_title_end'   => esc_html__( 'Right of Title (full width)', 'easy-elements' ),
		        ],
		        'description' => esc_html__( 'Choose which side the side border sits, and whether it spans only the text or the full container width.', 'easy-elements' ),
		        'condition' => [
		            'show_border_title' => 'yes',
		        ],
		    ]
		);


		$this->add_control(
			'border_color',
			[
				'label'     => esc_html__('Border Color', 'easy-elements'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel_full_title_end, {{WRAPPER}} .eel_title_end .e-e-title, {{WRAPPER}} .eel_full_title_start, {{WRAPPER}} .eel_title_start .e-e-title' => 'border-color: {{VALUE}};',
				],
				'condition' => [
				    'show_border_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
		    'title_border_padding',
		    [
		        'label' => esc_html__( 'Padding', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%', 'em' ],
		        'selectors' => [
		            '{{WRAPPER}} .eel_full_title_end, {{WRAPPER}} .eel_title_end .e-e-title, {{WRAPPER}} .eel_full_title_start, {{WRAPPER}} .eel_title_start .e-e-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		        'condition' => [
		            'show_border_title' => 'yes',
		        ],
		    ]
		);


		$this->add_control(
		    'link',
		    [
		        'label' => __( 'Link', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::URL,
		        'placeholder' => 'https://your-link.com',
		        'default' => [
		            'url' => '',
		            'is_external' => false,
		            'nofollow' => false,
		        ],
		        'show_external' => true,
		        'description' => esc_html__( 'Set a URL to make the heading clickable. Supports opening in a new tab and nofollow attribute.', 'easy-elements' ),
		    ]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__('HTML Tag', 'easy-elements'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'description' => esc_html__('Choose the HTML tag for SEO. Use H1 once per page only.', 'easy-elements'),
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
				],
			]
		);

		$this->add_control(
			'description',
			[
				'label' => esc_html__('Description', 'easy-elements'),
				'type'  => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'description' => esc_html__( 'Optional. A short paragraph displayed below the heading.', 'easy-elements' ),
			]
		);


		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__('Alignment', 'easy-elements'),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left' => [
						'title' => esc_html__('Left', 'easy-elements'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'easy-elements'),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'easy-elements'),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__('Justify', 'easy-elements'),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .e-e-heading' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .image-heading' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'sub_heading_section',
			[
				'label' => esc_html__('Sub Heading Settings', 'easy-elements'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
		    'sub_title',
		    [
		        'label' => esc_html__( 'Sub Heading', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::TEXTAREA,
		        'label_block' => true,
		        'description' => esc_html__( 'A small line of text above the main heading. Often used for category, tag, or eyebrow text.', 'easy-elements' ),
		    ]
		);

		$this->add_control(
		    'show_gradient_border',
		    [
		        'label' => esc_html__( 'Show Gradient Border', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SWITCHER,
		        'label_on' => esc_html__( 'Show', 'easy-elements' ),
		        'label_off' => esc_html__( 'Hide', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => '',
		    ]
		);

		$this->add_control(
			'gradient_color_1',
			[
				'label' => esc_html__( 'Gradient Color 1', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.15)',
				'selectors' => [
					'{{WRAPPER}} .easy_gradiant_border::before' => '--grad-color-1: {{VALUE}};',
				],
				'condition' => [
					'show_gradient_border' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'gradient_color_2',
			[
				'label' => esc_html__( 'Gradient Color 2', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.30)',
				'selectors' => [
					'{{WRAPPER}} .easy_gradiant_border::before' => '--grad-color-2: {{VALUE}};',
				],
				'condition' => [
					'show_gradient_border' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'gradient_color_3',
			[
				'label' => esc_html__( 'Gradient Color 3', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.50)',
				'selectors' => [
					'{{WRAPPER}} .easy_gradiant_border::before' => '--grad-color-3: {{VALUE}};',
				],
				'condition' => [
					'show_gradient_border' => 'yes',
				],
			]
		);
		

		$this->add_responsive_control(
		    'sub_gradient_border_padding',
		    [
		        'label' => __('Gradient Border Padding', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', '%', 'em'],
		        'selectors' => [
		            '{{WRAPPER}} .easy_gradiant_border' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
				'condition' => [
					'show_gradient_border' => 'yes',
				],
		    ]
		);

		$this->add_responsive_control(
			'gradient_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .easy_gradiant_border::before' => '--grad-border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_gradient_border' => 'yes',
				],
			]
		);
		

		$this->add_control(
		    'sub_heading_type',
		    [
		        'label' => esc_html__( 'Sub Heading Type', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::CHOOSE,
		        'options' => [
		            'none' => [
		                'title' => esc_html__( 'None', 'easy-elements' ),
		                'icon' => 'eicon-ban',
		            ],
		            'icon' => [
		                'title' => esc_html__( 'Icon', 'easy-elements' ),
		                'icon' => 'eicon-star',
		            ],
		            'image' => [
		                'title' => esc_html__( 'Image', 'easy-elements' ),
		                'icon' => 'eicon-image-bold',
		            ],
		        ],
		        'default' => 'none',
		        'toggle' => false,
		    ]
		);

		$this->add_control(
		    'sub_heading_icon',
		    [
		        'label' => esc_html__( 'Select Icon', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::ICONS,
		        'default' => [
		            'value' => 'fas fa-heart',
		            'library' => 'fa-solid',
		        ],
		        'condition' => [
		            'sub_heading_type' => 'icon',
		        ],
		    ]
		);

		$this->add_control(
		    'sub_heading_image',
		    [
		        'label' => esc_html__( 'Upload Image', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::MEDIA,
		        'default' => [
		            'url' => \Elementor\Utils::get_placeholder_image_src(),
		        ],
		        'condition' => [
		            'sub_heading_type' => 'image',
		        ],
		    ]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__('Icon Color', 'easy-elements'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-sub-heading i, {{WRAPPER}} .eel-sub-heading svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
				'condition' => [
				    'sub_heading_type' => ['icon'],
				],
			]
		);

		$this->add_responsive_control(
		    'sub_heading_icon_margin',
		    [
		        'label' => esc_html__( 'Icon Spacing', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%', 'em' ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-sub-heading span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		        'condition' => [
		            'sub_heading_type' => ['image', 'icon'],
		        ],
		    ]
		);

		$this->add_control(
		    'icon_direction',
		    [
		        'label' => esc_html__( 'Icon / Image Position', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::CHOOSE,
		        'default' => 'top',
		        'options' => [
		            'left' => [
		                'title' => esc_html__( 'Left of Text', 'easy-elements' ),
		                'icon'  => 'eicon-h-align-left',
		            ],
		            'top' => [
		                'title' => esc_html__( 'Above Text', 'easy-elements' ),
		                'icon'  => 'eicon-v-align-top',
		            ],
		            'right' => [
		                'title' => esc_html__( 'Right of Text', 'easy-elements' ),
		                'icon'  => 'eicon-h-align-right',
		            ],
		        ],
		        'description' => esc_html__( 'Place the icon/image relative to the sub heading text. Also controls how the sub heading wraps.', 'easy-elements' ),
		        'toggle' => false,
		    ]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'separator_section',
			[
				'label' => esc_html__('Separator Settings', 'easy-elements'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
		    'separator_',
		    [
		        'label' => __( 'Separator Type', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SELECT,
		        'options' => [
		            'none' => __( 'None', 'easy-elements' ),
		            'dotted' => __( 'Dotted', 'easy-elements' ),
		            'solid' => __( 'Solid', 'easy-elements' ),
		            'icon' => __( 'Icon', 'easy-elements' ),
		            'image' => __( 'Image', 'easy-elements' ),
		        ],
		        'default' => 'none',
		        'description' => esc_html__( 'Pick a separator style: solid bar, dotted dots, a font icon, or your own image.', 'easy-elements' ),
		    ]
		);

		// Solid / Dotted: color + size
		$this->add_control(
		    'solid_color',
		    [
		        'label'     => esc_html__('Separator Color', 'easy-elements'),
		        'type'      => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel--separator-icon.dotted::before, {{WRAPPER}} .eel--separator-icon' => 'color: {{VALUE}}; background-color: {{VALUE}};',
		        ],
		        'condition' => [
		            'separator_' => ['solid', 'dotted'],
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'separator_bar_width',
		    [
		        'label' => esc_html__( 'Width', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => [ 'px', '%', 'em' ],
		        'range' => [
		            'px' => [ 'min' => 5, 'max' => 500 ],
		            '%'  => [ 'min' => 1, 'max' => 100 ],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel--separator-icon' => 'width: {{SIZE}}{{UNIT}};',
		        ],
		        'condition' => [
		            'separator_' => ['solid', 'dotted'],
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'separator_bar_height',
		    [
		        'label' => esc_html__( 'Height (Solid)', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => [ 'px', 'em' ],
		        'range' => [
		            'px' => [ 'min' => 1, 'max' => 50 ],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel--separator-icon' => 'height: {{SIZE}}{{UNIT}};',
		        ],
		        'description' => esc_html__( 'Bar thickness. Applies to the Solid type.', 'easy-elements' ),
		        'condition' => [
		            'separator_' => ['solid'],
		        ],
		    ]
		);

		// Icon: picker + color + size
		$this->add_control(
		    'select_icon_',
		    [
		        'label' => esc_html__( 'Select/Upload Icon', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::ICONS,
		        'default' => [
		            'value' => 'fas fa-heart',
		            'library' => 'fa-solid',
		        ],
		        'description' => esc_html__( 'You can upload an SVG image or choose a font icon.', 'easy-elements' ),
		        'condition' => [
		            'separator_' => ['icon'],
		        ],
		    ]
		);

		$this->add_control(
		    'separator_icon_color',
		    [
		        'label' => esc_html__( 'Icon Color', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel--separator-icon-wrap i, {{WRAPPER}} .eel--separator-icon-wrap svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
		        ],
		        'condition' => [
		            'separator_' => ['icon'],
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'separator_icon_size',
		    [
		        'label' => esc_html__( 'Icon Size', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => [ 'px', 'em' ],
		        'range' => [
		            'px' => [ 'min' => 6, 'max' => 200 ],
		            'em' => [ 'min' => 0.5, 'max' => 10 ],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel--separator-icon-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
		            '{{WRAPPER}} .eel--separator-icon-wrap svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
		        ],
		        'condition' => [
		            'separator_' => ['icon'],
		        ],
		    ]
		);

		// Image: upload + size
		$this->add_control(
		    'sep_image',
		    [
		        'label' => esc_html__( 'Upload Image', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::MEDIA,
		        'default' => [
		            'url' => \Elementor\Utils::get_placeholder_image_src(),
		        ],
		        'condition' => [
		            'separator_' => 'image',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'separator_image_width',
		    [
		        'label' => esc_html__( 'Image Width', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => [ 'px', '%', 'em' ],
		        'range' => [
		            'px' => [ 'min' => 10, 'max' => 500 ],
		            '%'  => [ 'min' => 1, 'max' => 100 ],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-separator-img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
		        ],
		        'description' => esc_html__( 'Height stays auto to keep aspect ratio.', 'easy-elements' ),
		        'condition' => [
		            'separator_' => 'image',
		        ],
		    ]
		);

		// Position + Margin (apply to all types)
		$this->add_control(
		    'separator_position',
		    [
		        'label' => esc_html__( 'Position', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SELECT,
		        'default' => 'below',
		        'options' => [
		            'top'     => esc_html__( 'Top of Widget', 'easy-elements' ),
		            'above'   => esc_html__( 'Above Heading Title', 'easy-elements' ),
		            'below'   => esc_html__( 'Below Heading Title', 'easy-elements' ),
		            'bottom'  => esc_html__( 'Bottom of Widget', 'easy-elements' ),
		        ],
		        'description' => esc_html__( 'Where the separator sits inside the widget.', 'easy-elements' ),
		        'condition' => [
		            'separator_!' => 'none',
		        ],
		    ]
		);

		$this->add_control(
		    'separator_margin',
		    [
		        'label' => esc_html__( 'Margin', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%', 'em' ],
		        'selectors' => [
		            '{{WRAPPER}} .eel--separator-icon, {{WRAPPER}} .eel--separator-icon-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		        'condition' => [
		            'separator_!' => 'none',
		        ],
		    ]
		);

		$this->end_controls_section();


		$this->start_controls_section(
		    'water_mark_section',
		    [
		        'label' => esc_html__('WaterMark Settings', 'easy-elements'),
		        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		    ]
		);

		$this->add_control(
		    'water_mark',
		    [
		        'label'       => esc_html__('WaterMark Text', 'easy-elements'),
		        'type'        => \Elementor\Controls_Manager::TEXTAREA,
		        'label_block' => true,
		    ]
		);

		$this->end_controls_section();


		// Heading
		$this->start_controls_section(
			'section_heading_style',
			[
				'label' => esc_html__('Heading', 'easy-elements'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__('Color', 'easy-elements'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-e-heading .e-e-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_gradient_title' => '',
				],
			]
		);

		$this->add_control(
			'title_color_gradient_heading',
			[
				'label'     => esc_html__('Text Gradient Color', 'easy-elements'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_gradient_title' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'title_gradient_color',  
				'label'     => __( 'Title Color', 'easy-elements' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .e-e-heading .e-e-title.e-e-gradient-title',
				'condition' => [
					'show_gradient_title' => 'yes',
				], 
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__('Typography', 'easy-elements'),
				'selector' => '{{WRAPPER}} .e-e-heading .e-e-title',
			]
		);
		$this->add_control(
			'title_opacity',
			[
				'label'      => esc_html__('Opacity', 'easy-elements'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range'      => [
					'%' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
				],
				'default'    => [
		            'size' => 1,
		            'unit' => '%',
		        ],
				'selectors'  => [
					'{{WRAPPER}} .e-e-heading .e-e-title' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
		    'title_stroke_heading',
		    [
		        'label'     => esc_html__( 'Stroke', 'easy-elements' ),
		        'type'      => Controls_Manager::HEADING,
		        'separator' => 'before',
		    ]
		);

		$this->add_control(
		    'title_text_stroke',
		    [
		        'label'       => esc_html__('Stroke Width', 'easy-elements'),
		        'type'        => Controls_Manager::SLIDER,
		        'size_units'  => ['px', 'em'],
		        'range'       => [
		            'px' => [ 'min' => 0, 'max' => 10, 'step' => 0.1 ],
		            'em' => [ 'min' => 0, 'max' => 1,  'step' => 0.01 ],
		        ],
		        'default'     => [
		            'size' => 0,
		            'unit' => 'px',
		        ],
		        'selectors'   => [
		            '{{WRAPPER}} .e-e-heading .e-e-title' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
		        ],
		    ]
		);
		$this->add_control(
		    'title_text_stroke_color',
		    [
		        'label'     => esc_html__('Stroke Color', 'easy-elements'),
		        'type'      => Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .e-e-heading .e-e-title' => '-webkit-text-stroke-color: {{VALUE}};',
		        ],
		        'condition' => [
		            'title_text_stroke[size]!' => '',
		        ],
		    ]
		);
		$this->add_control(
		    'title_effects_heading',
		    [
		        'label'     => esc_html__( 'Effects', 'easy-elements' ),
		        'type'      => Controls_Manager::HEADING,
		        'separator' => 'before',
		    ]
		);

		// Text Shadow
		$this->add_group_control(
		    \Elementor\Group_Control_Text_Shadow::get_type(),
		    [
		        'name'     => 'title_text_shadow',
		        'label'    => esc_html__('Text Shadow', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .e-e-heading .e-e-title',
		    ]
		);
		// Mix Blend Mode
		$this->add_control(
		    'title_blend_mode',
		    [
		        'label'   => esc_html__('Blend Mode', 'easy-elements'),
		        'type'    => Controls_Manager::SELECT,
		        'options' => [
		            ''            => esc_html__('Default', 'easy-elements'),
		            'normal'      => 'Normal',
		            'multiply'    => 'Multiply',
		            'screen'      => 'Screen',
		            'overlay'     => 'Overlay',
		            'darken'      => 'Darken',
		            'lighten'     => 'Lighten',
		            'color-dodge' => 'Color Dodge',
		            'color-burn'  => 'Color Burn',
		            'hard-light'  => 'Hard Light',
		            'soft-light'  => 'Soft Light',
		            'difference'  => 'Difference',
		            'exclusion'   => 'Exclusion',
		            'hue'         => 'Hue',
		            'saturation'  => 'Saturation',
		            'color'       => 'Color',
		            'luminosity'  => 'Luminosity',
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .e-e-heading .e-e-title' => 'mix-blend-mode: {{VALUE}};',
		        ],
		    ]
		);	
		$this->add_control(
		    'title_spacing_heading',
		    [
		        'label'     => esc_html__( 'Spacing', 'easy-elements' ),
		        'type'      => Controls_Manager::HEADING,
		        'separator' => 'before',
		    ]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => esc_html__('Margin', 'easy-elements'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .e-e-heading .e-e-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => esc_html__('Padding', 'easy-elements'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .e-e-heading .e-e-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'title_image_fill_heading',
			[
				'label'     => esc_html__('Image Fill (Text Mask)', 'easy-elements'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'enable_title_image_fill',
			[
				'label' => esc_html__('Enable Image Fill', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'easy-elements'),
				'label_off' => esc_html__('No', 'easy-elements'),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'title_image_fill_bg',
				'label'     => esc_html__('Image Fill', 'easy-elements'),
				'types'     => [ 'classic' ],
				'selector'  => '{{WRAPPER}} .e-e-heading .e-e-title.e-e-image-fill, {{WRAPPER}} .e-e-heading .e-e-title.e-e-image-fill div',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'classic' => [
						'types' => [ 'image' ],
					],
				],
				'condition' => [
					'enable_title_image_fill' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_image_fill_note',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => __('Tip: Apply this only when you want the text to be filled with an image (the image will appear inside the text).', 'easy-elements'),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->end_controls_section();

		//Sub Heading
		$this->start_controls_section(
			'section_sub_heading_style',
			[
				'label' => esc_html__('Sub Heading', 'easy-elements'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sub_heading_color',
			[
				'label'     => esc_html__('Color', 'easy-elements'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-e-heading .eel-sub-heading span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'background_subtext',
				'label'    => esc_html__( 'Background', 'easy-elements' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-sub-heading.top',
				'condition' => [
		            'icon_direction' => 'top',
		        ],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'background_subtext_top',
				'label'    => esc_html__( 'Background', 'easy-elements' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-sub-heading',
				'condition' => [
		            'icon_direction' => ['left', 'right'],
		        ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_heading_typography',
				'label'    => esc_html__('Typography', 'easy-elements'),
				'selector' => '{{WRAPPER}} .e-e-heading .eel-sub-heading span',
			]
		);

		$this->add_control(
		    'sub_heading_icon_image_heading',
		    [
		        'label'     => esc_html__( 'Icon / Image', 'easy-elements' ),
		        'type'      => Controls_Manager::HEADING,
		        'separator' => 'before',
		        'condition' => [
		            'sub_heading_type' => [ 'icon', 'image' ],
		        ],
		    ]
		);

		$this->add_responsive_control(
			'sub_heading_icon_text_gap',
			[
				'label' => esc_html__( 'Icon/Image to Text Gap', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 100 ],
					'em' => [ 'min' => 0, 'max' => 10 ],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-sub-heading.left, {{WRAPPER}} .eel-sub-heading.right' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-sub-heading.top i, {{WRAPPER}} .eel-sub-heading.top svg, {{WRAPPER}} .eel-sub-heading.top img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'sub_heading_type' => [ 'icon', 'image' ],
				],
			]
		);

		$this->add_control(
		    'subtext_icon_size',
		    [
		        'label' => esc_html__( 'Icon Size', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'range' => [
		            'px' => [
		                'min' => 6,
		                'max' => 100,
		            ],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-sub-heading i, {{WRAPPER}} .eel-sub-heading svg' => 'font-size: {{SIZE}}{{UNIT}};',
		        ],
		        'description' => esc_html__( 'Adjust the icon size in pixels for the subheading icon.', 'easy-elements' ),
		        'condition' => [
		            'sub_heading_type' => 'icon',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'subtext_image_size',
		    [
		        'label' => esc_html__( 'Image Width', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => [ 'px', 'em', '%' ],
		        'range' => [
		            'px' => [
		                'min' => 10,
		                'max' => 300,
		            ],
		            '%' => [
		                'min' => 1,
		                'max' => 100,
		            ],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-sub-heading img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
		        ],
		        'description' => esc_html__( 'Adjust the image width. Height stays auto to keep aspect ratio (override below).', 'easy-elements' ),
		        'condition' => [
		            'sub_heading_type' => 'image',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'subtext_image_height',
		    [
		        'label' => esc_html__( 'Image Height', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => [ 'px', 'em', '%' ],
		        'range' => [
		            'px' => [
		                'min' => 10,
		                'max' => 300,
		            ],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-sub-heading img' => 'height: {{SIZE}}{{UNIT}};',
		        ],
		        'description' => esc_html__( 'Optional. Set a fixed height. Leave empty to keep auto height.', 'easy-elements' ),
		        'condition' => [
		            'sub_heading_type' => 'image',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'subtext_image_radius',
		    [
		        'label' => esc_html__( 'Image Border Radius', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-sub-heading img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		        'condition' => [
		            'sub_heading_type' => 'image',
		        ],
		    ]
		);

		$this->add_control(
		    'sub_heading_box_heading',
		    [
		        'label'     => esc_html__( 'Box', 'easy-elements' ),
		        'type'      => Controls_Manager::HEADING,
		        'separator' => 'before',
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Border::get_type(),
		    [
		        'name' => 'subtext_border',
		        'label' => esc_html__( 'Border', 'easy-elements' ),
		        'selector' => '{{WRAPPER}} .eel-sub-heading',
		    ]
		);

		$this->add_responsive_control(
		    'subtext_border_radius',
		    [
		        'label' => esc_html__( 'Border Radius', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%', 'em' ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-sub-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'subtext_padding',
		    [
		        'label' => esc_html__( 'Padding', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%', 'em' ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-sub-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);	

		$this->add_control(
		    'sub_heading_margin',
		    [
		        'label' => esc_html__( 'Margin', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%', 'em' ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-sub-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->end_controls_section();


		// Highlight
		$this->start_controls_section(
			'section_highlight_style',
			[
				'label' => esc_html__('Highlight', 'easy-elements'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'highlight_help',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => sprintf(
					/* translators: %s is the curly-brace syntax example rendered with HTML entities so Elementor's template engine does not evaluate it. */
					esc_html__( 'Wrap part of the heading text in double curly braces to highlight it. Example: %s highlights "Here".', 'easy-elements' ),
					'<code>Heading &#123;&#123;Here&#125;&#125;</code>'
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'highlight_color',
			[
				'label'     => esc_html__('Color', 'easy-elements'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-e-heading .e-e-title span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'background_highlight',
				'label'    => esc_html__( 'Background', 'easy-elements' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .e-e-heading .e-e-title span',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'highlight_typography',
				'label'    => esc_html__('Highlight Typography', 'easy-elements'),
				'selector' => '{{WRAPPER}} .e-e-heading .e-e-title span',
			]
		);	

		$this->add_responsive_control(
		    'highlight_padding',
		    [
		        'label' => esc_html__( 'Padding', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%', 'em' ],
		        'selectors' => [
		            '{{WRAPPER}} .e-e-heading .e-e-title span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);	

		$this->add_control(
		    'highlight_margin',
		    [
		        'label' => esc_html__( 'Margin', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%', 'em' ],
		        'selectors' => [
		            '{{WRAPPER}} .e-e-heading .e-e-title span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'hithlight_border_radius',
		    [
		        'label' => esc_html__( 'Border Radius', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%', 'em' ],
		        'selectors' => [
		            '{{WRAPPER}} .e-e-heading .e-e-title span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->end_controls_section();

		// Description
		$this->start_controls_section(
			'section_description_style',
			[
				'label' => esc_html__('Description', 'easy-elements'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__('Color', 'easy-elements'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-e-heading .e-e-description' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'label'    => esc_html__('Typography', 'easy-elements'),
				'selector' => '{{WRAPPER}} .e-e-heading .e-e-description',
			]
		);
		$this->add_responsive_control(
			'description_margin',
			[
				'label'      => esc_html__('Margin', 'easy-elements'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .e-e-heading .e-e-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'description_padding',
			[
				'label'      => esc_html__('Padding', 'easy-elements'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .e-e-heading .e-e-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();


		// =====================================================================
		// WaterMark Style
		// =====================================================================
		$this->start_controls_section(
		    'water_mark_style_section',
		    [
		        'label'     => esc_html__('WaterMark', 'easy-elements'),
		        'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
		        'condition' => [
		            'water_mark!' => '',
		        ],
		    ]
		);

		$this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
		        'name'     => 'water_mark_typography',
		        'label'    => esc_html__('Typography', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-watermark',
				// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
		        'exclude'  => ['font_size'],
		    ]
		);

		// Kept for back-compat with widgets saved before Typography group was added.
		$this->add_responsive_control(
		    'water_mark_font_size',
		    [
		        'label'      => esc_html__('Font Size', 'easy-elements'),
		        'type'       => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => ['px', 'em', 'rem', '%'],
		        'range'      => [
		            'px'  => ['min' => 10,  'max' => 300],
		            'em'  => ['min' => 0.5, 'max' => 20],
		            'rem' => ['min' => 0.5, 'max' => 20],
		            '%'   => ['min' => 10,  'max' => 500],
		        ],
		        'default'    => [
		            'size' => '',
		            'unit' => 'px',
		        ],
		        'selectors'  => [
		            '{{WRAPPER}} .eel-watermark' => 'font-size: {{SIZE}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_control(
		    '_mark_color',
		    [
		        'label'     => esc_html__('Text Color', 'easy-elements'),
		        'type'      => \Elementor\Controls_Manager::COLOR,
		        'default'   => '',
		        'selectors' => [
		            '{{WRAPPER}} .eel-watermark' => 'color: {{VALUE}};',
		        ],
		    ]
		);

		$this->add_control(
		    'water_mark_color',
		    [
		        'label'     => esc_html__('Stroke Color', 'easy-elements'),
		        'type'      => \Elementor\Controls_Manager::COLOR,
		        'default'   => 'rgba(0, 0, 0, 0.3)',
		        'selectors' => [
		            '{{WRAPPER}} .eel-watermark' => '-webkit-text-stroke-color: {{VALUE}}; text-stroke-color: {{VALUE}};',
		        ],
		    ]
		);

		$this->add_control(
		    'water_mark_stroke_width',
		    [
		        'label'      => esc_html__('Stroke Width', 'easy-elements'),
		        'type'       => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => ['px', 'em'],
		        'default'    => [
		            'size' => 1,
		            'unit' => 'px',
		        ],
		        'range'      => [
		            'px' => ['min' => 0, 'max' => 10, 'step' => 0.1],
		            'em' => ['min' => 0, 'max' => 1,  'step' => 0.01],
		        ],
		        'selectors'  => [
		            '{{WRAPPER}} .eel-watermark' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}}; text-stroke-width: {{SIZE}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Text_Shadow::get_type(),
		    [
		        'name'     => 'water_mark_text_shadow',
		        'label'    => esc_html__('Text Shadow', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-watermark',
		    ]
		);

		// Background & Box
		$this->add_control(
		    'water_mark_box_heading',
		    [
		        'label'     => esc_html__('Box', 'easy-elements'),
		        'type'      => \Elementor\Controls_Manager::HEADING,
		        'separator' => 'before',
		    ]
		);

		$this->add_group_control(
		    Group_Control_Background::get_type(),
		    [
		        'name'     => 'water_mark_background',
		        'label'    => esc_html__('Background', 'easy-elements'),
		        'types'    => ['classic', 'gradient'],
		        'selector' => '{{WRAPPER}} .eel-watermark',
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Border::get_type(),
		    [
		        'name'     => 'water_mark_border',
		        'label'    => esc_html__('Border', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-watermark',
		    ]
		);

		$this->add_responsive_control(
		    'water_mark_border_radius',
		    [
		        'label'      => esc_html__('Border Radius', 'easy-elements'),
		        'type'       => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', '%', 'em'],
		        'selectors'  => [
		            '{{WRAPPER}} .eel-watermark' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'water_mark_padding',
		    [
		        'label'      => esc_html__('Padding', 'easy-elements'),
		        'type'       => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', '%', 'em'],
		        'selectors'  => [
		            '{{WRAPPER}} .eel-watermark' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Box_Shadow::get_type(),
		    [
		        'name'     => 'water_mark_box_shadow',
		        'label'    => esc_html__('Box Shadow', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-watermark',
		    ]
		);

		// Position & Effects
		$this->add_control(
		    'water_mark_position_heading',
		    [
		        'label'     => esc_html__('Position & Effects', 'easy-elements'),
		        'type'      => \Elementor\Controls_Manager::HEADING,
		        'separator' => 'before',
		    ]
		);

		$this->add_responsive_control(
		    'water_mark_top',
		    [
		        'label'      => esc_html__('Top / Bottom', 'easy-elements'),
		        'type'       => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => ['px', '%', 'em'],
		        'range'      => [
		            'px' => ['min' => -500, 'max' => 500],
		            '%'  => ['min' => -100, 'max' => 100],
		            'em' => ['min' => -50,  'max' => 50],
		        ],
		        'selectors'  => [
		            '{{WRAPPER}} .eel-watermark' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'water_mark_left',
		    [
		        'label'      => esc_html__('Left / Right', 'easy-elements'),
		        'type'       => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => ['px', '%', 'em'],
		        'range'      => [
		            'px' => ['min' => -500, 'max' => 500],
		            '%'  => ['min' => 0,    'max' => 100],
		            'em' => ['min' => 0,    'max' => 50],
		        ],
		        'selectors'  => [
		            '{{WRAPPER}} .eel-watermark' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'water_mark_z_index',
		    [
		        'label'       => esc_html__('Z-Index', 'easy-elements'),
		        'type'        => \Elementor\Controls_Manager::SLIDER,
		        'default'     => [
		            'size' => -1,
		        ],
		        'range'       => [
		            'px' => [
		                'min' => -10,
		                'max' => 9999,
		            ],
		        ],
		        'description' => esc_html__('Use a negative value (e.g. -1) to keep the watermark behind the heading.', 'easy-elements'),
		        'selectors'   => [
		            '{{WRAPPER}} .eel-watermark' => 'z-index: {{SIZE}};',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'water_mark_rotation',
		    [
		        'label'      => esc_html__('Rotation', 'easy-elements'),
		        'type'       => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => ['deg'],
		        'range'      => [
		            'deg' => ['min' => -360, 'max' => 360, 'step' => 1],
		        ],
		        'default'    => [
		            'size' => '',
		            'unit' => 'deg',
		        ],
		        'selectors'  => [
		            '{{WRAPPER}} .eel-watermark' => 'transform: translateY(-50%) rotate({{SIZE}}deg);',
		        ],
		    ]
		);

		$this->add_control(
		    'water_mark_opacity',
		    [
		        'label'      => esc_html__('Opacity', 'easy-elements'),
		        'type'       => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => ['%'],
		        'range'      => [
		            '%' => ['min' => 0, 'max' => 1, 'step' => 0.01],
		        ],
		        'default'    => [
		            'size' => '',
		            'unit' => '%',
		        ],
		        'selectors'  => [
		            '{{WRAPPER}} .eel-watermark' => 'opacity: {{SIZE}};',
		        ],
		    ]
		);

		$this->add_control(
		    'water_mark_blend_mode',
		    [
		        'label'     => esc_html__('Blend Mode', 'easy-elements'),
		        'type'      => \Elementor\Controls_Manager::SELECT,
		        'options'   => [
		            ''            => esc_html__('Default', 'easy-elements'),
		            'normal'      => 'Normal',
		            'multiply'    => 'Multiply',
		            'screen'      => 'Screen',
		            'overlay'     => 'Overlay',
		            'darken'      => 'Darken',
		            'lighten'     => 'Lighten',
		            'color-dodge' => 'Color Dodge',
		            'color-burn'  => 'Color Burn',
		            'hard-light'  => 'Hard Light',
		            'soft-light'  => 'Soft Light',
		            'difference'  => 'Difference',
		            'exclusion'   => 'Exclusion',
		            'hue'         => 'Hue',
		            'saturation'  => 'Saturation',
		            'color'       => 'Color',
		            'luminosity'  => 'Luminosity',
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-watermark' => 'mix-blend-mode: {{VALUE}};',
		        ],
		    ]
		);

		$this->end_controls_section();
	}

	protected function render_separator( $settings ) {
	    if ( ! empty( $settings['separator_'] ) && in_array( $settings['separator_'], ['dotted', 'solid'], true ) ) {
	        echo '<div class="eel--separator-icon ' . esc_attr( $settings['separator_'] ) . '"></div>';
	    }

	    if ( 'icon' === $settings['separator_'] && ! empty( $settings['select_icon_']['value'] ) ) {
	        echo '<div class="eel--separator-icon-wrap">';
	        \Elementor\Icons_Manager::render_icon( $settings['select_icon_'], [ 'aria-hidden' => 'true' ] );
	        echo '</div>';
	    }	 
		if ( isset( $settings['separator_'] ) && 'image' === $settings['separator_'] && ! empty( $settings['sep_image']['url'] ) ) {
			echo '<div class="eel--separator-icon-wrap">';
			printf(
				'<img src="%s" alt="%s" title="%s" class="eel-separator-img" loading="lazy" decoding="async" />',
				esc_url( $settings['sep_image']['url'] ),
				esc_attr__( 'Separator Image', 'easy-elements' ),
				esc_attr__( 'Separator Image', 'easy-elements' )
			);

			echo '</div>';
		}
	}

	protected function render() {
	    $settings = $this->get_settings_for_display();

	    $tag = isset($settings['title_tag']) ? $settings['title_tag'] : 'h2';
	    $title = preg_replace_callback( '/\{\{(.*?)\}\}/', function( $matches ) {
	            return '<span>' . esc_html( trim( $matches[1] ) ) . '</span>';
	        }, $settings['title'] );

	    	if ( ! empty( $settings['link']['url'] ) ) {
	            $this->add_link_attributes( 'title_link', $settings['link'] );
	            $title = sprintf(
	                '<a %s>%s</a>',
	                $this->get_render_attribute_string( 'title_link' ),
	                $title
	            );
	        }

	       	$border_position = $settings['border_position'] ?? '';
			$unique_id = 'eel-heading-' . $this->get_id();
			$animation = isset( $settings['animation_type'] ) && ! empty($settings['animation_type']) ? $settings['animation_type'] : '';
			$watermark_class = ! empty( $settings['water_mark'] ) ? 'has-watermark' : '';
	    ?>
	    <div class="e-e-heading <?php echo esc_attr( trim( $border_position . ' ' . $watermark_class ) ); ?>" <?php if(!empty($animation)) : ?>
        data-eel-animation="<?php echo esc_attr($animation); ?>"
     <?php endif; ?>>
	    	<?php 
	    	$icon_direction = $settings['icon_direction']  ?? '' ;
	    	$separator_position = ! empty( $settings['separator_position'] ) ? $settings['separator_position'] : 'default';

	    	if ( in_array( $separator_position, ['top'], true ) ) {
	    	    if ( ! empty( $settings['separator_'] ) ) {
	    	        $this->render_separator( $settings );
	    	    }
	    	}

	    	if ( ! empty( $settings['sub_title'] ) ) : ?>
	    	    <div class="eel-sub-heading <?php echo esc_attr($icon_direction); ?> <?php echo esc_attr($settings['show_gradient_border'] == 'yes' ? 'easy_gradiant_border' : ''); ?>">
	    	        <?php
					$this->add_inline_editing_attributes( 'sub_title' );
	    	        if ( 'icon' === $settings['sub_heading_type'] && ! empty( $settings['sub_heading_icon']['value'] ) ) {
	    	            \Elementor\Icons_Manager::render_icon( $settings['sub_heading_icon'], [ 'aria-hidden' => 'true' ] );
	    	        } elseif ( 'image' === $settings['sub_heading_type'] && ! empty( $settings['sub_heading_image']['url'] ) ) {
	    	            if ( ! empty( $settings['sub_heading_image']['id'] ) ) {
							echo wp_get_attachment_image(
								$settings['sub_heading_image']['id'],
								'full',
								false,
								['alt' => $settings['sub_heading_image']['alt'] ?? '']
							);
						} elseif ( ! empty( $settings['sub_heading_image']['url'] ) ) {
							echo '<img src="' . esc_url( $settings['sub_heading_image']['url'] ) . '" alt="">';
						}
	    	        } 
					
					?>
	    	        <span class="elementor-inline-editing" <?php 
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $this->get_render_attribute_string( 'sub_title' ); ?>>
		    	        <?php						
		    	        echo wp_kses_post( $settings['sub_title'] );
		    	        ?>
		    	    </span>
	    	    </div>
	    	<?php endif;

	    	if ( in_array( $separator_position, ['above'], true ) ) {
	    	    if ( ! empty( $settings['separator_'] ) ) {
	    	        $this->render_separator( $settings );
	    	    }
	    	}

		    $allowed_tags = ['h1','h2','h3','h4','h5','h6','div','span'];
			$tag = in_array(strtolower($tag), $allowed_tags, true) ? strtolower($tag) : 'div';
			

			if ( ! empty( $title ) ) {
				$gradient_class   = ! empty( $settings['show_gradient_title'] ) ? ' e-e-gradient-title' : '';
				$image_fill_class = ( ! empty( $settings['enable_title_image_fill'] ) ) ? ' e-e-image-fill' : '';
				$tag_class        = 'e-e-title elementor-inline-editing' . $gradient_class . $image_fill_class;

				// Inline editing attribute add
				$this->add_inline_editing_attributes( 'title' );

				printf(
					'<%1$s class="%2$s" %3$s>%4$s</%1$s>',
					esc_html( $tag ),
					esc_attr( $tag_class ),
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$this->get_render_attribute_string( 'title' ),
					wp_kses_post( $title )
				);
			}



		    if ( in_array( $separator_position, ['below'], true ) ) {
		        if ( ! empty( $settings['separator_'] ) ) {
		            $this->render_separator( $settings );
		        }
		    }

	        if ( ! empty( $settings['description'] ) ) {
				$this->add_inline_editing_attributes( 'description' );
		    ?>
			    <div class="e-e-description elementor-inline-editing" <?php 
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->get_render_attribute_string( 'description' ); ?>>
			    	<?php echo wp_kses_post( $settings['description'] ); ?>
			    </div>
			<?php }
			if ( in_array( $separator_position, ['bottom'], true ) ) {
			    if ( ! empty( $settings['separator_'] ) ) {
			        $this->render_separator( $settings );
			    }
			}

			if ( ! empty( $settings['water_mark'] ) ) {
			    echo '<div class="eel-watermark">';
			    echo esc_html( $settings['water_mark'] );
			    echo '</div>';
			}

			?>
	    </div>
	<?php }
}