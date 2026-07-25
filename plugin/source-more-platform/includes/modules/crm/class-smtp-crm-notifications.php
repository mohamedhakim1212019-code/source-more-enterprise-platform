<?php
/**
 * Backward-compatible Fleet notification facade.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_CRM_Notifications {
	/**
	 * @param array<string, mixed> $data Validated lead data.
	 * @return array<string, bool>
	 */
	public function send_fleet_lead( int $lead_id, array $data, string $report_url ): array {
		return SMTP_Fleet_Module::instance()->notifications()->send( $lead_id, $data, $report_url );
	}
}
