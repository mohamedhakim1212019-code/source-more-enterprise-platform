<?php
if (!defined('ABSPATH')) exit;
function smt_platform_active(): bool { return defined('SMTP_PLATFORM_VERSION') && class_exists('SMTP_Leads'); }
function smt_platform_version(): string { return defined('SMTP_PLATFORM_VERSION') ? SMTP_PLATFORM_VERSION : ''; }
add_action('admin_notices', function(){
    if (!current_user_can('activate_plugins') || smt_platform_active()) return;
    echo '<div class="notice notice-warning"><p><strong>Source More Theme:</strong> Activate Source More Platform v2.1+ to enable fleet reports, lead capture, CRM, and the AI assistant.</p></div>';
});
add_action('wp_body_open', function(){
    if (!smt_platform_active() || !current_user_can('manage_options')) return;
    echo '<span class="screen-reader-text">Source More Platform '.esc_html(smt_platform_version()).' connected.</span>';
});
