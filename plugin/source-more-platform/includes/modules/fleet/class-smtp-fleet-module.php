<?php
/**
 * Fleet Assessment module composition root.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Fleet_Module {
	private static ?self $instance = null;

	private SMTP_CRM_Repository $repository;
	private SMTP_Fleet_Calculator $calculator;
	private SMTP_Fleet_PDF $pdf;
	private SMTP_Fleet_Report $report;
	private SMTP_Fleet_Notifications $notifications;
	private SMTP_Fleet_Frontend $frontend;
	private SMTP_Fleet_REST $rest;
	private bool $booted = false;

	private function __construct() {
		$this->repository    = SMTP_CRM_Module::instance()->repository();
		$this->calculator    = new SMTP_Fleet_Calculator();
		$this->pdf           = new SMTP_Fleet_PDF();
		$this->report        = new SMTP_Fleet_Report( $this->pdf );
		$this->notifications = new SMTP_Fleet_Notifications();
		$this->frontend      = new SMTP_Fleet_Frontend();
		$this->rest          = new SMTP_Fleet_REST( $this->repository, $this->calculator, $this->notifications, $this->report );
	}

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function boot(): void {
		$module = self::instance();

		if ( $module->booted ) {
			return;
		}

		$module->frontend->register_hooks();
		$module->rest->register_hooks();
		$module->report->register_hooks();
		$module->booted = true;
		do_action( 'smtp_fleet_module_booted', $module );
	}

	public static function is_booted(): bool {
		return self::instance()->booted;
	}

	public function calculator(): SMTP_Fleet_Calculator {
		return $this->calculator;
	}

	public function report(): SMTP_Fleet_Report {
		return $this->report;
	}

	public function notifications(): SMTP_Fleet_Notifications {
		return $this->notifications;
	}

	public function frontend(): SMTP_Fleet_Frontend {
		return $this->frontend;
	}

	public function rest(): SMTP_Fleet_REST {
		return $this->rest;
	}
}
