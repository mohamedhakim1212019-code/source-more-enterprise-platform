<?php
/**
 * Product Center content-type registration.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Products_Content_Types {
	public const POST_TYPE = 'smt_product';
	public const RFQ_TYPE  = 'smt_quote_request';
	public const CATEGORY  = 'smt_product_category';
	public const BRAND     = 'smt_product_brand';


	/**
	 * Keep public Product Center content translatable when Polylang is active.
	 * Internal quote requests remain CRM records and are intentionally excluded.
	 */
	public function register_polylang_support(): void {
		add_filter( 'pll_get_post_types', array( $this, 'polylang_post_types' ), 10, 2 );
		add_filter( 'pll_get_taxonomies', array( $this, 'polylang_taxonomies' ), 10, 2 );
	}

	/** @param array<string,string> $types */
	public function polylang_post_types( array $types, bool $is_settings ): array {
		$types[ self::POST_TYPE ] = self::POST_TYPE;
		return $types;
	}

	/** @param array<string,string> $taxonomies */
	public function polylang_taxonomies( array $taxonomies, bool $is_settings ): array {
		$taxonomies[ self::CATEGORY ] = self::CATEGORY;
		$taxonomies[ self::BRAND ]    = self::BRAND;
		return $taxonomies;
	}

	/**
	 * Register Product Center taxonomies and post types.
	 */
	public function register(): void {
		register_taxonomy(
			self::CATEGORY,
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => 'Product Categories',
					'singular_name' => 'Product Category',
				),
				'public'       => true,
				'show_ui'      => true,
				'show_in_rest' => true,
				'hierarchical' => true,
				'rewrite'      => array( 'slug' => 'product-category' ),
			)
		);

		register_taxonomy(
			self::BRAND,
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => 'Brands',
					'singular_name' => 'Brand',
				),
				'public'       => true,
				'show_ui'      => true,
				'show_in_rest' => true,
				'hierarchical' => true,
				'rewrite'      => array( 'slug' => 'brand' ),
			)
		);

		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'         => 'Products',
					'singular_name'=> 'Product',
					'add_new_item' => 'Add Product',
					'edit_item'    => 'Edit Product',
					'all_items'    => 'All Products',
					'menu_name'    => 'Products',
				),
				'public'       => true,
				'show_ui'      => true,
				'show_in_menu' => 'smtp-platform',
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
				'has_archive'  => 'products',
				'rewrite'      => array( 'slug' => 'products' ),
				'menu_icon'    => 'dashicons-products',
			)
		);

		register_post_type(
			self::RFQ_TYPE,
			array(
				'labels'          => array(
					'name'          => 'Quote Requests',
					'singular_name' => 'Quote Request',
					'all_items'     => 'Quote Requests',
					'edit_item'     => 'View Quote Request',
					'menu_name'     => 'Quote Requests',
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => 'smtp-platform',
				'supports'        => array( 'title', 'editor' ),
				'capability_type' => 'post',
				'map_meta_cap'    => true,
			)
		);
	}
}
