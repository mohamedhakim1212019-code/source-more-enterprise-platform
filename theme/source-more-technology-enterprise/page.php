<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<main id="primary" class="site-main"><div class="container smt-editor-content">
<?php while(have_posts()): the_post(); ?><article <?php post_class(); ?>><header class="page-header"><h1><?php the_title(); ?></h1></header><?php the_content(); ?></article><?php endwhile; ?>
</div></main><?php get_footer(); ?>
