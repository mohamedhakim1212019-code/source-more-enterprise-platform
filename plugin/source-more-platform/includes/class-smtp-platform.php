<?php
if (!defined('ABSPATH')) { exit; }
final class SMTP_Platform {
    public static function activate(): void {
        SMTP_Leads::register_post_type();
        SMTP_Products::register();
        update_option('smtp_platform_db_version', SMTP_PLATFORM_DB_VERSION, false);
        if (get_option('smtp_platform_options', null) === null) {
            add_option('smtp_platform_options', SMTP_Settings::defaults(), '', false);
        }
        flush_rewrite_rules();
    }
    public static function deactivate(): void { flush_rewrite_rules(); }
    public static function init(): void {
        load_plugin_textdomain('source-more-platform', false, dirname(plugin_basename(SMTP_PLATFORM_FILE)) . '/languages');
        SMTP_Dashboard::init();
        SMTP_Settings::init();
        if (SMTP_Modules::enabled('crm') || SMTP_Modules::enabled('fleet')) SMTP_Leads::init();
        if (SMTP_Modules::enabled('products')) SMTP_Products::init();
        SMTP_REST::init();
        if (SMTP_Modules::enabled('assistant')) SMTP_Assistant::init();
        if (SMTP_Modules::enabled('diagnostics')) SMTP_Diagnostics::init();
        self::maybe_upgrade();
    }
    private static function maybe_upgrade(): void {
        $installed = (string) get_option('smtp_platform_db_version', '1.0.0');
        if (version_compare($installed, SMTP_PLATFORM_DB_VERSION, '<')) {
            update_option('smtp_platform_db_version', SMTP_PLATFORM_DB_VERSION, false);
            SMTP_Logger::info('Platform upgraded', ['from' => $installed, 'to' => SMTP_PLATFORM_DB_VERSION]);
        }
    }
}
