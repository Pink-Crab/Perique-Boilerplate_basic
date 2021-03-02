<?php

use pxlrbt\PhpScoper\PrefixRemover\IdentifierExtractor;

$vendorDir = dirname( __DIR__, 1 ) . '/vendor';
require $vendorDir . '/autoload.php';
require __DIR__ . '/PatcherCreator/IdentifierExtractor.php';

$stubs = array(
	array(
		'package'     => 'php-stubs/wordpress-stubs',
		'stubsPath'   => array( $vendorDir . '/php-stubs/wordpress-stubs/wordpress-stubs.php' ),
		'destination' => __DIR__ . '/patchers/wp_patcher.do',
	),
	array(
		'package'     => 'kimhf/woocommerce-stubs',
		'stubsPath'   => array( $vendorDir . '/kimhf/woocommerce-stubs/woocommerce-stubs.php' ),
		'destination' => __DIR__ . '/patchers/wc_patcher.do',
	),
	array(
		'package'     => 'kimhf/advanced-custom-fields-pro-stubs',
		'stubsPath'   => array( $vendorDir . '/kimhf/advanced-custom-fields-pro-stubs/advanced-custom-fields-pro-stubs.php' ),
		'destination' => __DIR__ . '/patchers/acf_patcher.do',
	),
);

foreach ( $stubs as $stub ) {

	// Check vendor module exists.
	if ( file_exists( $vendorDir . '/' . $stub['package'] ) ) {
		$symbols = create_patcher( $stub );
		if ( count( $symbols ) >= 1 ) {
			file_put_contents( $stub['destination'], serialize( $symbols ) );

			print "** Created {$stub['package']} Patcher from Stub file. \r\n";
		}
	}
}

function create_patcher( array $stub ): array {
	$symbols = array();

	if ( count( $stub ) === 0
	|| ! array_key_exists( 'stubsPath', $stub )
	|| ! array_key_exists( 'destination', $stub )
	|| strlen( $stub['destination'] ) === ''
	) {
		return $symbols;
	}

	// Loop through
	foreach ( $stub['stubsPath'] as $path ) {

		if ( ! file_exists( $path ) ) {
			continue;
		}

		$patchNodes = ( new IdentifierExtractor() )
			->addStub( $path )
			->extract();

		$nodeSymbols = array_map(
			function( $e ) {
				return $e->name;
			},
			$patchNodes
		);

		$symbols = array_merge( $symbols, $nodeSymbols );
	}

	return $symbols;
}


