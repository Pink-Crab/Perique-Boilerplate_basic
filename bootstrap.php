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

// App setup
$loader    = Loader::boot();
$config    = new App_Config( require( 'config/settings.php' ) ); // Change if using custom path for config.
$container = new Container();

// Setup the service container .
$container->set( 'di', WP_Dice::constructWith( new Dice() ) );
$container->set( 'config', $config );

// Boot the app.
$app = App::init( $container );

// Add all DI rules and register the actions from loader.
add_action(
	'init',
	function () use ( $loader, $app, $config ) {

		// Add all DI rules.
		$app->get( 'di' )->addRules(
			apply_filters( 'PinkCrab\\di_rules', require( 'config/dependencies.php' ) ) // Change if using custom path for config.
		);
		// Initalise all registerable classes.
		Register_Loader::initalise(
			$app,
			apply_filters( 'PinkCrab\\registration_rules', require( 'config/registration.php' ) ), // Change if using custom path for config.
			$loader
		);

		// Register Loader hooks.
		$loader->register_hooks();
	},
	1
);
