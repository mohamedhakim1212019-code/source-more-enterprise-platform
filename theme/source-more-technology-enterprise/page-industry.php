<?php /* Template Name: Industry Detail */
if (!defined('ABSPATH')) exit;
get_header();
smt_render_industry_detail(smt_industry_key_from_current_page());
get_footer();
