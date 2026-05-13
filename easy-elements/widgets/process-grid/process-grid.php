<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
defined( 'ABSPATH' ) || die();

class Easyel_Process_Grid_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'eel-process';
    }

    public function get_title() {
        return esc_html__( 'Process Grid', 'easy-elements' );
    }

    public function get_icon() {
        return 'easyicon easyelIcon-process-grid';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'process', 'sevice', 'icon', 'process-box', 'text' ];
    }

    public function get_style_depends() {
        return [
            'eel-process',
        ];
    }


    protected function register_controls() {
        $this->start_controls_section(
            '_section_logo',
            [
                'label' => esc_html__( 'Process Grid Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
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

        $repeater->add_control(
            '_title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Manufacturing Industrial', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            '_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Optimizing production and supply chain operations and generational transitions', 'easy-elements' ),
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'easy-elements'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );
        $repeater->add_control(
            'process_number_or_icon',
            [
                'label' => esc_html__('Process Number or Icon', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'p_number' => esc_html__('Process Number', 'easy-elements'),
                    'p_icon' => esc_html__('Process Icon', 'easy-elements'),
                ],
                'default' => 'p_number',
            ]
        );
        $repeater->add_control(
            'process_number',
            [
                'label' => esc_html__( 'Process Number', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '1',
                'placeholder' => 'Enter a number',
                'condition' => [
                    'process_number_or_icon' => 'p_number',
                ],
            ]
        );
        $repeater->add_control(
            'process_icon',
            [
                'label' => esc_html__( 'Process Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'value' => 'fas fa-long-arrow-alt-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'process_number_or_icon' => 'p_icon',
                ],
            ]
        );


        $this->add_control(
            'easy_icon_box',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ _title }}}',
                'default' => [
                    [
                        'icon' => [
                            'value' => 'fas fa-heart',
                            'library' => 'fa-solid',
                        ],
                        '_title' => esc_html__( 'Manufacturing Industrial', 'easy-elements' ),
                        '_description' => esc_html__( 'Optimizing production and supply chain operations and generational transitions', 'easy-elements' ),
                        'link' => ['url' => ''],
                    ],
                    [
                        'icon' => [
                            'value' => 'fas fa-heart',
                            'library' => 'fa-solid',
                        ],
                        '_title' => esc_html__( 'Professional Services', 'easy-elements' ),
                        '_description' => esc_html__( 'Growth strategies for knowledge-based businesses with strategic guidance throughout', 'easy-elements' ),
                        'link' => ['url' => ''],
                    ],
                    [
                        'icon' => [
                            'value' => 'fas fa-heart',
                            'library' => 'fa-solid',
                        ],
                        '_title' => esc_html__( 'Technology & SaaS', 'easy-elements' ),
                        '_description' => esc_html__( 'Scaling strategies for rapid growth and market leadership expertise for companies facing challenges', 'easy-elements' ),
                        'link' => ['url' => ''],
                    ],
                ],
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
                    '{{WRAPPER}} .ee--icon-box' => 'text-align: {{VALUE}};',
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
                    '{{WRAPPER}} .eel-icon-box-wrap .eel-icon-item' => 'width: calc(100% / {{VALUE}});',
                ],
            ]
        );


        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__( 'Space', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-icon-box-wrap .eel-icon-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();        

        $this->start_controls_section(
            'section_style_icon_box',
            [
                'label' => esc_html__( 'Box Style', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_background_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-icon-item .ee--icon-box' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding_inner',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-icon-box-wrap .eel-icon-item .ee--icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .ee--icon-box',
            ]
        );

        $this->add_control(
            'item__border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-icon-box-wrap .eel-icon-item .ee--icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .eel-icon-box-wrap .ee--icon-box',
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
                    '{{WRAPPER}} .ee--icon-box .eel-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box .eel-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box .eel-icon svg path' => 'fill: {{VALUE}};',
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
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .icon-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .eel-process-number' => 'background-image:linear-gradient(180deg, {{VALUE}} 0%, rgba(255, 255, 255, 0.24) 100%);',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'number_typography',
                'label'    => esc_html__('Number Typography', 'easy-elements'),
                'selector' => '{{WRAPPER}} .eel-process-number',
            ]
        );
        $this->add_responsive_control(
            'process_number_opacity',
            [
                'label' => esc_html__( 'Opacity', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0, 'max' => 1, 'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-process-number' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'process_number_offset',
            [
                'label' => esc_html__( 'Number Vertical Position', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => -500, 'max' => 500 ],
                    '%' => [ 'min' => -100, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-process-number' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'process_number_offset_horizontal',
            [
                'label' => esc_html__( 'Number Horizontal Position', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => -500, 'max' => 500 ],
                    '%' => [ 'min' => -100, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-process-number' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['easy_icon_box'] ) ) {
            return;
        }
        ?>
        <div class="eel-icon-box-wrap">
            <div class="grid-wrap">
                <?php foreach ( $settings['easy_icon_box'] as $item ) :
                    $link     = $item['link']['url'] ?? '';
                    $target   = ! empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                    $nofollow = ! empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                    $icon_direction     = $settings['icon_direction'] ?? '';
                    ?>

                    <div class="eel-icon-item">
                        <div class="ee--icon-box <?php echo esc_attr($icon_direction); ?>">
                            <?php if ( $link ) : ?>
                                <a href="<?php echo esc_url( $link ); ?>" <?php echo esc_attr( $target ); ?> <?php echo esc_attr( $nofollow ); ?>>
                            <?php endif; ?>

                            <?php
                            if ( isset( $item['icon']['value'] ) && $item['icon']['value'] ) { ?>
                                <span class="eel-icon"><?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
                            <?php } ?>

                            <?php if ( $icon_direction === 'left' || $icon_direction === 'right' ) : ?>
                                <div class="eel-title-content-wrap">
                            <?php endif; ?>
                            <?php 
                                if ( $item['process_number_or_icon'] === 'p_number' && ! empty( $item['process_number'] ) ) :
                                    echo '<span class="eel-process-number">' . esc_html( $item['process_number'] ) . '</span>';
                                elseif ( $item['process_number_or_icon'] === 'p_icon' && ! empty( $item['process_icon'] ) ) :
                                    echo '<span class="eel-process-number">';
                                        \Elementor\Icons_Manager::render_icon( $item['process_icon'], [ 'aria-hidden' => 'true' ] );
                                    echo '</span>';
                                endif;
                            ?>
                            <?php if ( ! empty( $item['_title'] ) ) :
                                $title_tag = isset( $settings['title_tag'] ) ? $settings['title_tag'] : 'h3'; ?>
                                <<?php echo esc_attr( $title_tag ); ?> class="icon-box-title">
                                    <?php echo wp_kses_post( $item['_title'] ); ?>
                                </<?php echo esc_attr( $title_tag ); ?>>
                            <?php endif; ?>

                            <?php if ( ! empty( $item['_description'] ) ) : ?>
                                <div class="icon-box-description"><?php echo wp_kses_post( $item['_description'] ); ?></div>
                            <?php endif; ?>

                            <?php if ( $icon_direction === 'left' || $icon_direction === 'right' ) : ?>
                                </div>
                            <?php endif; ?>

                            <?php if ( $link ) : ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}