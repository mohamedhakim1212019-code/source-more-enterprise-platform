<?php
if (!defined('ABSPATH')) { exit; }
class SMTP_Settings {
    public static function defaults(): array { return ['notification_email'=>get_option('admin_email'),'assistant_endpoint'=>'','assistant_enabled'=>0,'logging_enabled'=>1,'report_expiry_days'=>30,'privacy_url'=>get_privacy_policy_url()]; }
    public static function get(): array { return wp_parse_args((array)get_option('smtp_platform_options', []), self::defaults()); }
    public static function init(): void { add_action('admin_menu',[__CLASS__,'menu']); add_action('admin_init',[__CLASS__,'register']); }
    public static function menu(): void { add_submenu_page('smtp-platform','Platform Settings','Settings','manage_options','smtp-settings',[__CLASS__,'page']); }
    public static function register(): void { register_setting('smtp_platform','smtp_platform_options',['sanitize_callback'=>[__CLASS__,'sanitize']]); SMTP_Modules::register(); }
    public static function sanitize($v): array {
        $v = is_array($v) ? $v : [];
        return [
            'notification_email'=>sanitize_email($v['notification_email']??get_option('admin_email')),
            'assistant_endpoint'=>esc_url_raw($v['assistant_endpoint']??''),
            'assistant_enabled'=>empty($v['assistant_enabled'])?0:1,
            'logging_enabled'=>empty($v['logging_enabled'])?0:1,
            'report_expiry_days'=>min(365,max(1,absint($v['report_expiry_days']??30))),
            'privacy_url'=>esc_url_raw($v['privacy_url']??get_privacy_policy_url()),
        ];
    }
    public static function page(): void { if(!current_user_can('manage_options'))return; $o=self::get(); ?>
    <div class="wrap"><h1>Source More Platform Settings</h1><?php if(isset($_GET['smtp_mail_test'])): ?><div class="notice notice-<?php echo $_GET['smtp_mail_test']==='success'?'success':'error'; ?> is-dismissible"><p><?php echo $_GET['smtp_mail_test']==='success'?'Test email sent successfully.':'Test email failed. Configure SMTP and try again.'; ?></p></div><?php endif; ?><form method="post" action="options.php"><?php settings_fields('smtp_platform'); ?>
    <table class="form-table" role="presentation">
    <tr><th>Lead notification email</th><td><input class="regular-text" type="email" name="smtp_platform_options[notification_email]" value="<?php echo esc_attr($o['notification_email']); ?>"></td></tr>
    <tr><th>Report link expiry</th><td><input type="number" min="1" max="365" name="smtp_platform_options[report_expiry_days]" value="<?php echo esc_attr($o['report_expiry_days']); ?>"> days</td></tr>
    <tr><th>Privacy policy URL</th><td><input class="large-text" type="url" name="smtp_platform_options[privacy_url]" value="<?php echo esc_attr($o['privacy_url']); ?>"></td></tr>
    <tr><th>MPS assistant</th><td><label><input type="checkbox" name="smtp_platform_options[assistant_enabled]" value="1" <?php checked($o['assistant_enabled']); ?>> Enable floating assistant</label></td></tr>
    <tr><th>Optional AI endpoint</th><td><input class="large-text" type="url" name="smtp_platform_options[assistant_endpoint]" value="<?php echo esc_attr($o['assistant_endpoint']); ?>"><p class="description">Use an HTTPS server-side endpoint. Never add API keys in WordPress or browser code.</p></td></tr>
    <tr><th>Diagnostics logging</th><td><label><input type="checkbox" name="smtp_platform_options[logging_enabled]" value="1" <?php checked($o['logging_enabled']); ?>> Retain the latest 200 operational events</label></td></tr>
    </table><?php submit_button(); ?></form><hr><h2>Platform Modules</h2><p>Enable only the capabilities required for this site.</p><form method="post" action="options.php"><?php settings_fields('smtp_platform_modules'); SMTP_Modules::fields(); submit_button('Save Modules'); ?></form><hr><h2>Email delivery test</h2><p>Send a real test message to the notification email above.</p><a class="button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=smtp_test_email'),'smtp_test_email')); ?>">Send Test Email</a></div><?php }
}
