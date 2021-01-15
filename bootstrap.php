<?php

declare(strict_types=1);

/**
 * Used to bootload the application.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @since 1.0.0
 */

use PinkCrab\Core\Application\App;
use PinkCrab\Core\Services\Dice\Dice;
use PinkCrab\Core\Services\Dice\WP_Dice;
use PinkCrab\Core\Application\App_Config;
use PinkCrab\Core\Services\Registration\Loader;
use PinkCrab\Core\Services\ServiceContainer\Container;
use PinkCrab\Core\Services\Registration\Register_Loader;

// Populate Config with settings, if file exists.
$settings = file_exists( 'config/settings.php' )
	? require 'config/settings.php'
	: array();
$config   = new App_Config( $settings );

// Load hook loader, DI & container.
$loader    = Loader::boot();
$di        = WP_Dice::constructWith( new Dice() );
$container = new Container();

// Setup the service container .
$container->set( 'di', $di );
$container->set( 'config', $config );

// Boot the app.
$app = App::init( $container );

// Add all DI rules and register the actions from loader.
add_action(
	'init',
	function () use ( $loader, $app, $config ) {

		// If the dependencies file exists, add rules.
		if ( file_exists( 'config/dependencies.php' ) ) {
			$dependencies = include 'config/dependencies.php';
			$app->get( 'di' )->addRules( $dependencies );
		}

		// Add all registerable objects to loader, if file exists.
		if ( file_exists( 'config/registration.php' ) ) {
			$registerables = include 'config/registration.php';
			Register_Loader::initalise( $app, $registerables, $loader );
		}

		// Initalise all registerable classes.
		$loader->register_hooks();
	},
	1
);
