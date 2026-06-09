<?php	
namespace Easyel\EasyElements\Widgets;	
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

// Include the Menu_Walker class
require_once plugin_dir_path( __FILE__ ) . 'menu-walker.php';

/**
 * Class Nav Menu.
 */
class Easyel_Navigation_Menu_Widget extends \Elementor\Widget_Base {

	protected $script_handles = [];
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$js_files = [
			'widgets/navigation-menu/js/navigation-menu.min.js',
		];

		foreach ( $js_files as $js ) {

			$path = EASYELEMENTS_DIR_PATH . $js;

			if ( file_exists( $path ) ) {

				$handle = 'easyel-' . md5( $js );

				wp_register_script(
					$handle,
					EASYELEMENTS_DIR_URL . $js,
					[ 'jquery' ],
					filemtime( $path ),
					true
				);

				$this->script_handles[] = $handle;
			}
		}
	}

	/**
	 * Menu index.
	 *
	 * @access protected
	 * @var int $nav_menu_index
	 */
	// phpcs:ignore
	protected $nav_menu_index = 1;

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	
	public function get_name() {
        return 'eel-navigation-menu';
    }

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Navigation Menu', 'easy-elements' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
        return 'easyicon easyelIcon-navigation';
    }

	/**
	 * Retrieve the widget categories.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'easyelements_header_footer_category' ];
	}

	public function get_style_depends() {
        return [
            'eel-navigation-menu',
        ];
    }

	public function get_script_depends() {
		return $this->script_handles;
	}

	/**
	 * Retrieve the menu index.
	 *
	 * Used to get index of nav menu.
	 *
	 * @since 1.3.0
	 * @access protected
	 *
	 * @return int nav index.
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
	 * @return void
	 */

	public function get_keywords() {
        return [ 'navigation', 'menu', 'nav', 'text' ];
    }
	protected function register_controls() {

		$this->register_general_content_controls();
		$this->register_style_content_controls();
		$this->register_dropdown_content_controls();
	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 * @return void
	 */

	protected function register_general_content_controls() {

		$this->start_controls_section(
			'section_menu',
			[
				'label' => __( 'Menu Settings', 'easy-elements' ),
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
			'enable_sticky_header',
			[
				'label'        => __( 'Enable Sticky Header', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements' ),
				'label_off'    => __( 'No', 'easy-elements' ),
				'frontend_available' => true,
				'default'      => 'no',
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
				'default'      => 'no',
				'condition' => [
					'enable_sticky_header' => 'yes',
				],
			]
		);

		$this->start_controls_tabs(
			'sticky_bg_color_tabs',
			[
				'condition' => [
					'enable_sticky_header' => 'yes',
				],
			]
		);

		$this->start_controls_tab(
			'sticky_bg_color_normal_tab',
			[
				'label' => __( 'Normal', 'easy-elements' ),
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
				'description' => __( 'Note: If you are using the Sticky Header background color, Do not set a background color from the Elementor container or section. Please use the Sticky option to set the normal background color to avoid conflicts.', 'easy-elements' ),
				'selectors' => [
					'header.eel-sticky-header-on' => 'background-color: {{VALUE}}',
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
			'sticky_bg_color_hover_tab',
			[
				'label' => __( 'Sticky', 'easy-elements' ),
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
					'header.eel-sticky-header-on.eel-up-scroll, header.eel-sticky-header-on.eel--fixed-top-sticky' => 'background-color: {{VALUE}} !important',
				],
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
					'header.eel-sticky-header-on.eel-up-scroll .eel-nav-menu a.eel-menu-item, header.eel-sticky-header-on.eel-up-scroll .eel-nav-menu a.eel-sub-menu-item, .header.eel-sticky-header-on.eel-up-scroll *, header.eel-sticky-header-on.eel--fixed-top-sticky .eel-nav-menu a.eel-menu-item, header.eel-sticky-header-on.eel--fixed-top-sticky .eel-nav-menu a.eel-sub-menu-item, .header.eel-sticky-header-on.eel--fixed-top-sticky *' => 'color: {{VALUE}} !important',
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

		$current_theme = wp_get_theme();

		if ( 'Twenty Twenty-One' === $current_theme->get( 'Name' ) ) {
			$this->add_control(
				'hide_theme_icons',
				[
					'label'        => __( 'Hide + & - Sign', 'easy-elements' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'easy-elements' ),
					'label_off'    => __( 'No', 'easy-elements' ),
					'return_value' => 'yes',
					'default'      => 'no',
					'prefix_class' => 'eel-nav-menu__theme-icon-',
				]
			);
		}

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout Settings', 'easy-elements' ),
			]
		);

		$this->add_control(
		'layout',
		[
			'label'   => __( 'Layout', 'easy-elements' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'horizontal',
			'options' => [
				'horizontal' => __( 'Horizontal', 'easy-elements' ),
				'vertical'   => __( 'Vertical', 'easy-elements' ),
			],
		]
		);

		$this->add_responsive_control(
			'navmenu_align',
			[
				'label'   => __( 'Alignment', 'easy-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'easy-elements' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'easy-elements' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'easy-elements' ),
						'icon'  => 'eicon-h-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'easy-elements' ),
						'icon'  => 'eicon-h-align-stretch',
					],
				],
				'default' => 'left',
				'condition' => [
					'layout' => 'horizontal',
				],
				'selectors_dictionary' => [
					'left'    => 'flex-start',
					'center'  => 'center',
					'right'   => 'flex-end',
					'justify' => 'space-between',
				],
				'selectors' => [
					'{{WRAPPER}} .eel-nav-menu__layout-horizontal, {{WRAPPER}} .eel-nav-menu__layout-horizontal .eel-nav-menu' => 'justify-content: {{VALUE}};',
				],
			]
		);


		$this->add_responsive_control(
			'navmenu_align_text',
			[
				'label'     => __( 'Menu Alignment', 'easy-elements' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left' => [
						'title' => __( 'Left', 'easy-elements' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'easy-elements' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'easy-elements' ),
						'icon'  => 'eicon-h-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'easy-elements' ),
						'icon'  => 'eicon-h-align-stretch',
					],
				],
				'default'   => '',
				'condition'    => [
					'layout' => [ 'vertical' ],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-nav-menu li.menu-item' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .eel-nav-menu li.menu-item a' => 'max-width: 100%; width: auto; display: block;',
				],
			]
		);			

		$this->add_control(
			'submenu_animation',
			[
				'label'        => __( 'Dropdown Animation', 'easy-elements' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'eel_animation_slide_down',
				'options'      => [
					'eel_animation_slide_down' => __( 'Animation Down', 'easy-elements' ),
					'eel_animation_slide_up' => __( 'Animation Up', 'easy-elements' ),
					'eel_animation_fade' => __( 'Animation Fade', 'easy-elements' ),
					'eel_animation_right' => __( 'Animation Right', 'easy-elements' ),
					'eel_animation_left' => __( 'Animation Left', 'easy-elements' ),
					'eel_animation_zoom' => __( 'Animation Zoom', 'easy-elements' ),
				],
				'condition'    => [
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_control(
		    'menu_icon_vertical',
		    [
		        'label' => __('Icon', 'easy-elements'),
		        'type' => Controls_Manager::ICONS,
				'condition'    => [
					'layout' => 'vertical',
				],
		    ]
		);

		$this->add_responsive_control(
			'icon_alignment_vertical',
			[
				'label'     => __( 'Alignment', 'easy-elements' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'align_left' => [
						'title' => __( 'Left', 'easy-elements' ),
						'icon'  => 'eicon-h-align-left',
					],
					'align_right' => [
						'title' => __( 'Right', 'easy-elements' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => 'align_left',
				'condition' => [
					'layout' => 'vertical',
				],
				'selectors_dictionary' => [
					'align_left'  => '-1',
					'align_right' => '1',
				],
				'selectors' => [
					'{{WRAPPER}} .eel-layout-vertical .eel-nav-menu li>.eel-menu-item .eel-menu-dynamic-icon' => 'order: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin_vertical',
			[
				'label' => esc_html__( 'Margin', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eel-layout-vertical .eel-nav-menu li>.eel-menu-item .eel-menu-dynamic-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'layout' => 'vertical',
				],
			]
		);

		$this->add_control(
			'heading_responsive',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Responsive Menu Settings', 'easy-elements' ),
				'separator' => 'before',
				'condition' => [
					'layout' => [ 'horizontal' ],
				],
			]
		);

		$this->add_control(
			'dropdown',
			[
				'label'        => __( 'Breakpoint', 'easy-elements' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'tablet',
				'options'      => [
					'tablet' => __( 'Tablet (1025px >)', 'easy-elements' ),
					'none'   => __( 'None', 'easy-elements' ),
				],
				'prefix_class' => 'eel-nav-menu__breakpoint-',
				'condition'    => [
					'layout' => [ 'horizontal' ],
				],
				'render_type'  => 'template',
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
				'condition'    => [
					'layout' => [ 'horizontal' ],
				],
			]
		);

		$this->add_responsive_control(
			'mobile_nav_sidebar_width',
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
					'.sidebar-on-mobile[data-nav-id="{{ID}}"]' => 'max-width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);


		$this->start_controls_tabs( 'tabs_menu_item_style_mobile' );

		$this->start_controls_tab(
			'tab_menu_item_normal_m',
			[
				'label' => __( 'Normal', 'easy-elements' ),
				'condition' => [
					'layout' => [ 'horizontal' ],
				],
			]
		);

		$this->add_control(
			'color_menu_item_m',
			[
				'label'     => __( 'Text Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.sidebar-on-mobile[data-nav-id="{{ID}}"] li.menu-item a' => 'color: {{VALUE}} !important;',
				],
				'condition'    => [
					'layout' => [ 'horizontal' ],
				],
			]
		);

		$this->add_control(
			'bg_color_full',
			[
				'label' => esc_html__( 'Background Color (Full Sidebar)', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'.sidebar-on-mobile[data-nav-id="{{ID}}"]' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_item_bg',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'.sidebar-on-mobile[data-nav-id="{{ID}}"] .sub-menu:not(.easyel--elementor-template-mega-menu) li > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'color_menu_item_m_typography',
				'label' => esc_html__('Typography', 'easy-elements' ),
				'selector' => '.sidebar-on-mobile[data-nav-id="{{ID}}"] li.menu-item a',
				'condition'    => [
					'layout' => [ 'horizontal' ],
				],
			]
		); 

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover_m',
			[
				'label' => __( 'Hover', 'easy-elements' ),
				'condition'    => [
					'layout' => [ 'horizontal' ],
				],
			]
		);

		$this->add_control(
			'color_menu_item_hover_m',
			[
				'label'     => __( 'Text Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.sidebar-on-mobile[data-nav-id="{{ID}}"] li.menu-item a:hover, .sidebar-on-mobile[data-nav-id="{{ID}}"] li.menu-item a.highlighted' => 'color: {{VALUE}} !important;',
				],
				'condition'    => [
					'layout' => [ 'horizontal' ],
				],
			]
		);

		$this->add_control(
			'dropdown_item_bg_hover',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'.sidebar-on-mobile[data-nav-id="{{ID}}"] .sub-menu:not(.easyel--elementor-template-mega-menu) li > a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_active_m',
			[
				'label' => __( 'Active', 'easy-elements' ),
				'condition'    => [
					'layout' => [ 'horizontal' ],
				],
			]
		);

		$this->add_control(
			'color_menu_item_active_m',
			[
				'label'     => __( 'Text Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.sidebar-on-mobile[data-nav-id="{{ID}}"] li.menu-item.current-menu-item a, .sidebar-on-mobile[data-nav-id="{{ID}}"] li.menu-item.current-menu-ancestor a, .sidebar-on-mobile[data-nav-id="{{ID}}"] li.menu-item a.eel-active' => 'color: {{VALUE}} !important;',
				],
				'condition'    => [
					'layout' => [ 'horizontal' ],
				],
			]
		);

		$this->add_control(
			'dropdown_item_bg_active',
			[
				'label' => esc_html__( 'Background Color', 'easy-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => [
					'.sidebar-on-mobile[data-nav-id="{{ID}}"] .sub-menu:not(.easyel--elementor-template-mega-menu) li.current-menu-item > a' => 'background-color: {{VALUE}};',
				],
			]
		);


	$this->end_controls_tab();

	$this->end_controls_tabs();

		$this->add_responsive_control(
			'resp_align',
			[
				'label'                => __( 'Hamburger Alignment', 'easy-elements' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'left'   => [
						'title' => __( 'Left', 'easy-elements' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'easy-elements' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'easy-elements' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'              => 'right',
				'description'          => __( 'This is the alignment of menu icon on selected responsive breakpoints.', 'easy-elements' ),
				'condition'            => [
					'layout'    => [ 'horizontal' ],
					'dropdown!' => 'none',
				],
				'selectors_dictionary' => [
					'left'   => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right'  => 'margin-left: auto',
				],
				'selectors'            => [
					'{{WRAPPER}} .eel-nav-menu__toggle' => '{{VALUE}}',
				],
			]
		);

	
		$this->add_control(
			'dropdown_icon',
			[
				'label'       => __( 'Choose Hamburger Icon', 'easy-elements' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => 'true',
				'default'     => [
					'value'   => 'fas fa-align-justify',
					'library' => 'fa-solid',
				],
				'condition'   => [
					'layout'    => [ 'horizontal' ],
					'dropdown!' => 'none',
				],
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
                    'layout'    => [ 'horizontal' ],
					'dropdown!' => 'none',
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
	 * @return void
	 */
	protected function register_style_content_controls() {

		$this->start_controls_section(
			'section_style_main-menu',
			[
				'label'     => __( 'Main Menu', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_menu_padding',
			[
				'label' => esc_html__( 'Padding', 'easy-elements' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eel-nav-menu > .menu-item > a.eel-menu-item' =>
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_menu_margin',
			[
				'label' => esc_html__( 'Margin', 'easy-elements' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eel-nav-menu > .menu-item > a.eel-menu-item' =>
						'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'style_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'menu_typography',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} a.eel-menu-item, {{WRAPPER}} a.eel-sub-menu-item',
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

				$this->start_controls_tab(
					'tab_menu_item_normal',
					[
						'label' => __( 'Normal', 'easy-elements' ),
					]
				);

				$this->add_control(
					'color_menu_item',
					[
						'label'     => __( 'Text Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .eel-nav-menu > .menu-item > a.eel-menu-item' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'bg_color_menu_item',
					[
						'label'     => __( 'Background Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .eel-nav-menu > .menu-item > a.eel-menu-item' => 'background-color: {{VALUE}}',
						],
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_menu_item_hover',
					[
						'label' => __( 'Hover', 'easy-elements' ),
					]
				);

				$this->add_control(
					'color_menu_item_hover',
					[
						'label'     => __( 'Text Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .eel-nav-menu > .menu-item:hover > a.eel-menu-item' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'bg_color_menu_item_hover',
					[
						'label'     => __( 'Background Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .eel-nav-menu > .menu-item:hover > a.eel-menu-item' => 'background-color: {{VALUE}}',
						],
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_menu_item_active',
					[
						'label' => __( 'Active', 'easy-elements' ),
					]
				);

				$this->add_control(
					'color_menu_item_active',
					[
						'label'     => __( 'Text Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .eel-nav-menu > .menu-item.current-menu-item > a.eel-menu-item, {{WRAPPER}} .eel-nav-menu > .menu-item.current_page_item > a.eel-menu-item, {{WRAPPER}} .eel-nav-menu > .menu-item.current-menu-ancestor > a.eel-menu-item' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'bg_color_menu_item_active',
					[
						'label'     => __( 'Background Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .eel-nav-menu > .menu-item.current-menu-item > a.eel-menu-item, {{WRAPPER}} .eel-nav-menu > .menu-item.current_page_item > a.eel-menu-item, {{WRAPPER}} .eel-nav-menu > .menu-item.current-menu-ancestor > a.eel-menu-item' => 'background-color: {{VALUE}}',
						],
					]
				);

				$this->end_controls_tab();
			$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 * @return void
	 */
	protected function register_dropdown_content_controls() {
		$this->start_controls_section(
			'section_style_dropdown',
			[
				'label' => __( 'Dropdown', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout' => ['horizontal'],
				],
			]
		);

		$this->add_control(
			'dropdown_description',
			[
				'raw'=> __( '<b>Note:</b> On desktop, below style options will apply to the submenu. On mobile, this will apply to the entire menu.', 'easy-elements' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		$this->start_controls_tabs( 'tabs_dropdown_item_style' );

			$this->start_controls_tab(
				'tab_dropdown_item_normal',
				[
					'label' => __( 'Normal', 'easy-elements' ),
				]
			);

				$this->add_control(
					'color_dropdown_item',
					[
						'label'     => __( 'Text Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .sub-menu:not(.easyel--elementor-template-mega-menu) .menu-item > a' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'background_color_dropdown_item',
					[
						'label'     => __( 'Background Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#fff',
						'selectors' => [
							'{{WRAPPER}} .sub-menu:not(.easyel--elementor-template-mega-menu) .menu-item > a' => 'background-color: {{VALUE}}',
						],
						'separator' => 'after',
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_dropdown_item_hover',
				[
					'label' => __( 'Hover', 'easy-elements' ),
				]
			);

				$this->add_control(
					'color_dropdown_item_hover',
					[
						'label'     => __( 'Text Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .sub-menu:not(.easyel--elementor-template-mega-menu) .menu-item:hover > a' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'background_color_dropdown_item_hover',
					[
						'label'     => __( 'Background Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .sub-menu:not(.easyel--elementor-template-mega-menu) .menu-item:hover > a' => 'background-color: {{VALUE}}',
						],
						'separator' => 'after',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_dropdown_item_active',
					[
						'label' => __( 'Active', 'easy-elements' ),
					]
				);

				$this->add_control(
					'color_dropdown_item_active',
					[
						'label'     => __( 'Text Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .sub-menu:not(.easyel--elementor-template-mega-menu) .menu-item.current-menu-item > a, {{WRAPPER}} .sub-menu:not(.easyel--elementor-template-mega-menu) .menu-item.current_page_item > a, {{WRAPPER}} .sub-menu:not(.easyel--elementor-template-mega-menu) .menu-item.current-menu-ancestor > a
							' => 'color: {{VALUE}}',

						],
					]
				);

				$this->add_control(
					'background_color_dropdown_item_active',
					[
						'label'     => __( 'Background Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .sub-menu:not(.easyel--elementor-template-mega-menu) .menu-item.current-menu-item > a, {{WRAPPER}} .sub-menu:not(.easyel--elementor-template-mega-menu) .menu-item.current_page_item > a, {{WRAPPER}} .sub-menu:not(.easyel--elementor-template-mega-menu) .menu-item.current-menu-ancestor > a' => 'background-color: {{VALUE}}',
						],
						'separator' => 'after',

					]
				);
			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'dropdown_typography',
					'separator' => 'before',
					'selector'  => '
					{{WRAPPER}} .sub-menu:not(.easyel--elementor-template-mega-menu) .menu-item > a',
					'condition' => [
						'layout' => 'horizontal',
					],
				]
			);			

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name'     => 'dropdown_item_border',
					'selector' => '{{WRAPPER}} .eel-nav-menu ul.sub-menu:not(.easyel--elementor-template-mega-menu) .menu-item > a',
					'condition' => [
						'layout' => 'horizontal',
					],
				]
			);			

			$this->add_responsive_control(
				'item__full_padding',
				[
					'label'      => esc_html__( 'Padding', 'easy-elements' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .eel-nav-menu ul.sub-menu:not(.easyel--elementor-template-mega-menu) .menu-item > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'layout' => 'horizontal',
					],
				]
			);

			$this->add_responsive_control(
				'width_dropdown_item',
				[
					'label'              => __( 'Dropdown Width (px)', 'easy-elements' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => [
						'px' => [
							'min' => 0,
							'max' => 500,
						],
					],
					'default'            => [
						'size' => '220',
						'unit' => 'px',
					],
					'selectors'          => [
						'{{WRAPPER}} ul.sub-menu:not(.easyel--elementor-template-mega-menu)' => 'width: {{SIZE}}{{UNIT}}',
					],
					'condition'          => [
						'layout' => 'horizontal',
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'icon_size',
				[
					'label'              => __( 'Icon Size', 'easy-elements' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => [
						'px' => [
							'min' => 15,
							'max' => 25,
						],
					],
					'selectors'          => [
						'{{WRAPPER}} .eel-nav-menu .menu-item-has-children a.eel-menu-item::before' => 'font-size: {{SIZE}}px;',
					],
					'condition'          => [
						'layout' => [ 'horizontal' ],
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'icon_distance_from_menu',
				[
					'label'              => __( 'Icon Top Distance', 'easy-elements' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => [
						'px' => [
							'min' => -100,
							'max' => 100,
						],
					],
					'selectors'          => [
						'{{WRAPPER}} .eel-nav-menu .menu-item-has-children a.eel-menu-item::before' => 'top: {{SIZE}}%;',
					],
					'condition'          => [
						'layout' => [ 'horizontal' ],
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'icon_left_right_from_menu',
				[
					'label'              => __( 'Icon Right Distance', 'easy-elements' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => [
						'px' => [
							'min' => -100,
							'max' => 100,
						],
					],
					'selectors'          => [
						'{{WRAPPER}} .eel-nav-menu .menu-item-has-children>a:before' => 'right: {{SIZE}}px;',
					],
					'condition'          => [
						'layout' => [ 'horizontal' ],
					],
					'frontend_available' => true,
				]
			);
			
			$this->add_control(
				'full_heading',
				[
					'label' => esc_html__( 'Dropdown Wrapper', 'easy-elements' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_control(
				'background_color_wrapper',
				[
					'label'     => __( 'Background Color', 'easy-elements' ),
					'type'      => Controls_Manager::COLOR,
					'description' => __( 'Note: If you use a background color on individual items, It will not be visible on the wrapper.', 'easy-elements' ),
					'selectors' => [
						'{{WRAPPER}} .eel-nav-menu ul.sub-menu:not(.easyel--elementor-template-mega-menu)' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'dropdown_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'easy-elements' ),
					'type'  => Controls_Manager::DIMENSIONS,
					'selectors' => [
						'{{WRAPPER}} .eel-nav-menu ul.sub-menu:not(.easyel--elementor-template-mega-menu), {{WRAPPER}} .eel-nav-menu .sub-menu li.menu-item' =>
							'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'description' => esc_html__( 'Note: Border radius will only be visible if you apply a background color to the wrapper.', 'easy-elements' ),
					'condition' => [
						'layout' => 'horizontal',
					],
				]
			);

			$this->add_responsive_control(
				'ul__full_padding',
				[
					'label'      => esc_html__( 'Padding', 'easy-elements' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .eel-nav-menu ul.sub-menu:not(.easyel--elementor-template-mega-menu)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'layout' => 'horizontal',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'      => 'dropdown_box_shadow',					
					'selector'  => '{{WRAPPER}} .eel-nav-menu .sub-menu:not(.easyel--elementor-template-mega-menu)',
					'separator' => 'after',
					'condition' => [
						'layout' => 'horizontal',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_toggle',
			[
				'label' => __( 'Menu Trigger', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_toggle_style' );

		$this->start_controls_tab(
			'toggle_style_normal',
			[
				'label' => __( 'Normal', 'easy-elements' ),
			]
		);

		$this->add_control(
			'toggle_color',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} div.eel-nav-menu-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} div.eel-nav-menu-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_background_color',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-nav-menu-icon' => 'background-color: {{VALUE}}; padding: 0.35em;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_hover',
			[
				'label' => __( 'Hover', 'easy-elements' ),
			]
		);

		$this->add_control(
			'toggle_hover_color',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} div.eel-nav-menu-icon:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} div.eel-nav-menu-icon:hover svg' => 'fill: {{VALUE}}',

				],
			]
		);

		$this->add_control(
			'toggle_hover_background_color',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-nav-menu-icon:hover' => 'background-color: {{VALUE}}; padding: 0.35em;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'toggle_size',
			[
				'label'              => __( 'Icon Size', 'easy-elements' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'min' => 15,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .eel-nav-menu-icon'     => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .eel-nav-menu-icon svg' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
				],
				'frontend_available' => true,
				'separator'          => 'before',
			]
		);

		$this->add_responsive_control(
			'toggle_border_width',
			[
				'label'              => __( 'Border Width', 'easy-elements' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'max' => 10,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .eel-nav-menu-icon' => 'border-width: {{SIZE}}{{UNIT}}; padding: 0.35em;',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'toggle_border_radius',
			[
				'label'              => __( 'Border Radius', 'easy-elements' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .eel-nav-menu-icon' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_toggle_close',
			[
				'label' => __( 'Menu Close', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'toggle_color_close',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.sidebar-on-mobile[data-nav-id="{{ID}}"] .eel-nav-menu-icon' => 'color: {{VALUE}}',
					'.sidebar-on-mobile[data-nav-id="{{ID}}"] .eel-nav-menu-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_background_color_close',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.sidebar-on-mobile[data-nav-id="{{ID}}"] .eel-nav-menu-icon' => 'background-color: {{VALUE}}; padding: 0.35em;',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'toggle_border_close',
				'label' => __( 'Border', 'easy-elements' ),
				'selector' => '.sidebar-on-mobile[data-nav-id="{{ID}}"] .eel-nav-menu-icon',
			]
		);

		$this->add_responsive_control(
			'toggle_size_close',
			[
				'label'              => __( 'Icon Size', 'easy-elements' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'min' => 15,
					],
				],
				'selectors'          => [
					'.sidebar-on-mobile[data-nav-id="{{ID}}"] .eel-nav-menu-icon'     => 'font-size: {{SIZE}}{{UNIT}}',
					'.sidebar-on-mobile[data-nav-id="{{ID}}"] .eel-nav-menu-icon svg' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
				],
				'frontend_available' => true,
				'separator'          => 'before',
			]
		);

		$this->add_responsive_control(
			'toggle_border_radius_close',
			[
				'label'              => __( 'Border Radius', 'easy-elements' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px', '%' ],
				'selectors'          => [
					'.sidebar-on-mobile[data-nav-id="{{ID}}"] .eel-nav-menu-icon' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Add itemprop for Navigation Schema.
	 *
	 * @since 1.5.2
	 * @param string $atts link attributes.
	 * @access public
	 * @return string
	 */
	public function handle_link_attrs( $atts ) {

		$atts .= ' itemprop="url"';
		return $atts;
	}

	/**
	 * Add itemprop to the li tag of Navigation Schema.
	 *
	 * @since 1.6.0
	 * @param string $value link attributes.
	 * @access public
	 * @return string
	 */
	public function handle_li_values( $value ) {

		$value .= ' itemprop="name"';
		return $value;
	}


	/**
	 * Get the menu and close icon HTML.
	 *
	 * @since 1.5.2
	 * @param array $settings Widget settings array.
	 * @access public
	 * @return array
	 */
	public function get_menu_close_icon( $settings ) {
		$menu_icon     = '';
		$close_icon    = '';
		$icons         = [];
		$icon_settings = [
			$settings['dropdown_icon'],
			//$settings['dropdown_close_icon'],
		];

		foreach ( $icon_settings as $icon ) {
			if ( $this->is_elementor_updated() ) {
				ob_start();
				\Elementor\Icons_Manager::render_icon(
					$icon,
					[
						'aria-hidden' => 'true',
						'tabindex'    => '0',
					]
				);
				$menu_icon = ob_get_clean();
			} else {
				$menu_icon = '<i class="' . esc_attr( $icon ) . '" aria-hidden="true" tabindex="0"></i>';
			}

			array_push( $icons, $menu_icon );
		}

		return $icons;
	}	

	/**
	 * Render Nav Menu output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @access protected
	 * @return (void | false)
	 */
	protected function render() {

	$menus = $this->get_available_menus();

	if ( empty( $menus ) ) {
		return;
	}

	$settings = $this->get_settings_for_display();

	$menu_close_icons = [];
	$menu_close_icons = $this->get_menu_close_icon( $settings );	

	$enableSticky  = isset($settings['enable_sticky_header']) && 'yes' === $settings['enable_sticky_header'];
	$enablePadding = isset($settings['disable_top_padding']) && 'yes' === $settings['disable_top_padding'];

	if ( isset( $settings['enable_sticky_header'] ) && 'yes' === $settings['enable_sticky_header'] ) {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$GLOBALS['easyel_force_sticky_header'] = true;  // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		wp_enqueue_script(
			'eel-sticky-header',
			plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'widgets/navigation-menu/js/eel-sticky-header.js',
			[ 'jquery' ],
			EASYELEMENTS_VER,
			true
		);

		wp_localize_script('eel-sticky-header', 'eelStickyHeaderSettings', [
			'enableSticky'  => $enableSticky,
			'enablePadding' => $enablePadding
		]);

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
	}

		$args = [
			'echo'        => false,
			'menu'        => $settings['menu'],
			'menu_class'  => 'eel-nav-menu',
			'menu_id'     => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
			'fallback_cb' => '__return_empty_string',
			'walker'      => new \Easyel_Menu_Walker(),
		];

		$mobile_args = [
			'echo'        => false,
			'menu'        => $settings['menu'],
			'menu_class'  => 'eel-nav-menu eel-nav-menu-mobile',
			'fallback_cb' => '__return_empty_string',
			'walker'      => new \Easyel_Menu_Walker(),
			'is_mobile_menu' => true,
		];

		$this->add_render_attribute(
			'eel-main-menu',
			'class',
			[
				'eel-nav-menu',
				'eel-layout-' . $settings['layout'],
			]
		);

		$this->add_render_attribute( 'eel-main-menu', 'class', 'eel-nav-menu-layout' );

		$this->add_render_attribute( 'eel-main-menu', 'class', $settings['layout'] );

		$this->add_render_attribute( 'eel-main-menu', 'data-layout', $settings['layout'] );

		$this->add_render_attribute(
			'eel-nav-menu',
			'class',
			[
				'eel-nav-menu__layout-' . $settings['layout'],
			]
		);

		$icon_alignment = $settings['icon_alignment_vertical'] ?? 'align_left';
		$this->add_render_attribute(
			'eel-main-menu',
			'class',
			'icon-' . $icon_alignment
		);

		if ( 'vertical' === $settings['layout'] && !empty($settings['menu_icon_vertical']['value']) ) {
			$this->add_render_attribute('eel-nav-menu', 'data-vertical-icon', $settings['menu_icon_vertical']['value']);
		}

		$animation_class = !empty($settings['submenu_animation']) ? $settings['submenu_animation'] : 'eel_animation_slide_down';
		if ( 'horizontal' === $settings['layout'] ) {
			$this->add_render_attribute('eel-nav-menu', 'class', $animation_class);	
		}	
		?>
		
		<div <?php $this->print_render_attribute_string( 'eel-main-menu' ); ?>>
			<?php if ( isset( $settings['layout'] ) && 'horizontal' === $settings['layout'] ) : ?>
			<div role="button" class="eel-nav-menu__toggle elementor-clickable">
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'easy-elements' ); ?></span>
				<div class="eel-nav-menu-icon" data-nav-target="<?php echo esc_attr( $this->get_id() ); ?>">
					<?php
						if ( isset( $menu_close_icons[0] ) ) {
							$menu_close_icon = str_replace( 'tabindex="0"', '', $menu_close_icons[0] );
							$allowed_svg = [
								'svg'   => [
									'class'       => true,
									'xmlns'       => true,
									'width'       => true,
									'height'      => true,
									'viewbox'     => true,
									'aria-hidden' => true,
									'role'        => true,
									'focusable'   => true,
								],
								'path'  => [
									'd'    => true,
									'fill' => true,
								],
								'g'     => [ 'fill' => true ],
								'title' => [],
							];
							echo wp_kses( $menu_close_icon, $allowed_svg );
						}
					?>
				</div>
			</div>
			<?php endif; ?>
			

			<?php
				$settings = $this->get_settings_for_display();
				$nav_widget_id = $this->get_id();
				if ( isset( $settings['layout'] ) && 'horizontal' === $settings['layout'] ) {
					$open_position = !empty( $settings['mobile_menu_open_position'] ) ? $settings['mobile_menu_open_position'] : 'right';
					?>
					<nav class="sidebar-on-mobile eel-mobile-open-position-<?php echo esc_attr( $open_position ); ?>" data-nav-id="<?php echo esc_attr( $nav_widget_id ); ?>">
						<span class="eel-nav-menu-icon eel-mobile-menu-icon-<?php echo esc_attr( $open_position ); ?>">
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
				}
			?>

			<nav <?php $this->print_render_attribute_string( 'eel-nav-menu' ); ?>>
				<?php echo wp_nav_menu( $args ); ?> 
			</nav>						
		</div>
		<?php									
	}
}