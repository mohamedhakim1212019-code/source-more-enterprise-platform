<?php
/**
 * CRM data access and activity management.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_CRM_Repository {
	public const QUOTE_POST_TYPE = 'smt_quote_request';

	/**
	 * @return array<string, string>
	 */
	public function statuses(): array {
		return array(
			'new'       => 'New',
			'contacted' => 'Contacted',
			'qualified' => 'Qualified',
			'proposal'  => 'Proposal',
			'won'       => 'Won',
			'lost'      => 'Lost',
		);
	}

	public function valid_status( string $status ): bool {
		return isset( $this->statuses()[ $status ] );
	}

	/**
	 * Create a Fleet Lead and preserve the existing metadata contract.
	 *
	 * @param array<string, mixed> $values Sanitized lead values.
	 * @return int|WP_Error
	 */
	public function create_lead( array $values ) {
		$company = trim( (string) ( $values['company'] ?? '' ) );
		$name    = trim( (string) ( $values['contact_name'] ?? '' ) );
		$title   = '' !== $company ? $company . ' — ' . $name : $name . ' — Website Inquiry';

		$post_id = wp_insert_post(
			array(
				'post_type'   => SMTP_CRM_Content_Types::POST_TYPE,
				'post_status' => 'publish',
				'post_title'  => $title,
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		foreach ( $values as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		$this->add_activity(
			(int) $post_id,
			'lead_created',
			'Lead created from ' . ( $values['lead_source'] ?? 'unknown source' ),
			array( 'source' => $values['lead_source'] ?? '' )
		);

		return (int) $post_id;
	}

	public function count_leads( string $status = '' ): int {
		$args = array(
			'post_type'      => SMTP_CRM_Content_Types::POST_TYPE,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
		);

		if ( '' !== $status && $this->valid_status( $status ) ) {
			$args['meta_query'] = $this->status_meta_query( 'lead_status', $status );
		}

		return count( get_posts( $args ) );
	}

	public function count_quote_requests( string $status = '' ): int {
		$args = array(
			'post_type'      => self::QUOTE_POST_TYPE,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
		);

		if ( '' !== $status && $this->valid_status( $status ) ) {
			$args['meta_query'] = $this->status_meta_query( '_smtp_status', $status );
		}

		return count( get_posts( $args ) );
	}

	/**
	 * Build a status query that treats legacy records without a stored status as New.
	 *
	 * @return array<int|string, mixed>
	 */
	public function status_meta_query( string $meta_key, string $status ): array {
		if ( 'new' !== $status ) {
			return array(
				array(
					'key'   => $meta_key,
					'value' => $status,
				),
			);
		}

		return array(
			'relation' => 'OR',
			array(
				'key'   => $meta_key,
				'value' => 'new',
			),
			array(
				'key'     => $meta_key,
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'   => $meta_key,
				'value' => '',
			),
		);
	}

	public function status( int $post_id ): string {
		$key    = $this->meta_key( $post_id, 'status' );
		$status = (string) get_post_meta( $post_id, $key, true );

		return $this->valid_status( $status ) ? $status : 'new';
	}

	public function source( int $post_id ): string {
		$key    = $this->is_quote_request( $post_id ) ? '_smtp_source' : 'lead_source';
		$source = (string) get_post_meta( $post_id, $key, true );

		if ( '' === $source ) {
			return $this->is_quote_request( $post_id ) ? 'product-quote-form' : 'manual';
		}

		return $source;
	}

	public function assigned_to( int $post_id ): int {
		return absint( get_post_meta( $post_id, $this->meta_key( $post_id, 'assigned_to' ), true ) );
	}

	public function last_activity( int $post_id ): string {
		return (string) get_post_meta( $post_id, $this->meta_key( $post_id, 'last_activity' ), true );
	}

	/**
	 * Update status and create an auditable activity event only when it changes.
	 */
	public function update_status( int $post_id, string $status, int $actor_id = 0 ): bool {
		$status = sanitize_key( $status );

		if ( ! $this->valid_status( $status ) ) {
			return false;
		}

		$previous = $this->status( $post_id );
		$key      = $this->meta_key( $post_id, 'status' );
		$updated  = update_post_meta( $post_id, $key, $status );
		$this->touch( $post_id );

		if ( $previous !== $status ) {
			$this->add_activity(
				$post_id,
				'status_changed',
				sprintf( 'Status changed from %s to %s.', $previous, $status ),
				array(
					'from'     => $previous,
					'to'       => $status,
					'actor_id' => $actor_id,
				)
			);
		}

		return false !== $updated;
	}

	/**
	 * Assign a lead or quote request to a WordPress user.
	 */
	public function assign( int $post_id, int $user_id, int $actor_id = 0 ): bool {
		$user_id  = max( 0, $user_id );
		$previous = $this->assigned_to( $post_id );
		$updated  = update_post_meta( $post_id, $this->meta_key( $post_id, 'assigned_to' ), $user_id );
		$this->touch( $post_id );

		if ( $previous !== $user_id ) {
			$this->add_activity(
				$post_id,
				'assignment_changed',
				0 === $user_id ? 'Record assignment cleared.' : 'Record assigned to user ID ' . $user_id . '.',
				array(
					'from'     => $previous,
					'to'       => $user_id,
					'actor_id' => $actor_id,
				)
			);
		}

		return false !== $updated;
	}

	/**
	 * @param array<string, mixed> $context Activity context.
	 */
	public function add_activity( int $post_id, string $event, string $message, array $context = array() ): void {
		$key     = $this->meta_key( $post_id, 'activity_history' );
		$history = get_post_meta( $post_id, $key, true );
		$history = is_array( $history ) ? $history : array();

		$history[] = array(
			'timestamp' => current_time( 'mysql' ),
			'event'     => sanitize_key( $event ),
			'message'   => sanitize_text_field( $message ),
			'user_id'   => absint( $context['actor_id'] ?? get_current_user_id() ),
			'context'   => $this->sanitize_context( $context ),
		);

		if ( count( $history ) > 100 ) {
			$history = array_slice( $history, -100 );
		}

		update_post_meta( $post_id, $key, $history );
		$this->touch( $post_id );
	}

	/**
	 * @return array<int, array<string, mixed>>
	 */
	public function activity_history( int $post_id ): array {
		$history = get_post_meta( $post_id, $this->meta_key( $post_id, 'activity_history' ), true );
		return is_array( $history ) ? array_reverse( $history ) : array();
	}

	public function initialize_quote_request( int $post_id, array $values = array() ): void {
		if ( ! $this->is_quote_request( $post_id ) ) {
			return;
		}

		if ( '' === (string) get_post_meta( $post_id, '_smtp_source', true ) ) {
			update_post_meta( $post_id, '_smtp_source', 'product-quote-form' );
		}

		if ( '' === (string) get_post_meta( $post_id, '_smtp_last_activity', true ) ) {
			update_post_meta( $post_id, '_smtp_last_activity', current_time( 'mysql' ) );
		}

		$this->add_activity(
			$post_id,
			'quote_request_created',
			'Product quote request created.',
			array( 'product_id' => absint( $values['product_id'] ?? 0 ) )
		);
	}

	public function owner_name( int $post_id ): string {
		$user_id = $this->assigned_to( $post_id );

		if ( ! $user_id ) {
			return 'Unassigned';
		}

		$user = get_userdata( $user_id );
		return $user ? (string) $user->display_name : 'User #' . $user_id;
	}

	private function touch( int $post_id ): void {
		update_post_meta( $post_id, $this->meta_key( $post_id, 'last_activity' ), current_time( 'mysql' ) );
	}

	private function is_quote_request( int $post_id ): bool {
		return self::QUOTE_POST_TYPE === get_post_type( $post_id );
	}

	private function meta_key( int $post_id, string $field ): string {
		if ( $this->is_quote_request( $post_id ) ) {
			$map = array(
				'status'           => '_smtp_status',
				'assigned_to'      => '_smtp_assigned_to',
				'last_activity'    => '_smtp_last_activity',
				'activity_history' => '_smtp_activity_history',
			);
		} else {
			$map = array(
				'status'           => 'lead_status',
				'assigned_to'      => 'assigned_to',
				'last_activity'    => 'last_activity',
				'activity_history' => 'activity_history',
			);
		}

		return $map[ $field ] ?? $field;
	}

	/**
	 * @param array<string, mixed> $context Raw context.
	 * @return array<string, scalar|null>
	 */
	private function sanitize_context( array $context ): array {
		$output = array();

		foreach ( $context as $key => $value ) {
			$key = sanitize_key( (string) $key );

			if ( is_bool( $value ) || is_int( $value ) || is_float( $value ) || null === $value ) {
				$output[ $key ] = $value;
			} elseif ( is_scalar( $value ) ) {
				$output[ $key ] = sanitize_text_field( (string) $value );
			}
		}

		return $output;
	}
}
