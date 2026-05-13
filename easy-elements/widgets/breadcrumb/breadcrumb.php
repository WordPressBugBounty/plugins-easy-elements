<?php
namespace Easyel\EasyElements\Widgets;
/**
 * Easy Elements Breadcrumb Widget
 *
 * @package EasyElements
 */
use Elementor\Utils;
use Elementor\Controls_Manager;
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
include 'helper.php';


class Easyel_Breadcrumb_Widget extends \Elementor\Widget_Base {


    /**
     * Get widget name.
     *
     * Retrieve widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */

    public function get_name() {
        return 'eel-breadcrumb';
    }   


    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Breadcrumb', 'easy-elements' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'easyicon easyelIcon-breadcumb';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'breadcrumb', 'text', 'link', 'click' ];
    }

    public function get_style_depends() {
        return [
            'eel-breadcrumb',
        ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'breadcrumb_section',
            [
                'label' => esc_html__( 'Content Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_home_icon',
            [
                'label' => esc_html__( 'Show Home Icon', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'home_icon_picker',
            [
                'label' => esc_html__( 'Choose Icon', 'easy-elements' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-home',
                    'library' => 'fa-solid',
                ],
                'description' => esc_html__('Pick an icon for the home.', 'easy-elements'),
                'condition' => [
                    'show_home_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'home_title',
            [
                'label' => esc_html__( 'Home Page Title', 'easy-elements' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Home', 'easy-elements' ),
                'placeholder' => esc_html__( 'Home', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'show_category_path',
            [
                'label' => esc_html__( 'Show Category Path', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_separator_icon',
            [
                'label' => esc_html__( 'Show Separator Icon', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'separator_icon',
            [
                'label' => esc_html__( 'Separator Icon', 'easy-elements' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_separator_icon' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section for Colors
        $this->start_controls_section(
            'breadcrumb_style_section',
            [
                'label' => esc_html__( 'Breadcrumb', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'breadcrumb_text_color',
            [
                'label' => esc_html__( 'Text Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb, {{WRAPPER}} .eel-breadcrumb a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'brea_text_active_color',
            [
                'label' => esc_html__( 'Active Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'breadcrumb_typography',
                'selector' => '{{WRAPPER}} .eel-breadcrumb a, {{WRAPPER}} .eel-breadcrumb, {{WRAPPER}} .eel-breadcrumb span',        
            ]
        );

        $this->add_responsive_control(
            'text_padding',
            [
                'label' => esc_html__( 'Padding Normal', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-path a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        ); 
        
        $this->add_responsive_control(
            'text_padding_active',
            [
                'label' => esc_html__( 'Padding Active', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_bg_color',
            [
                'label' => esc_html__( 'Background Color Normal', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-path a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_bg_color_active',
            [
                'label' => esc_html__( 'Background Color Active', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-text' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_border_radius',
            [
                'label' => esc_html__( 'Border Radius Normal', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-path a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_border_radius_active',
            [
                'label' => esc_html__( 'Border Radius Active', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

         // Style Section for Separator
        $this->start_controls_section(
            'breadcrumb_home_icon_section',
            [
                'label' => esc_html__( 'Home Icon', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_home_icon' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'breadcrumb_icon_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-home-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-home-icon, {{WRAPPER}} .eel-breadcrumb .breadcrumb-home-icon path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'home_icon_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-home-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_size_',
            [
                'label' => esc_html__( 'Size', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-home-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} svg.breadcrumb-home-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'home_icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-home-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'home_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-home-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );        

        $this->add_control(
            'icon_size_postition',
            [
                'label' => esc_html__( 'Vartical Position', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-home-icon' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_size_postition_left',
            [
                'label' => esc_html__( 'Horizontal Position', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-home-icon' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section for Separator
        $this->start_controls_section(
            'breadcrumb_separator_section',
            [
                'label' => esc_html__( 'Separator', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'breadcrumb_separator_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-separator' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-separator, {{WRAPPER}} .eel-breadcrumb .breadcrumb-separator path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'separator_icon_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-separator' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-separator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-separator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'separator_icon_size',
            [
                'label' => esc_html__( 'Size', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-separator' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-separator' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
			'separator_icon_gap',
			[
				'label'      => __( 'Gap', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eel-breadcrumb .breadcrumb-separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'icon_sap_size_postition',
            [
                'label' => esc_html__( 'Vartical Position', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-separator' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
                ],
            ]
        );

        $this->add_control(
            'icon_size_sap_postition_left',
            [
                'label' => esc_html__( 'Horizontal Position', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-separator' => 'left: {{SIZE}}{{UNIT}}; position: relative;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function easy_enqueue_styles() {
        $handle = 'eel-breadcrumb-style';
        $custom_css = '';

        $settings = $this->get_settings_for_display();
        $text_color = !empty($settings['breadcrumb_text_color']) ? $settings['breadcrumb_text_color'] : '';
        $icon_color = !empty($settings['breadcrumb_icon_color']) ? $settings['breadcrumb_icon_color'] : '';
        $separator_color = !empty($settings['breadcrumb_separator_color']) ? $settings['breadcrumb_separator_color'] : '';

        if ($text_color) {
            $custom_css .= '.eel-breadcrumb { color: ' . esc_attr($text_color) . '; }';
        }

        if ($icon_color) {
            $custom_css .= '.eel-breadcrumb .breadcrumb-icon { color: ' . esc_attr($icon_color) . '; }';
        }

        if ($separator_color) {
            $custom_css .= '.eel-breadcrumb .breadcrumb-separator { color: ' . esc_attr($separator_color) . '; }';
        }

        wp_register_style( $handle, false, [], EASYELEMENTS_VER ); 
        wp_enqueue_style( $handle );

        if ( $custom_css ) {
            wp_add_inline_style( $handle, $custom_css );
        }
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $show_category_path = isset($settings['show_category_path']) && $settings['show_category_path'] === 'yes';

        // =========================
        // Home Icon (Condition + SVG Fix)
        // =========================
        $home_icon_html = '';
        if ( !empty($settings['show_home_icon']) && $settings['show_home_icon'] === 'yes' && !empty($settings['home_icon_picker']) ) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['home_icon_picker'], [
                'aria-hidden' => 'true',
                'class'       => 'breadcrumb-home-icon',
            ]);
            $home_icon_html = ob_get_clean();

       
            if (strpos($home_icon_html, 'breadcrumb-home-icon') === false) {
                $home_icon_html = preg_replace(
                    '/<svg\b(?![^>]*class=)/',
                    '<svg class="breadcrumb-home-icon" ',
                    $home_icon_html,
                    1
                );
                $home_icon_html = preg_replace(
                    '/<img\b(?![^>]*class=)/',
                    '<img class="breadcrumb-home-icon" ',
                    $home_icon_html,
                    1
                );
            }
        }
        $home_icon_picker = $home_icon_html;


       
        $separator_html = '';
        if ( !empty($settings['show_separator_icon']) && $settings['show_separator_icon'] === 'yes' && !empty($settings['separator_icon']['value']) ) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['separator_icon'], [
                'aria-hidden' => 'true',
                'class'       => 'breadcrumb-separator',
            ]);
            $separator_html = ob_get_clean();
            if (strpos($separator_html, 'breadcrumb-separator') === false) {
                $separator_html = preg_replace(
                    '/<svg\b(?![^>]*class=)/',
                    '<svg class="breadcrumb-separator" ',
                    $separator_html,
                    1
                );
                $separator_html = preg_replace(
                    '/<img\b(?![^>]*class=)/',
                    '<img class="breadcrumb-separator" ',
                    $separator_html,
                    1
                );
            }
        }


        $custom_separator = !empty($separator_html) ? $separator_html : '';

        $this->easy_enqueue_styles();

        if (function_exists('easyel_get_easyel_breadcrumb')) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo easyel_get_easyel_breadcrumb(
                '', '', $custom_separator, $settings['home_title'] ?? '', '', '', $show_category_path, '', $home_icon_picker
            );
        } else {
            echo '<!-- Breadcrumb function not found -->';
        }
    }
}