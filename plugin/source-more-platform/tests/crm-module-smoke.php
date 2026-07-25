<?php
/**
 * Isolated smoke test for the CRM module after Fleet separation.
 *
 * Run: php tests/crm-module-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );
define( 'SMTP_PLATFORM_VERSION', '3.7.0' );
define( 'SMTP_PLATFORM_URL', 'https://example.test/wp-content/plugins/source-more-platform/' );

$GLOBALS['smtp_crm_test_hooks']         = array();
$GLOBALS['smtp_crm_test_post_types']    = array();
$GLOBALS['smtp_crm_test_actions_fired'] = array();

function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ): void {
	$GLOBALS['smtp_crm_test_hooks'][] = array( 'action', $hook, $callback, $priority, $accepted_args );
}
function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ): void {
	$GLOBALS['smtp_crm_test_hooks'][] = array( 'filter', $hook, $callback, $priority, $accepted_args );
}
function do_action( string $hook, ...$args ): void {
	$GLOBALS['smtp_crm_test_actions_fired'][] = $hook;
}
function register_post_type( string $post_type, array $args = array() ): void {
	$GLOBALS['smtp_crm_test_post_types'][ $post_type ] = $args;
}
function flush_rewrite_rules(): void {}

$base  = dirname( __DIR__ ) . '/';
$files = array(
	'includes/modules/crm/class-smtp-crm-content-types.php',
	'includes/modules/crm/class-smtp-crm-repository.php',
	'includes/modules/crm/class-smtp-crm-admin.php',
	'includes/modules/crm/class-smtp-crm-contact-capture.php',
	'includes/modules/crm/class-smtp-crm-module.php',
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

$assert( 13 === $first_hook_count, 'CRM module should register thirteen controlled hooks including contact capture.' );
$assert( $first_hook_count === count( $GLOBALS['smtp_crm_test_hooks'] ), 'Repeated CRM initialization must not duplicate hooks.' );
$assert( SMTP_CRM_Module::is_booted(), 'CRM module should report a booted state.' );
$assert( in_array( 'smtp_crm_module_booted', $GLOBALS['smtp_crm_test_actions_fired'], true ), 'CRM module boot action should fire.' );

$hook_contract = array_map(
	static fn( array $hook ): string => $hook[0] . ':' . $hook[1],
	$GLOBALS['smtp_crm_test_hooks']
);
$expected = array(
	'action:add_meta_boxes',
	'action:after_setup_theme',
	'action:admin_menu',
	'action:admin_post_smt_export_leads',
	'action:init',
	'action:manage_smt_fleet_lead_posts_custom_column',
	'action:pre_get_posts',
	'action:restrict_manage_posts',
	'action:save_post_smt_fleet_lead',
	'action:save_post_smt_quote_request',
	'action:smtp_product_quote_request_created',
	'filter:manage_smt_fleet_lead_posts_columns',
	'filter:post_row_actions',
);
sort( $hook_contract );
sort( $expected );
$assert( $expected === $hook_contract, 'CRM hook contract does not match the post-refactor design.' );

SMTP_CRM_Module::register_content_types();
$assert( isset( $GLOBALS['smtp_crm_test_post_types']['smt_fleet_lead'] ), 'Fleet Lead post type must retain its original key.' );
$assert( 'smtp-platform' === $GLOBALS['smtp_crm_test_post_types']['smt_fleet_lead']['show_in_menu'], 'Fleet Leads must remain under the platform admin menu.' );

fwrite( STDOUT, "PASS: CRM module smoke test ({$first_hook_count} hooks registered)\n" );
