<?php
/**
 * CRM module composition root.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_CRM_Module {
	private static ?self $instance = null;

	private SMTP_CRM_Content_Types $content_types;
	private SMTP_CRM_Repository $repository;
	private SMTP_CRM_Admin $admin;
	private SMTP_CRM_Contact_Capture $contact_capture;
	private bool $booted = false;

	private function __construct() {
		$this->content_types = new SMTP_CRM_Content_Types();
		$this->repository    = new SMTP_CRM_Repository();
		$this->admin           = new SMTP_CRM_Admin( $this->repository );
		$this->contact_capture = new SMTP_CRM_Contact_Capture( $this->repository );
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

		add_action( 'init', array( $module->content_types, 'register' ) );
		$module->admin->register_hooks();
		$module->contact_capture->register_hooks();
		$module->booted = true;
		do_action( 'smtp_crm_module_booted', $module );
	}

	public static function register_content_types(): void {
		self::instance()->content_types->register();
	}

	public static function is_booted(): bool {
		return self::instance()->booted;
	}

	public function content_types(): SMTP_CRM_Content_Types {
		return $this->content_types;
	}

	public function repository(): SMTP_CRM_Repository {
		return $this->repository;
	}

	public function admin(): SMTP_CRM_Admin {
		return $this->admin;
	}

	public function contact_capture(): SMTP_CRM_Contact_Capture {
		return $this->contact_capture;
	}

	/**
	 * Backward-compatible accessors retained after Fleet moved to its own module.
	 */
	public function notifications(): SMTP_Fleet_Notifications {
		return SMTP_Fleet_Module::instance()->notifications();
	}

	public function frontend(): SMTP_Fleet_Frontend {
		return SMTP_Fleet_Module::instance()->frontend();
	}

	public function rest(): SMTP_Fleet_REST {
		return SMTP_Fleet_Module::instance()->rest();
	}
}
