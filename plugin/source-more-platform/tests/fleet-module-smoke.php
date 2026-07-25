<?php
/**
 * Isolated smoke test for the Fleet Assessment module.
 *
 * Run: php tests/fleet-module-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );
define( 'SMTP_PLATFORM_VERSION', '3.7.0' );
define( 'SMTP_PLATFORM_URL', 'https://example.test/wp-content/plugins/source-more-platform/' );

$GLOBALS['smtp_fleet_test_hooks']         = array();
$GLOBALS['smtp_fleet_test_actions_fired'] = array();
$GLOBALS['smtp_fleet_test_routes']        = array();

function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ): void {
	$GLOBALS['smtp_fleet_test_hooks'][] = array( 'action', $hook, $callback, $priority, $accepted_args );
}
function add_shortcode( $tag, $callback ): void {
	$GLOBALS['smtp_fleet_test_hooks'][] = array( 'shortcode', $tag, $callback, 10, 2 );
}
function do_action( string $hook, ...$args ): void {
	$GLOBALS['smtp_fleet_test_actions_fired'][] = $hook;
}
function register_rest_route( string $namespace, string $route, array $args = array() ): void {
	$GLOBALS['smtp_fleet_test_routes'][] = $namespace . $route;
}
function apply_filters( string $hook, $value, ...$args ) { return $value; }

$base = dirname( __DIR__ ) . '/';
$files = array(
	'includes/modules/crm/class-smtp-crm-content-types.php',
	'includes/modules/crm/class-smtp-crm-repository.php',
	'includes/modules/crm/class-smtp-crm-admin.php',
	'includes/modules/crm/class-smtp-crm-contact-capture.php',
	'includes/modules/crm/class-smtp-crm-module.php',
	'includes/modules/fleet/class-smtp-fleet-calculator.php',
	'includes/modules/fleet/class-smtp-fleet-pdf.php',
	'includes/modules/fleet/class-smtp-fleet-report.php',
	'includes/modules/fleet/class-smtp-fleet-notifications.php',
	'includes/modules/fleet/class-smtp-fleet-frontend.php',
	'includes/modules/fleet/class-smtp-fleet-rest.php',
	'includes/modules/fleet/class-smtp-fleet-module.php',
	'includes/modules/crm/class-smtp-crm-notifications.php',
	'includes/modules/crm/class-smtp-crm-frontend.php',
	'includes/modules/crm/class-smtp-crm-rest.php',
	'includes/class-smtp-pdf.php',
	'includes/class-smtp-leads.php',
	'includes/class-smtp-rest.php',
);
foreach ( $files as $file ) {
	require_once $base . $file;
}

$assert = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
};

SMTP_Fleet_Module::boot();
$first_hook_count = count( $GLOBALS['smtp_fleet_test_hooks'] );
SMTP_Fleet_Module::boot();
SMTP_REST::init();

$assert( 5 === $first_hook_count, 'Fleet module should register five controlled hooks.' );
$assert( $first_hook_count === count( $GLOBALS['smtp_fleet_test_hooks'] ), 'Repeated Fleet or REST initialization must not duplicate hooks.' );
$assert( SMTP_Fleet_Module::is_booted(), 'Fleet module should report a booted state.' );
$assert( in_array( 'smtp_fleet_module_booted', $GLOBALS['smtp_fleet_test_actions_fired'], true ), 'Fleet module boot action should fire.' );

$hook_contract = array_map(
	static fn( array $hook ): string => $hook[0] . ':' . $hook[1],
	$GLOBALS['smtp_fleet_test_hooks']
);
$expected = array(
	'action:admin_post_nopriv_smt_download_report',
	'action:admin_post_smt_download_report',
	'action:rest_api_init',
	'action:wp_enqueue_scripts',
	'shortcode:smt_fleet_lead_form',
);
sort( $hook_contract );
sort( $expected );
$assert( $expected === $hook_contract, 'Fleet hook contract does not match the module design.' );

SMTP_REST::routes();
sort( $GLOBALS['smtp_fleet_test_routes'] );
$assert(
	array( 'source-more/v1/lead', 'source-more/v2/lead' ) === $GLOBALS['smtp_fleet_test_routes'],
	'Both legacy Fleet Lead REST namespaces must remain available.'
);

foreach ( array( 'init', 'routes', 'lead' ) as $method ) {
	$assert( method_exists( 'SMTP_REST', $method ), 'REST compatibility facade is missing method: ' . $method );
}
foreach ( array( 'assets', 'shortcode', 'download_report' ) as $method ) {
	$assert( method_exists( 'SMTP_Leads', $method ), 'Lead compatibility facade is missing Fleet method: ' . $method );
}
$assert( method_exists( 'SMTP_Simple_PDF', 'output_lead' ), 'PDF compatibility facade must remain available.' );
$assert( method_exists( 'SMTP_CRM_REST', 'create_lead' ), 'Former CRM REST class must remain available as a facade.' );
$assert( method_exists( 'SMTP_CRM_Frontend', 'shortcode' ), 'Former CRM frontend class must remain available as a facade.' );
$assert( method_exists( 'SMTP_CRM_Notifications', 'send_fleet_lead' ), 'Former CRM notification class must remain available as a facade.' );

fwrite( STDOUT, "PASS: Fleet module smoke test ({$first_hook_count} hooks registered)\n" );
