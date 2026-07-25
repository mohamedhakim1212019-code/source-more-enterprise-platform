<?php
/**
 * Read-only data access for reporting and analytics.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Analytics_Repository {
	private SMTP_CRM_Repository $crm;

	public function __construct( SMTP_CRM_Repository $crm ) {
		$this->crm = $crm;
	}

	/** @return int[] */
	public function lead_ids( SMTP_Analytics_Date_Range $range ): array {
		return $this->ids( SMTP_CRM_Content_Types::POST_TYPE, $range );
	}

	/** @return int[] */
	public function quote_ids( SMTP_Analytics_Date_Range $range ): array {
		return $this->ids( SMTP_CRM_Repository::QUOTE_POST_TYPE, $range );
	}

	/** @return int[] */
	public function conversation_ids( SMTP_Analytics_Date_Range $range ): array {
		if ( ! class_exists( 'SMTP_Assistant_Content_Types' ) ) {
			return array();
		}

		return $this->ids( SMTP_Assistant_Content_Types::CONVERSATION_POST_TYPE, $range );
	}

	public function published_product_count(): int {
		if ( ! class_exists( 'SMTP_Products_Content_Types' ) ) {
			return 0;
		}

		return count(
			get_posts(
				array(
					'post_type'      => SMTP_Products_Content_Types::POST_TYPE,
					'post_status'    => 'publish',
					'fields'         => 'ids',
					'posts_per_page' => -1,
					'no_found_rows'  => true,
				)
			)
		);
	}

	public function status( int $post_id ): string {
		return $this->crm->status( $post_id );
	}

	public function source( int $post_id ): string {
		return $this->crm->source( $post_id );
	}

	public function owner( int $post_id ): string {
		return $this->crm->owner_name( $post_id );
	}

	public function meta( int $post_id, string $key ) {
		return get_post_meta( $post_id, $key, true );
	}

	public function title( int $post_id ): string {
		return (string) get_the_title( $post_id );
	}

	public function date( int $post_id ): string {
		return (string) get_post_field( 'post_date', $post_id );
	}

	public function edit_url( int $post_id ): string {
		$url = get_edit_post_link( $post_id, 'raw' );
		return is_string( $url ) ? $url : '';
	}

	/**
	 * @return int[]
	 */
	private function ids( string $post_type, SMTP_Analytics_Date_Range $range ): array {
		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		);

		if ( ! $range->is_all_time() ) {
			$args['date_query'] = $range->date_query();
		}

		return array_values( array_map( 'intval', get_posts( $args ) ) );
	}
}
