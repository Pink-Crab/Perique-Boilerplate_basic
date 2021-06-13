<?php

declare(strict_types=1);

/**
 * Creates the custom patchers for WordPess Core, WooCommerce and ACF
 */

use pxlrbt\PhpScoper\PrefixRemover\IdentifierExtractor;

class Patcher_Builder {

	/**
	 * Path to vendor dir
	 */
	protected string $vendor_dir = '';

	/**
	 * All passed stub details.
	 */
	protected array $stubs = array();

	public function __construct( string $vendor_dir ) {
		$this->vendor_dir = $vendor_dir;
	}

	/**
	 * Populates the sub files.
	 *
	 * @param array<array{package:string,stubsPath:string,destination:string}> $stubs
	 * @return self
	 */
	public function set_stubs( array $stubs ): self {
		// Remove any invalid stubs.
		$this->stubs = array_filter(
			$stubs,
			function( $stub ) {
				return array_key_exists( 'package', $stub )
				&& array_key_exists( 'stubsPath', $stub )
				&& array_key_exists( 'destination', $stub );
			}
		);
		return $this;
	}

	/**
	 * Triggers the creation of all patchers from defined stubs.
	 *
	 * @return self
	 */
	public function execute(): self {
		// Loops through the stubs and creates patchers
		foreach ( $this->stubs as $stub ) {
			$this->populate_patcher( $stub );
		}

		return $this;
	}

	/**
	 * Attempts to populate a patcher from a defined stub.
	 *
	 * @param array{package:string,stubsPath:string,destination:string} $stub
	 * @return void
	 */
	protected function populate_patcher( array $stub ) {
		// Abort of package not in vendor.
		if ( ! file_exists( $this->vendor_dir . '/' . $stub['package'] ) ) {
			print "⚠️ {$stub['package']} was not found in vendor, skipping..... \r\n";
			return;
		}

		$symbols = $this->extract_stub_elements( $stub );

		if ( count( $symbols ) >= 1 ) {
			file_put_contents( $stub['destination'], serialize( $symbols ) );

			print "** Created {$stub['package']} Patcher from Stub file. \r\n";
		}
	}

	/**
	 * Attempts to populate a patcher from a defined stub.
	 *
	 * @param array{package:string,stubsPath:string,destination:string} $stub
	 * @return array<string> An array of all Function, Class and Constant names from stubs.
	 */
	protected function extract_stub_elements( array $stub ): array {

		$elements = array();

		// If we have no stub paths or the destination is empty, return empty elements..
		if ( count( $stub['stubsPath'] ) === 0 || strlen( $stub['destination'] ) === 0 ) {
			return $elements;
		}

		// Process each stub file.
		foreach ( $stub['stubsPath'] as $path ) {

			// Skip if file doesnt exist.
			if ( ! file_exists( $path ) ) {
				print "⚠️ {$stub['package']} file {$path} was not found, skipping..... \r\n";
				continue;
			}

			// Extract all element names from stub file.
			$element_names = array_map(
				function( $e ) {
					return $e->name;
				},
				( new IdentifierExtractor() )->addStub( $path )->extract()
			);

			// Comnbine with existing elements.
			$elements = array_merge( $elements, $element_names );
		}

		return $elements;
	}
}























// $stubs = array(
// 	array(
// 		'package'     => 'php-stubs/wordpress-stubs',
// 		'stubsPath'   => array( $vendor_dir . '/php-stubs/wordpress-stubs/wordpress-stubs.php' ),
// 		'destination' => __DIR__ . '/patchers/wp_patcher.do',
// 	),
// 	array(
// 		'package'     => 'kimhf/woocommerce-stubs',
// 		'stubsPath'   => array( $vendor_dir . '/kimhf/woocommerce-stubs/woocommerce-stubs.php' ),
// 		'destination' => __DIR__ . '/patchers/wc_patcher.do',
// 	),
// 	array(
// 		'package'     => 'kimhf/advanced-custom-fields-pro-stubs',
// 		'stubsPath'   => array( $vendor_dir . '/kimhf/advanced-custom-fields-pro-stubs/advanced-custom-fields-pro-stubs.php' ),
// 		'destination' => __DIR__ . '/patchers/acf_patcher.do',
// 	),
// );

// dd( $stubs );

// foreach ( $stubs as $stub ) {

// 	// Check vendor module exists.
// 	if ( file_exists( $vendor_dir . '/' . $stub['package'] ) ) {
// 		$symbols = create_patcher( $stub );
// 		if ( count( $symbols ) >= 1 ) {
// 			file_put_contents( $stub['destination'], serialize( $symbols ) );

// 			print "** Created {$stub['package']} Patcher from Stub file. \r\n";
// 		}
// 	}
// }

// function create_patcher( array $stub ): array {
// 	$symbols = array();

// 	if ( count( $stub ) === 0
// 	|| ! array_key_exists( 'stubsPath', $stub )
// 	|| ! array_key_exists( 'destination', $stub )
// 	|| strlen( $stub['destination'] ) === 0
// 	) {
// 		return $symbols;
// 	}

// 	// Loop through
// 	foreach ( $stub['stubsPath'] as $path ) {

// 		if ( ! file_exists( $path ) ) {
// 			continue;
// 		}

// 		$patchNodes = ( new IdentifierExtractor() )
// 			->addStub( $path )
// 			->extract();

// 		$nodeSymbols = array_map(
// 			function( $e ) {
// 				return $e->name;
// 			},
// 			$patchNodes
// 		);

// 		$symbols = array_merge( $symbols, $nodeSymbols );
// 	}

// 	return $symbols;
// }


