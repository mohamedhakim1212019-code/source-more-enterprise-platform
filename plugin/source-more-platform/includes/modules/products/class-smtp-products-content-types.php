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
