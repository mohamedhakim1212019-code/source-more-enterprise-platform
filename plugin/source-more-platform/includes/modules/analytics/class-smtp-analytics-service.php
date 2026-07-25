<?php
/**
 * Reporting calculations for CRM, Fleet, Products, and the AI Assistant.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Analytics_Service {
	private SMTP_Analytics_Repository $repository;
	private SMTP_CRM_Repository $crm;

	public function __construct( SMTP_Analytics_Repository $repository, SMTP_CRM_Repository $crm ) {
		$this->repository = $repository;
		$this->crm        = $crm;
	}

	/**
	 * Build one presentation-safe analytics report.
	 *
	 * @return array<string,mixed>
	 */
	public function report( SMTP_Analytics_Date_Range $range ): array {
		$lead_ids         = $this->repository->lead_ids( $range );
		$quote_ids        = $this->repository->quote_ids( $range );
		$conversation_ids = $this->repository->conversation_ids( $range );
		$lead_pipeline    = $this->pipeline( $lead_ids );
		$quote_pipeline   = $this->pipeline( $quote_ids );
		$all_ids          = array_merge( $lead_ids, $quote_ids );
		$won              = $lead_pipeline['won'] + $quote_pipeline['won'];
		$lost             = $lead_pipeline['lost'] + $quote_pipeline['lost'];
		$closed           = $won + $lost;

		return array(
			'range'       => $range,
			'summary'     => array(
				'total_opportunities' => count( $all_ids ),
				'open_opportunities'  => $lead_pipeline['open'] + $quote_pipeline['open'],
				'won'                 => $won,
				'lost'                => $lost,
				'closed_win_rate'     => $this->percent( $won, $closed ),
				'published_products'  => $this->repository->published_product_count(),
			),
			'lead_pipeline'  => $lead_pipeline,
			'quote_pipeline' => $quote_pipeline,
			'sources'        => $this->sources( $all_ids ),
			'fleet'          => $this->fleet( $lead_ids ),
			'assistant'      => $this->assistant( $conversation_ids ),
			'product_demand' => $this->product_demand( $quote_ids ),
			'recent'         => $this->recent( $lead_ids, $quote_ids ),
		);
	}

	/**
	 * @param int[] $ids Record IDs.
	 * @return array<string,mixed>
	 */
	private function pipeline( array $ids ): array {
		$counts = array_fill_keys( array_keys( $this->crm->statuses() ), 0 );

		foreach ( $ids as $id ) {
			$status = $this->repository->status( $id );
			if ( isset( $counts[ $status ] ) ) {
				$counts[ $status ]++;
			}
		}

		$won    = (int) $counts['won'];
		$lost   = (int) $counts['lost'];
		$closed = $won + $lost;

		return array(
			'total'           => count( $ids ),
			'counts'          => $counts,
			'open'            => count( $ids ) - $closed,
			'won'             => $won,
			'lost'            => $lost,
			'closed_win_rate' => $this->percent( $won, $closed ),
		);
	}

	/**
	 * @param int[] $ids Record IDs.
	 * @return array<int,array<string,mixed>>
	 */
	private function sources( array $ids ): array {
		$rows = array();

		foreach ( $ids as $id ) {
			$source = $this->repository->source( $id ) ?: 'manual';
			$status = $this->repository->status( $id );

			if ( ! isset( $rows[ $source ] ) ) {
				$rows[ $source ] = array(
					'key'   => $source,
					'label' => $this->source_label( $source ),
					'total' => 0,
					'won'   => 0,
					'lost'  => 0,
				);
			}

			$rows[ $source ]['total']++;
			if ( 'won' === $status ) {
				$rows[ $source ]['won']++;
			}
			if ( 'lost' === $status ) {
				$rows[ $source ]['lost']++;
			}
		}

		foreach ( $rows as &$row ) {
			$row['closed_win_rate'] = $this->percent( $row['won'], $row['won'] + $row['lost'] );
		}
		unset( $row );

		usort( $rows, static fn( array $a, array $b ): int => $b['total'] <=> $a['total'] );
		return array_values( $rows );
	}

	/**
	 * @param int[] $lead_ids Lead IDs.
	 * @return array<string,float|int>
	 */
	private function fleet( array $lead_ids ): array {
		$count              = 0;
		$current_cost       = 0.0;
		$annual_savings     = 0.0;
		$three_year_savings = 0.0;
		$devices            = 0;
		$saving_rates       = array();

		foreach ( $lead_ids as $id ) {
			$source  = $this->repository->source( $id );
			$savings = $this->number( $this->repository->meta( $id, 'annual_savings' ) );

			if ( 'fleet-calculator' !== $source && $savings <= 0 ) {
				continue;
			}

			$count++;
			$current_cost       += $this->number( $this->repository->meta( $id, 'current_cost' ) );
			$annual_savings     += $savings;
			$three_year_savings += $this->number( $this->repository->meta( $id, 'three_year' ) );
			$devices            += absint( $this->repository->meta( $id, 'devices' ) );
			$rate                = $this->number( $this->repository->meta( $id, 'saving_rate' ) );
			if ( $rate > 0 ) {
				$saving_rates[] = $rate;
			}
		}

		return array(
			'assessments'          => $count,
			'devices'              => $devices,
			'current_annual_cost'  => round( $current_cost, 2 ),
			'annual_savings'       => round( $annual_savings, 2 ),
			'three_year_savings'   => round( $three_year_savings, 2 ),
			'average_savings'      => $count ? round( $annual_savings / $count, 2 ) : 0.0,
			'average_saving_rate'  => $saving_rates ? round( array_sum( $saving_rates ) / count( $saving_rates ), 1 ) : 0.0,
		);
	}

	/**
	 * @param int[] $conversation_ids Conversation IDs.
	 * @return array<string,float|int>
	 */
	private function assistant( array $conversation_ids ): array {
		$converted = 0;
		$messages  = 0;

		foreach ( $conversation_ids as $id ) {
			$status = sanitize_key( (string) $this->repository->meta( $id, '_smtp_assistant_status' ) );
			if ( 'converted' === $status || absint( $this->repository->meta( $id, '_smtp_assistant_lead_id' ) ) > 0 ) {
				$converted++;
			}
			$messages += absint( $this->repository->meta( $id, '_smtp_assistant_message_count' ) );
		}

		return array(
			'conversations'   => count( $conversation_ids ),
			'converted'       => $converted,
			'messages'        => $messages,
			'conversion_rate' => $this->percent( $converted, count( $conversation_ids ) ),
			'average_messages'=> count( $conversation_ids ) ? round( $messages / count( $conversation_ids ), 1 ) : 0.0,
		);
	}

	/**
	 * @param int[] $quote_ids Quote Request IDs.
	 * @return array<int,array<string,mixed>>
	 */
	private function product_demand( array $quote_ids ): array {
		$rows = array();

		foreach ( $quote_ids as $id ) {
			$name = trim( (string) $this->repository->meta( $id, '_smtp_product_name' ) );
			$name = '' !== $name ? $name : 'Unspecified product';
			$qty  = max( 1, absint( $this->repository->meta( $id, '_smtp_quantity' ) ) );

			if ( ! isset( $rows[ $name ] ) ) {
				$rows[ $name ] = array( 'product' => $name, 'requests' => 0, 'quantity' => 0 );
			}

			$rows[ $name ]['requests']++;
			$rows[ $name ]['quantity'] += $qty;
		}

		usort(
			$rows,
			static fn( array $a, array $b ): int => ( $b['requests'] <=> $a['requests'] ) ?: ( $b['quantity'] <=> $a['quantity'] )
		);

		return array_slice( array_values( $rows ), 0, 10 );
	}

	/**
	 * @param int[] $lead_ids Lead IDs.
	 * @param int[] $quote_ids Quote IDs.
	 * @return array<int,array<string,mixed>>
	 */
	private function recent( array $lead_ids, array $quote_ids ): array {
		$rows = array();

		foreach ( array( 'Lead' => $lead_ids, 'Quote Request' => $quote_ids ) as $type => $ids ) {
			foreach ( $ids as $id ) {
				$rows[] = array(
					'id'     => $id,
					'type'   => $type,
					'title'  => $this->repository->title( $id ),
					'date'   => $this->repository->date( $id ),
					'status' => $this->repository->status( $id ),
					'source' => $this->source_label( $this->repository->source( $id ) ),
					'owner'  => $this->repository->owner( $id ),
					'url'    => $this->repository->edit_url( $id ),
				);
			}
		}

		usort( $rows, static fn( array $a, array $b ): int => strcmp( $b['date'], $a['date'] ) );
		return array_slice( $rows, 0, 10 );
	}

	private function source_label( string $source ): string {
		$labels = array(
			'fleet-calculator'   => 'Fleet Calculator',
			'ai-assistant'       => 'AI Assistant',
			'website-contact'    => 'Website Contact',
			'product-quote-form' => 'Product Quote Form',
			'manual'             => 'Manual',
		);

		return $labels[ $source ] ?? ucwords( str_replace( array( '-', '_' ), ' ', $source ) );
	}

	private function percent( int $part, int $whole ): float {
		return $whole > 0 ? round( ( $part / $whole ) * 100, 1 ) : 0.0;
	}

	private function number( $value ): float {
		if ( is_string( $value ) ) {
			$value = str_replace( array( ',', 'EGP', ' ' ), '', $value );
		}

		return max( 0, (float) $value );
	}
}
