<?php
/**
 * Fleet Assessment REST endpoints.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Fleet_REST {
	private SMTP_CRM_Repository $repository;
	private SMTP_Fleet_Calculator $calculator;
	private SMTP_Fleet_Notifications $notifications;
	private SMTP_Fleet_Report $report;
	private bool $registered = false;

	public function __construct(
		SMTP_CRM_Repository $repository,
		SMTP_Fleet_Calculator $calculator,
		SMTP_Fleet_Notifications $notifications,
		SMTP_Fleet_Report $report
	) {
		$this->repository    = $repository;
		$this->calculator    = $calculator;
		$this->notifications = $notifications;
		$this->report        = $report;
	}

	public function register_hooks(): void {
		if ( $this->registered ) {
			return;
		}

		add_action( 'rest_api_init', array( $this, 'routes' ) );
		$this->registered = true;
	}

	public function routes(): void {
		foreach ( array( 'source-more/v1', 'source-more/v2' ) as $namespace ) {
			register_rest_route(
				$namespace,
				'/lead',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'create_lead' ),
					'permission_callback' => '__return_true',
				)
			);
		}
	}

	public function create_lead( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		if ( ! wp_verify_nonce( $request->get_header( 'X-WP-Nonce' ), 'wp_rest' ) ) {
			return new WP_Error( 'bad_nonce', 'Security check failed.', array( 'status' => 403 ) );
		}

		if ( ! SMTP_Rate_Limiter::check( 'lead', 5, 900 ) ) {
			return new WP_Error( 'rate_limited', 'Too many submissions. Please try again later.', array( 'status' => 429 ) );
		}

		$data = $request->get_json_params();
		$data = is_array( $data ) ? $data : $request->get_params();

		if ( ! empty( $data['website'] ) ) {
			return new WP_Error( 'spam', 'Invalid submission.', array( 'status' => 400 ) );
		}

		$company = sanitize_text_field( $data['company'] ?? '' );
		$name    = sanitize_text_field( $data['contact_name'] ?? '' );
		$email   = sanitize_email( $data['email'] ?? '' );
		$phone   = sanitize_text_field( $data['phone'] ?? '' );

		if ( ! $company || ! $name || ! is_email( $email ) || ! $phone || empty( $data['consent'] ) ) {
			return new WP_Error( 'validation', 'Please complete the required business and consent fields.', array( 'status' => 422 ) );
		}

		$calculation = $this->calculator->calculate( $data );
		$values      = array_merge(
			$calculation,
			array(
				'company'           => $company,
				'contact_name'      => $name,
				'email'             => $email,
				'phone'             => $phone,
				'industry'          => sanitize_text_field( $data['industry'] ?? '' ),
				'locations'         => max( 1, absint( $data['locations'] ?? 1 ) ),
				'lead_status'       => 'new',
				'lead_source'       => sanitize_key( $data['lead_source'] ?? 'fleet-calculator' ) ?: 'fleet-calculator',
				'source_url'        => esc_url_raw( $data['source_url'] ?? wp_get_referer() ),
				'consent_timestamp' => current_time( 'mysql' ),
				'last_activity'     => current_time( 'mysql' ),
				'assigned_to'       => 0,
			)
		);

		$lead_id = $this->repository->create_lead( $values );

		if ( is_wp_error( $lead_id ) ) {
			SMTP_Logger::error( 'Lead insert failed', array( 'error' => $lead_id->get_error_message() ) );
			return $lead_id;
		}

		$access = $this->report->create_access( (int) $lead_id );
		update_post_meta( $lead_id, 'report_token_hash', $access['token_hash'] );
		update_post_meta( $lead_id, 'report_expires', $access['expires'] );

		$this->notifications->send( (int) $lead_id, $values, $access['url'] );
		SMTP_Logger::info( 'Fleet lead created', array( 'lead_id' => $lead_id, 'source' => $values['lead_source'] ) );
		do_action( 'smtp_platform_lead_created', $lead_id, $values );
		do_action( 'smtp_fleet_assessment_created', $lead_id, $values, $calculation );

		return new WP_REST_Response(
			array(
				'success'        => true,
				'lead_id'        => $lead_id,
				'report_url'     => esc_url_raw( $access['url'] ),
				'annual_savings' => $calculation['annual_savings'],
			),
			201
		);
	}

	/**
	 * Backward-compatible alias used by SMTP_REST and SMTP_CRM_REST.
	 */
	public function lead( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		return $this->create_lead( $request );
	}
}
