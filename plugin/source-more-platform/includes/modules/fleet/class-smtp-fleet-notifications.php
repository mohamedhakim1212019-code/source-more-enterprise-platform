<?php
/**
 * Fleet Assessment email notifications.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Fleet_Notifications {
	/** @param array<string,mixed> $data */
	public function send( int $lead_id, array $data, string $report_url ): array {
		$options = SMTP_Settings::get();
		$to      = sanitize_email( $options['notification_email'] ?? get_option( 'admin_email' ) );
		if ( ! is_email( $to ) ) {
			$to = sanitize_email( get_option( 'admin_email' ) );
		}
		$lang = SMTP_I18n::language( (string) ( $data['language'] ?? '' ) );

		$admin_subject = 'New Fleet Assessment Lead: ' . $data['company'];
		$admin_body    = "A new fleet savings lead has been submitted.\n\nCompany: {$data['company']}\nContact: {$data['contact_name']}\nEmail: {$data['email']}\nPhone: {$data['phone']}\nIndustry: {$data['industry']}\nDevices: {$data['devices']}\nLanguage: {$lang}\nEstimated annual savings: EGP " . number_format( (float) $data['annual_savings'], 0 ) . "\n\nView lead: " . admin_url( 'post.php?post=' . $lead_id . '&action=edit' ) . "\nReport: {$report_url}";
		$admin_sent    = is_email( $to ) && wp_mail( $to, $admin_subject, $admin_body, array( 'Reply-To: ' . $data['contact_name'] . ' <' . $data['email'] . '>' ) );

		if ( 'ar' === $lang ) {
			$customer_subject = 'تقرير وفر الطباعة — سورس مور تكنولوجي';
			$customer_body    = "الأستاذ/ة {$data['contact_name']}،\n\nشكرًا لاستخدام حاسبة وفر الطباعة من سورس مور تكنولوجي.\n\nالتوفير السنوي التقديري: " . number_format( (float) $data['annual_savings'], 0 ) . " جنيه مصري.\n\nتحميل التقرير: {$report_url}\n\nهذه نتيجة تقديرية ويجب التحقق منها من خلال تقييم احترافي لأسطول الطباعة.\n\nسورس مور تكنولوجي\nمصدر واحد. قيمة أكبر.";
		} else {
			$customer_subject = 'Your Fleet Savings Report — Source More Technology';
			$customer_body    = "Dear {$data['contact_name']},\n\nThank you for using the Source More Technology Fleet Savings Calculator.\n\nYour estimated annual savings are EGP " . number_format( (float) $data['annual_savings'], 0 ) . ".\n\nDownload your report: {$report_url}\n\nThis indicative estimate should be validated through a professional fleet assessment.\n\nSource More Technology\nOne Source, More Value.";
		}

		$customer_headers = is_email( $to ) ? array( 'Reply-To: Source More Technology <' . $to . '>' ) : array();
		$customer_sent    = wp_mail( $data['email'], $customer_subject, $customer_body, $customer_headers );
		SMTP_Logger::info( 'Fleet lead notifications processed', array( 'lead_id' => $lead_id, 'language' => $lang, 'notification_email' => $to, 'admin_email_sent' => (bool) $admin_sent, 'customer_email_sent' => (bool) $customer_sent ) );
		if ( ! $admin_sent ) SMTP_Logger::warning( 'Admin lead email failed', array( 'lead_id' => $lead_id, 'recipient' => $to ) );
		if ( ! $customer_sent ) SMTP_Logger::warning( 'Customer report email failed', array( 'lead_id' => $lead_id, 'recipient' => $data['email'] ) );
		return array( 'admin' => (bool) $admin_sent, 'customer' => (bool) $customer_sent );
	}

	public function send_fleet_lead( int $lead_id, array $data, string $report_url ): array {
		return $this->send( $lead_id, $data, $report_url );
	}
}
