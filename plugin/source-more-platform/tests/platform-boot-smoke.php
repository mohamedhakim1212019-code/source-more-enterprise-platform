<?php
/**
 * Isolated smoke test for the default SMEP runtime module map.
 *
 * Run: php tests/platform-boot-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );
define( 'SMTP_PLATFORM_VERSION', '3.3.0' );
define( 'SMTP_PLATFORM_DB_VERSION', '3.1.0' );
define( 'SMTP_PLATFORM_FILE', __FILE__ );
define( 'SMTP_PLATFORM_DIR', dirname( __DIR__ ) . '/' );
define( 'SMTP_PLATFORM_URL', 'https://example.test/wp-content/plugins/source-more-platform/' );

$GLOBALS['smtp_test_hooks'] = array();

function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ): void {
	$GLOBALS['smtp_test_hooks'][] = array( 'action', $hook, $callback, $priority, $accepted_args );
}

function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ): void {
	$GLOBALS['smtp_test_hooks'][] = array( 'filter', $hook, $callback, $priority, $accepted_args );
}

function add_shortcode( $tag, $callback ): void {
	$GLOBALS['smtp_test_hooks'][] = array( 'shortcode', $tag, $callback, 10, 2 );
}

function do_action( string $hook, ...$args ): void {
	// External action callbacks are outside this isolated smoke test.
}

function apply_filters( string $hook, $value, ...$args ) {
	return $value;
}

function get_option( $key, $default = false ) {
	return $default;
}

function wp_parse_args( $args, $defaults = array() ): array {
	return array_merge( $defaults, is_array( $args ) ? $args : array() );
}

function get_privacy_policy_url(): string {
	return 'https://example.test/privacy/';
}

require_once SMTP_PLATFORM_DIR . 'includes/core/class-smtp-module.php';
require_once SMTP_PLATFORM_DIR . 'includes/core/class-smtp-module-registry.php';

$runtime_files = array(
	'includes/class-smtp-modules.php',
	'includes/class-smtp-logger.php',
	'includes/class-smtp-rate-limiter.php',
	'includes/class-smtp-pdf.php',
	'includes/class-smtp-dashboard.php',
	'includes/modules/products/class-smtp-products-content-types.php',
	'includes/modules/products/class-smtp-products-repository.php',
	'includes/modules/products/class-smtp-products-admin.php',
	'includes/modules/products/class-smtp-products-frontend.php',
	'includes/modules/products/class-smtp-products-rest.php',
	'includes/modules/products/class-smtp-products-module.php',
	'includes/class-smtp-products.php',
	'includes/class-smtp-leads.php',
	'includes/class-smtp-settings.php',
	'includes/class-smtp-rest.php',
	'includes/class-smtp-assistant.php',
	'includes/class-smtp-diagnostics.php',
	'includes/class-smtp-platform.php',
);

foreach ( $runtime_files as $runtime_file ) {
	require_once SMTP_PLATFORM_DIR . $runtime_file;
}

SMTP_Platform::init();
$statuses = SMTP_Module_Registry::statuses();

foreach ( array( 'settings', 'dashboard', 'leads', 'products', 'rest', 'assistant', 'diagnostics' ) as $module_id ) {
	if ( 'booted' !== ( $statuses[ $module_id ]['status'] ?? '' ) ) {
		fwrite( STDERR, 'FAIL: ' . $module_id . ' did not boot.' . PHP_EOL );
		exit( 1 );
	}
}

$hook_count = count( $GLOBALS['smtp_test_hooks'] );
SMTP_Platform::init();

if ( count( $GLOBALS['smtp_test_hooks'] ) !== $hook_count ) {
	fwrite( STDERR, 'FAIL: repeated platform initialization registered duplicate hooks.' . PHP_EOL );
	exit( 1 );
}

fwrite( STDOUT, sprintf( "PASS: platform boot smoke test (%d hooks registered)\n", $hook_count ) );
