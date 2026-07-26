<?php
/**
 * Product Center module composition root.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Products_Module {
	private static ?self $instance = null;

	private SMTP_Products_Content_Types $content_types;
	private SMTP_Products_Repository $repository;
	private SMTP_Products_Admin $admin;
	private SMTP_Products_Frontend $frontend;
	private SMTP_Products_REST $rest;
	private bool $booted = false;

	private function __construct() {
		$this->content_types = new SMTP_Products_Content_Types();
		$this->repository    = new SMTP_Products_Repository();
		$this->admin         = new SMTP_Products_Admin();
		$this->frontend      = new SMTP_Products_Frontend( $this->repository );
		$this->rest          = new SMTP_Products_REST( $this->repository );
	}

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register all Product Center hooks exactly once.
	 */
	public static function boot(): void {
		$module = self::instance();

		if ( $module->booted ) {
			return;
		}

		$module->content_types->register_polylang_support();
		add_action( 'init', array( $module->content_types, 'register' ) );
		$module->admin->register_hooks();
		$module->frontend->register_hooks();
		$module->rest->register_hooks();

		$module->booted = true;

		do_action( 'smtp_products_module_booted', $module );
	}

	/**
	 * Register content types immediately for activation and upgrade routines.
	 */
	public static function register_content_types(): void {
		self::instance()->content_types->register();
	}

	public static function is_booted(): bool {
		return self::instance()->booted;
	}

	public function content_types(): SMTP_Products_Content_Types {
		return $this->content_types;
	}

	public function repository(): SMTP_Products_Repository {
		return $this->repository;
	}

	public function admin(): SMTP_Products_Admin {
		return $this->admin;
	}

	public function frontend(): SMTP_Products_Frontend {
		return $this->frontend;
	}

	public function rest(): SMTP_Products_REST {
		return $this->rest;
	}
}
