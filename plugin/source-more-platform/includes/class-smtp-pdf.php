<?php
/**
 * Backward-compatible Fleet PDF facade.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SMTP_Simple_PDF {
	public function output_lead( $lead_id ): void {
		SMTP_Fleet_Module::instance()->report()->output_lead( (int) $lead_id );
	}
}
