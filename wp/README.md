# Framework_wp_dir
The basics wp directory used for all plugin activation/deactivatrion calls.

This should be installed to a dir called 'wp' in the base of your plugin.

To make use of the actions, they will need to be added to your plugin.php file.

````php
use PinkCrab\WP\Activation;
use PinkCrab\WP\Deactivation;

// Include activate and deactivate hooks.
register_activation_hook( __FILE__, array( App::make( Activation::class ), 'activate' ) );
register_deactivation_hook( __FILE__, array( App::make( Deactivation::class ), 'deactivate' ) );

````
