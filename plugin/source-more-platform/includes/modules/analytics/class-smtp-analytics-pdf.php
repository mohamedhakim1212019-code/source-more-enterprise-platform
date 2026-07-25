<?php
/**
 * Printable PDF export for the Reports & Analytics module.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Analytics_PDF {
	private SMTP_Analytics_Service $service;
	private bool $registered = false;

	public function __construct( SMTP_Analytics_Service $service ) {
		$this->service = $service;
	}

	public function register_hooks(): void {
		if ( $this->registered ) {
			return;
		}

		add_action( 'admin_post_smtp_export_analytics_pdf', array( $this, 'download' ) );
		$this->registered = true;
	}

	public function download(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Not allowed', 403 );
		}

		check_admin_referer( 'smtp_export_analytics_pdf' );
		$range  = SMTP_Analytics_Date_Range::from_request( wp_unslash( $_GET ) );
		$report = $this->service->report( $range );
		$pdf    = $this->render( $report, $range );

		while ( ob_get_level() ) {
			ob_end_clean();
		}

		$filename = 'source-more-analytics-' . gmdate( 'Y-m-d' ) . '.pdf';
		nocache_headers();
		header( 'X-Content-Type-Options: nosniff' );
		header( 'Content-Type: application/pdf' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		header( 'Content-Length: ' . strlen( $pdf ) );
		echo $pdf; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Binary PDF output.
		exit;
	}

	/**
	 * Build a printable analytics PDF.
	 *
	 * @param array<string,mixed>       $report Analytics report data.
	 * @param SMTP_Analytics_Date_Range $range  Selected reporting period.
	 */
	public function render( array $report, SMTP_Analytics_Date_Range $range ): string {
		$document = new SMTP_Analytics_PDF_Document(
			'Source More Technology',
			'Reports & Analytics',
			$range->label()
		);

		$document->paragraph(
			'Commercial performance summary covering CRM leads, quote requests, fleet assessments, products, and the AI Assistant.',
			10
		);

		$document->section( 'Executive Summary' );
		$document->key_value_table(
			array(
				array( 'Total opportunities', $report['summary']['total_opportunities'] ),
				array( 'Open pipeline', $report['summary']['open_opportunities'] ),
				array( 'Won', $report['summary']['won'] ),
				array( 'Lost', $report['summary']['lost'] ),
				array( 'Closed win rate', $this->percent( $report['summary']['closed_win_rate'] ) ),
				array( 'Published products', $report['summary']['published_products'] ),
			)
		);

		$this->pipeline( $document, 'Lead Pipeline', $report['lead_pipeline'] );
		$this->pipeline( $document, 'Quote Request Pipeline', $report['quote_pipeline'] );

		$document->section( 'Opportunity Sources' );
		$source_rows = array();
		foreach ( $report['sources'] as $row ) {
			$source_rows[] = array(
				$row['label'],
				$row['total'],
				$row['won'],
				$row['lost'],
				$this->percent( $row['closed_win_rate'] ),
			);
		}
		$document->table(
			array( 'Source', 'Total', 'Won', 'Lost', 'Win rate' ),
			$source_rows,
			array( 205, 70, 65, 65, 90 )
		);

		$document->section( 'Fleet Assessment Value' );
		$document->key_value_table(
			array(
				array( 'Assessments', $report['fleet']['assessments'] ),
				array( 'Devices assessed', $report['fleet']['devices'] ),
				array( 'Current annual cost', $this->money( $report['fleet']['current_annual_cost'] ) ),
				array( 'Estimated annual savings', $this->money( $report['fleet']['annual_savings'] ) ),
				array( 'Three-year savings', $this->money( $report['fleet']['three_year_savings'] ) ),
				array( 'Average savings per assessment', $this->money( $report['fleet']['average_savings'] ) ),
				array( 'Average optimization', $this->percent( $report['fleet']['average_saving_rate'] ) ),
			)
		);

		$document->section( 'AI Assistant Performance' );
		$document->key_value_table(
			array(
				array( 'Conversations', $report['assistant']['conversations'] ),
				array( 'Converted to leads', $report['assistant']['converted'] ),
				array( 'Conversion rate', $this->percent( $report['assistant']['conversion_rate'] ) ),
				array( 'Messages', $report['assistant']['messages'] ),
				array( 'Average messages', $report['assistant']['average_messages'] ),
			)
		);

		$document->section( 'Product Demand' );
		$product_rows = array();
		foreach ( $report['product_demand'] as $row ) {
			$product_rows[] = array( $row['product'], $row['requests'], $row['quantity'] );
		}
		$document->table(
			array( 'Product', 'Requests', 'Requested quantity' ),
			$product_rows,
			array( 305, 90, 100 )
		);

		$document->section( 'Recent Opportunities' );
		$recent_rows = array();
		foreach ( $report['recent'] as $row ) {
			$recent_rows[] = array(
				$row['date'],
				$row['title'],
				$row['type'],
				ucfirst( (string) $row['status'] ),
				$row['source'],
				$row['owner'],
			);
		}
		$document->table(
			array( 'Date', 'Record', 'Type', 'Status', 'Source', 'Owner' ),
			$recent_rows,
			array( 70, 145, 60, 60, 80, 80 ),
			7.2
		);

		$document->note( 'Generated from Source More Enterprise Platform. Financial values are shown in Egyptian pounds (EGP).' );
		return $document->output();
	}

	private function pipeline( SMTP_Analytics_PDF_Document $document, string $title, array $pipeline ): void {
		$document->section( $title );
		$rows = array( array( 'Total', $pipeline['total'] ) );
		foreach ( $pipeline['counts'] as $status => $count ) {
			$rows[] = array( ucwords( str_replace( '_', ' ', (string) $status ) ), $count );
		}
		$rows[] = array( 'Open', $pipeline['open'] );
		$rows[] = array( 'Closed win rate', $this->percent( $pipeline['closed_win_rate'] ) );
		$document->key_value_table( $rows );
	}

	private function money( $amount ): string {
		return 'EGP ' . number_format( (float) $amount, 0, '.', ',' );
	}

	private function percent( $value ): string {
		return number_format( (float) $value, 1 ) . '%';
	}
}

/**
 * Small dependency-free A4 PDF document writer.
 */
final class SMTP_Analytics_PDF_Document {
	private string $company;
	private string $title;
	private string $period;
	/** @var string[] */
	private array $pages = array();
	private int $page_index = -1;
	private float $y = 0.0;
	private const PAGE_WIDTH = 595.0;
	private const PAGE_HEIGHT = 842.0;
	private const LEFT = 50.0;
	private const CONTENT_WIDTH = 495.0;
	private const BOTTOM = 54.0;

	public function __construct( string $company, string $title, string $period ) {
		$this->company = $company;
		$this->title   = $title;
		$this->period  = $period;
		$this->new_page();
	}

	public function paragraph( string $text, float $size = 10.0 ): void {
		$lines  = $this->wrap( $text, self::CONTENT_WIDTH, $size );
		$height = ( count( $lines ) * ( $size + 4 ) ) + 5;
		$this->ensure_space( $height );
		foreach ( $lines as $line ) {
			$this->text( self::LEFT, $this->y, $line, $size, false, array( 0.22, 0.27, 0.32 ) );
			$this->y -= $size + 4;
		}
		$this->y -= 5;
	}

	public function section( string $title ): void {
		$this->ensure_space( 32 );
		$this->fill_rect( self::LEFT, $this->y - 3, 4, 20, array( 0.86, 0.68, 0.13 ) );
		$this->text( self::LEFT + 12, $this->y, $title, 13, true, array( 0.02, 0.19, 0.36 ) );
		$this->y -= 29;
	}

	/**
	 * @param array<int,array{0:mixed,1:mixed}> $rows Rows.
	 */
	public function key_value_table( array $rows ): void {
		$table_rows = array();
		foreach ( $rows as $row ) {
			$table_rows[] = array( (string) $row[0], (string) $row[1] );
		}
		$this->table( array( 'Metric', 'Value' ), $table_rows, array( 330, 165 ) );
	}

	/**
	 * @param string[]                     $headers Headers.
	 * @param array<int,array<int,mixed>>  $rows    Rows.
	 * @param float[]                      $widths  Column widths.
	 */
	public function table( array $headers, array $rows, array $widths, float $font_size = 8.5 ): void {
		$header_height = 24.0;
		$row_height    = 21.0;
		$this->ensure_space( $header_height + $row_height );
		$this->table_header( $headers, $widths, $header_height, $font_size );

		if ( ! $rows ) {
			$this->ensure_space( $row_height );
			$this->table_row( array( 'No data available for the selected period.' ), array( array_sum( $widths ) ), $row_height, $font_size, false );
			$this->y -= 8;
			return;
		}

		foreach ( $rows as $row_index => $row ) {
			if ( $this->y - $row_height < self::BOTTOM ) {
				$this->new_page();
				$this->table_header( $headers, $widths, $header_height, $font_size );
			}
			$this->table_row( $row, $widths, $row_height, $font_size, 0 === $row_index % 2 );
		}
		$this->y -= 8;
	}

	public function note( string $text ): void {
		$this->ensure_space( 35 );
		$this->fill_rect( self::LEFT, $this->y - 22, self::CONTENT_WIDTH, 29, array( 0.96, 0.97, 0.98 ) );
		$lines = $this->wrap( $text, self::CONTENT_WIDTH - 20, 8.5 );
		$line_y = $this->y - 3;
		foreach ( array_slice( $lines, 0, 2 ) as $line ) {
			$this->text( self::LEFT + 10, $line_y, $line, 8.5, false, array( 0.30, 0.34, 0.38 ) );
			$line_y -= 11;
		}
		$this->y -= 38;
	}

	public function output(): string {
		$total_pages = count( $this->pages );
		foreach ( $this->pages as $index => &$content ) {
			$page_number = $index + 1;
			$content .= $this->text_command( self::LEFT, 28, $this->company . ' | One Source, More Value', 8, false, array( 0.38, 0.42, 0.46 ) );
			$content .= $this->text_command( 465, 28, 'Page ' . $page_number . ' of ' . $total_pages, 8, false, array( 0.38, 0.42, 0.46 ) );
			$content .= $this->line_command( self::LEFT, 42, self::LEFT + self::CONTENT_WIDTH, 42, array( 0.82, 0.84, 0.86 ), 0.7 );
		}
		unset( $content );

		return $this->assemble_pdf();
	}

	private function new_page(): void {
		$this->pages[]   = '';
		$this->page_index = count( $this->pages ) - 1;
		$this->y          = 742.0;

		$this->fill_rect( 0, 772, self::PAGE_WIDTH, 70, array( 0.02, 0.19, 0.36 ) );
		$this->fill_rect( 0, 768, self::PAGE_WIDTH, 4, array( 0.86, 0.68, 0.13 ) );
		$this->text( self::LEFT, 808, $this->company, 17, true, array( 1, 1, 1 ) );
		$this->text( self::LEFT, 788, $this->title, 11, false, array( 0.90, 0.93, 0.96 ) );
		$this->text( 390, 796, 'Period: ' . $this->period, 8.5, false, array( 0.90, 0.93, 0.96 ) );
	}

	private function ensure_space( float $height ): void {
		if ( $this->y - $height < self::BOTTOM ) {
			$this->new_page();
		}
	}

	/** @param string[] $headers @param float[] $widths */
	private function table_header( array $headers, array $widths, float $height, float $font_size ): void {
		$this->fill_rect( self::LEFT, $this->y - $height + 5, array_sum( $widths ), $height, array( 0.02, 0.19, 0.36 ) );
		$x = self::LEFT;
		foreach ( $headers as $index => $header ) {
			$this->text( $x + 6, $this->y - 10, $this->truncate( (string) $header, $widths[ $index ] - 12, $font_size ), $font_size, true, array( 1, 1, 1 ) );
			$x += $widths[ $index ];
		}
		$this->y -= $height;
	}

	/** @param array<int,mixed> $row @param float[] $widths */
	private function table_row( array $row, array $widths, float $height, float $font_size, bool $shade ): void {
		if ( $shade ) {
			$this->fill_rect( self::LEFT, $this->y - $height + 5, array_sum( $widths ), $height, array( 0.965, 0.973, 0.98 ) );
		}
		$x = self::LEFT;
		foreach ( $widths as $index => $width ) {
			$value = isset( $row[ $index ] ) ? (string) $row[ $index ] : '';
			$this->text( $x + 6, $this->y - 10, $this->truncate( $value, $width - 12, $font_size ), $font_size, false, array( 0.12, 0.15, 0.18 ) );
			$x += $width;
		}
		$this->line( self::LEFT, $this->y - $height + 5, self::LEFT + array_sum( $widths ), $this->y - $height + 5, array( 0.85, 0.87, 0.89 ), 0.4 );
		$this->y -= $height;
	}

	private function truncate( string $text, float $width, float $font_size ): string {
		$text     = $this->ascii( $text );
		$max_chars = max( 4, (int) floor( $width / max( 3.7, $font_size * 0.51 ) ) );
		if ( strlen( $text ) <= $max_chars ) {
			return $text;
		}
		return substr( $text, 0, max( 1, $max_chars - 3 ) ) . '...';
	}

	/** @return string[] */
	private function wrap( string $text, float $width, float $font_size ): array {
		$text      = $this->ascii( $text );
		$max_chars = max( 12, (int) floor( $width / max( 4.0, $font_size * 0.52 ) ) );
		$wrapped   = wordwrap( $text, $max_chars, "\n", true );
		return explode( "\n", $wrapped );
	}

	/** @param float[] $color */
	private function text( float $x, float $y, string $text, float $size, bool $bold, array $color ): void {
		$this->pages[ $this->page_index ] .= $this->text_command( $x, $y, $text, $size, $bold, $color );
	}

	/** @param float[] $color */
	private function fill_rect( float $x, float $y, float $width, float $height, array $color ): void {
		$this->pages[ $this->page_index ] .= sprintf(
			"%.3F %.3F %.3F rg\n%.2F %.2F %.2F %.2F re f\n",
			$color[0],
			$color[1],
			$color[2],
			$x,
			$y,
			$width,
			$height
		);
	}

	/** @param float[] $color */
	private function line( float $x1, float $y1, float $x2, float $y2, array $color, float $width ): void {
		$this->pages[ $this->page_index ] .= $this->line_command( $x1, $y1, $x2, $y2, $color, $width );
	}

	/** @param float[] $color */
	private function text_command( float $x, float $y, string $text, float $size, bool $bold, array $color ): string {
		$font = $bold ? 'F2' : 'F1';
		return sprintf(
			"BT\n/%s %.2F Tf\n%.3F %.3F %.3F rg\n1 0 0 1 %.2F %.2F Tm\n(%s) Tj\nET\n",
			$font,
			$size,
			$color[0],
			$color[1],
			$color[2],
			$x,
			$y,
			$this->escape( $text )
		);
	}

	/** @param float[] $color */
	private function line_command( float $x1, float $y1, float $x2, float $y2, array $color, float $width ): string {
		return sprintf(
			"%.3F %.3F %.3F RG\n%.2F w\n%.2F %.2F m\n%.2F %.2F l\nS\n",
			$color[0],
			$color[1],
			$color[2],
			$width,
			$x1,
			$y1,
			$x2,
			$y2
		);
	}

	private function escape( string $text ): string {
		$text = $this->ascii( $text );
		return str_replace( array( '\\', '(', ')' ), array( '\\\\', '\\(', '\\)' ), $text );
	}

	private function ascii( string $text ): string {
		$text = wp_strip_all_tags( $text );
		$text = str_replace( array( '–', '—', '’', '“', '”', '•' ), array( '-', '-', "'", '"', '"', '-' ), $text );
		if ( function_exists( 'iconv' ) ) {
			$converted = iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $text );
			if ( false !== $converted ) {
				$text = $converted;
			}
		}
		return preg_replace( '/[^\x20-\x7E]/', '', $text ) ?? '';
	}

	private function assemble_pdf(): string {
		$page_count        = count( $this->pages );
		$regular_font_obj  = 3 + ( 2 * $page_count );
		$bold_font_obj     = $regular_font_obj + 1;
		$objects           = array();
		$page_refs         = array();

		$objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
		for ( $index = 0; $index < $page_count; $index++ ) {
			$page_obj    = 3 + $index;
			$content_obj = 3 + $page_count + $index;
			$page_refs[] = $page_obj . ' 0 R';
			$objects[ $page_obj ] = sprintf(
				'<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %.0F %.0F] /Resources << /Font << /F1 %d 0 R /F2 %d 0 R >> >> /Contents %d 0 R >>',
				self::PAGE_WIDTH,
				self::PAGE_HEIGHT,
				$regular_font_obj,
				$bold_font_obj,
				$content_obj
			);
			$content = $this->pages[ $index ];
			$objects[ $content_obj ] = '<< /Length ' . strlen( $content ) . ">>\nstream\n" . $content . "\nendstream";
		}
		$objects[2] = '<< /Type /Pages /Kids [' . implode( ' ', $page_refs ) . '] /Count ' . $page_count . ' >>';
		$objects[ $regular_font_obj ] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
		$objects[ $bold_font_obj ]    = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>';
		ksort( $objects );

		$pdf     = "%PDF-1.4\n%SMEP\n";
		$offsets = array( 0 );
		$max_obj = max( array_keys( $objects ) );
		for ( $number = 1; $number <= $max_obj; $number++ ) {
			$offsets[ $number ] = strlen( $pdf );
			$pdf .= $number . " 0 obj\n" . $objects[ $number ] . "\nendobj\n";
		}

		$xref = strlen( $pdf );
		$pdf .= "xref\n0 " . ( $max_obj + 1 ) . "\n0000000000 65535 f \n";
		for ( $number = 1; $number <= $max_obj; $number++ ) {
			$pdf .= sprintf( '%010d 00000 n ', $offsets[ $number ] ) . "\n";
		}
		$pdf .= 'trailer << /Size ' . ( $max_obj + 1 ) . " /Root 1 0 R >>\nstartxref\n" . $xref . "\n%%EOF";
		return $pdf;
	}
}
