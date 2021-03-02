<?php

declare(strict_types=1);

/**
 * Used to bootload the application.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @since 1.0.0
 */

// use PinkCrab\Core\Application\App;
// use PinkCrab\Core\Services\Dice\Dice;
// use PinkCrab\Core\Services\Dice\WP_Dice;
// use PinkCrab\Core\Application\App_Config;
// use PinkCrab\Core\Services\Registration\Loader;
// use PinkCrab\Core\Services\ServiceContainer\Container;
// use PinkCrab\Core\Services\Registration\Register_Loader;

/** REPLACE THE ABOVE WITH YOUR NEW NAMESPACE AFTER RUNNING BUILD.SH */

// Populate Config with settings, if file exists.
$settings = file_exists( __DIR__ . '/config/settings.php' )
	? require __DIR__ . '/config/settings.php'
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
		if ( file_exists( __DIR__ . '/config/dependencies.php' ) ) {
			$dependencies = include __DIR__ . '/config/dependencies.php';
			$app->get( 'di' )->addRules( $dependencies );
		}

		// Add all registerable objects to loader, if file exists.
		if ( file_exists( __DIR__ . '/config/registration.php' ) ) {
			$registerables = include __DIR__ . '/config/registration.php';
			Register_Loader::initalise( $app, $registerables, $loader );
		}

		/** UNCOMMENT BELOW IF YOU WANT ACCESS TO App::view()->render('template', ['data' => 1]); */
		// Bind view to App
		//$view = $app::make( View::class );
		//$app->set( 'view', $view );

		// Initalise all registerable classes.
		$loader->register_hooks();
	},
	1
);
