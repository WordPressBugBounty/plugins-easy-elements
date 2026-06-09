<?php
/**
 * Mini Cart — Free hooks
 *
 * The Free version's mini cart only renders a cart icon plus an optional count
 * badge. The single thing that needs to stay reactive is that count badge:
 * when WooCommerce fires its AJAX add-to-cart response, it returns a list of
 * "fragments" (selector → replacement HTML) that the WC front-end JS then
 * swaps into the page. This filter adds our count badge to that list so it
 * updates automatically without us shipping any custom JS.
 *
 * @package Easy_Elements
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'easyel_mini_cart_add_count_fragment' );

if ( ! function_exists( 'easyel_mini_cart_add_count_fragment' ) ) {
	/**
	 * Add the cart count badge to the WooCommerce fragment list.
	 *
	 * @param array $fragments Existing fragments.
	 * @return array Modified fragments.
	 */
	function easyel_mini_cart_add_count_fragment( $fragments ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- "eel_" is the Easy Elements plugin prefix.
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return $fragments;
		}

		$fragments['span.eel-cart-count'] = '<span class="eel-cart-count">' . esc_html( WC()->cart->get_cart_contents_count() ) . '</span>';

		return $fragments;
	}
}
