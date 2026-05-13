<?php
namespace Easyel\EasyElements\Extensions\Jarallax;
use Elementor\Controls_Manager;
use Elementor\Element_Base;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


class JaralaxControl {

    private static $instance = null;

    public static function get_instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function init() {
        add_action('elementor/element/image/section_style_image/after_section_end', [__CLASS__, 'Easyel_inject_section_class'], 199, 2);
        add_action('elementor/element/eel-advance-image/image_style_section/after_section_end', [__CLASS__, 'Easyel_inject_section_class'], 199, 2);
    }

    public static function Easyel_inject_section_class( $element, $args ) {

        $element->start_controls_section(
            'rt_jarallax_section',
            [
                'label' => esc_html__( 'Easy Parallax', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $element->add_control(
            'enable_jarallax',
            [
                'label' => esc_html__( 'Enable Parallax', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'return_value' => 'yes',
                'prefix_class' => 'eele-has-jarallax-img-',
                'render_type'  => 'template',
            ]
        );

        $element->add_responsive_control(
            'jarallax_image_width',
            [
                'label' => esc_html__( 'Width', 'easy-elements' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1000, 'step' => 1 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .jarallax' => 'width: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .elementor-widget-container img, {{WRAPPER}} .jarallax img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_jarallax' => 'yes',
                ]
            ]
        );

        $element->add_responsive_control(
            'jarallax_image_height',
            [
                'label' => esc_html__( 'Height', 'easy-elements' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1000, 'step' => 1 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .jarallax, {{WRAPPER}} .jarallax-container' => 'height: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .elementor-widget-container img, {{WRAPPER}} .jarallax img' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_jarallax' => 'yes',
                ]
            ]
        );

        $element->add_responsive_control(
            'jarallax_image_border_radius',
            [
                'label' => esc_html__( 'Image Border Radius', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .jarallax-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .jarallax-container img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'enable_jarallax' => 'yes',
                ]
            ]
        );

        $element->end_controls_section();
    }
}