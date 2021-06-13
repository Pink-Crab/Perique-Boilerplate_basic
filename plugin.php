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

use ##SCOPER_PREFIX##\PinkCrab\Perique\Application\App_Factory;

require_once __DIR__ . '/function_pollyfills.php';
require_once __DIR__ . '/build/vendor/autoload.php';

( new App_Factory() )->with_wp_dice( true )
	->di_rules( require __DIR__ . '/config/dependencies.php' )
	->app_config( require __DIR__ . '/config/settings.php' )
	->registration_classes( require __DIR__ . '/config/registration.php' )
	->boot();
