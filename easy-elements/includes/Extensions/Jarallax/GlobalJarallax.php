<?php 
namespace Easyel\EasyElements\Extensions\Jarallax;
// Jarallax Use Background
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit;

class GlobalJarallax {

    private static $instance = null;

    public static function get_instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {

        // Add controls
        add_action( 'elementor/element/container/section_background/before_section_end', [ $this, 'eel_add_jarallax_controls' ], 10, 2 );
        add_action( 'elementor/element/common/section_background/before_section_end', [ $this, 'eel_add_jarallax_controls' ], 10, 2 );
        add_action( 'elementor/element/section/section_background/before_section_end', [ $this, 'eel_add_jarallax_controls' ], 10, 2 );

        add_action( 'elementor/section/print_template', [ $this, 'easyel_jarallax_print_template' ], 10, 2 );
        add_action( 'elementor/column/print_template', [ $this, 'easyel_jarallax_print_template' ], 10, 2 );
        add_action( 'elementor/widget/print_template', [ $this, 'easyel_jarallax_print_template' ], 10, 2 );
       

        // Add render actions
        add_action( 'elementor/frontend/section/before_render', [ $this, 'easyel_jarallax_before_render' ] );
        add_action( 'elementor/frontend/column/before_render', [ $this, 'easyel_jarallax_before_render' ] );
        add_action( 'elementor/widget/before_render_content', [ $this, 'easyel_jarallax_before_render' ], 10, 1 );
        add_action( 'elementor/container/print_template', [ $this, 'easyel_jarallax_print_template' ], 10, 2 );
        add_action( 'elementor/frontend/container/before_render', [ $this, 'easyel_jarallax_before_render' ] );

      

    }

    // Controls function
    
    public function eel_add_jarallax_controls( $element, $args ) {
        $element->add_control(
			'easy_jarallax_switcher',
			[
				'label' => esc_html__( 'Enable Jarallax', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'easy-elements' ),
				'label_off' => esc_html__( 'Hide', 'easy-elements' ),
				'return_value' => 'yes',
				'default' => 'no',
                'prefix_class' => 'easy-enable-jarallax-',
				'render_type'  => 'template',
			]
		);

        $element->add_control(
            'jarallax_smooth_scroll_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(
                    '<div style="background-color: #f1f1f1; border-left: 4px solid #3b82f6; padding: 12px; font-size: 12px; line-height: 1.5; color: #444; margin-top: 10px; border-radius: 0 4px 4px 0;">
                        <strong style="color: #111;">%s</strong> %s
                    </div>',
                    esc_html__( 'Note:', 'easy-elements' ),
                    esc_html__( 'For a better parallax experience, We highly recommend enabling the "Scroll Smoother" option from the Plugin Settings.', 'easy-elements' )
                ),
                'condition' => [
                    'easy_jarallax_switcher' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eel_jarallax_bg',
            [
                'label' => __( 'Jarallax Background Image', 'easy-elements' ),
                'type'  => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
                'condition' => [
                    'easy_jarallax_switcher' => 'yes',
                ],
            ]
        );       

        $element->add_control(
            'eel_jarallax_speed',
            [
                'label' => __( 'Parallax Speed', 'easy-elements' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.5,
                ],
                'condition' => [
                    'eel_jarallax_bg[url]!' => '',
                    'easy_jarallax_switcher' => 'yes',
                ],
            ]
        );
    }

    public function easyel_jarallax_print_template( $template, $element ) {
        if ( ! $template && 'widget' === $element->get_type() ) return $template;
        $before_template = $template;
        ob_start();
        ?>
        <#
            var jarallax_enabled = settings.easy_jarallax_switcher === 'yes';
            var bg  = settings.eel_jarallax_bg ? settings.eel_jarallax_bg.url : '';
            var spd = settings.eel_jarallax_speed ? settings.eel_jarallax_speed.size : 0.5;

            if ( jarallax_enabled && bg ) {
                var wrapper_class = 'eele-has-jarallax, eele-inner-jarallax';
                if ( elementorFrontend.isEditMode() ) {
                    wrapper_class += ' eele-editor-bg-wrap';
                }

                view.addRenderAttribute('jarallax_data', {
                    'class': wrapper_class,
                    'data-jarallax-bg': bg,
                    'data-jarallax-speed': spd
                });
        #>
                <div {{{ view.getRenderAttributeString('jarallax_data') }}}></div>
        <#
            }
        #>
        <?php
        $jarallax_content = ob_get_clean();
        $template = $jarallax_content . $before_template;
        return $template;
    }

    public function easyel_jarallax_before_render( $element ) {
        $settings = $element->get_settings_for_display();

        if ( empty( $settings['easy_jarallax_switcher'] ) || $settings['easy_jarallax_switcher'] !== 'yes' ) return;
        if ( empty( $settings['eel_jarallax_bg']['url'] ) ) return;

        $bg  = $settings['eel_jarallax_bg']['url'];
        $spd = ! empty( $settings['eel_jarallax_speed']['size'] ) ? $settings['eel_jarallax_speed']['size'] : 0.5;
        $element->add_render_attribute( '_wrapper', 'class', 'eele-has-jarallax' );
        $element->add_render_attribute( '_wrapper', 'class', 'easy-enable-jarallax-yes' );

        // background & speed data
        $element->add_render_attribute( '_wrapper', 'data-jarallax-bg', esc_url($bg) );
        $element->add_render_attribute( '_wrapper', 'data-jarallax-speed', esc_attr($spd) );
    }
}