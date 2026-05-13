<?php
namespace Easyel\EasyElements\Extensions\WrapperLink\Controls;
if ( ! defined( 'ABSPATH' ) ) exit;

class LinkControls {

	public static function register_controls( $element ) {

        $element->add_control(
            'easyel_wrapper_link_url',
            [
                'label'       => __( 'Link', 'easy-elements' ),
                'type'        => \Elementor\Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'easy-elements' ),
                'dynamic'     => [ 'active' => true ],
                'default'     => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'show_external' => true,
            ]
        );
    }
}