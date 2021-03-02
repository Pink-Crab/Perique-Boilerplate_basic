<?php

/**
 * @wordpress-plugin
 * Plugin Name:     ##PLUGIN_NAME##
 * Plugin URI:      ##PLUGIN_URL##
 * Description:     ##PLUGIN_DESCRIPTION##
 * Version:         ##PLUGIN_VERSION##
 * Author:          ##AUTHOR_NAME##
 * Author URI:      ##AUTHOR_URL##
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     ##PLUGIN_TEXTDOMAIN##
 */

use ##NAMESPACE_WP##\Activation; 
use ##NAMESPACE_WP##\Deactivation;
use ##SCOPER_PREFIX##\PinkCrab\Core\Application\App; 

require_once __DIR__ . '/function_pollyfills.php';
require_once __DIR__ . '/build/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

// Include activate and deactivate hooks (can be removed if not using the hooks).
register_activation_hook( __FILE__, array( App::make( Activation::class ), 'activate' ) );
register_deactivation_hook( __FILE__, array( App::make( Deactivation::class ), 'deactivate' ) );

