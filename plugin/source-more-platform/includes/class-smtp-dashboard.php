<?php
if (!defined('ABSPATH')) { exit; }
class SMTP_Dashboard {
    public static function init(): void {
        add_action('admin_menu', [__CLASS__, 'menu'], 5);
        add_action('admin_enqueue_scripts', [__CLASS__, 'assets']);
        add_action('admin_post_smtp_test_email', [__CLASS__, 'test_email']);
    }
    public static function menu(): void {
        add_menu_page('Source More Platform','Source More CRM','edit_posts','smtp-platform',[__CLASS__,'page'],'dashicons-chart-line',26);
        add_submenu_page('smtp-platform','Dashboard','Dashboard','edit_posts','smtp-platform',[__CLASS__,'page']);
        add_submenu_page('smtp-platform','All Leads','All Leads','edit_posts','edit.php?post_type='.SMTP_Leads::POST_TYPE);
        add_submenu_page('smtp-platform','Add Fleet Lead','Add Fleet Lead','edit_posts','post-new.php?post_type='.SMTP_Leads::POST_TYPE);
    }
    public static function assets(string $hook): void {
        if (strpos($hook, 'smtp-platform') === false && strpos($hook, SMTP_Leads::POST_TYPE) === false) return;
        wp_add_inline_style('wp-admin', '.smtp-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:16px;margin:20px 0}.smtp-card{background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:20px}.smtp-card strong{display:block;font-size:30px;margin-top:8px}.smtp-health{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px}.smtp-health div{background:#fff;border-left:4px solid #2271b1;padding:14px}.smtp-actions{display:flex;gap:10px;flex-wrap:wrap;margin:18px 0}');
    }
    private static function count(string $status=''): int {
        $args=['post_type'=>SMTP_Leads::POST_TYPE,'post_status'=>'publish','fields'=>'ids','posts_per_page'=>-1,'no_found_rows'=>true];
        if ($status) $args['meta_query']=[['key'=>'lead_status','value'=>$status]];
        return count(get_posts($args));
    }
    public static function page(): void {
        if (!current_user_can('edit_posts')) return;
        $total=self::count(); $new=self::count('new'); $qualified=self::count('qualified'); $proposal=self::count('proposal'); $won=self::count('won');
        $opts=SMTP_Settings::get();
        ?>
        <div class="wrap"><h1>Source More CRM Dashboard</h1><p>Lead capture, fleet assessments, reports, assistant integration, and platform health.</p>
        <div class="smtp-cards">
          <div class="smtp-card">Total leads<strong><?php echo esc_html($total); ?></strong></div>
          <div class="smtp-card">New<strong><?php echo esc_html($new); ?></strong></div>
          <div class="smtp-card">Qualified<strong><?php echo esc_html($qualified); ?></strong></div>
          <div class="smtp-card">Proposal<strong><?php echo esc_html($proposal); ?></strong></div>
          <div class="smtp-card">Won<strong><?php echo esc_html($won); ?></strong></div>
        </div>
        <div class="smtp-actions"><a class="button button-primary" href="<?php echo esc_url(admin_url('post-new.php?post_type='.SMTP_Leads::POST_TYPE)); ?>">Add Fleet Lead</a><a class="button" href="<?php echo esc_url(admin_url('edit.php?post_type='.SMTP_Leads::POST_TYPE)); ?>">View All Leads</a><a class="button" href="<?php echo esc_url(admin_url('admin.php?page=smtp-settings')); ?>">Platform Settings</a></div>
        <h2>System status</h2><div class="smtp-health">
          <div><strong>WordPress</strong><br><?php echo esc_html(get_bloginfo('version')); ?></div>
          <div><strong>PHP</strong><br><?php echo esc_html(PHP_VERSION); ?></div>
          <div><strong>AI Assistant</strong><br><?php echo $opts['assistant_enabled'] ? ($opts['assistant_endpoint'] ? 'Enabled and connected' : 'Enabled — endpoint required') : 'Disabled'; ?></div>
          <div><strong>Lead Email</strong><br><?php echo esc_html($opts['notification_email']); ?></div>
        </div></div><?php
    }
    public static function test_email(): void {
        if (!current_user_can('manage_options')) wp_die('Not allowed',403);
        check_admin_referer('smtp_test_email');
        $to=SMTP_Settings::get()['notification_email'];
        $sent=wp_mail($to,'Source More Platform — Test Email','This confirms that WordPress email delivery is working for Source More Platform.');
        SMTP_Logger::info($sent ? 'Test email sent' : 'Test email failed',['recipient'=>$to]);
        wp_safe_redirect(add_query_arg(['page'=>'smtp-settings','smtp_mail_test'=>$sent?'success':'failed'],admin_url('admin.php'))); exit;
    }
}
