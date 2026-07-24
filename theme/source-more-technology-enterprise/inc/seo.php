<?php
if (!defined('ABSPATH')) exit;
function smt_breadcrumbs() {
    if (is_front_page()) return;
    echo '<nav class="smt-breadcrumbs" aria-label="'.esc_attr(smt_t('Breadcrumb','مسار التنقل')).'"><div class="container">';
    echo '<a href="'.esc_url(smt_home_url()).'">'.esc_html(smt_t('Home','الرئيسية')).'</a><span aria-hidden="true">/</span>';
    if (is_singular('post')) {
        $page_for_posts=(int)get_option('page_for_posts');
        if ($page_for_posts) echo '<a href="'.esc_url(get_permalink($page_for_posts)).'">'.esc_html(get_the_title($page_for_posts)).'</a><span aria-hidden="true">/</span>';
    }
    echo '<span aria-current="page">'.esc_html(wp_get_document_title()).'</span></div></nav>';
}
add_action('wp_head',function(){
    if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION')) return;
    $org=[
      '@context'=>'https://schema.org','@type'=>'Organization','name'=>get_bloginfo('name'),
      'url'=>home_url('/'),'email'=>smt_get_setting('email'),'telephone'=>smt_get_setting('phone'),
      'address'=>['@type'=>'PostalAddress','addressLocality'=>smt_t(smt_get_setting('address_en'),smt_get_setting('address_ar')),'addressCountry'=>'EG']
    ];
    echo '<script type="application/ld+json">'.wp_json_encode($org,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).'</script>';
},30);
