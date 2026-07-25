<?php
if (!defined('ABSPATH')) exit;
get_header();

$homepage_mode = function_exists('smt_get_setting') ? smt_get_setting('homepage_mode') : 'showcase';

if ($homepage_mode === 'editor' && function_exists('smt_has_editor_content') && smt_has_editor_content()) {
    smt_render_editor_content();
} else {
    echo '<main id="primary" class="site-main showcase-homepage">';
    get_template_part('template-parts/hero');
    get_template_part('template-parts/benefits');
    get_template_part('template-parts/business-challenges');
    get_template_part('template-parts/services');
    get_template_part('template-parts/technology-coverage');
    get_template_part('template-parts/calculator-teaser');
    get_template_part('template-parts/why-source-more');
    get_template_part('template-parts/industries-home');
    get_template_part('template-parts/process-home');
    get_template_part('template-parts/ai-advisor');
    get_template_part('template-parts/partner-ecosystem');
    get_template_part('template-parts/resources-home');
    get_template_part('template-parts/about');
    get_template_part('template-parts/final-cta');
    echo '</main>';
}

get_footer();
