<?php
/**
 * CRM REST endpoints.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_CRM_REST {
	private SMTP_CRM_Repository $repository;
	private SMTP_CRM_Notifications $notifications;
	private bool $registered = false;

	public function __construct( SMTP_CRM_Repository $repository, SMTP_CRM_Notifications $notifications ) {
		$this->repository    = $repository;
		$this->notifications = $notifications;
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

		$devices   = max( 1, $this->number( $data, 'devices' ) );
		$mono      = $this->number( $data, 'mono_pages' );
		$color     = $this->number( $data, 'color_pages' );
		$mono_cpp  = $this->number( $data, 'mono_cpp' );
		$color_cpp = $this->number( $data, 'color_cpp' );
		$fixed     = $this->number( $data, 'fixed_cost' );
		$rate      = min( 35, max( 5, $this->number( $data, 'saving_rate' ) ) );
		$current   = ( ( $mono * $mono_cpp ) + ( $color * $color_cpp ) + $fixed ) * 12;
		$savings   = $current * ( $rate / 100 );
		$optimized = $current - $savings;
		$three     = $savings * 3;
		$options   = SMTP_Settings::get();
		$token     = wp_generate_password( 48, false, false );

		$values = array(
			'company'           => $company,
			'contact_name'      => $name,
			'email'             => $email,
			'phone'             => $phone,
			'industry'          => sanitize_text_field( $data['industry'] ?? '' ),
			'locations'         => max( 1, absint( $data['locations'] ?? 1 ) ),
			'devices'           => $devices,
			'mono_pages'        => $mono,
			'color_pages'       => $color,
			'mono_cpp'          => $mono_cpp,
			'color_cpp'         => $color_cpp,
			'fixed_cost'        => $fixed,
			'saving_rate'       => $rate,
			'current_cost'      => round( $current, 2 ),
			'annual_savings'    => round( $savings, 2 ),
			'optimized_cost'    => round( $optimized, 2 ),
			'three_year'        => round( $three, 2 ),
			'lead_status'       => 'new',
			'lead_source'       => sanitize_key( $data['lead_source'] ?? 'fleet-calculator' ) ?: 'fleet-calculator',
			'source_url'        => esc_url_raw( $data['source_url'] ?? wp_get_referer() ),
			'consent_timestamp' => current_time( 'mysql' ),
			'last_activity'     => current_time( 'mysql' ),
			'assigned_to'       => 0,
			'report_token_hash' => wp_hash_password( $token ),
			'report_expires'    => time() + ( DAY_IN_SECONDS * max( 1, (int) ( $options['report_expiry_days'] ?? 30 ) ) ),
		);

		$lead_id = $this->repository->create_lead( $values );

		if ( is_wp_error( $lead_id ) ) {
			SMTP_Logger::error( 'Lead insert failed', array( 'error' => $lead_id->get_error_message() ) );
			return $lead_id;
		}

		$report_url = add_query_arg(
			array(
				'action' => 'smt_download_report',
				'lead'   => $lead_id,
				'token'  => $token,
			),
			admin_url( 'admin-post.php' )
		);

		$this->notifications->send_fleet_lead( $lead_id, $values, $report_url );
		SMTP_Logger::info( 'Fleet lead created', array( 'lead_id' => $lead_id, 'source' => $values['lead_source'] ) );
		do_action( 'smtp_platform_lead_created', $lead_id, $values );

		return new WP_REST_Response(
			array(
				'success'        => true,
				'lead_id'        => $lead_id,
				'report_url'     => esc_url_raw( $report_url ),
				'annual_savings' => $savings,
			),
			201
		);
	}

	/**
	 * Backward-compatible alias used by SMTP_REST.
	 */
	public function lead( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		return $this->create_lead( $request );
	}

	private function number( array $data, string $key ): float {
		return max( 0, (float) ( $data[ $key ] ?? 0 ) );
	}
}
