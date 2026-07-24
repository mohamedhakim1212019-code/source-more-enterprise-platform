<?php
if (!defined('ABSPATH')) { exit; }
class SMTP_REST {
    public static function init(): void { add_action('rest_api_init',[__CLASS__,'routes']); }
    public static function routes(): void {
        foreach (['source-more/v1','source-more/v2'] as $ns) register_rest_route($ns,'/lead',['methods'=>'POST','callback'=>[__CLASS__,'lead'],'permission_callback'=>'__return_true']);
    }
    private static function num(array $d,string $k): float { return max(0,(float)($d[$k]??0)); }
    public static function lead(WP_REST_Request $r) {
        if(!wp_verify_nonce($r->get_header('X-WP-Nonce'),'wp_rest')) return new WP_Error('bad_nonce','Security check failed.',['status'=>403]);
        if(!SMTP_Rate_Limiter::check('lead',5,900)) return new WP_Error('rate_limited','Too many submissions. Please try again later.',['status'=>429]);
        $d=(array)$r->get_json_params(); if(!empty($d['website'])) return new WP_Error('spam','Invalid submission.',['status'=>400]);
        $company=sanitize_text_field($d['company']??''); $name=sanitize_text_field($d['contact_name']??''); $email=sanitize_email($d['email']??''); $phone=sanitize_text_field($d['phone']??'');
        if(!$company||!$name||!is_email($email)||!$phone||empty($d['consent'])) return new WP_Error('validation','Please complete the required business and consent fields.',['status'=>422]);
        $devices=max(1,self::num($d,'devices')); $mono=self::num($d,'mono_pages'); $color=self::num($d,'color_pages'); $mono_cpp=self::num($d,'mono_cpp'); $color_cpp=self::num($d,'color_cpp'); $fixed=self::num($d,'fixed_cost'); $rate=min(35,max(5,self::num($d,'saving_rate')));
        $current=(($mono*$mono_cpp)+($color*$color_cpp)+$fixed)*12; $savings=$current*($rate/100); $optimized=$current-$savings; $three=$savings*3;
        $id=wp_insert_post(['post_type'=>SMTP_Leads::POST_TYPE,'post_status'=>'publish','post_title'=>$company.' — '.$name],true); if(is_wp_error($id)){SMTP_Logger::error('Lead insert failed',['error'=>$id->get_error_message()]);return $id;}
        $data=['company'=>$company,'contact_name'=>$name,'email'=>$email,'phone'=>$phone,'industry'=>sanitize_text_field($d['industry']??''),'locations'=>max(1,absint($d['locations']??1)),'devices'=>$devices,'mono_pages'=>$mono,'color_pages'=>$color,'mono_cpp'=>$mono_cpp,'color_cpp'=>$color_cpp,'fixed_cost'=>$fixed,'saving_rate'=>$rate,'current_cost'=>round($current,2),'annual_savings'=>round($savings,2),'optimized_cost'=>round($optimized,2),'three_year'=>round($three,2),'lead_status'=>'new','lead_source'=>'fleet-calculator','consent_timestamp'=>current_time('mysql'),'last_activity'=>current_time('mysql')];
        $token=wp_generate_password(48,false,false); $opts=SMTP_Settings::get(); $data['report_token_hash']=wp_hash_password($token); $data['report_expires']=time()+(DAY_IN_SECONDS*(int)$opts['report_expiry_days']); foreach($data as $k=>$v)update_post_meta($id,$k,$v);
        $url=add_query_arg(['action'=>'smt_download_report','lead'=>$id,'token'=>$token],admin_url('admin-post.php'));
        self::email($id,$data,$url); SMTP_Logger::info('Fleet lead created',['lead_id'=>$id,'source'=>'fleet-calculator']); do_action('smtp_platform_lead_created',$id,$data);
        return new WP_REST_Response(['success'=>true,'lead_id'=>$id,'report_url'=>esc_url_raw($url),'annual_savings'=>$savings],201);
    }
    private static function email(int $id,array $d,string $url): void {
        $opts=SMTP_Settings::get(); $to=sanitize_email($opts['notification_email']);
        $subject='New Fleet Assessment Lead: '.$d['company'];
        $body="A new fleet savings lead has been submitted.\n\nCompany: {$d['company']}\nContact: {$d['contact_name']}\nEmail: {$d['email']}\nPhone: {$d['phone']}\nIndustry: {$d['industry']}\nDevices: {$d['devices']}\nEstimated annual savings: EGP ".number_format($d['annual_savings'],0)."\n\nView lead: ".admin_url('post.php?post='.$id.'&action=edit')."\nReport: $url";
        if(!wp_mail($to,$subject,$body)) SMTP_Logger::warning('Admin lead email failed',['lead_id'=>$id]);
        $customer="Dear {$d['contact_name']},\n\nThank you for using the Source More Technology Fleet Savings Calculator.\n\nYour estimated annual savings are EGP ".number_format($d['annual_savings'],0).".\n\nDownload your report: $url\n\nThis indicative estimate should be validated through a professional fleet assessment.\n\nSource More Technology\nOne Source, More Value";
        if(!wp_mail($d['email'],'Your Fleet Savings Report — Source More Technology',$customer)) SMTP_Logger::warning('Customer report email failed',['lead_id'=>$id]);
    }
}
