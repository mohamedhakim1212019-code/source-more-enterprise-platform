<?php
/**
 * Isolated smoke test for the refactored Product Center module.
 *
 * Run: php tests/products-module-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );
define( 'SMTP_PLATFORM_VERSION', '3.6.1' );
define( 'SMTP_PLATFORM_URL', 'https://example.test/wp-content/plugins/source-more-platform/' );

$GLOBALS['smtp_product_test_hooks']         = array();
$GLOBALS['smtp_product_test_post_types']    = array();
$GLOBALS['smtp_product_test_taxonomies']    = array();
$GLOBALS['smtp_product_test_actions_fired'] = array();
$GLOBALS['smtp_product_test_routes']        = array();

function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ): void {
	$GLOBALS['smtp_product_test_hooks'][] = array( 'action', $hook, $callback, $priority, $accepted_args );
}

function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ): void {
	$GLOBALS['smtp_product_test_hooks'][] = array( 'filter', $hook, $callback, $priority, $accepted_args );
}

function add_shortcode( $tag, $callback ): void {
	$GLOBALS['smtp_product_test_hooks'][] = array( 'shortcode', $tag, $callback, 10, 2 );
}

function do_action( string $hook, ...$args ): void {
	$GLOBALS['smtp_product_test_actions_fired'][] = $hook;
}

function apply_filters( string $hook, $value, ...$args ) {
	return $value;
}

function register_post_type( string $post_type, array $args = array() ): void {
	$GLOBALS['smtp_product_test_post_types'][ $post_type ] = $args;
}

function register_taxonomy( string $taxonomy, $object_type, array $args = array() ): void {
	$GLOBALS['smtp_product_test_taxonomies'][ $taxonomy ] = array(
		'object_type' => $object_type,
		'args'        => $args,
	);
}

function register_rest_route( string $namespace, string $route, array $args = array() ): void {
	$GLOBALS['smtp_product_test_routes'][] = $namespace . $route;
}

$base = dirname( __DIR__ ) . '/';
$files = array(
	'includes/modules/products/class-smtp-products-content-types.php',
	'includes/modules/products/class-smtp-products-repository.php',
	'includes/modules/products/class-smtp-products-admin.php',
	'includes/modules/products/class-smtp-products-frontend.php',
	'includes/modules/products/class-smtp-products-rest.php',
	'includes/modules/products/class-smtp-products-module.php',
	'includes/class-smtp-products.php',
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

SMTP_Products_Module::boot();
$first_hook_count = count( $GLOBALS['smtp_product_test_hooks'] );
SMTP_Products_Module::boot();
SMTP_Products::init();

$assert( 12 === $first_hook_count, 'Product module should register the same twelve public hooks as the legacy class.' );
$assert( $first_hook_count === count( $GLOBALS['smtp_product_test_hooks'] ), 'Repeated module or facade initialization must not duplicate hooks.' );
$assert( SMTP_Products_Module::is_booted(), 'Product module should report a booted state.' );
$assert( in_array( 'smtp_products_module_booted', $GLOBALS['smtp_product_test_actions_fired'], true ), 'Product module boot action should fire.' );

$hook_contract = array_map(
	static fn( array $hook ): string => $hook[0] . ':' . $hook[1],
	$GLOBALS['smtp_product_test_hooks']
);
$expected_hook_contract = array(
	'action:add_meta_boxes',
	'action:init',
	'action:manage_smt_product_posts_custom_column',
	'action:manage_smt_quote_request_posts_custom_column',
	'action:rest_api_init',
	'action:save_post_smt_product',
	'action:save_post_smt_quote_request',
	'action:wp_enqueue_scripts',
	'filter:manage_smt_product_posts_columns',
	'filter:manage_smt_quote_request_posts_columns',
	'shortcode:smtp_products',
	'shortcode:source_more_quote_form',
);
sort( $hook_contract );
sort( $expected_hook_contract );
$assert( $expected_hook_contract === $hook_contract, 'The public Product Center hook contract must remain unchanged.' );

$shortcodes = array();
foreach ( $GLOBALS['smtp_product_test_hooks'] as $hook ) {
	if ( 'shortcode' === $hook[0] ) {
		$shortcodes[] = $hook[1];
	}
}

sort( $shortcodes );
$assert( array( 'smtp_products', 'source_more_quote_form' ) === $shortcodes, 'Legacy shortcode tags must remain registered.' );

SMTP_Products::register();
$assert( isset( $GLOBALS['smtp_product_test_post_types']['smt_product'] ), 'Product post type must retain its original key.' );
$assert( isset( $GLOBALS['smtp_product_test_post_types']['smt_quote_request'] ), 'Quote-request post type must retain its original key.' );
$assert( isset( $GLOBALS['smtp_product_test_taxonomies']['smt_product_category'] ), 'Product category taxonomy must retain its original key.' );
$assert( isset( $GLOBALS['smtp_product_test_taxonomies']['smt_product_brand'] ), 'Brand taxonomy must retain its original key.' );
$assert( 'products' === $GLOBALS['smtp_product_test_post_types']['smt_product']['has_archive'], 'Product archive slug must remain products.' );
$assert( 'products' === $GLOBALS['smtp_product_test_post_types']['smt_product']['rewrite']['slug'], 'Product rewrite slug must remain products.' );
$assert( 'smtp-platform' === $GLOBALS['smtp_product_test_post_types']['smt_product']['show_in_menu'], 'Products must remain under the platform admin menu.' );
$assert( 'product-category' === $GLOBALS['smtp_product_test_taxonomies']['smt_product_category']['args']['rewrite']['slug'], 'Product category rewrite must remain compatible.' );
$assert( 'brand' === $GLOBALS['smtp_product_test_taxonomies']['smt_product_brand']['args']['rewrite']['slug'], 'Brand rewrite must remain compatible.' );
$assert( 'smt_product' === SMTP_Products::POST_TYPE, 'Facade product constant must remain compatible.' );
$assert( 'smt_quote_request' === SMTP_Products::RFQ_TYPE, 'Facade quote-request constant must remain compatible.' );

SMTP_Products::routes();
sort( $GLOBALS['smtp_product_test_routes'] );
$assert(
	array( 'source-more/v2/quote-request', 'source-more/v3/quote-request' ) === $GLOBALS['smtp_product_test_routes'],
	'Both legacy quote-request REST namespaces must remain available.'
);

$legacy_methods = array(
	'init',
	'register',
	'meta_boxes',
	'product_box',
	'save_product',
	'rfq_box',
	'save_rfq',
	'product_columns',
	'product_column',
	'rfq_columns',
	'rfq_column',
	'assets',
	'products_shortcode',
	'quote_shortcode',
	'routes',
	'create_quote',
);

foreach ( $legacy_methods as $method ) {
	$assert( method_exists( 'SMTP_Products', $method ), 'Compatibility facade is missing method: ' . $method );
}

fwrite( STDOUT, "PASS: products module smoke test ({$first_hook_count} hooks registered)\n" );
