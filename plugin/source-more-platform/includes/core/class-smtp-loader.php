<?php
/**
 * Loads the legacy platform classes in a deterministic order.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Loader {
	/**
	 * Load all existing platform classes.
	 */
	public static function load_legacy_classes(): void {
		$files = array(
			'class-smtp-modules.php',
			'class-smtp-logger.php',
			'class-smtp-rate-limiter.php',
			'class-smtp-pdf.php',
			'class-smtp-dashboard.php',
			'class-smtp-products.php',
			'class-smtp-leads.php',
			'class-smtp-settings.php',
			'class-smtp-rest.php',
			'class-smtp-assistant.php',
			'class-smtp-diagnostics.php',
			'class-smtp-platform.php',
		);

		foreach ( $files as $file ) {
			$path = SMTP_PLATFORM_DIR . 'includes/' . $file;

			if ( ! is_readable( $path ) ) {
				self::fail( $path );
			}

			require_once $path;
		}
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
