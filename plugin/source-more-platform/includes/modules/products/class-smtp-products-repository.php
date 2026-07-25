<?php
/**
 * Product Center data access.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Products_Repository {
	/**
	 * Query published products for the public Product Center.
	 */
	public function query_published( int $limit = 24 ): WP_Query {
		$args = array(
			'post_type'      => SMTP_Products_Content_Types::POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, min( 100, $limit ) ),
			'orderby'        => array(
				'meta_value_num' => 'DESC',
				'date'           => 'DESC',
			),
			'meta_key'       => '_smtp_featured',
		);

		/**
		 * Filter the public Product Center query without changing the shortcode contract.
		 */
		$args = apply_filters( 'smtp_products_query_args', $args, $limit );

		return new WP_Query( $args );
	}

	/**
	 * Return presentation-safe product data used by the shortcode renderer.
	 *
	 * @return array<string, mixed>
	 */
	public function card_data( int $product_id ): array {
		$terms = get_the_terms( $product_id, SMTP_Products_Content_Types::BRAND );
		$brand = '';

		if ( $terms && ! is_wp_error( $terms ) ) {
			$brand = (string) $terms[0]->name;
		}

		$content = (string) get_the_content( null, false, $product_id );
		$excerpt = (string) get_the_excerpt( $product_id );

		return array(
			'id'           => $product_id,
			'type'         => (string) ( get_post_meta( $product_id, '_smtp_product_type', true ) ?: 'hardware' ),
			'model'        => (string) get_post_meta( $product_id, '_smtp_model', true ),
			'availability' => (string) ( get_post_meta( $product_id, '_smtp_availability', true ) ?: 'available' ),
			'brand'        => $brand,
			'excerpt'      => $excerpt ?: wp_trim_words( wp_strip_all_tags( $content ), 22 ),
		);
	}

	/**
	 * Create a quote-request record and persist its normalized metadata.
	 *
	 * @param array<string, mixed> $values Validated and sanitized request values.
	 * @return int|WP_Error
	 */
	public function create_quote_request( array $values ) {
		$post_id = wp_insert_post(
			array(
				'post_type'    => SMTP_Products_Content_Types::RFQ_TYPE,
				'post_status'  => 'publish',
				'post_title'   => $values['company'] . ' — ' . $values['product_name'],
				'post_content' => $values['message'],
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		foreach ( $values as $key => $value ) {
			update_post_meta( $post_id, '_smtp_' . $key, $value );
		}

		return (int) $post_id;
	}
}
