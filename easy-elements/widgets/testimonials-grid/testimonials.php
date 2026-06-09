<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

class Easyel_Testimonials__Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'eel-testimonials';
    }

    public function get_title() {
        return esc_html__( 'Testimonials Grid', 'easy-elements' );
    }

    public function get_icon() {
        return 'easyicon easyelIcon-testimonials-grid';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'testimonials', 'clients', 'feedback', 'partner', 'text' ];
    }

    public function get_style_depends() {
        return [
            'eel-testimonials',
        ];
    }

    public function get_script_depends() {
        return [
            'eel-testimonials',
        ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            '_section_layout',
            [
                'label' => esc_html__( 'Layout Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'testimonials_skin',
            [
                'label'   => esc_html__('Skin Type', 'easy-elements'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default'   => esc_html__('default', 'easy-elements'),
                    'skin1' => esc_html__('Skin 01', 'easy-elements'),
                    'skin2' => esc_html__('Skin 02', 'easy-elements'),
                    'skin3' => esc_html__('Skin 03', 'easy-elements'),
                    'skin4' => esc_html__('Skin 04', 'easy-elements'),
                    'skin5' => esc_html__('Skin 05', 'easy-elements'),
                    'skin6' => esc_html__('Skin 06', 'easy-elements'),
                ],
            ]
        );

        $this->add_control(
            'avatar_image_top',
            [
                'label'   => esc_html__('Avatar Image Top', 'easy-elements'),
                'type'    => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'condition' => [
                    'testimonials_skin' => ['skin2'],
                ],
            ]
        );
        
        $this->add_control(
            'show_loadmore',
            [
                'label' => esc_html__( 'View All Button', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'default' => 'no',
                'condition' => [
                    'testimonials_skin' => ['skin3'],
                ],
                'description' => sprintf(
                    // Translators: %s is the number of items after which the button will work.
                    '<strong>%s</strong>',
                    sprintf(
                        // Translators: %s is the number of items after which the button will work.
                        __('This button will work after %s items', 'easy-elements'),
                        6
                    )
                ),

            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label' => esc_html__('View all reviews', 'easy-elements'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'View all reviews', 'easy-elements' ),
                'label_block' => true,
                'condition' => [
                    'testimonials_skin' => 'skin3',
                    'show_loadmore' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_logo',
            [
                'label' => esc_html__( 'Testimonials Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Picture', 'easy-elements'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'name',
            [
                'label' => esc_html__('Name', 'easy-elements'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Stefan Sears', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'designation',
            [
                'label' => esc_html__('Designation', 'easy-elements'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Developer, Easy Elements Inc', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'easy-elements'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Enter Stefan Sears Description', 'easy-elements' ),
            ]
        );

       $repeater->add_control(
           'quote_icon',
           [
                'label'       => esc_html__( 'Quote Icon', 'easy-elements' ),
                'type'        => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default'     => [
                    'value'   => 'fas fa-quote-right',
                    'library' => 'fa-solid',
                ],
                'description' => sprintf(
                    '<strong>%s</strong>',
                    __('Only Default skin supported', 'easy-elements')
                ),

           ]
        );
        $repeater->add_control(
            'show_quote_icon_skin1',
            [
                'label'        => esc_html__( 'Show Quote Icon on Skin 01', 'easy-elements' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'easy-elements' ),
                'label_off'    => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default'      => '',
                'separator'    => 'after',
            ]
        );
        $repeater->add_control(
            'rating',
            [
                'label'   => esc_html__( 'Rating', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '★☆☆☆☆',
                    '2' => '★★☆☆☆',
                    '3' => '★★★☆☆',
                    '4' => '★★★★☆',
                    '5' => '★★★★★',
                ],
                'default' => '5',
                'separator' => 'after',
                'description' => sprintf(
                    '<strong>%s</strong>',
                    __('[skin1, skin2, skin4] skins type not supported', 'easy-elements')
                ),

            ]
        );
        $repeater->add_control(
            'logo_company',
            [
                'label' => esc_html__('Logo', 'easy-elements'),
                'type' => Controls_Manager::MEDIA,
                'default' => [ '' ],
                'description' => sprintf(
                    '<strong>%s</strong>',
                    __('Default skin not supported', 'easy-elements')
                ),

            ]
        );

        $this->add_control(
            'easy_testimonials',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default' => array_fill( 0, 4, [
                    'image' => [ 'url' => Utils::get_placeholder_image_src() ],
                    'name' => esc_html__( 'Stefan Sears', 'easy-elements' ),
                    'description' => esc_html__( 'This service exceeded all my expectations. The team was professional, fast, and truly cared about delivering a top-notch experience from start to finish.', 'easy-elements' ),
                ]),
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
            'show_image',
            [
                'label' => esc_html__( 'Show Image', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Select Columns', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '3',
                'mobile_default' => '2',
                'options' => [
                    '1' => '1 Column',
                    '2' => '2 Columns',
                    '3' => '3 Columns',
                    '4' => '4 Columns',
                    '5' => '5 Columns',
                    '6' => '6 Columns',
                ],
                'selectors' => [
                    '{{WRAPPER}} .e-e-testimonial .grid-item' => 'width: calc(100% / {{VALUE}});',
                ],
                'condition' => [
                    'testimonials_skin!' => ['default', 'skin4'],
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__( 'Item Padding', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .e-e-testimonial .grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_height',
            [
                'label' => esc_html__( 'Logo Height', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 500,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap.skin1 .eel-company-logo img, {{WRAPPER}} .eel-company-logo img' => 'height: {{SIZE}}{{UNIT}}; width:auto;',
                ],
                'condition' => [ 'testimonials_skin!' => 'default' ],
                'description' => sprintf(
                    '<strong>%s</strong>',
                    esc_html__( 'Works only if a Logo is added to testimonial items', 'easy-elements' )
                ),
            ]
        );

        $this->add_responsive_control(
            'testimonials_alignment',
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
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-description' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-author' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-name' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-designation' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-quote' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-company-logo' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-author-wrap' => 'justify-content: {{VALUE}}; text-align: {{VALUE}};',
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-rating' => 'justify-content: {{VALUE}}; text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'show_rating',
            [
                'label' => esc_html__( 'Show Rating', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [ 
                    'testimonials_skin!' => ['skin1','skin2','skin4'],
                ],
            ]
        );
        $this->add_control(
            'rating_color',
            [
                'label' => esc_html__( 'Rating Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-rating span.star' => 'color: {{VALUE}};',
                ],
                'condition' => [ 
                    'testimonials_skin!' => ['skin1','skin2','skin4'],
                    'show_rating' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'rating_size',
            [
                'label' => esc_html__( 'Rating Size', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .eel-rating span.star' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [ 
                    'testimonials_skin!' => ['skin1','skin2','skin4'],
                    'show_rating' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_icon',
            [
                'label'       => esc_html__( 'Title Icon', 'easy-elements' ),
                'type'        => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default'     => [
                    'value'   => 'fas fa-check-circle',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'testimonials_skin' => ['skin1'],
                ],
            ]
         );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__( 'Item', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'bg',
                'label' => __('Background', 'easy-elements'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .ee--tstml-inner-wrap',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => __('Border', 'easy-elements'),
                'selector' => '{{WRAPPER}} .ee--tstml-inner-wrap',
            ]
        );


        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'label' => __('Box Shadow', 'easy-elements'),
                'selector' => '{{WRAPPER}} .ee--tstml-inner-wrap',
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => __('Padding', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_gap',
            [
                'label' => __('Gap', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'testimonials_skin' => 'default'
                ]
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            '_section_name',
            [
                'label' => esc_html__( 'Name', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .ee--tstml-inner-wrap .eel-name',
            ]
        );
         $this->add_responsive_control(
            'name_margin',
            [
                'label' => __('Margin', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-author-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_designation',
            [
                'label' => esc_html__( 'Designation', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'designation_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-designation' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'designation_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .ee--tstml-inner-wrap .eel-designation',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .ee--tstml-inner-wrap .eel-description',
            ]
        );

        $this->add_responsive_control(
		    'description_margin',
		    [
		        'label' => esc_html__( 'Description Margin', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%', 'em' ],
		        'selectors' => [
		            '{{WRAPPER}} .ee--tstml-inner-wrap .eel-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

        $this->add_responsive_control(
            'min_height',
            [
                'label' => esc_html__( 'Min Height', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 500,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-description' => 'min-height: {{SIZE}}{{UNIT}}; width:auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'max_width',
            [
                'label' => esc_html__( 'Max Width', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 500,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-description' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'author_image',
            [
                'label' => esc_html__( 'Author Image Settings', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_image' => 'yes',
                ],                
            ]
        );

        $this->add_control(
            'author_meta_alignment',
            [
                'label'   => esc_html__( 'Author Info Alignment', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start'   => [
                        'title' => esc_html__( 'flex-start', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'item-center', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'flex-end'  => [
                        'title' => esc_html__( 'flex-end', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-author-wrap' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'author_meta_alignment_style4',
            [
                'label'     => esc_html__( 'Alignment', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'easy-elements' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'easy-elements' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'easy-elements' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap.skin4 .eel-author' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'testimonials_skin' => 'skin4',
                ],
            ]
        );

        $this->add_responsive_control(
            'author_meta_gap',[
                'label' => esc_html__( 'Author Info Gap', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--tstml-inner-wrap .eel-author-wrap .eel-picture' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],                
            ]  
        );

        $this->add_responsive_control(
            'author_image_size',
            [
                'label'      => esc_html__( 'Image Size', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'size' => 50,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-author-wrap .eel-picture img' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; object-fit: cover;',
                    '{{WRAPPER}} .eel-picture img' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; object-fit: cover;',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-author-wrap .eel-picture img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eel-picture img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();  

        $this->start_controls_section(
            'quote_icon_styles',
            [
                'label' => esc_html__( 'Quote Icon', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'testimonials_skin' => ['default', 'skin1'],
                ],
            ]
        );
        
        $this->add_control(
            'quote_icon_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-quote svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eel-quote svg path' => 'fill: {{VALUE}};',
                ],
                'description' => sprintf(
                    '<strong>%s</strong>',
                    esc_html__( 'Works only if a Quote Icon is added to testimonial items', 'easy-elements' )
                ),
            ]
        );
        $this->add_responsive_control(
            'quote_icon_size',
            [
                'label'      => esc_html__( 'Size', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .eel-quote svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // Style 3 View All Button
        $this->start_controls_section(
            'style3_view_all_button',
            [
                'label' => esc_html__( 'View All Button', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'testimonials_skin' => 'skin3',
                    'show_loadmore' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'load_more_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-testimonial-more-btn',
            ]
        );
        $this->add_control(
            'load_more_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-testimonial-more-btn' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'load_more_bg',
                'label' => esc_html__('Background', 'easy-elements'),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel-testimonial-more-btn',
            ]
        );
        $this->add_responsive_control(
            'loadmore_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-testimonial-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'loadmore_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-testimonial-more-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'load_more_border',
                'label' => __('Border', 'easy-elements'),
                'selector' => '{{WRAPPER}} .eel-testimonial-more-btn',
            ]
        );
        $this->add_control(
			'load_more_hover_bg_heading',
			[
				'label' => esc_html__( 'Hover Style', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
            'load_more_hover_color',
            [
                'label' => esc_html__( 'Hover Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-testimonial-more-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'load_more_hover_bg',
                'label' => esc_html__('Hover Background', 'easy-elements'),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel-testimonial-more-btn:hover',
            ]
        );
        $this->add_control(
            'load_more_hover_border_color',
            [
                'label' => esc_html__( 'Hover Border Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-testimonial-more-btn:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();            
        
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings['easy_testimonials'] ) ) {
            return;
        }
        ?>
        
        <div class="e-e-testimonial grid-layout">
            <div class="grid-wrap">
                <?php $count = 0; foreach ( $settings['easy_testimonials'] as $item ) :
                    $count++;
                    $image_id = $item['image']['id'] ?? '';
                    // Safely get image size, supporting both array and string cases
                    if ( isset( $settings['image_size'] ) ) {
                        if ( is_array( $settings['image_size'] ) && isset( $settings['image_size']['size'] ) ) {
                            $image_size = $settings['image_size']['size'];
                        } else {
                            $image_size = $settings['image_size'];
                        }
                    } else {
                        $image_size = 'full';
                    }
                    if ( $image_id ) {
                        $image_data = wp_get_attachment_image_src( $image_id, $image_size );
                        $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                        $title = get_the_title( $image_id );
                    } else {
                        $fallback_url = Utils::get_placeholder_image_src();
                        $image_data = [ $fallback_url, 600, 400 ];
                        $alt = esc_attr__( 'Sample Image', 'easy-elements' );
                        $title = esc_attr__( 'Sample Image', 'easy-elements' );
                    }

                    $logo_company_id = $item['logo_company']['id'] ?? '';
                    if ( $logo_company_id ) {
                        $logo_data = wp_get_attachment_image_src( $logo_company_id, $image_size );
                        $logo_url = $logo_data[0] ?? '';
                        $logo_alt = get_post_meta( $logo_company_id, '_wp_attachment_image_alt', true );
                        $logo_title = get_the_title( $logo_company_id );
                    }

                    $raw_skin = $settings['testimonials_skin'] ?? 'default';
                    // Sanitize to a slug so the value can never contain
                    // path separators, dots, or anything that could let
                    // realpath() resolve outside skins/ below.
                    $skin = preg_replace( '/[^a-z0-9_-]/i', '', (string) $raw_skin );
                    if ( '' === $skin ) {
                        $skin = 'default';
                    }

                    $skins_root  = realpath( plugin_dir_path( __FILE__ ) . 'skins' );
                    $skin_target = false !== $skins_root
                        ? realpath( $skins_root . DIRECTORY_SEPARATOR . $skin . '.php' )
                        : false;

                    $classes = 'not-hidden';
                    if ( isset( $count ) && $count > 6 ) {
                        $classes .= ' eel-hidden-testimonial';
                    }
                    ?>
                    <div class="grid-item <?php echo esc_attr($classes); ?> testimonials--<?php echo esc_attr($skin); ?>">
                        <div class="ee--testimonial">
                            <?php
                                if ( false !== $skin_target
                                    && false !== $skins_root
                                    && 0 === strpos( $skin_target, $skins_root . DIRECTORY_SEPARATOR )
                                    && substr( $skin_target, -4 ) === '.php'
                                ) {
                                    include $skin_target;
                                }
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ( $count > 6 ) : ?>  
                <?php if($settings['show_loadmore'] == 'yes'): ?>                         
                <div class="eel-testimonial-more-btn"> <?php echo esc_html($settings['load_more_text']); ?> </div>    
                <?php endif; ?>          
            <?php endif; ?>
        </div>
        <?php
    }
}


