<?php
if (!defined('ABSPATH')) exit;
class SMTP_Leads {
    const POST_TYPE = 'smt_fleet_lead';
    public static function activate(){ self::register_post_type(); flush_rewrite_rules(); }
    public static function init(){
        add_action('init', [__CLASS__, 'register_post_type']);
        add_shortcode('smt_fleet_lead_form', [__CLASS__, 'shortcode']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
        add_filter('manage_'.self::POST_TYPE.'_posts_columns', [__CLASS__, 'columns']);
        add_action('manage_'.self::POST_TYPE.'_posts_custom_column', [__CLASS__, 'column_content'], 10, 2);
        add_filter('post_row_actions', [__CLASS__, 'row_actions'], 10, 2);
        add_action('add_meta_boxes', [__CLASS__, 'meta_boxes']);
        add_action('save_post_'.self::POST_TYPE, [__CLASS__, 'save_status']);
        add_action('admin_post_smt_export_leads', [__CLASS__, 'export_csv']);
        add_action('admin_post_smt_download_report', [__CLASS__, 'download_report']);
        add_action('admin_menu', [__CLASS__, 'submenu']);
    }
    public static function register_post_type(){
        register_post_type(self::POST_TYPE, [
            'labels'=>['name'=>'Source More Leads','singular_name'=>'Fleet Lead','menu_name'=>'Source More CRM','add_new_item'=>'Add Fleet Lead','edit_item'=>'View Fleet Lead'],
            'public'=>false,'show_ui'=>true,'show_in_menu'=>'smtp-platform','menu_icon'=>'dashicons-chart-line','supports'=>['title','editor'],'capability_type'=>'post','map_meta_cap'=>true
        ]);
    }
    public static function assets(){
        if (is_page_template('page-fleet-savings-calculator.php') || is_page('fleet-savings-calculator')) {
            wp_enqueue_style('smtp-platform', SMTP_PLATFORM_URL.'assets/css/platform.css', [], SMTP_PLATFORM_VERSION);
            wp_enqueue_script('smtp-platform', SMTP_PLATFORM_URL.'assets/js/platform.js', [], SMTP_PLATFORM_VERSION, true);
            wp_localize_script('smtp-platform','SMTPPlatform',[
                'rest'=>esc_url_raw(rest_url('source-more/v1/lead')),
                'nonce'=>wp_create_nonce('wp_rest'),
                'messages'=>[
                    'sending'=>'Preparing your report…','success'=>'Your report is ready.','error'=>'We could not prepare the report. Please check the fields and try again.'
                ]
            ]);
        }
    }
    public static function shortcode(){
        ob_start(); ?>
        <section class="smtp-lead-capture" id="smtp-lead-capture">
          <div class="smtp-lead-heading">
            <span class="smtp-step">02</span>
            <div><h2>Receive your branded savings report</h2><p>Enter your business details to save the result, download the PDF, and request a free fleet assessment.</p></div>
          </div>
          <form id="smtp-lead-form" class="smtp-lead-form" novalidate>
            <div class="smtp-form-grid">
              <label>Company name<input name="company" type="text" autocomplete="organization" required></label>
              <label>Contact person<input name="contact_name" type="text" autocomplete="name" required></label>
              <label>Business email<input name="email" type="email" autocomplete="email" required></label>
              <label>Phone / WhatsApp<input name="phone" type="tel" autocomplete="tel" required></label>
              <label>Industry<select name="industry"><option value="">Select industry</option><option>Banking & Financial Services</option><option>Manufacturing</option><option>Healthcare</option><option>Education</option><option>Government</option><option>Professional Services</option><option>Retail & Distribution</option><option>Other</option></select></label>
              <label>Number of locations<input name="locations" type="number" min="1" value="1"></label>
            </div>
            <label class="smtp-consent"><input type="checkbox" name="consent" value="1" required><span>I agree that Source More Technology may contact me about this assessment. My data will not be sold to third parties.</span></label>
            <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="smtp-honeypot" aria-hidden="true">
            <button type="submit" class="btn btn-gold smtp-submit"><i class="fa-solid fa-file-pdf"></i> Create My Savings Report</button>
            <div class="smtp-form-status" id="smtp-form-status" role="status" aria-live="polite"></div>
          </form>
          <div class="smtp-success" id="smtp-success" hidden>
            <i class="fa-solid fa-circle-check"></i><h3>Your report is ready</h3>
            <p>A copy of the report link has also been sent to your business email when WordPress email delivery is configured.</p>
            <div class="smtp-success-actions"><a id="smtp-download-report" class="btn btn-gold" href="#">Download PDF Report</a><a class="btn smtp-secondary" href="<?php echo esc_url(home_url('/contact/')); ?>">Book a Free Fleet Assessment</a></div>
          </div>
        </section>
        <?php return ob_get_clean();
    }
    public static function columns($cols){ return ['cb'=>$cols['cb'],'title'=>'Lead','company'=>'Company','contact'=>'Contact','savings'=>'Annual Savings','status'=>'Status','date'=>$cols['date']]; }
    public static function column_content($col,$id){
        if($col==='company') echo esc_html(get_post_meta($id,'company',true));
        if($col==='contact') echo esc_html(get_post_meta($id,'contact_name',true)).'<br><small>'.esc_html(get_post_meta($id,'email',true)).'</small>';
        if($col==='savings') echo 'EGP '.esc_html(number_format((float)get_post_meta($id,'annual_savings',true),0));
        if($col==='status') echo '<strong>'.esc_html(ucfirst(get_post_meta($id,'lead_status',true) ?: 'new')).'</strong>';
    }
    public static function row_actions($actions,$post){
        if($post->post_type===self::POST_TYPE){
            $token=get_post_meta($post->ID,'report_token',true);
            if($token) $actions['report']='<a target="_blank" href="'.esc_url(admin_url('admin-post.php?action=smt_download_report&lead='.$post->ID.'&token='.$token)).'">PDF Report</a>';
        }
        return $actions;
    }
    public static function meta_boxes(){ add_meta_box('smtp_lead_details','Fleet Assessment Details',[__CLASS__,'meta_box'],self::POST_TYPE,'normal','high'); }
    public static function meta_box($post){
        wp_nonce_field('smtp_save_status','smtp_status_nonce');
        $fields=['company'=>'Company','contact_name'=>'Contact person','email'=>'Email','phone'=>'Phone','industry'=>'Industry','locations'=>'Locations','devices'=>'Devices','mono_pages'=>'Monthly mono pages','color_pages'=>'Monthly color pages','mono_cpp'=>'Mono CPP','color_cpp'=>'Color CPP','fixed_cost'=>'Monthly fixed cost','saving_rate'=>'Optimization rate','current_cost'=>'Current annual cost','annual_savings'=>'Annual savings','optimized_cost'=>'Optimized annual cost','three_year'=>'Three-year savings'];
        echo '<table class="widefat striped"><tbody>';
        foreach($fields as $key=>$label) echo '<tr><th style="width:230px">'.esc_html($label).'</th><td>'.esc_html(get_post_meta($post->ID,$key,true)).'</td></tr>';
        echo '<tr><th>Lead status</th><td><select name="smtp_lead_status">';
        $current=get_post_meta($post->ID,'lead_status',true) ?: 'new'; foreach(['new','contacted','qualified','proposal','won','lost'] as $s) echo '<option '.selected($current,$s,false).' value="'.$s.'">'.ucfirst($s).'</option>';
        echo '</select></td></tr></tbody></table>';
    }
    public static function save_status($id){ if(!isset($_POST['smtp_status_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['smtp_status_nonce'])),'smtp_save_status'))return; if(!current_user_can('edit_post',$id))return; if(isset($_POST['smtp_lead_status'])){ $allowed=['new','contacted','qualified','proposal','won','lost']; $status=sanitize_key($_POST['smtp_lead_status']); if(in_array($status,$allowed,true)){update_post_meta($id,'lead_status',$status);update_post_meta($id,'last_activity',current_time('mysql'));}} }
    public static function submenu(){ add_submenu_page('smtp-platform','Export Leads','Export CSV','manage_options','smtp-export',[__CLASS__,'export_page']); }
    public static function export_page(){ echo '<div class="wrap"><h1>Export Source More Leads</h1><p>Download all calculator leads and assessment results as a CSV file.</p><a class="button button-primary" href="'.esc_url(wp_nonce_url(admin_url('admin-post.php?action=smt_export_leads'),'smtp_export')).'">Download CSV</a></div>'; }
    public static function export_csv(){
        if(!current_user_can('manage_options')||!check_admin_referer('smtp_export'))wp_die('Not allowed');
        $leads=get_posts(['post_type'=>self::POST_TYPE,'posts_per_page'=>-1,'post_status'=>'publish','orderby'=>'date','order'=>'DESC']);
        header('Content-Type: text/csv; charset=utf-8'); header('Content-Disposition: attachment; filename=source-more-leads-'.gmdate('Y-m-d').'.csv');
        $out=fopen('php://output','w'); fputcsv($out,['Date','Company','Contact','Email','Phone','Industry','Locations','Devices','Annual Savings','Current Cost','Status']);
        foreach($leads as $l) fputcsv($out,[$l->post_date,get_post_meta($l->ID,'company',true),get_post_meta($l->ID,'contact_name',true),get_post_meta($l->ID,'email',true),get_post_meta($l->ID,'phone',true),get_post_meta($l->ID,'industry',true),get_post_meta($l->ID,'locations',true),get_post_meta($l->ID,'devices',true),get_post_meta($l->ID,'annual_savings',true),get_post_meta($l->ID,'current_cost',true),get_post_meta($l->ID,'lead_status',true)]);
        fclose($out); exit;
    }
    public static function download_report(){
        $id=absint($_GET['lead']??0); $token=sanitize_text_field(wp_unslash($_GET['token']??''));
        $hash=(string)get_post_meta($id,'report_token_hash',true); $legacy=(string)get_post_meta($id,'report_token',true); $expires=(int)get_post_meta($id,'report_expires',true);
        $valid=$id && (($hash && wp_check_password($token,$hash)) || ($legacy && hash_equals($legacy,$token)));
        if(!$valid || ($expires && time()>$expires))wp_die('Invalid or expired report link.',403);
        $pdf=new SMTP_Simple_PDF(); $pdf->output_lead($id); exit;
    }
}
