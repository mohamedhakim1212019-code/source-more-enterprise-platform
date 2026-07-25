<?php
/**
 * Isolated smoke test for controlled Analytics module boot.
 *
 * Run: php tests/analytics-module-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );
define( 'SMTP_PLATFORM_VERSION', '3.7.1' );
define( 'SMTP_PLATFORM_URL', 'https://example.test/wp-content/plugins/source-more-platform/' );

$GLOBALS['smtp_analytics_test_hooks'] = array();
$GLOBALS['smtp_analytics_actions']    = array();

function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ): void {
	$GLOBALS['smtp_analytics_test_hooks'][] = array( $hook, $callback, $priority, $accepted_args );
}
function do_action( string $hook, ...$args ): void {
	$GLOBALS['smtp_analytics_actions'][] = $hook;
}

final class SMTP_CRM_Repository {}
final class SMTP_CRM_Module {
	private static ?self $instance = null;
	private SMTP_CRM_Repository $repository;
	private function __construct() { $this->repository = new SMTP_CRM_Repository(); }
	public static function instance(): self { return self::$instance ??= new self(); }
	public function repository(): SMTP_CRM_Repository { return $this->repository; }
}

$base = dirname( __DIR__ ) . '/';
foreach ( array(
	'includes/modules/analytics/class-smtp-analytics-date-range.php',
	'includes/modules/analytics/class-smtp-analytics-repository.php',
	'includes/modules/analytics/class-smtp-analytics-service.php',
	'includes/modules/analytics/class-smtp-analytics-export.php',
	'includes/modules/analytics/class-smtp-analytics-pdf.php',
	'includes/modules/analytics/class-smtp-analytics-admin.php',
	'includes/modules/analytics/class-smtp-analytics-module.php',
) as $file ) {
	require_once $base . $file;
}

$assert = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
};

SMTP_Analytics_Module::boot();
$count = count( $GLOBALS['smtp_analytics_test_hooks'] );
SMTP_Analytics_Module::boot();

$assert( 4 === $count, 'Analytics module should register menu, assets, CSV export, and PDF export hooks.' );
$assert( $count === count( $GLOBALS['smtp_analytics_test_hooks'] ), 'Repeated boot must not duplicate Analytics hooks.' );
$assert( SMTP_Analytics_Module::is_booted(), 'Analytics module should report a booted state.' );
$assert( in_array( 'smtp_analytics_module_booted', $GLOBALS['smtp_analytics_actions'], true ), 'Analytics boot action should fire.' );

$hooks = array_map( static fn( array $hook ): string => $hook[0], $GLOBALS['smtp_analytics_test_hooks'] );
sort( $hooks );
$expected = array( 'admin_enqueue_scripts', 'admin_menu', 'admin_post_smtp_export_analytics', 'admin_post_smtp_export_analytics_pdf' );
sort( $expected );
$assert( $expected === $hooks, 'Analytics hook contract does not match the design.' );

fwrite( STDOUT, "PASS: analytics module smoke test ({$count} hooks registered)\n" );
