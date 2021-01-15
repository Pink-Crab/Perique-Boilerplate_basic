<?php

declare(strict_types=1);

/**
 * Actiation hook event.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\WP
 */

namespace PinkCrab\WP;

use PinkCrab\WP\Uninstalled;
use PinkCrab\Core\Application\App;

class Activation {

	/**
	 * Entry point for action hook call.
	 *
	 * @return void
	 */
	public function activate() {
		// Register unistall hook.
		register_uninstall_hook( __FILE__, array( App::make( Uninstalled::class ), 'uninstall' ) );
	}
}
