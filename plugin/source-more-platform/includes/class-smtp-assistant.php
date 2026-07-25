<?php
/**
 * Backward-compatible AI Assistant facade.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Assistant {
	public static function init(): void {
		SMTP_Assistant_Module::boot();
	}

	public static function routes(): void {
		SMTP_Assistant_Module::instance()->rest()->routes();
	}

	public static function assets(): void {
		SMTP_Assistant_Module::instance()->frontend()->assets();
	}

	public static function markup(): void {
		SMTP_Assistant_Module::instance()->frontend()->markup();
	}

	public static function reply( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		return SMTP_Assistant_Module::instance()->rest()->reply( $request );
	}

	public static function capture_lead( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		return SMTP_Assistant_Module::instance()->rest()->capture_lead( $request );
	}
}
