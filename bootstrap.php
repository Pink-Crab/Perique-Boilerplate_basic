<?php

declare(strict_types=1);

/**
 * Used to bootload the application.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @since 1.0.0
 */

use ErrorException;
use PinkCrab\Core\Application\App;
use PinkCrab\Core\Services\Dice\Dice;
use PinkCrab\Core\Services\Dice\WP_Dice;
use PinkCrab\Core\Application\App_Config;
use PinkCrab\Core\Services\Registration\Loader;
use PinkCrab\Core\Services\ServiceContainer\Container;
use PinkCrab\Core\Services\Registration\Register_Loader;

// App setup
try {
	// Load config with settings.
	$settings = include 'config/settings.php';
	$config   = new App_Config( $settings ); // Change if using custom path for config.

	// Load hook loader, DI & container.
	$loader    = Loader::boot();
	$di        = WP_Dice::constructWith( new Dice() );
	$container = new Container();

} catch ( \Throwable $th ) {
	throw new ErrorException( 'Failed to initalise PinkCrab Application : ' . $th->getMessage() );
}

// Setup the service container .
$container->set( 'di', $di );
$container->set( 'config', $config );

// Boot the app.
$app = App::init( $container );

// Add all DI rules and register the actions from loader.
add_action(
	'init',
	function () use ( $loader, $app, $config ) {

		$dependencies  = include 'config/dependencies.php';
		$registerables = include 'config/registration.php';

		// Add all DI rules.
		$app->get( 'di' )->addRules( apply_filters( 'PinkCrab\\di_rules', $dependencies ) );

		// Initalise all registerable classes.
		Register_Loader::initalise(
			$app,
			apply_filters( 'PinkCrab\\registration_rules', $registerables ), // Change if using custom path for config.
			$loader
		);

		// Register Loader hooks.
		$loader->register_hooks();
	},
	1
);
