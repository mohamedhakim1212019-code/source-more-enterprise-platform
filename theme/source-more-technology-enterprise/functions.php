<?php
if (!defined('ABSPATH')) exit;
require_once get_template_directory() . '/inc/components.php';
require_once get_template_directory() . '/inc/i18n.php';
require_once get_template_directory() . '/inc/mega-navigation.php';
require_once get_template_directory() . '/inc/homepage-content.php';
require_once get_template_directory() . '/inc/visual-media.php';
require_once get_template_directory() . '/inc/theme-settings.php';
require_once get_template_directory() . '/inc/solution-pages.php';
require_once get_template_directory() . '/inc/industry-resources.php';
require_once get_template_directory() . '/inc/bilingual-page-setup.php';
require_once get_template_directory() . '/inc/starter-site.php';
require_once get_template_directory() . '/inc/contact-handler.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/platform-integration.php';
require_once get_template_directory() . '/inc/content-migration.php';

add_action('after_setup_theme', function(){
  load_theme_textdomain('source-more-technology', get_template_directory().'/languages');
  add_theme_support('title-tag'); add_theme_support('post-thumbnails'); add_theme_support('custom-logo',['height'=>70,'width'=>280,'flex-height'=>true,'flex-width'=>true]); add_theme_support('html5',['search-form','gallery','caption','script','style','comment-list','comment-form']); add_theme_support('automatic-feed-links'); add_theme_support('responsive-embeds'); add_theme_support('align-wide'); add_theme_support('editor-styles'); add_editor_style('assets/css/editor.css');
  register_nav_menus(['primary'=>__('Primary Menu','source-more-technology'),'footer'=>__('Footer Menu','source-more-technology')]);
});
add_action('wp_enqueue_scripts', function(){
  wp_enqueue_style('smt-fonts','https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap',[],null);
  wp_enqueue_style('smt-icons','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css',[],'6.7.2');
  wp_enqueue_style('smt-main',get_template_directory_uri().'/assets/css/main.css',[],'7.9.4');
  if (is_rtl() || (function_exists('smt_is_ar') && smt_is_ar())) wp_enqueue_style('smt-rtl',get_template_directory_uri().'/rtl.css',['smt-main'],'7.9.4');
  wp_enqueue_script('smt-main',get_template_directory_uri().'/assets/js/main.js',[],'7.9.4',true);
});
add_filter('body_class',function($classes){ $classes[]='smt-lang-'.sanitize_html_class(smt_lang()); return $classes; });

add_filter('wp_resource_hints', function($urls, $relation_type){
  if ($relation_type === 'preconnect') {
    $urls[] = ['href'=>'https://fonts.googleapis.com','crossorigin'=>'anonymous'];
    $urls[] = ['href'=>'https://fonts.gstatic.com','crossorigin'=>'anonymous'];
  }
  return $urls;
},10,2);


/** v6.0 production foundations. */
function smt_primary_menu_fallback() {
    echo '<ul class="enterprise-menu">';
    foreach ([
        [smt_home_url(), smt_t('Home','الرئيسية')],
        [smt_page_url('about-us'), smt_t('About Us','من نحن')],
        [smt_page_url('solutions'), smt_t('Solutions','الحلول')],
        [get_post_type_archive_link('smt_product') ?: smt_page_url('products'), smt_t('Products','المنتجات')],
        [smt_page_url('industries'), smt_t('Industries','القطاعات')],
        [smt_page_url('resources'), smt_t('Resources','الموارد')],
        [smt_page_url('contact'), smt_t('Contact Us','تواصل معنا')],
    ] as $item) printf('<li><a href="%s">%s</a></li>', esc_url($item[0]), esc_html($item[1]));
    echo '</ul>';
}

add_action('wp_enqueue_scripts', function(){
    wp_localize_script('smt-main','smtTheme',[
        'ajaxUrl'=>admin_url('admin-ajax.php'),
        'contactNonce'=>wp_create_nonce('smt_contact_submit'),
        'messages'=>[
            'sending'=>smt_t('Sending…','جارٍ الإرسال…'),
            'error'=>smt_t('Something went wrong. Please try again.','حدث خطأ. يرجى المحاولة مرة أخرى.'),
            'openNavigation'=>smt_t('Open navigation','فتح قائمة التنقل'),
            'closeNavigation'=>smt_t('Close navigation','إغلاق قائمة التنقل'),
            'toggleSubmenu'=>smt_t('Toggle submenu','فتح أو إغلاق القائمة الفرعية'),
        ],
    ]);
},20);

add_action('wp_head', function(){
    if (is_singular() && pings_open()) printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
},1);

/** Use the signed-off SM monogram for the browser tab and saved shortcuts. */
add_action('wp_head', function(){
    $icon = get_template_directory_uri() . '/assets/images/branding/source-more-icon-approved.png?ver=7.9.4';
    printf('<link rel="icon" href="%s" type="image/png" sizes="512x512">', esc_url($icon));
    printf('<link rel="shortcut icon" href="%s" type="image/png">', esc_url($icon));
    printf('<link rel="apple-touch-icon" href="%s" sizes="512x512">', esc_url($icon));
},100);

/** Enterprise v7: create and assign editable WordPress navigation menus. */
function smt_v7_ensure_page(string $title, string $slug): int {
    $page = get_page_by_path($slug);
    if ($page instanceof WP_Post) return (int) $page->ID;
    return (int) wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>$title,'post_name'=>$slug]);
}
function smt_v7_setup_navigation(): void {
    if (get_option('smt_v7_navigation_ready')) return;
    $items = [
      ['Home','home'],['About Us','about-us'],['Solutions','solutions'],['Products','products'],
      ['Industries','industries'],['Resources','resources'],['Contact Us','contact']
    ];
    $menu_name = 'Source More Primary Menu';
    $menu = wp_get_nav_menu_object($menu_name);
    $menu_id = $menu ? (int)$menu->term_id : (int)wp_create_nav_menu($menu_name);
    if ($menu_id && !is_wp_error($menu_id)) {
      $existing = wp_get_nav_menu_items($menu_id) ?: [];
      $existing_objects = array_map(fn($i)=>(int)$i->object_id, $existing);
      foreach ($items as [$title,$slug]) {
        $page_id = smt_v7_ensure_page($title,$slug);
        if ($page_id && !in_array($page_id,$existing_objects,true)) {
          wp_update_nav_menu_item($menu_id,0,['menu-item-title'=>$title,'menu-item-object'=>'page','menu-item-object-id'=>$page_id,'menu-item-type'=>'post_type','menu-item-status'=>'publish']);
        }
      }
      $locations = get_theme_mod('nav_menu_locations',[]); $locations['primary']=$menu_id; set_theme_mod('nav_menu_locations',$locations);
    }
    update_option('smt_v7_navigation_ready',1,false);
}
add_action('after_switch_theme','smt_v7_setup_navigation');
add_action('admin_init','smt_v7_setup_navigation');

/** Use Gutenberg content whenever an editor has added content. */
function smt_has_editor_content(?int $post_id=null): bool {
    $post_id = $post_id ?: get_the_ID();
    return trim((string)get_post_field('post_content',$post_id)) !== '';
}
function smt_render_editor_content(): void {
    while (have_posts()) { the_post(); echo '<main id="primary" class="site-main"><div class="container smt-editor-content">'; the_content(); echo '</div></main>'; }
}

/** Redirect obsolete /services/ requests to the real Solutions hub. */
add_action('template_redirect',function(){
  if (is_page('services')) { wp_safe_redirect(smt_page_url('solutions'),301); exit; }
});


/**
 * v7.2 navigation structure.
 * Adds editable solution child links to the generated primary menu without
 * removing Products or overwriting a manually selected custom menu.
 */
function smt_v72_setup_navigation(): void {
    if (get_option('smt_v72_navigation_ready')) return;

    $locations = get_theme_mod('nav_menu_locations', []);
    $menu_id = isset($locations['primary']) ? (int) $locations['primary'] : 0;
    if (!$menu_id) {
        smt_v7_setup_navigation();
        $locations = get_theme_mod('nav_menu_locations', []);
        $menu_id = isset($locations['primary']) ? (int) $locations['primary'] : 0;
    }
    if (!$menu_id) return;

    $menu = wp_get_nav_menu_object($menu_id);
    if (!$menu || $menu->name !== 'Source More Primary Menu') {
        update_option('smt_v72_navigation_ready', 1, false);
        return;
    }

    $items = wp_get_nav_menu_items($menu_id) ?: [];
    $by_object = [];
    foreach ($items as $item) $by_object[(int) $item->object_id] = $item;

    $solutions_id = smt_v7_ensure_page('Solutions', 'solutions');
    $solutions_menu_item = $by_object[$solutions_id] ?? null;
    $parent_id = $solutions_menu_item ? (int) $solutions_menu_item->ID : 0;

    $solution_pages = [
        ['Managed Print Services', 'managed-print-services'],
        ['Enterprise Printing', 'enterprise-printing'],
        ['Document Management', 'document-management'],
        ['IT Infrastructure', 'it-infrastructure'],
        ['Cloud & Microsoft', 'cloud-microsoft-solutions'],
        ['Cybersecurity', 'cybersecurity'],
        ['Office Automation', 'office-automation'],
        ['Annual Maintenance', 'annual-maintenance'],
    ];

    foreach ($solution_pages as [$title, $slug]) {
        $page_id = smt_v7_ensure_page($title, $slug);
        if (!$page_id || isset($by_object[$page_id])) continue;
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title' => $title,
            'menu-item-object' => 'page',
            'menu-item-object-id' => $page_id,
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $parent_id,
            'menu-item-status' => 'publish',
        ]);
    }

    update_option('smt_v72_navigation_ready', 1, false);
}
add_action('after_switch_theme', 'smt_v72_setup_navigation', 20);
add_action('admin_init', 'smt_v72_setup_navigation', 20);
