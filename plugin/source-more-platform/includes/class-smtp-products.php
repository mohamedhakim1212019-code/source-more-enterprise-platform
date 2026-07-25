<?php
/**
 * Backward-compatible Product Center facade.
 *
 * The Product Center implementation lives in includes/modules/products.
 * Existing integrations may continue to call SMTP_Products methods and constants.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Products {
	public const POST_TYPE = SMTP_Products_Content_Types::POST_TYPE;
	public const RFQ_TYPE  = SMTP_Products_Content_Types::RFQ_TYPE;
	public const CATEGORY  = SMTP_Products_Content_Types::CATEGORY;
	public const BRAND     = SMTP_Products_Content_Types::BRAND;

	public static function init(): void {
		SMTP_Products_Module::boot();
	}

	public static function register(): void {
		SMTP_Products_Module::register_content_types();
	}

	public static function meta_boxes(): void {
		SMTP_Products_Module::instance()->admin()->meta_boxes();
	}

	public static function product_box( $post ): void {
		SMTP_Products_Module::instance()->admin()->product_box( $post );
	}

	public static function save_product( int $post_id ): void {
		SMTP_Products_Module::instance()->admin()->save_product( $post_id );
	}

	public static function rfq_box( $post ): void {
		SMTP_Products_Module::instance()->admin()->rfq_box( $post );
	}

	public static function save_rfq( int $post_id ): void {
		SMTP_Products_Module::instance()->admin()->save_rfq( $post_id );
	}

	public static function product_columns( $columns ): array {
		return SMTP_Products_Module::instance()->admin()->product_columns( (array) $columns );
	}

	public static function product_column( $column, $post_id ): void {
		SMTP_Products_Module::instance()->admin()->product_column( (string) $column, (int) $post_id );
	}

	public static function rfq_columns( $columns ): array {
		return SMTP_Products_Module::instance()->admin()->rfq_columns( (array) $columns );
	}

	public static function rfq_column( $column, $post_id ): void {
		SMTP_Products_Module::instance()->admin()->rfq_column( (string) $column, (int) $post_id );
	}

	public static function assets(): void {
		SMTP_Products_Module::instance()->frontend()->assets();
	}

	public static function products_shortcode( $atts = array() ): string {
		return SMTP_Products_Module::instance()->frontend()->products_shortcode( $atts );
	}

	public static function quote_shortcode( $atts = array() ): string {
		return SMTP_Products_Module::instance()->frontend()->quote_shortcode( $atts );
	}

	public static function routes(): void {
		SMTP_Products_Module::instance()->rest()->routes();
	}

	public static function create_quote( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		return SMTP_Products_Module::instance()->rest()->create_quote( $request );
	}
}
