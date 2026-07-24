<?php
get_template_part('template-parts/footer-content');
if (!is_page('fleet-savings-calculator')) : ?>
<a class="calculator-fab" href="<?php echo esc_url(smt_page_url('fleet-savings-calculator')); ?>" aria-label="<?php echo esc_attr(smt_t('Open Fleet Savings Calculator','افتح حاسبة توفير أسطول الطباعة')); ?>">
  <i class="fa-solid fa-calculator"></i><span><?php echo esc_html(smt_t('Calculate Savings','احسب التوفير')); ?></span>
</a>
<?php endif;
wp_footer();
?></body></html>
