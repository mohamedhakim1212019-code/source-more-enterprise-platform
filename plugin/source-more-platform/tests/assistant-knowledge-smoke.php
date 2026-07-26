<?php
/**
 * Isolated smoke test for deterministic AI knowledge matching.
 *
 * Run: php tests/assistant-knowledge-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );

function get_posts( array $args = array() ): array { return array(); }
function wp_strip_all_tags( string $text ): string { return strip_tags( $text ); }
function remove_accents( string $text ): string { return $text; }
function absint( $value ): int { return abs( (int) $value ); }
function sanitize_key( string $key ): string { return strtolower( preg_replace( '/[^a-z0-9_\-]/', '', $key ) ); }
function apply_filters( string $hook, $value, ...$args ) { return $value; }
function sanitize_title( string $value ): string { return strtolower( preg_replace( '/[^a-z0-9\-]+/', '-', $value ) ); }
function is_rtl(): bool { return false; }

require_once dirname( __DIR__ ) . '/includes/core/class-smtp-i18n.php';
require_once dirname( __DIR__ ) . '/includes/modules/assistant/class-smtp-assistant-content-types.php';
require_once dirname( __DIR__ ) . '/includes/modules/assistant/class-smtp-assistant-knowledge.php';

$assert = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
};

$knowledge = new SMTP_Assistant_Knowledge();
$mps       = $knowledge->answer( 'How does Managed Print Services help our printer fleet?' );
$savings   = $knowledge->answer( 'I want to calculate print savings.' );
$contact   = $knowledge->answer( 'Can someone from sales call me for an assessment?' );
$general   = $knowledge->answer( 'Tell me about Source More.' );
$arabic    = $knowledge->answer( 'كيف تساعد خدمات الطباعة المدارة في خفض التكلفة؟', 'ar' );

$assert( 'built-in-mps' === $mps['source'], 'MPS question should match the built-in MPS entry.' );
$assert( 'calculator' === $savings['action'], 'Savings question should return the calculator action.' );
$assert( 'lead_capture' === $contact['action'], 'Sales contact question should return the lead-capture action.' );
$assert( 'built-in-general-en' === $general['source'], 'General question should use the service overview fallback.' );
$assert( 'built-in-mps-ar' === $arabic['source'], 'Arabic MPS question should use the Arabic knowledge entry.' );

fwrite( STDOUT, "PASS: Assistant knowledge smoke test\n" );
