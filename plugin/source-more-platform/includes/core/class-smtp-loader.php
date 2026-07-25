<?php
/**
 * Loads the existing platform runtime classes in a deterministic order.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Loader {
	private static bool $runtime_loaded = false;

	/**
	 * Load all existing platform classes exactly once.
	 */
	public static function load_runtime_classes(): void {
		if ( self::$runtime_loaded ) {
			return;
		}

		$files = array(
			'includes/class-smtp-modules.php',
			'includes/class-smtp-logger.php',
			'includes/class-smtp-rate-limiter.php',
			'includes/class-smtp-dashboard.php',
			'includes/modules/crm/class-smtp-crm-content-types.php',
			'includes/modules/crm/class-smtp-crm-repository.php',
			'includes/modules/crm/class-smtp-crm-admin.php',
			'includes/modules/crm/class-smtp-crm-contact-capture.php',
			'includes/modules/crm/class-smtp-crm-module.php',
			'includes/modules/fleet/class-smtp-fleet-calculator.php',
			'includes/modules/fleet/class-smtp-fleet-pdf.php',
			'includes/modules/fleet/class-smtp-fleet-report.php',
			'includes/modules/fleet/class-smtp-fleet-notifications.php',
			'includes/modules/fleet/class-smtp-fleet-frontend.php',
			'includes/modules/fleet/class-smtp-fleet-rest.php',
			'includes/modules/fleet/class-smtp-fleet-module.php',
			'includes/modules/crm/class-smtp-crm-notifications.php',
			'includes/modules/crm/class-smtp-crm-frontend.php',
			'includes/modules/crm/class-smtp-crm-rest.php',
			'includes/class-smtp-pdf.php',
			'includes/modules/products/class-smtp-products-content-types.php',
			'includes/modules/products/class-smtp-products-repository.php',
			'includes/modules/products/class-smtp-products-admin.php',
			'includes/modules/products/class-smtp-products-frontend.php',
			'includes/modules/products/class-smtp-products-rest.php',
			'includes/modules/products/class-smtp-products-module.php',
			'includes/class-smtp-products.php',
			'includes/class-smtp-leads.php',
			'includes/class-smtp-settings.php',
			'includes/class-smtp-rest.php',
			'includes/modules/assistant/class-smtp-assistant-content-types.php',
			'includes/modules/assistant/class-smtp-assistant-conversations.php',
			'includes/modules/assistant/class-smtp-assistant-knowledge.php',
			'includes/modules/assistant/class-smtp-assistant-endpoint.php',
			'includes/modules/assistant/class-smtp-assistant-service.php',
			'includes/modules/assistant/class-smtp-assistant-lead-converter.php',
			'includes/modules/assistant/class-smtp-assistant-rest.php',
			'includes/modules/assistant/class-smtp-assistant-frontend.php',
			'includes/modules/assistant/class-smtp-assistant-admin.php',
			'includes/modules/assistant/class-smtp-assistant-module.php',
			'includes/class-smtp-assistant.php',
			'includes/modules/analytics/class-smtp-analytics-date-range.php',
			'includes/modules/analytics/class-smtp-analytics-repository.php',
			'includes/modules/analytics/class-smtp-analytics-service.php',
			'includes/modules/analytics/class-smtp-analytics-export.php',
			'includes/modules/analytics/class-smtp-analytics-pdf.php',
			'includes/modules/analytics/class-smtp-analytics-admin.php',
			'includes/modules/analytics/class-smtp-analytics-module.php',
			'includes/class-smtp-diagnostics.php',
			'includes/class-smtp-platform.php',
		);

		foreach ( $files as $file ) {
			$path = SMTP_PLATFORM_DIR . $file;

			if ( ! is_readable( $path ) ) {
				self::fail( $path );
			}

			require_once $path;
		}

		self::$runtime_loaded = true;
	}

	/**
	 * Backward-compatible alias retained for Sprint 1.1 integrations.
	 */
	public static function load_legacy_classes(): void {
		self::load_runtime_classes();
	}

	/**
	 * Stop booting when a required platform file is unavailable.
	 */
	private static function fail( string $path ): void {
		$message = sprintf(
			/* translators: %s: absolute PHP file path. */
			__( 'Source More Platform could not load the required file: %s', 'source-more-platform' ),
			esc_html( $path )
		);

		if ( function_exists( 'wp_die' ) ) {
			wp_die( $message, esc_html__( 'Source More Platform Error', 'source-more-platform' ) );
		}

		throw new RuntimeException( wp_strip_all_tags( $message ) );
	}
}
