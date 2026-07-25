<?php
$final_primary = smt_home_url_value('final_primary_url', smt_page_url('contact'));
$final_secondary_default = smt_is_ar() ? smt_page_url('fleet-savings-calculator') : smt_page_url('solutions');
$final_secondary = smt_home_url_value('final_secondary_url', $final_secondary_default);
?>
<section class="final-cta"><div class="container final-cta-inner reveal">
<div><span class="section-kicker light"><?php echo esc_html(smt_home_value('final_kicker')); ?></span><h2><?php echo esc_html(smt_home_value('final_title')); ?></h2>
<p><?php echo esc_html(smt_home_value('final_description')); ?></p></div>
<div class="final-cta-actions"><a class="btn btn-white" href="<?php echo esc_url($final_primary); ?>"><?php echo esc_html(smt_home_value('final_primary_label')); ?></a><a class="btn btn-outline" href="<?php echo esc_url($final_secondary); ?>"><?php echo esc_html(smt_home_value('final_secondary_label')); ?></a></div>
</div></section>
