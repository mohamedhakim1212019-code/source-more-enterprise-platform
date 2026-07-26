<?php
if (!defined('ABSPATH')) exit;

/**
 * Theme v7.6.3 bilingual page provisioning.
 * Creates only missing pages and translations. Existing titles/content are never overwritten.
 */
function smt_v761_bilingual_page_definitions(): array {
    return [
        'solutions' => ['en'=>'Solutions','ar'=>'الحلول','template'=>'page-services.php','parent'=>null],
        'contact' => ['en'=>'Contact Us','ar'=>'تواصل معنا','template'=>'page-contact.php','parent'=>null],
        'it-consulting' => ['en'=>'IT Consulting','ar'=>'استشارات تكنولوجيا المعلومات','template'=>'page-it-consulting.php','parent'=>'solutions'],
        'digital-transformation' => ['en'=>'Digital Transformation','ar'=>'التحول الرقمي','template'=>'page-digital-transformation.php','parent'=>'solutions'],
        'industries' => ['en'=>'Industries','ar'=>'القطاعات','template'=>'page-industries.php','parent'=>null],
        'banking' => ['en'=>'Banking & Financial Services','ar'=>'البنوك والخدمات المالية','template'=>'page-industry.php','parent'=>'industries'],
        'government' => ['en'=>'Government & Public Sector','ar'=>'الجهات الحكومية والقطاع العام','template'=>'page-industry.php','parent'=>'industries'],
        'manufacturing' => ['en'=>'Manufacturing','ar'=>'التصنيع','template'=>'page-industry.php','parent'=>'industries'],
        'healthcare' => ['en'=>'Healthcare','ar'=>'الرعاية الصحية','template'=>'page-industry.php','parent'=>'industries'],
        'education' => ['en'=>'Education','ar'=>'التعليم','template'=>'page-industry.php','parent'=>'industries'],
        'retail' => ['en'=>'Retail & Distribution','ar'=>'التجزئة والتوزيع','template'=>'page-industry.php','parent'=>'industries'],
        'logistics' => ['en'=>'Logistics & Distribution','ar'=>'الخدمات اللوجستية والتوزيع','template'=>'page-industry.php','parent'=>'industries'],
        'oil-gas' => ['en'=>'Oil & Gas','ar'=>'البترول والغاز','template'=>'page-industry.php','parent'=>'industries'],
        'resources' => ['en'=>'Resources','ar'=>'الموارد','template'=>'page-resources.php','parent'=>null],
        'fleet-savings-calculator' => ['en'=>'Fleet Savings Calculator','ar'=>'حاسبة وفر الطباعة','template'=>'page-fleet-savings-calculator.php','parent'=>'resources'],
        'company-profile' => ['en'=>'Company Profile','ar'=>'الملف التعريفي للشركة','template'=>'page-company-profile.php','parent'=>'resources'],
        'technology-assessment' => ['en'=>'Technology Assessment','ar'=>'تقييم البيئة التقنية','template'=>'page-technology-assessment.php','parent'=>'resources'],
        'faq-support' => ['en'=>'FAQs & Support','ar'=>'الأسئلة الشائعة والدعم','template'=>'page-faq-support.php','parent'=>'resources'],
        'insights' => ['en'=>'Insights & Articles','ar'=>'الرؤى والمقالات','template'=>'page-insights.php','parent'=>'resources'],
        'downloads' => ['en'=>'Downloads Center','ar'=>'مركز التحميل','template'=>'page-downloads.php','parent'=>'resources'],
    ];
}

function smt_v761_ensure_english_page(string $slug, array $config): int {
    $page = function_exists('smt_find_page_by_slug') ? smt_find_page_by_slug($slug, 'en') : get_page_by_path($slug, OBJECT, 'page');
    if (!$page) {
        $id = wp_insert_post([
            'post_type'=>'page','post_status'=>'publish','post_title'=>$config['en'],
            'post_name'=>$slug,'post_content'=>'','comment_status'=>'closed'
        ], true);
        if (is_wp_error($id)) return 0;
        $page = get_post($id);
    }
    $id = (int)$page->ID;
    if (!empty($config['template'])) update_post_meta($id,'_wp_page_template',$config['template']);
    if (function_exists('pll_get_post_language') && function_exists('pll_set_post_language') && !pll_get_post_language($id)) {
        pll_set_post_language($id,'en');
    }
    return $id;
}


/**
 * Find an existing unlinked Arabic page before creating a duplicate.
 * This is especially useful for Contact pages that may have been added
 * manually before the bilingual repair tool was introduced.
 */
function smt_v763_find_existing_arabic_page(array $config): int {
    $arabic_slug = sanitize_title((string)($config['ar'] ?? ''));
    $candidates = get_posts([
        'post_type'              => 'page',
        'post_status'            => ['publish','private','draft','pending','future'],
        'posts_per_page'         => 50,
        'suppress_filters'       => true,
        'orderby'                => 'ID',
        'order'                  => 'ASC',
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    foreach ($candidates as $candidate) {
        if (!$candidate instanceof WP_Post) continue;
        $is_match = ($arabic_slug && $candidate->post_name === $arabic_slug)
            || trim((string)$candidate->post_title) === trim((string)($config['ar'] ?? ''));
        if (!$is_match) continue;

        if (function_exists('pll_get_post_language')) {
            $lang = (string) pll_get_post_language($candidate->ID, 'slug');
            if ($lang && $lang !== 'ar') continue;
        }
        return (int)$candidate->ID;
    }

    return 0;
}

function smt_v761_create_or_repair_bilingual_pages(): array {
    $result = ['created_en'=>0,'created_ar'=>0,'repaired'=>0,'skipped'=>0,'errors'=>[]];
    $defs = smt_v761_bilingual_page_definitions();
    $en_ids = [];
    $ar_ids = [];

    foreach ($defs as $slug=>$config) {
        $before = get_page_by_path($slug, OBJECT, 'page');
        $en_id = smt_v761_ensure_english_page($slug,$config);
        if (!$en_id) { $result['errors'][]=$slug; continue; }
        if (!$before) $result['created_en']++;
        $en_ids[$slug]=$en_id;
    }

    foreach ($defs as $slug=>$config) {
        $en_id = $en_ids[$slug] ?? 0;
        if (!$en_id || empty($config['parent'])) continue;
        $parent_en = $en_ids[$config['parent']] ?? 0;
        if ($parent_en && (int)get_post_field('post_parent',$en_id) !== (int)$parent_en) {
            wp_update_post(['ID'=>$en_id,'post_parent'=>$parent_en]);
            $result['repaired']++;
        }
    }

    foreach ($defs as $slug=>$config) {
        $en_id = $en_ids[$slug] ?? 0;
        if (!$en_id) continue;
        if (!function_exists('pll_get_post') || !function_exists('pll_set_post_language') || !function_exists('pll_save_post_translations')) {
            $result['skipped']++;
            continue;
        }

        $ar_id = (int)pll_get_post($en_id,'ar');
        $parent_ar = !empty($config['parent']) ? ($ar_ids[$config['parent']] ?? 0) : 0;
        if (!$ar_id) {
            $ar_id = smt_v763_find_existing_arabic_page($config);
            if ($ar_id) {
                $updates=['ID'=>$ar_id];
                if ((int)get_post_field('post_parent',$ar_id) !== $parent_ar) $updates['post_parent']=$parent_ar;
                if (count($updates)>1) wp_update_post($updates);
                pll_set_post_language($ar_id,'ar');
                pll_save_post_translations(['en'=>$en_id,'ar'=>$ar_id]);
                $result['repaired']++;
            } else {
                $ar_id = wp_insert_post([
                    'post_type'=>'page','post_status'=>'publish','post_title'=>$config['ar'],
                    'post_name'=>sanitize_title($config['ar']),'post_content'=>'','comment_status'=>'closed',
                    'post_parent'=>$parent_ar,
                ], true);
                if (is_wp_error($ar_id)) { $result['errors'][]='ar:'.$slug; continue; }
                $ar_id=(int)$ar_id;
                pll_set_post_language($ar_id,'ar');
                pll_save_post_translations(['en'=>$en_id,'ar'=>$ar_id]);
                $result['created_ar']++;
            }
        } else {
            $updates=['ID'=>$ar_id];
            if ((int)get_post_field('post_parent',$ar_id) !== $parent_ar) $updates['post_parent']=$parent_ar;
            if (count($updates)>1) { wp_update_post($updates); $result['repaired']++; }
            pll_set_post_language($ar_id,'ar');
            pll_save_post_translations(['en'=>$en_id,'ar'=>$ar_id]);
        }
        if (!empty($config['template'])) update_post_meta($ar_id,'_wp_page_template',$config['template']);
        $ar_ids[$slug]=$ar_id;
    }

    flush_rewrite_rules(false);
    update_option('smt_v761_bilingual_pages_ready','7.6.3',false);
    return $result;
}

add_action('admin_init', function(){
    if (!current_user_can('manage_options')) return;
    if (get_option('smt_v761_bilingual_pages_ready') === '7.6.3') return;
    smt_v761_create_or_repair_bilingual_pages();
}, 60);

add_action('admin_menu', function(){
    add_theme_page(
        'Bilingual Pages Setup',
        'Bilingual Pages',
        'manage_options',
        'smt-bilingual-pages',
        'smt_v761_bilingual_pages_admin_page'
    );
});

function smt_v761_bilingual_pages_admin_page(): void {
    if (!current_user_can('manage_options')) return;
    $notice='';
    if (isset($_POST['smt_repair_bilingual_pages'])) {
        check_admin_referer('smt_repair_bilingual_pages');
        $r=smt_v761_create_or_repair_bilingual_pages();
        $notice=sprintf('Created EN: %d | Created AR: %d | Repaired: %d | Skipped: %d', $r['created_en'],$r['created_ar'],$r['repaired'],$r['skipped']);
    }
    $defs=smt_v761_bilingual_page_definitions();
    ?>
    <div class="wrap"><h1>Source More Bilingual Pages</h1><p>Create or repair the English and Arabic Contact, Solutions, Industries, and Resources pages without overwriting existing content.</p>
    <?php if($notice): ?><div class="notice notice-success is-dismissible"><p><?php echo esc_html($notice); ?></p></div><?php endif; ?>
    <form method="post"><?php wp_nonce_field('smt_repair_bilingual_pages'); ?><p><button class="button button-primary" name="smt_repair_bilingual_pages" value="1">Create / Repair Bilingual Pages</button></p></form>
    <table class="widefat striped"><thead><tr><th>Section</th><th>English</th><th>Arabic</th><th>Template</th></tr></thead><tbody>
    <?php foreach($defs as $slug=>$config): $en=function_exists('smt_find_page_by_slug')?smt_find_page_by_slug($slug,'en'):get_page_by_path($slug,OBJECT,'page'); $ar=($en&&function_exists('pll_get_post'))?pll_get_post($en->ID,'ar'):0; ?>
      <tr><td><code><?php echo esc_html($slug); ?></code></td><td><?php echo $en?'✓ '.esc_html(get_the_title($en)):'—'; ?></td><td><?php echo $ar?'✓ '.esc_html(get_the_title($ar)):'—'; ?></td><td><code><?php echo esc_html($config['template']); ?></code></td></tr>
    <?php endforeach; ?>
    </tbody></table></div>
    <?php
}
