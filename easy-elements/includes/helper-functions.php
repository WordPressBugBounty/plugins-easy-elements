<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'easyel_allowed_html' ) ) {
    /**
     * Returns allowed HTML tags and attributes for wp_kses.
     *
     * Covers all HTML used in the product gallery output including
     * SVG elements, Swiper markup, form elements, and data attributes.
     *
     * @since 1.0.0
     *
     * @return array Allowed HTML tags with their attributes.
     */
    function easyel_allowed_html() {

        $global_attributes = array(
            'class'            => true,
            'id'               => true,
            'style'            => true,
            'title'            => true,
            'role'             => true,
            'tabindex'         => true,
            'aria-label'       => true,
            'aria-live'        => true,
            'aria-atomic'      => true,
            'aria-current'     => true,
            'aria-controls'    => true,
            'aria-disabled'    => true,
            'aria-hidden'      => true,
            'aria-expanded'    => true,
            'aria-selected'    => true,
            'aria-describedby' => true,
            'aria-labelledby'  => true,
            'aria-haspopup'    => true,
            'aria-pressed'     => true,
            'aria-checked'     => true,
            'aria-valuenow'    => true,
            'aria-valuemin'    => true,
            'aria-valuemax'    => true,
            'data-*'           => true,
        );

        $allowed_tags = array(

            // Structural
            'div'      => $global_attributes,
            'span'     => $global_attributes,
            'section'  => $global_attributes,
            'article'  => $global_attributes,
            'aside'    => $global_attributes,
            'header'   => $global_attributes,
            'footer'   => $global_attributes,
            'main'     => $global_attributes,
            'figure'   => $global_attributes,
            'figcaption' => $global_attributes,
            'nav'      => $global_attributes,
            'ul'       => $global_attributes,
            'ol'       => array_merge( $global_attributes, array(
                'start'    => true,
                'reversed' => true,
                'type'     => true,
            )),
            'li'       => array_merge( $global_attributes, array(
                'value' => true,
            )),

            // Headings
            'h1'       => $global_attributes,
            'h2'       => $global_attributes,
            'h3'       => $global_attributes,
            'h4'       => $global_attributes,
            'h5'       => $global_attributes,
            'h6'       => $global_attributes,

            // Text / Inline
            'p'        => $global_attributes,
            'a'        => array_merge( $global_attributes, array(
                'href'     => true,
                'target'   => true,
                'rel'      => true,
                'download' => true,
            )),
            'strong'   => $global_attributes,
            'b'        => $global_attributes,
            'em'       => $global_attributes,
            'i'        => $global_attributes,
            'u'        => $global_attributes,
            's'        => $global_attributes,
            'small'    => $global_attributes,
            'sub'      => $global_attributes,
            'sup'      => $global_attributes,
            'br'       => $global_attributes,
            'hr'       => $global_attributes,
            'abbr'     => $global_attributes,
            'cite'     => $global_attributes,
            'code'     => $global_attributes,
            'pre'      => $global_attributes,
            'mark'     => $global_attributes,
            'del'      => array_merge( $global_attributes, array(
                'datetime' => true,
            )),
            'ins'      => array_merge( $global_attributes, array(
                'datetime' => true,
            )),
            'bdi'      => $global_attributes,
            'bdo'      => array_merge( $global_attributes, array(
                'dir' => true,
            )),
            'time'     => array_merge( $global_attributes, array(
                'datetime' => true,
            )),

            // Images
            'img'      => array_merge( $global_attributes, array(
                'src'           => true,
                'srcset'        => true,
                'sizes'         => true,
                'alt'           => true,
                'width'         => true,
                'height'        => true,
                'loading'       => true,
                'decoding'      => true,
                'fetchpriority' => true,
                'crossorigin'   => true,
                'usemap'        => true,
                'ismap'         => true,
            )),
            'picture'  => $global_attributes,
            'source'   => array_merge( $global_attributes, array(
                'src'    => true,
                'srcset' => true,
                'sizes'  => true,
                'media'  => true,
                'type'   => true,
            )),

            // Video / Audio
            'video'    => array_merge( $global_attributes, array(
                'src'         => true,
                'poster'      => true,
                'width'       => true,
                'height'      => true,
                'autoplay'    => true,
                'controls'    => true,
                'loop'        => true,
                'muted'       => true,
                'playsinline' => true,
                'preload'     => true,
            )),
            'audio'    => array_merge( $global_attributes, array(
                'src'      => true,
                'autoplay' => true,
                'controls' => true,
                'loop'     => true,
                'muted'    => true,
                'preload'  => true,
            )),
            'iframe'   => array_merge( $global_attributes, array(
                'src'             => true,
                'width'           => true,
                'height'          => true,
                'frameborder'     => true,
                'allow'           => true,
                'allowfullscreen' => true,
                'loading'         => true,
                'sandbox'         => true,
                'name'            => true,
            )),

            // Form elements
            'form'     => array_merge( $global_attributes, array(
                'action'  => true,
                'method'  => true,
                'enctype' => true,
                'name'    => true,
                'target'  => true,
                'novalidate' => true,
                'autocomplete' => true,
            )),
            'input'    => array_merge( $global_attributes, array(
                'type'         => true,
                'name'         => true,
                'value'        => true,
                'placeholder'  => true,
                'min'          => true,
                'max'          => true,
                'step'         => true,
                'checked'      => true,
                'disabled'     => true,
                'readonly'     => true,
                'required'     => true,
                'autofocus'    => true,
                'autocomplete' => true,
                'inputmode'    => true,
                'pattern'      => true,
                'size'         => true,
                'maxlength'    => true,
                'minlength'    => true,
                'multiple'     => true,
                'accept'       => true,
                'list'         => true,
                'form'         => true,
            )),
            'button'   => array_merge( $global_attributes, array(
                'type'     => true,
                'name'     => true,
                'value'    => true,
                'disabled' => true,
                'form'     => true,
            )),
            'label'    => array_merge( $global_attributes, array(
                'for' => true,
            )),
            'select'   => array_merge( $global_attributes, array(
                'name'         => true,
                'multiple'     => true,
                'disabled'     => true,
                'required'     => true,
                'size'         => true,
                'autocomplete' => true,
                'form'         => true,
            )),
            'option'   => array_merge( $global_attributes, array(
                'value'    => true,
                'selected' => true,
                'disabled' => true,
                'label'    => true,
            )),
            'optgroup' => array_merge( $global_attributes, array(
                'label'    => true,
                'disabled' => true,
            )),
            'textarea' => array_merge( $global_attributes, array(
                'name'         => true,
                'rows'         => true,
                'cols'         => true,
                'placeholder'  => true,
                'disabled'     => true,
                'readonly'     => true,
                'required'     => true,
                'maxlength'    => true,
                'minlength'    => true,
                'wrap'         => true,
                'autocomplete' => true,
                'form'         => true,
            )),
            'fieldset' => array_merge( $global_attributes, array(
                'disabled' => true,
                'form'     => true,
                'name'     => true,
            )),
            'legend'   => $global_attributes,

            // Table
            'table'    => array_merge( $global_attributes, array(
                'border'      => true,
                'cellpadding' => true,
                'cellspacing' => true,
                'width'       => true,
            )),
            'thead'    => $global_attributes,
            'tbody'    => $global_attributes,
            'tfoot'    => $global_attributes,
            'tr'       => $global_attributes,
            'th'       => array_merge( $global_attributes, array(
                'colspan' => true,
                'rowspan' => true,
                'scope'   => true,
                'abbr'    => true,
                'headers' => true,
            )),
            'td'       => array_merge( $global_attributes, array(
                'colspan' => true,
                'rowspan' => true,
                'headers' => true,
            )),
            'caption'  => $global_attributes,
            'colgroup' => array_merge( $global_attributes, array(
                'span' => true,
            )),
            'col'      => array_merge( $global_attributes, array(
                'span' => true,
            )),

            // SVG
            'svg'      => array_merge( $global_attributes, array(
                'xmlns'            => true,
                'viewBox'          => true,
                'viewbox'          => true,
                'width'            => true,
                'height'           => true,
                'fill'             => true,
                'stroke'           => true,
                'stroke-width'     => true,
                'stroke-linecap'   => true,
                'stroke-linejoin'  => true,
                'enable-background' => true,
                'preserveAspectRatio' => true,
                'x'                => true,
                'y'                => true,
                'opacity'          => true,
                'transform'        => true,
                'clip-path'        => true,
                'clip-rule'        => true,
                'mask'             => true,
                'overflow'         => true,
                'version'          => true,
                'xml:space'        => true,
                'xmlns:xlink'      => true,
                'focusable'        => true,
            )),
            'path'     => array_merge( $global_attributes, array(
                'd'              => true,
                'fill'           => true,
                'fill-rule'      => true,
                'fill-opacity'   => true,
                'stroke'         => true,
                'stroke-width'   => true,
                'stroke-linecap' => true,
                'stroke-linejoin' => true,
                'stroke-dasharray' => true,
                'stroke-dashoffset' => true,
                'stroke-opacity' => true,
                'opacity'        => true,
                'transform'      => true,
                'clip-path'      => true,
                'clip-rule'      => true,
            )),
            'circle'   => array_merge( $global_attributes, array(
                'cx'             => true,
                'cy'             => true,
                'r'              => true,
                'fill'           => true,
                'fill-opacity'   => true,
                'stroke'         => true,
                'stroke-width'   => true,
                'opacity'        => true,
                'transform'      => true,
            )),
            'ellipse'  => array_merge( $global_attributes, array(
                'cx'             => true,
                'cy'             => true,
                'rx'             => true,
                'ry'             => true,
                'fill'           => true,
                'fill-opacity'   => true,
                'stroke'         => true,
                'stroke-width'   => true,
                'opacity'        => true,
                'transform'      => true,
            )),
            'rect'     => array_merge( $global_attributes, array(
                'x'              => true,
                'y'              => true,
                'width'          => true,
                'height'         => true,
                'rx'             => true,
                'ry'             => true,
                'fill'           => true,
                'fill-opacity'   => true,
                'stroke'         => true,
                'stroke-width'   => true,
                'opacity'        => true,
                'transform'      => true,
            )),
            'line'     => array_merge( $global_attributes, array(
                'x1'             => true,
                'y1'             => true,
                'x2'             => true,
                'y2'             => true,
                'stroke'         => true,
                'stroke-width'   => true,
                'stroke-linecap' => true,
                'opacity'        => true,
                'transform'      => true,
            )),
            'polyline' => array_merge( $global_attributes, array(
                'points'         => true,
                'fill'           => true,
                'stroke'         => true,
                'stroke-width'   => true,
                'stroke-linecap' => true,
                'stroke-linejoin' => true,
                'opacity'        => true,
                'transform'      => true,
            )),
            'polygon'  => array_merge( $global_attributes, array(
                'points'         => true,
                'fill'           => true,
                'fill-rule'      => true,
                'stroke'         => true,
                'stroke-width'   => true,
                'opacity'        => true,
                'transform'      => true,
            )),
            'g'        => array_merge( $global_attributes, array(
                'fill'           => true,
                'fill-rule'      => true,
                'stroke'         => true,
                'stroke-width'   => true,
                'opacity'        => true,
                'transform'      => true,
                'clip-path'      => true,
            )),
            'defs'     => $global_attributes,
            'symbol'   => array_merge( $global_attributes, array(
                'viewBox'  => true,
                'viewbox'  => true,
                'width'    => true,
                'height'   => true,
                'fill'     => true,
                'overflow' => true,
            )),
            'use'      => array_merge( $global_attributes, array(
                'href'       => true,
                'xlink:href' => true,
                'x'          => true,
                'y'          => true,
                'width'      => true,
                'height'     => true,
                'fill'       => true,
                'stroke'     => true,
            )),
            'clipPath'  => $global_attributes,
            'linearGradient' => array_merge( $global_attributes, array(
                'x1'                => true,
                'y1'                => true,
                'x2'                => true,
                'y2'                => true,
                'gradientUnits'     => true,
                'gradientTransform' => true,
            )),
            'radialGradient' => array_merge( $global_attributes, array(
                'cx'                => true,
                'cy'                => true,
                'r'                 => true,
                'fx'                => true,
                'fy'                => true,
                'gradientUnits'     => true,
                'gradientTransform' => true,
            )),
            'stop'     => array_merge( $global_attributes, array(
                'offset'     => true,
                'stop-color' => true,
                'stop-opacity' => true,
            )),
            'text'     => array_merge( $global_attributes, array(
                'x'           => true,
                'y'           => true,
                'dx'          => true,
                'dy'          => true,
                'text-anchor' => true,
                'font-size'   => true,
                'font-family' => true,
                'font-weight' => true,
                'fill'        => true,
                'opacity'     => true,
                'transform'   => true,
            )),
            'tspan'    => array_merge( $global_attributes, array(
                'x'           => true,
                'y'           => true,
                'dx'          => true,
                'dy'          => true,
                'fill'        => true,
                'font-size'   => true,
                'font-family' => true,
                'font-weight' => true,
            )),
            'mask'     => array_merge( $global_attributes, array(
                'x'      => true,
                'y'      => true,
                'width'  => true,
                'height' => true,
                'maskUnits' => true,
                'maskContentUnits' => true,
            )),
            'title'    => $global_attributes,
            'desc'     => $global_attributes,
        );

        return apply_filters( 'easyel_allowed_html', $allowed_tags );
    }
}

add_filter( 'elementor/files/allow_unfiltered_files', '__return_false' );


if ( ! function_exists( 'easyel_premium_addon_active' ) ) {
    /**
     * Check if Easy Elements Pro is active.
     *
     * Cached per request and filterable for compatibility.
     *
     * @return bool
     */
    function easyel_premium_addon_active() {
        static $is_active = null;
        if ( null === $is_active ) {
            $detected  = defined( 'EASYELEMENTS_PRO_VER' ) || defined( 'EASYELEMENTS_PRO_FILE' );
            $is_active = (bool) apply_filters( 'easyel_premium_addon_active', $detected );
        }
        return $is_active;
    }
}

if ( ! function_exists( 'easyel_get_pro_extension_keys' ) ) {
    /**
     * Option keys for the Pro-only extensions.
     *
     * Deliberately free of any __() / translation call so it is safe to use
     * before the `init` action (e.g. inside option filters that run on
     * `plugins_loaded`). Mirrors every entry flagged `'is_pro' => true` in
     * easyel_get_extension_fields() — keep the two lists in sync when adding
     * or removing a Pro extension.
     *
     * @return string[]
     */
    function easyel_get_pro_extension_keys() {
        return [
            'enable_cursor',
            'enable_sticky_elements',
            'enable_cursor_hover_effect',
            'enable_cursor_move_effect',
            'enable_scroll_trigger',
            'enable_parallax_image',
            'enable_drawsvg',
            'enable_image_3d_effect',
            'enable_smooth_scroller',
            'enable_bounce_animation',
            'enable_visibility_control',
            'enable_dynamic_content',
            'enable_live_copy_paste',
            'enable_easy_custom_css',
            'enable_global_badge',
            'enable_megamenu_builder',
            'enable_popup_builder',
            'enable_quick_view',
            'enable_easyel_compare',
            'enable_easyel_wishlist',
        ];
    }
}

if ( ! function_exists( 'easyel_force_pro_options_off_when_inactive' ) ) {
    /**
     * Disable Pro-only options when the Pro add-on is inactive.
     *
     * Forces Pro settings to appear disabled without modifying saved values.
     */
    function easyel_force_pro_options_off_when_inactive() {
        if ( easyel_premium_addon_active() ) {
            return;
        }

        add_filter( 'option_easy_element_extensions', static function ( $value ) {
            if ( ! is_array( $value ) ) {
                return $value;
            }
            // Use the translation-free key list here: this filter runs on
            // `plugins_loaded` (before `init`), so it must not call any
            // translating helper such as easyel_get_extension_fields(), which
            // would trigger the "_load_textdomain_just_in_time" notice.
            foreach ( easyel_get_pro_extension_keys() as $key ) {
                if ( isset( $value[ $key ] ) ) {
                    $value[ $key ] = 0;
                }
            }
            return $value;
        } );

        if ( class_exists( '\\Easyel\\EasyElements\\Admin\\Admin_Settings' ) ) {
            $instance = \Easyel\EasyElements\Admin\Admin_Settings::get_instance();
            if ( method_exists( $instance, 'easyel_elements_get_available_widgets' ) ) {
                foreach ( (array) $instance->easyel_elements_get_available_widgets() as $widget_key => $widget ) {
                    if ( ! empty( $widget['is_pro'] ) ) {
                        add_filter( 'option_easy_element_widget_' . $widget_key, '__return_zero' );
                    }
                }
            }
        }
    }
    // Run before init_widgets (which fires on plugins_loaded default 10).
    add_action( 'plugins_loaded', 'easyel_force_pro_options_off_when_inactive', 5 );
}

if ( ! function_exists( 'easyel_get_pro_clean_version' ) ) {
    function easyel_get_pro_clean_version() {

        if ( ! defined( 'EASYELEMENTS_PRO_FILE' ) ) {
            return null;
        }

        if ( ! function_exists( 'get_file_data' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $data = get_file_data(
            EASYELEMENTS_PRO_FILE,
            [ 'Version' => 'Version' ]
        );

        return $data['Version'] ?? null;
    }
}

if ( ! function_exists( 'easyel_get_license_status' ) ) {
   
    function easyel_get_license_status() {
        return (string) apply_filters( 'easyel_license_status', 'invalid' );
    }
}

if ( ! function_exists( 'easyel_get_license_activate_url' ) ) {
   
    function easyel_get_license_activate_url() {
        return (string) apply_filters( 'easyel_license_activate_url', '' );
    }
}

if ( ! function_exists( 'easyel_enable_svg_upload' ) ) {
    function easyel_enable_svg_upload( $mimes ) {
        $mimes['svg']  = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
        return $mimes;
    }
    add_filter( 'upload_mimes', 'easyel_enable_svg_upload' );
}

if ( ! function_exists( 'easyel_fix_svg_filetype' ) ) {
    function easyel_fix_svg_filetype( $data, $file, $filename, $mimes ) {
        $ext = pathinfo( $filename, PATHINFO_EXTENSION );
        if ( strtolower( $ext ) === 'svg' || strtolower( $ext ) === 'svgz' ) {
            $data['ext']  = 'svg';
            $data['type'] = 'image/svg+xml';
        }
        return $data;
    }
    add_filter( 'wp_check_filetype_and_ext', 'easyel_fix_svg_filetype', 10, 4 );
}

if ( ! function_exists( 'easyel_handle_sideload_svg' ) ) {
    function easyel_handle_sideload_svg( $file ) {
        $ext = pathinfo( $file['name'], PATHINFO_EXTENSION );
        if ( strtolower( $ext ) === 'svg' || strtolower( $ext ) === 'svgz' ) {
            $file['type'] = 'image/svg+xml';
        }
        return $file;
    }

    add_filter( 'wp_handle_sideload_prefilter', 'easyel_handle_sideload_svg' );
}

if ( ! function_exists( 'easyel_get_cf7_forms' ) ) {
    /**
     * Get a list of all CF7 forms
     *
     * @return array
     */
    function easyel_get_cf7_forms() {
        $forms = get_posts( [
            'post_type'      => 'wpcf7_contact_form',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );

        if ( ! empty( $forms ) ) {
            return wp_list_pluck( $forms, 'post_title', 'ID' );
        }
        return [];
    }
}


// Domain Search Code
add_action('template_redirect', function(){
    // phpcs:ignore WordPress.Security.NonceVerification.Missing
	if (isset($_POST['easyel_domain_redirect'])) {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$domain   = isset($_POST['domain']) ? sanitize_text_field(wp_unslash($_POST['domain'])) : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $base_url = isset($_POST['base_url']) ? esc_url_raw( wp_unslash($_POST['base_url'] ) ) : '';

        if ( !empty($domain) && !empty($base_url) && filter_var($base_url, FILTER_VALIDATE_URL) ) {
            $redirect_url = esc_url_raw($base_url . urlencode($domain));
            wp_safe_redirect( $redirect_url );
            exit;
        }
	}
});

function easyel_get_extension_fields() {
    $fields = [
        'enable_cursor' => [
            'label'   => __('Magic Cursor', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/magic-cursor.svg',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://wpeasyelements.com/docs/magic-cursor/',
            'docs_url'    => 'https://wpeasyelements.com/docs/magic-cursor/',
        ],
        'enable_sticky_elements' => [
            'label'   => __('Sticky Elements', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/sticky-elements.svg',
            'is_pro'  => true,
            'group' => 'General Extensions',
            'demo_url'    => 'https://wpeasyelements.com/extensions/sticky-elements/',
            'docs_url'    => 'https://wpeasyelements.com/docs/sticky-elements/',
        ],
        'enable_cursor_hover_effect' => [
            'label'   => __('Cursor Hover Effect', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/cursor-hover-effect.svg',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://wpeasyelements.com/extensions/cursor-hover-effect/',
            'docs_url'    => 'https://wpeasyelements.com/docs/cursor-hover-effect/',
        ],
        'enable_cursor_move_effect' => [
            'label'   => __('Cursor Move Effect', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/cursor-move-effect.svg',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://wpeasyelements.com/extensions/cursor-move-effect/',
            'docs_url'    => 'https://wpeasyelements.com/docs/cursor-move-effect/',
            
        ],
        'enable_scroll_trigger' => [
            'label'   => __('ScrollTrigger', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/scrolltrigger.svg',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://wpeasyelements.com/extensions/scroll-trigger/',
            'docs_url'    => 'https://wpeasyelements.com/docs/scrolltrigger/',
            
        ],
        'enable_parallax_image' => [
            'label'   => __('Background Parallax', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/background-parallax.svg',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://wpeasyelements.com/extensions/background-parallax/',
            'docs_url'    => 'https://wpeasyelements.com/docs/background-parallax/',
        ],
        'enable_drawsvg' => [
            'label'   => __('DrawSVG', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/drawsvg.svg',
            'is_pro'  => true,
            'upcoming' => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => '#',
            'docs_url'    => '#',
        ],
        'enable_image_3d_effect' => [
            'label'   => __('Image 3D Effect', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/image-3d-effect.svg',
            'is_pro'  => true,
            'upcoming' => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => '#',
            'docs_url'    => '#',
        ],
        'enable_smooth_scroller' => [
            'label'   => __('Scroll Smoother', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/image-3d-effect.svg',
            'is_pro'  => true,
            'setting' => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://wpeasyelements.com/',
            'docs_url'    => 'https://wpeasyelements.com/docs/scroll-smoother/',
        ],
        'enable_bounce_animation' => [
            'label'   => __('Bounce Animation', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/image-3d-effect.svg',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://wpeasyelements.com/extensions/bounce-animation/',
            'docs_url'    => 'https://wpeasyelements.com/docs/bounce-animation/',
        ],        
        'enable_wrapper_link' => [
            'label'   => __('Wrapper Link', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/wrapper-link.svg',
            'is_pro'  => false,
            'group' => 'General Extensions',
            'demo_url'    => 'https://wpeasyelements.com/extensions/wrapper-link/',
            'docs_url'    => 'https://wpeasyelements.com/docs/wrapper-link/',
        ],
        'enable_post_duplicator' => [
            'label'   => __('Duplicator', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/duplicator.svg',
            'is_pro'  => false,
            'group' => 'General Extensions',
            'demo_url'    => 'https://wpeasyelements.com/docs/post-page-duplicate/',
            'docs_url'    => 'https://wpeasyelements.com/docs/post-page-duplicate/',
        ],
        'enable_postpage_export' => [
            'label'   => __('Easy Export', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/duplicator.svg',
            'is_pro'  => false,
            'group' => 'General Extensions',
            'demo_url'    => 'https://wpeasyelements.com/extensions/docs/easy-export/',
            'docs_url'    => 'https://wpeasyelements.com/docs/easy-export/',
        ],
        'enable_visibility_control' => [
            'label'   => __('Visibility Control', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/visibility-control.svg',
            'is_pro'  => true,
            'group' => 'General Extensions',
            'demo_url'    => 'https://wpeasyelements.com/extensions/visibility-control/',
            'docs_url'    => 'https://wpeasyelements.com/docs/visibility-control/',
        ],
        'enable_dynamic_content' => [
            'label'   => __('Dynamic Content', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/dynamic-content.svg',
            'is_pro'  => true,
            'group' => 'General Extensions',
            'demo_url'    => 'https://wpeasyelements.com/extensions/dynamic-content/',
            'docs_url' => 'https://wpeasyelements.com/docs/dynamic-content/',
        ],
        'enable_live_copy_paste' => [
            'label'   => __('Live Copy Paste', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/live-copy.svg',
            'is_pro'  => true,
            'group' => 'General Extensions',
            'demo_url'    => 'https://wpeasyelements.com/extensions/live-copy/',
            'docs_url'    => 'https://wpeasyelements.com/docs/live-copy/',
        ],
        'enable_easy_custom_css' => [
            'label'   => __('Custom CSS', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/easy-custom-css.svg',
            'is_pro'  => true,
            'group' => 'General Extensions',
            'demo_url'    => 'https://wpeasyelements.com/docs/custom-css/',
            'docs_url'    => 'https://wpeasyelements.com/docs/custom-css/',
        ],
        'enable_global_badge' => [
            'label'   => __('Global Badge', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/global-badge.svg',
            'is_pro'  => true,
            'group' => 'General Extensions',
            'demo_url'    => 'https://wpeasyelements.com/extensions/global-badge/',
            'docs_url'    => 'https://wpeasyelements.com/docs/global-badge/',
        ],
        'enable_megamenu_builder' => [
            'label'   => __('Megamenu Builder', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/megamenu.svg',
            'is_pro'  => true,
            'group' => 'General Extensions',
            'demo_url'    => 'https://wpeasyelements.com/megamenu-builder/',
            'docs_url'    => 'https://wpeasyelements.com/docs/megamenu-builder/',
        ],
        'enable_reading_progress_bar' => [
            'label'   => __('Reading Progress Bar', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/progressbar.svg',
            'is_pro'  => false,
            'setting' => true,
            'group' => 'General Extensions',
            'demo_url'    => 'https://wpeasyelements.com/reading-progress-bar/',
            'docs_url'    => 'https://wpeasyelements.com/docs/reading-progress-bar/',
        ],
        'enable_scroll_top' => [
            'label'   => __('Scroll Top', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/arrow-up.svg',
            'is_pro'  => false,
            'setting' => true,
            'group' => 'General Extensions',
            'demo_url'    => '',
            'docs_url'    => 'https://wpeasyelements.com/docs/scroll-to-top/',
        ],
        'enable_preloader' => [
            'label'    => __( 'Preloader', 'easy-elements' ),
            'icon'     => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/loader.svg',
            'is_pro'   => false,
            'setting'  => true,
            'group'    => 'General Extensions',
            'demo_url' => '',
            'docs_url' => 'https://wpeasyelements.com/docs/preloader/',
        ],
        'enable_popup_builder' => [
            'label'   => __('Popup Builder', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/popup-builder.svg',
            'is_pro'  => true,
            'setting' => true,
            'group' => 'General Extensions',
            'demo_url'    => 'https://wpeasyelements.com/extensions/popup-builder/',
            'docs_url'    => 'https://wpeasyelements.com/docs/popup-builder/',
        ],  
        'enable_quick_view' => [
            'label'   => __('Quick View', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/quickview.svg',
            'is_pro'  => true,
            'setting' => true,
            'group' => 'WooCommerce Extensions',
            'demo_url'    => 'https://wpeasyelements.com/shop/',
            'docs_url'    => 'https://wpeasyelements.com/docs/quick-view/',
        ],  
        'enable_easyel_compare' => [
            'label'   => __('Compare', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/compare.svg',
            'is_pro'  => true,
            'setting' => true,
            'group' => 'WooCommerce Extensions',
            'demo_url'    => 'https://wpeasyelements.com/shop/',
            'docs_url'    => 'https://wpeasyelements.com/docs/compare/',
        ],   
        'enable_easyel_wishlist' => [
            'label'   => __('Wishlist', 'easy-elements'),
            'icon'        => EASYELEMENTS_DIR_URL . 'includes/Admin/icons/extension/wishlist.svg',
            'is_pro'  => true,
            'setting' => true,
            'group' => 'WooCommerce Extensions',
            'demo_url'    => 'https://wpeasyelements.com/shop/',
            'docs_url'    => 'https://wpeasyelements.com/docs/wishlist/',
        ],        
    ];

    return apply_filters('easyel_extension_fields', $fields);
}

function easyel_sanitize_conditions_array( $conditions ) {
    if ( ! is_array( $conditions ) ) {
        return [];
    }

    $sanitized = [];
    foreach ( $conditions as $cond ) {
        if ( ! is_array( $cond ) ) continue;

        $sanitized[] = [
            'include' => isset($cond['include']) ? sanitize_text_field($cond['include']) : 'include',
            'main'    => isset($cond['main']) ? sanitize_text_field($cond['main']) : '',
            'sub'     => isset($cond['sub']) ? sanitize_text_field($cond['sub']) : '',
            'child_sub'     => isset($cond['child_sub']) ? sanitize_text_field($cond['child_sub']) : '',
        ];
    }

    return $sanitized;
}

/**
 * Safely decode JSON or return array as-is
 */
function easyel_safe_json_decode( $data ) {
    if ( is_string($data) ) {
        $decoded = json_decode($data, true);
        if ( json_last_error() === JSON_ERROR_NONE && is_array($decoded) ) {
            return $decoded;
        }
        return []; 
    } elseif ( is_array($data) ) {
        return $data; 
    } else {
        return []; 
    }
}

class Easyel_Widget_Injection {
    public static function init() {
        add_filter( 'elementor/controls/animations/additional_animations', [ __CLASS__, 'inject_easy_animations' ] );
    }

    public static function inject_easy_animations( $animations ) {
        $animations['Easy Animations'] = [
            'scaleIn' => __( 'Scale In', 'easy-elements' ),
        ];
        return $animations;
    }
}

// Initialize the class
Easyel_Widget_Injection::init();


/**
 * Check if a specific Easy Element extension setting is enabled.
 *
 * @param string $setting_key The key of the setting to check (e.g. 'enable_sticky_elements').
 * @return bool True if enabled, false otherwise.
 */
function easyel_element_is_enabled( $setting_key ) {
    if ( empty( $setting_key ) ) {
        return false;
    }

    $tab_slug = 'extensions';
    $settings = get_option( 'easy_element_' . $tab_slug, [] );

    // Sanitize and check
    $value = isset( $settings[ $setting_key ] ) ? (int) $settings[ $setting_key ] : 0;

    return ( $value === 1 );
}


function easyel_translate_hfe_template( $template_id ) {
    if ( function_exists( 'pll_get_post' ) ) {
        $translated = pll_get_post( $template_id );
        if ( $translated ) {
            $template_id = $translated;
        }
    }
    if ( has_filter( 'wpml_object_id' ) ) {

        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
        $translated = apply_filters( 'wpml_object_id', $template_id, 'ee-elementor-hf', true );
        if ( $translated ) {
            $template_id = $translated;
        }
    }
    return $template_id;
}

add_filter( 'easyel_get_settings_type_header', 'easyel_translate_hfe_template' );
add_filter( 'easyel_get_settings_type_footer', 'easyel_translate_hfe_template' );
add_filter( 'easyel_get_settings_type_easy_topbar', 'easyel_translate_hfe_template' );
add_filter( 'easyel_get_settings_type_easy_after__header', 'easyel_translate_hfe_template' );
add_filter( 'easyel_get_settings_type_before_footer', 'easyel_translate_hfe_template' );

if ( ! function_exists( 'easyel_get_valid_archive_post_types' ) ) {
    function easyel_get_valid_archive_post_types() {

        $post_types = get_post_types(
            [
                'public'            => true,
                'show_in_nav_menus' => true,
            ],
            'objects'
        );

        $valid = [];

        foreach ( $post_types as $post_type => $pt ) {

            if ( ! $pt->has_archive || ! get_post_type_archive_link( $post_type ) ) {
                continue;
            }

            if ( $post_type === 'page' ) {
                continue;
            }

            if (
                ! empty( $pt->_builtin ) === false &&
                str_starts_with( $post_type, 'elementor' )
            ) {
                continue;
            }

            if ( ! $pt->publicly_queryable ) {
                continue;
            }

            $valid[ $post_type ] = $pt;
        }

        return $valid;
    }
}

if ( ! function_exists( 'easyel_get_hierarchical_taxonomies_by_post_type' ) ) {
    function easyel_get_hierarchical_taxonomies_by_post_type() {

        $map = [];
        $post_types = easyel_get_valid_archive_post_types(); 

        foreach ($post_types as $post_type => $pt) {
            $taxonomies = get_object_taxonomies($post_type, 'objects');

            foreach ($taxonomies as $tax_slug => $tax) {
                if (!$tax->public) {
                    continue;
                }

                $map[$tax_slug] = [
                    'post_type' => $post_type,
                    'taxonomy'  => $tax_slug,
                ];
            }
        }

        return $map;
    }
}

if ( ! function_exists('easyel_theme_builder_child_options') ) {
    /**
     * Get global condition arrays: needs_child_sub & no_child_sub
     *
     * @return array
     */
    function easyel_theme_builder_child_options() {
        // Default needs_child_sub
        $needs_child_sub = [
            'author',
            'category',
            'child_of_category',
            'any_child_of_category',
            'in_category',
            'in_category_children',
            'post_tag',
            'in_post_tag',
            'child_of',
            'any_child_of',
            'product_cat',
            'product_tag',
            'product_brand',
            'post',
            'page',
            'page_by_author',
        ];

        // Default no_child_sub
        $no_child_sub = [
            "all",
            "front_page",
            "not_found404",
            "index",
            "search",
            "date",
            "post_archive",
            "all_product_archive",
            "product_search",
            "shop_page",
        ];

        // Get dynamic archive taxonomies
        $archive_taxonomies = function_exists('easyel_get_hierarchical_taxonomies_by_post_type') 
            ? easyel_get_hierarchical_taxonomies_by_post_type() 
            : [];

        if ( is_array($archive_taxonomies) || is_object($archive_taxonomies) ) {
            foreach ($archive_taxonomies as $tax_slug => $data) {
                $needs_child_sub[] = $tax_slug;
                $needs_child_sub[] = 'child_of_' . $tax_slug;
                $needs_child_sub[] = 'any_child_of_' . $tax_slug;

                $needs_child_sub[] = 'in_' . $tax_slug;
                $needs_child_sub[] = 'in_' . $tax_slug . '_children';
                $needs_child_sub[] = $data['post_type'] . '_by_author';

                if ( ! empty($data['post_type']) ) {
                    $no_child_sub[] = $data['post_type']."_archive";
                    $needs_child_sub[] = $data['post_type'];
                }
            }
        }

        return [
            'needs_child_sub' => $needs_child_sub,
            'no_child_sub' => $no_child_sub,
        ];
    }
}

if ( ! function_exists( 'easyel_get_prepared_post_id' ) ) {
    function easyel_get_prepared_post_id() {

        if ( is_singular( 'post' ) ) {
            return get_the_ID();
        }

        $cache_key = 'easyel_latest_post_id';
        $post_id   = wp_cache_get( $cache_key, 'easyel' );

        if ( ! $post_id || get_post_status( $post_id ) !== 'publish' ) {

            $latest_post = get_posts([
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => 1,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'fields'         => 'ids',
            ]);

            $post_id = !empty($latest_post) ? $latest_post[0] : 0;

            wp_cache_set( $cache_key, $post_id, 'easyel', 12 * HOUR_IN_SECONDS );
        }

        return $post_id;
    }
}

add_action('template_redirect', function() {
    ob_start(function ($html) {
        if ( isset($GLOBALS['easyel_force_sticky_header']) && $GLOBALS['easyel_force_sticky_header'] === true ) {
            return preg_replace(
                '/(class=["\'][^"\']*easy-site-header[^"\']*)/i',
                '$1 eel-sticky-header-on',
                $html
            );
        }
        return $html;
    });

    $GLOBALS['easyel_sticky_buffer_level'] = ob_get_level();
});

add_action('shutdown', function() {
    if ( ! isset( $GLOBALS['easyel_sticky_buffer_level'] ) ) {
        return;
    }

    $target = (int) $GLOBALS['easyel_sticky_buffer_level'];
    while ( ob_get_level() >= $target ) {
        if ( ! @ob_end_flush() ) {
            break;
        }
    }
    unset( $GLOBALS['easyel_sticky_buffer_level'] );
}, 0);