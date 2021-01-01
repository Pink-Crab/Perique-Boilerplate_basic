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

);
