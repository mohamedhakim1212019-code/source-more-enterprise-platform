<?php
/**
 * Analytics administration screen.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Analytics_Admin {
	private SMTP_Analytics_Service $service;
	private SMTP_CRM_Repository $crm;
	private bool $registered = false;

	public function __construct( SMTP_Analytics_Service $service, SMTP_CRM_Repository $crm ) {
		$this->service = $service;
		$this->crm     = $crm;
	}

	public function register_hooks(): void {
		if ( $this->registered ) {
			return;
		}

		add_action( 'admin_menu', array( $this, 'menu' ), 25 );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		$this->registered = true;
	}

	public function menu(): void {
		add_submenu_page(
			'smtp-platform',
			'Reports & Analytics',
			'Reports & Analytics',
			'edit_posts',
			'smtp-analytics',
			array( $this, 'page' )
		);
	}

	public function assets(): void {
		if ( 'smtp-analytics' !== sanitize_key( (string) ( $_GET['page'] ?? '' ) ) ) {
			return;
		}

		wp_enqueue_style(
			'smtp-analytics',
			SMTP_PLATFORM_URL . 'assets/css/analytics.css',
			array(),
			SMTP_PLATFORM_VERSION
		);
	}

	public function page(): void {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$range  = SMTP_Analytics_Date_Range::from_request( wp_unslash( $_GET ) );
		$report = $this->service->report( $range );
		?>
		<div class="wrap smtp-analytics">
			<div class="smtp-analytics-head">
				<div>
					<h1>Reports &amp; Analytics</h1>
					<p>Commercial performance across CRM leads, quote requests, fleet assessments, products, and the AI Assistant.</p>
				</div>
				<?php if ( current_user_can( 'manage_options' ) ) : ?>
					<div class="smtp-analytics-actions">
						<a class="button" href="<?php echo esc_url( $this->export_url( $range ) ); ?>">Export Summary CSV</a>
						<a class="button button-primary" href="<?php echo esc_url( $this->pdf_url( $range ) ); ?>">Download PDF Report</a>
					</div>
				<?php endif; ?>
			</div>

			<?php $this->filters( $range ); ?>
			<p class="smtp-period">Reporting period: <strong><?php echo esc_html( $range->label() ); ?></strong></p>

			<div class="smtp-analytics-cards">
				<?php $this->card( 'Total opportunities', $report['summary']['total_opportunities'] ); ?>
				<?php $this->card( 'Open pipeline', $report['summary']['open_opportunities'] ); ?>
				<?php $this->card( 'Won', $report['summary']['won'] ); ?>
				<?php $this->card( 'Closed win rate', $this->format_percent( $report['summary']['closed_win_rate'] ) ); ?>
				<?php $this->card( 'Published products', $report['summary']['published_products'] ); ?>
			</div>

			<div class="smtp-analytics-grid smtp-analytics-grid-2">
				<?php $this->pipeline_panel( 'Lead Pipeline', $report['lead_pipeline'], SMTP_CRM_Content_Types::POST_TYPE, $range ); ?>
				<?php $this->pipeline_panel( 'Quote Request Pipeline', $report['quote_pipeline'], SMTP_CRM_Repository::QUOTE_POST_TYPE, $range ); ?>
			</div>

			<div class="smtp-analytics-grid smtp-analytics-grid-2">
				<section class="smtp-analytics-panel">
					<h2>Opportunity Sources</h2>
					<?php $this->sources_table( $report['sources'] ); ?>
				</section>
				<section class="smtp-analytics-panel">
					<h2>Fleet Assessment Value</h2>
					<div class="smtp-mini-cards">
						<?php $this->mini_card( 'Assessments', $report['fleet']['assessments'] ); ?>
						<?php $this->mini_card( 'Devices assessed', $report['fleet']['devices'] ); ?>
						<?php $this->mini_card( 'Current annual cost', $this->format_money( $report['fleet']['current_annual_cost'] ) ); ?>
						<?php $this->mini_card( 'Estimated annual savings', $this->format_money( $report['fleet']['annual_savings'] ) ); ?>
						<?php $this->mini_card( 'Three-year savings', $this->format_money( $report['fleet']['three_year_savings'] ) ); ?>
						<?php $this->mini_card( 'Average optimization', $this->format_percent( $report['fleet']['average_saving_rate'] ) ); ?>
					</div>
				</section>
			</div>

			<div class="smtp-analytics-grid smtp-analytics-grid-2">
				<section class="smtp-analytics-panel">
					<h2>AI Assistant Performance</h2>
					<div class="smtp-mini-cards">
						<?php $this->mini_card( 'Conversations', $report['assistant']['conversations'] ); ?>
						<?php $this->mini_card( 'Converted to leads', $report['assistant']['converted'] ); ?>
						<?php $this->mini_card( 'Conversion rate', $this->format_percent( $report['assistant']['conversion_rate'] ) ); ?>
						<?php $this->mini_card( 'Messages', $report['assistant']['messages'] ); ?>
						<?php $this->mini_card( 'Average messages', $report['assistant']['average_messages'] ); ?>
					</div>
				</section>
				<section class="smtp-analytics-panel">
					<h2>Product Demand</h2>
					<?php $this->product_table( $report['product_demand'] ); ?>
				</section>
			</div>

			<section class="smtp-analytics-panel">
				<h2>Recent Opportunities</h2>
				<?php $this->recent_table( $report['recent'] ); ?>
			</section>
		</div>
		<?php
	}

	private function filters( SMTP_Analytics_Date_Range $range ): void {
		?>
		<form class="smtp-analytics-filter" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
			<input type="hidden" name="page" value="smtp-analytics">
			<label>Period
				<select name="range" id="smtp-analytics-range">
					<?php foreach ( array( '7' => 'Last 7 days', '30' => 'Last 30 days', '90' => 'Last 90 days', '365' => 'Last 12 months', 'all' => 'All time', 'custom' => 'Custom dates' ) as $key => $label ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $range->key(), $key ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
			<label>From <input type="date" name="from" value="<?php echo esc_attr( $range->from() ?? '' ); ?>"></label>
			<label>To <input type="date" name="to" value="<?php echo esc_attr( $range->to() ?? '' ); ?>"></label>
			<button class="button button-primary" type="submit">Apply</button>
		</form>
		<?php
	}

	private function pipeline_panel( string $title, array $pipeline, string $post_type, SMTP_Analytics_Date_Range $range ): void {
		$max = max( array_merge( array( 1 ), array_values( $pipeline['counts'] ) ) );
		?>
		<section class="smtp-analytics-panel">
			<div class="smtp-panel-title"><h2><?php echo esc_html( $title ); ?></h2><span><?php echo esc_html( (string) $pipeline['total'] ); ?> total</span></div>
			<div class="smtp-bars">
				<?php foreach ( $this->crm->statuses() as $status => $label ) : ?>
					<?php $count = (int) $pipeline['counts'][ $status ]; ?>
					<a class="smtp-bar-row" href="<?php echo esc_url( $this->pipeline_url( $post_type, $status, $range ) ); ?>">
						<span class="smtp-bar-label"><?php echo esc_html( $label ); ?></span>
						<span class="smtp-bar-track"><span style="width:<?php echo esc_attr( (string) round( ( $count / $max ) * 100, 1 ) ); ?>%"></span></span>
						<strong><?php echo esc_html( (string) $count ); ?></strong>
					</a>
				<?php endforeach; ?>
			</div>
			<p class="smtp-panel-foot">Open: <strong><?php echo esc_html( (string) $pipeline['open'] ); ?></strong> &nbsp; Closed win rate: <strong><?php echo esc_html( $this->format_percent( $pipeline['closed_win_rate'] ) ); ?></strong></p>
		</section>
		<?php
	}

	private function sources_table( array $rows ): void {
		if ( ! $rows ) {
			echo '<p>No opportunities were recorded in this period.</p>';
			return;
		}
		?>
		<table class="widefat striped smtp-analytics-table"><thead><tr><th>Source</th><th>Total</th><th>Won</th><th>Lost</th><th>Closed win rate</th></tr></thead><tbody>
		<?php foreach ( $rows as $row ) : ?>
			<tr><td><?php echo esc_html( $row['label'] ); ?></td><td><?php echo esc_html( (string) $row['total'] ); ?></td><td><?php echo esc_html( (string) $row['won'] ); ?></td><td><?php echo esc_html( (string) $row['lost'] ); ?></td><td><?php echo esc_html( $this->format_percent( $row['closed_win_rate'] ) ); ?></td></tr>
		<?php endforeach; ?>
		</tbody></table>
		<?php
	}

	private function product_table( array $rows ): void {
		if ( ! $rows ) {
			echo '<p>No product quote requests were recorded in this period.</p>';
			return;
		}
		?>
		<table class="widefat striped smtp-analytics-table"><thead><tr><th>Product</th><th>Requests</th><th>Requested quantity</th></tr></thead><tbody>
		<?php foreach ( $rows as $row ) : ?>
			<tr><td><?php echo esc_html( $row['product'] ); ?></td><td><?php echo esc_html( (string) $row['requests'] ); ?></td><td><?php echo esc_html( (string) $row['quantity'] ); ?></td></tr>
		<?php endforeach; ?>
		</tbody></table>
		<?php
	}

	private function recent_table( array $rows ): void {
		if ( ! $rows ) {
			echo '<p>No recent opportunities were recorded in this period.</p>';
			return;
		}
		?>
		<table class="widefat striped smtp-analytics-table"><thead><tr><th>Date</th><th>Record</th><th>Type</th><th>Status</th><th>Source</th><th>Owner</th></tr></thead><tbody>
		<?php foreach ( $rows as $row ) : ?>
			<tr>
				<td><?php echo esc_html( $row['date'] ); ?></td>
				<td><?php if ( $row['url'] ) : ?><a href="<?php echo esc_url( $row['url'] ); ?>"><?php echo esc_html( $row['title'] ); ?></a><?php else : ?><?php echo esc_html( $row['title'] ); ?><?php endif; ?></td>
				<td><?php echo esc_html( $row['type'] ); ?></td>
				<td><?php echo esc_html( ucfirst( $row['status'] ) ); ?></td>
				<td><?php echo esc_html( $row['source'] ); ?></td>
				<td><?php echo esc_html( $row['owner'] ); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody></table>
		<?php
	}

	private function card( string $label, $value ): void {
		echo '<div class="smtp-analytics-card"><span>' . esc_html( $label ) . '</span><strong>' . esc_html( (string) $value ) . '</strong></div>';
	}

	private function mini_card( string $label, $value ): void {
		echo '<div><span>' . esc_html( $label ) . '</span><strong>' . esc_html( (string) $value ) . '</strong></div>';
	}

	private function pipeline_url( string $post_type, string $status, SMTP_Analytics_Date_Range $range ): string {
		$args = array(
			'post_type'              => $post_type,
			'smtp_crm_status_filter' => $status,
		);

		if ( ! $range->is_all_time() ) {
			$args['smtp_crm_from'] = (string) $range->from();
			$args['smtp_crm_to']   = (string) $range->to();
		}

		return add_query_arg( $args, admin_url( 'edit.php' ) );
	}

	private function export_url( SMTP_Analytics_Date_Range $range ): string {
		$args = array_merge( array( 'action' => 'smtp_export_analytics' ), $range->query_args() );
		return wp_nonce_url( add_query_arg( $args, admin_url( 'admin-post.php' ) ), 'smtp_export_analytics' );
	}

	private function pdf_url( SMTP_Analytics_Date_Range $range ): string {
		$args = array_merge( array( 'action' => 'smtp_export_analytics_pdf' ), $range->query_args() );
		return wp_nonce_url( add_query_arg( $args, admin_url( 'admin-post.php' ) ), 'smtp_export_analytics_pdf' );
	}

	private function format_money( float $amount ): string {
		return 'EGP ' . number_format_i18n( $amount, 0 );
	}

	private function format_percent( float $value ): string {
		return number_format_i18n( $value, 1 ) . '%';
	}
}
