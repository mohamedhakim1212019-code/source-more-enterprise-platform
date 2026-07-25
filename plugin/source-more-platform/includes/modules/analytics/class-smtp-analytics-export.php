<?php
/**
 * Summary CSV export for the Analytics module.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Analytics_Export {
	private SMTP_Analytics_Service $service;
	private bool $registered = false;

	public function __construct( SMTP_Analytics_Service $service ) {
		$this->service = $service;
	}

	public function register_hooks(): void {
		if ( $this->registered ) {
			return;
		}

		add_action( 'admin_post_smtp_export_analytics', array( $this, 'download' ) );
		$this->registered = true;
	}

	public function download(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Not allowed', 403 );
		}

		check_admin_referer( 'smtp_export_analytics' );
		$range  = SMTP_Analytics_Date_Range::from_request( wp_unslash( $_GET ) );
		$report = $this->service->report( $range );

		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=source-more-analytics-' . gmdate( 'Y-m-d' ) . '.csv' );
		$output = fopen( 'php://output', 'w' );
		fwrite( $output, "\xEF\xBB\xBF" );
		fputcsv( $output, array( 'Source More Analytics Report', $range->label() ) );
		fputcsv( $output, array() );

		fputcsv( $output, array( 'Summary', 'Value' ) );
		foreach ( $report['summary'] as $metric => $value ) {
			fputcsv( $output, array( $this->label( $metric ), $value ) );
		}

		$this->pipeline_rows( $output, 'Lead Pipeline', $report['lead_pipeline'] );
		$this->pipeline_rows( $output, 'Quote Request Pipeline', $report['quote_pipeline'] );

		fputcsv( $output, array() );
		fputcsv( $output, array( 'Opportunity Sources', 'Total', 'Won', 'Lost', 'Closed Win Rate (%)' ) );
		foreach ( $report['sources'] as $row ) {
			fputcsv( $output, array( $row['label'], $row['total'], $row['won'], $row['lost'], $row['closed_win_rate'] ) );
		}

		fputcsv( $output, array() );
		fputcsv( $output, array( 'Fleet Assessment', 'Value' ) );
		foreach ( $report['fleet'] as $metric => $value ) {
			fputcsv( $output, array( $this->label( $metric ), $value ) );
		}

		fputcsv( $output, array() );
		fputcsv( $output, array( 'AI Assistant', 'Value' ) );
		foreach ( $report['assistant'] as $metric => $value ) {
			fputcsv( $output, array( $this->label( $metric ), $value ) );
		}

		fputcsv( $output, array() );
		fputcsv( $output, array( 'Product Demand', 'Requests', 'Requested Quantity' ) );
		foreach ( $report['product_demand'] as $row ) {
			fputcsv( $output, array( $row['product'], $row['requests'], $row['quantity'] ) );
		}

		fclose( $output );
		exit;
	}

	private function pipeline_rows( $output, string $title, array $pipeline ): void {
		fputcsv( $output, array() );
		fputcsv( $output, array( $title, 'Count' ) );
		fputcsv( $output, array( 'Total', $pipeline['total'] ) );
		foreach ( $pipeline['counts'] as $status => $count ) {
			fputcsv( $output, array( $this->label( $status ), $count ) );
		}
		fputcsv( $output, array( 'Closed Win Rate (%)', $pipeline['closed_win_rate'] ) );
	}

	private function label( string $key ): string {
		return ucwords( str_replace( '_', ' ', $key ) );
	}
}
