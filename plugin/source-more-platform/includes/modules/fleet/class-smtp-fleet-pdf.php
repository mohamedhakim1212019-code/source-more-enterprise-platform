<?php
/**
 * Minimal branded PDF renderer for Fleet Assessment reports.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Fleet_PDF {
	public function output_lead( int $lead_id ): void {
		$meta = static fn( string $key ) => get_post_meta( $lead_id, $key, true );
		$lines = array(
			array( 'Source More Technology', 22 ),
			array( 'Fleet Savings Assessment Report', 17 ),
			array( 'Generated: ' . date_i18n( 'd M Y' ), 10 ),
			array( '', 8 ),
			array( 'Prepared for', 14 ),
			array( $meta( 'company' ), 13 ),
			array( 'Contact: ' . $meta( 'contact_name' ) . ' | ' . $meta( 'email' ) . ' | ' . $meta( 'phone' ), 10 ),
			array( 'Industry: ' . $meta( 'industry' ) . ' | Locations: ' . $meta( 'locations' ), 10 ),
			array( '', 8 ),
			array( 'Executive Savings Summary', 14 ),
			array( 'Current annual print cost: ' . $this->money( $meta( 'current_cost' ) ), 12 ),
			array( 'Potential annual savings: ' . $this->money( $meta( 'annual_savings' ) ), 14 ),
			array( 'Optimized annual cost: ' . $this->money( $meta( 'optimized_cost' ) ), 12 ),
			array( 'Three-year savings opportunity: ' . $this->money( $meta( 'three_year' ) ), 12 ),
			array( '', 8 ),
			array( 'Current Environment', 14 ),
			array( 'Devices / MFPs: ' . $meta( 'devices' ), 11 ),
			array( 'Monthly mono pages: ' . number_format( (float) $meta( 'mono_pages' ) ), 11 ),
			array( 'Monthly color pages: ' . number_format( (float) $meta( 'color_pages' ) ), 11 ),
			array( 'Mono cost per page: EGP ' . $meta( 'mono_cpp' ), 11 ),
			array( 'Color cost per page: EGP ' . $meta( 'color_cpp' ), 11 ),
			array( 'Monthly service, rental and maintenance: ' . $this->money( $meta( 'fixed_cost' ) ), 11 ),
			array( 'Planning optimization assumption: ' . $meta( 'saving_rate' ) . '%', 11 ),
			array( '', 8 ),
			array( 'Recommended Next Steps', 14 ),
			array( '1. Validate the device inventory, meter readings and contracts.', 11 ),
			array( '2. Analyze utilization, placement, cost per page and service history.', 11 ),
			array( '3. Build a right-sized fleet and managed service roadmap.', 11 ),
			array( '4. Confirm savings through a professional on-site assessment.', 11 ),
			array( '', 8 ),
			array( 'Important note', 12 ),
			array( 'This report is an indicative planning estimate, not a commercial quotation. Verified savings require a detailed fleet assessment.', 9 ),
			array( '', 8 ),
			array( 'Source More Technology | One Source, More Value', 11 ),
			array( home_url( '/' ), 9 ),
		);

		$content = "BT\n";
		$y       = 800;

		foreach ( $lines as $row ) {
			list( $text, $size ) = $row;
			if ( $y < 55 ) {
				$content .= "ET\n";
				break;
			}
			$content .= "/F1 {$size} Tf\n1 0 0 1 55 {$y} Tm\n(" . $this->clean( $text ) . ") Tj\n";
			$y       -= ( $size + 8 );
		}
		$content .= 'ET';

		$objects   = array();
		$objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
		$objects[] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
		$objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>';
		$objects[] = '<< /Length ' . strlen( $content ) . ">>\nstream\n" . $content . "\nendstream";
		$objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
		$pdf       = "%PDF-1.4\n";
		$offsets   = array( 0 );

		foreach ( $objects as $index => $object ) {
			$offsets[] = strlen( $pdf );
			$number    = $index + 1;
			$pdf       .= "{$number} 0 obj\n{$object}\nendobj\n";
		}

		$xref = strlen( $pdf );
		$pdf .= 'xref' . "\n0 " . ( count( $objects ) + 1 ) . "\n0000000000 65535 f \n";
		for ( $index = 1; $index <= count( $objects ); $index++ ) {
			$pdf .= sprintf( '%010d 00000 n ', $offsets[ $index ] ) . "\n";
		}
		$pdf .= 'trailer << /Size ' . ( count( $objects ) + 1 ) . ' /Root 1 0 R >>' . "\nstartxref\n{$xref}\n%%EOF";

		$filename = 'source-more-fleet-savings-' . sanitize_file_name( (string) $meta( 'company' ) ) . '.pdf';
		while ( ob_get_level() ) {
			ob_end_clean();
		}
		nocache_headers();
		header( 'X-Content-Type-Options: nosniff' );
		header( 'Content-Type: application/pdf' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		header( 'Content-Length: ' . strlen( $pdf ) );
		echo $pdf; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Binary PDF output.
	}

	private function clean( $value ): string {
		$value     = wp_strip_all_tags( (string) $value );
		$converted = function_exists( 'iconv' ) ? iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $value ) : $value;
		$value     = false !== $converted ? $converted : $value;
		return str_replace( array( '\\', '(', ')' ), array( '\\\\', '\\(', '\\)' ), $value );
	}

	private function money( $number ): string {
		return 'EGP ' . number_format( (float) $number, 0 );
	}
}
