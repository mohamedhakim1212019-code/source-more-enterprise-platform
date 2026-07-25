<?php
/**
 * Isolated aggregation smoke test for Reports & Analytics.
 *
 * Run: php tests/analytics-service-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );

class SMTP_CRM_Content_Types { public const POST_TYPE = 'smt_fleet_lead'; }
class SMTP_Assistant_Content_Types { public const CONVERSATION_POST_TYPE = 'smt_ai_conversation'; }
class SMTP_Products_Content_Types { public const POST_TYPE = 'smt_product'; }

$GLOBALS['smtp_analytics_posts'] = array(
	'smt_fleet_lead' => array( 1, 2, 3 ),
	'smt_quote_request' => array( 11, 12 ),
	'smt_ai_conversation' => array( 21, 22 ),
	'smt_product' => array( 31, 32 ),
);
$GLOBALS['smtp_analytics_types'] = array( 1 => 'smt_fleet_lead', 2 => 'smt_fleet_lead', 3 => 'smt_fleet_lead', 11 => 'smt_quote_request', 12 => 'smt_quote_request', 21 => 'smt_ai_conversation', 22 => 'smt_ai_conversation' );
$GLOBALS['smtp_analytics_meta'] = array(
	1 => array( 'lead_status' => 'won', 'lead_source' => 'fleet-calculator', 'current_cost' => '100000', 'annual_savings' => '20000', 'three_year' => '60000', 'saving_rate' => '20', 'devices' => '10', 'assigned_to' => 1 ),
	2 => array( 'lead_status' => 'new', 'lead_source' => 'website-contact', 'assigned_to' => 0 ),
	3 => array( 'lead_status' => 'lost', 'lead_source' => 'ai-assistant', 'assigned_to' => 0 ),
	11 => array( '_smtp_status' => 'qualified', '_smtp_source' => 'product-quote-form', '_smtp_product_name' => 'Kyocera M3645idn', '_smtp_quantity' => '1', '_smtp_assigned_to' => 1 ),
	12 => array( '_smtp_status' => 'won', '_smtp_source' => 'product-quote-form', '_smtp_product_name' => 'Kyocera M3645idn', '_smtp_quantity' => '2', '_smtp_assigned_to' => 0 ),
	21 => array( '_smtp_assistant_status' => 'converted', '_smtp_assistant_lead_id' => 3, '_smtp_assistant_message_count' => 6 ),
	22 => array( '_smtp_assistant_status' => 'active', '_smtp_assistant_message_count' => 2 ),
);

function sanitize_key( string $value ): string { return strtolower( preg_replace( '/[^a-z0-9_-]/', '', $value ) ); }
function current_time( string $format ): string { return '2026-07-25'; }
function get_posts( array $args ): array { return $GLOBALS['smtp_analytics_posts'][ $args['post_type'] ] ?? array(); }
function get_post_type( int $id ): string { return $GLOBALS['smtp_analytics_types'][ $id ] ?? ''; }
function get_post_meta( int $id, string $key, bool $single = false ) { return $GLOBALS['smtp_analytics_meta'][ $id ][ $key ] ?? ''; }
function get_the_title( int $id ): string { return 'Record ' . $id; }
function get_post_field( string $field, int $id ): string { return sprintf( '2026-07-%02d 10:00:00', min( 25, $id ) ); }
function get_edit_post_link( int $id, string $context = 'display' ): string { return 'https://example.test/wp-admin/post.php?post=' . $id; }
function get_userdata( int $id ) { return 1 === $id ? (object) array( 'display_name' => 'Sales Owner' ) : false; }
function absint( $value ): int { return abs( (int) $value ); }
function get_current_user_id(): int { return 0; }

$base = dirname( __DIR__ ) . '/';
require_once $base . 'includes/modules/crm/class-smtp-crm-repository.php';
require_once $base . 'includes/modules/analytics/class-smtp-analytics-date-range.php';
require_once $base . 'includes/modules/analytics/class-smtp-analytics-repository.php';
require_once $base . 'includes/modules/analytics/class-smtp-analytics-service.php';

$crm        = new SMTP_CRM_Repository();
$repository = new SMTP_Analytics_Repository( $crm );
$service    = new SMTP_Analytics_Service( $repository, $crm );
$report     = $service->report( SMTP_Analytics_Date_Range::from_request( array( 'range' => 'all' ) ) );

$assert = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
};

$assert( 5 === $report['summary']['total_opportunities'], 'Report should aggregate Leads and Quote Requests.' );
$assert( 2 === $report['summary']['won'] && 1 === $report['summary']['lost'], 'Won and Lost totals are incorrect.' );
$assert( 66.7 === $report['summary']['closed_win_rate'], 'Closed win rate should be calculated from closed opportunities.' );
$assert( 2 === $report['summary']['published_products'], 'Published product count is incorrect.' );
$assert( 1 === $report['fleet']['assessments'], 'Fleet assessment detection is incorrect.' );
$assert( 20000.0 === $report['fleet']['annual_savings'], 'Fleet savings total is incorrect.' );
$assert( 2 === $report['assistant']['conversations'] && 1 === $report['assistant']['converted'], 'Assistant conversation metrics are incorrect.' );
$assert( 50.0 === $report['assistant']['conversion_rate'], 'Assistant conversion rate is incorrect.' );
$assert( 1 === count( $report['product_demand'] ), 'Product demand should group identical products.' );
$assert( 2 === $report['product_demand'][0]['requests'] && 3 === $report['product_demand'][0]['quantity'], 'Product demand aggregation is incorrect.' );
$assert( 5 === count( $report['recent'] ), 'Recent opportunity list should contain all five sample records.' );

fwrite( STDOUT, "PASS: analytics service aggregation smoke test\n" );
