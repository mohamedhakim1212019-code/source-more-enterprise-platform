<?php
/**
 * Secure external AI endpoint adapter.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Assistant_Endpoint {
	/**
	 * Return an external reply or null so the deterministic knowledge layer can answer.
	 *
	 * @return array{reply:string,source:string,action:string}|null
	 */
	public function reply( string $question, int $conversation_id = 0 ): ?array {
		$options  = SMTP_Settings::get();
		$endpoint = esc_url_raw( (string) ( $options['assistant_endpoint'] ?? '' ) );

		if ( '' === $endpoint || ! str_starts_with( strtolower( $endpoint ), 'https://' ) ) {
			return null;
		}

		$response = wp_remote_post(
			$endpoint,
			array(
				'timeout'     => 20,
				'redirection' => 2,
				'headers'     => array( 'Content-Type' => 'application/json' ),
				'body'        => wp_json_encode(
					array(
						'message'         => $question,
						'source'          => 'source-more-wordpress',
						'conversation_id' => $conversation_id,
					)
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			SMTP_Logger::warning( 'Assistant endpoint failed', array( 'error' => $response->get_error_message() ) );
			return null;
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( $code < 200 || $code >= 300 ) {
			SMTP_Logger::warning( 'Assistant endpoint returned an error', array( 'status' => $code ) );
			return null;
		}

		$json = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! is_array( $json ) ) {
			SMTP_Logger::warning( 'Assistant endpoint returned invalid JSON' );
			return null;
		}

		$text = trim( wp_strip_all_tags( (string) ( $json['reply'] ?? $json['message'] ?? $json['response'] ?? '' ) ) );
		if ( '' === $text ) {
			return null;
		}

		$action = sanitize_key( (string) ( $json['action'] ?? '' ) );
		if ( ! in_array( $action, array( '', 'calculator', 'products', 'contact', 'lead_capture' ), true ) ) {
			$action = '';
		}

		return array(
			'reply'  => function_exists( 'mb_substr' ) ? mb_substr( $text, 0, 2500 ) : substr( $text, 0, 2500 ),
			'source' => 'external-endpoint',
			'action' => $action,
		);
	}
}
