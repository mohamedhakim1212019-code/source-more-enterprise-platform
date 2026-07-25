<?php
/**
 * Analytics reporting date-range value object.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Analytics_Date_Range {
	private string $key;
	private string $label;
	private ?string $from;
	private ?string $to;

	private function __construct( string $key, string $label, ?string $from, ?string $to ) {
		$this->key   = $key;
		$this->label = $label;
		$this->from  = $from;
		$this->to    = $to;
	}

	/**
	 * Resolve a reporting period from request-like input.
	 *
	 * @param array<string,mixed> $request Request values.
	 */
	public static function from_request( array $request ): self {
		$key     = sanitize_key( (string) ( $request['range'] ?? '30' ) );
		$allowed = array( '7', '30', '90', '365', 'all', 'custom' );
		$key     = in_array( $key, $allowed, true ) ? $key : '30';
		$today   = function_exists( 'current_time' ) ? current_time( 'Y-m-d' ) : gmdate( 'Y-m-d' );

		if ( 'all' === $key ) {
			return new self( 'all', 'All time', null, null );
		}

		if ( 'custom' === $key ) {
			$from = self::valid_date( (string) ( $request['from'] ?? '' ) );
			$to   = self::valid_date( (string) ( $request['to'] ?? '' ) );

			if ( null !== $from && null !== $to ) {
				if ( $from > $to ) {
					$tmp  = $from;
					$from = $to;
					$to   = $tmp;
				}

				return new self( 'custom', self::date_label( $from, $to ), $from, $to );
			}

			$key = '30';
		}

		$days = max( 1, (int) $key );
		$to   = $today;
		$from = ( new DateTimeImmutable( $today ) )->modify( '-' . ( $days - 1 ) . ' days' )->format( 'Y-m-d' );

		return new self( (string) $days, 'Last ' . $days . ' days', $from, $to );
	}

	public function key(): string {
		return $this->key;
	}

	public function label(): string {
		return $this->label;
	}

	public function from(): ?string {
		return $this->from;
	}

	public function to(): ?string {
		return $this->to;
	}

	public function is_all_time(): bool {
		return null === $this->from || null === $this->to;
	}

	/**
	 * @return array<int,array<string,mixed>>
	 */
	public function date_query(): array {
		if ( $this->is_all_time() ) {
			return array();
		}

		return array(
			array(
				'after'     => $this->from . ' 00:00:00',
				'before'    => $this->to . ' 23:59:59',
				'inclusive' => true,
			),
		);
	}

	/**
	 * @return array<string,string>
	 */
	public function query_args(): array {
		$args = array( 'range' => $this->key );

		if ( 'custom' === $this->key && null !== $this->from && null !== $this->to ) {
			$args['from'] = $this->from;
			$args['to']   = $this->to;
		}

		return $args;
	}

	private static function valid_date( string $value ): ?string {
		$value = trim( $value );

		if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ) {
			return null;
		}

		$parts = array_map( 'intval', explode( '-', $value ) );
		return checkdate( $parts[1], $parts[2], $parts[0] ) ? $value : null;
	}

	private static function date_label( string $from, string $to ): string {
		return $from === $to ? $from : $from . ' to ' . $to;
	}
}
