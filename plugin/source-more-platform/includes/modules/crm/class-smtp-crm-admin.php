<?php
/**
 * CRM administration, exports, assignment, status, and activity history.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_CRM_Admin {
	private SMTP_CRM_Repository $repository;
	private bool $registered = false;

	public function __construct( SMTP_CRM_Repository $repository ) {
		$this->repository = $repository;
	}

	public function register_hooks(): void {
		if ( $this->registered ) {
			return;
		}

		add_filter( 'manage_' . SMTP_CRM_Content_Types::POST_TYPE . '_posts_columns', array( $this, 'columns' ) );
		add_action( 'manage_' . SMTP_CRM_Content_Types::POST_TYPE . '_posts_custom_column', array( $this, 'column_content' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'row_actions' ), 10, 2 );
		add_action( 'add_meta_boxes', array( $this, 'meta_boxes' ) );
		add_action( 'save_post_' . SMTP_CRM_Content_Types::POST_TYPE, array( $this, 'save_lead' ) );
		add_action( 'save_post_' . SMTP_CRM_Repository::QUOTE_POST_TYPE, array( $this, 'save_quote_request' ) );
		add_action( 'admin_post_smt_export_leads', array( $this, 'export_csv' ) );
		add_action( 'admin_menu', array( $this, 'submenu' ) );
		add_action( 'restrict_manage_posts', array( $this, 'filters' ) );
		add_action( 'pre_get_posts', array( $this, 'apply_filters' ) );
		add_action( 'smtp_product_quote_request_created', array( $this, 'quote_request_created' ), 10, 2 );

		$this->registered = true;
	}

	public function columns( array $columns ): array {
		return array(
			'cb'       => $columns['cb'] ?? '<input type="checkbox" />',
			'title'    => 'Lead',
			'company'  => 'Company',
			'contact'  => 'Contact',
			'savings'  => 'Annual Savings',
			'source'   => 'Source',
			'owner'    => 'Assigned To',
			'status'   => 'Status',
			'activity' => 'Last Activity',
			'date'     => $columns['date'] ?? 'Date',
		);
	}

	public function column_content( string $column, int $post_id ): void {
		if ( 'company' === $column ) {
			echo esc_html( get_post_meta( $post_id, 'company', true ) );
		}

		if ( 'contact' === $column ) {
			echo esc_html( get_post_meta( $post_id, 'contact_name', true ) ) . '<br><small>' . esc_html( get_post_meta( $post_id, 'email', true ) ) . '</small>';
		}

		if ( 'savings' === $column ) {
			echo 'EGP ' . esc_html( number_format( (float) get_post_meta( $post_id, 'annual_savings', true ), 0 ) );
		}

		if ( 'source' === $column ) {
			echo esc_html( ucwords( str_replace( '-', ' ', $this->repository->source( $post_id ) ?: 'manual' ) ) );
		}

		if ( 'owner' === $column ) {
			echo esc_html( $this->repository->owner_name( $post_id ) );
		}

		if ( 'status' === $column ) {
			echo '<strong>' . esc_html( $this->repository->statuses()[ $this->repository->status( $post_id ) ] ) . '</strong>';
		}

		if ( 'activity' === $column ) {
			echo esc_html( $this->repository->last_activity( $post_id ) ?: '—' );
		}
	}

	public function row_actions( array $actions, $post ): array {
		if ( SMTP_CRM_Content_Types::POST_TYPE === $post->post_type ) {
			$token = (string) get_post_meta( $post->ID, 'report_token', true );
			if ( $token ) {
				$actions['report'] = '<a target="_blank" href="' . esc_url( admin_url( 'admin-post.php?action=smt_download_report&lead=' . $post->ID . '&token=' . $token ) ) . '">PDF Report</a>';
			}
		}

		return $actions;
	}

	public function meta_boxes(): void {
		add_meta_box(
			'smtp_lead_details',
			'Fleet Assessment Details',
			array( $this, 'lead_meta_box' ),
			SMTP_CRM_Content_Types::POST_TYPE,
			'normal',
			'high'
		);

		add_meta_box(
			'smtp_crm_management',
			'CRM Management',
			array( $this, 'management_box' ),
			SMTP_CRM_Content_Types::POST_TYPE,
			'side',
			'high'
		);

		if ( post_type_exists( SMTP_CRM_Repository::QUOTE_POST_TYPE ) ) {
			add_meta_box(
				'smtp_quote_crm_management',
				'CRM Management',
				array( $this, 'management_box' ),
				SMTP_CRM_Repository::QUOTE_POST_TYPE,
				'side',
				'high'
			);

			add_meta_box(
				'smtp_quote_crm_activity',
				'CRM Activity History',
				array( $this, 'activity_box' ),
				SMTP_CRM_Repository::QUOTE_POST_TYPE,
				'normal',
				'default'
			);
		}
	}

	public function lead_meta_box( $post ): void {
		$fields = array(
			'company'        => 'Company',
			'contact_name'   => 'Contact person',
			'email'          => 'Email',
			'phone'          => 'Phone',
			'industry'       => 'Industry',
			'locations'      => 'Locations',
			'devices'        => 'Devices',
			'mono_pages'     => 'Monthly mono pages',
			'color_pages'    => 'Monthly color pages',
			'mono_cpp'       => 'Mono CPP',
			'color_cpp'      => 'Color CPP',
			'fixed_cost'     => 'Monthly fixed cost',
			'saving_rate'    => 'Optimization rate',
			'current_cost'   => 'Current annual cost',
			'annual_savings' => 'Annual savings',
			'optimized_cost' => 'Optimized annual cost',
			'three_year'     => 'Three-year savings',
			'lead_source'    => 'Lead source',
			'source_url'     => 'Source URL',
		);

		echo '<table class="widefat striped"><tbody>';
		foreach ( $fields as $key => $label ) {
			echo '<tr><th style="width:230px">' . esc_html( $label ) . '</th><td>' . esc_html( get_post_meta( $post->ID, $key, true ) ) . '</td></tr>';
		}
		echo '</tbody></table>';

		$this->activity_timeline( $post->ID );
	}

	public function management_box( $post ): void {
		wp_nonce_field( 'smtp_save_crm_record', 'smtp_crm_nonce' );
		$current_status = $this->repository->status( $post->ID );
		$assigned_to    = $this->repository->assigned_to( $post->ID );

		echo '<p><label for="smtp_crm_status"><strong>Status</strong></label></p><select class="widefat" id="smtp_crm_status" name="smtp_crm_status">';
		foreach ( $this->repository->statuses() as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . selected( $current_status, $status, false ) . '>' . esc_html( $label ) . '</option>';
		}
		echo '</select>';

		echo '<p><label for="smtp_crm_assigned_to"><strong>Assigned To</strong></label></p>';
		wp_dropdown_users(
			array(
				'name'              => 'smtp_crm_assigned_to',
				'id'                => 'smtp_crm_assigned_to',
				'class'             => 'widefat',
				'selected'          => $assigned_to,
				'show_option_none'  => 'Unassigned',
				'option_none_value' => 0,
				'role__in'          => array( 'administrator', 'editor', 'author' ),
			)
		);

		echo '<p><strong>Source</strong><br>' . esc_html( ucwords( str_replace( '-', ' ', $this->repository->source( $post->ID ) ?: 'manual' ) ) ) . '</p>';
		echo '<p><strong>Last activity</strong><br>' . esc_html( $this->repository->last_activity( $post->ID ) ?: '—' ) . '</p>';
	}

	public function activity_box( $post ): void {
		$this->activity_timeline( $post->ID );
	}

	public function save_lead( int $post_id ): void {
		$this->save_management( $post_id );
	}

	public function save_quote_request( int $post_id ): void {
		$this->save_management( $post_id );
	}

	public function quote_request_created( int $post_id, array $values ): void {
		$this->repository->initialize_quote_request( $post_id, $values );
	}

	public function submenu(): void {
		add_submenu_page( 'smtp-platform', 'Export Leads', 'Export CSV', 'manage_options', 'smtp-export', array( $this, 'export_page' ) );
	}

	public function export_page(): void {
		echo '<div class="wrap"><h1>Export Source More Leads</h1><p>Download all calculator leads and assessment results as a CSV file.</p><a class="button button-primary" href="' . esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=smt_export_leads' ), 'smtp_export' ) ) . '">Download CSV</a></div>';
	}

	public function export_csv(): void {
		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'smtp_export' ) ) {
			wp_die( 'Not allowed' );
		}

		$leads = get_posts(
			array(
				'post_type'      => SMTP_CRM_Content_Types::POST_TYPE,
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);

		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=source-more-leads-' . gmdate( 'Y-m-d' ) . '.csv' );
		$output = fopen( 'php://output', 'w' );
		fputcsv( $output, array( 'Date', 'Company', 'Contact', 'Email', 'Phone', 'Industry', 'Locations', 'Devices', 'Annual Savings', 'Current Cost', 'Status', 'Source', 'Assigned To', 'Last Activity' ) );

		foreach ( $leads as $lead ) {
			fputcsv(
				$output,
				array(
					$lead->post_date,
					get_post_meta( $lead->ID, 'company', true ),
					get_post_meta( $lead->ID, 'contact_name', true ),
					get_post_meta( $lead->ID, 'email', true ),
					get_post_meta( $lead->ID, 'phone', true ),
					get_post_meta( $lead->ID, 'industry', true ),
					get_post_meta( $lead->ID, 'locations', true ),
					get_post_meta( $lead->ID, 'devices', true ),
					get_post_meta( $lead->ID, 'annual_savings', true ),
					get_post_meta( $lead->ID, 'current_cost', true ),
					$this->repository->status( $lead->ID ),
					$this->repository->source( $lead->ID ),
					$this->repository->owner_name( $lead->ID ),
					$this->repository->last_activity( $lead->ID ),
				)
			);
		}

		fclose( $output );
		exit;
	}

	public function download_report(): void {
		SMTP_Fleet_Module::instance()->report()->download();
	}

	public function filters(): void {
		$screen = get_current_screen();

		if ( ! $screen || ! in_array( $screen->post_type, array( SMTP_CRM_Content_Types::POST_TYPE, SMTP_CRM_Repository::QUOTE_POST_TYPE ), true ) ) {
			return;
		}

		$current_status = sanitize_key( $_GET['smtp_crm_status_filter'] ?? '' );
		echo '<select name="smtp_crm_status_filter"><option value="">All CRM statuses</option>';
		foreach ( $this->repository->statuses() as $status => $label ) {
			echo '<option value="' . esc_attr( $status ) . '" ' . selected( $current_status, $status, false ) . '>' . esc_html( $label ) . '</option>';
		}
		echo '</select>';

		if ( SMTP_CRM_Content_Types::POST_TYPE === $screen->post_type ) {
			$current_source = sanitize_key( $_GET['smtp_crm_source_filter'] ?? '' );
			$sources        = array( 'fleet-calculator' => 'Fleet Calculator', 'manual' => 'Manual' );
			echo '<select name="smtp_crm_source_filter"><option value="">All lead sources</option>';
			foreach ( $sources as $source => $label ) {
				echo '<option value="' . esc_attr( $source ) . '" ' . selected( $current_source, $source, false ) . '>' . esc_html( $label ) . '</option>';
			}
			echo '</select>';
		}
	}

	public function apply_filters( $query ): void {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$post_type = $query->get( 'post_type' );
		if ( ! in_array( $post_type, array( SMTP_CRM_Content_Types::POST_TYPE, SMTP_CRM_Repository::QUOTE_POST_TYPE ), true ) ) {
			return;
		}

		$meta_query = (array) $query->get( 'meta_query' );
		$status     = sanitize_key( $_GET['smtp_crm_status_filter'] ?? '' );
		$source     = sanitize_key( $_GET['smtp_crm_source_filter'] ?? '' );

		if ( $status && $this->repository->valid_status( $status ) ) {
			$status_key = SMTP_CRM_Repository::QUOTE_POST_TYPE === $post_type ? '_smtp_status' : 'lead_status';
			$meta_query[] = $this->repository->status_meta_query( $status_key, $status );
		}

		if ( $source && SMTP_CRM_Content_Types::POST_TYPE === $post_type ) {
			if ( 'manual' === $source ) {
				$meta_query[] = array(
					'relation' => 'OR',
					array( 'key' => 'lead_source', 'value' => 'manual' ),
					array( 'key' => 'lead_source', 'compare' => 'NOT EXISTS' ),
				);
			} else {
				$meta_query[] = array( 'key' => 'lead_source', 'value' => $source );
			}
		}

		if ( $meta_query ) {
			$query->set( 'meta_query', $meta_query );
		}
	}

	private function save_management( int $post_id ): void {
		if ( ! isset( $_POST['smtp_crm_nonce'] ) ) {
			return;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['smtp_crm_nonce'] ) );
		if ( ! wp_verify_nonce( $nonce, 'smtp_save_crm_record' ) || ! current_user_can( 'edit_post', $post_id ) || wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		$actor_id = get_current_user_id();

		if ( isset( $_POST['smtp_crm_status'] ) ) {
			$this->repository->update_status( $post_id, sanitize_key( wp_unslash( $_POST['smtp_crm_status'] ) ), $actor_id );
		}

		if ( isset( $_POST['smtp_crm_assigned_to'] ) ) {
			$this->repository->assign( $post_id, absint( $_POST['smtp_crm_assigned_to'] ), $actor_id );
		}
	}

	private function activity_timeline( int $post_id ): void {
		$history = $this->repository->activity_history( $post_id );
		echo '<h3>Activity History</h3>';

		if ( ! $history ) {
			echo '<p>No activity has been recorded yet.</p>';
			return;
		}

		echo '<table class="widefat striped"><thead><tr><th style="width:170px">Date</th><th>Activity</th><th style="width:150px">User</th></tr></thead><tbody>';
		foreach ( $history as $entry ) {
			$user_id = absint( $entry['user_id'] ?? 0 );
			$user    = $user_id ? get_userdata( $user_id ) : false;
			echo '<tr><td>' . esc_html( $entry['timestamp'] ?? '' ) . '</td><td>' . esc_html( $entry['message'] ?? '' ) . '</td><td>' . esc_html( $user ? $user->display_name : 'System' ) . '</td></tr>';
		}
		echo '</tbody></table>';
	}
}
