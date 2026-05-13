<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define SVG allowed HTML tags for wp_kses
 *
 * @return array Array of allowed tags and attributes for SVG
 */
if (!function_exists('easyel_helper_svg_allowed_html')) {
    function easyel_helper_svg_allowed_html() {
        return array(
            'svg' => array(
                'xmlns' => true,
                'fill' => true,
                'viewbox' => true,
                'role' => true,
                'aria-hidden' => true,
                'focusable' => true,
                'class' => true,
                'width' => true,
                'height' => true,
            ),
            'path' => array(
                'd' => true,
                'fill' => true,
            ),
            'polygon' => array(
                'fill' => true,
                'points' => true,
            ),
            'circle' => array(
                'cx' => true,
                'cy' => true,
                'r' => true,
                'fill' => true,
            ),
        );
    }
}

if (!function_exists('easyel_get_easyel_breadcrumb')) {
    function easyel_get_easyel_breadcrumb(
        $custom_title = '',
        $custom_path = '',
        $custom_separator = '',
        $custom_home_title = '',
        $search_custom_title = '',
        $search_result_title = '',
        $show_category_path = true,
        $home_icon_url = '',
        $home_icon_picker = ''
    ) {
        global $post;
        $post = get_queried_object();
        $object_id = get_queried_object_id();
        $home_title = !empty($custom_home_title) ? $custom_home_title : 'Home';

        // Separator
        $separator = !empty($custom_separator) ? $custom_separator : '<span class="breadcrumb-separator">/</span>';

        // Home icon
        $home_icon = !empty($home_icon_picker) ? $home_icon_picker : '';
        $home_link = '<a href="' . esc_url(home_url('/')) . '">' . $home_icon . (!empty($home_icon) ? ' ' : '') . '<span class="breadcrumb-home-text">' . esc_html($home_title) . '</span></a>';
        $output = $home_link;

        // Single Post
        if (is_single()) {
            $post_type = get_post_type();

            if ($post_type != 'post') {
                // Custom Post Type
                $post_type_object = get_post_type_object($post_type);
                if ($post_type_object && $post_type_object->has_archive) {
                    $output .= $separator . '<a href="' . esc_url(get_post_type_archive_link($post_type)) . '">' . esc_html($post_type_object->labels->name) . '</a>';
                }
                if ($show_category_path) {
                    $taxonomies = get_object_taxonomies($post_type, 'objects');
                    foreach ($taxonomies as $taxonomy) {
                        if ($taxonomy->hierarchical) {
                            $terms = get_the_terms($object_id, $taxonomy->name);
                            if ($terms && !is_wp_error($terms)) {
                                $main_term = $terms[0];
                                if ($main_term->parent != 0) {
                                    $ancestors = get_ancestors($main_term->term_id, $taxonomy->name);
                                    $ancestors = array_reverse($ancestors);
                                    foreach ($ancestors as $ancestor) {
                                        $ancestor_term = get_term($ancestor, $taxonomy->name);
                                        $output .= $separator . '<a href="' . esc_url(get_term_link($ancestor_term)) . '">' . esc_html($ancestor_term->name) . '</a>';
                                    }
                                }
                                $output .= $separator . '<a href="' . esc_url(get_term_link($main_term)) . '">' . esc_html($main_term->name) . '</a>';
                            }
                        }
                    }
                }
            }

            // Categories for normal posts
            if ($post_type == 'post' && $show_category_path) {
                $categories = get_the_category($object_id);
                if (!empty($categories) && !is_wp_error($categories)) {
                    $main_cat = $categories[0];

                    // Include parent categories
                    $parent_cats = get_ancestors($main_cat->term_id, 'category');
                    if (!empty($parent_cats)) {
                        $parent_cats = array_reverse($parent_cats);
                        foreach ($parent_cats as $parent_id) {
                            $parent_term = get_term($parent_id, 'category');
                            if ($parent_term && !is_wp_error($parent_term)) {
                                $output .= $separator . '<a href="' . esc_url(get_term_link($parent_term)) . '">' . esc_html($parent_term->name) . '</a>';
                            }
                        }
                    }

                    // Main category
                    $output .= $separator . '<a href="' . esc_url(get_term_link($main_cat)) . '">' . esc_html($main_cat->name) . '</a>';
                }
            }

            // Post title
            $output .= $separator . '<span class="breadcrumb-text">' . esc_html(get_the_title()) . '</span>';
        }

        // Page
        elseif (is_page()) {
            if ($post->post_parent) {
                $ancestors = array_reverse(get_post_ancestors($post->ID));
                foreach ($ancestors as $ancestor) {
                    $output .= $separator . '<a href="' . esc_url(get_permalink($ancestor)) . '">' . esc_html(get_the_title($ancestor)) . '</a>';
                }
            }
            $output .= $separator . '<span class="breadcrumb-text">' . esc_html(get_the_title()) . '</span>';
        }

        // Category / Tag / Taxonomy
        elseif (is_category() || is_tag() || is_tax()) {
            $term = get_queried_object();
            if ($term && !is_wp_error($term)) {
                if ($term->parent != 0) {
                    $ancestors = array_reverse(get_ancestors($term->term_id, $term->taxonomy));
                    foreach ($ancestors as $ancestor) {
                        $ancestor_term = get_term($ancestor, $term->taxonomy);
                        $output .= $separator . '<a href="' . esc_url(get_term_link($ancestor_term)) . '">' . esc_html($ancestor_term->name) . '</a>';
                    }
                }
                $output .= $separator . '<span class="breadcrumb-text">' . esc_html(single_term_title('', false)) . '</span>';
            }
        }

        // Post type archive
        elseif (is_post_type_archive()) {
            $output .= $separator . '<span class="breadcrumb-text">' . esc_html(post_type_archive_title('', false)) . '</span>';
        }

        // Blog page
        elseif (is_home() && !is_front_page()) {
            $blog_title = get_the_title(get_option('page_for_posts'));
            $output .= $separator . '<span class="breadcrumb-text">' . esc_html($blog_title) . '</span>';
        }

        // Search
        elseif (is_search()) {
            $output .= $separator . '<span class="breadcrumb-text">' . esc_html__('Search Results for:', 'easy-elements') . ' ' . esc_html(get_search_query()) . '</span>';
        }

        // 404
        elseif (is_404()) {
            $output .= $separator . '<span class="breadcrumb-text">' . esc_html__('404 Not Found', 'easy-elements') . '</span>';
        }

        // Allowed tags
        $allowed_tags = array_merge(wp_kses_allowed_html('post'), easyel_helper_svg_allowed_html());

        // Elementor-friendly: return instead of echo
        return '<div class="eel-breadcrumb"><div class="breadcrumb-path">' . wp_kses($output, $allowed_tags) . '</div></div>';
    }
}
