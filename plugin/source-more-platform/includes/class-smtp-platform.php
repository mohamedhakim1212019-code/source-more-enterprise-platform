<?php
/**
 * Platform lifecycle and default runtime module registration.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Platform {
	private static bool $modules_registered = false;

	public static function activate(): void {
		SMTP_Leads::register_post_type();
		SMTP_Products_Module::register_content_types();
		SMTP_Assistant_Module::register_content_types();
		update_option( 'smtp_platform_db_version', SMTP_PLATFORM_DB_VERSION, false );

		if ( null === get_option( 'smtp_platform_options', null ) ) {
			add_option( 'smtp_platform_options', SMTP_Settings::defaults(), '', false );
		}

		flush_rewrite_rules();
	}

	public static function deactivate(): void {
		if ( class_exists( 'SMTP_Assistant_Module' ) ) {
			SMTP_Assistant_Module::deactivate();
		}

		flush_rewrite_rules();
	}

	/**
	 * Backward-compatible platform entry point.
	 */
	public static function init(): void {
		self::register_modules();
		SMTP_Module_Registry::boot_enabled();
	}

	/**
	 * Register the default platform modules once.
	 */
	public static function register_modules(): void {
		if ( self::$modules_registered ) {
			return;
		}

		SMTP_Module_Registry::register(
			new SMTP_Module(
				'settings',
				'Platform Settings',
				SMTP_PLATFORM_VERSION,
				array( 'SMTP_Settings', 'init' ),
				array(),
				null,
				array( 'SMTP_Settings', 'SMTP_Modules' )
			)
		);

		SMTP_Module_Registry::register(
			new SMTP_Module(
				'dashboard',
				'Enterprise Dashboard',
				SMTP_PLATFORM_VERSION,
				array( 'SMTP_Dashboard', 'init' ),
				array( 'settings' ),
				null,
				array( 'SMTP_Dashboard', 'SMTP_Leads', 'SMTP_Settings' )
			)
		);

		SMTP_Module_Registry::register(
			new SMTP_Module(
				'leads',
				'CRM and Lead Management',
				SMTP_PLATFORM_VERSION,
				array( 'SMTP_CRM_Module', 'boot' ),
				array( 'settings' ),
				static fn(): bool => SMTP_Modules::enabled( 'crm' ) || SMTP_Modules::enabled( 'fleet' ),
				array( 'SMTP_CRM_Module', 'SMTP_Leads' )
			)
		);

		SMTP_Module_Registry::register(
			new SMTP_Module(
				'products',
				'Product Center and Quote Requests',
				SMTP_PLATFORM_VERSION,
				array( 'SMTP_Products_Module', 'boot' ),
				array( 'settings' ),
				static fn(): bool => SMTP_Modules::enabled( 'products' ),
				array( 'SMTP_Products_Module', 'SMTP_Products' )
			)
		);

		SMTP_Module_Registry::register(
			new SMTP_Module(
				'fleet',
				'Fleet Assessment Engine',
				SMTP_PLATFORM_VERSION,
				array( 'SMTP_Fleet_Module', 'boot' ),
				array( 'settings', 'leads' ),
				static fn(): bool => SMTP_Modules::enabled( 'fleet' ),
				array( 'SMTP_Fleet_Module', 'SMTP_Fleet_Calculator', 'SMTP_Fleet_Report', 'SMTP_Fleet_REST', 'SMTP_Rate_Limiter', 'SMTP_Settings' )
			)
		);

		SMTP_Module_Registry::register(
			new SMTP_Module(
				'assistant',
				'AI Assistant',
				SMTP_PLATFORM_VERSION,
				array( 'SMTP_Assistant_Module', 'boot' ),
				array( 'settings' ),
				static fn(): bool => SMTP_Modules::enabled( 'assistant' ),
				array( 'SMTP_Assistant_Module', 'SMTP_Assistant', 'SMTP_Rate_Limiter', 'SMTP_Settings' )
			)
		);

		SMTP_Module_Registry::register(
			new SMTP_Module(
				'analytics',
				'Reports and Analytics',
				SMTP_PLATFORM_VERSION,
				array( 'SMTP_Analytics_Module', 'boot' ),
				array( 'settings', 'leads' ),
				static fn(): bool => SMTP_Modules::enabled( 'analytics' ),
				array( 'SMTP_Analytics_Module', 'SMTP_Analytics_Service', 'SMTP_Analytics_Repository', 'SMTP_Analytics_PDF', 'SMTP_CRM_Repository' )
			)
		);

		SMTP_Module_Registry::register(
			new SMTP_Module(
				'diagnostics',
				'Diagnostics and Operational Logs',
				SMTP_PLATFORM_VERSION,
				array( 'SMTP_Diagnostics', 'init' ),
				array( 'dashboard' ),
				static fn(): bool => SMTP_Modules::enabled( 'diagnostics' ),
				array( 'SMTP_Diagnostics', 'SMTP_Logger' )
			)
		);

		/**
		 * Register third-party or future SMEP modules before the boot cycle.
		 */
		do_action( 'smtp_platform_register_modules' );

		self::$modules_registered = true;
	}
}
