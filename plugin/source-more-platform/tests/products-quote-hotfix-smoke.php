<?php
/**
 * Static regression guard for the Product Quote hotfix.
 */
$root = dirname( __DIR__ );
$rate_limiter = file_get_contents( $root . '/includes/class-smtp-rate-limiter.php' );
$rest = file_get_contents( $root . '/includes/modules/products/class-smtp-products-rest.php' );
$js = file_get_contents( $root . '/assets/js/products.js' );

$checks = array(
    'rate limiter allow alias' => strpos( $rate_limiter, 'public static function allow' ) !== false,
    'RFQ stable bucket' => strpos( $rest, "SMTP_Rate_Limiter::allow( 'rfq', 5, HOUR_IN_SECONDS )" ) !== false,
    'non-JSON guard' => strpos( $js, "contentType.includes('application/json')" ) !== false,
);

foreach ( $checks as $label => $passed ) {
    if ( ! $passed ) {
        fwrite( STDERR, "FAIL: {$label}\n" );
        exit( 1 );
    }
}

echo "PASS: product quote hotfix smoke test\n";
