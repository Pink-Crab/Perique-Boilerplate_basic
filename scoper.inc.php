<?php

declare(strict_types=1);
use Isolated\Symfony\Component\Finder\Finder;

// Returns an array of all symbols from processed stubs.
$patcherProvider = function() {

	// Alter this if you change the patcher creation dir.
	$patcherDir = __DIR__ . '/build-tools/patchers';
	$stubFiles  = array_diff( scandir( $patcherDir ), array( '..', '.' ) );

	$symbols = array();
	foreach ( $stubFiles as $stub ) {

		if ( $stub === '.gitkeep' ) {
				continue;
		}

		try {
			// Attempt to unserialize
			$stubSymbols = unserialize( file_get_contents( $patcherDir . '/' . $stub ) );

			if ( ! is_array( $stubSymbols ) ) {
				throw new Exception( 'Array of symbols expected' );
			}

			$symbols = array_merge( $symbols, $stubSymbols );
		} catch ( \Throwable $th ) {
			die( $th->getMessage() );
		}
	}

	return $symbols;
};

return array(
	// Set your namespace prefix here
	'prefix'                     => '##SCOPER_PREFIX##',
	'finders'                    => array(
		Finder::create()
			->files()
			->ignoreVCS( true )
			->notName( '/LICENSE|.*\\.md|.*\\.dist|Makefile|composer\\.json|composer\\.lock/' )
			->exclude(
				array(
					'doc',
					'test',
					'test_old',
					'tests',
					'Tests',
					'vendor-bin',
				)
			)
			->in( 'vendor' ),
		Finder::create()->append(
			array(
				'composer.json',
			)
		),
	),
	'patchers'                   => array(
		function ( $filePath, $prefix, $contents ) use ( $patcherProvider ) {
			$prefixDoubleSlashed = str_replace( '\\', '\\\\', $prefix );
			$quotes = array( '\'', '"', '`' );

			foreach ( $patcherProvider() as $identifier ) {
				$contents = str_replace( "\\$prefix\\$identifier", "\\$identifier", $contents );
			}

			// Add in any additional symbols to not prefix.
			// $contents = str_replace( "\\$prefix\\my_global_function", '\\my_global_function', $contents );

			return $contents;
		},
	),
	'whitelist'                  => array(
		'PHPUnit\Framework\*',
		'Composer\Autoload\ClassLoader',
		'##NAMESPACE##\*',
	),
	'whitelist-global-constants' => true,
	'whitelist-global-classes'   => true,
	'whitelist-global-functions' => true,
);
