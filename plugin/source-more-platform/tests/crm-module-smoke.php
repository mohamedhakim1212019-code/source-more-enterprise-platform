<?php
/**
 * Isolated smoke test for the refactored CRM and Fleet Leads module.
 *
 * Run: php tests/crm-module-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );
define( 'SMTP_PLATFORM_VERSION', '3.4.1' );
define( 'SMTP_PLATFORM_URL', 'https://example.test/wp-content/plugins/source-more-platform/' );

$GLOBALS['smtp_crm_test_hooks']         = array();
$GLOBALS['smtp_crm_test_post_types']    = array();
$GLOBALS['smtp_crm_test_actions_fired'] = array();
$GLOBALS['smtp_crm_test_routes']        = array();

function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ): void {
	$GLOBALS['smtp_crm_test_hooks'][] = array( 'action', $hook, $callback, $priority, $accepted_args );
}

function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ): void {
	$GLOBALS['smtp_crm_test_hooks'][] = array( 'filter', $hook, $callback, $priority, $accepted_args );
}

function add_shortcode( $tag, $callback ): void {
	$GLOBALS['smtp_crm_test_hooks'][] = array( 'shortcode', $tag, $callback, 10, 2 );
}

function do_action( string $hook, ...$args ): void {
	$GLOBALS['smtp_crm_test_actions_fired'][] = $hook;
}

function register_post_type( string $post_type, array $args = array() ): void {
	$GLOBALS['smtp_crm_test_post_types'][ $post_type ] = $args;
}

function register_rest_route( string $namespace, string $route, array $args = array() ): void {
	$GLOBALS['smtp_crm_test_routes'][] = $namespace . $route;
}

function flush_rewrite_rules(): void {}

$base  = dirname( __DIR__ ) . '/';
$files = array(
	'includes/modules/crm/class-smtp-crm-content-types.php',
	'includes/modules/crm/class-smtp-crm-repository.php',
	'includes/modules/crm/class-smtp-crm-notifications.php',
	'includes/modules/crm/class-smtp-crm-admin.php',
	'includes/modules/crm/class-smtp-crm-frontend.php',
	'includes/modules/crm/class-smtp-crm-rest.php',
	'includes/modules/crm/class-smtp-crm-module.php',
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

SMTP_CRM_Module::boot();
$first_hook_count = count( $GLOBALS['smtp_crm_test_hooks'] );
SMTP_CRM_Module::boot();
SMTP_Leads::init();
SMTP_REST::init();

$assert( 16 === $first_hook_count, 'CRM module should register sixteen controlled hooks.' );
$assert( $first_hook_count === count( $GLOBALS['smtp_crm_test_hooks'] ), 'Repeated CRM, facade, or REST initialization must not duplicate hooks.' );
$assert( SMTP_CRM_Module::is_booted(), 'CRM module should report a booted state.' );
$assert( in_array( 'smtp_crm_module_booted', $GLOBALS['smtp_crm_test_actions_fired'], true ), 'CRM module boot action should fire.' );

$hook_contract = array_map(
	static fn( array $hook ): string => $hook[0] . ':' . $hook[1],
	$GLOBALS['smtp_crm_test_hooks']
);
$expected = array(
	'action:add_meta_boxes',
	'action:admin_menu',
	'action:admin_post_smt_download_report',
	'action:admin_post_smt_export_leads',
	'action:init',
	'action:manage_smt_fleet_lead_posts_custom_column',
	'action:pre_get_posts',
	'action:rest_api_init',
	'action:restrict_manage_posts',
	'action:save_post_smt_fleet_lead',
	'action:save_post_smt_quote_request',
	'action:smtp_product_quote_request_created',
	'action:wp_enqueue_scripts',
	'filter:manage_smt_fleet_lead_posts_columns',
	'filter:post_row_actions',
	'shortcode:smt_fleet_lead_form',
);
sort( $hook_contract );
sort( $expected );
$assert( $expected === $hook_contract, 'CRM hook contract does not match the controlled module design.' );

SMTP_Leads::register_post_type();
$assert( isset( $GLOBALS['smtp_crm_test_post_types']['smt_fleet_lead'] ), 'Fleet Lead post type must retain its original key.' );
$assert( 'smtp-platform' === $GLOBALS['smtp_crm_test_post_types']['smt_fleet_lead']['show_in_menu'], 'Fleet Leads must remain under the platform admin menu.' );
$assert( 'smt_fleet_lead' === SMTP_Leads::POST_TYPE, 'SMTP_Leads facade constant must remain compatible.' );

SMTP_REST::routes();
sort( $GLOBALS['smtp_crm_test_routes'] );
$assert(
	array( 'source-more/v1/lead', 'source-more/v2/lead' ) === $GLOBALS['smtp_crm_test_routes'],
	'Both legacy Fleet Lead REST namespaces must remain available.'
);

$legacy_methods = array(
	'activate',
	'init',
	'register_post_type',
	'assets',
	'shortcode',
	'columns',
	'column_content',
	'row_actions',
	'meta_boxes',
	'meta_box',
	'save_status',
	'submenu',
	'export_page',
	'export_csv',
	'download_report',
);

foreach ( $legacy_methods as $method ) {
	$assert( method_exists( 'SMTP_Leads', $method ), 'CRM compatibility facade is missing method: ' . $method );
}

foreach ( array( 'init', 'routes', 'lead' ) as $method ) {
	$assert( method_exists( 'SMTP_REST', $method ), 'REST compatibility facade is missing method: ' . $method );
}

fwrite( STDOUT, "PASS: CRM module smoke test ({$first_hook_count} hooks registered)\n" );
