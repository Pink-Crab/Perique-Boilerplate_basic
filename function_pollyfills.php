<?php

/**
 * A custom file of function pollyfills to get around php-scoper using global functions
 * in funtion_exist calls.
 *
 * This file should have the same namespace used in scoper.inc.php config.
 *
 * @since 0.3.1
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 */

// namespace PcLocations_001; // <-- Replace with your namespace in scoper.inc.php

function esc_attr() {
	return \esc_attr( ...func_get_args() );
}

