<?php

declare(strict_types=1);

/**
 * Applies all the defined patchers to an instance of PHP_Scoper
 */

class Patcher_Dispatcher {

	/**
	 * Path to the serialised patcher files.
	 */
	protected string $patchers = '';

	public function __construct( string $patchers ) {
		$this->patchers = $patchers;
	}

	/**
	 * Returns an array of all serialised patcher files.
	 * Removed . , .. & .gitkeep from file list.
	 *
	 * @return array<string>
	 */
	protected function get_patcher_files(): array {
		return array_diff( scandir( $this->patchers ), array( '..', '.', '.gitkeep' ) );
	}

	/**
	 * Reuturns an array of all Class, Function, Constant, Interfaces and Traits.
	 *
	 * @return array<string>
	 */
	public function get_patcher_elements(): array {
		$elements = array();
		foreach ( $this->get_patcher_files() as $file ) {
			try {
				// Attempt to unserialize
				$stub_elements = unserialize( file_get_contents( $this->patchers . '/' . $file ) );

				if ( ! is_array( $stub_elements ) ) {
					throw new Exception( 'Array of elements expected' );
				}

				$elements = array_merge( $elements, $stub_elements );
			} catch ( \Throwable $th ) {
				die( $th->getMessage() );
			}
		}

		return $elements;
	}
}
