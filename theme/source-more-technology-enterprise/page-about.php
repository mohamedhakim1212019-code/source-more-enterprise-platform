<?php
/* Template Name: About Source More Technology */
if (!defined('ABSPATH')) exit;
if (smt_has_editor_content()) { get_header(); smt_render_editor_content(); get_footer(); return; }
require get_template_directory() . '/page-about-legacy.php';
