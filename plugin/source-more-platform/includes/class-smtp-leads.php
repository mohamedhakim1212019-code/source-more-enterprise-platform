<?php
/**
 * Backward-compatible CRM and Fleet Leads facade.
 *
 * CRM data management lives in includes/modules/crm. Fleet calculation,
 * capture, reports, notifications, and REST delivery live in
 * includes/modules/fleet.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SMTP_Leads {
	public const POST_TYPE = SMTP_CRM_Content_Types::POST_TYPE;

	public static function activate() {
		self::register_post_type();
		flush_rewrite_rules();
	}

	public static function init() {
		SMTP_CRM_Module::boot();
		SMTP_Fleet_Module::boot();
	}

	public static function register_post_type() {
		SMTP_CRM_Module::register_content_types();
	}

	public static function assets() {
		SMTP_Fleet_Module::instance()->frontend()->assets();
	}

	public static function shortcode() {
		return SMTP_Fleet_Module::instance()->frontend()->shortcode();
	}

	public static function columns( $columns ) {
		return SMTP_CRM_Module::instance()->admin()->columns( (array) $columns );
	}

	public static function column_content( $column, $post_id ) {
		SMTP_CRM_Module::instance()->admin()->column_content( (string) $column, (int) $post_id );
	}

	public static function row_actions( $actions, $post ) {
		return SMTP_CRM_Module::instance()->admin()->row_actions( (array) $actions, $post );
	}

	public static function meta_boxes() {
		SMTP_CRM_Module::instance()->admin()->meta_boxes();
	}

	public static function meta_box( $post ) {
		SMTP_CRM_Module::instance()->admin()->lead_meta_box( $post );
	}

	public static function save_status( $post_id ) {
		SMTP_CRM_Module::instance()->admin()->save_lead( (int) $post_id );
	}

	public static function submenu() {
		SMTP_CRM_Module::instance()->admin()->submenu();
	}

	public static function export_page() {
		SMTP_CRM_Module::instance()->admin()->export_page();
	}

	public static function export_csv() {
		SMTP_CRM_Module::instance()->admin()->export_csv();
	}

	public static function download_report() {
		SMTP_Fleet_Module::instance()->report()->download();
	}
}
