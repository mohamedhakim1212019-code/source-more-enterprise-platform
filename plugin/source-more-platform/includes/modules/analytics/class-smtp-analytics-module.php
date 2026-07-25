<?php
/**
 * Reports & Analytics module composition root.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Analytics_Module {
	private static ?self $instance = null;

	private SMTP_Analytics_Repository $repository;
	private SMTP_Analytics_Service $service;
	private SMTP_Analytics_Admin $admin;
	private SMTP_Analytics_Export $export;
	private SMTP_Analytics_PDF $pdf;
	private bool $booted = false;

	private function __construct() {
		$crm              = SMTP_CRM_Module::instance()->repository();
		$this->repository = new SMTP_Analytics_Repository( $crm );
		$this->service    = new SMTP_Analytics_Service( $this->repository, $crm );
		$this->admin      = new SMTP_Analytics_Admin( $this->service, $crm );
		$this->export     = new SMTP_Analytics_Export( $this->service );
		$this->pdf        = new SMTP_Analytics_PDF( $this->service );
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

		$module->admin->register_hooks();
		$module->export->register_hooks();
		$module->pdf->register_hooks();
		$module->booted = true;
		do_action( 'smtp_analytics_module_booted', $module );
	}

	public static function is_booted(): bool {
		return self::instance()->booted;
	}

	public function repository(): SMTP_Analytics_Repository {
		return $this->repository;
	}

	public function service(): SMTP_Analytics_Service {
		return $this->service;
	}

	public function admin(): SMTP_Analytics_Admin {
		return $this->admin;
	}

	public function export(): SMTP_Analytics_Export {
		return $this->export;
	}

	public function pdf(): SMTP_Analytics_PDF {
		return $this->pdf;
	}
}
