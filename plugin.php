<?php

/**
 * @wordpress-plugin
 * Plugin Name:     AVo_E_O
 * Plugin URI:      ##YOUR URL##
 * Description:     ##YOUR PLUGIN DESC##
 * Version:         ##VERSION##
 * Author:          ##AUTHOR##
 * Author URI:      ##YOUR URL##
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     ##TEXT DOMAIN##
 */

// use PinkCrab\WP\Activation; // <-- Repalce these to match your namespace for 'wp' in composer.json
// use PinkCrab\WP\Deactivation;
// use PinkCrab\Core\Applcation\App; // <--- replace this with the new scoped name

require_once __DIR__ . '/function_pollyfills.php';
require_once __DIR__ . '/build/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

// Include activate and deactivate hooks (can be removed if not using the hooks).
register_activation_hook( __FILE__, array( App::make( Activation::class ), 'activate' ) );
register_deactivation_hook( __FILE__, array( App::make( Deactivation::class ), 'deactivate' ) );

