<?php
/**
 * Standalone smoke test for the SMEP module registry.
 *
 * Run: php tests/module-registry-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );

final class SMTP_Logger {
	public static array $events = array();

	public static function warning( string $message, array $context = array() ): void {
		self::$events[] = array( 'warning', $message, $context );
	}

	public static function error( string $message, array $context = array() ): void {
		self::$events[] = array( 'error', $message, $context );
	}
}

function do_action( string $hook, ...$args ): void {
	// Action dispatch is intentionally not required for this isolated smoke test.
}

function apply_filters( string $hook, $value, ...$args ) {
	return $value;
}

require_once dirname( __DIR__ ) . '/includes/core/class-smtp-module.php';
require_once dirname( __DIR__ ) . '/includes/core/class-smtp-module-registry.php';

$boot_order = array();

$assert = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
};

$assert(
	SMTP_Module_Registry::register(
		new SMTP_Module(
			'settings',
			'Settings',
			'1.0.0',
			static function () use ( &$boot_order ): void {
				$boot_order[] = 'settings';
			}
		)
	),
	'Settings module should register.'
);

$assert(
	SMTP_Module_Registry::register(
		new SMTP_Module(
			'dashboard',
			'Dashboard',
			'1.0.0',
			static function () use ( &$boot_order ): void {
				$boot_order[] = 'dashboard';
			},
			array( 'settings' )
		)
	),
	'Dashboard module should register.'
);

$assert(
	SMTP_Module_Registry::register(
		new SMTP_Module(
			'disabled',
			'Disabled Feature',
			'1.0.0',
			static function () use ( &$boot_order ): void {
				$boot_order[] = 'disabled';
			},
			array(),
			static fn(): bool => false
		)
	),
	'Disabled module should register.'
);

$assert(
	SMTP_Module_Registry::register(
		new SMTP_Module(
			'missing-dependency',
			'Missing Dependency',
			'1.0.0',
			static function (): void {},
			array( 'not-registered' )
		)
	),
	'Module with a missing dependency should register before validation.'
);

$assert(
	SMTP_Module_Registry::register(
		new SMTP_Module(
			'missing-class',
			'Missing Class',
			'1.0.0',
			static function (): void {},
			array(),
			null,
			array( 'Class_That_Does_Not_Exist' )
		)
	),
	'Module with a missing runtime class should register before validation.'
);

$assert(
	! SMTP_Module_Registry::register(
		new SMTP_Module( 'settings', 'Duplicate', '1.0.0', static function (): void {} )
	),
	'Duplicate module registration should be rejected.'
);

SMTP_Module_Registry::boot_enabled();
$first_boot_order = $boot_order;
SMTP_Module_Registry::boot_enabled();

$statuses = SMTP_Module_Registry::statuses();

$assert( array( 'settings', 'dashboard' ) === $first_boot_order, 'Dependencies should boot in deterministic order.' );
$assert( $first_boot_order === $boot_order, 'A second boot cycle must not initialize modules again.' );
$assert( 'booted' === $statuses['settings']['status'], 'Settings should be booted.' );
$assert( 'booted' === $statuses['dashboard']['status'], 'Dashboard should be booted.' );
$assert( 'disabled' === $statuses['disabled']['status'], 'Disabled feature should be skipped.' );
$assert( 'failed' === $statuses['missing-dependency']['status'], 'Missing dependency should fail safely.' );
$assert( 'failed' === $statuses['missing-class']['status'], 'Missing runtime class should fail safely.' );
$assert( SMTP_Module_Registry::boot_complete(), 'Boot cycle should be marked complete.' );

fwrite( STDOUT, "PASS: module registry smoke test\n" );
