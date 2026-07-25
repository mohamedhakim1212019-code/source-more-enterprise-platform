<?php
/**
 * Backward-compatible Fleet REST facade.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SMTP_REST {
	public static function init(): void {
		SMTP_Fleet_Module::boot();
	}

	public static function routes(): void {
		SMTP_Fleet_Module::instance()->rest()->routes();
	}

	public static function lead( WP_REST_Request $request ) {
		return SMTP_Fleet_Module::instance()->rest()->create_lead( $request );
	}
}
