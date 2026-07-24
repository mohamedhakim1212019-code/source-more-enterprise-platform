<?php
/**
 * Plugin Name: Source More Platform
 * Description: Business platform for fleet assessments, lead management, branded reports, diagnostics, and the Source More AI assistant.
 * Version: 3.1.0
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * Author: Source More Technology
 * Text Domain: source-more-platform
 */
if (!defined('ABSPATH')) { exit; }

define('SMTP_PLATFORM_VERSION', '3.1.0');
define('SMTP_PLATFORM_DB_VERSION', '3.1.0');
define('SMTP_PLATFORM_FILE', __FILE__);
define('SMTP_PLATFORM_DIR', plugin_dir_path(__FILE__));
define('SMTP_PLATFORM_URL', plugin_dir_url(__FILE__));

$files = [
    'class-smtp-modules.php', 'class-smtp-logger.php', 'class-smtp-rate-limiter.php', 'class-smtp-pdf.php', 'class-smtp-dashboard.php', 'class-smtp-products.php',
    'class-smtp-leads.php', 'class-smtp-settings.php', 'class-smtp-rest.php',
    'class-smtp-assistant.php', 'class-smtp-diagnostics.php', 'class-smtp-platform.php'
];
foreach ($files as $file) { require_once SMTP_PLATFORM_DIR . 'includes/' . $file; }

register_activation_hook(__FILE__, ['SMTP_Platform', 'activate']);
register_deactivation_hook(__FILE__, ['SMTP_Platform', 'deactivate']);
add_action('plugins_loaded', ['SMTP_Platform', 'init']);

/** v3 upgrade routine runs even when the plugin ZIP replaces an active version. */
function smtp_platform_v31_upgrade(): void {
    if (get_option('smtp_platform_db_version') === SMTP_PLATFORM_VERSION) return;
    if (class_exists('SMTP_Products')) SMTP_Products::register();
    $products = get_page_by_path('products');
    if (!$products) wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>'Products','post_name'=>'products']);
    flush_rewrite_rules(false);
    update_option('smtp_platform_db_version',SMTP_PLATFORM_VERSION,false);
}
add_action('admin_init','smtp_platform_v31_upgrade',20);
