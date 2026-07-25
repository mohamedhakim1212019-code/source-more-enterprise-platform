<?php
/**
 * Immutable runtime module definition.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Module {
	/** @var callable */
	private $boot_callback;

	/** @var callable */
	private $enabled_callback;

	private string $id;
	private string $label;
	private string $version;
	private array $dependencies;
	private array $required_classes;
	private bool $booted = false;

	/**
	 * @param string        $id                Stable machine-readable module ID.
	 * @param string        $label             Human-readable module label.
	 * @param string        $version           Module implementation version.
	 * @param callable      $boot_callback     Callback that registers the module runtime.
	 * @param array         $dependencies      Other registered module IDs required first.
	 * @param callable|null $enabled_callback  Optional callback that determines whether the module is enabled.
	 * @param array         $required_classes  PHP classes or interfaces that must exist before booting.
	 */
	public function __construct(
		string $id,
		string $label,
		string $version,
		callable $boot_callback,
		array $dependencies = array(),
		?callable $enabled_callback = null,
		array $required_classes = array()
	) {
		$id = strtolower( trim( $id ) );

		if ( '' === $id || ! preg_match( '/^[a-z0-9_-]+$/', $id ) ) {
			throw new InvalidArgumentException( 'A module ID may contain only lowercase letters, numbers, underscores, and hyphens.' );
		}

		$this->id                = $id;
		$this->label             = '' !== trim( $label ) ? trim( $label ) : $id;
		$this->version           = '' !== trim( $version ) ? trim( $version ) : '0.0.0';
		$this->boot_callback     = $boot_callback;
		$this->enabled_callback  = $enabled_callback ?? static fn(): bool => true;
		$this->dependencies      = $this->normalize_identifiers( $dependencies );
		$this->required_classes  = $this->normalize_class_names( $required_classes );
	}

	public function id(): string {
		return $this->id;
	}

	public function label(): string {
		return $this->label;
	}

	public function version(): string {
		return $this->version;
	}

	public function dependencies(): array {
		return $this->dependencies;
	}

	public function required_classes(): array {
		return $this->required_classes;
	}

	public function enabled(): bool {
		$enabled = (bool) call_user_func( $this->enabled_callback );

		if ( function_exists( 'apply_filters' ) ) {
			$enabled = (bool) apply_filters( 'smtp_platform_module_enabled', $enabled, $this->id, $this );
		}

		return $enabled;
	}

	public function requirements_met(): bool {
		foreach ( $this->required_classes as $class_name ) {
			if ( ! class_exists( $class_name ) && ! interface_exists( $class_name ) ) {
				return false;
			}
		}

		return true;
	}

	public function missing_requirements(): array {
		$missing = array();

		foreach ( $this->required_classes as $class_name ) {
			if ( ! class_exists( $class_name ) && ! interface_exists( $class_name ) ) {
				$missing[] = $class_name;
			}
		}

		return $missing;
	}

	/**
	 * Execute the module bootstrap callback once.
	 *
	 * @return bool True when this call performed the boot, false when already booted.
	 */
	public function boot(): bool {
		if ( $this->booted ) {
			return false;
		}

		call_user_func( $this->boot_callback );
		$this->booted = true;

		return true;
	}

	public function is_booted(): bool {
		return $this->booted;
	}

	private function normalize_identifiers( array $identifiers ): array {
		$normalized = array();

		foreach ( $identifiers as $identifier ) {
			$identifier = strtolower( trim( (string) $identifier ) );

			if ( '' !== $identifier && preg_match( '/^[a-z0-9_-]+$/', $identifier ) ) {
				$normalized[] = $identifier;
			}
		}

		return array_values( array_unique( $normalized ) );
	}

	private function normalize_class_names( array $class_names ): array {
		$normalized = array();

		foreach ( $class_names as $class_name ) {
			$class_name = ltrim( trim( (string) $class_name ), '\\' );

			if ( '' !== $class_name ) {
				$normalized[] = $class_name;
			}
		}

		return array_values( array_unique( $normalized ) );
	}
}
