<?php

declare(strict_types=1);

/**
 * Handles all depenedency injection rules and config.
 *
 * @package ##NAMESPACE##
 * @author ##AUTHOR_NAME## ##AUTHOR_EMAIL##
 * @since ##PLUGIN_VERSION##
 */

use ##SCOPER_PREFIX##\PinkCrab\Core\Application\App;
use ##SCOPER_PREFIX##\PinkCrab\Core\Interfaces\Renderable;
use ##SCOPER_PREFIX##\PinkCrab\Core\Application\App_Config;
use ##SCOPER_PREFIX##\PinkCrab\Core\Services\View\PHP_Engine;

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
