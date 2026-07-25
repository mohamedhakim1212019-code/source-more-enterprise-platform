<?php
/**
 * AI conversation persistence and retention.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Assistant_Conversations {
	private const TOKEN_META      = '_smtp_assistant_token_hash';
	private const MESSAGES_META   = '_smtp_assistant_messages';
	private const STATUS_META     = '_smtp_assistant_status';
	private const LAST_META       = '_smtp_assistant_last_activity';
	private const COUNT_META      = '_smtp_assistant_message_count';
	private const LEAD_META       = '_smtp_assistant_lead_id';
	private const CUSTOMER_META   = '_smtp_assistant_customer';
	private const MAX_MESSAGES    = 100;

	/**
	 * Create a conversation and return its public identity.
	 *
	 * @return array{conversation_id:int,conversation_token:string}|WP_Error
	 */
	public function create(): array|WP_Error {
		$token = wp_generate_password( 40, false, false );
		$title = sprintf( 'AI Conversation — %s', current_time( 'Y-m-d H:i' ) );
		$post_id = wp_insert_post(
			array(
				'post_type'   => SMTP_Assistant_Content_Types::CONVERSATION_POST_TYPE,
				'post_status' => 'publish',
				'post_title'  => $title,
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		update_post_meta( $post_id, self::TOKEN_META, $this->hash_token( $token ) );
		update_post_meta( $post_id, self::STATUS_META, 'active' );
		update_post_meta( $post_id, self::LAST_META, current_time( 'mysql' ) );
		update_post_meta( $post_id, self::COUNT_META, 0 );

		return array(
			'conversation_id'    => (int) $post_id,
			'conversation_token' => $token,
		);
	}

	/**
	 * Resolve an existing public conversation or create a new one.
	 *
	 * @return array{conversation_id:int,conversation_token:string}|WP_Error
	 */
	public function resolve( int $conversation_id = 0, string $token = '' ): array|WP_Error {
		if ( $conversation_id > 0 || '' !== $token ) {
			if ( $this->verify( $conversation_id, $token ) ) {
				return array(
					'conversation_id'    => $conversation_id,
					'conversation_token' => $token,
				);
			}

			return new WP_Error( 'invalid_conversation', 'The assistant conversation could not be verified.', array( 'status' => 403 ) );
		}

		return $this->create();
	}

	public function verify( int $conversation_id, string $token ): bool {
		if ( $conversation_id <= 0 || '' === $token || SMTP_Assistant_Content_Types::CONVERSATION_POST_TYPE !== get_post_type( $conversation_id ) ) {
			return false;
		}

		$stored = (string) get_post_meta( $conversation_id, self::TOKEN_META, true );
		return '' !== $stored && hash_equals( $stored, $this->hash_token( $token ) );
	}

	/**
	 * Store a sanitized user, assistant, or system message.
	 */
	public function add_message( int $conversation_id, string $role, string $content, string $source = 'platform' ): void {
		if ( ! in_array( $role, array( 'user', 'assistant', 'system' ), true ) || SMTP_Assistant_Content_Types::CONVERSATION_POST_TYPE !== get_post_type( $conversation_id ) ) {
			return;
		}

		$content = trim( wp_strip_all_tags( $content ) );
		if ( '' === $content ) {
			return;
		}

		$messages = get_post_meta( $conversation_id, self::MESSAGES_META, true );
		$messages = is_array( $messages ) ? $messages : array();
		$messages[] = array(
			'role'      => $role,
			'content'   => $this->substr( $content, 0, 2500 ),
			'source'    => sanitize_key( $source ),
			'timestamp' => current_time( 'mysql' ),
		);

		if ( count( $messages ) > self::MAX_MESSAGES ) {
			$messages = array_slice( $messages, -self::MAX_MESSAGES );
		}

		update_post_meta( $conversation_id, self::MESSAGES_META, $messages );
		update_post_meta( $conversation_id, self::COUNT_META, count( $messages ) );
		update_post_meta( $conversation_id, self::LAST_META, current_time( 'mysql' ) );

		if ( 'user' === $role && 1 === count( array_filter( $messages, static fn( array $message ): bool => 'user' === ( $message['role'] ?? '' ) ) ) ) {
			wp_update_post(
				array(
					'ID'         => $conversation_id,
					'post_title' => 'AI — ' . $this->substr( $content, 0, 70 ),
				)
			);
		}
	}

	/**
	 * @return array<int,array<string,string>>
	 */
	public function messages( int $conversation_id ): array {
		$messages = get_post_meta( $conversation_id, self::MESSAGES_META, true );
		return is_array( $messages ) ? $messages : array();
	}

	public function link_lead( int $conversation_id, int $lead_id, array $customer = array() ): void {
		if ( SMTP_Assistant_Content_Types::CONVERSATION_POST_TYPE !== get_post_type( $conversation_id ) ) {
			return;
		}

		update_post_meta( $conversation_id, self::LEAD_META, $lead_id );
		update_post_meta( $conversation_id, self::STATUS_META, 'converted' );
		update_post_meta( $conversation_id, self::LAST_META, current_time( 'mysql' ) );
		update_post_meta(
			$conversation_id,
			self::CUSTOMER_META,
			array(
				'company' => sanitize_text_field( $customer['company'] ?? '' ),
				'name'    => sanitize_text_field( $customer['contact_name'] ?? '' ),
				'email'   => sanitize_email( $customer['email'] ?? '' ),
				'phone'   => sanitize_text_field( $customer['phone'] ?? '' ),
			)
		);
	}

	public function status( int $conversation_id ): string {
		$status = sanitize_key( (string) get_post_meta( $conversation_id, self::STATUS_META, true ) );
		return in_array( $status, array( 'active', 'converted', 'closed' ), true ) ? $status : 'active';
	}

	public function lead_id( int $conversation_id ): int {
		return absint( get_post_meta( $conversation_id, self::LEAD_META, true ) );
	}

	public function message_count( int $conversation_id ): int {
		return absint( get_post_meta( $conversation_id, self::COUNT_META, true ) );
	}

	public function last_activity( int $conversation_id ): string {
		return (string) get_post_meta( $conversation_id, self::LAST_META, true );
	}

	/**
	 * Delete old unconverted conversations according to the platform setting.
	 */
	public function cleanup(): void {
		$options = SMTP_Settings::get();
		$days    = min( 365, max( 1, absint( $options['assistant_retention_days'] ?? 90 ) ) );
		$cutoff  = gmdate( 'Y-m-d H:i:s', time() - ( DAY_IN_SECONDS * $days ) );
		$ids     = get_posts(
			array(
				'post_type'      => SMTP_Assistant_Content_Types::CONVERSATION_POST_TYPE,
				'post_status'    => 'publish',
				'fields'         => 'ids',
				'posts_per_page' => 100,
				'date_query'     => array(
					array(
						'before'    => $cutoff,
						'inclusive' => true,
					),
				),
				'meta_query'     => array(
					array(
						'key'     => self::LEAD_META,
						'compare' => 'NOT EXISTS',
					),
				),
			)
		);

		foreach ( $ids as $id ) {
			wp_delete_post( (int) $id, true );
		}
	}

	private function substr( string $text, int $start, int $length ): string {
		return function_exists( 'mb_substr' ) ? mb_substr( $text, $start, $length ) : substr( $text, $start, $length );
	}

	private function hash_token( string $token ): string {
		return hash_hmac( 'sha256', $token, wp_salt( 'auth' ) );
	}
}
