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
	private SMTP_CRM_Notifications $notifications;
	private SMTP_CRM_Admin $admin;
	private SMTP_CRM_Frontend $frontend;
	private SMTP_CRM_REST $rest;
	private bool $booted = false;

	private function __construct() {
		$this->content_types = new SMTP_CRM_Content_Types();
		$this->repository    = new SMTP_CRM_Repository();
		$this->notifications = new SMTP_CRM_Notifications();
		$this->admin         = new SMTP_CRM_Admin( $this->repository );
		$this->frontend      = new SMTP_CRM_Frontend();
		$this->rest          = new SMTP_CRM_REST( $this->repository, $this->notifications );
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
		$module->frontend->register_hooks();
		$module->rest->register_hooks();
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

	public function notifications(): SMTP_CRM_Notifications {
		return $this->notifications;
	}

	public function admin(): SMTP_CRM_Admin {
		return $this->admin;
	}

	public function frontend(): SMTP_CRM_Frontend {
		return $this->frontend;
	}

	public function rest(): SMTP_CRM_REST {
		return $this->rest;
	}
}
