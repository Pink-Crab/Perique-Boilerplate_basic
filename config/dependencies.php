<?php

declare(strict_types=1);

/**
 * Handles all depenedency injection rules and config.
 *
 * @package PinkCrab\PluginBoilerplate
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @since 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

use PinkCrab\Core\Application\App;
use PinkCrab\Core\Interfaces\Renderable;
use PinkCrab\Core\Services\View\PHP_Engine;

return array(
	// Gloabl Rules
	'*'         => array(
		'substitutions' => array(
			App::class        => App::get_instance(),
			Renderable::class => PHP_Engine::class,
		),
	),

	// Use wpdb as an injectable object.
	wpdb::class => array(
		'shared'          => true,
		'constructParams' => array( \DB_USER, \DB_PASSWORD, \DB_NAME, \DB_HOST ),
	),

	/** ADD YOUR CUSTOM RULES HERE */
);
