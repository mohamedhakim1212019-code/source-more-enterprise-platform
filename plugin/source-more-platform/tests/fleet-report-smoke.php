<?php
/**
 * Isolated smoke test for Fleet report-token validation.
 *
 * Run: php tests/fleet-report-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['smtp_report_meta'] = array();

function get_post_type( int $post_id ): string { return 10 === $post_id ? 'smt_fleet_lead' : 'post'; }
function get_post_meta( int $post_id, string $key, bool $single = false ) { return $GLOBALS['smtp_report_meta'][ $key ] ?? ''; }
function wp_check_password( string $token, string $hash ): bool { return 'valid-token' === $token && 'valid-hash' === $hash; }

require_once dirname( __DIR__ ) . '/includes/modules/crm/class-smtp-crm-content-types.php';
require_once dirname( __DIR__ ) . '/includes/modules/fleet/class-smtp-fleet-pdf.php';
require_once dirname( __DIR__ ) . '/includes/modules/fleet/class-smtp-fleet-report.php';

$report = new SMTP_Fleet_Report( new SMTP_Fleet_PDF() );
$assert = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
};

$GLOBALS['smtp_report_meta'] = array(
	'report_token_hash' => 'valid-hash',
	'report_expires'    => time() + 3600,
);
$assert( $report->valid( 10, 'valid-token' ), 'Hashed report token should be accepted before expiry.' );
$assert( ! $report->valid( 10, 'wrong-token' ), 'Incorrect hashed report token should be rejected.' );

$GLOBALS['smtp_report_meta'] = array(
	'report_token'   => 'legacy-token',
	'report_expires' => time() + 3600,
);
$assert( $report->valid( 10, 'legacy-token' ), 'Legacy plain report token should remain compatible.' );

$GLOBALS['smtp_report_meta']['report_expires'] = time() - 1;
$assert( ! $report->valid( 10, 'legacy-token' ), 'Expired report token should be rejected.' );
$assert( ! $report->valid( 11, 'legacy-token' ), 'Report token must only work for Fleet Lead records.' );

fwrite( STDOUT, "PASS: Fleet report token smoke test\n" );
