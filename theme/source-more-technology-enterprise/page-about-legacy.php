<?php
/**
 * Template Name: About Source More Technology
 * Template Post Type: page
 */
if (!defined('ABSPATH')) exit;
get_header();
?>
<main id="primary" class="site-main about-page">
    <?php
    get_template_part('template-parts/about/hero');
    get_template_part('template-parts/about/who-we-are');
    get_template_part('template-parts/about/why-source-more');
    get_template_part('template-parts/about/mission-vision');
    get_template_part('template-parts/about/core-values');
    get_template_part('template-parts/about/what-we-do');
    get_template_part('template-parts/about/why-choose-us');
    get_template_part('template-parts/about/industries');
    get_template_part('template-parts/about/approach');
    get_template_part('template-parts/about/cta');
    ?>
</main>
<?php get_footer(); ?>
