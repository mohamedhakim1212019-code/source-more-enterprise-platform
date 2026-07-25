<?php
/**
 * Isolated smoke test for the AI Assistant module.
 *
 * Run: php tests/assistant-module-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );
define( 'SMTP_PLATFORM_VERSION', '3.7.1' );
define( 'SMTP_PLATFORM_URL', 'https://example.test/wp-content/plugins/source-more-platform/' );

$GLOBALS['smtp_assistant_test_hooks']         = array();
$GLOBALS['smtp_assistant_test_routes']        = array();
$GLOBALS['smtp_assistant_test_post_types']    = array();
$GLOBALS['smtp_assistant_test_actions_fired'] = array();

function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ): void {
	$GLOBALS['smtp_assistant_test_hooks'][] = array( 'action', $hook, $callback, $priority, $accepted_args );
}
function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ): void {
	$GLOBALS['smtp_assistant_test_hooks'][] = array( 'filter', $hook, $callback, $priority, $accepted_args );
}
function do_action( string $hook, ...$args ): void {
	$GLOBALS['smtp_assistant_test_actions_fired'][] = $hook;
}
function register_rest_route( string $namespace, string $route, array $args = array() ): void {
	$GLOBALS['smtp_assistant_test_routes'][] = $namespace . $route;
}
function register_post_type( string $post_type, array $args = array() ): void {
	$GLOBALS['smtp_assistant_test_post_types'][ $post_type ] = $args;
}

$base = dirname( __DIR__ ) . '/';
$files = array(
	'includes/modules/assistant/class-smtp-assistant-content-types.php',
	'includes/modules/assistant/class-smtp-assistant-conversations.php',
	'includes/modules/assistant/class-smtp-assistant-knowledge.php',
	'includes/modules/assistant/class-smtp-assistant-endpoint.php',
	'includes/modules/assistant/class-smtp-assistant-service.php',
	'includes/modules/assistant/class-smtp-assistant-lead-converter.php',
	'includes/modules/assistant/class-smtp-assistant-rest.php',
	'includes/modules/assistant/class-smtp-assistant-frontend.php',
	'includes/modules/assistant/class-smtp-assistant-admin.php',
	'includes/modules/assistant/class-smtp-assistant-module.php',
	'includes/class-smtp-assistant.php',
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

SMTP_Assistant_Module::boot();
$first_hook_count = count( $GLOBALS['smtp_assistant_test_hooks'] );
SMTP_Assistant_Module::boot();
SMTP_Assistant::init();

$assert( 13 === $first_hook_count, 'Assistant module should register thirteen controlled hooks.' );
$assert( $first_hook_count === count( $GLOBALS['smtp_assistant_test_hooks'] ), 'Repeated Assistant initialization must not duplicate hooks.' );
$assert( SMTP_Assistant_Module::is_booted(), 'Assistant module should report a booted state.' );
$assert( in_array( 'smtp_assistant_module_booted', $GLOBALS['smtp_assistant_test_actions_fired'], true ), 'Assistant module boot action should fire.' );

$hook_contract = array_map(
	static fn( array $hook ): string => $hook[0] . ':' . $hook[1],
	$GLOBALS['smtp_assistant_test_hooks']
);
$expected = array(
	'action:add_meta_boxes',
	'action:init',
	'action:init',
	'action:manage_smt_ai_conversation_posts_custom_column',
	'action:manage_smt_ai_knowledge_posts_custom_column',
	'action:rest_api_init',
	'action:save_post_smt_ai_knowledge',
	'action:smtp_assistant_cleanup',
	'action:wp_enqueue_scripts',
	'action:wp_footer',
	'filter:manage_smt_ai_conversation_posts_columns',
	'filter:manage_smt_ai_knowledge_posts_columns',
	'filter:post_row_actions',
);
sort( $hook_contract );
sort( $expected );
$assert( $expected === $hook_contract, 'Assistant hook contract does not match the module design.' );

SMTP_Assistant_Module::register_content_types();
$assert( isset( $GLOBALS['smtp_assistant_test_post_types']['smt_ai_knowledge'] ), 'AI Knowledge post type must be registered.' );
$assert( isset( $GLOBALS['smtp_assistant_test_post_types']['smt_ai_conversation'] ), 'AI Conversation post type must be registered.' );
$assert( 'smtp-platform' === $GLOBALS['smtp_assistant_test_post_types']['smt_ai_knowledge']['show_in_menu'], 'AI Knowledge must remain under the platform menu.' );
$assert( 'smtp-platform' === $GLOBALS['smtp_assistant_test_post_types']['smt_ai_conversation']['show_in_menu'], 'AI Conversations must remain under the platform menu.' );

SMTP_Assistant::routes();
sort( $GLOBALS['smtp_assistant_test_routes'] );
$assert(
	array(
		'source-more/v1/assistant',
		'source-more/v2/assistant',
		'source-more/v2/assistant/lead',
		'source-more/v3/assistant/lead',
	) === $GLOBALS['smtp_assistant_test_routes'],
	'Assistant reply and lead routes must remain available.'
);

foreach ( array( 'init', 'routes', 'assets', 'markup', 'reply', 'capture_lead' ) as $method ) {
	$assert( method_exists( 'SMTP_Assistant', $method ), 'Assistant compatibility facade is missing method: ' . $method );
}

fwrite( STDOUT, "PASS: Assistant module smoke test ({$first_hook_count} hooks registered)\n" );
