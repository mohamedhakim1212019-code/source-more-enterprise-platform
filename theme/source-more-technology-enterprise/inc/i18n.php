<?php
if (!defined('ABSPATH')) exit;

function smt_lang() {
    if (function_exists('pll_current_language')) {
        return pll_current_language('slug') ?: 'en';
    }
    return is_rtl() ? 'ar' : 'en';
}
function smt_is_ar() { return smt_lang() === 'ar'; }
function smt_t($en, $ar) { return smt_is_ar() ? $ar : $en; }
function smt_page_url($slug) {
    $page = get_page_by_path($slug);
    if ($page) {
        if (function_exists('pll_get_post')) {
            $translated = pll_get_post($page->ID, smt_lang());
            if ($translated) return get_permalink($translated);
        }
        return get_permalink($page);
    }
    return home_url('/' . trim($slug, '/') . '/');
}
function smt_home_url() {
    return function_exists('pll_home_url') ? pll_home_url(smt_lang()) : home_url('/');
}
function smt_language_switcher() {
    if (!function_exists('pll_the_languages')) return;
    $langs = pll_the_languages(['raw'=>1, 'hide_if_empty'=>0]);
    if (!$langs) return;
    echo '<div class="language-switcher" aria-label="'.esc_attr(smt_t('Language selector','اختيار اللغة')).'">';
    foreach ($langs as $lang) {
        $label = $lang['slug'] === 'ar' ? 'العربية' : strtoupper($lang['slug']);
        printf('<a href="%s" lang="%s" hreflang="%s" class="%s">%s</a>', esc_url($lang['url']), esc_attr($lang['slug']), esc_attr($lang['slug']), !empty($lang['current_lang'])?'is-current':'', esc_html($label));
    }
    echo '</div>';
}
