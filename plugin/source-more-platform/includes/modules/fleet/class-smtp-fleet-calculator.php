<?php
/**
 * Fleet savings calculation service.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Fleet_Calculator {
	/**
	 * Calculate annual and three-year fleet savings.
	 *
	 * @param array<string, mixed> $data Raw calculator inputs.
	 * @return array<string, float|int>
	 */
	public function calculate( array $data ): array {
		$inputs = array(
			'devices'      => max( 1, (int) $this->number( $data, 'devices' ) ),
			'mono_pages'   => $this->number( $data, 'mono_pages' ),
			'color_pages'  => $this->number( $data, 'color_pages' ),
			'mono_cpp'     => $this->number( $data, 'mono_cpp' ),
			'color_cpp'    => $this->number( $data, 'color_cpp' ),
			'fixed_cost'   => $this->number( $data, 'fixed_cost' ),
			'saving_rate'  => min( 35, max( 5, $this->number( $data, 'saving_rate' ) ) ),
		);

		/**
		 * Filter normalized fleet-calculator inputs before calculation.
		 *
		 * @param array<string, float|int> $inputs Normalized inputs.
		 * @param array<string, mixed>     $data   Raw input data.
		 */
		$inputs = apply_filters( 'smtp_fleet_calculation_inputs', $inputs, $data );

		$current   = ( ( (float) $inputs['mono_pages'] * (float) $inputs['mono_cpp'] ) + ( (float) $inputs['color_pages'] * (float) $inputs['color_cpp'] ) + (float) $inputs['fixed_cost'] ) * 12;
		$savings   = $current * ( (float) $inputs['saving_rate'] / 100 );
		$optimized = $current - $savings;
		$three     = $savings * 3;

		$result = array_merge(
			$inputs,
			array(
				'current_cost'   => round( $current, 2 ),
				'annual_savings' => round( $savings, 2 ),
				'optimized_cost' => round( $optimized, 2 ),
				'three_year'     => round( $three, 2 ),
			)
		);

		/**
		 * Filter the final fleet-calculation result.
		 *
		 * @param array<string, float|int> $result Calculated result.
		 * @param array<string, mixed>     $data   Raw input data.
		 */
		return apply_filters( 'smtp_fleet_calculation_result', $result, $data );
	}

	private function number( array $data, string $key ): float {
		return max( 0, (float) ( $data[ $key ] ?? 0 ) );
	}
}
