<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

defined( 'ABSPATH' ) || die();

class Easyel_Single_Nav_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'eel-single-nav';
    }

    /**
     * Get widget title.
     *
     * Retrieve counter widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Single Navigation', 'easy-elements' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve counter widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'easyicon easyelIcon-navigation';
    }

    /**
     * Retrieve the list of scripts the counter widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.3.0
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_categories() {
        return [ 'easyelements_header_footer_category' ];
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return [ 'navigation', 'menu', 'nav', 'text' ];
    }

    public function get_style_depends() {
        return [
            'eel-single-nav',
        ];
    }


	protected function register_controls() {
        $this->start_controls_section(
            'section_single_nav',
            [
                'label' => esc_html__( 'Single Navigation', 'easy-elements' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'nav_text',
            [
                'label' => esc_html__( 'Enter Menu Text Here', 'easy-elements' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Menu Item', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'nav_link',
            [
                'label' => esc_html__( 'Enter Menu Link Here', 'easy-elements' ),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
                'options' => [ 'url', 'is_external', 'nofollow' ],
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $repeater->add_control(
            'nav_icon',
            [
                'label'   => esc_html__( 'Menu Icon', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => '',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'nav_items',
            [
                'label' => esc_html__( 'Single Page Navigation Items Here', 'easy-elements' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'nav_text' => 'Home',
                    ],
                    [
                        'nav_text' => 'About Us',
                    ],
                    [
                        'nav_text' => 'Services',
                    ],
                    [
                        'nav_text' => 'Contact Us',
                    ],
                ],
                'title_field' => '{{{ nav_text }}}',
            ]
        );

        $this->add_responsive_control(
            'nav_display',
            [
                'label' => __( 'Display', 'easy-elements' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex'  => [
                        'title' => __( 'Flex', 'easy-elements' ),
                        'icon'  => 'eicon-arrow-right',
                    ],
                    'block' => [
                        'title' => __( 'Block', 'easy-elements' ),
                        'icon'  => 'eicon-flex eicon-wrap',
                    ],
                    'none' => [
                        'title' => __( 'None', 'easy-elements' ),
                        'icon' => 'eicon-close',
                    ],
                ],
                'default' => '',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .eel-single-nav-list' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_alignment',
            [
                'label' => __( 'Alignment', 'easy-elements' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'easy-elements' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'easy-elements' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'easy-elements' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => '',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .eel-single-nav-list' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mobile_menu_breakpoint',
            [
                'label' => __( 'Mobile Menu Breakpoint', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''     => __( 'None (Disable Mobile Menu)', 'easy-elements' ),
                    '480'  => __( '480px - Small devices', 'easy-elements' ),
                    '576'  => __( '576px - Medium devices', 'easy-elements' ),
                    '768'  => __( '768px - Tablets', 'easy-elements' ),
                    '992'  => __( '992px - Desktop small', 'easy-elements' ),
                ],
                'description' => __( 'Select the screen width at which the mobile menu will activate.', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'mobile_hamburger_icon',
            [
                'label'            => esc_html__( 'Choose Hamburger Icon', 'easy-elements' ),
                'type'             => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default'          => [],
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ]
            ]
        );

        $this->add_control(
            'mobile_close_icon',
            [
                'label'            => esc_html__( 'Choose Close Icon', 'easy-elements' ),
                'type'             => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default'          => [],
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ]
            ]
        );

        $this->add_control(
			'enable_sticky_header',
			[
				'label'        => __( 'Enable Sticky Header', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements' ),
				'label_off'    => __( 'No', 'easy-elements' ),
				'frontend_available' => true,
				'default'      => 'no',
                'prefix_class' => 'eel-sticky-header-',
			]
		);

		$this->add_control(
			'fixed_top_sticky',
			[
				'label'        => __( 'Fixed Top Sticky', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements' ),
				'label_off'    => __( 'No', 'easy-elements' ),
				'frontend_available' => true,
				'default'      => 'yes',
				'condition' => [
					'enable_sticky_header' => 'yes',
				],
			]
		);

        
        $this->start_controls_tabs(
            'nav_sticky_color_tabs',
            [
                'condition' => [
                    'enable_sticky_header' => 'yes',
                ],
            ]
        );

        $this->start_controls_tab(
            'nav_sticky_color_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-elements' ),
                'condition' => [
                    'enable_sticky_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
			'bg_color_sticky_normal',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'.eel-single-nav-sticky-enabled' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'enable_sticky_header' => 'yes',
				],
			]
		);  
        
        $this->add_control(
			'disable_top_padding',
			[
				'label'        => __( 'Enable Top Padding', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements' ),
				'label_off'    => __( 'No', 'easy-elements' ),
				'frontend_available' => true,
				'default'      => 'yes',
				'condition' => [
					'enable_sticky_header' => 'yes',
				],
			]
		);

        $this->add_responsive_control(
			'dynamic_top_padding_value',
			[
				'label' => __( 'Custom Top Padding', 'easy-elements' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'condition' => [
					'disable_top_padding'   => '',
					'enable_sticky_header'  => 'yes',
				],
				'selectors' => [
					'body #page' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'nav_sticky_color_sticky',
            [
                'label' => esc_html__( 'Sticky', 'easy-elements' ),
                'condition' => [
                    'enable_sticky_header' => 'yes',
                ],
            ]
        );
        $this->add_control(
			'text_color_sticky',
			[
				'label'     => __( 'Text Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'header.eel-sticky-header-on.eel-up-scroll .eel-single-nav-sticky-enabled .eel-single-nav-list li a, header.eel-sticky-header-on.eel-up-scroll .eel-single-nav-sticky-enabled *, header.eel-sticky-header-on.eel--fixed-top-sticky .eel-single-nav-sticky-enabled .eel-single-nav-list li a, header.eel-sticky-header-on.eel--fixed-top-sticky eel-single-nav-sticky-enabled *' => 'color: {{VALUE}}',
                    'header.eel-sticky-header-on.eel-up-scroll .eel-single-nav-sticky-enabled .eel-icon-menu, header.eel-sticky-header-on.eel--fixed-top-sticky .eel-single-nav-sticky-enabled .eel-icon-menu' => 'border-bottom-color: {{VALUE}} !important; transition: none !important;',
                    'header.eel-sticky-header-on.eel-up-scroll .eel-single-nav-sticky-enabled .eel-single-nav .eel-nav-menu-icon svg path, header.eel-sticky-header-on.eel--fixed-top-sticky .eel-single-nav-sticky-enabled .eel-single-nav .eel-nav-menu-icon svg path' => 'fill: {{VALUE}} !important;',
				],
				'condition' => [
					'enable_sticky_header' => 'yes',
				],
			]
		);		
        $this->add_control(
			'bg_color_sticky',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'header.eel-sticky-header-on.eel-up-scroll .eel-single-nav-sticky-enabled, header.eel-sticky-header-on.eel--fixed-top-sticky .eel-single-nav-sticky-enabled, header.eel-sticky-header-on.eel-up-scroll .eel-nav-menu__layout-horizontal .eel-nav-menu .sub-menu:not(.easyel--elementor-template-mega-menu)' => 'background-color: {{VALUE}} !important',
				],
				'condition' => [
					'enable_sticky_header' => 'yes',
				],
			]
		);
        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => 'body header.eel-sticky-header-on.eel-up-scroll, body header.eel-sticky-header-on.eel--fixed-top-sticky',
				'condition' => [
					'enable_sticky_header' => 'yes',
				],
			]
		);
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'section_nav_style',
            [
                'label' => esc_html__( 'Navigation Style', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'nav_color_tabs' );

        $this->start_controls_tab(
            'nav_color_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'nav_text_color',
            [
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-single-nav-list .eel-nav-item a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'nav_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-single-nav-list .eel-nav-item a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'nav_typography',
                'label'    => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-single-nav-list .eel-nav-item a',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'nav_text_shadow',
                'label'    => esc_html__( 'Text Shadow', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-single-nav-list .eel-nav-item a',
            ]
        );

        $this->add_responsive_control(
            'nav_padding',
            [
                'label'      => esc_html__( 'Padding', 'easy-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-single-nav-list .eel-nav-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_margin',
            [
                'label'      => esc_html__( 'Margin', 'easy-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-single-nav-list .eel-nav-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'nav_color_hover',
            [
                'label' => esc_html__( 'Hover', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'nav_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-single-nav-list .eel-nav-item a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'nav_bg_color_hover',
            [
                'label'     => esc_html__( 'Background Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-single-nav-list .eel-nav-item:hover a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        /* Active */
        $this->start_controls_tab(
            'nav_color_active',
            [
                'label' => esc_html__( 'Active', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'nav_active_color',
            [
                'label'     => esc_html__( 'Active Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-single-nav-list .eel-nav-item.active a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'nav_bg_color_active',
            [
                'label'     => esc_html__( 'Background Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-single-nav-list .eel-nav-item.active a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'nav_icon_style',
            [
                'label'     => esc_html__( 'Icon', 'easy-elements' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'nav_icon_color_tabs' );

        $this->start_controls_tab(
            'nav_icon_color_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-elements' ),
            ]
        );
        $this->add_control(
            'single_nav_icon_color',
            [
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-single-nav-list .eel-nav-item a svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_icon_size',
            [
                'label' => __( 'Icon Size', 'easy-elements' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 8,
                        'max' => 80,
                    ],
                ],
                'default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-single-nav-list .eel-nav-item i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-single-nav-list .eel-nav-item svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_icon_gap',
            [
                'label' => __( 'Gap', 'easy-elements' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-nav-item a' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'nav_icon_color_hover',
            [
                'label' => esc_html__( 'Hover', 'easy-elements' ),
            ]
        );
        $this->add_control(
            'single_nav_icon_color_hover',
            [
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-single-nav-list .eel-nav-item:hover a svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'nav_icon_color_active',
            [
                'label' => esc_html__( 'Active', 'easy-elements' ),
            ]
        );
        $this->add_control(
            'single_nav_icon_color_active',
            [
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-single-nav-list .eel-nav-item.active a svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'section_mobile_style',
            [
                'label' => esc_html__( 'Mobile Style', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        // Start Tabs Wrapper
        $this->start_controls_tabs( 'mobile_text_tabs' );

        // --- NORMAL STATE ---
        $this->start_controls_tab(
            'mobile_text_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'mobile_text_color',
            [
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sidebar-on-mobile-single-nav .eel-single-nav-list li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // --- HOVER STATE ---
        $this->start_controls_tab(
            'mobile_text_hover',
            [
                'label' => esc_html__( 'Hover', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'mobile_text_hover_color',
            [
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sidebar-on-mobile-single-nav .eel-single-nav-list li:hover a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // --- ACTIVE STATE ---
        $this->start_controls_tab(
            'mobile_text_active',
            [
                'label' => esc_html__( 'Active', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'mobile_text_active_color',
            [
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sidebar-on-mobile-single-nav .eel-single-nav-list li a.eel-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Background Color
        $this->add_control(
            'mobile_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sidebar-on-mobile-single-nav' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'mobile_typography',
                'selector' => '{{WRAPPER}} .sidebar-on-mobile-single-nav .eel-single-nav-list li a',
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        // Padding (Responsive)
        $this->add_responsive_control(
            'mobile_padding',
            [
                'label'      => esc_html__( 'Padding', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .sidebar-on-mobile-single-nav .eel-single-nav-list li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        $this->add_control(
            'mobile_humburger_icon_heading',
            [
                'label'     => esc_html__( 'Humburger Icon', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        // Humburger Icon Color
        $this->add_control(
            'mobile_humburger_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-single-nav .eel-nav-menu-icon i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel-single-nav .eel-nav-menu-icon svg path' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        // Humburger Icon Size (Responsive)
        $this->add_responsive_control(
            'mobile_humburger_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-single-nav .eel-nav-menu-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-single-nav .eel-nav-menu-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        $this->add_control(
            'mobile_close_icon_heading',
            [
                'label'     => esc_html__( 'Close Icon', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        // Close Icon Color
        $this->add_control(
            'mobile_close_icon_color',
            [
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sidebar-on-mobile-single-nav .eel-nav-menu-icon, {{WRAPPER}} .sidebar-on-mobile-single-nav .eel-nav-menu-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        $this->add_responsive_control(
            'mobile_close_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .sidebar-on-mobile-single-nav .eel-nav-menu-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .sidebar-on-mobile-single-nav .eel-nav-menu-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        // Close Icon Border Control
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'mobile_close_icon_border',
                'label'    => esc_html__( 'Border', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .sidebar-on-mobile-single-nav .eel-nav-menu-icon',
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        // Close Icon Border Radius (Optional but useful for circular icons)
        $this->add_responsive_control(
            'mobile_close_icon_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .sidebar-on-mobile-single-nav .eel-nav-menu-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings['nav_items'] ) ) {
            return;
        }
        $enableSticky  = isset($settings['enable_sticky_header']) && 'yes' === $settings['enable_sticky_header'];
	    $enablePadding = isset($settings['disable_top_padding']) && 'yes' === $settings['disable_top_padding'];

        if ( isset( $settings['enable_sticky_header'] ) && 'yes' === $settings['enable_sticky_header'] ) {
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
            $GLOBALS['easyel_force_sticky_header'] = true; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
            wp_enqueue_script(
                'eel-sticky-header',
                plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'widgets/single-nav/js/eel-sticky-header.js',
                [ 'jquery' ],
                EASYELEMENTS_VER,
                true
            );

            wp_localize_script('eel-sticky-header', 'eelStickyHeaderSettings', [
                'enableSticky'  => $enableSticky,
                'enablePadding' => $enablePadding
            ]);
        }

        if ( 'yes' === $settings['fixed_top_sticky'] ) {
			$this->add_render_attribute( 'eel-main-menu', 'class', 'eel-fixed-top-sticky' );		
			$inline_js = "
				document.addEventListener('DOMContentLoaded', function() {
					var header = document.querySelector('header.easy-site-header');
					if (header) {
						header.classList.add('eel-fixed-top-sticky');
					}
				});
			";
			wp_add_inline_script( 'eel-sticky-header', $inline_js );
		}        
        ?>
        <?php $bp_class = ( isset( $settings['mobile_menu_breakpoint'] ) && '' !== $settings['mobile_menu_breakpoint'] ) ? ' eel-mobile-bp-' . esc_attr( $settings['mobile_menu_breakpoint'] ) : ''; ?>
        <nav class="eel-single-nav <?php echo esc_attr( $bp_class ); ?>">
            <ul class="eel-single-nav-list">
                <?php $i = 0; foreach ( $settings['nav_items'] as $item ) :
                    $active_class = ( $i === 0 ) ? 'active' : '';
                    $target   = ! empty( $item['nav_link']['is_external'] ) ? '_blank' : '';
                    $nofollow = ! empty( $item['nav_link']['nofollow'] ) ? 'nofollow' : '';
                    $nav_icon = ! empty( $item['nav_icon']['value'] ) ? 'eel-has-icon-link' : '';
                    ?>
                    <li class="eel-nav-item <?php echo esc_attr( $active_class ); ?>">
                        <a 
                            href="<?php echo esc_url( $item['nav_link']['url'] ); ?>"
                            <?php if ( $target ) : ?>
                                target="<?php echo esc_attr( $target ); ?>"
                            <?php endif; ?>
                            <?php if ( $nofollow ) : ?>
                                rel="<?php echo esc_attr( $nofollow ); ?>"
                            <?php endif; ?>
                         class="<?php echo esc_attr( $nav_icon ); ?>">
                        <?php if ( ! empty( $item['nav_icon']['value'] ) ) : ?>
                            <span class="eel-nav-icon-menu">
                                <?php \Elementor\Icons_Manager::render_icon(
                                    $item['nav_icon'],
                                    [ 'aria-hidden' => 'true' ]
                                ); ?>
                            </span>
                        <?php endif; ?>
                            <?php echo esc_html( $item['nav_text'] ); ?>
                        </a>
                    </li>
                <?php $i++; endforeach; ?>
            </ul>
            <?php if ( isset( $settings['mobile_menu_breakpoint'] ) && '' !== $settings['mobile_menu_breakpoint'] ) : ?>
                <div class="eel-nav-menu-icon">
                    <?php
                        if ( ! empty( $settings['mobile_hamburger_icon']['value'] ) ) {
                            \Elementor\Icons_Manager::render_icon( $settings['mobile_hamburger_icon'], [ 'aria-hidden' => 'true' ] );
                        } else { 
                    ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 4H21V6H3V4ZM3 11H15V13H3V11ZM3 18H21V20H3V18Z"></path></svg>
                    <?php } ?>
                </div>
            <?php endif; ?>
        </nav>

        <?php   
        static $mega_mobile_rendered = false;
		if ( ! $mega_mobile_rendered ) {
			$mega_mobile_rendered = true;       
            $settings = $this->get_settings_for_display(); 
            if ( isset( $settings['mobile_menu_breakpoint'] ) && '' !== $settings['mobile_menu_breakpoint'] ) :
            $this->add_render_attribute( 'eel-nav-single-menu-mobile', 'class', 'sidebar-on-mobile-single-nav' );				
            $widget_instance = $this;           
                ?>
                <nav <?php $widget_instance->print_render_attribute_string( 'eel-nav-single-menu-mobile' ); ?>>
                    <span class="eel-nav-menu-icon">
                        <?php
                            if ( ! empty( $settings['mobile_close_icon']['value'] ) ) {                               
                                \Elementor\Icons_Manager::render_icon( $settings['mobile_close_icon'], [ 'aria-hidden' => 'true' ] );
                            } else { ?>
                                <i class="unicon-close"></i>
                            <?php }
                        ?>
                    </span>
                    <ul class="eel-single-nav-list eel-single-nav-list-mobile">
                        <?php 
                        $nav_items = $settings['nav_items'] ?? [];
                        foreach ( $nav_items as $i => $item ) :
                            $active_class = ( $i === 0 ) ? 'active' : '';
                            $target   = ! empty( $item['nav_link']['is_external'] ) ? '_blank' : '';
                            $nofollow = ! empty( $item['nav_link']['nofollow'] ) ? 'nofollow' : '';
                        ?>
                        <li class="eel-nav-item <?php echo esc_attr( $active_class ); ?>">
                            <a 
                                href="<?php echo esc_url( $item['nav_link']['url'] ); ?>"
                                <?php if ( $target ) : ?> target="<?php echo esc_attr( $target ); ?>" <?php endif; ?>
                                <?php if ( $nofollow ) : ?> rel="<?php echo esc_attr( $nofollow ); ?>" <?php endif; ?>
                            >
                                <?php echo esc_html( $item['nav_text'] ); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
                <?php       
            endif;
        }
        ?>
        
        <?php
    }
}