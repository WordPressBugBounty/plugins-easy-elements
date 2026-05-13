<?php
/**
 * Preloader Extension.
 *
 * @package Easyel\EasyElements\Extensions\Preloader
 */

namespace Easyel\EasyElements\Extensions\Preloader;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Preloader extension class.
 */
class EasyelPreloader {

    /**
     * Singleton instance.
     *
     * @var EasyelPreloader|null
     */
    private static $instance = null;

    /**
     * Whether preloader HTML has been printed already.
     *
     * @var bool
     */
    private $rendered = false;

    /**
     * Constructor.
     */
    private function __construct() {

        $tab_slug            = 'extensions';
        $extensions_settings = get_option( 'easy_element_' . $tab_slug, array() );

        $enabled = isset( $extensions_settings['enable_preloader'] ) ? (int) $extensions_settings['enable_preloader'] : 0;

        if ( 1 !== $enabled ) {
            return;
        }

        if ( is_admin() ) {
            return;
        }

        add_action( 'wp_body_open', array( $this, 'render_preloader_html' ), 1 );
        add_action( 'wp_footer', array( $this, 'render_preloader_html_fallback' ), 1 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Get singleton instance.
     *
     * @return EasyelPreloader
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get sanitized settings merged with defaults.
     *
     * @return array
     */
    private function get_settings() {

        $defaults = array(
            'preloader_style'           => 'circle',
            'preloader_bg_color'        => '#ffffff',
            'preloader_color'           => '#5933ff',
            'preloader_secondary_color' => '#e0e0e0',
            'preloader_size'            => 60,
            'preloader_speed'           => 1.0,
            'preloader_min_time'        => 500,
            'preloader_fadeout_time'    => 600,
            'preloader_logo'            => '',
            'preloader_logo_width'      => 36,
            'preloader_logo_height'     => 36,
            'preloader_disable_mobile'  => 0,
        );

        $saved = get_option( 'easyel_preloader_settings', array() );

        if ( ! is_array( $saved ) ) {
            $saved = array();
        }

        return wp_parse_args( $saved, $defaults );
    }

    /**
     * Enqueue CSS/JS and inline dynamic styles.
     */
    public function enqueue_scripts() {

        $settings = $this->get_settings();

        if ( ! empty( $settings['preloader_disable_mobile'] ) && wp_is_mobile() ) {
            return;
        }

        wp_enqueue_style(
            'easyel-preloader',
            EASYELEMENTS_DIR_URL . 'includes/Extensions/Preloader/assets/css/preloader.css',
            array(),
            EASYELEMENTS_VER
        );

        $bg = sanitize_hex_color( $settings['preloader_bg_color'] );
        if ( ! $bg ) {
            $bg = '#ffffff';
        }

        $color = sanitize_hex_color( $settings['preloader_color'] );
        if ( ! $color ) {
            $color = '#5933ff';
        }

        $sec_color = sanitize_hex_color( $settings['preloader_secondary_color'] );
        if ( ! $sec_color ) {
            $sec_color = '#e0e0e0';
        }

        $size  = absint( $settings['preloader_size'] );
        $size  = $size > 0 ? $size : 60;

        $speed = (float) $settings['preloader_speed'];
        $speed = $speed > 0 ? $speed : 1.0;

        $fadeout = absint( $settings['preloader_fadeout_time'] );

        $custom_css  = '.easyel-preloader{';
        $custom_css .= '--easyel-preloader-bg:' . $bg . ';';
        $custom_css .= '--easyel-preloader-color:' . $color . ';';
        $custom_css .= '--easyel-preloader-secondary:' . $sec_color . ';';
        $custom_css .= '--easyel-preloader-size:' . $size . 'px;';
        $custom_css .= '--easyel-preloader-speed:' . $speed . 's;';
        $custom_css .= '--easyel-preloader-fadeout:' . $fadeout . 'ms;';
        $custom_css .= '}';

        wp_add_inline_style( 'easyel-preloader', $custom_css );

        wp_enqueue_script(
            'easyel-preloader',
            EASYELEMENTS_DIR_URL . 'includes/Extensions/Preloader/assets/js/preloader.js',
            array( 'jquery' ),
            EASYELEMENTS_VER,
            true
        );

        wp_localize_script(
            'easyel-preloader',
            'easyelPreloaderData',
            array(
                'minTime'     => absint( $settings['preloader_min_time'] ),
                'fadeoutTime' => $fadeout,
            )
        );
    }

    /**
     * Render preloader HTML markup.
     */
    public function render_preloader_html() {

        if ( $this->rendered ) {
            return;
        }

        $settings = $this->get_settings();

        if ( ! empty( $settings['preloader_disable_mobile'] ) && wp_is_mobile() ) {
            $this->rendered = true;
            return;
        }

        $allowed_styles = array( 'circle', 'dotted_circle', 'dots', 'bars', 'pulse', 'custom_logo' );
        $style          = isset( $settings['preloader_style'] ) ? sanitize_key( $settings['preloader_style'] ) : 'circle';

        if ( ! in_array( $style, $allowed_styles, true ) ) {
            $style = 'circle';
        }

        $logo = isset( $settings['preloader_logo'] ) ? esc_url( $settings['preloader_logo'] ) : '';

        $inner = '';

        $inner .= '<div class="easyel-preloader-spinner">';

        switch ( $style ) {
            case 'dots':
                $inner .= '<span class="easyel-preloader-dot"></span>';
                $inner .= '<span class="easyel-preloader-dot"></span>';
                $inner .= '<span class="easyel-preloader-dot"></span>';
                break;

            case 'bars':
                $inner .= '<span class="easyel-preloader-bar"></span>';
                $inner .= '<span class="easyel-preloader-bar"></span>';
                $inner .= '<span class="easyel-preloader-bar"></span>';
                $inner .= '<span class="easyel-preloader-bar"></span>';
                break;

            case 'pulse':
                $inner .= '<span class="easyel-preloader-pulse"></span>';
                break;

            case 'dotted_circle':
                $inner .= '<span class="easyel-preloader-dotted-circle">';
                $inner .= '<div></div><div></div><div></div><div></div>';
                $inner .= '<div></div><div></div><div></div><div></div>';
                $inner .= '</span>';
                break;

            case 'custom_logo':
                $inner .= '<span class="easyel-preloader-logo-ring"></span>';
                if ( ! empty( $logo ) ) {
                    $logo_w = absint( $settings['preloader_logo_width'] );
                    $logo_w = $logo_w > 0 ? $logo_w : 36;
                    $logo_h = absint( $settings['preloader_logo_height'] );
                    $logo_h = $logo_h > 0 ? $logo_h : 36;
                    $inner .= '<img class="easyel-preloader-logo-img" src="' . $logo . '" alt=""'
                        . ' style="width:' . $logo_w . 'px;height:' . $logo_h . 'px;">';
                }
                break;

            case 'circle':
            default:
                $inner .= '<span class="easyel-preloader-circle"></span>';
                break;
        }

        $inner .= '</div>';

        $html  = '<div class="easyel-preloader easyel-preloader-style-' . esc_attr( $style ) . '" aria-hidden="true">';
        $html .= $inner;
        $html .= '</div>';

        // Markup is built from sanitized values via esc_attr/esc_url; pass through wp_kses for defense in depth.
        echo wp_kses( $html, easyel_allowed_html() );

        $this->rendered = true;
    }

    /**
     * Fallback rendering for themes that do not implement wp_body_open().
     */
    public function render_preloader_html_fallback() {

        if ( $this->rendered ) {
            return;
        }

        $this->render_preloader_html();
    }
}
