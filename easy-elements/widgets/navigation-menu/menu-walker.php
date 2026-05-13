<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Menu_Walker extends \Walker_Nav_Menu {

	/**
	 * Start element
	 */
	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$args   = (object) $args;

		$class_names = '';
		$value       = '';
		$rel_xfn     = '';
		$rel_blank   = '';

		$classes = ! empty( $item->classes ) && is_array( $item->classes ) ? $item->classes : [];

		// Mega menu (Elementor template) ke o has_children hisebe treat kori,
		// jate dropdown icon, submenu class ar aria attributes thik moto add hoy.
		$has_megamenu_template = false;
		if ( function_exists( 'easy_element_is_enabled' ) && easy_element_is_enabled( 'enable_megamenu_builder' ) ) {
			$mm_template_id = get_post_meta( $item->ID, 'easyel__menu_item_elementor_template', true );
			if ( ! empty( $mm_template_id ) ) $has_megamenu_template = true;
		}
		$has_children_or_megamenu = $args->has_children || $has_megamenu_template;

		$submenu = $has_children_or_megamenu ? ' eel-has-submenu' : '';

		if ( 0 === $depth ) array_push( $classes, 'parent' );
		if ( $has_children_or_megamenu ) array_push( $classes, 'menu-item-has-children' );

		// last-item logic
		$is_last_item = false;
		$menu_last_item_setting = isset( $args->menu_last_item ) ? $args->menu_last_item : '';
		if ( $depth === 0 ) {
			$menu_items = wp_get_nav_menu_items( $args->menu );
			if ( $menu_items && is_array( $menu_items ) ) {
				$top_level_items = array_filter( $menu_items, fn($menu_item) => $menu_item->menu_item_parent == 0 );
				$last_top_level_item = end( $top_level_items );
				if ( $last_top_level_item && $last_top_level_item->ID === $item->ID ) $is_last_item = true;
			}
		}
		if ( $is_last_item && $menu_last_item_setting === 'cta' ) $classes[] = 'last-item';

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$filtered_classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );
		if ( ! is_array( $filtered_classes ) ) $filtered_classes = array_filter( $classes );
		
		
		$class_names = ' class="' . esc_attr( join( ' ', $filtered_classes ) ) . $submenu . ' eel-creative-menu"';

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$value = apply_filters( 'nav_menu_li_values', $value );

		$output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';

		// anchor attributes
		if ( isset( $item->target ) && '_blank' === $item->target && isset( $item->xfn ) && false === strpos( $item->xfn, 'noopener' ) ) $rel_xfn = ' noopener';
		if ( isset( $item->target ) && '_blank' === $item->target && isset( $item->xfn ) && empty( $item->xfn ) ) $rel_blank = 'rel="noopener"';

		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . $rel_xfn . '"' : '' . $rel_blank;
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_url( $item->url ) . '"' : '';
		if ( $has_children_or_megamenu ) $attributes .= ' aria-haspopup="true" aria-expanded="false"';

		$atts = apply_filters( 'Easyel_Menu_Walker_nav_menu_attrs', $attributes );

		$item_output  = $args->before;
		$item_output .= '<a' . $atts;
		if ( 0 === $depth ) {
			$anchor_class = 'eel-menu-item';
			if ( $is_last_item && $menu_last_item_setting === 'cta' ) $anchor_class .= ' elementor-button';
			$item_output .= ' class="' . $anchor_class . '"';
		} else {
			$item_output .= in_array( 'current-menu-item', $classes ) ? ' class="eel-sub-menu-item eel-sub-menu-item-active"' : ' class="eel-sub-menu-item"';
		}
		$item_output .= '>';
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		if ( function_exists( 'easy_element_is_enabled' ) &&  easy_element_is_enabled( 'enable_megamenu_builder' ) ) {
			if ( class_exists( '\Elementor\Plugin' ) && ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
				$template_id = get_post_meta( $item->ID, 'easyel__menu_item_elementor_template', true );
				if ( ! empty( $template_id ) ) {
					if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
						$css_file = new \Elementor\Core\Files\CSS\Post( $template_id );
						$css_file->enqueue();
					}
					$template_content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id );
					if ( $template_content ) {
						$item_output .= '<ul class="easyel--elementor-template-mega-menu easyel-mega--current sub-menu">' . $template_content . '</ul>';
					}
				}
			}
		}

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Start level (submenu)
	 */
	public function start_lvl( &$output, $depth = 0, $args = [] ) {
		$indent = str_repeat( "\t", $depth );
		$submenu_class = 'sub-menu';
		if ( $depth > 0 ) $submenu_class .= ' sub-sub-menu';
		$output .= "\n" . $indent . '<ul class="' . $submenu_class . '">' . "\n";
	}

	/**
	 * End level (submenu)
	 */
	public function end_lvl( &$output, $depth = 0, $args = [] ) {
		$indent = str_repeat( "\t", $depth );
		$output .= $indent . "</ul>\n";
	}

	/**
	 * Display element (children check)
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		$id_field = $this->db_fields['id'];
		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}
		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}
