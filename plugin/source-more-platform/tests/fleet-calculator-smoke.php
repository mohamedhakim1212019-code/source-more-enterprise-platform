<?php
/**
 * Isolated smoke test for Fleet savings calculations.
 *
 * Run: php tests/fleet-calculator-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );
function apply_filters( string $hook, $value, ...$args ) { return $value; }
require_once dirname( __DIR__ ) . '/includes/modules/fleet/class-smtp-fleet-calculator.php';

$calculator = new SMTP_Fleet_Calculator();
$result = $calculator->calculate(
	array(
		'devices'      => 10,
		'mono_pages'   => 10000,
		'color_pages'  => 1000,
		'mono_cpp'     => 0.10,
		'color_cpp'    => 1.00,
		'fixed_cost'   => 500,
		'saving_rate'  => 20,
	)
);

$assert = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
};

$assert( 30000.0 === $result['current_cost'], 'Current annual cost calculation is incorrect.' );
$assert( 6000.0 === $result['annual_savings'], 'Annual savings calculation is incorrect.' );
$assert( 24000.0 === $result['optimized_cost'], 'Optimized annual cost calculation is incorrect.' );
$assert( 18000.0 === $result['three_year'], 'Three-year savings calculation is incorrect.' );
$assert( 10 === $result['devices'], 'Device count should be preserved.' );

$bounded = $calculator->calculate( array( 'saving_rate' => 99 ) );
$assert( 35.0 === (float) $bounded['saving_rate'], 'Saving rate must be capped at 35 percent.' );
$assert( 1 === $bounded['devices'], 'Device count must have a minimum of one.' );

fwrite( STDOUT, "PASS: Fleet calculator smoke test\n" );
