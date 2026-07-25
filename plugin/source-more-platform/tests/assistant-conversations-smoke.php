<?php
/**
 * Isolated smoke test for assistant conversation identities and messages.
 *
 * Run: php tests/assistant-conversations-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );

define( 'DAY_IN_SECONDS', 86400 );
$GLOBALS['smtp_ai_posts'] = array();
$GLOBALS['smtp_ai_meta']  = array();
$GLOBALS['smtp_ai_next']  = 1;

class WP_Error {
	private string $code;
	public function __construct( string $code ) { $this->code = $code; }
	public function get_error_code(): string { return $this->code; }
}
function is_wp_error( $value ): bool { return $value instanceof WP_Error; }
function wp_generate_password( int $length, bool $special = true, bool $extra = false ): string { return str_repeat( 'x', $length ); }
function current_time( string $format ): string { return 'mysql' === $format ? '2026-07-25 12:00:00' : '2026-07-25 12:00'; }
function wp_insert_post( array $post, bool $wp_error = false ) {
	$id = $GLOBALS['smtp_ai_next']++;
	$post['ID'] = $id;
	$GLOBALS['smtp_ai_posts'][ $id ] = (object) $post;
	return $id;
}
function wp_update_post( array $post ) {
	$id = (int) $post['ID'];
	foreach ( $post as $key => $value ) {
		if ( 'ID' !== $key ) $GLOBALS['smtp_ai_posts'][ $id ]->$key = $value;
	}
	return $id;
}
function update_post_meta( int $id, string $key, $value ): bool { $GLOBALS['smtp_ai_meta'][ $id ][ $key ] = $value; return true; }
function get_post_meta( int $id, string $key, bool $single = false ) { return $GLOBALS['smtp_ai_meta'][ $id ][ $key ] ?? ''; }
function get_post_type( int $id ): string { return $GLOBALS['smtp_ai_posts'][ $id ]->post_type ?? ''; }
function wp_strip_all_tags( string $text ): string { return strip_tags( $text ); }
function sanitize_key( string $key ): string { return strtolower( preg_replace( '/[^a-z0-9_\-]/', '', $key ) ); }
function sanitize_text_field( string $text ): string { return trim( strip_tags( $text ) ); }
function sanitize_email( string $email ): string { return $email; }
function absint( $value ): int { return abs( (int) $value ); }
function wp_salt( string $scheme = 'auth' ): string { return 'test-salt'; }
function get_posts( array $args = array() ): array { return array(); }
function wp_delete_post( int $id, bool $force = false ): void { unset( $GLOBALS['smtp_ai_posts'][ $id ], $GLOBALS['smtp_ai_meta'][ $id ] ); }
class SMTP_Settings { public static function get(): array { return array( 'assistant_retention_days' => 90 ); } }

require_once dirname( __DIR__ ) . '/includes/modules/assistant/class-smtp-assistant-content-types.php';
require_once dirname( __DIR__ ) . '/includes/modules/assistant/class-smtp-assistant-conversations.php';

$assert = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
};

$repo     = new SMTP_Assistant_Conversations();
$identity = $repo->create();
$assert( ! is_wp_error( $identity ), 'Conversation should be created.' );
$assert( $repo->verify( $identity['conversation_id'], $identity['conversation_token'] ), 'Conversation token should verify.' );
$assert( ! $repo->verify( $identity['conversation_id'], 'wrong-token' ), 'Incorrect token must not verify.' );

$repo->add_message( $identity['conversation_id'], 'user', 'Tell me about MPS.', 'visitor' );
$repo->add_message( $identity['conversation_id'], 'assistant', 'Managed Print Services...', 'built-in-mps' );
$assert( 2 === $repo->message_count( $identity['conversation_id'] ), 'Conversation should count stored messages.' );
$assert( 2 === count( $repo->messages( $identity['conversation_id'] ) ), 'Conversation should return stored messages.' );

$repo->link_lead( $identity['conversation_id'], 55, array( 'company' => 'Test Company', 'email' => 'test@example.com' ) );
$assert( 55 === $repo->lead_id( $identity['conversation_id'] ), 'Conversation should link to the CRM lead.' );
$assert( 'converted' === $repo->status( $identity['conversation_id'] ), 'Linked conversation should be marked converted.' );

fwrite( STDOUT, "PASS: Assistant conversation smoke test\n" );
