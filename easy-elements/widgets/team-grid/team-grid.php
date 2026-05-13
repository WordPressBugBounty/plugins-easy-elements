<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

defined( 'ABSPATH' ) || die();
class Easyel_Team_Grid__Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'eel-team-grid';
    }

    public function get_title() {
        return esc_html__( 'Team Grid', 'easy-elements' );
    }

    public function get_icon() {
        return 'easyicon easyelIcon-team-grid';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'member', 'team', 'brand', 'partner', 'image', 'text' ];
    }

    public function get_style_depends() {
        return [
            'eel-team-grid',
        ];
    }

    public function get_script_depends() {
        return [
            'eel-team-grid',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            '_section_team',
            [
                'label' => esc_html__( 'Team Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
            $this->add_control(
                'team_skin',
                [
                    'label'   => esc_html__('Skin Type', 'easy-elements'),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default'   => esc_html__('Default', 'easy-elements'),
                        'skin1' => esc_html__('Skin 01', 'easy-elements'),
                        'skin2' => esc_html__('Skin 02', 'easy-elements'),
                        'skin3' => esc_html__('Skin 03', 'easy-elements'),
                        'skin4' => esc_html__('Skin 04 (Hover Overlay)', 'easy-elements'),
                    ],
                ]
            );

            $this->add_control(
                'image',
                [
                    'label' => esc_html__('Image', 'easy-elements'),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_control(
                '_name',
                [
                    'label' => esc_html__( 'Name', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__( 'Harry Nelson', 'easy-elements' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'title_tag',
                [
                    'label' => esc_html__( 'Title HTML Tag', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
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
                        'p'   => 'p',
                    ],
                ]
            );

            $this->add_control(
                'designation',
                [
                    'label' => esc_html__('Designation', 'easy-elements'),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Head of Operations', 'easy-elements' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'details',
                [
                    'label' => esc_html__('Description', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'default' => '',
                    'rows' => 4,
                    'placeholder' => esc_html__('Enter description here...', 'easy-elements'),
                    'label_block' => true,
                    'description' => esc_html__('Shown below designation on the card (default, Skin 01, Skin 02), as the hover overlay text on Skin 03, and inside the popup when Action Type is set to Popup.', 'easy-elements'),
                ]
            );
            
            $this->add_control(
                'action_type',
                [
                    'label' => esc_html__('Action Type', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'link' => esc_html__('Link', 'easy-elements'),
                        'popup' => esc_html__('Popup', 'easy-elements'),
                    ],
                    'default' => 'link',
                    'description' => esc_html__('Choose between link or popup action.', 'easy-elements'),
                ]
            );
            $this->add_control(
                'link',
                [
                    'label' => esc_html__('Link', 'easy-elements'),
                    'type' => Controls_Manager::URL,
                    'placeholder' => 'https://example.com',
                    'description' => esc_html__('You can add a page link here, such as the team member\'s profile page.', 'easy-elements'),
                    'condition' => [
                        'action_type' => 'link',
                    ],
                ]
            );
            $this->add_control(
                'content_show',
                [
                    'label' => esc_html__('Content Show', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'inside',
                    'options' => [
                        'inside' => esc_html__('Inside Image', 'easy-elements'),
                        'normal' => esc_html__('Normal', 'easy-elements'),
                    ],
                    'condition' => [
                        'team_skin' => ['default','skin2'],
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'image', 
                    'default' => 'full',
                ]
            );

            $this->add_control(
                'fetchpriority',
                [
                    'label' => __('Image Fetch Priority', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        ''      => __('Default', 'easy-elements'),
                        'high'  => __('High', 'easy-elements'),
                        'low'   => __('Low', 'easy-elements'),
                    ],
                    'default' => 'low',
                ]
            );

            $this->add_control(
                'image_overlay_note',
                [
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<div class="eel-popup-note">' . esc_html__('Note: The Image Overlay supports both classic (solid color) and gradient backgrounds.', 'easy-elements') . '</div>',
                    'separator' => 'before',
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'image_overlay',
                    'label' => esc_html__('Image Overlay', 'easy-elements'),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-image-overlay',
                ]
            );
            $this->add_control(
                'disable_image_scale',
                [
                    'label'        => esc_html__( 'Disable Image Scale on Hover', 'easy-elements' ),
                    'type'         => \Elementor\Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Yes', 'easy-elements' ),
                    'label_off'    => esc_html__( 'No', 'easy-elements' ),
                    'return_value' => 'yes',
                    'default'      => '',
                    'selectors'    => [
                        '{{WRAPPER}} .eel-team-grid .grid-item .ee--team-img:hover img' => 'transform: none;',
                    ],
                    'separator'    => 'before',
                ]
            );

            $this->add_control(
                'disable_social_icon_lift',
                [
                    'label'        => esc_html__( 'Disable Social Icon Lift on Hover', 'easy-elements' ),
                    'type'         => \Elementor\Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Yes', 'easy-elements' ),
                    'label_off'    => esc_html__( 'No', 'easy-elements' ),
                    'return_value' => 'yes',
                    'default'      => '',
                    'selectors'    => [
                        '{{WRAPPER}} .eel-team-grid-social .eel-team-social-hover a:hover' => 'transform: none;',
                        '{{WRAPPER}} .eel-team-grid-social ul li a:not(.eel-popup-trigger):hover' => 'transform: none;',
                    ],
                ]
            );

            $this->add_control(
                'show_social_icon',
                [
                    'label' => esc_html__( 'Show Social Icon', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'easy-elements' ),
                    'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

        // Social Item 
        $this->start_controls_section(
			'social_icon_section',
			[
				'label' => esc_html__( 'Social Settings', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
                'condition' => [ 
                    'show_social_icon' => 'yes',
                ],
			]
		 );
            
            $this->add_control(
                'social_icon_position',
                [
                    'label' => esc_html__('Icon Position', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default' => esc_html__('Default', 'easy-elements'),
                        'posi_left' => esc_html__('Top Left', 'easy-elements'),
                        'posi_right' => esc_html__('Top Right', 'easy-elements'),
                        'posi_botttom_left' => esc_html__('Bottom Left', 'easy-elements'),
                        'posi_botttom_right' => esc_html__('Bottom Right', 'easy-elements'),
                    ],
                ]
            );
            $this->add_control(
                'social_icon_show',
                [
                    'label' => esc_html__('Icon Show', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'dafault_show',
                    'options' => [
                        'dafault_show' => esc_html__('Dafault Show', 'easy-elements'),
                        'hover_show' => esc_html__('Icon Hover Show', 'easy-elements'),
                    ],
                    'condition' => [ 
                        'social_icon_position!' => 'default',
                    ],
                ]
            );
            $this->add_control(
                'social_hover_icon',
                [
                    'label'       => esc_html__( 'Hover Icon', 'easy-elements' ),
                    'type'        => \Elementor\Controls_Manager::ICONS,
                    'label_block' => true,
                    'default'     => [
                        'value'   => 'fas fa-plus',
                        'library' => 'fa-solid',
                    ],
                    'condition' => [ 
                        'social_icon_show' => 'hover_show',
                        'social_icon_position!' => 'default',
                    ],
                ]
            );
            $this->add_responsive_control(
                's_icon_posi_top',
                [
                    'label' => esc_html__( 'Top Position', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em','%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid-social.posi_left, {{WRAPPER}} .eel-team-grid-social.posi_right' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [ 
                        'social_icon_position' => ['posi_left','posi_right'],
                    ],
                ]
            );
            $this->add_responsive_control(
                's_icon_posi_bottom',
                [
                    'label' => esc_html__( 'Bottom Position', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em','%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid-social.posi_botttom_left, {{WRAPPER}} .eel-team-grid-social.posi_botttom_right' => 'bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [ 
                        'social_icon_position' => ['posi_botttom_left','posi_botttom_right'],
                    ],
                ]
            );
            $this->add_responsive_control(
                's_icon_posi_left',
                [
                    'label' => esc_html__( 'Left Position', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em','%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid-social.posi_left' => 'left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .eel-team-grid-social.posi_botttom_left' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [ 
                        'social_icon_position' => ['posi_left','posi_botttom_left'],
                    ],
                ]
            );
            $this->add_responsive_control(
                's_icon_posi_right',
                [
                    'label' => esc_html__( 'Right Position', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em','%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid-social.posi_right' => 'right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .eel-team-grid-social.posi_botttom_right' => 'right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [ 
                        'social_icon_position' => ['posi_right','posi_botttom_right'],
                    ],
                ]
            );

            $this->add_control(
                'social_links',
                [
                    'label'       => esc_html__( 'Social Links', 'easy-elements' ),
                    'type'        => Controls_Manager::REPEATER,
                    'fields'      => [
                        [
                            'name'    => 'link_url',
                            'label'   => esc_html__( 'Link URL', 'easy-elements' ),
                            'type'    => Controls_Manager::URL,
                            'default' => [
                                'url'         => '#',
                                'is_external' => true,
                                'nofollow'    => false,
                            ],
                        ],
                        [
                            'name'    => 'icon',
                            'label'   => esc_html__( 'Icon', 'easy-elements' ),
                            'type'    => Controls_Manager::ICONS,
                            'default' => [
                                'value'   => 'fab fa-facebook-f',
                                'library' => 'fa-brands',
                            ],
                        ],
                    ],
                    'default'     => [
                        [
                            'link_url'                => [ 'url' => '#' ],
                            'icon'                    => [ 'value' => 'fab fa-facebook-f' ],
                        ],
                    ],
                    'title_field' => '<i class="{{ icon.value }}"></i> {{{ icon.value.replace(/^(fab fa-|fas fa-|far fa-|fa fa-)/, "") }}}',
                ]
            );

		$this->end_controls_section();

        // Tab STYLE 
        $this->start_controls_section(
            'section_item_per',
            [
                'label' => esc_html__( 'Team Item', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
         );
        
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name'     => 'team_wrap_background',
                    'label'    => __( 'Background', 'easy-elements' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .eel-team-grid .ee--team-img',
                ]
            );

            $this->add_responsive_control(
                'item_padding',
                [
                    'label' => esc_html__( 'Padding', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'item_bdr_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name'     => 'team_border',
                    'selector' => '{{WRAPPER}} .eel-team-grid .ee--team-img',
                ]
            );
            $this->add_control(
                'team_hover_border_color',
                [
                    'label' => esc_html__( 'Item Hover Border Color', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img:hover' => 'border-color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'team_box_shadow',
                    'label' => esc_html__('Box Shadow', 'easy-elements'),
                    'selector' => '{{WRAPPER}} .eel-team-grid .ee--team-img',
                ]
            );
            $this->add_responsive_control(
                'team_content_alignment',
                [
                    'label' => esc_html__( 'Alignment', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'easy-elements' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'easy-elements' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'easy-elements' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-name-deg-wrap' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Image
        $this->start_controls_section(
            'section_image',
            [
                'label' => esc_html__( 'Image', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
         );
            $this->add_responsive_control(
                'image_width',
                [
                    'label' => esc_html__( 'Image Width', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em', '%' ],
                    'range' => [
                        'px' => [
                            'max' => 500,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-team-img-box' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'image_height',
                [
                    'label' => esc_html__( 'Image Height', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em', '%' ],
                    'range' => [
                        'px' => [
                            'max' => 500,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-team-img-box img' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'image_padding',
                [
                    'label' => esc_html__( 'Padding', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-team-img-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'image_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-team-img-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'image_below_bg',
                [
                    'label' => esc_html__( 'Below Background Color', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-team-img-area .eel-image-below-bg' => 'background-color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_responsive_control(
                'image_below_height',
                [
                    'label' => esc_html__( 'Image BG Height', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-team-img-area .eel-image-below-bg' => 'height: {{SIZE}}%;',
                    ],
                ]
            );

            $this->add_control(
                'image_below_position',
                [
                    'label'   => esc_html__( 'Image BG Position', 'easy-elements' ),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => 'top',
                    'options' => [
                        'top'    => esc_html__( 'Top', 'easy-elements' ),
                        'bottom' => esc_html__( 'Bottom', 'easy-elements' ),
                    ],
                    'selectors_dictionary' => [
                        'top'    => 'top: 0; bottom: auto;',
                        'bottom' => 'top: auto; bottom: 0;',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-team-img-area .eel-image-below-bg' => '{{VALUE}}',
                    ],
                ]
            );
            $this->add_responsive_control(
                'image_below_border_radius',
                [
                    'label' => esc_html__( 'Below BG Border Radius', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-team-img-area .eel-image-below-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Name & Designation 
        $this->start_controls_section(
            'section_item',
            [
                'label' => esc_html__( 'Name & Designation Area', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
         );

            $this->add_control(
                '_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-name-deg-wrap' => 'background-color: {{VALUE}}',
                    ],
                    'condition' => [
                        'team_skin!' => ['skin1']
                    ],
                ]
            );

            $this->add_responsive_control(
                'wrap_padding',
                [
                    'label' => esc_html__( 'Padding', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-name-deg-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'wrap_margin',
                [
                    'label' => esc_html__( 'Margin', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-name-deg-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                '_bdr_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-name-deg-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'team_skin!' => ['skin1']
                    ],
                ]
            );

        $this->end_controls_section();

        // Skin 04 Hover Overlay
        $this->start_controls_section(
            'section_skin4_hover_overlay',
            [
                'label' => esc_html__( 'Hover Overlay', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'team_skin' => 'skin4',
                ],
            ]
        );

            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'skin4_overlay_bg',
                    'label' => esc_html__( 'Background', 'easy-elements' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .eel-team-grid.skin4 .eel-team-hover-content',
                ]
            );

            $this->add_responsive_control(
                'skin4_overlay_blur',
                [
                    'label' => esc_html__( 'Backdrop Blur', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [ 'min' => 0, 'max' => 50, 'step' => 1 ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid.skin4 .eel-team-hover-content' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}); -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}});',
                    ],
                ]
            );

            $this->add_control(
                'skin4_overlay_text_color',
                [
                    'label' => esc_html__( 'Text Color', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid.skin4 .eel-team-hover-content' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'skin4_overlay_padding',
                [
                    'label' => esc_html__( 'Padding', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid.skin4 .eel-team-hover-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'skin4_overlay_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid.skin4 .eel-team-hover-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'skin4_overlay_transition',
                [
                    'label' => esc_html__( 'Transition Duration (s)', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid.skin4 .eel-team-hover-content' => 'transition: opacity {{SIZE}}s ease;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Name
        $this->start_controls_section(
            'section_name',
            [
                'label' => esc_html__( 'Name', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
         );

            $this->add_control(
                'name_color',
                [
                    'label' => esc_html__( 'Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-name' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'name_typography',
                    'label' => esc_html__( 'Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-name',
                ]
            );

            $this->add_responsive_control(
                'name_padding',
                [
                    'label' => esc_html__( 'Padding', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Designation
        $this->start_controls_section(
            'section_designation',
            [
                'label' => esc_html__( 'Designation', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
         );

            $this->add_control(
                'designation_color',
                [
                    'label' => esc_html__( 'Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-designation' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'designation_typography',
                    'label' => esc_html__( 'Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-designation',
                ]
            );

        $this->end_controls_section();

        // Description (default, skin1, skin2)
        $this->start_controls_section(
            'section_team_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'team_skin' => [ 'default', 'skin1', 'skin2' ],
                ],
            ]
        );

            $this->add_control(
                'team_description_color',
                [
                    'label' => esc_html__( 'Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-description' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'team_description_typography',
                    'label' => esc_html__( 'Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-team-description',
                ]
            );

            $this->add_responsive_control(
                'team_description_margin',
                [
                    'label' => esc_html__( 'Margin', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'team_description_padding',
                [
                    'label' => esc_html__( 'Padding', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

$this->start_controls_section(
    'section_description',
    [
        'label' => esc_html__( 'Description', 'easy-elements' ),
        'tab'   => Controls_Manager::TAB_STYLE,
        'condition' => [
            'team_skin' => 'skin3',
        ],
    ]
);


$this->start_controls_tabs( 'description_colors' );

// Normal Tab
$this->start_controls_tab(
    'description_colors_normal',
    [
        'label' => esc_html__( 'Normal', 'easy-elements' ),
    ]
);

$this->add_control(
    'description_color',
    [
        'label' => esc_html__( 'Color', 'easy-elements' ),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .eel-image-content .eel-description' => 'color: {{VALUE}};',
        ],
    ]
);

$this->add_group_control(
    \Elementor\Group_Control_Background::get_type(),
    [
        'name' => 'description_background',
        'label' => esc_html__( 'Background Color', 'easy-elements' ),
        'types' => [ 'classic', 'gradient' ],
        'selector' => '{{WRAPPER}} .eel-image-content',
    ]
);

$this->end_controls_tab();

// Hover Tab
$this->start_controls_tab(
    'description_colors_hover',
    [
        'label' => esc_html__( 'Hover', 'easy-elements' ),
    ]
);

$this->add_control(
    'description_color_hover',
    [
        'label' => esc_html__( 'Color', 'easy-elements' ),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .eel-image-content:hover .eel-description' => 'color: {{VALUE}};',
        ],
    ]
);

$this->add_group_control(
    \Elementor\Group_Control_Background::get_type(),
    [
        'name' => 'description_background_hover',
        'label' => esc_html__( 'Background Color', 'easy-elements' ),
        'types' => [ 'classic', 'gradient' ],
        'selector' => '{{WRAPPER}} .eel-image-content:hover:before',
    ]
);

$this->add_control(
    'description_background_hover_opacity',
    [
        'label' => esc_html__( 'Opacity', 'easy-elements' ),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'range' => [
            '' => [
                'min' => 0,
                'max' => 1,
                'step' => 0.1,
            ],
        ],
        'default' => [
            'size' => 0.5,
        ],
        'selectors' => [
            '{{WRAPPER}} .eel-image-content:hover::before' => 'opacity: {{SIZE}};',
        ],
    ]
);


$this->end_controls_tab();

$this->end_controls_tabs();

// Typography control (applies to both normal and hover states)
$this->add_group_control(
    \Elementor\Group_Control_Typography::get_type(),
    [
        'name' => 'description_typography',
        'label' => esc_html__( 'Typography', 'easy-elements' ),
        'selector' => '{{WRAPPER}} .eel-image-content .eel-description',
    ]
);

$this->add_control(
    'description_hover_transition',
    [
        'label' => esc_html__( 'Transition Duration', 'easy-elements' ),
        'type' => Controls_Manager::SLIDER,
        'size_units' => [ 's', 'ms' ],
        'range' => [
            's' => [
                'min' => 0,
                'max' => 3,
                'step' => 0.1,
            ],
            'ms' => [
                'min' => 0,
                'max' => 3000,
                'step' => 100,
            ],
        ],
        'default' => [
            'unit' => 's',
            'size' => 0.3,
        ],
        'selectors' => [
            '{{WRAPPER}} .eel-image-content' => 'transition-duration: {{SIZE}}{{UNIT}};',
        ],
    ]
);

$this->add_responsive_control(
    'description_padding',
    [
        'label' => esc_html__( 'Padding', 'easy-elements' ),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%', 'em' ],
        'selectors' => [
            '{{WRAPPER}} .eel-image-content' => 'padding: {{TOP}}{{UNIT}}} {{RIGHT}}{{UNIT}}} {{BOTTOM}}{{UNIT}}} {{LEFT}}{{UNIT}};',
        ],
    ]
);

$this->add_control(
    'image_descripiton_overlay_note',
    [
        'type' => \Elementor\Controls_Manager::RAW_HTML,
        'raw' => '<div class="eel-popup-note">' . esc_html__('Note: This style only workable when description added for style 3.', 'easy-elements') . '</div>',
        'separator' => 'before',
    ]
);

$this->end_controls_section();

        // Social Item
        $this->start_controls_section(
            'section_social',
            [
                'label' => esc_html__( 'Social Icon', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_social_icon' => 'yes',
                ]
            ]
         );

            $this->start_controls_tabs( 's_icon_color_tabs' );

                $this->start_controls_tab(
                    's_icon_tab_normal',
                    [ 'label' => esc_html__( 'Normal', 'easy-elements' ) ]
                );

                    $this->add_control(
                        's_icon_color',
                        [
                            'label' => esc_html__( 'Color', 'easy-elements' ),
                            'type'  => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-team-grid-social ul li a' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .eel-team-grid-social .eel-team-social-hover a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        's_icon_bg_color',
                        [
                            'label' => esc_html__( 'Background Color', 'easy-elements' ),
                            'type'  => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-team-grid-social ul li a' => 'background: {{VALUE}};',
                                '{{WRAPPER}} .eel-team-grid-social .eel-team-social-hover a' => 'background: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    's_icon_tab_hover',
                    [ 'label' => esc_html__( 'Hover', 'easy-elements' ) ]
                );

                    $this->add_control(
                        's_icon_hover_color',
                        [
                            'label' => esc_html__( 'Color', 'easy-elements' ),
                            'type'  => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-team-grid-social ul li a:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .eel-team-grid-social .eel-team-social-hover a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        's_icon_hover_bg_color',
                        [
                            'label' => esc_html__( 'Background Color', 'easy-elements' ),
                            'type'  => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eel-team-grid-social ul li a:hover' => 'background: {{VALUE}};',
                                '{{WRAPPER}} .eel-team-grid-social .eel-team-social-hover a:hover' => 'background: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 's_icon_typography',
                    'label' => esc_html__( 'Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-team-grid-social ul li a, {{WRAPPER}} .eel-team-grid-social .eel-team-social-hover a',
                ]
            );
            $this->add_responsive_control(
                's_icon_gap',
                [
                    'label' => esc_html__( 'Icon Gap', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em'],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid-social ul' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                's_icon_width-height',
                [
                    'label' => esc_html__( 'Button Size', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em'],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid-social ul li a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .eel-team-grid-social .eel-team-social-hover a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                's_icon_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid-social ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .eel-team-grid-social .eel-team-social-hover a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                's_icon_area_padding',
                [
                    'label' => esc_html__( 'Area Padding', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid-social' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'social_item_border',
                    'label' => esc_html__( 'Area Border', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-team-grid .eel-team-grid-social.default',
                     'condition' => [
                        'social_icon_position' => 'default',
                    ]
                ]
            );
            $this->add_control(
                'social_item_border_note',
                [
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<strong>Note:</strong>Social Area Border',
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                    'condition' => [
                        'social_icon_position' => 'default',
                    ]
                ]
            );
            $this->add_responsive_control(
                'team_social_icon_alignment',
                [
                    'label' => esc_html__( 'Alignment', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__( 'Left', 'easy-elements' ),
                            'icon'  => 'eicon-justify-start-h',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'easy-elements' ),
                            'icon'  => 'eicon-justify-center-h',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'easy-elements' ),
                            'icon'  => 'eicon-justify-end-h',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-grid .ee--team-img .eel-team-grid-social.default ul' => 'justify-content: {{VALUE}};',
                    ],
                    'condition' => [
                        'social_icon_position' => 'default',
                    ]
                ]
            );

        $this->end_controls_section();
        
        // Popup
        $this->start_controls_section(
            'section_popup_style',
            [
                'label' => esc_html__( 'Popup Style', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'action_type' => 'popup',
                ],
            ]
         );

            $this->add_control(
                'popup_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-popup-content' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'popup_name_color',
                [
                    'label' => esc_html__( 'Name Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-popup-name .eel-name' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'popup_name_typography',
                    'label' => esc_html__( 'Name Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-popup-name .eel-name',
                ]
            );

            $this->add_control(
                'popup_designation_color',
                [
                    'label' => esc_html__( 'Designation Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-popup-designation' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'popup_designation_typography',
                    'label' => esc_html__( 'Designation Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-popup-designation',
                ]
            );

            $this->add_control(
                'popup_details_color',
                [
                    'label' => esc_html__( 'Details Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-popup-details' => 'color: {{VALUE}};',
                    ],
                ]
            );           
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'popup_details_typography',
                    'label' => esc_html__( 'Details Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-popup-details',
                ]
            );
            $this->add_control(
                'popup_close_color',
                [
                    'label' => esc_html__( 'Close Icon Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-popup-close' => 'color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $team_skin = $settings['team_skin'];
        ?>
        
        <div class="eel-team-wraps eel-team-grid grid-layout <?php echo esc_attr( $team_skin )?>">
            <div class="grid-wrap">
                <?php
                $image_id = $settings['image']['id'] ?? '';
                $image_size = $settings['image_size'] ?? 'full';
                if ( $image_id ) {
                    $image_data = wp_get_attachment_image_src( $image_id, $image_size );
                    $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                    $title = get_the_title( $image_id );
                } else {
                    $fallback_url = Utils::get_placeholder_image_src();
                    $image_data = [ $fallback_url, 600, 400 ];
                    $alt = esc_attr__( 'Team Image', 'easy-elements' );
                    $title = esc_attr__( 'Team Image', 'easy-elements' );
                }

                $action_type = $settings['action_type'] ?? 'link';
                $link     = $settings['link']['url'] ?? '';
                $target   = ! empty( $settings['link']['is_external'] ) ? '_blank' : '';
                $nofollow = ! empty( $settings['link']['nofollow'] ) ? 'nofollow' : '';
                $fetchpriority = $settings['fetchpriority'] ?? '';
                $unique_id = uniqid('eel_team_');

                $title_tag = isset( $settings['title_tag'] ) ? $settings['title_tag'] : 'h4';
                $name = sprintf( '<%1$s class="eel-name">%2$s</%1$s>', esc_attr( $title_tag ), esc_html( $settings['_name'] ));
                $designation = $settings['designation'] ?? '';
                $details = $settings['details'] ?? '';
                ?>
                <?php if ($team_skin == 'default'): ?>
                    <div class="grid-item">
                        <div class="ee--team-img">
                            <?php if ( $image_data ) : ?>
                                <div class="eel-team-img-area">
                                    <?php if ( $action_type === 'link' && $link ) : ?>
                                        <a href="<?php echo esc_url( $link ); ?>"
                                        <?php if ( $target ) : ?>target="<?php echo esc_attr( $target ); ?>"<?php endif; ?>
                                        <?php if ( $nofollow ) : ?>rel="<?php echo esc_attr( $nofollow ); ?>"<?php endif; ?>>
                                    <?php elseif ( $action_type === 'popup' ) : ?>
                                        <a href="#<?php echo esc_attr($unique_id); ?>" class="eel-popup-trigger" data-popup-id="<?php echo esc_attr($unique_id); ?>">
                                    <?php endif; ?>
                                    <div class="eel-team-img-box">
                                        <img class="eel-team-img"
                                        src="<?php echo esc_url( $image_data[0] ); ?>"
                                        width="<?php echo esc_attr( $image_data[1] ); ?>"
                                        height="<?php echo esc_attr( $image_data[2] ); ?>"
                                        alt="<?php echo esc_attr( $alt ); ?>"
                                        title="<?php echo esc_attr( $title ); ?>"
                                        loading="lazy"
                                        decoding="async" fetchpriority="<?php echo esc_attr( $fetchpriority ); ?>">
                                        <div class="eel-image-below-bg"></div>
                                        <div class="eel-image-overlay"></div>
                                    </div>
                                    <?php if ( ($action_type === 'link' && $link) || $action_type === 'popup' ) : ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if($settings['content_show'] == 'inside'):?>
                                        <div class="eel-name-deg-wrap <?php echo esc_attr( $settings['content_show'] )?>">
                                            <?php if ( ! empty( $name ) ) :
                                                echo wp_kses_post( $name );
                                            endif; ?>
                                            <?php if ( ! empty( $designation ) ) : ?>
                                                <div class="eel-designation"><?php echo esc_html( $designation ); ?></div>
                                                <?php if ( ! empty( $details ) ) : ?>
                                                    <div class="eel-team-description"><?php echo nl2br( esc_html( $details ) ); ?></div>
                                                <?php endif; ?>
                                                <?php if ( $settings['social_links'] && $settings['show_social_icon'] == 'yes' && $settings['social_icon_position'] == 'default'): ?>
                                                    <div class="eel-team-grid-social <?php echo esc_attr( $settings['social_icon_position'].' '.$settings['social_icon_show'] )?>">
                                                        <?php if( $settings['social_icon_show'] =='hover_show' && !empty( $settings['social_hover_icon']['value']) ): ?>
                                                            <div class="eel-team-social-hover">
                                                                <a href="#">
                                                                    <?php \Elementor\Icons_Manager::render_icon( $settings['social_hover_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                                                </a>
                                                            </div>
                                                        <?php endif;?>
                                                        <ul>
                                                            <?php foreach (  $settings['social_links'] as $item ):?>
                                                                <li>
                                                                    <a href="<?php echo esc_attr( $item['link_url']['url'] )?>">
                                                                        <?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                                                    </a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if($settings['content_show'] != 'inside'):?>
                                <div class="eel-name-deg-wrap">
                                    <?php if ( ! empty( $name ) ) :
                                        echo wp_kses_post( $name );
                                    endif; ?>
                                    <?php if ( ! empty( $designation ) ) : ?>
                                        <div class="eel-designation"><?php echo esc_html( $designation ); ?></div>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $details ) ) : ?>
                                        <div class="eel-team-description"><?php echo nl2br( esc_html( $details ) ); ?></div>
                                    <?php endif; ?>
                                    <?php if ( $settings['social_links'] && $settings['show_social_icon'] == 'yes' && $settings['social_icon_position'] == 'default'): ?>
                                        <div class="eel-team-grid-social <?php echo esc_attr( $settings['social_icon_position'].' '.$settings['social_icon_show'] )?>">
                                            <?php if( $settings['social_icon_show'] =='hover_show' && !empty( $settings['social_hover_icon']['value']) ): ?>
                                                <div class="eel-team-social-hover">
                                                    <a href="#">
                                                        <?php \Elementor\Icons_Manager::render_icon( $settings['social_hover_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                                    </a>
                                                </div>
                                            <?php endif;?>
                                            <ul>
                                                <?php foreach (  $settings['social_links'] as $item ):?>
                                                    <li>
                                                        <a href="<?php echo esc_attr( $item['link_url']['url'] )?>">
                                                            <?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ( $settings['social_links'] && $settings['show_social_icon'] == 'yes' && $settings['social_icon_position'] != 'default'): ?>
                                <div class="eel-team-grid-social <?php echo esc_attr( $settings['social_icon_position'].' '.$settings['social_icon_show'] )?>">
                                    <?php if( $settings['social_icon_show'] =='hover_show' && !empty( $settings['social_hover_icon']['value']) ): ?>
                                        <div class="eel-team-social-hover">
                                            <a href="#">
                                                <?php \Elementor\Icons_Manager::render_icon( $settings['social_hover_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                            </a>
                                        </div>
                                    <?php endif;?>
                                    <ul>
                                        <?php foreach (  $settings['social_links'] as $item ):?>
                                            <li>
                                                <a href="<?php echo esc_attr( $item['link_url']['url'] )?>">
                                                    <?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <!-- Others Skins -->
                <?php else:
                  include plugin_dir_path(__FILE__) . 'skins/' . $team_skin . '.php';
                 endif;
                ?>
                <?php if ( $action_type === 'popup' ) : ?>
                    <div id="<?php echo esc_attr($unique_id); ?>" class="eel-popup-modal" style="display:none;">
                        <div class="eel-popup-content">
                            <span class="eel-popup-close">&times;</span>
                            <div class="eel-popup-header">
                                <?php if ( ! empty( $name ) ) : ?>
                                    <div class="eel-popup-name"><?php echo wp_kses_post( $name ); ?></div>
                                <?php endif; ?>
                                <?php if ( ! empty( $designation ) ) : ?>
                                    <div class="eel-popup-designation"><?php echo esc_html( $designation ); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="eel-popup-details">
                                <?php if ( ! empty( $details ) ) : ?>
                                    <?php echo nl2br( esc_html( $details ) ); ?>
                                <?php else : ?>
                                    <p><?php esc_html_e( 'No additional details available.', 'easy-elements' ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}