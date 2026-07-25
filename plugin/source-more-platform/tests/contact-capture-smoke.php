<?php
/**
 * Isolated smoke test for website contact form CRM capture.
 *
 * Run: php tests/contact-capture-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );

$GLOBALS['smtp_contact_meta']    = array();
$GLOBALS['smtp_contact_posts']   = array();
$GLOBALS['smtp_contact_mails']   = array();
$GLOBALS['smtp_contact_hooks']   = array();
$GLOBALS['smtp_contact_actions'] = array();

class WP_Error {
	private string $code;
	private string $message;
	private $data;
	public function __construct( string $code = '', string $message = '', $data = null ) {
		$this->code = $code;
		$this->message = $message;
		$this->data = $data;
	}
	public function get_error_message(): string { return $this->message; }
	public function get_error_data() { return $this->data; }
}

function is_wp_error( $value ): bool { return $value instanceof WP_Error; }
function sanitize_text_field( $value ): string { return trim( strip_tags( (string) $value ) ); }
function sanitize_textarea_field( $value ): string { return trim( strip_tags( (string) $value ) ); }
function sanitize_email( $value ): string { return filter_var( (string) $value, FILTER_SANITIZE_EMAIL ); }
function is_email( $value ): bool { return (bool) filter_var( $value, FILTER_VALIDATE_EMAIL ); }
function esc_url_raw( $value ): string { return trim( (string) $value ); }
function sanitize_key( $value ): string { return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $value ) ); }
function absint( $value ): int { return abs( (int) $value ); }
function current_time( $type ): string { return '2026-07-25 23:30:00'; }
function get_current_user_id(): int { return 0; }
function wp_get_referer(): string { return 'https://example.test/contact/'; }
function get_option( $key, $default = false ) { return 'admin@example.test'; }
function wp_insert_post( array $post, bool $wp_error = false ) {
	$id = count( $GLOBALS['smtp_contact_posts'] ) + 100;
	$GLOBALS['smtp_contact_posts'][ $id ] = $post;
	return $id;
}
function update_post_meta( $post_id, $key, $value ) {
	$GLOBALS['smtp_contact_meta'][ (int) $post_id ][ (string) $key ] = $value;
	return true;
}
function get_post_meta( $post_id, $key, $single = false ) {
	return $GLOBALS['smtp_contact_meta'][ (int) $post_id ][ (string) $key ] ?? '';
}
function get_post_type( $post_id ): string { return 'smt_fleet_lead'; }
function wp_mail( $to, $subject, $body, $headers = array() ): bool {
	$GLOBALS['smtp_contact_mails'][] = compact( 'to', 'subject', 'body', 'headers' );
	return true;
}
function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ): void {
	$GLOBALS['smtp_contact_hooks'][] = array( $hook, $callback, $priority, $accepted_args );
}
function remove_action( $hook, $callback, $priority = 10 ): bool {
	$GLOBALS['smtp_contact_actions'][] = 'removed:' . $hook;
	return true;
}
function do_action( string $hook, ...$args ): void { $GLOBALS['smtp_contact_actions'][] = $hook; }

final class SMTP_Settings {
	public static function get(): array { return array( 'notification_email' => 'sales@example.test' ); }
}
final class SMTP_Logger {
	public static function info( string $message, array $context = array() ): void {}
	public static function warning( string $message, array $context = array() ): void {}
	public static function error( string $message, array $context = array() ): void {}
}
final class SMTP_Rate_Limiter {
	public static function check( string $bucket, int $limit = 10, int $window = 600 ): bool { return true; }
}

$base = dirname( __DIR__ ) . '/';
require_once $base . 'includes/modules/crm/class-smtp-crm-content-types.php';
require_once $base . 'includes/modules/crm/class-smtp-crm-repository.php';
require_once $base . 'includes/modules/crm/class-smtp-crm-contact-capture.php';

$assert = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
};

$repository = new SMTP_CRM_Repository();
$capture    = new SMTP_CRM_Contact_Capture( $repository );
$capture->register_hooks();
$assert( 1 === count( $GLOBALS['smtp_contact_hooks'] ), 'Contact capture should register one deferred override hook.' );
$assert( 'after_setup_theme' === $GLOBALS['smtp_contact_hooks'][0][0], 'Theme handler replacement should wait for after_setup_theme.' );

$result = $capture->capture(
	array(
		'name'       => 'Mohamed Hakim',
		'company'    => 'Source More Test',
		'email'      => 'customer@example.test',
		'phone'      => '+20 100 000 0000',
		'interest'   => 'Managed Print Services',
		'message'    => 'Please contact us about a managed print assessment.',
		'consent'    => true,
		'source_url' => 'https://example.test/contact/',
	)
);

$assert( ! is_wp_error( $result ), 'A valid consultation request should create a CRM lead.' );
$lead_id = (int) $result['lead_id'];
$assert( 100 === $lead_id, 'First contact lead should use the generated post ID.' );
$assert( 'Source More Test — Mohamed Hakim' === $GLOBALS['smtp_contact_posts'][ $lead_id ]['post_title'], 'Lead title should include company and contact.' );
$assert( 'website-contact' === get_post_meta( $lead_id, 'lead_source', true ), 'Lead source should be Website Contact.' );
$assert( 'new' === get_post_meta( $lead_id, 'lead_status', true ), 'New contact leads should enter the New stage.' );
$assert( 'Managed Print Services' === get_post_meta( $lead_id, 'contact_interest', true ), 'Area of interest should be stored.' );
$assert( 'Please contact us about a managed print assessment.' === get_post_meta( $lead_id, 'contact_message', true ), 'Inquiry message should be stored.' );
$history = get_post_meta( $lead_id, 'activity_history', true );
$assert( 2 === count( $history ), 'Lead creation and website submission should create two activity events.' );
$assert( 2 === count( $GLOBALS['smtp_contact_mails'] ), 'Admin and customer emails should both be sent.' );
$assert( 'sales@example.test' === $GLOBALS['smtp_contact_mails'][0]['to'], 'Admin notification should use platform settings.' );
$assert( 'customer@example.test' === $GLOBALS['smtp_contact_mails'][1]['to'], 'Customer confirmation should use submitted email.' );

$invalid = $capture->capture(
	array(
		'name'    => '',
		'email'   => 'invalid',
		'message' => '',
		'consent' => false,
	)
);
$assert( is_wp_error( $invalid ), 'Invalid submissions should return a validation error.' );
$assert( 422 === $invalid->get_error_data()['status'], 'Validation error should use HTTP 422.' );

fwrite( STDOUT, "PASS: website contact CRM capture smoke test\n" );
