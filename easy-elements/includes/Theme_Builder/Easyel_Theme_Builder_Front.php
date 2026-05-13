<?php
namespace Easyel\EasyElements\Theme_Builder;
defined('ABSPATH') || exit;

class Easyel_Theme_Builder_Front {

    const CPT = 'easy_theme_builder';

    public static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->init_hooks();
    }

    public function init_hooks() {
        add_filter('template_include', [$this, 'easyel_load_templates'], 999 );
        add_action('easyel_builder_archive_content', [$this, 'easyel_archive_content_elementor'], 999 );
        add_action('easyel_builder_singular_content', [$this, 'easyel_singular_content_elementor'], 999 );
        add_filter( 'body_class', [ $this, 'easyel_body_class' ] );
        add_action('wp_enqueue_scripts', [$this, 'enqueue_template_styles'], 10);

    }

    public function enqueue_template_styles() {
        if ( ! class_exists( '\Elementor\Plugin' ) ) return;

        $template_id = $this->easyel_match_template();
        if ( $template_id ) {
           
            \Elementor\Plugin::instance()->frontend->enqueue_styles();
            
            if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
                $css_file = new \Elementor\Core\Files\CSS\Post( $template_id );
                $css_file->enqueue();
            }
        }
    }

    public function easyel_load_templates( $template ) {
        if (!class_exists('\Elementor\Plugin')) return $template;
        if ( is_embed() ) return $template;

        
        $template_id = $this->easyel_match_template();

        if ( $template_id ) {
            $page_template = get_page_template_slug( $template_id );

            if ( is_404() ) {
                return EASYELEMENTS_DIR_PATH . 'includes/Theme_Builder/elementor-canvas/archive.php';
            }

            if ( is_singular() || is_front_page() || is_singular('easy_theme_builder') ) {

                if ( 'elementor_header_footer' === $page_template ) {
                    return EASYELEMENTS_DIR_PATH.'includes/Theme_Builder/elementor-canvas/single-fullwidth.php';
                }
                elseif ( 'elementor_canvas' === $page_template ) {
                    return EASYELEMENTS_DIR_PATH . 'includes/Theme_Builder/elementor-canvas/single-canvas.php';
                } else {
                   
                    return EASYELEMENTS_DIR_PATH . 'includes/Theme_Builder/elementor-canvas/single.php';
                }
            }
            elseif ( is_archive() || is_search() || is_home() || is_date() || is_author() ) {
                if ( 'elementor_header_footer' === $page_template ) {
                    return EASYELEMENTS_DIR_PATH.'includes/Theme_Builder/elementor-canvas/archive-fullwidth.php';
                }
                elseif ( 'elementor_canvas' === $page_template ) {
                    return EASYELEMENTS_DIR_PATH . 'includes/Theme_Builder/elementor-canvas/archive-canvas.php';
                } else {
                    return EASYELEMENTS_DIR_PATH . 'includes/Theme_Builder/elementor-canvas/archive.php';
                }
            }
        }

        return $template;
    }

    /**
     * Template Condition Match System
     */

    public function easyel_match_template() {
        $templates = get_posts([
            'post_type'   => self::CPT,
            'post_status' => 'publish',
            'numberposts' => -1,
        ]);

        foreach ( $templates as $tmpl ) {
            $template_type = get_post_meta( $tmpl->ID, 'easyel_template_type', true );
            $conditions    = get_post_meta($tmpl->ID, 'easyel_conditions', true );

            // safe decode
            $conditions = easyel_safe_json_decode($conditions);

            foreach ( $conditions as $key => $cond ) {
                if ( ! is_array($cond) ) {
                    $conditions[$key] = [
                        'include' => 'include',
                        'main'    => '',
                        'sub'     => '',
                        'child_sub'     => '',
                    ];
                } else {
                    $conditions[$key]['include'] = $cond['include'] ?? 'include';
                    $conditions[$key]['main']    = $cond['main'] ?? '';
                    $conditions[$key]['sub']     = $cond['sub'] ?? '';
                    $conditions[$key]['child_sub']     = $cond['child_sub'] ?? '';
                }
            }

            if ( $this->check_conditions( $template_type, $conditions ) ) {
                return $tmpl->ID; 
            }
        }

        return false;
    }

    public function check_conditions( $type, $conditions ) {


        $pro_version = easyel_get_pro_clean_version();

        $pre = apply_filters(
            'easyel/pre_check_conditions',
            null,
            $type,
            $conditions
        );

        if ( $pre !== null ) {
            return (bool) $pre;
        }

        if (
            $pro_version &&
            version_compare( $pro_version, '1.0.8', '>=' )
        ) {

            if ( did_action( 'plugins_loaded' ) && class_exists( '\EasyElements_Elementor\Pro\ThemeBuilder\ThemeBuilderPro' ) ) {

                $instance = \EasyElements_Elementor\Pro\ThemeBuilder\ThemeBuilderPro::get_instance();

                if ( $instance && method_exists( $instance, 'check_conditions_pro' ) ) {
                   return $instance->check_conditions_pro( $type, $conditions );
                }
            }
                            
        } else {
            if ( did_action( 'plugins_loaded' ) && class_exists( '\EasyEL_Free_Pro_Unlock' ) ) {

                $instance = \EasyEL_Free_Pro_Unlock::instance();

                if ( $instance && method_exists( $instance, 'check_conditions_pro' ) ) {
                    
                    return $instance->check_conditions_pro( $type, $conditions );
                }
            }
        }

        foreach ( $conditions as $cond ) {
            $main = $cond['main'] ?? '';
            $sub  = $cond['sub'] ?? $main;

            if ( ($cond['include'] ?? 'include') !== 'include' ) continue;

            // Only allow "all archive" and "all singular"
            if ( ( $type === 'archive' && $sub === 'index' ) ) {

                if ( class_exists('WooCommerce') && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) ) {
                    return false;
                } else {
                    if ( is_home() || is_archive() || is_search() || is_date() || is_author() ) {
                        return true;
                    }
                }
            }

            if ( ( $type === 'single' && $sub === 'all' ) ) {
                if ( class_exists('WooCommerce') && (
                    is_singular('product') ||
                    is_cart() ||
                    is_checkout() ||
                    is_account_page()
                ) ) {
                    return false;
                } {
                    if ( is_singular() || is_front_page() || is_404() ) {
                        return true;
                    }
                }
            }

            if ( $type === 'single' && $sub === 'post' ) {
                if ( is_singular('post') ) {
                    return true;
                }
            }
        }

        return false;
    }

    public function get_builder_content_for_display( $post_id ) {
        if ( empty( $post_id ) || ! get_post_status( $post_id ) ) return ''; 
        if ( ! did_action('elementor/loaded') || ! class_exists('Elementor\Plugin') ) return ''; 

        $elementor = \Elementor\Plugin::instance();
        if ( 'elementor_library' !== get_post_type( $post_id ) && self::CPT !== get_post_type($post_id) ) {
            return '';
        }

        return $elementor->frontend->get_builder_content_for_display( $post_id, true );
    }

    public function easyel_archive_content_elementor( $query = null ) {
        $template_id = $this->easyel_match_template();
        if ( $template_id ) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe because output is intended HTML.
            echo $this->get_builder_content_for_display( $template_id ); 
        } else {
            the_content();
        }
    }

    public function easyel_get_template_content_by_id( $template_id ) {
        $template_post = get_post( $template_id );
        
        // Check if the post exists and its status is 'publish'
        if ( $template_post && $template_post->post_status === 'publish' ) {
            return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id );
        } else {
            return esc_html__( 'Template not published or does not exist', 'easy-elements');
        }
    }

    public function easyel_singular_content_elementor( $post_id = null ) {
       
        $template_id = $this->easyel_match_template();

        if( !empty( $template_id ) ){
            echo $this->easyel_get_template_content_by_id( $template_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }else{
            the_content();
        }

    }

    public function easyel_body_class( $classes ) {
       
        if ( ! class_exists('\Elementor\Plugin') ) return $classes;

        $template_id = $this->easyel_match_template();

        if ( $template_id ) {
            $template_type = get_post_meta( $template_id, 'easyel_template_type', true );

            if ( $template_type === 'archive' && ( is_archive() || is_search() || is_home() || is_date() || is_author() ) ) {
                $classes[] = 'elementor-archive-template';
            }

            if ( $template_type === 'single' && ( is_singular() || is_front_page() || is_404() ) ) {
                $classes[] = 'elementor-single-template';
            }
        }

        return $classes;
    }

}