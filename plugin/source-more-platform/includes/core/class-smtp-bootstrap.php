<?php
/**
 * Source More Platform bootstrap.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Bootstrap {
	/**
	 * Load classes and register the WordPress lifecycle hooks.
	 */
	public static function register(): void {
		SMTP_Loader::load_legacy_classes();

		register_activation_hook( SMTP_PLATFORM_FILE, array( 'SMTP_Platform', 'activate' ) );
		register_deactivation_hook( SMTP_PLATFORM_FILE, array( 'SMTP_Platform', 'deactivate' ) );

		add_action( 'plugins_loaded', array( __CLASS__, 'boot' ) );
		add_action( 'admin_init', array( __CLASS__, 'maybe_upgrade' ), 20 );
	}

	/**
	 * Start the existing platform runtime.
	 */
	public static function boot(): void {
		load_plugin_textdomain(
			'source-more-platform',
			false,
			dirname( plugin_basename( SMTP_PLATFORM_FILE ) ) . '/languages'
		);

		SMTP_Platform::init();
	}

	/**
	 * Run safe, idempotent database and content upgrades.
	 */
	public static function maybe_upgrade(): void {
		if ( ! SMTP_Version::database_upgrade_required() ) {
			return;
		}

		if ( class_exists( 'SMTP_Products' ) ) {
			SMTP_Products::register();
		}

		if ( ! get_page_by_path( 'products' ) ) {
			wp_insert_post(
				array(
					'post_type'   => 'page',
					'post_status' => 'publish',
					'post_title'  => 'Products',
					'post_name'   => 'products',
				)
			);
		}

		flush_rewrite_rules( false );
		update_option( 'smtp_platform_db_version', SMTP_Version::database(), false );
	}
}
