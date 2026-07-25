<?php
/**
 * Isolated behavior test for CRM status, assignment, source, and activity metadata.
 *
 * Run: php tests/crm-repository-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );

$GLOBALS['smtp_crm_meta'] = array();
$GLOBALS['smtp_crm_types'] = array(
	10 => 'smt_fleet_lead',
	20 => 'smt_quote_request',
);

function sanitize_key( $value ): string {
	return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $value ) );
}

function sanitize_text_field( $value ): string {
	return trim( strip_tags( (string) $value ) );
}

function absint( $value ): int {
	return abs( (int) $value );
}

function current_time( $type ): string {
	return '2026-07-25 20:30:00';
}

function get_current_user_id(): int {
	return 99;
}

function get_post_type( $post_id ): string {
	return $GLOBALS['smtp_crm_types'][ (int) $post_id ] ?? '';
}

function get_post_meta( $post_id, $key, $single = false ) {
	return $GLOBALS['smtp_crm_meta'][ (int) $post_id ][ (string) $key ] ?? '';
}

function update_post_meta( $post_id, $key, $value ) {
	$post_id = (int) $post_id;
	$key = (string) $key;
	$previous = $GLOBALS['smtp_crm_meta'][ $post_id ][ $key ] ?? null;
	$GLOBALS['smtp_crm_meta'][ $post_id ][ $key ] = $value;
	return $previous === $value ? false : true;
}

function get_userdata( $user_id ) {
	return (object) array( 'display_name' => 'User ' . (int) $user_id );
}

require_once dirname( __DIR__ ) . '/includes/modules/crm/class-smtp-crm-content-types.php';
require_once dirname( __DIR__ ) . '/includes/modules/crm/class-smtp-crm-repository.php';

$assert = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
};

$repository = new SMTP_CRM_Repository();
update_post_meta( 10, 'lead_status', 'new' );
$repository->update_status( 10, 'qualified', 5 );
$repository->assign( 10, 7, 5 );

$assert( 'qualified' === get_post_meta( 10, 'lead_status', true ), 'Lead status should use the existing lead_status key.' );
$assert( 7 === get_post_meta( 10, 'assigned_to', true ), 'Lead assignment should use the new assigned_to key.' );
$assert( '2026-07-25 20:30:00' === get_post_meta( 10, 'last_activity', true ), 'Lead last activity should be updated.' );
$lead_history = get_post_meta( 10, 'activity_history', true );
$assert( 2 === count( $lead_history ), 'Lead status and assignment should create two activity events.' );
$assert( 'status_changed' === $lead_history[0]['event'], 'First lead activity should record the status change.' );
$assert( 'assignment_changed' === $lead_history[1]['event'], 'Second lead activity should record assignment.' );
$assert( 'User 7' === $repository->owner_name( 10 ), 'Owner name should resolve from the assigned WordPress user.' );

update_post_meta( 20, '_smtp_status', 'new' );
$repository->initialize_quote_request( 20, array( 'product_id' => 123 ) );
$repository->update_status( 20, 'proposal', 5 );
$repository->assign( 20, 8, 5 );

$assert( 'product-quote-form' === get_post_meta( 20, '_smtp_source', true ), 'Quote Request source should use Product Quote Form.' );
$assert( 'proposal' === get_post_meta( 20, '_smtp_status', true ), 'Quote Request status should preserve the _smtp_status key.' );
$assert( 8 === get_post_meta( 20, '_smtp_assigned_to', true ), 'Quote Request assignment should use the prefixed key.' );
$quote_history = get_post_meta( 20, '_smtp_activity_history', true );
$assert( 3 === count( $quote_history ), 'Quote creation, status, and assignment should create three activity events.' );
$assert( 'quote_request_created' === $quote_history[0]['event'], 'Quote activity should start with quote creation.' );

fwrite( STDOUT, "PASS: CRM repository activity smoke test\n" );
