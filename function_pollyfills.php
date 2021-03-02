<?php

/**
 * A custom file of function pollyfills to get around php-scoper using global functions
 * in funtion_exist calls.
 *
 * This file should have the same namespace used in scoper.inc.php config.
 *
 * @package ##NAMESPACE##
 * @author ##AUTHOR_NAME## ##AUTHOR_EMAIL##
 * @since ##PLUGIN_VERSION##
 */

namespace ##SCOPER_PREFIX##; 

function esc_attr() {
	return \esc_attr( ...func_get_args() );
}

