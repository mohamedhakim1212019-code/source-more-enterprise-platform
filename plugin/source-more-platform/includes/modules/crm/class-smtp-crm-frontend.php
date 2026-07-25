<?php
/**
 * Backward-compatible Fleet frontend facade.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_CRM_Frontend {
	public function register_hooks(): void {
		SMTP_Fleet_Module::instance()->frontend()->register_hooks();
	}

	public function assets(): void {
		SMTP_Fleet_Module::instance()->frontend()->assets();
	}

	public function shortcode(): string {
		return SMTP_Fleet_Module::instance()->frontend()->shortcode();
	}
}
