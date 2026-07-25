<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SMTP_Dashboard {
	public static function init(): void {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ), 5 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'assets' ) );
		add_action( 'admin_post_smtp_test_email', array( __CLASS__, 'test_email' ) );
	}

	public static function menu(): void {
		add_menu_page( 'Source More Platform', 'Source More CRM', 'edit_posts', 'smtp-platform', array( __CLASS__, 'page' ), 'dashicons-chart-line', 26 );
		add_submenu_page( 'smtp-platform', 'Dashboard', 'Dashboard', 'edit_posts', 'smtp-platform', array( __CLASS__, 'page' ) );
		add_submenu_page( 'smtp-platform', 'All Leads', 'All Leads', 'edit_posts', 'edit.php?post_type=' . SMTP_Leads::POST_TYPE );
		add_submenu_page( 'smtp-platform', 'Add Fleet Lead', 'Add Fleet Lead', 'edit_posts', 'post-new.php?post_type=' . SMTP_Leads::POST_TYPE );
	}

	public static function assets( string $hook ): void {
		if ( false === strpos( $hook, 'smtp-platform' ) && false === strpos( $hook, SMTP_Leads::POST_TYPE ) && false === strpos( $hook, SMTP_CRM_Repository::QUOTE_POST_TYPE ) ) {
			return;
		}

		wp_add_inline_style( 'wp-admin', '.smtp-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin:14px 0 24px}.smtp-card{display:block;background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:20px;color:#1d2327;text-decoration:none;transition:border-color .15s ease,box-shadow .15s ease,transform .15s ease}.smtp-card:hover,.smtp-card:focus{color:#1d2327;border-color:#2271b1;box-shadow:0 4px 14px rgba(0,0,0,.08);transform:translateY(-1px)}.smtp-card strong{display:block;font-size:30px;margin-top:8px}.smtp-health{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px}.smtp-health div{background:#fff;border-left:4px solid #2271b1;padding:14px}.smtp-actions{display:flex;gap:10px;flex-wrap:wrap;margin:18px 0}.smtp-pipeline-heading{margin-top:26px;margin-bottom:0}' );
	}

	private static function repository(): SMTP_CRM_Repository {
		return SMTP_CRM_Module::instance()->repository();
	}

	private static function count( string $status = '' ): int {
		return self::repository()->count_leads( $status );
	}

	private static function pipeline_url( string $post_type, string $status = '' ): string {
		$args = array( 'post_type' => $post_type );

		if ( '' !== $status ) {
			$args['smtp_crm_status_filter'] = $status;
		}

		return add_query_arg( $args, admin_url( 'edit.php' ) );
	}

	private static function metric_card( string $label, int $count, string $url ): void {
		echo '<a class="smtp-card" href="' . esc_url( $url ) . '">' . esc_html( $label ) . '<strong>' . esc_html( (string) $count ) . '</strong></a>';
	}

	public static function page(): void {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$repository  = self::repository();
		$statuses    = $repository->statuses();
		$lead_counts = array();
		$quote_counts= array();

		foreach ( $statuses as $status => $label ) {
			$lead_counts[ $status ]  = $repository->count_leads( $status );
			$quote_counts[ $status ] = $repository->count_quote_requests( $status );
		}

		$total_leads  = $repository->count_leads();
		$total_quotes = $repository->count_quote_requests();
		$options  = SMTP_Settings::get();
		?>
		<div class="wrap">
			<h1>Source More CRM Dashboard</h1>
			<p>Lead capture, fleet assessments, quote requests, reports, assistant integration, and platform health.</p>
			<h2 class="smtp-pipeline-heading">Lead Pipeline</h2>
			<div class="smtp-cards">
				<?php self::metric_card( 'Total Leads', $total_leads, self::pipeline_url( SMTP_CRM_Content_Types::POST_TYPE ) ); ?>
				<?php foreach ( $statuses as $status => $label ) : ?>
					<?php self::metric_card( $label, $lead_counts[ $status ], self::pipeline_url( SMTP_CRM_Content_Types::POST_TYPE, $status ) ); ?>
				<?php endforeach; ?>
			</div>

			<h2 class="smtp-pipeline-heading">Quote Request Pipeline</h2>
			<div class="smtp-cards">
				<?php self::metric_card( 'Total Requests', $total_quotes, self::pipeline_url( SMTP_CRM_Repository::QUOTE_POST_TYPE ) ); ?>
				<?php foreach ( $statuses as $status => $label ) : ?>
					<?php self::metric_card( $label, $quote_counts[ $status ], self::pipeline_url( SMTP_CRM_Repository::QUOTE_POST_TYPE, $status ) ); ?>
				<?php endforeach; ?>
			</div>
			<div class="smtp-actions">
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=' . SMTP_Leads::POST_TYPE ) ); ?>">Add Fleet Lead</a>
				<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . SMTP_Leads::POST_TYPE ) ); ?>">View All Leads</a>
				<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . SMTP_CRM_Repository::QUOTE_POST_TYPE ) ); ?>">View Quote Requests</a>
				<?php if ( SMTP_Modules::enabled( 'assistant' ) && current_user_can( 'manage_options' ) ) : ?>
					<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . SMTP_Assistant_Content_Types::CONVERSATION_POST_TYPE ) ); ?>">View AI Conversations</a>
					<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . SMTP_Assistant_Content_Types::KNOWLEDGE_POST_TYPE ) ); ?>">Manage AI Knowledge</a>
				<?php endif; ?>
				<?php if ( SMTP_Modules::enabled( 'analytics' ) ) : ?>
					<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=smtp-analytics' ) ); ?>">Reports &amp; Analytics</a>
				<?php endif; ?>
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=smtp-settings' ) ); ?>">Platform Settings</a>
			</div>
			<h2>System status</h2>
			<div class="smtp-health">
				<div><strong>WordPress</strong><br><?php echo esc_html( get_bloginfo( 'version' ) ); ?></div>
				<div><strong>PHP</strong><br><?php echo esc_html( PHP_VERSION ); ?></div>
				<div><strong>AI Assistant</strong><br><?php echo $options['assistant_enabled'] ? ( $options['assistant_endpoint'] ? 'Enabled — external endpoint with built-in fallback' : 'Enabled — built-in knowledge' ) : 'Disabled'; ?></div>
				<div><strong>Lead Email</strong><br><?php echo esc_html( $options['notification_email'] ); ?></div>
			</div>
		</div>
		<?php
	}

	public static function test_email(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Not allowed', 403 );
		}

		check_admin_referer( 'smtp_test_email' );
		$to   = SMTP_Settings::get()['notification_email'];
		$sent = wp_mail( $to, 'Source More Platform — Test Email', 'This confirms that WordPress email delivery is working for Source More Platform.' );
		SMTP_Logger::info( $sent ? 'Test email sent' : 'Test email failed', array( 'recipient' => $to ) );
		wp_safe_redirect( add_query_arg( array( 'page' => 'smtp-settings', 'smtp_mail_test' => $sent ? 'success' : 'failed' ), admin_url( 'admin.php' ) ) );
		exit;
	}
}
