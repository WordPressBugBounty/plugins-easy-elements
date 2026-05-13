<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Easyel_vertical_Menu_Nav_Walker extends \Walker_Nav_Menu {
    
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat("\t", $depth);

        $classes = array( 'sub-menu' );

        $submenu_type = '';
        $menu_layout  = '';

        if ( is_object( $args ) ) {
            $submenu_type = isset( $args->submenu_type ) ? $args->submenu_type : '';
            $menu_layout  = isset( $args->menu_layout ) ? $args->menu_layout : '';
        } elseif ( is_array( $args ) ) {
            $submenu_type = isset( $args['submenu_type'] ) ? $args['submenu_type'] : '';
            $menu_layout  = isset( $args['menu_layout'] ) ? $args['menu_layout'] : '';
        }

        if ( ! empty( $submenu_type ) ) {
            $classes[] = esc_attr( $submenu_type );
        }
        
        if ( $menu_layout === 'vertical' ) {
            $classes[] = 'eel-submenu-vertical';
        }

        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
        $class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
        $output .= "\n$indent<ul class=\"$class_names\">\n";
    }

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

        $indent = ( $depth > 0 ) ? str_repeat("\t", $depth) : '';

        // Depth classes
        $depth_classes = array(
            ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
            ( $depth >= 2 ? 'sub-sub-menu-item' : '' ),
            ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
            'menu-item-depth-' . $depth
        );

        $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

        // Menu item classes
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
        $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

        $output .= $indent . '<li id="nav-menu-item-'. $item->ID .'" class="'. $depth_class_names .' '. $class_names .'">';

        // Link attributes
        $attributes  = ! empty( $item->attr_title ) ? ' title="'. esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="'. esc_attr($item->target) .'"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="'. esc_attr($item->xfn) .'"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="'. esc_attr($item->url) .'"' : '';
        $attributes .= ' class="menu-link '. ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) .'"';

        // Dropdown icon
        $dropdown_icon = '';
        if ( !empty($classes) && in_array('menu-item-has-children', $classes) ) {
            $icon = isset($args->submenu_parent_icon) ? $args->submenu_parent_icon : '';
            $dropdown_icon = '<span class="submenu-parent-icon">'. $icon .'</span>';
        }

        // Active vertical icon
        $vertical_icon = '';
        if ( isset($args->menu_layout) && $args->menu_layout === 'vertical' ) {
            if ( !empty($classes) && in_array('current-menu-item', $classes) ) {
                $active_icon = isset($args->vertical_menu_active_icon) ? $args->vertical_menu_active_icon : '';
                $vertical_icon = '<span class="vertical_menu_active_icon">'. $active_icon .'</span>';
            }
        }

        // Menu description
        $menu_description = '';
        if ( !empty($item->description) ) {
            $menu_description = '<span class="eel-menu-desc">'. esc_html($item->description) .'</span>';
        }

        // Menu meta settings
        $settings = get_post_meta( $item->ID, 'easyel_mega_menu_settings', true );

        // Badge
        $menu_item_badge = '';
        if ( isset($settings['content']['badge']) && !empty($settings['content']['badge']) ) {
            $menu_item_badge = '<span class="eel-menu-badge">'. esc_html($settings['content']['badge']) .'</span>';
        }

        // Icon
        $menu_item_icon = '';
        if ( isset($settings['content']['menu_icon']) && !empty($settings['content']['menu_icon']) ) {
            $menu_item_icon = '<i class="menu-icon '. esc_attr($settings['content']['menu_icon']) .'"></i>';
        }

        // Custom arrow icon
        $vertical_menu_custom_icon = isset($args->menu_arrow_vertical_custom) ? $args->menu_arrow_vertical_custom : '';

        // Before/after item icons (from widget settings)
        $menu_before_icon = isset($args->menu_before_icon) ? $args->menu_before_icon : '';
        $menu_after_icon  = isset($args->menu_after_icon) ? $args->menu_after_icon : '';

        // Output
        $item_output = sprintf(
            '%1$s<a%2$s>%3$s%4$s%5$s%6$s</a>',
            $args->before,
            $attributes,
            $args->link_before,
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
            apply_filters(
                // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
                'the_title',
                $menu_item_badge .
                '<div class="eel-menu-text">
                    <div class="eel-menu-text-inner">' .
                        $vertical_menu_custom_icon .
                        $menu_item_icon .
                        $menu_before_icon .
                        '<span>'. esc_html($item->title) .'</span>' .
                        $menu_description .
                    '</div>' .
                    $menu_after_icon .
                    $dropdown_icon .
                    $vertical_icon .
                '</div>',
                $item->ID
            ),
            $args->link_after,
            $args->after
        );

        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}