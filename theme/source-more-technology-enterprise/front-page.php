<?php if (!defined('ABSPATH')) exit; get_header();
if (smt_has_editor_content()) { smt_render_editor_content(); }
else {
 get_template_part('template-parts/hero');
 get_template_part('template-parts/benefits');
 get_template_part('template-parts/business-challenges');
 get_template_part('template-parts/solutions-grid');
 get_template_part('template-parts/industries');
 get_template_part('template-parts/why-source-more');
 get_template_part('template-parts/insights');
 get_template_part('template-parts/final-cta');
}
get_footer();
