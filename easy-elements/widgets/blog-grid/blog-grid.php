<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

class Easyel_Blog_Grid__Widget extends \Elementor\Widget_Base {   
    protected function get_all_posts() {
       $posts = get_posts( [ 'numberposts' => -1 ] );
       $options = [];
       foreach ( $posts as $post ) {
           $options[ $post->ID ] = $post->post_title;
       }
       return $options;
   }

    protected function get_post_type_options() {
        $exclude_post_types = ['attachment', 'revision', 'nav_menu_item', 'custom_post_type_slug'];

        $post_types = get_post_types(['public' => true], 'objects');
        $options = [];

        foreach ($post_types as $post_type => $post_type_obj) {
            if (!in_array($post_type, $exclude_post_types)) {
                $taxonomies = get_object_taxonomies($post_type, 'names');
                if (!empty($taxonomies)) {
                    $options[$post_type] = $post_type_obj->label;
                }
            }
        }
        return $options;
    }

    protected function get_all_categories() {
       $cats = get_categories( [ 'hide_empty' => false ] );
       $options = [];
       foreach ( $cats as $cat ) {
           $options[ $cat->term_id ] = $cat->name;
       }
       return $options;
    }


    public function get_name() {
        return 'eel-blog-grid';
    }

    public function get_title() {
        return esc_html__( 'Post Grid', 'easy-elements' );
    }

    public function get_icon() {
        return 'easyicon easyelIcon-post-grid';
    }

    public function get_categories() {
        return [ 'easyicon easyelIcon-post-grid' ];
    }

    public function get_keywords() {
        return [ 'blog', 'grid', 'post', 'news' ];
    }

    public function get_style_depends() {
        return [
            'eel-blog-grid',
        ];
    }

    protected function register_controls() {       

        $this->start_controls_section(
            '_section_grid_query',
            [
                'label' => esc_html__( 'Query Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => esc_html__( 'Post Type', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'options' =>  $this->get_post_type_options(),
                'default' => 'post',
                'description' =>  '',
            ]
        );

        $options = [
            'category' => esc_html__( 'Category', 'easy-elements' ),
            'post'     => esc_html__( 'Specific Posts', 'easy-elements' ),
        ];

        if ( apply_filters( 'easyel/pro_enabled', false ) ) {
            $options['user']     = esc_html__( 'User', 'easy-elements' );
            $options['userrole'] = esc_html__( 'UserRole', 'easy-elements' );
        }

        $this->add_control(
            'source_type',
            [
                'label' => esc_html__( 'Source Type', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'options' => $options,
                'default' => 'category',
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => esc_html__( 'Select Categories', 'easy-elements' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_all_categories(),
                'label_block' => true,
                'condition' => [
                    'source_type' => 'category',
                ],
            ]
        );

        $this->add_control(
            'exclude_categories',
            [
                'label' => esc_html__( 'Exclude Categories', 'easy-elements' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_all_categories(),
                'label_block' => true,
                'condition' => [
                    'source_type' => 'category',
                ],
            ]
        );

        $this->add_control(
            'post__in',
            [
                'label' => esc_html__( 'Select Specific Posts', 'easy-elements' ),
                'type' =>  Controls_Manager::SELECT2,
                'options' =>  $this->get_all_posts(),
                'multiple' =>  true,
                'label_block' => true,
                'description' =>  '',
                'condition' => [
                    'source_type' => 'post',
                ],
            ]
        );

        $this->add_control(
            'exclude_posts',
            [
                'label' => esc_html__( 'Exclude Posts', 'easy-elements' ),
                'type' =>  \Elementor\Controls_Manager::SELECT2,
                'multiple' =>  true,
                'options' =>  $this->get_all_posts(),
                'label_block' => true,
                'description' =>  '',
                'condition' => [
                    'source_type' => 'post',
                ],
            ]
        );

        if ( apply_filters( 'easyel/pro_enabled', false ) ) {

           do_action( 'easyel/userdata/source/control', $this );
        }

        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__( 'Posts Per Page', 'easy-elements' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );
        
        $this->add_control(
            'offset',
            [
                'label'       => esc_html__( 'Offset', 'easy-elements' ),
                'type'        => Controls_Manager::NUMBER,
                'description' => esc_html__( 'Skip posts from the top of the query.', 'easy-elements' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'       => esc_html__( 'Order By', 'easy-elements' ),
                'type'        => Controls_Manager::SELECT,
                'options'     =>  [
                        'date'       => esc_html__( 'Date', 'easy-elements' ),
                        'title'      => esc_html__( 'Title', 'easy-elements' ),
                        'rand'       => esc_html__( 'Random', 'easy-elements' ),
                        'menu_order' => esc_html__( 'Menu Order', 'easy-elements' ),
                    ],
                'default'     =>  'date',
                'description' =>  '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'order',
            [
                'label'       => esc_html__( 'Order', 'easy-elements' ),
                'type'        => Controls_Manager::SELECT,
                'options'     =>  [
                        'DESC' => esc_html__( 'Descending', 'easy-elements' ),
                        'ASC'  => esc_html__( 'Ascending', 'easy-elements' ),
                    ],
                'default'     =>  'DESC',
                'description' =>  '',
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            '_section_grid',
            [
                'label' => esc_html__( 'Posts Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
           'title_tag',
           [
               'label' => esc_html__( 'Title HTML Tag', 'easy-elements' ),
               'type' => Controls_Manager::SELECT,
               'options' => [
                   'h1' => 'H1',
                   'h2' => 'H2',
                   'h3' => 'H3',
                   'h4' => 'H4',
                   'h5' => 'H5',
                   'h6' => 'H6',
               ],
               'default' => 'h3',
           ]
        ); 

        $this->add_control(
            'title_trim',
            [
                'label' => esc_html__( 'Title Trim Words', 'easy-elements' ),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Limit number of words in the post title', 'easy-elements'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => esc_html__( 'Show Excerpt', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'excerpt_trim',
            [
                'label' => esc_html__( 'Excerpt Trim Words', 'easy-elements' ),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Limit number of words in the excerpt', 'easy-elements'),
                'separator' => 'before',
                'condition' => [
                    'show_excerpt' => 'yes',
                ]
            ]
        );            

        $this->add_control(
            'item_alignment',
            [
                'label' => __( 'Alignment', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'easy-elements' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'easy-elements' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'easy-elements' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'separator' => 'before',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .grid-item' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Select Columns', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
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
                    '{{WRAPPER}} .eel-post-grid-wrap' => 
                        'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
                'separator' => 'before',
            ]
        );


        $this->add_responsive_control(
            'flex_gap',
            [
                'label' => esc_html__( 'Column Spacing', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            '_section_meta',
            [
                'label' => esc_html__( 'Meta Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => esc_html__('Show Meta', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'date'      => esc_html__('Date', 'easy-elements'),
                    'author'    => esc_html__('Author', 'easy-elements'),
                    'category'  => esc_html__('Category', 'easy-elements'),
                    'comments'  => esc_html__('Comments', 'easy-elements'),
                    'read_time'   => esc_html__('Reading Time', 'easy-elements'),
                ],
                'default' => ['date', 'author'],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'meta_order_heading',
            [
                'label' => esc_html__('Meta Order (1 = First)', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'meta_order_date',
            [
                'label' => esc_html__('Date', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
                'max' => 5,
                'condition' => [ 'show_meta' => 'date' ],
            ]
        );

        $this->add_control(
            'meta_order_author',
            [
                'label' => esc_html__('Author', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 2,
                'min' => 1,
                'max' => 5,
                'condition' => [ 'show_meta' => 'author' ],
            ]
        );

        $this->add_control(
            'meta_order_category',
            [
                'label' => esc_html__('Category', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 5,
                'condition' => [ 'show_meta' => 'category' ],
            ]
        );

        $this->add_control(
            'meta_order_comments',
            [
                'label' => esc_html__('Comments', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 4,
                'min' => 1,
                'max' => 5,
                'condition' => [ 'show_meta' => 'comments' ],
            ]
        );

        $this->add_control(
            'meta_order_read_time',
            [
                'label' => esc_html__('Reading Time', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 5,
                'condition' => [ 'show_meta' => 'read_time' ],
            ]
        );

        $this->add_control(
            'show_date_icon',
            [
                'label' => esc_html__( 'Show Date Icon', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => 'yes',
                'condition' => [
                    'show_meta' => 'date',
                ]
            ]
        );

        $this->add_control(
            'date_icon',
            [
                'label' => esc_html__( 'Date Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_date_icon' => 'yes',
                    'show_meta' => 'date',
                ],
            ]
        );

        $this->add_control(
            'show_by_label',
            [
                'label'        => __( 'Show "By" Label', 'easy-elements' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'easy-elements' ),
                'label_off'    => __( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'author_link_enable',
            [
                'label'        => __( 'Author Link', 'easy-elements' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'easy-elements' ),
                'label_off'    => __( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );


        $this->add_control(
            'author_icon_type',
            [
                'label' => esc_html__( 'Author Indicator', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'none'   => esc_html__( 'None', 'easy-elements' ),
                    'icon'   => esc_html__( 'Icon', 'easy-elements' ),
                    'avatar' => esc_html__( 'Avatar', 'easy-elements' ),
                ],
                'condition' => [
                    'show_meta' => 'author',
                ],
            ]
        );

        $this->add_control(
            'author_icon',
            [
                'label' => esc_html__( 'Author Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'author_icon_type' => 'icon',
                    'show_meta' => 'author',
                ],
            ]
        );

        $this->add_responsive_control(
            'author_avatar_size',
            [
                'label' => esc_html__( 'Avatar Size', 'easy-elements' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 16, 'max' => 100 ],
                ],
                'default' => [ 'size' => 22, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-author.eel--has-avatar .eel--author-avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'author_icon_type' => 'avatar',
                    'show_meta' => 'author',
                ],
            ]
        );

        $this->add_responsive_control(
            'author_avatar_border_radius',
            [
                'label' => esc_html__( 'Avatar Border Radius', 'easy-elements' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                    '%'  => [ 'min' => 0, 'max' => 100 ],
                ],
                'size_units' => [ 'px', '%' ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-author.eel--has-avatar .eel--author-avatar' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'author_icon_type' => 'avatar',
                    'show_meta' => 'author',
                ],
            ]
        );


        $this->add_control(
            'show_category_icon',
            [
                'label' => esc_html__( 'Show Category Icon', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => 'yes',
                'condition' => [
                    'show_meta' => 'category',
                ]
            ]
        );

        $this->add_control(
            'category_icon',
            [
                'label'   => esc_html__( 'Category Icon', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => '',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_category_icon' => 'yes',
                    'show_meta' => 'category',
                ],
            ]
        );

        $this->add_control(
            'show_comments_icon',
            [
                'label' => esc_html__( 'Show Comments Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => 'yes',
                'condition' => [
                    'show_meta' => 'comments',
                ]
            ]
        );

        $this->add_control(
            'comments_icon',
            [
                'label' => esc_html__( 'Comments Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'show_comments_icon' => 'yes',
                    'show_meta' => 'comments',
                ],
            ]
        );

        $this->add_control(
            'show_read_time_icon',
            [
                'label' => esc_html__('Show Reading Time Icon', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'read_time_icon',
            [
                'label' => esc_html__('Reading Time Icon', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'show_read_time_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_date_on_top',[
            'label' => esc_html__( 'Date Badge', 'easy-elements' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__( 'Yes', 'easy-elements' ),
            'label_off' => esc_html__( 'No', 'easy-elements' ),
            'default' => 'no',
            ]
        );

        $this->add_control(
            'show_category_badge',[
            'label' => esc_html__( 'Category Badge', 'easy-elements' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__( 'Yes', 'easy-elements' ),
            'label_off' => esc_html__( 'No', 'easy-elements' ),
            'default' => 'no',
            ]
        );

        $this->add_responsive_control(
            'meta_icon_offset',
            [
                'label' => esc_html__( 'Meta Icon Offset (px)', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta svg, {{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta i' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'meta_position',
            [
                'label'       => esc_html__( 'Meta Position', 'easy-elements' ),
                'type'        => \Elementor\Controls_Manager::CHOOSE,
                'default'     => 'up_title',
                'options'     => [
                    'up_title' => [
                        'title' => esc_html__( 'Above Title', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'below_title' => [
                        'title' => esc_html__( 'Below Title', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'below_content' => [
                        'title' => esc_html__( 'Below Content', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'toggle' => false,
            ]
        );


        $this->add_control(
            'eel_separator',
            [
                'label' => esc_html__( 'Separator Type', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    ''         => esc_html__( 'None', 'easy-elements' ),
                    'line'     => esc_html__( 'Line', 'easy-elements' ),
                    'circle'   => esc_html__( 'Circle', 'easy-elements' ),
                ],
                'default' => '', 
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => __( 'Separator Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--separator--line li + li::before, {{WRAPPER}} .eel--separator--circle li + li::before' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eel_separator!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_offset',
            [
                'label' => esc_html__( 'Separator Offset (px)', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--separator--line li + li::before, {{WRAPPER}} .eel--separator--circle li + li::before' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eel_separator!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_post_thumbnail',
            [
                'label' => esc_html__( 'Post Thumbnail', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_thumbnail',
            [
                'label' => esc_html__( 'Show Thumbnail', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => 'yes',
            ]
        );  
        
        $this->add_responsive_control(
            'post_image_alignment',
            [
                'label' => esc_html__( 'Alignment', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html__( 'Left', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__( 'Right', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                    'column' => [
                        'title' => esc_html__( 'Top', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__( 'Bottom', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => '',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .grid-item-inner' => 'display:inline-flex; flex-direction: {{VALUE}}; align-items: center; gap: 30px;',
                ],
            ]
        );       
        

        $this->add_responsive_control(
            'flex_item_width_image',
            [
                'label' => esc_html__( 'Width', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px', 'vw' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 50,
                        'max' => 1200,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-img' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}; flex:0 0 {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_thumbnail' => 'yes',
                    'post_image_alignment!' => ['row', 'row-reverse'],                    
                ]
            ]
        );


        $this->add_responsive_control(
            'flex_item_width',
            [
                'label' => esc_html__( 'Flex Width', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px', 'vw' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 50,
                        'max' => 1200,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-img' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_thumbnail' => 'yes',
                    'post_image_alignment' => ['row', 'row-reverse'],                    
                ]
            ]
        );

        $this->add_responsive_control(
            'post_image_height',
            [
                'label' => esc_html__( 'Height', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .grid-item img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
           Group_Control_Image_Size::get_type(),
           [
               'name' => 'thumbnail',
               'default' => 'medium',
           ]
        );


        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .grid-item img, {{WRAPPER}} .grid-item-inner.eel--transparent-post .eel--overlay-all' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'thumb_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => 'no',
                'condition' => [
                    'show_thumbnail' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eel_animation_style',[
                'label' => esc_html__( 'Animation', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left_right',
                'options' => [
                    'left_right' => esc_html__( 'Left to Right', 'easy-elements' ),
                    'top_bottom' => esc_html__( 'Top to Bottom', 'easy-elements' ),
                ],
                'condition' => [
                    'show_thumbnail' => 'yes',
                    'thumb_animation' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            '_section_button',
            [
                'label' => esc_html__( 'Button Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_read_more',
            [
                'label' => esc_html__( 'Show Read More', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__( 'Read More', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Read More', 'easy-elements' ),
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_icon',
            [
                'label'   => esc_html__( 'Read More Icon', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => '',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_icon_position',
            [
                'label'   => esc_html__( 'Icon Position', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'before' => esc_html__( 'Before Text', 'easy-elements' ),
                    'after'  => esc_html__( 'After Text', 'easy-elements' ),
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'read_more_button_style_tabs' );

        // Normal State
        $this->start_controls_tab(
            'button_style_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel--read-more a svg, {{WRAPPER}} .eel--read-more a svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .eel--read-more a',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eel_btn_background',
                'label' => __( 'Button Background', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel--read-more a',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .eel--read-more a',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_style_hover',
            [
                'label' => esc_html__( 'Hover', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel--read-more a:hover svg, {{WRAPPER}} .eel--read-more a:hover svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border_hover',
                'selector' => '{{WRAPPER}} .eel--read-more a:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'icon__size',
            [
                'label' => esc_html__( 'Icon Size', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel--read-more i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon_box_color',
            [
                'label' => esc_html__( 'Icon Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--read-more a svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--read-more a svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--read-more a i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--read-more a i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon_box_color_hover',
            [
                'label' => esc_html__( 'Icon Color (Hover)', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--read-more a:hover svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--read-more a:hover svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--read-more a:hover i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_box_padding',
            [
                'label' => esc_html__( 'Icon Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_box_background',
                'label' => esc_html__( 'Icon Background', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel--read-more-icon',
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_box_border',
                'selector' => '{{WRAPPER}} .eel--read-more-icon',
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_box_border_radius',
            [
                'label' => esc_html__( 'Icon Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow',
                'selector' => '{{WRAPPER}} .eel--read-more-icon',
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_icon_offset',
            [
                'label' => esc_html__( 'Icon Offset (Vertical)', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more-icon.after, {{WRAPPER}} .eel--read-more svg' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_icon_offset_ho',
            [
                'label' => esc_html__( 'Icon Offset (Horizontal)', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--read-more-icon.after, {{WRAPPER}} .eel--read-more svg' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_button_icon',
            [
                'label' => esc_html__( 'Only Icon Button', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'only_icon_show',
            [
                'label' => esc_html__( 'Show Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'only_icon',
            [
                'label'   => esc_html__( 'Icon', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => '',
                    'library' => 'fa-solid',
                ],
            ]
        );
       
        $this->start_controls_tabs( 'button_style_tabs' );

        $this->start_controls_tab(
            'icon_btn_style_normal',
            [
                'label' => __( 'Normal', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--button-icon svg, {{WRAPPER}} .eel--button-icon i' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eel_iocn_background',
                'label' => __( 'Button Background', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel--button-icon',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_btn_border',
                'label' => __( 'Button Border', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel--button-icon',
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => __( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--button-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab(); 

        $this->start_controls_tab(
            'icon_btn_style_hover',
            [
                'label' => __( 'Hover', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => __( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--button-icon:hover svg, {{WRAPPER}} .eel--button-icon:hover i' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eel_iocn_background_hover',
                'label' => __( 'Button Background', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel--button-icon:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'section_transparent_settings',
            [
                'label' => __( 'Transparent Layout', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'style_transparent',
            [
                'label' => esc_html__( 'Blog Transparent', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'transparent_overlay_heading',[
                'label' => esc_html__( 'Overlay Background', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'condition' => [
                    'style_transparent' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eel_transparent_background',
                'label' => __( 'Overlay Background', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel--transparent-post .eel--overlay-all',
                'condition' => [
                    'style_transparent' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'eel_overlay_height',
            [
                'label' => __( 'Overlay Height', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--transparent-post .eel--overlay-all' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'style_transparent' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();


        $this->start_controls_section(
            'section_pagination_settings',
            [
                'label' => __( 'Pagination', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => esc_html__( 'Show Pagination', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control( 'pagination_top_spacing', [
            'label' => esc_html__( 'Top Spacing', 'easy-elements' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'selectors' => [ '{{WRAPPER}} .eel-blog-pagination' => 'margin-top: {{SIZE}}{{UNIT}};' ],
            'condition' => [ 'show_pagination' => 'yes' ],
        ]);

        $this->add_responsive_control( 'pagination_gap', [
            'label' => esc_html__( 'Gap', 'easy-elements' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
            'selectors' => [ '{{WRAPPER}} .eel-blog-pagination ul' => 'gap: {{SIZE}}{{UNIT}};' ],
            'condition' => [ 'show_pagination' => 'yes' ],
        ]);

        $this->start_controls_tabs( 'pagination__tabs' );

        $this->start_controls_tab(
            'pagination__normal',
            [
                'label' => __( 'Normal', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-blog-pagination ul li a, {{WRAPPER}} .eel-blog-pagination ul li span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'pagination_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-blog-pagination ul li a, {{WRAPPER}} .eel-blog-pagination ul li span',
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'pagination_typography',
                'selector' => '{{WRAPPER}} .eel-blog-pagination ul li a, {{WRAPPER}} .eel-blog-pagination ul li span',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'pagination_border',
                'selector' => '{{WRAPPER}} .eel-blog-pagination ul li a',
            ]
        );

        $this->add_responsive_control(
            'pagination_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-blog-pagination ul li a, {{WRAPPER}} .eel-blog-pagination ul li span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_width',
            [
                'label' => esc_html__( 'Width', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 40,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-blog-pagination ul li a, {{WRAPPER}} .eel-blog-pagination ul li span' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_pagination' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control( 'pagination_padding', [
            'label' => esc_html__( 'Padding', 'easy-elements' ),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors' => [
                '{{WRAPPER}} .eel-blog-pagination ul li a, {{WRAPPER}} .eel-blog-pagination ul li span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow',
				'selector' => '{{WRAPPER}} .eel-blog-pagination ul li a, {{WRAPPER}} .eel-blog-pagination ul li span',
			]
		);

        $this->add_control(
            'pagination_alignment',
            [
                'label' => __( 'Alignment', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'easy-elements' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'easy-elements' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'easy-elements' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .eel-blog-pagination' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab(); 

        $this->start_controls_tab(
            'pagination__hover',
            [
                'label' => __( 'Hover', 'easy-elements' ),
            ]
        );

         $this->add_control(
            'pagination_color_current',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-blog-pagination ul li a:hover, {{WRAPPER}} .eel-blog-pagination ul li span.current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'pagination_background_current',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-blog-pagination ul li span.current, {{WRAPPER}} .eel-blog-pagination ul li a:hover',
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'pagination_border_hover',
                'selector' => '{{WRAPPER}} .eel-blog-pagination ul li span.current, {{WRAPPER}} .eel-blog-pagination ul li a:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'pagination_hover_box_shadow',
                'selector' => '{{WRAPPER}} .eel-blog-pagination ul li span.current, {{WRAPPER}} .eel-blog-pagination ul li a:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        $this->start_controls_section(
            'section_item_style',
            [
                'label' => __( 'Items', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'item_background',
                'label'    => __( 'Item Background', 'easy-elements' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel-post-grid-wrap .grid-item-inner',
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label'      => __( 'Padding', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-grid-wrap .grid-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label'      => __( 'Border Radius', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel-post-grid-wrap .grid-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'item_border',
                'selector' => '{{WRAPPER}} .eel-post-grid-wrap .grid-item-inner',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .eel-post-grid-wrap .grid-item-inner',
            ]
        );
        

        $this->end_controls_section();

        $this->start_controls_section(
            'section_conten_style',
            [
                'label' => __( 'Content Part', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'content_background',
                'label'    => __( 'Item Background', 'easy-elements' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ee--blog-content-wrap',
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => __( 'Padding', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ee--blog-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_border_radius',
            [
                'label' => __( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--blog-content-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => __( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--blog-content-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __( 'Title', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ee--blog-title',
            ]
        );

        // Tabs for Normal and Hover state
        $this->start_controls_tabs( 'title_color_tabs' );

        // Normal
        $this->start_controls_tab(
            'title_color_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-elements' ),
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--blog-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'selector' => '{{WRAPPER}} .ee--blog-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ee--blog-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label'      => __( 'Padding', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ee--blog-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'title_color_hover',
            [
                'label' => esc_html__( 'Hover', 'easy-elements' ),
            ]
        );
        $this->add_control(
            'title_hover_color',
            [
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--blog-title:hover a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'section_excerpt_style',
            [
                'label' => __( 'Excerpt', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label'     => esc_html__( 'Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .eel--blog-excerpt',
            ]
        );

        $this->add_responsive_control(
            'excerpt_margin',
            [
                'label'      => __( 'Margin', 'easy-elements' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eel--blog-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'section_meta_style',
            [
                'label' => __( 'Meta', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'meta_text_color',
            [
                'label'     => __( 'Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li, {{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li *, {{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li i, {{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_icon_color',
            [
                'label'     => __( 'Icon Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_icon_size',
            [
                'label' => __( 'Icon Size', 'easy-elements' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 8, 'max' => 30 ] ],
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_icon_spacing',
            [
                'label' => __( 'Icon Spacing', 'easy-elements' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li i, {{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li svg' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eel_meta_background',
                'label' => __( 'Button Background', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'meta_typography',
                'label'    => __( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li',
            ]
        );

        $this->add_responsive_control(
            'meta_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_spancing',
            [
                'label' => esc_html__( 'Meta Spacing', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li+li' => 'margin-left: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-post-grid-wrap .eel--blog-meta li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'date_badge_style',
            [
                'label' => __( 'Date Badge', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_date_on_top' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'date_text_color_badge',
            [
                'label'     => __( 'Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-date-top, {{WRAPPER}} .eel--blog-date-top h4' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eel_date_background_badge',
                'label' => __( 'Button Background', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel--blog-date-top',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'date_typography_badge_top',
                'label'    => __( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel--blog-date-top h4',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'date_typography_badge_btm',
                'label'    => __( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel--blog-date-top',
            ]
        );

        $this->add_responsive_control(
            'date_border_radius_badge',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-date-top' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'date_padding_radius_badge',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-date-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'show_date_on_top_position_horizontal',
            [
                'label' => esc_html__('Offset Horizontal', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => -400, 'max' => 400, 'step' => 1 ],
                    '%' => [ 'min' => -100, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-date-top' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'show_date_on_top_position_vartical',
            [
                'label' => esc_html__('Offset Vertical', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => -400, 'max' => 400, 'step' => 1 ],
                    '%' => [ 'min' => -100, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-date-top' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eel_enable_blur_switcher',
            [
                'label' => __( 'Enable Blur', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'easy-elements' ),
                'label_off' => __( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'eel_blur_badge',
            [
                'label' => __( 'Blur', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100, 'step' => 1 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'condition' => [
                    'eel_enable_blur_switcher' => 'yes',  
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-date-top' => 'backdrop-filter: blur({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->end_controls_section();

        // Category Badge Style
        $this->start_controls_section(
            'category_badge_style',
            [
                'label' => __( 'Category Badge', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_category_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'category_badge_color',
            [
                'label'     => __( 'Color', 'easy-elements' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-category-badge a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'category_badge_background',
                'label' => __( 'Background', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel--blog-category-badge',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'category_badge_typography',
                'label'    => __( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel--blog-category-badge a',
            ]
        );

        $this->add_responsive_control(
            'category_badge_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-category-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'category_badge_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-category-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'category_badge_position_horizontal',
            [
                'label' => esc_html__('Offset Horizontal', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => -400, 'max' => 400, 'step' => 1 ],
                    '%' => [ 'min' => -100, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-category-badge' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'category_badge_position_vertical',
            [
                'label' => esc_html__('Offset Vertical', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => -400, 'max' => 400, 'step' => 1 ],
                    '%' => [ 'min' => -100, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-category-badge' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'category_badge_blur',
            [
                'label' => __( 'Backdrop Blur', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100, 'step' => 1 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--blog-category-badge' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}); -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render_blog_meta( $settings ) {
        if ( empty( $settings['show_meta'] ) || ! is_array( $settings['show_meta'] ) ) {
            return;
        }

        $show_meta = $settings['show_meta'];

        usort( $show_meta, function( $a, $b ) use ( $settings ) {
            $order_a = ! empty( $settings['meta_order_' . $a] ) ? (int) $settings['meta_order_' . $a] : 99;
            $order_b = ! empty( $settings['meta_order_' . $b] ) ? (int) $settings['meta_order_' . $b] : 99;
            return $order_a - $order_b;
        });

        $eel_separator = ! empty( $settings['eel_separator'] ) ? 'eel--separator--' . esc_attr( $settings['eel_separator'] ) : '';
        ?>
        <ul class="eel--blog-meta <?php echo esc_attr($eel_separator); ?>">
            <?php foreach ( $show_meta as $meta ) :
                switch ( $meta ) :
                    case 'date': ?>
                        <li class="eel--blog-date">
                            <?php
                            if ( ! empty($settings['show_date_icon']) && $settings['show_date_icon'] === 'yes' ) {
                                if ( empty( $settings['date_icon']['value'] ) ) {
                                    echo '<i class="unicon-calendar"></i>';
                                } else {
                                    \Elementor\Icons_Manager::render_icon( $settings['date_icon'], [ 'aria-hidden' => 'true' ] );
                                }
                            }
                            echo esc_html( get_the_date() );
                            ?>
                        </li>
                    <?php break;

                    case 'author':
                        $author_icon_type = $settings['author_icon_type'] ?? 'icon';
                    ?>
                        <li class="eel--blog-author <?php echo $author_icon_type === 'avatar' ? 'eel--has-avatar' : ''; ?>">
                            <?php
                            $author_id   = get_the_author_meta( 'ID' );
                            $author_name = get_the_author();
                            $author_url  = get_author_posts_url( $author_id );

                            if ( $author_icon_type === 'icon' ) {
                                if ( empty( $settings['author_icon']['value'] ) ) {
                                    echo '<i class="unicon-user"></i>';
                                } else {
                                    \Elementor\Icons_Manager::render_icon( $settings['author_icon'], [ 'aria-hidden' => 'true' ] );
                                }
                            } elseif ( $author_icon_type === 'avatar' ) {
                                $avatar_size = ! empty( $settings['author_avatar_size']['size'] ) ? (int) $settings['author_avatar_size']['size'] : 22;
                                echo '<img class="eel--author-avatar" src="' . esc_url( get_avatar_url( $author_id, [ 'size' => $avatar_size * 2 ] ) ) . '" alt="' . esc_attr( $author_name ) . '">';
                            }

                            if ( ! empty($settings['show_by_label']) && $settings['show_by_label'] === 'yes' ) {
                                echo '<em class="eel--meta-by">' . esc_html__( 'By', 'easy-elements' ) . '</em> ';
                            }

                            if ( ! empty($settings['author_link_enable']) && $settings['author_link_enable'] === 'yes' ) {
                                echo '<a class="eel--meta-author" href="' . esc_url( $author_url ) . '">' . esc_html( $author_name ) . '</a>';
                            } else {
                                echo '<span class="eel--meta-author">' . esc_html( $author_name ) . '</span>';
                            }
                            ?>
                        </li>
                    <?php break;

                    case 'category': ?>
                        <li class="eel--blog-cat">
                            <?php
                            if ( ! empty($settings['show_category_icon']) && $settings['show_category_icon'] === 'yes' ) {
                                if ( empty( $settings['category_icon']['value'] ) ) {
                                    echo '<i class="unicon-folder"></i>';
                                } else {
                                    \Elementor\Icons_Manager::render_icon( $settings['category_icon'], [ 'aria-hidden' => 'true' ] );
                                }
                            }
                            the_category( ', ' );
                            ?>
                        </li>
                    <?php break;

                    case 'comments': ?>
                        <li class="eel--blog-comments">
                            <?php
                            if ( ! empty($settings['show_comments_icon']) && $settings['show_comments_icon'] === 'yes' ) {
                                if ( empty( $settings['comments_icon']['value'] ) ) {
                                    echo '<i class="unicon-forum"></i>';
                                } else {
                                    \Elementor\Icons_Manager::render_icon( $settings['comments_icon'], [ 'aria-hidden' => 'true' ] );
                                }
                            }
                            $comments_number = get_comments_number();
                            echo '<a href="' . esc_url( get_comments_link() ) . '">' . esc_html( $comments_number ) . ' ' . esc_html( _n( 'Comment', 'Comments', $comments_number, 'easy-elements' ) ) . '</a>';
                            ?>
                        </li>
                    <?php break;

                    case 'read_time': ?>
                        <li class="eel--blog-read-time">
                            <?php
                            if ( ! empty($settings['show_read_time_icon']) && $settings['show_read_time_icon'] === 'yes' ) {
                                if ( empty( $settings['read_time_icon']['value'] ) ) {
                                    echo '<i class="unicon-time"></i>';
                                } else {
                                    \Elementor\Icons_Manager::render_icon( $settings['read_time_icon'], [ 'aria-hidden' => 'true' ] );
                                }
                            }
                            $word_count = str_word_count( wp_strip_all_tags( get_the_content() ) );
                            $reading_time = ceil( $word_count / 80 );
                            echo esc_html( $reading_time ) . ' ' . esc_html__( 'min read', 'easy-elements' );
                            ?>
                        </li>
                    <?php break;
                endswitch;
            endforeach; ?>
        </ul>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_type = $settings['post_type'] ?? 'post';
        $title_tag = $settings['title_tag'];
        $posts_per_page = isset($settings['posts_per_page']) ? (int) $settings['posts_per_page'] : 6;

        $orderby = $settings['orderby'];
        $order = $settings['order'];
        $offset = isset($settings['offset']) ? (int) $settings['offset'] : 0;

        $exclude_categories = $settings['exclude_categories'] ?? []; 
        $thumbnail_size = $settings['thumbnail_size'];
        $thumb_anim = ($settings['thumb_animation'] ?? 'yes') === 'yes' ? 'eel--animate' : '';
        $anim_style = ($settings['eel_animation_style'] ?? 'left_right') === 'left_right' ? 'left_right' : 'top_bottom';
        $title_trim = $settings['title_trim'] ?? '';
        $date_options = ($settings['show_date_on_top'] === 'yes') ? 'd-block' : '';
        $excerpt_trim = $settings['excerpt_trim'];

        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        
        $widget_id = $this->get_id();
        $paged_key = 'paged_' . $widget_id;

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $current_paged = isset( $_GET[$paged_key] ) ? absint( $_GET[$paged_key] ) : ( isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1 );
        
        
        $paged = $is_editor ? 1 : max( 1, $current_paged );

        $args = [
            'post_type'      => $post_type,
            'posts_per_page' => ($posts_per_page <= 0) ? 6 : $posts_per_page,
            'paged'          => $paged,
            'orderby'        => $orderby,
            'order'          => $order,
            'post_status'    => 'publish',
        ];

        if ( $offset > 0 ) {
            $args['offset'] = ( $paged - 1 ) * $posts_per_page + $offset;
        }

        if ( apply_filters( 'easyel/pro_enabled', false ) ) {
            $args = apply_filters( 'easyel/apply_user_source_query', $args, $settings );
        }

        if ( $settings['source_type'] === 'category' && ! empty( $settings['categories'] ) ) {
            $args['category__in'] = array_map( 'intval', (array) $settings['categories'] );
        } elseif ( $settings['source_type'] === 'post' && ! empty( $settings['post__in'] ) ) {
            $args['post__in'] = $settings['post__in'];
            $args['orderby'] = 'post__in';
        }

        if ( ! empty( $exclude_categories ) ) {
            $args['category__not_in'] = array_map( 'intval', (array) $exclude_categories );
        }

        
        if ( ! $is_editor && ( is_category() || is_tag() || is_tax() ) ) {
            global $wp_query;
            $query = $wp_query;
        } else {
            $query = new \WP_Query( $args );
        }

        if ( ! empty( $settings['exclude_posts'] ) && $query->have_posts() ) {
            $exclude_ids = (array) $settings['exclude_posts'];
            $query->posts = array_filter( $query->posts, function( $post ) use ( $exclude_ids ) {
                return ! in_array( $post->ID, $exclude_ids, true );
            });
            $query->post_count = count( $query->posts );
        }

        if ( $offset > 0 && $posts_per_page > 0 ) {
            $query->found_posts   = max( 0, $query->found_posts - $offset );
            $query->max_num_pages = ceil( $query->found_posts / $posts_per_page );
        }

        if ( ! $query->have_posts() ) {
            echo '<p>' . esc_html__( 'No posts found.', 'easy-elements' ) . '</p>';
            return;
        }

        if ( $query->have_posts() ) :
            echo '<div class="eel-post-grid-wrap">';
            while ( $query->have_posts() ) : $query->the_post();
                $trimmed_title = wp_trim_words( get_the_title(), $title_trim ?: 100, '...' );                
                $trimmed_excerpt = wp_trim_words( get_the_excerpt(), $excerpt_trim ?: 20, '...' );           
                $style_transparent = ($settings['style_transparent'] === 'yes') ? 'eel--transparent-post' : '' ;
            ?>
                <div class="grid-item <?php echo esc_attr( $anim_style ); ?>">
                    <div class="grid-item-inner <?php echo esc_attr( $style_transparent ); ?>">
                        <?php if ( 'yes' === $settings['show_thumbnail'] ) { ?>
                            <div class="eel--blog-img <?php echo esc_attr( $thumb_anim ); ?> <?php echo esc_attr( $anim_style ); ?>">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if ( has_post_thumbnail() ) { the_post_thumbnail( $thumbnail_size ); } ?>
                                    <?php if ( 'yes' === $settings['style_transparent'] ) { ?>
                                        <div class="eel--overlay-all"></div>
                                    <?php } ?>
                                </a>
                            </div>
                        <?php } ?>
                        
                        <div class="ee--blog-content-wrap">
                            <div class="ee--blog-content eel-content--<?php echo esc_attr( $style_transparent ); ?>">
                                <?php if ( 'up_title' === $settings['meta_position'] ) : $this->render_blog_meta( $settings ); endif; ?>
                                
                                <<?php echo esc_attr( $title_tag ); ?> class="ee--blog-title">
                                    <a href="<?php the_permalink(); ?>"><?php echo esc_html( $trimmed_title ); ?></a>
                                </<?php echo esc_attr( $title_tag ); ?>>

                                <?php if ( 'below_title' === $settings['meta_position'] ) : $this->render_blog_meta( $settings ); endif; ?>
                                
                                <?php if ( $settings['show_excerpt'] === 'yes' ) : ?>
                                    <div class="eel--blog-excerpt"><?php echo esc_html( $trimmed_excerpt ); ?></div>
                                <?php endif; ?>

                                <?php if ( 'below_content' === $settings['meta_position'] ) : $this->render_blog_meta( $settings ); endif; ?>
                            </div>
                            
                            <?php if ( ! empty( $settings['read_more_text'] ) ) : ?>
                                <div class="eel--read-more eel-read-more--<?php echo esc_attr( $style_transparent ); ?>">
                                    <a href="<?php the_permalink(); ?>" class="eel--read-more-link">
                                        <?php
                                        if ( isset( $settings['read_more_icon'] ) && ! empty( $settings['read_more_icon']['value'] ) && $settings['read_more_icon_position'] === 'before' ) {
                                            \Elementor\Icons_Manager::render_icon( $settings['read_more_icon'], [ 'aria-hidden' => 'true', 'class' => 'eel--read-more-icon before' ] );
                                        } elseif ( $settings['read_more_icon_position'] === 'before' ) {
                                            echo '<i class="unicon-chevron-right eel--read-more-icon before"></i>';
                                        }
                                        echo esc_html( $settings['read_more_text'] );
                                        if ( isset( $settings['read_more_icon'] ) && ! empty( $settings['read_more_icon']['value'] ) && $settings['read_more_icon_position'] === 'after' ) {
                                            \Elementor\Icons_Manager::render_icon( $settings['read_more_icon'], [ 'aria-hidden' => 'true', 'class' => 'eel--read-more-icon after' ] );
                                        } elseif ( $settings['read_more_icon_position'] === 'after' ) {
                                            echo '<i class="unicon-chevron-right eel--read-more-icon after"></i>';
                                        }
                                        ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if ( 'yes' === $settings['only_icon_show'] ) { ?>                                    
                                <a href="<?php the_permalink(); ?>" class="eel--button-icon">
                                    <?php
                                    if ( empty( $settings['only_icon']['value'] ) ) {
                                        echo '<i class="unicon-chevron-right"></i>';
                                    } else {
                                        \Elementor\Icons_Manager::render_icon( $settings['only_icon'], [ 'aria-hidden' => 'true' ] );
                                    } ?>
                                </a>                                    
                            <?php } ?>
                        </div>
                        
                        <?php if(!empty($date_options) && !empty($settings['show_date_on_top'])): ?>
                            <div class="eel--blog-date-top">
                                <h4><?php echo esc_html(get_the_time('d')); ?></h4>
                                <span><?php echo esc_html(get_the_time('M')); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if( 'yes' === ( $settings['show_category_badge'] ?? '' ) ) :
                            $cats = get_the_category();
                            if ( ! empty( $cats ) ) : ?>
                                <div class="eel--blog-category-badge">
                                    <?php
                                    $cat_links = [];
                                    foreach ( $cats as $cat ) {
                                        $cat_links[] = '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a>';
                                    }
                                    
                                    echo wp_kses(
                                        implode( ',&nbsp;', $cat_links ),
                                        easyel_allowed_html()
                                    );
                                    ?>
                                </div>
                            <?php endif;
                        endif; ?>
                    </div>
                </div>
            <?php
            endwhile;
            echo '</div>'; 
                
            if ( 'yes' === $settings['show_pagination'] && $query->max_num_pages > 1 ) {
                
                $pagination = paginate_links([
                    'base'      => add_query_arg( $paged_key, '%#%' ),
                    'format'    => '?paged=%#%',
                    'current'   => $paged,
                    'total'     => $query->max_num_pages,
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                    'type'      => 'list', 
                ]);

                if ( $pagination ) {
                    echo '<nav class="eel-blog-pagination">' . wp_kses_post( $pagination ) . '</nav>';
                }
            }
            wp_reset_postdata();
        endif;
    }
} 