<?php

declare(strict_types=1);

/**
 * All custom rules for the DI Container.
 * See docs at https://app.gitbook.com/@glynn-quelch/s/pinkcrab/application/dependency-injection
 */

return array(
	// Sets the base path for views.
	// If you are using a different views path, please update in PHP_Engine args
	// Remove this if not planning to use the View or replace if using BladeOne
	'*' => array(
		'substitutions' => array(
			PinkCrab\Perique\Interfaces\Renderable::class
				=> new PinkCrab\Perique\Services\View\PHP_Engine(
					\dirname( __DIR__, 1 ) . '/views'
				),
		),
	),
);
