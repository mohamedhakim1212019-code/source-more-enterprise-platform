<?php
get_header();
$type  = isset($_GET['type']) ? sanitize_key(wp_unslash($_GET['type'])) : '';
$brand = isset($_GET['brand']) ? sanitize_title(wp_unslash($_GET['brand'])) : '';
$args  = ['post_type'=>'smt_product','posts_per_page'=>12,'paged'=>max(1,get_query_var('paged'))];
if (in_array($type,['hardware','software','service'],true)) $args['meta_query']=[['key'=>'_smtp_product_type','value'=>$type]];
if ($brand) $args['tax_query']=[['taxonomy'=>'smt_product_brand','field'=>'slug','terms'=>$brand]];
$products = new WP_Query($args);
$brands   = get_terms(['taxonomy'=>'smt_product_brand','hide_empty'=>true]);

$product_type_label = static function(string $value): string {
    $labels = [
        'hardware'=>['Hardware','أجهزة'],
        'software'=>['Software','برمجيات'],
        'service'=>['Service / Subscription','خدمة / اشتراك'],
    ];
    $label = $labels[$value] ?? [ucwords(str_replace('-',' ',$value)),ucwords(str_replace('-',' ',$value))];
    return smt_t($label[0],$label[1]);
};
$availability_label = static function(string $value): string {
    $labels = [
        'available'=>['Available','متاح'],
        'limited'=>['Limited availability','متاح بكمية محدودة'],
        'pre-order'=>['Pre-order','طلب مسبق'],
        'on-request'=>['Available on request','متاح عند الطلب'],
        'out-of-stock'=>['Out of stock','غير متوفر حاليًا'],
    ];
    $label = $labels[$value] ?? [ucwords(str_replace('-',' ',$value)),ucwords(str_replace('-',' ',$value))];
    return smt_t($label[0],$label[1]);
};
?>
<main class="product-center">
<section class="ui-page-hero product-center-hero smt-photo-hero"><div class="container smt-photo-hero-grid"><div class="smt-photo-hero-copy"><h1><?php echo esc_html(smt_t('Product Center','مركز المنتجات')); ?></h1><p><?php echo esc_html(smt_t('Explore business hardware, software, subscriptions, and integrated workplace technology available from Source More.','اكتشف الأجهزة والبرمجيات والاشتراكات وحلول بيئة العمل المتكاملة التي توفرها سورس مور.')); ?></p><div class="hero-actions"><a class="btn btn-gold" href="#product-grid"><?php echo esc_html(smt_t('Browse Products','تصفح المنتجات')); ?></a><a class="btn btn-outline" href="<?php echo esc_url(smt_page_url('contact')); ?>"><?php echo esc_html(smt_t('Request Sourcing Support','اطلب دعم التوريد')); ?></a></div></div><div class="smt-photo-panel product-center-photo"><?php smt_visual_picture('products_hero', smt_t('Modern business technology showroom with print, endpoint and infrastructure products','صالة عرض حديثة لتكنولوجيا الأعمال تضم حلول الطباعة وأجهزة المستخدمين والبنية التحتية'), ['loading'=>'eager','fetchpriority'=>'high','class'=>'smt-visual-image']); ?><div class="smt-photo-badge"><i class="fa-solid fa-boxes-stacked"></i><span><strong><?php echo esc_html(smt_t('Hardware, software and services','أجهزة وبرمجيات وخدمات')); ?></strong><small><?php echo esc_html(smt_t('Selected around your business requirements','مختارة وفق احتياجات أعمالك')); ?></small></span></div></div></div></section>
<section class="section"><div class="container"><form class="product-filters" method="get"><label><?php echo esc_html(smt_t('Product type','نوع المنتج')); ?><select name="type"><option value=""><?php echo esc_html(smt_t('All types','كل الأنواع')); ?></option><option value="hardware" <?php selected($type,'hardware'); ?>><?php echo esc_html(smt_t('Hardware','أجهزة')); ?></option><option value="software" <?php selected($type,'software'); ?>><?php echo esc_html(smt_t('Software','برمجيات')); ?></option><option value="service" <?php selected($type,'service'); ?>><?php echo esc_html(smt_t('Service / Subscription','خدمة / اشتراك')); ?></option></select></label><label><?php echo esc_html(smt_t('Brand','العلامة التجارية')); ?><select name="brand"><option value=""><?php echo esc_html(smt_t('All brands','كل العلامات التجارية')); ?></option><?php if(!is_wp_error($brands)) foreach($brands as $term): ?><option value="<?php echo esc_attr($term->slug); ?>" <?php selected($brand,$term->slug); ?>><?php echo esc_html($term->name); ?></option><?php endforeach; ?></select></label><button class="btn btn-primary" type="submit"><?php echo esc_html(smt_t('Filter Products','تصفية المنتجات')); ?></button><a class="product-filter-reset" href="<?php echo esc_url(smt_products_url()); ?>"><?php echo esc_html(smt_t('Reset','إعادة ضبط')); ?></a></form>
<div class="product-grid" id="product-grid"><?php if($products->have_posts()): while($products->have_posts()): $products->the_post();
$type_label=get_post_meta(get_the_ID(),'_smtp_product_type',true) ?: 'hardware'; $availability=get_post_meta(get_the_ID(),'_smtp_availability',true) ?: 'available'; $model=get_post_meta(get_the_ID(),'_smtp_model',true); ?>
<article class="product-card"><a class="product-card-image" href="<?php the_permalink(); ?>"><?php if(has_post_thumbnail()) the_post_thumbnail('medium_large'); else echo '<span class="product-placeholder"><i class="fa-solid fa-box-open"></i></span>'; ?></a><div class="product-card-body"><div class="product-card-meta"><span><?php echo esc_html($product_type_label($type_label)); ?></span><span class="availability availability-<?php echo esc_attr($availability); ?>"><?php echo esc_html($availability_label($availability)); ?></span></div><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><?php if($model): ?><p class="product-model"><?php echo esc_html($model); ?></p><?php endif; ?><p><?php echo esc_html(get_the_excerpt()); ?></p><a class="product-card-link" href="<?php the_permalink(); ?>"><?php echo esc_html(smt_t('View product','عرض المنتج')); ?> <i class="fa-solid <?php echo smt_is_ar() ? 'fa-arrow-left' : 'fa-arrow-right'; ?>"></i></a></div></article>
<?php endwhile; else: ?><div class="ui-empty-state"><h2><?php echo esc_html(smt_t('No products found','لا توجد منتجات')); ?></h2><p><?php echo esc_html(smt_t('Try another filter or contact our team for sourcing support.','جرّب اختيار تصفية أخرى أو تواصل مع فريقنا للحصول على دعم التوريد.')); ?></p></div><?php endif; wp_reset_postdata(); ?></div><?php the_posts_pagination(['prev_text'=>smt_t('Previous','السابق'),'next_text'=>smt_t('Next','التالي')]); ?></div></section>
</main><?php get_footer(); ?>
