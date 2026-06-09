<?php
namespace Easyel\EasyElements\Widgets;	
if ( ! defined( 'ABSPATH' ) ) exit;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Plugin;

// Include the Menu_Walker class
require_once plugin_dir_path( __FILE__ ) . 'nav-walker.php';

/**
 * Class Nav Menu.
 */
class Easyel_vertical_Menu_Widget extends \Elementor\Widget_Base  {
	protected $nav_menu_index = 1;
	public function get_name() {
		return 'eel-vertical-navigation';
	}

	public function get_title() {
		return __( 'Vertical Menu', 'easy-elements' );
	}

	public function get_icon() {
		return 'easyicon easyelIcon-navigation';
	}

	public function get_categories() {
		return [ 'easyelements_header_footer_category' ];
	}

	public function get_style_depends() {
        return [
            'eel-vertical-navigation',
        ];
    }

	/**
	 * Retrieve the menu index.
	 *
	 * Used to get index of nav menu.
	 *
	 * @since 1.3.0
	 * @access protected
	 *
	 * @return string nav index.
	 */
	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

	/**
	 * Retrieve the list of available menus.
	 *
	 * Used to get the list of available menus.
	 *
	 * @since 1.3.0
	 * @access private
	 *
	 * @return array get WordPress menus list.
	 */
	private function get_available_menus() {

		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Check if the Elementor is updated.
	 *
	 * @since 1.3.0
	 *
	 * @return boolean if Elementor updated.
	 */
	public static function is_elementor_updated() {
		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Register Nav Menu controls.
	 *
	 * @since 1.5.7
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_general_content_controls();
		$this->register_style_content_controls();
	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_general_content_controls() {

		$this->start_controls_section(
			'section_menu',
			[
				'label' => __( 'Menu', 'easy-elements' ),
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label'        => __( 'Menu', 'easy-elements' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					/* translators: %s Nav menu URL */
					'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'easy-elements' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s Nav menu URL */
					'raw'             => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'easy-elements' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}
		$this->add_control(
			'enable_menu_description',
			[
				'label'        => __( 'Show Description', 'easy-elements' ),
				'type'         => Controls_Manager::SELECT,
				'options'   => [
					'none' => __( 'No', 'easy-elements' ),
					'inline-block'  => __( 'Yes', 'easy-elements' ),
				],
				'default'   => 'none',
				'selectors'          => [
					'{{WRAPPER}} li.menu-item .menu-desc' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_icon_position',
			[
				'label'   => __( 'Item Icon Position', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'   => __( 'None', 'easy-elements' ),
					'before' => __( 'Before Item', 'easy-elements' ),
					'after'  => __( 'After Item', 'easy-elements' ),
				],
			]
		);

		$this->add_control(
			'menu_item_position_icon',
			[
				'label'     => __( 'Item Icon', 'easy-elements' ),
				'type'      => Controls_Manager::ICONS,
				'condition' => [
					'menu_icon_position!' => 'none',
				],
			]
		);

		$this->add_control(
			'menu_icon_show_on',
			[
				'label'        => __( 'Show Icon On', 'easy-elements' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'all',
				'options'      => [
					'all'    => __( 'All Items', 'easy-elements' ),
					'active' => __( 'Active Item Only', 'easy-elements' ),
				],
				'prefix_class' => 'eel-icon-show-',
				'condition'    => [
					'menu_icon_position!' => 'none',
				],
			]
		);

		$menutype = [
			'eel_hover' => __( 'Hover', 'easy-elements' ),
		];

		if ( apply_filters( 'easyel/pro_enabled', false ) ) {
			$menutype['eel_click'] = __( 'Click', 'easy-elements' );
		}

		$this->add_control(
			'submenu_type',
			[
				'label'        => __( 'Dropdown Type', 'easy-elements' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'eel_hover',
				'options'      =>  $menutype,
			]
		);

		$this->add_control('eel_position_fixed', [
			'label' => __('Position Fixed', 'easy-elements'),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => __('Yes', 'easy-elements'),
			'label_off' => __('No', 'easy-elements'),
			'default' => '',
			'condition' => [
				'submenu_type' => 'eel_click',
			]
		]);

		$animationOptions = [
			'eel_animation_slide_down' => __( 'Animation Down', 'easy-elements' ),
		];

		if ( apply_filters( 'easyel/pro_enabled', false ) ) {
			$animationOptions['eel_animation_slide_up'] = __( 'Animation Up', 'easy-elements' );
			$animationOptions['eel_animation_fade']     = __( 'Animation Fade', 'easy-elements' );
			$animationOptions['eel_animation_right']    = __( 'Animation Right', 'easy-elements' );
			$animationOptions['eel_animation_left']     = __( 'Animation Left', 'easy-elements' );
			$animationOptions['eel_animation_zoom']     = __( 'Animation Zoom', 'easy-elements' );
		}

		$this->add_control(
			'submenu_animation',
			[
				'label'        => __( 'Dropdown Animation', 'easy-elements' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'eel_animation_slide_down',
				'options'      => $animationOptions,
				'condition' => [
					'submenu_type' => 'eel_hover',
				]
			]
		);		

		$this->add_control(
            'mobile_menu_breakpoint',
            [
                'label' => __( 'Mobile Menu Breakpoint', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => '992',
                'options' => [
                    ''     => __( 'None - Disable Mobile Menu', 'easy-elements' ),
                    '480'  => __( '480px - Small devices', 'easy-elements' ),
                    '576'  => __( '576px - Medium devices', 'easy-elements' ),
                    '768'  => __( '768px - Tablets', 'easy-elements' ),
                    '992'  => __( '992px - Desktop small', 'easy-elements' ),
                ],
                'description' => __( 'Select the screen width at which the mobile menu will activate.', 'easy-elements' ),
				'separator' => 'before',
            ]
        );

		$this->add_control(
			'mobile_menu_open_position',
			[
				'label'        => __( 'Mobile Menu Open Position', 'easy-elements' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'right',
				'options'      => [
					'top'   => __( 'Top', 'easy-elements' ),
					'right' => __( 'Right', 'easy-elements' ),
				],
				'condition' => [
					'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
				],
			]
		);

		$this->add_responsive_control(
			'mobile_menu_sidebar_width',
			[
				'label'      => __( 'Menu Sidebar Width', 'easy-elements' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 500,
					],
					'%' => [
						'min' => 50,
						'max' => 100,
					],
					'vw' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .sidebar-on-mobile-vertical-nav' => 'max-width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
				],
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
		$this->end_controls_section();
	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_style_content_controls() {

		$this->start_controls_section(
			'section_style_main-menu',
			[
				'label'     => __( 'Main Menu', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
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
                    '{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item > a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'nav_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'nav_typography',
                'label'    => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item > a',
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item__border',
				'selector' => '{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item > a',
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'nav_text_shadow',
                'label'    => esc_html__( 'Text Shadow', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item > a',
            ]
        );

        $this->add_responsive_control(
            'nav_padding',
            [
                'label'      => esc_html__( 'Padding', 'easy-elements' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'nav_menu_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item > a' =>
						'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item:hover > a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'nav_bg_color_hover',
            [
                'label'     => esc_html__( 'Background Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item:hover > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item__border_hover',
				'selector' => '{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item:hover > a',
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
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item.current-menu-item > a, {{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item.current_page_item > a, {{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item.current-menu-ancestor > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_bg_color_active',
            [
                'label'     => esc_html__( 'Background Color', 'easy-elements' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item.current-menu-item > a, {{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item.current_page_item > a, {{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item.current-menu-ancestor > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item__border_active',
				'selector' => '{{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item.current-menu-item > a, {{WRAPPER}} .eel-vertical-menu-wrap .menu > .menu-item.current_page_item > a',
			]
		);
        $this->end_controls_tab();
        $this->end_controls_tabs();	
		$this->end_controls_section();
		do_action( 'easyel_vertical_after_submenu_fields_el', $this );

		$this->start_controls_section(
			'section_style_dropdown',
			[
				'label' => esc_html__( 'Dropdown Menu', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'dropdown_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part), {{WRAPPER}} .eel-vertical-menu-area .eel-vertical-menu-wrap ul.eel-vertical-verticalmenu .menu-item-has-children .sub-menu:not(.eel-vertical-menu-content-part)' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'dropdown_border',
				'selector' => '{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part), {{WRAPPER}} .eel-vertical-menu-area .eel-vertical-menu-wrap ul.eel-vertical-verticalmenu .menu-item-has-children .sub-menu:not(.eel-vertical-menu-content-part)',
			]
		);

		$this->add_responsive_control(
			'dropdown_padding',
			[
				'label' => esc_html__( 'Padding', 'easy-elements' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part), {{WRAPPER}} .eel-vertical-menu-area .eel-vertical-menu-wrap ul.eel-vertical-verticalmenu .menu-item-has-children .sub-menu:not(.eel-vertical-menu-content-part)' =>
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part),
					{{WRAPPER}} .eel-vertical-menu-area .eel-vertical-menu-wrap ul.eel-vertical-verticalmenu 
					.menu-item-has-children .sub-menu:not(.eel-vertical-menu-content-part)' =>
						'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'item_title',
			[
				'label' => esc_html__( 'Dropdown Item', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		/* =========================
		* Dropdown Item Tabs
		* ========================= */
		$this->start_controls_tabs( 'dropdown_item_tabs' );

		/* Normal */
		$this->start_controls_tab(
			'dropdown_item_normal',
			[
				'label' => esc_html__( 'Normal', 'easy-elements' ),
			]
		);

		$this->add_control(
			'dropdown_item_color',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_item_bg',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'dropdown_typography',
				'selector' => '{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li > a',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'dropdown_item_border',
				'selector' =>'{{WRAPPER}} .eel-vertical-menu-area ul.eel-vertical-verticalmenu ul.sub-menu:not(.eel-vertical-menu-content-part) li+li',
			]
		);

		$this->add_responsive_control(
			'dropdown_item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li > a' =>
						'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'per_item_padding',
			[
				'label' => esc_html__( 'Padding', 'easy-elements' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li > a' =>'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'width_dropdown_full',
			[
				'label' => __( 'Dropdown Width', 'easy-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-area .eel-vertical-menu-wrap ul.eel-vertical-verticalmenu .menu-item-has-children .sub-menu:not(.eel-vertical-menu-content-part)'
					=> 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_tab();

		/* Hover */
		$this->start_controls_tab(
			'dropdown_item_hover',
			[
				'label' => esc_html__( 'Hover', 'easy-elements' ),
			]
		);

		$this->add_control(
			'dropdown_item_hover_color',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li:hover > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_item_hover_bg',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li:hover > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_item_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li:hover > a' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		/* Active */
		$this->start_controls_tab(
			'dropdown_item_active',
			[
				'label' => esc_html__( 'Active', 'easy-elements' ),
			]
		);

		$this->add_control(
			'dropdown_item_active_color',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li.current-menu-item > a, {{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li.current_page_item > a, {{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li.current-menu-ancestor > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_item_active_bg',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li.current-menu-item > a, {{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li.current_page_item > a, {{WRAPPER}} .eel-vertical-menu-wrap .sub-menu:not(.eel-vertical-menu-content-part) li.current-menu-ancestor > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_menu_icon',
			[
				'label' => esc_html__( 'Menu Item Icon', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_item_text_colors',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-menu-text-inner>i' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_item_space',
			[
				'label' => esc_html__( 'Spacing', 'easy-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [ 'min' => -20, 'max' => 20 ],
					'%'  => [ 'min' => -100, 'max' => 100 ],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-menu-text-inner>i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_item_top',
			[
				'label' => esc_html__( 'Spacing Top/Bottom', 'easy-elements' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -30,
						'max' => 30,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-menu-text-inner>i' => 'top: {{SIZE}}{{UNIT}}; position: relative; bottom: auto;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_before_after_icon',
			[
				'label' => esc_html__( 'Before / After Icon', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'before_icon_heading',
			[
				'label' => esc_html__( 'Before Icon', 'easy-elements' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->start_controls_tabs( 'before_icon_tabs' );

			$this->start_controls_tab( 'before_icon_normal_tab', [ 'label' => esc_html__( 'Normal', 'easy-elements' ) ] );
				$this->add_control(
					'before_icon_color',
					[
						'label' => esc_html__( 'Color', 'easy-elements' ),
						'type'  => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .eel-menu-before-icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .eel-menu-before-icon svg' => 'fill: {{VALUE}};',
						],
					]
				);
			$this->end_controls_tab();

			$this->start_controls_tab( 'before_icon_hover_tab', [ 'label' => esc_html__( 'Hover', 'easy-elements' ) ] );
				$this->add_control(
					'before_icon_color_hover',
					[
						'label' => esc_html__( 'Color', 'easy-elements' ),
						'type'  => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .menu-item:hover > a .eel-menu-before-icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .menu-item:hover > a .eel-menu-before-icon svg' => 'fill: {{VALUE}};',
						],
					]
				);
			$this->end_controls_tab();

			$this->start_controls_tab( 'before_icon_active_tab', [ 'label' => esc_html__( 'Active', 'easy-elements' ) ] );
				$this->add_control(
					'before_icon_color_active',
					[
						'label' => esc_html__( 'Color', 'easy-elements' ),
						'type'  => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .menu-item.current-menu-item > a .eel-menu-before-icon, {{WRAPPER}} .menu-item.current-menu-ancestor > a .eel-menu-before-icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .menu-item.current-menu-item > a .eel-menu-before-icon svg, {{WRAPPER}} .menu-item.current-menu-ancestor > a .eel-menu-before-icon svg' => 'fill: {{VALUE}};',
						],
					]
				);
			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'before_icon_size',
			[
				'label' => esc_html__( 'Size', 'easy-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px'  => [ 'min' => 6, 'max' => 80 ],
					'em'  => [ 'min' => 0.2, 'max' => 5, 'step' => 0.1 ],
					'rem' => [ 'min' => 0.2, 'max' => 5, 'step' => 0.1 ],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-menu-before-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-menu-before-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'before_icon_spacing',
			[
				'label' => esc_html__( 'Spacing (Right)', 'easy-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 50 ],
					'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.1 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-menu-before-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'before_icon_offset_y',
			[
				'label' => esc_html__( 'Offset Top/Bottom', 'easy-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [ 'min' => -30, 'max' => 30 ],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-menu-before-icon' => 'position: relative; top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'after_icon_heading',
			[
				'label' => esc_html__( 'After Icon', 'easy-elements' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'after_icon_tabs' );

			$this->start_controls_tab( 'after_icon_normal_tab', [ 'label' => esc_html__( 'Normal', 'easy-elements' ) ] );
				$this->add_control(
					'after_icon_color',
					[
						'label' => esc_html__( 'Color', 'easy-elements' ),
						'type'  => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .eel-menu-after-icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .eel-menu-after-icon svg' => 'fill: {{VALUE}};',
						],
					]
				);
			$this->end_controls_tab();

			$this->start_controls_tab( 'after_icon_hover_tab', [ 'label' => esc_html__( 'Hover', 'easy-elements' ) ] );
				$this->add_control(
					'after_icon_color_hover',
					[
						'label' => esc_html__( 'Color', 'easy-elements' ),
						'type'  => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .menu-item:hover > a .eel-menu-after-icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .menu-item:hover > a .eel-menu-after-icon svg' => 'fill: {{VALUE}};',
						],
					]
				);
			$this->end_controls_tab();

			$this->start_controls_tab( 'after_icon_active_tab', [ 'label' => esc_html__( 'Active', 'easy-elements' ) ] );
				$this->add_control(
					'after_icon_color_active',
					[
						'label' => esc_html__( 'Color', 'easy-elements' ),
						'type'  => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .menu-item.current-menu-item > a .eel-menu-after-icon, {{WRAPPER}} .menu-item.current-menu-ancestor > a .eel-menu-after-icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .menu-item.current-menu-item > a .eel-menu-after-icon svg, {{WRAPPER}} .menu-item.current-menu-ancestor > a .eel-menu-after-icon svg' => 'fill: {{VALUE}};',
						],
					]
				);
			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'after_icon_size',
			[
				'label' => esc_html__( 'Size', 'easy-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px'  => [ 'min' => 6, 'max' => 80 ],
					'em'  => [ 'min' => 0.2, 'max' => 5, 'step' => 0.1 ],
					'rem' => [ 'min' => 0.2, 'max' => 5, 'step' => 0.1 ],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-menu-after-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-menu-after-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'after_icon_spacing',
			[
				'label' => esc_html__( 'Spacing (Left)', 'easy-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 50 ],
					'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.1 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-menu-after-icon' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'after_icon_offset_y',
			[
				'label' => esc_html__( 'Offset Top/Bottom', 'easy-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [ 'min' => -30, 'max' => 30 ],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-menu-after-icon' => 'position: relative; top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_menu_arrow_icon',
			[
				'label' => esc_html__( 'Dropdown Arrow', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_arrow_item_text_colors',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-area .eel-vertical-menu-wrap ul.eel-vertical-verticalmenu .menu-item-has-children .menu-link .submenu-parent-icon svg path' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_arrow_item_space',
			[
				'label' => esc_html__( 'Spacing', 'easy-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [ 'min' => -20, 'max' => 20 ],
					'%'  => [ 'min' => -100, 'max' => 100 ],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-area .eel-vertical-menu-wrap ul.eel-vertical-verticalmenu .menu-item-has-children .menu-link .submenu-parent-icon svg' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_top',
			[
				'label' => esc_html__( 'Spacing Top/Bottom', 'easy-elements' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -30,
						'max' => 30,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-vertical-menu-area .eel-vertical-menu-wrap ul.eel-vertical-verticalmenu .menu-item-has-children .menu-link .submenu-parent-icon svg' => 'top: {{SIZE}}{{UNIT}}; position: relative; bottom: auto;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_menu_badge',
			[
				'label' => esc_html__( 'Menu Item Badge', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'badge_text_colors',
			[
				'label' => esc_html__( 'Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-menu-badge' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-menu-badge' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .eel-menu-badge',
			]
		);

		$this->add_responsive_control(
			'badge_padding',
			[
				'label' => esc_html__( 'Padding', 'easy-elements' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eel-menu-badge' =>
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'badge_border',
				'selector' => '{{WRAPPER}} .eel-menu-badge',
			]
		);

		$this->add_control(
			'badge_radius',
			[
				'label' => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eel-menu-badge' =>
						'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'badge_top',
			[
				'label' => esc_html__( 'Top/Bottom', 'easy-elements' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-menu-badge' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'badge_left',
			[
				'label' => esc_html__( 'Left/Right', 'easy-elements' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-menu-badge' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_mobile_menu',
			[
				'label' => __( 'Mobile Menu', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
				],
			]
		);

		$this->add_control(
			'bg_full_color_menu_item',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .sidebar-on-mobile-vertical-nav' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'close_icon_color',
			[
				'label'     => __( 'Close Icon Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,		
				'selectors' => [
					'{{WRAPPER}} .sidebar-on-mobile-vertical-nav .eel-vertical-menu-icon-close' => 'color: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style_mobile' );

		$this->start_controls_tab(
			'tab_menu_item_normal_m',
			[
				'label' => __( 'Normal', 'easy-elements' ),
			]
		);		

		$this->add_control(
			'color_menu_item_m',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,		
				'selectors' => [
					'{{WRAPPER}} .sidebar-on-mobile-vertical-nav .mobile-menu.eel-vertical-verticalmenu li a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sidebar-on-mobile-vertical-nav .mobile-menu.eel-vertical-verticalmenu .submenu-parent-icon svg path' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'color_menu_item_m_typography',
				'label' => esc_html__('Typography', 'easy-elements' ),
				'selector' => '{{WRAPPER}} .sidebar-on-mobile-vertical-nav .mobile-menu.eel-vertical-verticalmenu li a',
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
                    '{{WRAPPER}} .sidebar-on-mobile-vertical-nav .mobile-menu.eel-vertical-verticalmenu > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        $this->add_control(
            'mobile_humburger_icon_heading',
            [
                'label'     => esc_html__( 'Hamburger Icon', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        // Hamburger Icon Color
        $this->add_control(
            'mobile_humburger_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-vertical-menu-icon-mobile i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel-vertical-menu-icon-mobile svg path' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        // Hamburger Icon Size (Responsive)
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
                    '{{WRAPPER}} .eel-vertical-menu-icon-mobile i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-vertical-menu-icon-mobile svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .sidebar-on-mobile-vertical-nav .eel-vertical-menu-icon-close, {{WRAPPER}} .sidebar-on-mobile-vertical-nav .eel-vertical-menu-icon-close svg path' => 'color: {{VALUE}}; fill: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .sidebar-on-mobile-vertical-nav .eel-vertical-menu-icon-close',
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

        $this->add_responsive_control(
            'mobile_close_icon_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .sidebar-on-mobile-vertical-nav .eel-vertical-menu-icon-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'mobile_menu_breakpoint' => [ '480', '576', '768', '992' ],
                ],
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover_m',
			[
				'label' => __( 'Hover', 'easy-elements' ),
			]
		);

		$this->add_control(
			'color_menu_item_hover_m',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sidebar-on-mobile-vertical-nav .mobile-menu.eel-vertical-verticalmenu li > a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sidebar-on-mobile-vertical-nav .mobile-menu.eel-vertical-verticalmenu li > a:hover .submenu-parent-icon svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_active_m',
			[
				'label' => __( 'Active', 'easy-elements' ),
			]
		);

		$this->add_control(
			'color_menu_item_active_m',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sidebar-on-mobile-vertical-nav .mobile-menu.eel-vertical-verticalmenu li.menu-item.current-menu-item > a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sidebar-on-mobile-vertical-nav .mobile-menu.eel-vertical-verticalmenu li.menu-item.current-menu-item > .submenu-parent-icon svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/**
	 * Render Nav Menu output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function render() { 
		$settings = $this->get_settings_for_display();					     
		$menus = $this->get_available_menus();
		if ( empty( $menus ) ) {
			return false;
		}
		$submenu_parent_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M201.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 338.7 54.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>';
		
		$mobile_icon = '';
		// Check if custom mobile icon exists
		if ( ! empty( $settings['mobile_hamburger_icon']['value'] ) ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon(
				$settings['mobile_hamburger_icon'],
				[ 'aria-hidden' => 'true' ]
			);
			$mobile_icon = ob_get_clean();
		} else {
			$mobile_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
				<path d="M3 4H21V6H3V4ZM3 11H15V13H3V11ZM3 18H21V20H3V18Z"></path>
			</svg>';
		}

		// Desktop Menu
		$items_wrap = '<div class="eel-vertical-menu-wrap">
			<div class="eel-vertical-menu-icon-mobile">' . $mobile_icon . '</div>
			<ul id="%1$s" class="%2$s">%3$s</ul>
		</div>';

		if ( ! easyel_premium_addon_active() ) {
			$submenu_animation =  'eel_animation_slide_down';
			$submenu_type      =  'eel_hover';
			$position_fixed    =  '';
		} else {
			$submenu_animation = ! empty( $settings['submenu_animation'] ) ? $settings['submenu_animation'] : '';
			$submenu_type      = ! empty( $settings['submenu_type'] ) ? $settings['submenu_type'] : 'eel_hover';
			$position_fixed    = ! empty( $settings['eel_position_fixed'] ) ? 'eel_position_fixed' : '';
		}

		$menu_before_icon_html = '';
		$menu_after_icon_html  = '';
		$icon_position         = isset( $settings['menu_icon_position'] ) ? $settings['menu_icon_position'] : 'none';

		if ( 'none' !== $icon_position && ! empty( $settings['menu_item_position_icon']['value'] ) ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon( $settings['menu_item_position_icon'], [ 'aria-hidden' => 'true' ] );
			$icon_markup = ob_get_clean();

			$allowed_icon_tags = [
				'i'     => [
					'class'       => true,
					'aria-hidden' => true,
				],
				'span'  => [
					'class'       => true,
					'aria-hidden' => true,
					'style'       => true,
				],
				'svg'   => [
					'class'               => true,
					'xmlns'               => true,
					'xmlns:xlink'         => true,
					'width'               => true,
					'height'              => true,
					'viewbox'             => true,
					'aria-hidden'         => true,
					'role'                => true,
					'focusable'           => true,
					'fill'                => true,
					'preserveaspectratio' => true,
					'style'               => true,
				],
				'path'   => [
					'd'              => true,
					'fill'           => true,
					'stroke'         => true,
					'stroke-width'   => true,
					'stroke-linecap' => true,
				],
				'g'      => [
					'fill'      => true,
					'transform' => true,
				],
				'circle' => [
					'cx'   => true,
					'cy'   => true,
					'r'    => true,
					'fill' => true,
				],
				'rect'   => [
					'x'      => true,
					'y'      => true,
					'width'  => true,
					'height' => true,
					'fill'   => true,
				],
				'defs'   => [],
				'symbol' => [
					'id'      => true,
					'viewbox' => true,
				],
				'use'    => [
					'href'       => true,
					'xlink:href' => true,
				],
				'title'  => [],
			];
			$icon_markup = wp_kses( $icon_markup, $allowed_icon_tags );

			if ( 'before' === $icon_position ) {
				$menu_before_icon_html = '<span class="eel-menu-before-icon">' . $icon_markup . '</span>';
			} elseif ( 'after' === $icon_position ) {
				$menu_after_icon_html = '<span class="eel-menu-after-icon">' . $icon_markup . '</span>';
			}
		}

		$container_class = 'eel-vertical-menu-elelmentor-widget menu-wrapper eel-vertical-menu-container eel-vertical-menu-area';
		if ( ! empty( $settings['mobile_menu_breakpoint'] ) ) {
			$container_class .= ' eel-mobile-bp-' . absint( $settings['mobile_menu_breakpoint'] );
		}

		$args = [
			'echo'        => false,
			'menu'        => $settings['menu'],
			'fallback_cb' => '__return_empty_string',
			'menu_class'      => 'menu desktop-menu eel-vertical-verticalmenu ' . $submenu_animation . ' ' . $submenu_type . ' ' . $position_fixed,
			'container_class'	=> $container_class,
			'items_wrap'      => $items_wrap,
			'submenu_parent_icon' => $submenu_parent_icon,
			'is_mobile_menu'	=> '',
			'walker'          	=> new \Easyel_vertical_Menu_Nav_Walker(),
			'easyel_navigation_vertical_menu' => true,
			'submenu_type'      => $submenu_type,
			'menu_before_icon'  => $menu_before_icon_html,
			'menu_after_icon'   => $menu_after_icon_html,
		];
		
		echo wp_nav_menu( $args );

		// Mobile Menu — each widget renders its own sidebar
		$settings = $this->get_settings_for_display();
		$open_position = !empty( $settings['mobile_menu_open_position'] ) ? $settings['mobile_menu_open_position'] : 'right';

		if ( ! empty( $settings['mobile_menu_breakpoint'] ) ) :
			$this->add_render_attribute( 'eel-vertical-menu-mobile', 'class', 'sidebar-on-mobile-vertical-nav eel-mobile-open-position-' . $open_position );
			$widget_instance = $this;

				$submenu_parent_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M201.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 338.7 54.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>';
				
				$mobile_args = [
					'echo'            => false,
					'menu'            => $settings['menu'],
					'fallback_cb'     => '__return_empty_string',
					'menu_class'      => 'menu mobile-menu eel-vertical-verticalmenu',
					'container'       => false,
					'walker'          => new \Easyel_vertical_Menu_Nav_Walker(),
					'easyel_navigation_vertical_menu' => true,
					'submenu_parent_icon' => $submenu_parent_icon,
					'menu_before_icon'    => $menu_before_icon_html,
					'menu_after_icon'     => $menu_after_icon_html,
				];
				?>
				<nav <?php $widget_instance->print_render_attribute_string( 'eel-vertical-menu-mobile' ); ?>>
					<span class="eel-vertical-menu-icon-close">
						<?php
                            if ( ! empty( $settings['mobile_close_icon']['value'] ) ) {                               
                                \Elementor\Icons_Manager::render_icon( $settings['mobile_close_icon'], [ 'aria-hidden' => 'true' ] );
                            } else { ?>
                                <i class="unicon-close"></i>
                            <?php }
                        ?>
					</span>
					<?php echo wp_nav_menu( $mobile_args ); ?>
				</nav>
				<?php
		endif;

	}
}