<?php

declare(strict_types=1);

/**
 * Actiation hook event.
 *
 * @package ##NAMESPACE##
 * @author ##AUTHOR_NAME## ##AUTHOR_EMAIL##
 * @since ##PLUGIN_VERSION##
 */

namespace ##NAMESPACE_WP##;

use ##NAMESPACE_WP##\Uninstalled;
use ##SCOPER_PREFIX##\PinkCrab\Core\Application\App;

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
