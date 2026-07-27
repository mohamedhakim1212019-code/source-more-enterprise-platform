<?php
if (!defined('ABSPATH')) exit;
function smt_settings_defaults() {
 return [
  'phone'=>'+20 100 000 0000','email'=>'info@sourcemoreg.com','whatsapp'=>'201000000000',
  'address_en'=>'Cairo, Egypt','address_ar'=>'القاهرة، مصر','linkedin'=>'','facebook'=>'','profile_url'=>'','lead_email'=>'','privacy_url'=>'','homepage_mode'=>'showcase',
 ];
}
function smt_get_setting($key) {
 $opts=wp_parse_args((array)get_option('smt_business_settings',[]),smt_settings_defaults());
 return isset($opts[$key]) ? $opts[$key] : '';
}
add_action('admin_menu',function(){
 add_options_page('Source More Settings','Source More Settings','manage_options','smt-settings','smt_settings_page');
});
add_action('admin_init',function(){
 register_setting('smt_settings_group','smt_business_settings',['sanitize_callback'=>'smt_sanitize_settings']);
});
function smt_sanitize_settings($input){
 $out=[];
 foreach(smt_settings_defaults() as $k=>$v){
  $value=isset($input[$k])?$input[$k]:'';
  if ($k === 'homepage_mode') {
    $out[$k] = in_array($value, ['showcase','editor'], true) ? $value : 'showcase';
  } else {
    $out[$k]=in_array($k,['email','lead_email'])?sanitize_email($value):(in_array($k,['linkedin','facebook','profile_url','privacy_url'])?esc_url_raw($value):sanitize_text_field($value));
  }
 }
 return $out;
}
function smt_settings_page(){
 if(!current_user_can('manage_options'))return; $o=wp_parse_args((array)get_option('smt_business_settings',[]),smt_settings_defaults()); ?>
 <div class="wrap"><h1>Source More Settings</h1><p>Update business details used throughout the theme.</p>
 <form method="post" action="options.php"><?php settings_fields('smt_settings_group'); ?>
 <table class="form-table" role="presentation">
 <?php $fields=['phone'=>'Phone','email'=>'Email','whatsapp'=>'WhatsApp number (country code, digits only)','address_en'=>'Address — English','address_ar'=>'Address — Arabic','linkedin'=>'LinkedIn URL','facebook'=>'Facebook URL','profile_url'=>'Company Profile PDF URL','lead_email'=>'Lead notification email','privacy_url'=>'Privacy Policy URL']; foreach($fields as $key=>$label): ?>
 <tr><th scope="row"><label for="smt_<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label></th><td><input class="regular-text" id="smt_<?php echo esc_attr($key); ?>" name="smt_business_settings[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($o[$key]); ?>"></td></tr>
 <?php endforeach; ?>
 <tr><th scope="row"><label for="smt_homepage_mode">Homepage Layout</label></th><td><select id="smt_homepage_mode" name="smt_business_settings[homepage_mode]"><option value="showcase" <?php selected($o['homepage_mode'],'showcase'); ?>>Showcase Homepage</option><option value="editor" <?php selected($o['homepage_mode'],'editor'); ?>>WordPress Editor Content</option></select><p class="description">Switch without deleting the existing homepage content.</p></td></tr>
 </table><?php submit_button(); ?></form></div><?php
}
