<?php
/**
 * AI Assistant module composition root.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Assistant_Module {
	private static ?self $instance = null;

	private SMTP_Assistant_Content_Types $content_types;
	private SMTP_Assistant_Conversations $conversations;
	private SMTP_Assistant_Knowledge $knowledge;
	private SMTP_Assistant_Endpoint $endpoint;
	private SMTP_Assistant_Service $service;
	private SMTP_Assistant_Lead_Converter $lead_converter;
	private SMTP_Assistant_REST $rest;
	private SMTP_Assistant_Frontend $frontend;
	private SMTP_Assistant_Admin $admin;
	private bool $booted = false;

	private function __construct() {
		$this->content_types  = new SMTP_Assistant_Content_Types();
		$this->conversations  = new SMTP_Assistant_Conversations();
		$this->knowledge      = new SMTP_Assistant_Knowledge();
		$this->endpoint       = new SMTP_Assistant_Endpoint();
		$this->service        = new SMTP_Assistant_Service( $this->knowledge, $this->endpoint, $this->conversations );
		$this->lead_converter = new SMTP_Assistant_Lead_Converter( $this->conversations );
		$this->rest           = new SMTP_Assistant_REST( $this->service, $this->lead_converter );
		$this->frontend       = new SMTP_Assistant_Frontend();
		$this->admin          = new SMTP_Assistant_Admin( $this->conversations );
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

		$module->content_types->register_polylang_support();
		add_action( 'init', array( $module->content_types, 'register' ) );
		add_action( 'init', array( $module, 'schedule_cleanup' ), 20 );
		add_action( 'smtp_assistant_cleanup', array( $module->conversations, 'cleanup' ) );
		$module->rest->register_hooks();
		$module->frontend->register_hooks();
		$module->admin->register_hooks();
		$module->booted = true;
		do_action( 'smtp_assistant_module_booted', $module );
	}

	public static function register_content_types(): void {
		self::instance()->content_types->register();
	}

	public static function is_booted(): bool {
		return self::instance()->booted;
	}

	public static function deactivate(): void {
		$timestamp = wp_next_scheduled( 'smtp_assistant_cleanup' );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, 'smtp_assistant_cleanup' );
		}
	}

	public function schedule_cleanup(): void {
		if ( ! wp_next_scheduled( 'smtp_assistant_cleanup' ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'smtp_assistant_cleanup' );
		}
	}

	public function content_types(): SMTP_Assistant_Content_Types { return $this->content_types; }
	public function conversations(): SMTP_Assistant_Conversations { return $this->conversations; }
	public function knowledge(): SMTP_Assistant_Knowledge { return $this->knowledge; }
	public function service(): SMTP_Assistant_Service { return $this->service; }
	public function lead_converter(): SMTP_Assistant_Lead_Converter { return $this->lead_converter; }
	public function rest(): SMTP_Assistant_REST { return $this->rest; }
	public function frontend(): SMTP_Assistant_Frontend { return $this->frontend; }
	public function admin(): SMTP_Assistant_Admin { return $this->admin; }
}
