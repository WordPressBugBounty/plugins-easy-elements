<?php 
namespace Easyel\EasyElements\Extensions;
if ( ! defined( 'ABSPATH' ) ) exit;

class PromoHelper {

    /**
     * Return Promo HTML for Elementor
     *
     * @param string $button_url
     * @param string $button_text
     * @return string
     */
    public static function get_pro_promo_html( $button_url = '', $button_text = '' ) {
        $button_url  = $button_url ? esc_url( $button_url ) : esc_url( 'https://wpeasyelements.com' );
        $button_text = $button_text ? esc_html( $button_text ) : esc_html__( 'Upgrade Easy Elements', 'easy-elements' );

        return sprintf(
            '<div class="easy-elements-pro-badge">
                <div class="easy-elements-pro-icon">
                    <!-- SVG Lock Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <p class="easy-elements-pro-text">
                   Upgrade now to access every advanced feature!
                </p>
                <a href="%s" target="_blank" class="easy-elements-pro-btn">%s</a>
            </div>',
            $button_url,
            $button_text
        );
    }

}