<?php 
namespace Easyel\EasyElements\Widgets;
defined( 'ABSPATH' ) || die();
class Easyel_Before_After_Widget extends \Elementor\Widget_Base {
     
    public function get_name() {
        return 'eel-image-before-after';
    }

    public function get_title() {
        return esc_html__( 'Image Comparison', 'easy-elements' );
    }

    public function get_icon() {
        return 'easyicon easyelIcon-image-carousel';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_style_depends() {
        return [
            'eel-image-before-after',
        ];
    }

    public function get_script_depends() {
        return [
            'eel-image-before-after',
        ];
    }

    public function get_keywords() {
        return ['before', 'after', 'image', 'compare', 'slider', 'comparison', 'comparison slider', 'comparison image', 'comparison before', 'comparison after', 'comparison slider','image comparison'];
    }

     protected function _register_controls() {
          $this->start_controls_section(
              'content_section',
              [
                  'label' => __('Images', 'easy-elements'),
                  'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
              ]
          );
  
          $this->add_control(
              'before_image',
              [
                  'label' => __('Before Image', 'easy-elements'),
                  'type' => \Elementor\Controls_Manager::MEDIA,
                  'default' => [
                      'url' => \Elementor\Utils::get_placeholder_image_src(),
                  ],
              ]
          );
  
          $this->add_control(
              'after_image',
              [
                  'label' => __('After Image', 'easy-elements'),
                  'type' => \Elementor\Controls_Manager::MEDIA,
                  'default' => [
                      'url' => \Elementor\Utils::get_placeholder_image_src(),
                  ],
              ]
          );
  
            $this->add_control(
              'orientation',
              [
                  'label' => __('Layout', 'easy-elements'),
                  'type' => \Elementor\Controls_Manager::SELECT,
                  'options' => [
                      'horizontal' => __('Horizontal', 'easy-elements'),
                      'vertical' => __('Vertical', 'easy-elements'),
                  ],
                  'default' => 'horizontal',
              ]
            );
  
          $this->add_control(
              'offset',
              [
                  'label' => __('Default Offset (%)', 'easy-elements'),
                  'type' => \Elementor\Controls_Manager::SLIDER,
                  'range' => [
                      '%' => [
                          'min' => 0,
                          'max' => 100,
                          'step' => 1,
                      ],
                  ],
                  'default' => [
                      'unit' => '%',
                      'size' => 50,
                  ],
              ]
          );

          $this->end_controls_section();

        // style section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Styles', 'easy-elements'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'wimage_heightidth',
			[
				'label' => esc_html__( 'Width', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 540,
				],
				'selectors' => [
                    '{{WRAPPER}} .eel_comparison-container' => 'min-height: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .eel_comparison-container img' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

        $this->add_responsive_control(
            'container_radius',
            [
                'label' => __('Container Radius', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eel_comparison-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
     }

    protected function render() {
        $settings = $this->get_settings_for_display();        
        $before_image_url = ! empty( $settings['before_image']['url'] ) ? $settings['before_image']['url'] : '';
        $after_image_url  = ! empty( $settings['after_image']['url'] ) ? $settings['after_image']['url'] : '';        
        
        $orientation = ! empty( $settings['orientation'] ) ? $settings['orientation'] : 'horizontal';
        $offset      = isset( $settings['offset']['size'] ) ? ( $settings['offset']['size'] / 100 ) : 0.5;
        
        $this->add_render_attribute( 'wrapper', [
            'class'            => 'eel_comparison-container',
            'data-offset'      => esc_attr( $offset ),
            'data-orientation' => esc_attr( $orientation ),
        ] );
        ?>

        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
            <?php if ( ! empty( $before_image_url ) ) : ?>
                <img src="<?php echo esc_url( $before_image_url ); ?>" alt="<?php echo esc_attr__( 'Before', 'easy-elements' ); ?>">
            <?php endif; ?>

            <?php if ( ! empty( $after_image_url ) ) : ?>
                <img src="<?php echo esc_url( $after_image_url ); ?>" alt="<?php echo esc_attr__( 'After', 'easy-elements' ); ?>">
            <?php endif; ?>
        </div>
        <?php
    }
}