<?php
/**
 * Persistent audit log for important platform actions.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Audit_Log {
	public static function table(): string {
		global $wpdb;
		return $wpdb->prefix . 'smtp_audit_log';
	}

	public static function install(): void {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$table   = self::table();
		$charset = $wpdb->get_charset_collate();
		$sql     = "CREATE TABLE {$table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			created_at datetime NOT NULL,
			user_id bigint(20) unsigned NOT NULL DEFAULT 0,
			action varchar(100) NOT NULL,
			object_type varchar(80) NOT NULL DEFAULT '',
			object_id bigint(20) unsigned NOT NULL DEFAULT 0,
			ip_address varchar(45) NOT NULL DEFAULT '',
			context longtext NULL,
			PRIMARY KEY  (id),
			KEY action (action),
			KEY object_lookup (object_type, object_id),
			KEY created_at (created_at)
		) {$charset};";

		dbDelta( $sql );
	}

	public static function record( string $action, string $object_type = '', int $object_id = 0, array $context = array() ): bool {
		global $wpdb;

		$inserted = $wpdb->insert(
			self::table(),
			array(
				'created_at'  => current_time( 'mysql', true ),
				'user_id'     => get_current_user_id(),
				'action'      => sanitize_key( $action ),
				'object_type' => sanitize_key( $object_type ),
				'object_id'   => max( 0, $object_id ),
				'ip_address'  => self::ip_address(),
				'context'     => wp_json_encode( $context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ),
			),
			array( '%s', '%d', '%s', '%s', '%d', '%s', '%s' )
		);

		return false !== $inserted;
	}

	/** @return array<int, object> */
	public static function recent( int $limit = 50 ): array {
		global $wpdb;
		$limit = max( 1, min( 200, $limit ) );
		return $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . self::table() . ' ORDER BY id DESC LIMIT %d', $limit ) );
	}

	private static function ip_address(): string {
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? wp_unslash( $_SERVER['REMOTE_ADDR'] ) : '';
		return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '';
	}
}
