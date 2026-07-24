<?php
/**
 * Platform version information.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Version {
	public static function plugin(): string {
		return SMTP_PLATFORM_VERSION;
	}

	public static function database(): string {
		return SMTP_PLATFORM_DB_VERSION;
	}

	public static function installed_database(): string {
		return (string) get_option( 'smtp_platform_db_version', '0.0.0' );
	}

	public static function database_upgrade_required(): bool {
		return version_compare( self::installed_database(), self::database(), '<' );
	}
}
