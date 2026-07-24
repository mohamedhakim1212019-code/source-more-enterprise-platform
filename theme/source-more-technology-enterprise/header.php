<?php if (!defined('ABSPATH')) exit; ?>
<!doctype html><html <?php language_attributes(); ?>><head>
<meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?></head><body <?php body_class(); ?>><?php wp_body_open(); ?>
<header class="site-header" id="site-header">
 <div class="container header-inner">
  <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr__('Source More Technology home','source-more-technology'); ?>">
   <?php if (has_custom_logo()) { the_custom_logo(); } else { ?>
   <span class="brand-mark">SM</span><span class="brand-copy"><strong><?php bloginfo('name'); ?></strong><small><?php echo esc_html(get_bloginfo('description') ?: 'One Source. More Value.'); ?></small></span>
   <?php } ?>
  </a>
  <button class="menu-toggle" aria-label="<?php echo esc_attr__('Open navigation','source-more-technology'); ?>" aria-controls="primary-navigation" aria-expanded="false"><span></span><span></span><span></span></button>
  <nav class="primary-nav" id="primary-navigation" aria-label="<?php echo esc_attr__('Primary navigation','source-more-technology'); ?>">
   <?php wp_nav_menu([
     'theme_location'=>'primary','container'=>false,'menu_class'=>'enterprise-menu','menu_id'=>'primary-menu',
     'fallback_cb'=>'smt_primary_menu_fallback','depth'=>3,
   ]); ?>
   <div class="header-tools"><?php if (function_exists('smt_language_switcher')) smt_language_switcher(); ?><a class="header-cta" href="<?php echo esc_url(smt_page_url('contact')); ?>"><?php echo esc_html(smt_t('Contact Us','تواصل معنا')); ?></a></div>
  </nav>
 </div>
</header>
