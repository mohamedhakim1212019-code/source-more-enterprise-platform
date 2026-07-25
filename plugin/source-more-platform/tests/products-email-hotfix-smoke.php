<?php
/**
 * Static regression guard for Product Quote email delivery.
 */
$root = dirname( __DIR__ );
$rest = file_get_contents( $root . '/includes/modules/products/class-smtp-products-rest.php' );
$main = file_get_contents( $root . '/source-more-platform.php' );

$checks = array(
    'plugin version 3.6.1' => strpos( $main, "SMTP_PLATFORM_VERSION', '3.6.1" ) !== false,
    'configured notification email' => strpos( $rest, "options['notification_email']" ) !== false,
    'customer confirmation' => strpos( $rest, 'We received your quote request:' ) !== false,
    'admin delivery logging' => strpos( $rest, 'Quote request admin email failed' ) !== false,
    'customer delivery logging' => strpos( $rest, 'Quote request customer confirmation failed' ) !== false,
);

foreach ( $checks as $label => $passed ) {
    if ( ! $passed ) {
        fwrite( STDERR, "FAIL: {$label}\n" );
        exit( 1 );
    }
}

echo "PASS: product quote email hotfix smoke test\n";
