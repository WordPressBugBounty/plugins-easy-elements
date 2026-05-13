<?php
namespace Easyel\EasyElements\Widgets;
use Elementor\Utils;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Easyel_Domain_Search_Widget extends \Elementor\Widget_Base {


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
        return 'eel-domain-search';
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
        return esc_html__( 'Domain Search', 'easy-elements' );
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
        return 'easyicon easyelIcon-domain-search';
    }


    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'search', 'domain', 'input', 'click', 'text' ];
    }

    public function get_style_depends() {
        return [
            'eel-domain-search',
        ];
    }

    protected function register_controls() {

        // ===== Content Tab =====
        $this->start_controls_section(
            'domain_section',
            [
                'label' => esc_html__( 'Content Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'action_url',
            [
                'label'       => esc_html__( 'Action URL', 'easy-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://example.com/search', 'easy-elements' ),
                'default'     => [
                    'url' => '#',
                ],
                'show_external' => true,
                'description' => esc_html__( 'On submit the form will append ?domain=YOUR_INPUT to this URL. Do not add ?domain= yourself.', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'placeholder_text',
            [
                'label'       => esc_html__( 'Placeholder', 'easy-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Search domain...', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'       => esc_html__( 'Button Text', 'easy-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Create my website', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'show_search_icon',
            [
                'label' => esc_html__( 'Show Search Icon', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => esc_html__( 'Toggle the magnifier icon inside the input.', 'easy-elements' ),
            ]
        );

        $this->end_controls_section();

        // ===== Style Tab — Input =====
        $this->start_controls_section(
            'input_style_section',
            [
                'label' => esc_html__( 'Input Style', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label' => __('Input Text Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form input[type="text"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label' => __('Placeholder Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form input[type="text"]::placeholder' => 'color: {{VALUE}}; opacity: 1;',
                ],
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label' => __('Input Background', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form input[type="text"]' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eel-domain-search form input[type="text"]:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eel-domain-search form input[type="text"]:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'input_typography',
                'label'    => __( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-domain-search form input[type="text"]',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'input_border',
                'label' => __( 'Border', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-domain-search form input[type="text"]',
            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label' => __( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form input[type="text"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label' => __( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form input[type="text"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'input_icon_color',
            [
                'label' => __('Search Icon Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search .unicon-search' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_search_icon' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_icon_size',
            [
                'label' => __( 'Search Icon Size', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 8, 'max' => 60 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search .unicon-search' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_search_icon' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // ===== Style Tab — Button =====
        $this->start_controls_section(
            'button_style_section',
            [
                'label' => esc_html__( 'Button Style', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('button_style_tabs');

        // Normal tab
        $this->start_controls_tab(
            'button_normal_tab',
            [
                'label' => __('Normal', 'easy-elements'),
            ]
        );

        // Button background color (Normal)
        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Button text color (Normal)
        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_typography',
                'label'    => __( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-domain-search form button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'label' => __( 'Border', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-domain-search form button',
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => __( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => __( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover tab
        $this->start_controls_tab(
            'button_hover_tab',
            [
                'label' => __('Hover', 'easy-elements'),
            ]
        );

        // Button background color (Hover)
        $this->add_control(
            'button_bg_hover_color',
            [
                'label' => __('Background', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Button text color (Hover)
        $this->add_control(
            'button_text_hover_color',
            [
                'label' => __('Text Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'btn_border_hover',
                'label' => __( 'Border', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-domain-search form button:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings    = $this->get_settings_for_display();
        $base_url    = ! empty( $settings['action_url']['url'] ) ? $settings['action_url']['url'] : '#';
        $placeholder = ! empty( $settings['placeholder_text'] ) ? $settings['placeholder_text'] : 'Search domain...';
        $button_text = ! empty( $settings['button_text'] ) ? $settings['button_text'] : 'Create my website';
        $show_icon   = ! isset( $settings['show_search_icon'] ) || $settings['show_search_icon'] === 'yes';
        ?>
        <div class="eel-domain-search">
            <form method="get" action="<?php echo esc_url( $base_url ); ?>" target="_blank">
                <?php if ( $show_icon ) : ?>
                    <i class="unicon-search"></i>
                <?php endif; ?>
                <input type="text" name="domain" placeholder="<?php echo esc_attr( $placeholder ); ?>" required>
                <button type="submit"><?php echo esc_html( $button_text ); ?></button>
            </form>
        </div>
        <?php
    }
}