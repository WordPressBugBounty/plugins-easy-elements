<?php
namespace Easyel\EasyElements\Extensions\ExtensionHook;
use Easyel\EasyElements\Extensions\PromoHelper;
if ( ! defined( 'ABSPATH' ) ) exit;

class HookControl {

	public static function register_controls( $element, $scroll_trigger = 'scroll_trigger_html_promo' ) {
		$promo_html = PromoHelper::get_pro_promo_html(); 

        $element->add_control(
            'easy_' . $scroll_trigger,
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw'  => $promo_html,
            ]
        );
	}
}