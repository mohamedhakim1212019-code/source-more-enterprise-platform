<?php
/**
 * Capture the enterprise theme contact form as a CRM lead.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_CRM_Contact_Capture {
	private SMTP_CRM_Repository $repository;
	private bool $registered = false;

	public function __construct( SMTP_CRM_Repository $repository ) {
		$this->repository = $repository;
	}

	/**
	 * Register the contact-form bridge once.
	 *
	 * The active theme registers its AJAX callback while its functions.php file is
	 * loaded. Waiting until after_setup_theme lets the platform safely replace that
	 * callback without editing the theme or changing the public AJAX contract.
	 */
	public function register_hooks(): void {
		if ( $this->registered ) {
			return;
		}

		add_action( 'after_setup_theme', array( $this, 'replace_theme_handler' ), 100 );
		$this->registered = true;
	}

	/**
	 * Replace the theme-only mail handler with CRM-aware capture.
	 */
	public function replace_theme_handler(): void {
		remove_action( 'wp_ajax_smt_contact_submit', 'smt_contact_submit' );
		remove_action( 'wp_ajax_nopriv_smt_contact_submit', 'smt_contact_submit' );

		add_action( 'wp_ajax_smt_contact_submit', array( $this, 'submit' ) );
		add_action( 'wp_ajax_nopriv_smt_contact_submit', array( $this, 'submit' ) );
	}

	/**
	 * Handle the existing theme AJAX request.
	 */
	public function submit(): void {
		if ( ! check_ajax_referer( 'smt_contact_submit', 'nonce', false ) ) {
			wp_send_json_error(
				array( 'message' => $this->translate( 'Security validation failed. Please refresh and try again.', 'فشل التحقق الأمني. حدّث الصفحة وحاول مرة أخرى.' ) ),
				403
			);
		}

		if ( ! SMTP_Rate_Limiter::check( 'website_contact', 5, 900 ) ) {
			wp_send_json_error(
				array( 'message' => $this->translate( 'Too many submissions. Please wait and try again.', 'تم إرسال عدد كبير من الطلبات. يرجى الانتظار ثم المحاولة مرة أخرى.' ) ),
				429
			);
		}

		$data = array(
			'name'     => isset( $_POST['name'] ) ? wp_unslash( $_POST['name'] ) : '',
			'company'  => isset( $_POST['company'] ) ? wp_unslash( $_POST['company'] ) : '',
			'email'    => isset( $_POST['email'] ) ? wp_unslash( $_POST['email'] ) : '',
			'phone'    => isset( $_POST['phone'] ) ? wp_unslash( $_POST['phone'] ) : '',
			'interest' => isset( $_POST['interest'] ) ? wp_unslash( $_POST['interest'] ) : '',
			'message'  => isset( $_POST['message'] ) ? wp_unslash( $_POST['message'] ) : '',
			'website'  => isset( $_POST['website'] ) ? wp_unslash( $_POST['website'] ) : '',
			'consent'  => ! empty( $_POST['consent'] ),
			'source_url' => wp_get_referer(),
		);

		$result = $this->capture( $data );
		if ( is_wp_error( $result ) ) {
			$status = (int) ( $result->get_error_data()['status'] ?? 500 );
			wp_send_json_error( array( 'message' => $result->get_error_message() ), $status );
		}

		wp_send_json_success(
			array(
				'message' => $this->translate( 'Thank you. A Source More consultant will contact you shortly.', 'شكرًا لك. سيتواصل معك أحد مستشاري سورس مور قريبًا.' ),
				'lead_id' => (int) $result['lead_id'],
			)
		);
	}

	/**
	 * Convert sanitized contact-form input into a CRM lead.
	 *
	 * @param array<string,mixed> $data Contact form data.
	 * @return array<string,mixed>|WP_Error
	 */
	public function capture( array $data ): array|WP_Error {
		if ( ! empty( $data['website'] ) ) {
			// Honeypot submissions receive a neutral success response and create no data.
			return array( 'lead_id' => 0, 'spam_ignored' => true );
		}

		$name     = sanitize_text_field( $data['name'] ?? '' );
		$company  = sanitize_text_field( $data['company'] ?? '' );
		$email    = sanitize_email( $data['email'] ?? '' );
		$phone    = sanitize_text_field( $data['phone'] ?? '' );
		$interest = sanitize_text_field( $data['interest'] ?? '' );
		$message  = sanitize_textarea_field( $data['message'] ?? '' );
		$consent  = ! empty( $data['consent'] );

		if ( '' === $name || ! is_email( $email ) || '' === $message || ! $consent ) {
			return new WP_Error(
				'validation',
				$this->translate( 'Please complete all required fields and accept the privacy notice.', 'يرجى استكمال الحقول المطلوبة والموافقة على إشعار الخصوصية.' ),
				array( 'status' => 422 )
			);
		}

		$values = array(
			'company'           => $company,
			'contact_name'      => $name,
			'email'             => $email,
			'phone'             => $phone,
			'industry'          => '',
			'locations'         => 0,
			'devices'           => 0,
			'mono_pages'        => 0,
			'color_pages'       => 0,
			'mono_cpp'          => 0,
			'color_cpp'         => 0,
			'fixed_cost'        => 0,
			'saving_rate'       => 0,
			'current_cost'      => 0,
			'annual_savings'    => 0,
			'optimized_cost'    => 0,
			'three_year'        => 0,
			'lead_status'       => 'new',
			'lead_source'       => 'website-contact',
			'source_url'        => esc_url_raw( $data['source_url'] ?? wp_get_referer() ),
			'consent_timestamp' => current_time( 'mysql' ),
			'last_activity'     => current_time( 'mysql' ),
			'assigned_to'       => 0,
			'contact_interest'  => $interest,
			'contact_message'   => $message,
		);

		$lead_id = $this->repository->create_lead( $values );
		if ( is_wp_error( $lead_id ) ) {
			SMTP_Logger::error( 'Website contact lead insert failed', array( 'error' => $lead_id->get_error_message() ) );
			return $lead_id;
		}

		$this->repository->add_activity(
			(int) $lead_id,
			'website_contact_submitted',
			'Consultation request submitted from the website contact form.',
			array( 'interest' => $interest )
		);

		$email_results = $this->send_notifications( (int) $lead_id, $values );
		SMTP_Logger::info(
			'Website contact lead created',
			array(
				'lead_id'       => (int) $lead_id,
				'admin_email'   => $email_results['admin'],
				'customer_email'=> $email_results['customer'],
			)
		);
		do_action( 'smtp_contact_lead_created', (int) $lead_id, $values, $email_results );

		return array(
			'success'       => true,
			'lead_id'       => (int) $lead_id,
			'email_results' => $email_results,
		);
	}

	/**
	 * Send both the internal notification and the customer confirmation.
	 *
	 * Mail delivery failure is logged but does not discard a successfully saved
	 * CRM lead.
	 *
	 * @param array<string,mixed> $values CRM lead values.
	 * @return array<string,bool>
	 */
	private function send_notifications( int $lead_id, array $values ): array {
		$options = SMTP_Settings::get();
		$admin   = sanitize_email( (string) ( $options['notification_email'] ?? get_option( 'admin_email' ) ) );
		if ( ! is_email( $admin ) ) {
			$admin = sanitize_email( get_option( 'admin_email' ) );
		}

		$company_label = '' !== $values['company'] ? $values['company'] : 'Not provided';
		$phone_label   = '' !== $values['phone'] ? $values['phone'] : 'Not provided';
		$interest      = '' !== $values['contact_interest'] ? $values['contact_interest'] : 'General consultation';
		$subject       = sprintf( 'New Website Consultation: %s — %s', $interest, $values['contact_name'] );
		$body          = "A visitor submitted the Request a Consultation form.\n\n";
		$body         .= 'Name: ' . $values['contact_name'] . "\n";
		$body         .= 'Company: ' . $company_label . "\n";
		$body         .= 'Email: ' . $values['email'] . "\n";
		$body         .= 'Phone: ' . $phone_label . "\n";
		$body         .= 'Area of interest: ' . $interest . "\n";
		$body         .= 'Source URL: ' . ( $values['source_url'] ?: 'Not available' ) . "\n";
		$body         .= "\nMessage:\n" . $values['contact_message'] . "\n\n";
		$body         .= 'CRM Lead ID: ' . $lead_id . "\n";

		$admin_headers = array( 'Content-Type: text/plain; charset=UTF-8' );
		$admin_headers[] = 'Reply-To: ' . $values['contact_name'] . ' <' . $values['email'] . '>';
		$admin_sent = is_email( $admin ) ? wp_mail( $admin, $subject, $body, $admin_headers ) : false;
		if ( ! $admin_sent ) {
			SMTP_Logger::warning( 'Website contact admin notification failed', array( 'lead_id' => $lead_id, 'recipient' => $admin ) );
		}

		$customer_subject = 'We received your consultation request — Source More Technology';
		$customer_body    = "Dear {$values['contact_name']},\n\n";
		$customer_body   .= "Thank you for contacting Source More Technology. We received your consultation request regarding {$interest}. A member of our solutions team will contact you shortly.\n\n";
		$customer_body   .= "Source More Technology\nOne Source, More Value.";
		$customer_headers = array( 'Content-Type: text/plain; charset=UTF-8' );
		if ( is_email( $admin ) ) {
			$customer_headers[] = 'Reply-To: Source More Technology <' . $admin . '>';
		}
		$customer_sent = wp_mail( $values['email'], $customer_subject, $customer_body, $customer_headers );
		if ( ! $customer_sent ) {
			SMTP_Logger::warning( 'Website contact customer confirmation failed', array( 'lead_id' => $lead_id, 'recipient' => $values['email'] ) );
		}

		return array(
			'admin'    => (bool) $admin_sent,
			'customer' => (bool) $customer_sent,
		);
	}

	private function translate( string $english, string $arabic ): string {
		return function_exists( 'smt_t' ) ? (string) smt_t( $english, $arabic ) : $english;
	}
}
