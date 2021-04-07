<?php

declare(strict_types=1);

/**
 * Handles all general settings.
 *
 * @package PinkCrab\Framework
 * @author ##AUTHOR_NAME## ##AUTHOR_EMAIL##
 * @since ##PLUGIN_VERSION##
 */

// Base path and urls
$base_path  = \dirname( __DIR__, 1 );
$plugin_dir = \basename( $base_path );

// Useful WP helpers
$wp_uploads = \wp_upload_dir();
global $wpdb;

// Include the plugins file for access plugin details before init.
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$plugin_data = get_plugin_data( $base_path . '/plugin.php' );

return array(
	'path'       => array(
		'plugin'         => $base_path,
		'view'           => $base_path . '/views',
		'assets'         => $base_path . '/assets',
		'upload_root'    => $wp_uploads['basedir'],
		'upload_current' => $wp_uploads['path'],
	),
	'url'        => array(
		'plugin'         => \plugins_url( $plugin_dir ),
		'view'           => \plugins_url( $plugin_dir ) . '/views',
		'assets'         => \plugins_url( $plugin_dir ) . '/assets',
		'upload_root'    => $wp_uploads['baseurl'],
		'upload_current' => $wp_uploads['url'],
	),
	'post_types' => array(
		// 'your_key' => array(   // use with Config::post_types('your_key')
		// 	'slug' => 'cpt_slug',
		// 	'meta' => array(
		// 		'your_key'  => 'meta_key',
		// 	),
		// ),
	),
	'taxonomies' => array(
		// 'your_key' => array( // use with Config::taxonomies('your_key')
		// 	'slug' => 'tax_slug',
		// 	'term' => array(),
		// ),
	),
	'plugin'     => array(
		'version' => is_array( $plugin_data ) && array_key_exists( 'Version', $plugin_data )
			? $plugin_data['Version'] : '##PLUGIN_VERSION##',
	),
	'namespaces' => array(
		'rest'  => 'pinkcrab/boilerplate',
		'cache' => 'pinkcrab_boilerplate',
	),
);
