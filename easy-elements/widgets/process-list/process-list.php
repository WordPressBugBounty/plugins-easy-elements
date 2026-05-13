<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

class Easyel_Process_lists_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'eel-process-list';
    }

    public function get_title() {
        return esc_html__( 'Process List', 'easy-elements' );
    }

    public function get_icon() {
        return 'easyicon easyelIcon-process-list';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'process', 'sevice', 'icon', 'process-box', 'text' ];
    }

    public function get_style_depends() {
        return [
            'eel-process-list',
        ];
    }


    protected function register_controls() {
        $this->start_controls_section(
            '_section_logo',
            [
                'label' => esc_html__( 'Process Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
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
            ]
        );

        $this->add_control(
            'process_title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Initial Consultation', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            '_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'We start by understanding your vision, needs, lifestyle, and budget to establish the foundation of your project.', 'easy-elements' ),
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
            'process_number',
            [
                'label' => esc_html__( 'Process Number', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '01',
                'placeholder' => 'Enter a number',
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

        $this->add_responsive_control(
            'flex_align_vertical',
            [
                'label' => esc_html__( 'Vertical Align', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'easy-elements' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'easy-elements' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Bottom', 'easy-elements' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .eel--process-list' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label' => esc_html__( 'Gap', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 64,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--process-list' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();        

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Icon', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--process-list .eel-process-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel--process-list .eel-process-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => esc_html__( 'Background', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel--process-list .eel-process-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),  
            [
                'name' => 'icon_background',
                'label' => esc_html__( 'Background', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel--process-list .eel-process-icon',
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 76,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel--process-list .eel-process-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel--process-list .eel-process-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .eel--process-list .eel-process-icon',
            ]
        );

        $this->add_control(
            'icon__border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--process-list .eel-process-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--process-list .eel-process-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel--process-list .eel-process-icon',
            ]
        );

        $this->add_responsive_control(
            'icon_box_size',
            [
                'label'      => esc_html__( 'Box Size', 'easy-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 30,
                        'max' => 150,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eel--process-list .eel-process-icon' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'box_title_typography',
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
                    '{{WRAPPER}} .eel--process-list .icon-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );  

        $this->end_controls_section();

        $this->start_controls_section(
            'title_sub_style',[
                'label' => esc_html__( 'Highlight Title', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_highlight_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw'  => __( 'Highlight options work only when part of the title is wrapped in a &lt;span&gt; tag.', 'easy-elements' ),
                'content_classes' => 'elementor-control-field-description',
            ]
        );

        $this->add_control(
            'title_sub_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-box-title span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_sub_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .icon-box-title span',
            ]
        );     
        
        $this->add_responsive_control(
            'title_sub_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .eel--process-list .icon-box-title span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );  

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

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

        $this->end_controls_section();

        $this->start_controls_section(
            'section_process_number',
            [
                'label' => esc_html__( 'Number', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
        'process_number_color',
        [
            'label' => esc_html__( 'Color', 'easy-elements' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .eel-pro-number' => 'color: {{VALUE}};',
            ],
        ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-pro-number',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $link     = $settings['link']['url'] ?? '';
        $target   = ! empty( $settings['link']['is_external'] ) ? ' target="_blank"' : '';
        $nofollow = ! empty( $settings['link']['nofollow'] ) ? ' rel="nofollow"' : '';
        ?>
        <div class="eel-process-wrap-list"> 
            <?php if ( $link ) : ?>
                <a href="<?php echo esc_url( $link ); ?>" <?php echo esc_attr( $target ); ?> <?php echo esc_attr( $nofollow ); ?>>
            <?php endif; ?>              
            <div class="eel--process-list">                   
                <?php if ( ! empty( $settings['process_number'] ) ) : ?>
                    <span class="eel-pro-number"><?php echo esc_html( $settings['process_number'] ); ?></span>
                <?php endif; ?>

                <?php
                if ( isset( $settings['icon']['value'] ) && ! empty( $settings['icon']['value'] ) ) { ?>
                    <span class="eel-process-icon"><?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
                <?php } ?>               
            
                <div class="eel-process-wrap">                
                    <?php if ( ! empty( $settings['process_title'] ) ) :
                        $title_tag = isset( $settings['title_tag'] ) ? $settings['title_tag'] : 'h3'; ?>
                        <<?php echo esc_attr( $title_tag ); ?> class="icon-box-title">
                        <?php 
                                $allowed_tags = wp_kses_allowed_html('post'); 
                                echo wp_kses( $settings['process_title'], $allowed_tags );
                            ?>
                        </<?php echo esc_attr( $title_tag ); ?>>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['_description'] ) ) : ?>
                        <div class="icon-box-description"><?php echo wp_kses_post( $settings['_description'] ); ?></div>
                    <?php endif; ?> 
                </div>                      
            </div>  
            <?php if ( $link ) : ?>
                </a>
            <?php endif; ?>           
        </div>
    <?php
    }
}