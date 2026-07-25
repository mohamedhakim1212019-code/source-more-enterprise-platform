<?php
/**
 * Central registry and controlled runtime initializer for platform modules.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Module_Registry {
	private const STATUS_REGISTERED = 'registered';
	private const STATUS_DISABLED   = 'disabled';
	private const STATUS_BOOTING    = 'booting';
	private const STATUS_BOOTED     = 'booted';
	private const STATUS_FAILED     = 'failed';

	/** @var array<string, SMTP_Module> */
	private static array $modules = array();

	/** @var array<string, string> */
	private static array $statuses = array();

	/** @var array<string, string> */
	private static array $errors = array();

	private static bool $boot_complete = false;

	/**
	 * Register a module before the boot cycle begins.
	 */
	public static function register( SMTP_Module $module ): bool {
		$id = $module->id();

		if ( self::$boot_complete ) {
			self::log_warning(
				'Module registration rejected after the boot cycle completed.',
				array( 'module' => $id )
			);
			return false;
		}

		if ( isset( self::$modules[ $id ] ) ) {
			self::log_warning(
				'Duplicate module registration ignored.',
				array( 'module' => $id )
			);
			return false;
		}

		self::$modules[ $id ]  = $module;
		self::$statuses[ $id ] = self::STATUS_REGISTERED;

		if ( function_exists( 'do_action' ) ) {
			do_action( 'smtp_platform_module_registered', $module );
		}

		return true;
	}

	public static function has( string $id ): bool {
		return isset( self::$modules[ strtolower( $id ) ] );
	}

	public static function get( string $id ): ?SMTP_Module {
		$id = strtolower( $id );
		return self::$modules[ $id ] ?? null;
	}

	/**
	 * @return array<string, SMTP_Module>
	 */
	public static function all(): array {
		return self::$modules;
	}

	/**
	 * Boot each enabled module exactly once, resolving dependencies first.
	 */
	public static function boot_enabled(): void {
		if ( self::$boot_complete ) {
			return;
		}

		foreach ( self::$modules as $id => $module ) {
			if ( ! $module->enabled() ) {
				self::$statuses[ $id ] = self::STATUS_DISABLED;
				continue;
			}

			self::boot( $id );
		}

		self::$boot_complete = true;

		if ( function_exists( 'do_action' ) ) {
			do_action( 'smtp_platform_modules_booted', self::statuses() );
		}
	}

	/**
	 * Boot one module and all of its enabled dependencies.
	 */
	public static function boot( string $id ): bool {
		$id = strtolower( trim( $id ) );

		if ( ! isset( self::$modules[ $id ] ) ) {
			self::log_error( 'Attempted to boot an unregistered module.', array( 'module' => $id ) );
			return false;
		}

		$module = self::$modules[ $id ];
		$status = self::$statuses[ $id ] ?? self::STATUS_REGISTERED;

		if ( self::STATUS_BOOTED === $status || $module->is_booted() ) {
			self::$statuses[ $id ] = self::STATUS_BOOTED;
			return true;
		}

		if ( self::STATUS_DISABLED === $status || self::STATUS_FAILED === $status ) {
			return false;
		}

		if ( self::STATUS_BOOTING === $status ) {
			return self::fail( $id, 'Circular module dependency detected.' );
		}

		if ( ! $module->enabled() ) {
			self::$statuses[ $id ] = self::STATUS_DISABLED;
			return false;
		}

		self::$statuses[ $id ] = self::STATUS_BOOTING;

		foreach ( $module->dependencies() as $dependency_id ) {
			$dependency = self::get( $dependency_id );

			if ( null === $dependency ) {
				return self::fail(
					$id,
					sprintf( 'Required module "%s" is not registered.', $dependency_id )
				);
			}

			if ( ! $dependency->enabled() ) {
				return self::fail(
					$id,
					sprintf( 'Required module "%s" is disabled.', $dependency_id )
				);
			}

			if ( ! self::boot( $dependency_id ) ) {
				return self::fail(
					$id,
					sprintf( 'Required module "%s" failed to boot.', $dependency_id )
				);
			}
		}

		if ( ! $module->requirements_met() ) {
			return self::fail(
				$id,
				sprintf(
					'Missing runtime requirement(s): %s',
					implode( ', ', $module->missing_requirements() )
				)
			);
		}

		try {
			$module->boot();
			self::$statuses[ $id ] = self::STATUS_BOOTED;

			if ( function_exists( 'do_action' ) ) {
				do_action( 'smtp_platform_module_booted', $module );
			}

			return true;
		} catch ( Throwable $throwable ) {
			return self::fail( $id, $throwable->getMessage(), $throwable );
		}
	}

	/**
	 * Return a diagnostic-safe status map.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public static function statuses(): array {
		$output = array();

		foreach ( self::$modules as $id => $module ) {
			$output[ $id ] = array(
				'label'        => $module->label(),
				'version'      => $module->version(),
				'enabled'      => $module->enabled(),
				'status'       => self::$statuses[ $id ] ?? self::STATUS_REGISTERED,
				'dependencies' => $module->dependencies(),
				'error'        => self::$errors[ $id ] ?? '',
			);
		}

		return $output;
	}

	public static function boot_complete(): bool {
		return self::$boot_complete;
	}

	private static function fail( string $id, string $message, ?Throwable $throwable = null ): bool {
		self::$statuses[ $id ] = self::STATUS_FAILED;
		self::$errors[ $id ]   = $message;

		$context = array(
			'module' => $id,
			'error'  => $message,
		);

		if ( null !== $throwable ) {
			$context['exception'] = get_class( $throwable );
		}

		self::log_error( 'Platform module failed to boot.', $context );

		if ( function_exists( 'do_action' ) ) {
			do_action( 'smtp_platform_module_failed', $id, $message, $throwable );
		}

		return false;
	}

	private static function log_warning( string $message, array $context = array() ): void {
		if ( class_exists( 'SMTP_Logger' ) ) {
			SMTP_Logger::warning( $message, $context );
		}
	}

	private static function log_error( string $message, array $context = array() ): void {
		if ( class_exists( 'SMTP_Logger' ) ) {
			SMTP_Logger::error( $message, $context );
		}
	}
}
