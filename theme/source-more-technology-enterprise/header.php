<?php if (!defined('ABSPATH')) exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content','source-more-technology'); ?></a>
<header class="site-header" id="site-header">
 <div class="container header-inner">
  <div class="site-branding">
   <?php if (has_custom_logo()) : ?>
    <?php the_custom_logo(); ?>
   <?php else : ?>
    <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="<?php echo esc_attr__('Source More Technology home','source-more-technology'); ?>">
     <span class="brand-mark" aria-hidden="true">SM</span>
     <span class="brand-copy"><strong><?php bloginfo('name'); ?></strong><small><?php echo esc_html(get_bloginfo('description') ?: 'One Source. More Value.'); ?></small></span>
    </a>
   <?php endif; ?>
  </div>
  <button class="menu-toggle" type="button" aria-label="<?php echo esc_attr__('Open navigation','source-more-technology'); ?>" aria-controls="primary-navigation" aria-expanded="false">
   <span></span><span></span><span></span>
  </button>
  <nav class="primary-nav" id="primary-navigation" aria-label="<?php echo esc_attr__('Primary navigation','source-more-technology'); ?>">
   <?php if (function_exists('smt_render_mega_navigation')) { smt_render_mega_navigation(); } else { smt_primary_menu_fallback(); } ?>
   <div class="header-tools"><?php if (function_exists('smt_language_switcher')) smt_language_switcher(); ?><a class="header-cta" href="<?php echo esc_url(smt_page_url('contact')); ?>"><?php echo esc_html(smt_t('Contact Us','تواصل معنا')); ?></a></div>
  </nav>
  <button class="nav-backdrop" type="button" aria-label="<?php echo esc_attr__('Close navigation','source-more-technology'); ?>" tabindex="-1"></button>
 </div>
</header>
