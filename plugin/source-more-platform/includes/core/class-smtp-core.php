<?php
/**
 * SMEP core framework runtime.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Core {
	public static function boot(): void {
		SMTP_Container::set( 'audit_log', SMTP_Audit_Log::class );
		SMTP_Container::set( 'module_registry', SMTP_Module_Registry::class );
		SMTP_Container::set( 'settings', SMTP_Settings::class );

		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_routes' ) );
		add_action( 'admin_menu', array( __CLASS__, 'register_admin_page' ), 90 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_design_system' ) );
		add_action( 'smtp_platform_module_failed', array( __CLASS__, 'audit_module_failure' ), 10, 2 );
	}

	public static function register_rest_routes(): void {
		register_rest_route(
			'sourcemore/v1',
			'/platform/status',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => static function (): bool {
					return current_user_can( SMTP_Capabilities::ACCESS_PLATFORM ) || current_user_can( 'manage_options' );
				},
				'callback'            => static function (): WP_REST_Response {
					return rest_ensure_response(
						array(
							'platform' => 'SMEP',
							'version'  => SMTP_PLATFORM_VERSION,
							'database' => SMTP_PLATFORM_DB_VERSION,
							'locale'   => determine_locale(),
							'modules'  => SMTP_Module_Registry::statuses(),
							'services' => SMTP_Container::ids(),
						)
					);
				},
			)
		);
	}

	public static function register_admin_page(): void {
		add_submenu_page(
			'smtp-platform',
			'Platform Architecture',
			'Platform Architecture',
			SMTP_Capabilities::MANAGE_PLATFORM,
			'smtp-platform-architecture',
			array( __CLASS__, 'render_admin_page' )
		);
	}

	public static function enqueue_design_system( string $hook ): void {
		if ( false === strpos( $hook, 'smtp-' ) ) {
			return;
		}

		wp_enqueue_style(
			'smtp-enterprise-design-system',
			SMTP_PLATFORM_URL . 'assets/css/enterprise-design-system.css',
			array(),
			SMTP_PLATFORM_VERSION
		);
	}

	public static function render_admin_page(): void {
		if ( ! current_user_can( SMTP_Capabilities::MANAGE_PLATFORM ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'source-more-platform' ) );
		}

		$modules = SMTP_Module_Registry::statuses();
		$logs    = SMTP_Audit_Log::recent( 20 );
		?>
		<div class="wrap smtp-enterprise-wrap">
			<div class="smtp-page-header">
				<div><span class="smtp-eyebrow">SMEP 4.0 FOUNDATION</span><h1>Platform Architecture</h1><p>Core services, module health, permissions, audit readiness, and API status.</p></div>
				<span class="smtp-status-badge smtp-status-success">Operational</span>
			</div>

			<div class="smtp-kpi-grid">
				<div class="smtp-kpi-card"><span>Platform Version</span><strong><?php echo esc_html( SMTP_PLATFORM_VERSION ); ?></strong></div>
				<div class="smtp-kpi-card"><span>Registered Modules</span><strong><?php echo esc_html( (string) count( $modules ) ); ?></strong></div>
				<div class="smtp-kpi-card"><span>Core Services</span><strong><?php echo esc_html( (string) count( SMTP_Container::ids() ) ); ?></strong></div>
				<div class="smtp-kpi-card"><span>REST Namespace</span><strong>sourcemore/v1</strong></div>
			</div>

			<div class="smtp-panel">
				<div class="smtp-panel-heading"><h2>Module Registry</h2><p>Controlled boot order and dependency health.</p></div>
				<table class="widefat striped smtp-data-table"><thead><tr><th>Module</th><th>Version</th><th>Status</th><th>Dependencies</th></tr></thead><tbody>
				<?php foreach ( $modules as $id => $module ) : ?>
					<tr><td><strong><?php echo esc_html( $module['label'] ); ?></strong><br><code><?php echo esc_html( $id ); ?></code></td><td><?php echo esc_html( $module['version'] ); ?></td><td><span class="smtp-status-badge <?php echo 'booted' === $module['status'] ? 'smtp-status-success' : 'smtp-status-neutral'; ?>"><?php echo esc_html( ucfirst( $module['status'] ) ); ?></span></td><td><?php echo esc_html( $module['dependencies'] ? implode( ', ', $module['dependencies'] ) : '—' ); ?></td></tr>
				<?php endforeach; ?>
				</tbody></table>
			</div>

			<div class="smtp-panel">
				<div class="smtp-panel-heading"><h2>Recent Audit Events</h2><p>Foundation log for security-sensitive and operational events.</p></div>
				<?php if ( empty( $logs ) ) : ?><p>No audit events have been recorded yet.</p><?php else : ?>
				<table class="widefat striped smtp-data-table"><thead><tr><th>Time</th><th>Action</th><th>User</th><th>Object</th></tr></thead><tbody>
				<?php foreach ( $logs as $log ) : ?><tr><td><?php echo esc_html( get_date_from_gmt( $log->created_at, 'Y-m-d H:i' ) ); ?></td><td><code><?php echo esc_html( $log->action ); ?></code></td><td><?php echo esc_html( (string) $log->user_id ); ?></td><td><?php echo esc_html( trim( $log->object_type . ' #' . $log->object_id ) ); ?></td></tr><?php endforeach; ?>
				</tbody></table><?php endif; ?>
			</div>
		</div>
		<?php
	}

	public static function audit_module_failure( string $module_id, string $message ): void {
		SMTP_Audit_Log::record( 'module_failed', 'module', 0, array( 'module' => $module_id, 'message' => $message ) );
	}
}
