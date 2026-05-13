<?php
namespace Easyel\EasyElements\Extensions\WrapperLink;
use Easyel\EasyElements\Extensions\WrapperLink\Controls\LinkControls;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class EasyelWrapperLink {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
            self::$instance->easyel_setup_hooks();
        }
        return self::$instance;
    }

    private function easyel_setup_hooks() {

        $tab_slug = 'extensions';
        $extensions_settings = get_option('easy_element_' . $tab_slug, [] );

        $enable_wrapper_link = isset( $extensions_settings['enable_wrapper_link'] ) ? $extensions_settings['enable_wrapper_link'] : 0;

        if(  (int) $enable_wrapper_link !== 1 ) {
            return;
        }
        
        add_action( 'plugins_loaded', [ $this, 'easyel_check_elementor' ] );
        
    }

    public function easyel_check_elementor() {
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }

        add_action('elementor/element/common/_section_style/after_section_end', [ $this, 'easy_wrapper_link_register_section'] );
		add_action('elementor/element/section/section_advanced/after_section_end', [ $this, 'easy_wrapper_link_register_section'] );

        add_action('elementor/element/common/easyel_wrapper_link_section/before_section_end', [ $this, 'easyel_add_wrapper_controls'], 10, 2 );
 		add_action('elementor/element/section/easyel_wrapper_link_section/before_section_end', [ $this, 'easyel_add_wrapper_controls'], 10, 2 );

        add_action('elementor/element/container/section_layout/after_section_end', [ $this, 'easy_wrapper_link_register_section'] );

        add_action('elementor/element/container/easyel_wrapper_link_section/before_section_end', [ $this, 'easyel_add_wrapper_controls'], 10, 2 );
        add_action( 'elementor/frontend/before_render', [ $this, 'easyel_link_before_render' ], 10, 1 );

        $render_hooks = [
            'elementor/frontend/widget/before_render'   => 'should_script_enqueue',
            'elementor/frontend/widget/after_render'    => 'should_script_enqueue',
            'elementor/frontend/section/before_render'  => 'should_script_enqueue',
            'elementor/frontend/section/after_render'   => 'should_script_enqueue',
            'elementor/frontend/column/before_render'   => 'should_script_enqueue',
            'elementor/frontend/column/after_render'    => 'should_script_enqueue',
            'elementor/frontend/before_render'          => 'should_script_enqueue',
            'elementor/frontend/container/before_render'=> 'should_script_enqueue',
        ];

        foreach ( $render_hooks as $hook => $method ) {
            add_action( $hook, [ $this, $method ] );
        }

    }

    public function easy_wrapper_link_register_section( $element_data ) {

        if ( 'section' === $element_data->get_name() || 'column' === $element_data->get_name() || 'container' === $element_data->get_name() ) {
			$wrapper_position = \Elementor\Controls_Manager::TAB_ADVANCED;
		} else {
			$wrapper_position = \Elementor\Controls_Manager::TAB_CONTENT;
		}

        $element_data->start_controls_section(
            'easyel_wrapper_link_section',
            [
                'label' => EASY_EXTENSION_BADGE . __( 'Wrapper Link', 'easy-elements' ),
                'tab'   => $wrapper_position,
            ]
        );
       
		$element_data->end_controls_section();
	}

    public function easyel_add_wrapper_controls( $element, $section_id ) {

		LinkControls::register_controls( $element );
    }

    public function easyel_link_before_render( $widget ) {
		$settings = $widget->get_settings_for_display();

        if ( isset( $settings['easyel_wrapper_link_url'] ) && ! empty( $settings['easyel_wrapper_link_url']['url'] ) ) {
            $wrapper_link = $settings['easyel_wrapper_link_url'];

            if ( $wrapper_link && ! empty( $wrapper_link['url'] ) ) {
                $wrapper_link['url'] = esc_url( $wrapper_link['url'] );

                $widget->add_render_attribute(
                    '_wrapper',
                    [ 
                        'data-easyel-wrapper-link' => wp_json_encode( $wrapper_link, true ),
                        'style' => 'cursor: pointer',
                        'class' => 'easyel-wrapper-link'
                    ]
                );
            }
        }
	}

    public function enqueue_scripts() {
		wp_enqueue_script( 'easyel-wrapper-link' );
	}

	public function should_script_enqueue( $widget ) {

        $settings = $widget->get_settings_for_display();
        if ( isset( $settings['easyel_wrapper_link_url'] ) && ! empty( $settings['easyel_wrapper_link_url']['url'] ) ) {
            $element_link = $settings['easyel_wrapper_link_url'];

            if ( $element_link && ! empty( $element_link['url'] ) ) {
                $this->enqueue_scripts();
            }
        }
	}
}
