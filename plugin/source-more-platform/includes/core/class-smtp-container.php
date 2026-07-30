<?php
/**
 * Lightweight service container for SMEP core services.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Container {
	/** @var array<string, mixed> */
	private static array $services = array();

	/** @var array<string, callable> */
	private static array $factories = array();

	public static function set( string $id, $service ): void {
		self::$services[ strtolower( trim( $id ) ) ] = $service;
	}

	public static function factory( string $id, callable $factory ): void {
		self::$factories[ strtolower( trim( $id ) ) ] = $factory;
	}

	public static function has( string $id ): bool {
		$id = strtolower( trim( $id ) );
		return array_key_exists( $id, self::$services ) || array_key_exists( $id, self::$factories );
	}

	public static function get( string $id ) {
		$id = strtolower( trim( $id ) );

		if ( array_key_exists( $id, self::$services ) ) {
			return self::$services[ $id ];
		}

		if ( ! array_key_exists( $id, self::$factories ) ) {
			throw new InvalidArgumentException( sprintf( 'Unknown Source More Platform service: %s', $id ) );
		}

		self::$services[ $id ] = call_user_func( self::$factories[ $id ], self::class );
		return self::$services[ $id ];
	}

	/** @return string[] */
	public static function ids(): array {
		return array_values( array_unique( array_merge( array_keys( self::$services ), array_keys( self::$factories ) ) ) );
	}
}
