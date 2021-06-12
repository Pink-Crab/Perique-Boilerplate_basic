<?php

declare(strict_types=1);

/**
 * Holds all custom app config values.
 * See docs at https://app.gitbook.com/@glynn-quelch/s/pinkcrab/application/app_config
 */

// Base path and urls
$base_path  = \dirname( __DIR__, 1 );
$plugin_dir = \basename( $base_path );

// Useful WP helpers
$wp_uploads = \wp_upload_dir();

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

		// Include custom
		// 'foo' => 'some/path',
		// Will allow Config::path('foo') === 'some/path'
	),
	'url'        => array(
		'plugin'         => \plugins_url( $plugin_dir ),
		'view'           => \plugins_url( $plugin_dir ) . '/views',
		'assets'         => \plugins_url( $plugin_dir ) . '/assets',
		'upload_root'    => $wp_uploads['baseurl'],
		'upload_current' => $wp_uploads['url'],

		// Include custom
		// 'bar' => 'some/url',
		// Will allow Config::url('bar') === 'some/path'
	),
	'post_types' => array(
		// Use this to prefix your cpt types
		// 'events' => 'pinkcrab_event',
		// Will allow Config::post_types('events') === 'pinkcrab_event'
	),
	'taxonomies' => array(
		// Use this to prefix your taxonomies
		// 'location' => 'pinkcrab_location',
		// Will allow Config::taxonomies('location') === 'pinkcrab_location'
	),
	'meta'       => array(
		'post' => array(
			// 'key_1' => 'pinkcrab_post_meta_key_1',
			// Will allow Config::post_meta('key_1') === 'pinkcrab_post_meta_key_1'
		),
		'user' => array(
			// 'key_1' => 'pinkcrab_user_meta_key_1',
			// Will allow Config::user_meta('key_1') === 'pinkcrab_user_meta_key_1'
		),
		'term' => array(
			// 'key_1' => 'pinkcrab_term_meta_key_1',
			// Will allow Config::term_meta('key_1') === 'pinkcrab_term_meta_key_1'
		),
	),
	'db_tables'  => array(
		// 'db' => $GLOBALS['wpdb']->prefix . 'db_table',
		// Will allow Config::db_tables('db') === 'wp_db_table'
	),
	'plugin'     => array(
		'version' => is_array( $plugin_data ) && array_key_exists( 'Version', $plugin_data )
			? $plugin_data['Version'] : '0.1.0', // <- fallback version 
	),
	'namespaces' => array(
		'rest'  => 'pinkcrab/boilerplate',
		'cache' => 'pinkcrab_boilerplate',
	),
);

