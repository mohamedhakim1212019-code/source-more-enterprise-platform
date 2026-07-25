<?php
/**
 * Backward-compatible Fleet REST facade.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_CRM_REST {
	public function __construct( ?SMTP_CRM_Repository $repository = null, ?SMTP_CRM_Notifications $notifications = null ) {
		// Constructor arguments are retained for source compatibility.
	}

	public function register_hooks(): void {
		SMTP_Fleet_Module::instance()->rest()->register_hooks();
	}

	public function routes(): void {
		SMTP_Fleet_Module::instance()->rest()->routes();
	}

	public function create_lead( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		return SMTP_Fleet_Module::instance()->rest()->create_lead( $request );
	}

	public function lead( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		return $this->create_lead( $request );
	}
}
