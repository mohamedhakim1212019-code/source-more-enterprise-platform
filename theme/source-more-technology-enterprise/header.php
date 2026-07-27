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
   <?php smt_brand_logo('header'); ?>
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
