<?php
if (!defined('ABSPATH')) exit;
final class SMTP_Modules {
    public static function defaults(): array { return ['crm'=>1,'products'=>1,'fleet'=>1,'assistant'=>1,'diagnostics'=>1]; }
    public static function all(): array { return wp_parse_args((array)get_option('smtp_platform_modules',[]),self::defaults()); }
    public static function enabled(string $module): bool { $all=self::all(); return !empty($all[$module]); }
    public static function register(): void { register_setting('smtp_platform_modules','smtp_platform_modules',['sanitize_callback'=>[__CLASS__,'sanitize']]); }
    public static function sanitize($value): array { $value=is_array($value)?$value:[]; $out=[]; foreach(self::defaults() as $key=>$default)$out[$key]=empty($value[$key])?0:1; return $out; }
    public static function fields(): void { $m=self::all(); $labels=['crm'=>'CRM & Leads','products'=>'Product Center & Quote Requests','fleet'=>'Fleet Calculator','assistant'=>'AI Assistant','diagnostics'=>'Diagnostics & Logs']; echo '<table class="form-table"><tbody>'; foreach($labels as $key=>$label) printf('<tr><th>%s</th><td><label><input type="checkbox" name="smtp_platform_modules[%s]" value="1" %s> Enable module</label></td></tr>',esc_html($label),esc_attr($key),checked(!empty($m[$key]),true,false)); echo '</tbody></table>'; }
}
