<?php
/**
 * Convert an AI Assistant conversation into a CRM lead.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Assistant_Lead_Converter {
	private SMTP_Assistant_Conversations $conversations;

	public function __construct( SMTP_Assistant_Conversations $conversations ) {
		$this->conversations = $conversations;
	}

	/**
	 * @param array<string,mixed> $data Lead form payload.
	 * @return array<string,mixed>|WP_Error
	 */
	public function convert( array $data ): array|WP_Error {
		$options = SMTP_Settings::get();
		if ( empty( $options['assistant_lead_capture'] ) ) {
			return new WP_Error( 'lead_capture_disabled', 'Assistant lead capture is disabled.', array( 'status' => 403 ) );
		}

		if ( ! class_exists( 'SMTP_CRM_Module' ) || ! SMTP_Modules::enabled( 'crm' ) ) {
			return new WP_Error( 'crm_unavailable', 'CRM lead capture is not available.', array( 'status' => 503 ) );
		}

		if ( ! empty( $data['website'] ) ) {
			return new WP_Error( 'spam', 'Invalid submission.', array( 'status' => 400 ) );
		}

		$company = sanitize_text_field( $data['company'] ?? '' );
		$name    = sanitize_text_field( $data['contact_name'] ?? '' );
		$email   = sanitize_email( $data['email'] ?? '' );
		$phone   = sanitize_text_field( $data['phone'] ?? '' );
		$message = sanitize_textarea_field( $data['message'] ?? '' );

		if ( '' === $company || '' === $name || ! is_email( $email ) || '' === $phone || empty( $data['consent'] ) ) {
			return new WP_Error( 'validation', 'Please complete the required business and consent fields.', array( 'status' => 422 ) );
		}

		$conversation_id    = absint( $data['conversation_id'] ?? 0 );
		$conversation_token = sanitize_text_field( $data['conversation_token'] ?? '' );
		$identity           = array( 'conversation_id' => 0, 'conversation_token' => '' );

		if ( ! empty( $options['assistant_conversation_logging'] ) ) {
			$identity = $this->conversations->resolve( $conversation_id, $conversation_token );
			if ( is_wp_error( $identity ) ) {
				return $identity;
			}
		}

		$values = array(
			'company'                   => $company,
			'contact_name'              => $name,
			'email'                     => $email,
			'phone'                     => $phone,
			'industry'                  => '',
			'locations'                 => 1,
			'devices'                   => 0,
			'mono_pages'                => 0,
			'color_pages'               => 0,
			'mono_cpp'                  => 0,
			'color_cpp'                 => 0,
			'fixed_cost'                => 0,
			'saving_rate'               => 0,
			'current_cost'              => 0,
			'annual_savings'            => 0,
			'optimized_cost'            => 0,
			'three_year'                => 0,
			'lead_status'               => 'new',
			'lead_source'               => 'ai-assistant',
			'source_url'                => esc_url_raw( $data['source_url'] ?? wp_get_referer() ),
			'consent_timestamp'         => current_time( 'mysql' ),
			'last_activity'             => current_time( 'mysql' ),
			'assigned_to'               => 0,
			'assistant_message'         => $message,
			'assistant_conversation_id' => (int) $identity['conversation_id'],
		);

		$repository = SMTP_CRM_Module::instance()->repository();
		$lead_id    = $repository->create_lead( $values );
		if ( is_wp_error( $lead_id ) ) {
			SMTP_Logger::error( 'Assistant lead insert failed', array( 'error' => $lead_id->get_error_message() ) );
			return $lead_id;
		}

		$repository->add_activity(
			(int) $lead_id,
			'assistant_conversation_converted',
			'AI Assistant conversation converted into a CRM lead.',
			array( 'conversation_id' => (int) $identity['conversation_id'] )
		);

		if ( (int) $identity['conversation_id'] > 0 ) {
			if ( '' !== $message ) {
				$this->conversations->add_message( (int) $identity['conversation_id'], 'user', 'Contact request: ' . $message, 'lead-form' );
			}
			$this->conversations->link_lead( (int) $identity['conversation_id'], (int) $lead_id, $values );
			$this->conversations->add_message( (int) $identity['conversation_id'], 'system', 'Contact request saved as CRM lead #' . $lead_id . '.', 'crm' );
		}

		$this->send_notifications( (int) $lead_id, $values );
		SMTP_Logger::info( 'Assistant lead created', array( 'lead_id' => $lead_id, 'conversation_id' => (int) $identity['conversation_id'] ) );
		do_action( 'smtp_assistant_lead_created', $lead_id, $values, (int) $identity['conversation_id'] );

		return array(
			'success'         => true,
			'lead_id'         => (int) $lead_id,
			'message'         => 'Thank you. The Source More team will contact you shortly.',
			'conversation_id'    => (int) $identity['conversation_id'],
			'conversation_token' => (string) $identity['conversation_token'],
		);
	}

	/**
	 * @param array<string,mixed> $values Sanitized lead values.
	 */
	private function send_notifications( int $lead_id, array $values ): void {
		$options = SMTP_Settings::get();
		$admin   = sanitize_email( (string) ( $options['notification_email'] ?? get_option( 'admin_email' ) ) );
		$subject = 'New AI Assistant Lead: ' . $values['company'];
		$body    = "A visitor requested contact through the Source More AI Assistant.\n\n";
		$body   .= 'Company: ' . $values['company'] . "\n";
		$body   .= 'Contact: ' . $values['contact_name'] . "\n";
		$body   .= 'Email: ' . $values['email'] . "\n";
		$body   .= 'Phone: ' . $values['phone'] . "\n";
		$body   .= 'Message: ' . ( $values['assistant_message'] ?: 'Not provided' ) . "\n";
		$body   .= 'CRM Lead ID: ' . $lead_id . "\n";

		$admin_sent = $admin ? wp_mail( $admin, $subject, $body, array( 'Reply-To: ' . $values['contact_name'] . ' <' . $values['email'] . '>' ) ) : false;
		if ( ! $admin_sent ) {
			SMTP_Logger::warning( 'Assistant admin notification failed', array( 'lead_id' => $lead_id, 'recipient' => $admin ) );
		}

		$customer_subject = 'We received your request — Source More Technology';
		$customer_body    = "Dear {$values['contact_name']},\n\nThank you for contacting Source More Technology. Our team has received your request and will contact you shortly.\n\nSource More Technology\nOne Source, More Value.";
		$customer_sent    = wp_mail( $values['email'], $customer_subject, $customer_body, $admin ? array( 'Reply-To: Source More Technology <' . $admin . '>' ) : array() );
		if ( ! $customer_sent ) {
			SMTP_Logger::warning( 'Assistant customer confirmation failed', array( 'lead_id' => $lead_id, 'recipient' => $values['email'] ) );
		}
	}
}
