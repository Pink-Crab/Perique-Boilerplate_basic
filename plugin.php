<?php
/**
 * @wordpress-plugin
 * Plugin Name:     ##PLUGIN NAME##
 * Plugin URI:      ##YOUR URL##
 * Description:     ##DESCRIPTION##
 * Version:         ##VERSION##
 * Author:          ##AUTHOR##
 * Author URI:      ##YOUR URL##
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * TextDomain:      ##TEXT DOMAIN##
 */

require_once __DIR__ . '/build/vendor/autoload.php';

( new App_Factory )->with_wp_dice( true )
	->container_config(
		function( DI_Container $container ): void {
			// Pass an array of rules
			$container->addRules( require __DIR__ . '/config/dependencies.php' );
		}
	)
	->app_config( require __DIR__ . '/config/settings.php' )
	->registration_classses( require __DIR__ . '/config/registration.php' )
	->boot();


