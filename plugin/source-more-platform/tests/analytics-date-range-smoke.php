<?php
/**
 * Isolated smoke test for Analytics date ranges.
 *
 * Run: php tests/analytics-date-range-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );

function sanitize_key( string $value ): string {
	return strtolower( preg_replace( '/[^a-z0-9_-]/', '', $value ) );
}
function current_time( string $format ): string {
	return '2026-07-25';
}

require_once dirname( __DIR__ ) . '/includes/modules/analytics/class-smtp-analytics-date-range.php';

$assert = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
};

$last_30 = SMTP_Analytics_Date_Range::from_request( array( 'range' => '30' ) );
$assert( '2026-06-26' === $last_30->from(), 'Last 30 days should use an inclusive 30-day period.' );
$assert( '2026-07-25' === $last_30->to(), 'Preset range should end today.' );
$assert( ! $last_30->is_all_time(), 'Preset range must be bounded.' );
$assert( 1 === count( $last_30->date_query() ), 'Bounded range should provide a WordPress date query.' );

$custom = SMTP_Analytics_Date_Range::from_request( array( 'range' => 'custom', 'from' => '2026-07-20', 'to' => '2026-07-01' ) );
$assert( '2026-07-01' === $custom->from() && '2026-07-20' === $custom->to(), 'Reversed custom dates should be normalized.' );
$assert( 'custom' === $custom->query_args()['range'], 'Custom range must retain its query identity.' );

$all = SMTP_Analytics_Date_Range::from_request( array( 'range' => 'all' ) );
$assert( $all->is_all_time(), 'All-time range must be unbounded.' );
$assert( array() === $all->date_query(), 'All-time range must not add a date query.' );

$invalid = SMTP_Analytics_Date_Range::from_request( array( 'range' => 'custom', 'from' => 'bad', 'to' => 'bad' ) );
$assert( '30' === $invalid->key(), 'Invalid custom dates should fall back to 30 days.' );

fwrite( STDOUT, "PASS: analytics date-range smoke test\n" );
