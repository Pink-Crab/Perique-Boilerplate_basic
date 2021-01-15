<?php

declare(strict_types=1);

/**
 * Handles all general settings.
 *
 * @package PinkCrab\Framework
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @since 0.1.0
 */

// Get the path of the plugin base.
$base_path  = \dirname( __DIR__, 1 );
$plugin_dir = \basename( $base_path );
$wp_uploads = \wp_upload_dir();

return array(
	'additional' => array(
		// Register your custom config data.
	),

	// DEFAULT VALUES, OVERWRITE IN ARRAY ^^
	// array(
	// 	'plugin'     => array(
	// 		'version' => '0.1.0',
	// 	),
	// 	'path'       => array(
	// 		'plugin'         => $base_path,
	// 		'view'           => $base_path . '/views',
	// 		'assets'         => $base_path . '/assets',
	// 		'upload_root'    => $wp_uploads['basedir'],
	// 		'upload_current' => $wp_uploads['path'],
	// 	),
	// 	'url'        => array(
	// 		'plugin'         => plugins_url( $plugin_dir ),
	// 		'view'           => plugins_url( $plugin_dir ) . '/views',
	// 		'assets'         => plugins_url( $plugin_dir ) . '/assets',
	// 		'upload_root'    => $wp_uploads['baseurl'],
	// 		'upload_current' => $wp_uploads['url'],
	// 	),
	// 	'post_types' => array(),
	// 	'taxonomies' => array(),
	// 	'db_tables'  => array(),
	// 	'namespaces' => array(
	// 		'rest'  => 'pinkcrab',
	// 		'cache' => 'pc_cache',
	// 	),
	// 	'additional' => array(),

	// );

);
