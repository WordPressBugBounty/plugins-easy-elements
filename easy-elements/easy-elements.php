<?php
/**
 * Plugin Name: Easy Elements
 * Plugin URI:  https://wpeasyelements.com/
 * Description: Provides a set of custom Elementor widgets, shortcodes, and enhancements.
 * Version:     1.4.4
 * Author:      Themewant
 * Author URI:  https://wpeasyelements.com/
 * Text Domain: easy-elements
 * Domain Path: /languages
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Prevent loading the plugin twice.
if ( defined( 'EASYELEMENTS_VER' ) ) {
	return;
}

define( 'EASY_ELEMENTS_DEV', false );
if ( defined( 'EASY_ELEMENTS_DEV' ) && true == EASY_ELEMENTS_DEV ) {
	define('EASYELEMENTS_VER', '1.4.4' . time() );
} else {
	define( 'EASYELEMENTS_VER', '1.4.4' );
}

// Define constants
define( 'EASYELEMENTS_FILE', __FILE__ );
define( 'EASYELEMENTS_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'EASYELEMENTS_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'EASYELEMENTS_ASSETS_URL', EASYELEMENTS_DIR_URL . 'assets/' );
define( 'EASYELEMENTS_URL', plugins_url( '/', __FILE__ ) );
define( 'EASYELEMENTS_PATH', plugin_basename( EASYELEMENTS_FILE ) );
define( 'EASYELEMENTS_DOMAIN', trailingslashit( 'https://wpeasyelements.com/' ) );
define( 'EASYELEMENTS_URL_ADMIN', plugin_dir_url( __FILE__ ) );
define( 'EASYELEMENTS_ASSETS_ADMIN', trailingslashit( EASYELEMENTS_URL_ADMIN ) );

if ( ! defined( 'EASY_EXTENSION_BADGE' ) ) {
	define( 'EASY_EXTENSION_BADGE', '<span class="easy-extension-badge"></span>' );
}

require_once __DIR__ . '/vendor/autoload.php';

// Mini Cart AJAX handlers — load at the earliest possible point so the
// wp_ajax_* actions are registered before admin-ajax.php runs do_action().
// On WP 6.9+ admin-ajax.php returns 400 for unregistered actions, so any
// race or load-order issue surfaces as a hard failure for the mini cart.
require_once EASYELEMENTS_DIR_PATH . 'includes/Hooks/mini-cart-ajax.php';

require_once EASYELEMENTS_DIR_PATH  . 'base.php';