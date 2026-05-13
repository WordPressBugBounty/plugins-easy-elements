<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_Icon_Box__Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'eel-icon-box';
    }

    public function get_title() {
        return esc_html__( 'Info Box', 'easy-elements' );
    }

    public function get_icon() {
        return 'easyicon easyelIcon-iconbox';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'box', 'sevice', 'icon', 'icon-box', 'text' ];
    }

    public function get_style_depends() {
        return [
            'eel-icon-box',
        ];
    }


    protected function register_controls() {
        $this->start_controls_section(
            '_section_logo',
            [
                'label' => esc_html__( 'Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
          'info_skin',
            [
              'label' => esc_html__( 'Skin', 'easy-elements' ),
              'type' => \Elementor\Controls_Manager::SELECT,
              'options' => [
                'default' => esc_html__( 'Skin 01', 'easy-elements' ),
                'skin-2' => esc_html__( 'Skin 02', 'easy-elements' ),
              ],
              'default' => 'default',
            ]  
        );

        $this->add_control(
		    'icon_type',
		    [
		        'label' => esc_html__( 'Type', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::CHOOSE,
		        'options' => [
		            'icon' => [
		                'title' => esc_html__( 'Icon', 'easy-elements' ),
		                'icon' => 'eicon-star',
		            ],
		            'image' => [
		                'title' => esc_html__( 'Image', 'easy-elements' ),
		                'icon' => 'eicon-image-bold',
		            ],
		        ],
		        'default' => 'icon',
		        'toggle' => false,
		    ]
		);

        $this->add_control(
            'icon',
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

        $this->add_control(
		    'icon_image',
		    [
		        'label' => esc_html__( 'Upload Image', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::MEDIA,
		        'default' => [
		            'url' => \Elementor\Utils::get_placeholder_image_src(),
		        ],
		        'condition' => [
		            'icon_type' => 'image',
		        ],
		    ]
		);


        $this->add_control(
            'number_title',
            [
                'label' => esc_html__( 'Number', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' =>  '',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'number_gradeint',
            [
                'label' => esc_html__('Number Show Gradient', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'easy-elements'),
                'label_off' => esc_html__('Hide', 'easy-elements'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'number_title!' => ''
                ],
            ]
        );        

        $this->add_control(
            'procs_title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Manufacturing Industrial', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            '_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Optimizing production and supply chain operations and generational transitions', 'easy-elements' ),
            ]
        );

       $repeater = new \Elementor\Repeater();

$repeater->add_control(
    '_feature_icon',
    [
        'label' => esc_html__( 'Icon', 'easy-elements' ),
        'type' => \Elementor\Controls_Manager::ICONS,
        'fa4compatibility' => 'icon',
        'default' => [
            'value' => 'fas fa-check',
            'library' => 'fa-solid',
        ],
    ]
);

$repeater->add_control(
    '_feature',
    [
        'label' => esc_html__( 'Feature', 'easy-elements' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__( 'Manufacturing Industrial', 'easy-elements' ),
        'label_block' => true,
    ]
);

$this->add_control(
    '_features',
    [
        'label' => esc_html__( 'Features', 'easy-elements' ),
        'type' => \Elementor\Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'condition' => [
            'info_skin' => 'skin-2'
        ],
        'default' => [
            [
                '_feature_icon' => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid'
                ],
                '_feature' => esc_html__( 'Manufacturing Industrial', 'easy-elements' ),
            ],
            [
                '_feature_icon' => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid'
                ],
                '_feature' => esc_html__( 'Supply Chain', 'easy-elements' ),
            ],
        ],
        'title_field' => '<i class="{{ _feature_icon.value }}"></i> {{{ _feature }}}',
    ]
);


        $this->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'easy-elements'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_control(
            'enable_box_link',
            [
                'label' => esc_html__('Enable Full Box Link', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'easy-elements'),
                'label_off' => esc_html__('No', 'easy-elements'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => esc_html__('Off korle sudhu Read More button clickable thakbe.', 'easy-elements'),
                'condition' => [
                    'link[url]!' => '',
                ],
            ]
        );


        $this->add_control(
            'show_read_more',
            [
                'label' => esc_html__('Show Read More', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'easy-elements'),
                'label_off' => esc_html__('Hide', 'easy-elements'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        
        $this->add_control(
            'read_more_type',
            [
                'label' => esc_html__('Read More Type', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'read_text' => esc_html__('Text', 'easy-elements'),
                    'read_icon' => esc_html__('Icon', 'easy-elements'),
                    'read_icon_to_text' => esc_html__('Icon Hover to Text Show', 'easy-elements'),
                ],
                'default' => 'read_text',
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Read More Text', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Read More', 'easy-elements'),
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );
        
        $this->add_control(
            'read_more_icon',
            [
                'label' => esc_html__('Read More Icon', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => 'read_icon',
                ],
            ]
        );

        $this->add_control(
            'read_more_text_icon',
            [
                'label' => esc_html__('Text Button Icon', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                    'read_more_text_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_text_icon_show',
            [
                'label' => esc_html__('Show Icon Next to Text', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'easy-elements'),
                'label_off' => esc_html__('Hide', 'easy-elements'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );

        $this->add_control(
            'read_more_alignment',
            [
                'label' => esc_html__( 'Button Alignment', 'easy-elements' ),
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
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'easy-elements' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'prefix_class' => 'eel-btn-align-',
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__( 'Title HTML Tag', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p'   => 'p',
                ],
            ]
        );

        $this->end_controls_section();  

        $this->start_controls_section(
            'section_styles_item',
            [
                'label' => esc_html__( 'Item', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_direction',
            [
                'label' => esc_html__( 'Direction', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'top',
                'options' => [                    
                    'left' => [
                        'title' => esc_html__( 'Left', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => esc_html__( 'Top', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
            ]
        );

        // Vertical Alignment Control
        $this->add_responsive_control(
            'icon_vertical_alignment',
            [
                'label' => esc_html__( 'Vertical Alignment', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'easy-elements' ),
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
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box.right, {{WRAPPER}} .ee--icon-box.left' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'icon_direction' => ['left', 'right'],
                    'info_skin!' => 'skin-2',
                    'icon_type' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            '_text_align',
            [
                'label' => esc_html__( 'Alignment', 'easy-elements' ),
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box, {{WRAPPER}} .eel-pro-number' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'icon_type' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            '_textimg_align',
            [
                'label' => esc_html__( 'Alignment', 'easy-elements' ),
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box, {{WRAPPER}} .eel-pro-number' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'icon_type' => 'image',
                ],
            ]
        );

        $this->add_responsive_control('item_spacing', [
            'label' => __('Item Spacing', 'easy-elements'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'condition' => [
                'icon_direction' => ['left', 'right'],
            ],
            'selectors' => [
                '{{WRAPPER}} .ee--icon-box.right, {{WRAPPER}} .ee--icon-box.left' => 'gap: {{SIZE}}px;',
            ],
        ]);

        $this->start_controls_tabs('item_background_tabs');

        $this->start_controls_tab('item_background_normal', [
            'label' => __('Normal', 'easy-elements'),
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_normal_bg',
                'label' => __('Background', 'easy-elements'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .ee--icon-box',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_normal_border',
                'label' => __('Border', 'easy-elements'),
                'selector' => '{{WRAPPER}} .ee--icon-box',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_normal_box_shadow',
                'label' => __('Box Shadow', 'easy-elements'),
                'selector' => '{{WRAPPER}} .ee--icon-box',
            ]
        );

        $this->add_responsive_control(
            'item_normal_border_radius',
            [
                'label' => __('Border Radius', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ee--icon-box::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_normal_padding',
            [
                'label' => __('Padding', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_normal_margin',
            [
                'label' => __('Margin', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('item_background_hover', [
            'label' => __('Hover', 'easy-elements'),
        ]);

        $this->add_control(
			'item_bg_hover_styles',
			[
				'label' => esc_html__( 'Background Direction', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'   => esc_html__( 'Default', 'easy-elements' ),
					'left'      => esc_html__( 'Left', 'easy-elements' ),
					'right'     => esc_html__( 'Right', 'easy-elements' ),
					'top'       => esc_html__( 'Top', 'easy-elements' ),
					'bottom'    => esc_html__( 'Bottom', 'easy-elements' ),
					'middle'    => esc_html__( 'Middle', 'easy-elements' ),
				],
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_hover_bg',
                'label' => __('Background', 'easy-elements'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .ee--icon-box:hover::before',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border_hover',
                'label' => __('Border', 'easy-elements'),
                'selector' => '{{WRAPPER}} .ee--icon-box:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow_hover',
                'label' => __('Box Shadow', 'easy-elements'),
                'selector' => '{{WRAPPER}} .ee--icon-box:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();   
        
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__( 'Image', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'icon_type' => 'image',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_size',
            [
                'label'      => esc_html__( 'Image Size', 'easy-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon.eel-icon-image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_box_size',
            [
                'label'      => esc_html__( 'Box Size', 'easy-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon.eel-icon-image' => 'min-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('image_style_tabs');

        $this->start_controls_tab('image_style_normal', [
            'label' => __('Normal', 'easy-elements'),
        ]);

        $this->add_control(
            'image_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon.eel-icon-image' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_bg_gradient',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ee--icon-box .eel-icon.eel-icon-image',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .ee--icon-box .eel-icon.eel-icon-image',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .ee--icon-box .eel-icon.eel-icon-image',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('image_style_hover', [
            'label' => __('Hover', 'easy-elements'),
        ]);

        $this->add_control(
            'image_hover_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box:hover .eel-icon.eel-icon-image' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_hover_bg_gradient',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ee--icon-box:hover .eel-icon.eel-icon-image',
            ]
        );

        $this->add_control(
            'image_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box:hover .eel-icon.eel-icon-image' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_hover_box_shadow',
                'selector' => '{{WRAPPER}} .ee--icon-box:hover .eel-icon.eel-icon-image',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'image_box_border_radius',
            [
                'label' => esc_html__( 'Box Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon.eel-icon-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image__border_radius',
            [
                'label' => esc_html__( 'Image Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon.eel-icon-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon.eel-icon-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon.eel-icon-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_vertical_alignment',
            [
                'label' => esc_html__( 'Vertical Alignment', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'easy-elements' ),
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
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box.right, {{WRAPPER}} .ee--icon-box.left' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'icon_direction' => ['left', 'right'],
                ],
            ]
        );

        $this->end_controls_section();  

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Icon', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'icon_type' => 'icon',
                ],
            ]
        );

        $this->start_controls_tabs('background_tabs');

        $this->start_controls_tab('background_normal', [
            'label' => __('Normal', 'easy-elements'),
        ]);

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon' => 'color: {{VALUE}}; fill-opacity: 1;',
                    '{{WRAPPER}} .ee--icon-box .eel-icon svg' => 'fill: {{VALUE}}; fill-opacity: 1;',
                    '{{WRAPPER}} .ee--icon-box .eel-icon svg path' => 'fill: {{VALUE}}; fill-opacity: 1;',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => esc_html__( 'Background', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background_gradiant',
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .ee--icon-box .eel-icon',
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'icon_typography',
                'label' => esc_html__( 'Icon Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .ee--icon-box .eel-icon',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .ee--icon-box .eel-icon',
            ]
        );


        $this->add_control(
            'icon__border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // gradient border color
        $this->add_control(
            'gradient_border',
            [
                'label' => esc_html__( 'Gradient Border', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_box_size',
            [
                'label'      => esc_html__( 'Box Size', 'easy-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 150,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon' => 'min-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .ee--icon-box .eel-icon',
            ]
        );

        $this->add_control(
            'icon_rotate',
            [
                'label' => esc_html__( 'Rotate', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => [
                        'min' => 0,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon svg, {{WRAPPER}} .ee--icon-box .eel-icon i' => 'transform: rotate({{SIZE}}deg);',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab('background_hover', [
            'label' => __('Hover', 'easy-elements'),
        ]);

        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box:hover .eel-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box:hover .eel-icon svg path' => 'fill: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'icon_hover_bg_color',
            [
                'label' => esc_html__( 'Background', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box:hover .eel-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'icon_hover_bg_gradient',
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .ee--icon-box:hover .eel-icon',
			]
		);
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_number',
            [
                'label' => esc_html__( 'Number', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'number_title!' => '',
                ],
            ]
        );   

        $this->add_control(
            'number_title_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-pro-number' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'number_gradeint' => '',
                ],
            ]
        );        

        $this->add_control(
            'number_bg_color',
            [
                'label' => esc_html__( 'Background', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-pro-number' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'number_title_background',
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel-pro-number',
                'condition' => [
                    'number_gradeint' => 'yes',
                    'number_title!' => ''
                ],
            ]
        );

        $this->add_responsive_control(
            'number_alignment',
            [
                'label' => esc_html__( 'Alignment', 'easy-elements' ),
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
                'selectors' => [
                    '{{WRAPPER}} .eel-pro-number' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'number_title_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-pro-number',
            ]
        );   

        $this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'number_title_text_stroke',
				'selector' => '{{WRAPPER}} .eel-pro-number',
			]
		);   
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'number_border',
                'selector' => '{{WRAPPER}} .eel-pro-number',
            ]
        );

        $this->add_control(
            'number_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-pro-number' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-pro-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-pro-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_size',
            [
                'label'      => esc_html__( 'Size', 'easy-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 150,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-pro-number, {{WRAPPER}} .eel-pro-number *' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('title_style');

        $this->start_controls_tab('title_normal', [
            'label' => __('Normal', 'easy-elements'),
        ]);

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-box-title' => 'color: {{VALUE}}; transition: all 0.3s ease-in;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => '_title_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .icon-box-title',
            ]
        );  

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .icon-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        ); 

        $this->end_controls_tab();

        $this->start_controls_tab('title_hover', [
            'label' => __('Hover', 'easy-elements'),
        ]);

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box:hover .icon-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();  
        
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('desc_style');

        $this->start_controls_tab('desc_normal', [
            'label' => __('Normal', 'easy-elements'),
        ]);

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'box_desc_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .icon-box-description',
            ]
        );
        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .icon-box-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        ); 

        $this->end_controls_tab();

        $this->start_controls_tab('desc_hover', [
            'label' => __('Hover', 'easy-elements'),
        ]);

        $this->add_control(
            'desc_hover_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box:hover .icon-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();



        $this->start_controls_section(
            'section_feature_style',
            [
                'label' => esc_html__( 'Features', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'feature_style_tabs' );

        $this->start_controls_tab(
            'feature_style_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'feature_style_color',
            [
                'label' => esc_html__( 'Text Color', 'easy-elements' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .feature-item span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'feature_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'easy-elements' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .feature-item i'        => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box .feature-item svg'      => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box .feature-item svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'feature_style_hover',
            [
                'label' => esc_html__( 'Hover', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'feature_style_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'easy-elements' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box:hover .feature-item span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'feature_icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'easy-elements' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box:hover .feature-item i'        => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box:hover .feature-item svg'      => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box:hover .feature-item svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'feature_style_typography',
                'selector' => '{{WRAPPER}} .ee--icon-box .feature-item span',
            ]
        );

        $this->add_responsive_control(
            'feature_style_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type'  => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .feature-item' =>
                        'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_icon_gap',
            [
                'label' => esc_html__( 'Icon Gap', 'easy-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .feature-item .eel-info-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .feature-item svg'            => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'feature_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'easy-elements' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .feature-item .eel-info-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .feature-item svg'            => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_read_more' => 'yes',
                ]
            ]
        );

        $this->start_controls_tabs('button_tabs_style');
        
        $this->start_controls_tab('button_normal_tab_style', [
            'label' => __('Normal', 'easy-elements'),
        ]);

        $this->add_control(
                'read_more_icon_color',
                [
                    'label' => esc_html__('Icon Color', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-read-more-icon svg' => 'fill: {{VALUE}};',
                        '{{WRAPPER}} .eel-read-more-icon svg path' => 'fill: {{VALUE}};',
                        '{{WRAPPER}} .eel-read-more-icon i' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'show_read_more' => 'yes',
                        'read_more_type' => 'read_icon',
                    ],
                ]
            );

            $this->add_control(
                'read_more_icon_bg_color',
                [
                    'label' => esc_html__('Icon Background Color', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ee--icon-box .eel-read-more-icon' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'show_read_more' => 'yes',
                        'read_more_type' => 'read_icon',
                    ],
                ]
            );

            $this->add_responsive_control(
                'read_more_icon_padding',
                [
                    'label' => esc_html__('Padding', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ee--icon-box .eel-read-more-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_read_more' => 'yes',
                        'read_more_type' => 'read_icon',
                    ],
                ]
            );

            $this->add_control(
                'border_radius_icon',
                [
                    'label' => __('Border Radius', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        '{{WRAPPER}} .ee--icon-box .eel-read-more-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_read_more' => 'yes',
                        'read_more_type' => 'read_icon',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'read_more_icon_size',
                [
                    'label' => esc_html__('Icon Size', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-read-more-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .eel-read-more-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_read_more' => 'yes',
                        'read_more_type' => 'read_icon',
                    ],
                ]
            );


            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'read_m_read_mr_typography',
                    'label' => esc_html__( 'Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-read-more-text',
                    'condition' => [
                        'show_read_more' => 'yes',
                        'read_more_type' => ['read_text','read_icon_to_text'],
                    ],
                ]
            ); 

            $this->add_control(
                'read_more_text_color',
                [
                    'label' => esc_html__('Text Color', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-read-more-text' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .eel-read-more-text svg' => 'fill: {{VALUE}};',
                        '{{WRAPPER}} .eel-read-more-text svg path' => 'fill: {{VALUE}};',
                    ],
                    'condition' => [
                        'show_read_more' => 'yes',
                        'read_more_type' => ['read_text','read_icon_to_text'],
                    ],
                ]
            );

            $this->add_control(
                'read_more_text_bg_color',
                [
                    'label' => esc_html__('Background Color', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-read-more-text' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'show_read_more' => 'yes',
                        'read_more_type' => ['read_text','read_icon_to_text'],
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'read_more_text_border',
                    'label' => esc_html__('Border', 'easy-elements'),
                    'selector' => '{{WRAPPER}} .eel-read-more-text, {{WRAPPER}} .eel-read-more-icon',
                    'condition' => [
                        'show_read_more' => 'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'top_btm',
                [
                    'label' => esc_html__( 'Top/Bottom', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-read-more-text-icon' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
                    ],
                    'condition' => [
                        'show_read_more' => 'yes',
                        'read_more_type' => ['read_text','read_icon_to_text'],
                        'read_more_text_icon_show' => 'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'icon_left_right',
                [
                    'label' => esc_html__( 'Left/Right', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-read-more-text-icon' => 'left: {{SIZE}}{{UNIT}}; position: relative;',
                    ],
                    'condition' => [
                        'show_read_more' => 'yes',
                        'read_more_type' => ['read_text','read_icon_to_text'],
                        'read_more_text_icon_show' => 'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'read_more_text_icon_size',
                [
                    'label' => esc_html__('Button Icon Size', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-read-more-text-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .eel-read-more-text-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_read_more' => 'yes',
                        'read_more_type' => ['read_text','read_icon_to_text'],
                        'read_more_text_icon_show' => 'yes',
                    ],
                ]
            );        

            $this->add_responsive_control(
                'read_more_text_border_radius',
                [
                    'label' => esc_html__('Border Radius', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-read-more-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_read_more' => 'yes',
                        'read_more_type' => ['read_text','read_icon_to_text'],
                    ],
                ]
            );

            $this->add_responsive_control(
                'read_more_text_padding',
                [
                    'label' => esc_html__('Padding', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-read-more-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; display: inline-block;',
                    ],
                    'condition' => [
                        'show_read_more' => 'yes',
                        'read_more_type' => ['read_text','read_icon_to_text'],
                    ],
                ]
            );
            $this->add_responsive_control(
                'read_more_icon_margin',
                [
                    'label' => esc_html__('Margin', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ee--icon-box .eel-read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );



            $this->add_responsive_control(
                'button_text_align',
                [
                    'label' => esc_html__( 'Alignment', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start' => [
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
                        'stretch' => [
                            'title' => esc_html__( 'Justified', 'easy-elements' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'default' => 'left',
                    'condition' => [
                        'info_skin' => 'skin-2',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-info-skin-2 .eel-read-more' => 'justify-content: {{VALUE}};',

                    ],
                ]
            );

       

        $this->end_controls_tab();

        $this->start_controls_tab( 'button_hover_tab_style', [
            'label' => __('Hover', 'easy-elements'),
        ]);
    
        $this->add_control(
            'read_more_text_color_hover',
            [
                'label' => esc_html__('Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-read-more-text:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel-read-more-text:hover svg' => 'fill: {{VALUE}};',  
                    '{{WRAPPER}} .eel-read-more-text:hover svg path' => 'fill: {{VALUE}};',                   
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );

        $this->add_control(
            'read_more_text_color_item_hover',
            [
                'label' => esc_html__('Hover Color For Item', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .eel-read-more-text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-widget-container:hover .eel-read-more-text svg' => 'fill: {{VALUE}};',  
                    '{{WRAPPER}} .elementor-widget-container:hover .eel-read-more-text svg path' => 'fill: {{VALUE}};',                   
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );    
        
        $this->add_control(
            'read_more_bg_hover',
            [
                'label' => esc_html__('Background', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-read-more-text:hover' => 'background: {{VALUE}};',                
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );

        $this->add_control(
            'read_more_text_bg_item_hover',
            [
                'label' => esc_html__('Background Color For Item', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .eel-read-more-text' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',            
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );  

        $this->add_control(
            'read_more_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-read-more-text:hover' => 'border-color: {{VALUE}};',                
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();        
            
        $this->end_controls_section();
    }

    protected function render() {
        $settings        = $this->get_settings_for_display();
        $gradient_border = ( isset( $settings['gradient_border'] ) && $settings['gradient_border'] === 'yes' ) ? 'ee--gradient-border' : '';
        $info_skin       = isset($settings['info_skin']) ? esc_attr($settings['info_skin']) : '';

        ?>
        <div class="eel-icon-box-wraps">
            <?php
                $icon_direction     = isset($settings['icon_direction']) ? esc_attr($settings['icon_direction']) : '';
                $link               = isset($settings['link']['url']) ? esc_url($settings['link']['url']) : '';
                $target             = ! empty( $settings['link']['is_external'] ) ? ' target="_blank"' : '';
                $nofollow           = ! empty( $settings['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                $item_hover_styles  = !empty( $settings['item_bg_hover_styles'] ) ? $settings['item_bg_hover_styles'] : '';
                // Old widgets settings e ei key thakbe na — tokhon default enabled (backward compat).
                $enable_box_link    = ! isset( $settings['enable_box_link'] ) || $settings['enable_box_link'] === 'yes';

            ?>

            <?php 
                $skin_file = plugin_dir_path(__FILE__) . 'skin/';
                switch ($info_skin) {
                    case 'default':
                        include $skin_file . 'default.php';
                        break;
                    case 'skin-2':
                        include $skin_file . 'skin-2.php';
                        break;
                    default:
                        include $skin_file . 'default.php';
                        break;
                }
            ?>
                      
        </div>
    <?php
    }
} 