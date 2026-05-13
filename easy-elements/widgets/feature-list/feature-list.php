<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;

defined( 'ABSPATH' ) || die();
class Easyel_Feature_List__Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'eel-feature-list';
    }

    public function get_title() {
        return esc_html__( 'Feature List', 'easy-elements' );
    }

    public function get_icon() {
        return 'easyicon easyelIcon-service-list';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'feature', 'list', 'icon', 'icon-box', 'text' ];
    }

    public function get_style_depends() {
        return [
            'eel-feature-list',
        ];
    }


    protected function register_controls() {

        $this->start_controls_section(
            'section_feature_list',
            [
                'label' => esc_html__( 'Feature Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );        

        $repeater = new \Elementor\Repeater();

        // Icon Type
        $repeater->add_control(
            'icon_type',
            [
                'label' => esc_html__( 'Select Type', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'icon'   => esc_html__( 'Icon', 'easy-elements' ),
                    'number' => esc_html__( 'Number', 'easy-elements' ),
                    'image' => esc_html__( 'Image', 'easy-elements' ),
                ],
                'default' => 'icon',
                'label_block' => true,
            ]
        );

        // Icon
        $repeater->add_control(
            'fea_list_icon',
            [
                'label' => esc_html__( 'Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'icon_type' => 'icon',
                ],
            ]
        );

        // Number
        $repeater->add_control(
            'fea_list_number_title',
            [
                'label' => esc_html__( 'Number', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '01', 'easy-elements' ),
                'condition' => [
                    'icon_type' => 'number',
                ],
                'label_block' => true,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'fea_list_img',
            [
                'label' => esc_html__( 'Image', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'icon_type' => 'image',
                ],
            ]
        );

        // Title
        $repeater->add_control(
            'fea_list_title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Easy Elements', 'easy-elements' ),
                'label_block' => true,
                'dynamic' => [ 'active' => true ],
            ]
        );

        // Description
        $repeater->add_control(
            'fea_list_desc',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Feature description goes here...', 'easy-elements' ),
                'label_block' => true,
                'dynamic' => [ 'active' => true ],
            ]
        );

        // Add Repeater to widget
        $this->add_control(
            'features_list',
            [
                'label' => esc_html__( 'Feature Items', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'icon_type'             => 'icon',
                        'fea_list_icon'      => [ 'value' => 'fas fa-clock', 'library' => 'fa-solid' ],
                        'fea_list_title'     => 'Lightning Fast',
                        'fea_list_desc'      => 'Your site loads in seconds with our highly optimized structure.',
                    ],
                    [
                        'icon_type'          => 'icon',
                        'fea_list_icon'      => [ 'value' => 'far fa-clone', 'library' => 'fa-solid' ],
                        'fea_list_title'     => 'Smart & Flexible',
                        'fea_list_desc'      => 'Build exactly what you envision with powerful styling controls.',
                    ],
                    [
                        'icon_type'          => 'icon',
                        'fea_list_icon'      => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ],
                        'fea_list_title'     => 'Reliable Support',
                        'fea_list_desc'      => 'Our expert team is always ready to assist you with quick, friendly.',
                    ]
                ],
                'title_field' => '{{{ fea_list_title }}}',
            ]
        );   
        
        $this->add_responsive_control(
            'fea_dir',
            [
                'label' => esc_html__( 'Icon Direction', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-right',
                    ]
                ],
                'default' => 'left',
                'toggle' => true,
            ]
        );

        $this->add_responsive_control(
            'fea_vertical_align',
            [
                'label' => esc_html__( 'Vertical Alignment', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Middle', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Bottom', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list' => 'align-items: {{VALUE}};',
                ],
            ]
        );      
        
        $this->add_control(
            'fea_list_icon_view',
            [
                'label' => esc_html__( 'Icon View', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,   
                'default' => 'stracked',
                'options' => [
                    'default'  => esc_html__( 'Default', 'easy-elements' ),
                    'frame' => esc_html__( 'Frame', 'easy-elements' ),
                    'stracked' => esc_html__( 'Stracked', 'easy-elements' )
                ],  
				'prefix_class' => 'eel-fea-list-icon-view-', 
            ]
        );     
        
        $this->add_control(
            'fea_list_icon_shape',
            [
                'label' => esc_html__( 'Shape', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,   
                'default' => 'rounded',
                'options' => [
                    'rounded'   => esc_html__( 'Rounded', 'easy-elements' ),
                    'square'    => esc_html__( 'Square', 'easy-elements' ),
                    'circle'    => esc_html__( 'Circle', 'easy-elements' ),
                    'sq_rotate' => esc_html__( 'Square Rotate', 'easy-elements' ),
                ],  
				'prefix_class' => 'eel-fea-list-icon-shape-', 
                'condition' => [
                    'fea_list_icon_view' => ['frame', 'stracked']
                ]
            ]
        );
        
        $this->add_control(
			'title_tag',
			[
				'label'   => esc_html__('Title HTML Tag', 'easy-elements'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
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
            'fea_list_styles',
            [
                'label' => esc_html__( 'Feature List', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'list_background',
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .eel-fea-list',
			]
		);

        $this->add_responsive_control(
            'fea_item_gap',
            [
                'label' => esc_html__( 'Item Gap', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'fea_middle_gap',
            [
                'label' => esc_html__( 'Middle Gap', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );   

        $this->add_control(
            'fea_connector',
            [
                'label' => esc_html__( 'Connector', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );  

        $this->add_control(
            'fea_connector_left',
            [
                'label' => esc_html__( 'Left Connector', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'fea_connector' => 'yes'
                ]
            ]
        ); 
        
        $this->add_control(
            'fea_connector_type',
            [
                'label' => esc_html__( 'Type', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,   
                'default' => 'solid',
                'options' => [
                    'solid'  => esc_html__( 'Solid', 'easy-elements' ),
                    'dotted' => esc_html__( 'Dotted', 'easy-elements' ),
                    'dashed' => esc_html__( 'Dashed', 'easy-elements' ),
                    'double' => esc_html__( 'Double', 'easy-elements' ),
                ],             
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list-wrapper.eel-fea-list-connector::before' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .eel-fea-list-icon::after' => 'border-style: {{VALUE}};',
                ],
                'condition' => [
                    'fea_connector' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'fea_connector_width',
            [
                'label' => esc_html__( 'Connector Width', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list-wrapper.eel-fea-list-connector::before' => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-fea-list-icon::after' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'fea_connector' => 'yes'
                ]
            ]
        );     
        
        $this->add_responsive_control(
            'fea_connector_position_x',
            [
                'label' => esc_html__( 'Position Horizontal', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
					'px' => [
						'min' => -50,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => -50,
						'max' => 100,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list-wrapper.eel-fea-list-connector::before' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'fea_connector' => 'yes',
                    'fea_dir' => 'left',
                ]

            ]
        ); 

        $this->add_responsive_control(
            'fea_connector_right_position_x',
            [
                'label' => esc_html__( 'Position Horizontal', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
					'px' => [
						'min' => -50,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => -50,
						'max' => 100,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list-connector.eel-fea-list-dir-right::before' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'fea_connector' => 'yes',
                    'fea_dir' => 'right'
                ]
            ]
        ); 

        $this->add_control(
            'fea_connector_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list-wrapper.eel-fea-list-connector::before' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .eel-fea-list-icon::after' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'fea_connector' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'fea_list_border',
                'selector' => '{{WRAPPER}} .eel-fea-list',
                'separator' => 'before'
            ]
        );        

        $this->add_responsive_control(
            'fea_list_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );       

        $this->add_responsive_control(
            'fea_list_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );        
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Icon', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list-icon svg,{{WRAPPER}} .eel-fea-list-icon svg path, {{WRAPPER}} .eel-fea-list-icon i,  
                    {{WRAPPER}} .eel-fea-list-number' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );	

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'icon_bg_color',
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .eel-fea-list-icon',
			]
		);

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Size', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-fea-list-image' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-fea-list-number' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_box_size',
            [
                'label' => esc_html__( 'Box Size', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 150,
                    ],
                ],
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list-icon' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
			'icon_alignment',
			[
				'label' => esc_html__( 'Alignment', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'easy-elements' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'easy-elements' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'easy-elements' ),
						'icon' => 'eicon-text-align-right',
					],
				],
                'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .eel-fea-list-icon' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_shadow',
				'label'    => esc_html__('Box Shadow', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-fea-list-icon',
			]
		);	
      
		$this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'icon_stroke',
				'selector' => '{{WRAPPER}} .eel-fea-list-number,{{WRAPPER}} .eel-fea-list-icon svg',
			]
		);
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .eel-fea-list-icon',
            ]
        );

        $this->add_responsive_control(
			'icon_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-fea-list-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		); 
        $this->end_controls_section();   

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .eel-fea-list-title',
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'title_stroke',
				'selector' => '{{WRAPPER}} .eel-fea-list-title',
			]
		);	            

        $this->add_responsive_control(
            'fea_title_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );  
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_desc',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-fea-list-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .eel-fea-list-desc',
            ]
        );
        $this->end_controls_section();

    }

    protected function render() {

        $settings        = $this->get_settings_for_display();
        $connector       = ( isset($settings['fea_connector']) && $settings['fea_connector'] === 'yes' ) ? 'eel-fea-list-connector' : '';
        $connector_left  = ( isset($settings['fea_connector_left']) && $settings['fea_connector_left'] === 'yes' ) ? 'eel-fea-list-connector-left' : '';
        $title_tag       = ! empty($settings['title_tag']) ? \Elementor\Utils::validate_html_tag($settings['title_tag']) : 'h3';

        if ( empty( $settings['features_list'] ) ) {
            return;
        }

        echo '<div class="eel-fea-list-wrapper ' . esc_attr($connector) . ' ' . esc_attr($connector_left) . ' eel-fea-list-dir-' . esc_attr($settings['fea_dir']) . '">';

        foreach ( $settings['features_list'] as $item ) {

            echo '<div class="eel-fea-list eel-fea-list-dir-' . esc_attr($settings['fea_dir']) . ' ">';

                /* ------------------------------------
                    ICON / NUMBER / IMAGE WRAPPER
                ------------------------------------- */

                $has_icon = false;

                // check IMAGE
                if (
                    $item['icon_type'] === 'image' &&
                    ! empty($item['fea_list_img']) &&
                    ! empty($item['fea_list_img']['url'])
                ) {
                    $has_icon = true;
                }

                // check ICON
                elseif (
                    $item['icon_type'] === 'icon' &&
                    ! empty($item['fea_list_icon']) &&
                    ! empty($item['fea_list_icon']['value'])
                ) {
                    $has_icon = true;
                }

                // check NUMBER (0 allow )
                elseif (
                    $item['icon_type'] === 'number' &&
                    isset($item['fea_list_number_title']) &&
                    $item['fea_list_number_title'] !== ''
                ) {
                    $has_icon = true;
                }

                if ($has_icon) {

                $classes = [
                        'eel-fea-list-icon',
                        'eel-fea-list-type-' . $item['icon_type'],
                    ];

                    echo '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">';

                    // IMAGE
                    if (
                        $item['icon_type'] === 'image' &&
                        ! empty( $item['fea_list_img']['url'] )
                    ) {

                        $alt = ! empty( $item['fea_list_img']['alt'] )
                            ? $item['fea_list_img']['alt']
                            : $item['fea_list_title'];

                        echo '<img 
                            src="' . esc_url( $item['fea_list_img']['url'] ) . '" 
                            alt="' . esc_attr( $alt ) . '" 
                            class="eel-fea-list-img"
                        >';
                    }
                    // ICON
                    elseif ( $item['icon_type'] === 'icon' && ! empty( $item['fea_list_icon']['value'] ) ) {

                        \Elementor\Icons_Manager::render_icon(
                            $item['fea_list_icon'],
                            [ 'aria-hidden' => 'true' ]
                        );

                    }

                    // NUMBER
                    elseif ( $item['icon_type'] === 'number' && ! empty( $item['fea_list_number_title'] ) ) {

                        echo '<span class="eel-fea-list-number">'
                            . esc_html( $item['fea_list_number_title'] ) .
                        '</span>';

                    }

                    echo '</div>'; // .eel-fea-list-icon end
                }

                /* ------------------------------------
                        TEXT CONTENT WRAPPER
                ------------------------------------- */

                if ( ! empty($item['fea_list_title']) || ! empty($item['fea_list_desc']) ) {

                    echo '<div class="eel-fea-list-info">';

                    // Title
                    if ( ! empty( $item['fea_list_title'] ) ) {
                        printf( '<%1$s class="eel-fea-list-title">%2$s</%1$s>', esc_attr($title_tag), wp_kses_post($item['fea_list_title']) );
                    }

                    // Description
                    if ( ! empty( $item['fea_list_desc'] ) ) {
                        echo '<p class="eel-fea-list-desc">' . esc_html($item['fea_list_desc']) . '</p>';
                    }

                    echo '</div>'; // info end
                }

            echo '</div>'; // single item end
        }

        echo '</div>'; // wrapper end
    }

} 