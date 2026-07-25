<?php
/**
 * Fleet report access tokens and download delivery.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Fleet_Report {
	private SMTP_Fleet_PDF $pdf;
	private bool $registered = false;

	public function __construct( SMTP_Fleet_PDF $pdf ) {
		$this->pdf = $pdf;
	}

	public function register_hooks(): void {
		if ( $this->registered ) {
			return;
		}

		add_action( 'admin_post_smt_download_report', array( $this, 'download' ) );
		add_action( 'admin_post_nopriv_smt_download_report', array( $this, 'download' ) );
		$this->registered = true;
	}

	/**
	 * Create a temporary public report token and URL.
	 *
	 * @return array{token:string,token_hash:string,expires:int,url:string}
	 */
	public function create_access( int $lead_id ): array {
		$options = SMTP_Settings::get();
		$token   = wp_generate_password( 48, false, false );
		$expires = time() + ( DAY_IN_SECONDS * max( 1, (int) ( $options['report_expiry_days'] ?? 30 ) ) );

		return array(
			'token'      => $token,
			'token_hash' => wp_hash_password( $token ),
			'expires'    => $expires,
			'url'        => $this->url( $lead_id, $token ),
		);
	}

	public function url( int $lead_id, string $token ): string {
		return add_query_arg(
			array(
				'action' => 'smt_download_report',
				'lead'   => $lead_id,
				'token'  => $token,
			),
			admin_url( 'admin-post.php' )
		);
	}

	public function download(): void {
		$lead_id = absint( $_GET['lead'] ?? 0 );
		$token   = sanitize_text_field( wp_unslash( $_GET['token'] ?? '' ) );

		if ( ! $this->valid( $lead_id, $token ) ) {
			wp_die( 'Invalid or expired report link.', 'Report unavailable', array( 'response' => 403 ) );
		}

		$this->pdf->output_lead( $lead_id );
		exit;
	}

	public function valid( int $lead_id, string $token ): bool {
		if ( ! $lead_id || '' === $token || SMTP_CRM_Content_Types::POST_TYPE !== get_post_type( $lead_id ) ) {
			return false;
		}

		$hash    = (string) get_post_meta( $lead_id, 'report_token_hash', true );
		$legacy  = (string) get_post_meta( $lead_id, 'report_token', true );
		$expires = (int) get_post_meta( $lead_id, 'report_expires', true );
		$valid   = ( $hash && wp_check_password( $token, $hash ) ) || ( $legacy && hash_equals( $legacy, $token ) );

		return (bool) $valid && ( ! $expires || time() <= $expires );
	}

	public function output_lead( int $lead_id ): void {
		$this->pdf->output_lead( $lead_id );
	}
}
