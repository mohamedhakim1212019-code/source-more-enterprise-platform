<?php
/**
 * CRM email notifications.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_CRM_Notifications {
	/**
	 * Send internal and customer notifications for a Fleet Assessment lead.
	 *
	 * @param array<string, mixed> $data Validated lead data.
	 * @return array<string, bool>
	 */
	public function send_fleet_lead( int $lead_id, array $data, string $report_url ): array {
		$options = SMTP_Settings::get();
		$to      = sanitize_email( $options['notification_email'] ?? get_option( 'admin_email' ) );

		if ( ! is_email( $to ) ) {
			$to = sanitize_email( get_option( 'admin_email' ) );
		}

		$admin_subject = 'New Fleet Assessment Lead: ' . $data['company'];
		$admin_body    = "A new fleet savings lead has been submitted.\n\nCompany: {$data['company']}\nContact: {$data['contact_name']}\nEmail: {$data['email']}\nPhone: {$data['phone']}\nIndustry: {$data['industry']}\nDevices: {$data['devices']}\nEstimated annual savings: EGP " . number_format( (float) $data['annual_savings'], 0 ) . "\n\nView lead: " . admin_url( 'post.php?post=' . $lead_id . '&action=edit' ) . "\nReport: {$report_url}";
		$admin_headers = array( 'Reply-To: ' . $data['contact_name'] . ' <' . $data['email'] . '>' );
		$admin_sent    = is_email( $to ) && wp_mail( $to, $admin_subject, $admin_body, $admin_headers );

		$customer_body = "Dear {$data['contact_name']},\n\nThank you for using the Source More Technology Fleet Savings Calculator.\n\nYour estimated annual savings are EGP " . number_format( (float) $data['annual_savings'], 0 ) . ".\n\nDownload your report: {$report_url}\n\nThis indicative estimate should be validated through a professional fleet assessment.\n\nSource More Technology\nOne Source, More Value";
		$customer_headers = array();

		if ( is_email( $to ) ) {
			$customer_headers[] = 'Reply-To: Source More Technology <' . $to . '>';
		}

		$customer_sent = wp_mail(
			$data['email'],
			'Your Fleet Savings Report — Source More Technology',
			$customer_body,
			$customer_headers
		);

		SMTP_Logger::info(
			'Fleet lead notifications processed',
			array(
				'lead_id'             => $lead_id,
				'notification_email'  => $to,
				'admin_email_sent'    => (bool) $admin_sent,
				'customer_email_sent' => (bool) $customer_sent,
			)
		);

		if ( ! $admin_sent ) {
			SMTP_Logger::warning( 'Admin lead email failed', array( 'lead_id' => $lead_id, 'recipient' => $to ) );
		}

		if ( ! $customer_sent ) {
			SMTP_Logger::warning( 'Customer report email failed', array( 'lead_id' => $lead_id, 'recipient' => $data['email'] ) );
		}

		return array(
			'admin'    => (bool) $admin_sent,
			'customer' => (bool) $customer_sent,
		);
	}
}
