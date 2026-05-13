<?php
namespace Easyel\EasyElements\Extensions\ScrollTop;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ScrollTop {

    private static $instance = null;

    private function __construct() {

        $tab_slug = 'extensions';
        $extensions_settings = get_option( 'easy_element_' . $tab_slug, [] );

        $enable_scroll_top = isset( $extensions_settings['enable_scroll_top'] ) ? $extensions_settings['enable_scroll_top'] : 0;

        if ( (int) $enable_scroll_top !== 1 ) {
            return;
        }

        add_action( 'wp_footer', [ $this, 'render_scroll_top_html' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    public static function get_instance() : self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function enqueue_scripts() {

        wp_enqueue_style(
            'easyel-scroll-top',
            EASYELEMENTS_DIR_URL . 'includes/Extensions/ScrollTop/assets/css/scroll-top.css',
            [],
            EASYELEMENTS_VER
        );

        $settings = get_option( 'easyel_scroll_top_settings', [] );
        $bg_color_raw = ! empty( $settings['scroll_top_bg_color'] ) ? sanitize_hex_color( $settings['scroll_top_bg_color'] ) : null;
        $bg_color = $bg_color_raw ? $bg_color_raw : '#333333';
        $icon_color_raw = ! empty( $settings['scroll_top_icon_color'] ) ? sanitize_hex_color( $settings['scroll_top_icon_color'] ) : null;
        $icon_color = $icon_color_raw ? $icon_color_raw : '#ffffff';
        $size = ! empty( $settings['scroll_top_size'] ) ? absint( $settings['scroll_top_size'] ) : 45;
        $radius = ! empty( $settings['scroll_top_radius'] ) ? absint( $settings['scroll_top_radius'] ) : 50;
        $position = isset( $settings['scroll_top_position'] ) ? $settings['scroll_top_position'] : 'right';
        $offset = ! empty( $settings['scroll_top_offset'] ) ? absint( $settings['scroll_top_offset'] ) : 300;
        $border_width = isset( $settings['scroll_top_border_width'] ) ? absint( $settings['scroll_top_border_width'] ) : 0;
        $border_color_raw = ! empty( $settings['scroll_top_border_color'] ) ? sanitize_hex_color( $settings['scroll_top_border_color'] ) : null;
        $border_color = $border_color_raw ? $border_color_raw : '#cccccc';
        $hover_bg_color_raw = ! empty( $settings['scroll_top_hover_bg_color'] ) ? sanitize_hex_color( $settings['scroll_top_hover_bg_color'] ) : null;
        $hover_bg_color = $hover_bg_color_raw ? $hover_bg_color_raw : '#000000';
        $hover_icon_color_raw = ! empty( $settings['scroll_top_hover_icon_color'] ) ? sanitize_hex_color( $settings['scroll_top_hover_icon_color'] ) : null;
        $hover_icon_color = $hover_icon_color_raw ? $hover_icon_color_raw : '#ffffff';
        $hover_border_color_raw = ! empty( $settings['scroll_top_hover_border_color'] ) ? sanitize_hex_color( $settings['scroll_top_hover_border_color'] ) : null;
        $hover_border_color = $hover_border_color_raw ? $hover_border_color_raw : '#cccccc';

        switch ( $position ) {
            case 'left':
                $pos_css = 'left: 20px; right: auto;';
                break;
            case 'center':
                $pos_css = 'left: 50%; right: auto; transform: translateX(-50%);';
                break;
            default:
                $pos_css = 'right: 20px; left: auto;';
                break;
        }

        $custom_css = "
            .easyel-scroll-top-btn {
                --easyel-scroll-top-bg: {$bg_color};
                --easyel-scroll-top-color: {$icon_color};
                --easyel-scroll-top-hover-bg: {$hover_bg_color};
                --easyel-scroll-top-hover-color: {$hover_icon_color};
                --easyel-scroll-top-hover-border: {$hover_border_color};
                --easyel-scroll-top-size: {$size}px;
                --easyel-scroll-top-radius: {$radius}%;
                border: {$border_width}px solid {$border_color};
                {$pos_css}
            }
        ";

        wp_add_inline_style( 'easyel-scroll-top', $custom_css );

        wp_enqueue_script(
            'easyel-scroll-top',
            EASYELEMENTS_DIR_URL . 'includes/Extensions/ScrollTop/assets/js/scroll-top.js',
            [ 'jquery' ],
            EASYELEMENTS_VER,
            true
        );

        wp_localize_script( 'easyel-scroll-top', 'easyelScrollTopData', [
            'offset' => $offset,
        ] );
    }

    public function render_scroll_top_html() {
        $settings = get_option( 'easyel_scroll_top_settings', [] );
        $icon = isset( $settings['scroll_top_icon'] ) ? $settings['scroll_top_icon'] : 'arrow';

        $icon_html = '';

        switch ( $icon ) {
            case 'custom_icon':
                $custom_class = ! empty( $settings['scroll_top_custom_icon'] ) ? $settings['scroll_top_custom_icon'] : 'fas fa-arrow-up';
                $icon_html = '<i class="' . esc_attr( $custom_class ) . '"></i>';
                break;
            case 'custom_image':
                $custom_image = ! empty( $settings['scroll_top_custom_image'] ) ? $settings['scroll_top_custom_image'] : '';
                if ( $custom_image ) {
                    $icon_html = '<img src="' . esc_url( $custom_image ) . '" alt="' . esc_attr__( 'Scroll to top', 'easy-elements' ) . '">';
                } else {
                    $icon_html = $this->get_icon_svg( 'arrow' );
                }
                break;
            default:
                $icon_html = $this->get_icon_svg( $icon );
                break;
        }

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<button class="easyel-scroll-top-btn" aria-label="' . esc_attr__( 'Scroll to top', 'easy-elements' ) . '">' . wp_kses( $icon_html, easyel_allowed_html() ) . '</button>';
    }

    private function get_icon_svg( $icon ) {
        switch ( $icon ) {
            case 'chevron':
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M201.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 205.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z"/></svg>';
            case 'double':
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M246.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L224 109.3 361.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160zm160 352l-160-160c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L224 301.3 361.4 438.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3z"/></svg>';
            default: // arrow
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"/></svg>';
        }
    }
}
