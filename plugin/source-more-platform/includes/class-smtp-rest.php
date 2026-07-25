<?php
/**
 * Backward-compatible CRM REST facade.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SMTP_REST {
	public static function init(): void {
		SMTP_CRM_Module::instance()->rest()->register_hooks();
	}

	public static function routes(): void {
		SMTP_CRM_Module::instance()->rest()->routes();
	}

	public static function lead( WP_REST_Request $request ) {
		return SMTP_CRM_Module::instance()->rest()->create_lead( $request );
	}
}
