<?php

declare(strict_types=1);

/**
 * Handles all depenedency injection rules and config.
 *
 * @package PinkCrab\PluginBoilerplate
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @since 0.1.0
 */

/** REPLACE ALL THESE WITH YOUR ESCAPED NAMSPACES */
// use PinkCrab\Core\Application\App_Config;
// use PinkCrab\Core\Application\App;
// use PinkCrab\Core\Interfaces\Renderable;
// use PinkCrab\Core\Services\View\PHP_Engine;

// Gets the apps config array.
$config = App::retreive( 'config' );

return array(
	// Gloabl Rules
	'*'               => array(
		'substitutions' => array(
			App::class        => App::get_instance(),
			Renderable::class => new PHP_Engine( $config->path( 'view' ) ),
			wpdb::class       => $GLOBALS['wpdb'],
			App_Config::class => $config,
		),
	),
	/** ADD YOUR CUSTOM RULES HERE */
);
