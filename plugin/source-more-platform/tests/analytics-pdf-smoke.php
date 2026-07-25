<?php
/**
 * Isolated smoke test for branded Analytics PDF generation.
 *
 * Run: php tests/analytics-pdf-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );

function wp_strip_all_tags( $text ): string {
	return strip_tags( (string) $text );
}

function sanitize_key( string $value ): string {
	return strtolower( preg_replace( '/[^a-z0-9_-]/', '', $value ) );
}

function current_time( string $format ): string {
	return '2026-07-26';
}

final class SMTP_Analytics_Service {}

$base = dirname( __DIR__ ) . '/';
require_once $base . 'includes/modules/analytics/class-smtp-analytics-date-range.php';
require_once $base . 'includes/modules/analytics/class-smtp-analytics-pdf.php';

$counts = array( 'new' => 2, 'contacted' => 1, 'qualified' => 1, 'proposal' => 1, 'won' => 1, 'lost' => 1 );
$report = array(
	'summary' => array(
		'total_opportunities' => 14,
		'open_opportunities'  => 10,
		'won'                 => 3,
		'lost'                => 1,
		'closed_win_rate'     => 75.0,
		'published_products'  => 6,
	),
	'lead_pipeline' => array( 'total' => 7, 'counts' => $counts, 'open' => 5, 'won' => 1, 'lost' => 1, 'closed_win_rate' => 50.0 ),
	'quote_pipeline' => array( 'total' => 7, 'counts' => $counts, 'open' => 5, 'won' => 2, 'lost' => 0, 'closed_win_rate' => 100.0 ),
	'sources' => array(
		array( 'label' => 'Fleet Calculator', 'total' => 5, 'won' => 2, 'lost' => 1, 'closed_win_rate' => 66.7 ),
		array( 'label' => 'Website Contact', 'total' => 4, 'won' => 1, 'lost' => 0, 'closed_win_rate' => 100.0 ),
		array( 'label' => 'AI Assistant', 'total' => 3, 'won' => 0, 'lost' => 0, 'closed_win_rate' => 0.0 ),
	),
	'fleet' => array(
		'assessments' => 5,
		'devices' => 120,
		'current_annual_cost' => 1450000,
		'annual_savings' => 348000,
		'three_year_savings' => 1044000,
		'average_savings' => 69600,
		'average_saving_rate' => 24.0,
	),
	'assistant' => array( 'conversations' => 24, 'converted' => 5, 'conversion_rate' => 20.8, 'messages' => 132, 'average_messages' => 5.5 ),
	'product_demand' => array(
		array( 'product' => 'Kyocera ECOSYS M3645idn', 'requests' => 4, 'quantity' => 7 ),
		array( 'product' => 'Enterprise Print Management Software', 'requests' => 3, 'quantity' => 3 ),
	),
	'recent' => array(),
);

for ( $index = 1; $index <= 18; $index++ ) {
	$report['recent'][] = array(
		'date' => '2026-07-' . str_pad( (string) min( $index, 28 ), 2, '0', STR_PAD_LEFT ),
		'title' => 'Opportunity ' . $index . ' - Enterprise Managed Print Assessment',
		'type' => 0 === $index % 2 ? 'Quote' : 'Lead',
		'status' => 0 === $index % 3 ? 'qualified' : 'new',
		'source' => 0 === $index % 2 ? 'Product Quote Form' : 'Fleet Calculator',
		'owner' => 'Sales Owner',
	);
}

$pdf_service = new SMTP_Analytics_PDF( new SMTP_Analytics_Service() );
$range       = SMTP_Analytics_Date_Range::from_request( array( 'range' => 'all' ) );
$pdf         = $pdf_service->render( $report, $range );

$assert = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
};

$assert( str_starts_with( $pdf, '%PDF-1.4' ), 'PDF header is missing.' );
$assert( str_contains( $pdf, 'Reports & Analytics' ), 'Report title is missing from PDF content.' );
$assert( str_contains( $pdf, 'EGP 348,000' ), 'EGP Fleet value is missing from PDF content.' );
$assert( substr_count( $pdf, '/Type /Page ' ) >= 2, 'The long report should paginate across multiple pages.' );
$assert( str_contains( $pdf, '%%EOF' ), 'PDF trailer is missing.' );

$file = sys_get_temp_dir() . '/smep-analytics-smoke.pdf';
file_put_contents( $file, $pdf );
$assert( filesize( $file ) > 5000, 'Generated PDF is unexpectedly small.' );
@unlink( $file );

fwrite( STDOUT, "PASS: analytics PDF smoke test\n" );
