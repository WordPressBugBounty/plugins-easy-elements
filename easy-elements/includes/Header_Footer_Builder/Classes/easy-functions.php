<?php
use Easyel\EasyElements\Header_Footer_Builder\Classes\Easy_Header_Footer_Elementor;
/**
 * Easy Elements Header Footer Builder Functions
 *
 * Custom helper functions for rendering dynamic header and footer templates.
 * 
 * @package Easy Elements
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function ee_easy_header_enabled() {
	$header_id = Easy_Header_Footer_Elementor::get_settings( 'type_header', '' );
	$status    = false;

	if ( '' !== $header_id ) {
		$status = true;
	}

	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	return apply_filters( 'ee_easy_header_enabled', $status );
}

/**
 * Check if a footer layout is active.
 *
 * @since 1.0.0
 * @return bool True if footer is enabled, false otherwise.
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function ee_easy_footer_enabled() {
	$footer_id = Easy_Header_Footer_Elementor::get_settings( 'type_footer', '' );
	$status    = false;

	if ( '' !== $footer_id ) {
		$status = true;
	}

	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	return apply_filters( 'ee_easy_footer_enabled', $status );
}

/**
 * Get the active header template ID.
 *
 * @since 1.0.0
 * @return string|false Header ID if set, otherwise false.
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function get_ee_easy_header_id() {
	$header_id = Easy_Header_Footer_Elementor::get_settings( 'type_header', '' );

	if ( '' === $header_id ) {
		$header_id = false;
	}

	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	return apply_filters( 'get_ee_easy_header_id', $header_id );
}

/**
 * Get the active footer template ID.
 *
 * @since 1.0.0
 * @return string|false Footer ID if set, otherwise false.
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function get_ee_easy_footer_id() {
	$footer_id = Easy_Header_Footer_Elementor::get_settings( 'type_footer', '' );

	if ( '' === $footer_id ) {
		$footer_id = false;
	}
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	return apply_filters( 'get_ee_easy_footer_id', $footer_id );
}

/**
 * Render the active header layout.
 *
 * @since 1.0.0
 * @return void
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function ee_hfe_render_header() {

	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	if ( false == apply_filters( 'enable_ee_hfe_render_header', true ) ) {
		return;
	}
	?>
	<header class="easy-site-header">
	<?php
		Easy_Header_Footer_Elementor::get_header_content();
	?>
	</header>
	<?php

	if ( easyel_after_header_enabled() ) {
		easyel_render_after_header();
	}
}

/**
 * Render the active footer layout.
 *
 * @since 1.0.0
 * @return void
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function ee_hfe_render_footer() {

	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	if ( false == apply_filters( 'enable_ee_hfe_render_footer', true ) ) {
		return;
	}

	?>
		<footer id="colophon">
			<?php Easy_Header_Footer_Elementor::get_footer_content(); ?>
		</footer>
	<?php
}

/**
 * Get the active before-header template ID.
 *
 * @since 1.0.0
 * @return string|false Before-header ID if set, otherwise false.
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function hfe_get_before_header_id() {

	$before_header_id = Easy_Header_Footer_Elementor::get_settings( 'type_before_header', '' );

	if ( '' === $before_header_id ) {
		$before_header_id = false;
	}
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	return apply_filters( 'get_hfe_before_header_id', $before_header_id );
}

/**
 * Get the active before-footer template ID.
 *
 * @since 1.0.0
 * @return string|false Before-footer ID if set, otherwise false.
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function ee_hfe_get_before_footer_id() {

	$before_footer_id = Easy_Header_Footer_Elementor::get_settings( 'type_before_footer', '' );

	if ( '' === $before_footer_id ) {
		$before_footer_id = false;
	}
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	return apply_filters( 'get_hfe_before_footer_id', $before_footer_id );
}

/**
 * Check if the before-header layout is enabled.
 *
 * @since 1.0.0
 * @return bool True if before-header is enabled, false otherwise.
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function hfe_is_before_header_enabled() {

	$before_header_id = Easy_Header_Footer_Elementor::get_settings( 'type_before_header', '' );
	$status           = false;

	if ( '' !== $before_header_id ) {
		$status = true;
	}
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	return apply_filters( 'hfe_before_header_enabled', $status );
}

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function ee_hfe_is_before_footer_enabled() {

	$before_footer_id = Easy_Header_Footer_Elementor::get_settings( 'type_before_footer', '' );
	$status           = false;

	if ( '' !== $before_footer_id ) {
		$status = true;
	}
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	return apply_filters( 'hfe_before_footer_enabled', $status );
}

/**
 * Render the before-header layout.
 *
 * @since 1.0.0
 * @return void
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function ee_render_before_header() {
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	if ( false == apply_filters( 'enable_hfe_render_before_header', true ) ) {
		return;
	}

	?>
		<div class="hfe-before-header-wrap">
			<?php Easy_Header_Footer_Elementor::get_before_header_content(); ?>
		</div>
	<?php

}

/**
 * Render the before-footer layout.
 *
 * @since 1.0.0
 * @return void
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function ee_hfe_render_before_footer() {
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	if ( false == apply_filters( 'enable_ee_hfe_render_before_footer', true ) ) {
		return;
	}

	?>
		<div class="hfe-before-footer-wrap">
			<?php Easy_Header_Footer_Elementor::get_before_footer_content(); ?>
		</div>
	<?php
}


/**
 * Get the active before-header template ID.
 *
 * @since 1.0.0
 * @return string|false Before-header ID if set, otherwise false.
 */
function easyel_get_after_header_id() {

	$after_header_id = Easy_Header_Footer_Elementor::get_settings( 'type_after_header', '' );

	if ( '' === $after_header_id ) {
		$after_header_id = false;
	}
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	return apply_filters( 'get_hfe_after_header_id', $after_header_id );
}


function easyel_after_header_enabled() {

	$after_header_id = Easy_Header_Footer_Elementor::get_settings( 'type_after_header', '' );
	$status           = false;

	if ( '' !== $after_header_id ) {
		$status = true;
	}
	return apply_filters( 'easyel_after_header_enabled', $status );
}


function easyel_render_after_header() {
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	if ( false === apply_filters( 'enable_easyel_render_after_header', true ) ) {
		return;
	}

	?>
		<div class="easyel-after-header-wrap">
			<?php Easy_Header_Footer_Elementor::get_after_header_content(); ?>
		</div>
	<?php
}