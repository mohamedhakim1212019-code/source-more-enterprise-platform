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

/**
 * Locate the canonical page for a slug even when the page is hierarchical.
 *
 * get_page_by_path('child-slug') does not reliably locate a page after it is
 * moved under a parent (for example /solutions/it-consulting/). This resolver
 * searches by post_name without language filters, then prefers the English
 * page that has a Polylang translation relationship.
 */
function smt_find_page_by_slug(string $slug, string $preferred_language = 'en'): ?WP_Post {
    static $cache = [];

    $slug = sanitize_title($slug);
    if ($slug === '') return null;

    $cache_key = $slug . '|' . $preferred_language;
    if (array_key_exists($cache_key, $cache)) return $cache[$cache_key];

    $candidates = get_posts([
        'post_type'              => 'page',
        'post_status'            => ['publish', 'private', 'draft', 'pending', 'future'],
        'posts_per_page'         => 50,
        'name'                   => $slug,
        'orderby'                => 'ID',
        'order'                  => 'ASC',
        'suppress_filters'       => true,
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    // Defensive database fallback for installations where the `name` query is
    // affected by another plugin or by hierarchical page query handling.
    if (!$candidates) {
        global $wpdb;
        $ids = $wpdb->get_col($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts}
             WHERE post_type = 'page'
               AND post_name = %s
               AND post_status NOT IN ('trash','auto-draft')
             ORDER BY ID ASC
             LIMIT 50",
            $slug
        ));
        $candidates = array_values(array_filter(array_map('get_post', array_map('intval', $ids))));
    }

    if (!$candidates) {
        $cache[$cache_key] = null;
        return null;
    }

    $best = null;
    $best_score = -1;

    foreach ($candidates as $candidate) {
        if (!$candidate instanceof WP_Post) continue;

        $score = 0;
        $language = function_exists('pll_get_post_language')
            ? (string) pll_get_post_language($candidate->ID, 'slug')
            : '';

        if ($language === $preferred_language) $score += 100;
        elseif ($language === '') $score += 20;

        if (function_exists('pll_get_post')) {
            $translated = (int) pll_get_post($candidate->ID, $preferred_language === 'en' ? 'ar' : 'en');
            if ($translated && $translated !== (int) $candidate->ID) $score += 60;
        }

        if ($candidate->post_status === 'publish') $score += 20;
        if ((int) $candidate->post_parent > 0) $score += 5;
        if (get_page_template_slug($candidate->ID)) $score += 5;

        if ($score > $best_score) {
            $best = $candidate;
            $best_score = $score;
        }
    }

    $cache[$cache_key] = $best instanceof WP_Post ? $best : null;
    return $cache[$cache_key];
}

function smt_page_url($slug) {
    $page = smt_find_page_by_slug((string) $slug, 'en');
    if ($page) {
        if (function_exists('pll_get_post')) {
            $translated = (int) pll_get_post($page->ID, smt_lang());
            if ($translated) return get_permalink($translated);
        }
        return get_permalink($page);
    }

    // Keep fallback URLs inside the currently selected language rather than
    // sending Arabic visitors to an English-root URL.
    return user_trailingslashit(trailingslashit(smt_home_url()) . trim((string) $slug, '/'));
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
