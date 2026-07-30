<?php
/**
 * Platform roles and capabilities.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Capabilities {
	public const ACCESS_PLATFORM = 'smtp_access_platform';
	public const MANAGE_PLATFORM = 'smtp_manage_platform';
	public const MANAGE_CRM      = 'smtp_manage_crm';
	public const MANAGE_FLEET    = 'smtp_manage_fleet';
	public const VIEW_REPORTS    = 'smtp_view_reports';
	public const MANAGE_PRODUCTS = 'smtp_manage_products';
	public const VIEW_AUDIT_LOG  = 'smtp_view_audit_log';

	/** @return string[] */
	public static function all(): array {
		return array(
			self::ACCESS_PLATFORM,
			self::MANAGE_PLATFORM,
			self::MANAGE_CRM,
			self::MANAGE_FLEET,
			self::VIEW_REPORTS,
			self::MANAGE_PRODUCTS,
			self::VIEW_AUDIT_LOG,
		);
	}

	public static function install(): void {
		$administrator = get_role( 'administrator' );
		if ( $administrator ) {
			foreach ( self::all() as $capability ) {
				$administrator->add_cap( $capability );
			}
		}

		self::upsert_role(
			'smtp_sales_manager',
			'SMEP Sales Manager',
			array(
				'read'                  => true,
				'edit_posts'            => true,
				'publish_posts'         => true,
				self::ACCESS_PLATFORM   => true,
				self::MANAGE_CRM        => true,
				self::MANAGE_FLEET      => true,
				self::VIEW_REPORTS      => true,
				self::MANAGE_PRODUCTS   => true,
			)
		);

		self::upsert_role(
			'smtp_sales_executive',
			'SMEP Sales Executive',
			array(
				'read'                => true,
				'edit_posts'          => true,
				self::ACCESS_PLATFORM => true,
				self::MANAGE_CRM      => true,
				self::MANAGE_FLEET    => true,
				self::VIEW_REPORTS    => true,
			)
		);

		self::upsert_role(
			'smtp_customer',
			'SMEP Customer',
			array(
				'read'                => true,
				self::ACCESS_PLATFORM => true,
				self::VIEW_REPORTS    => true,
			)
		);
	}

	private static function upsert_role( string $key, string $label, array $capabilities ): void {
		$role = get_role( $key );
		if ( ! $role ) {
			add_role( $key, $label, $capabilities );
			return;
		}

		foreach ( $capabilities as $capability => $grant ) {
			if ( $grant ) {
				$role->add_cap( $capability );
			} else {
				$role->remove_cap( $capability );
			}
		}
	}
}
