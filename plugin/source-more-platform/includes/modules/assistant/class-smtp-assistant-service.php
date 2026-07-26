<?php
/**
 * AI Assistant orchestration service.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Assistant_Service {
	private SMTP_Assistant_Knowledge $knowledge;
	private SMTP_Assistant_Endpoint $endpoint;
	private SMTP_Assistant_Conversations $conversations;

	public function __construct( SMTP_Assistant_Knowledge $knowledge, SMTP_Assistant_Endpoint $endpoint, SMTP_Assistant_Conversations $conversations ) {
		$this->knowledge     = $knowledge;
		$this->endpoint      = $endpoint;
		$this->conversations = $conversations;
	}

	/** @return array<string,mixed>|WP_Error */
	public function reply( string $question, int $conversation_id = 0, string $conversation_token = '', string $language = 'en' ): array|WP_Error {
		$lang     = SMTP_I18n::language( $language );
		$question = trim( wp_strip_all_tags( $question ) );
		if ( '' === $question || $this->length( $question ) > 500 ) {
			return new WP_Error( 'validation', SMTP_I18n::text( 'Please enter a valid question.', 'يرجى كتابة سؤال صحيح.', $lang ), array( 'status' => 422 ) );
		}

		$options  = SMTP_Settings::get();
		$identity = array( 'conversation_id' => 0, 'conversation_token' => '' );
		if ( ! empty( $options['assistant_conversation_logging'] ) ) {
			$identity = $this->conversations->resolve( $conversation_id, $conversation_token );
			if ( is_wp_error( $identity ) ) return $identity;
			$this->conversations->add_message( $identity['conversation_id'], 'user', $question, 'visitor-' . $lang );
		}

		$answer = $this->endpoint->reply( $question, (int) $identity['conversation_id'], $lang );
		if ( null === $answer ) $answer = $this->knowledge->answer( $question, $lang );
		$reply = $this->substr( trim( wp_strip_all_tags( (string) $answer['reply'] ) ), 0, 2500 );
		if ( '' === $reply ) $reply = SMTP_I18n::text( 'Please contact Source More Technology for a detailed assessment.', 'يرجى التواصل مع سورس مور تكنولوجي للحصول على تقييم تفصيلي.', $lang );

		if ( (int) $identity['conversation_id'] > 0 ) {
			$this->conversations->add_message( (int) $identity['conversation_id'], 'assistant', $reply, (string) ( $answer['source'] ?? 'platform' ) );
		}

		return array(
			'reply'              => $reply,
			'source'             => sanitize_key( (string) ( $answer['source'] ?? 'platform' ) ),
			'action'             => sanitize_key( (string) ( $answer['action'] ?? '' ) ),
			'language'           => $lang,
			'conversation_id'    => (int) $identity['conversation_id'],
			'conversation_token' => (string) $identity['conversation_token'],
		);
	}

	private function length( string $text ): int { return function_exists( 'mb_strlen' ) ? mb_strlen( $text ) : strlen( $text ); }
	private function substr( string $text, int $start, int $length ): string { return function_exists( 'mb_substr' ) ? mb_substr( $text, $start, $length ) : substr( $text, $start, $length ); }
}
