<section class="section home-industries"><div class="container">
<div class="section-heading centered reveal"><div><span class="section-kicker"><?php echo esc_html(smt_home_value('industries_kicker')); ?></span><h2><?php echo esc_html(smt_home_value('industries_title')); ?></h2></div><p><?php echo esc_html(smt_home_value('industries_description')); ?></p></div>
<div class="home-industry-grid">
<?php foreach([
['fa-building-columns',smt_t('Banking & Finance','البنوك والخدمات المالية')],['fa-landmark',smt_t('Government','الجهات الحكومية والقطاع العام')],['fa-industry',smt_t('Manufacturing','المصانع والشركات الصناعية')],['fa-heart-pulse',smt_t('Healthcare','الرعاية الصحية')],['fa-graduation-cap',smt_t('Education','التعليم')],['fa-store',smt_t('Retail & Distribution','التجزئة والقطاع التجاري')],['fa-truck-fast',smt_t('Logistics','الخدمات اللوجستية')],['fa-oil-well',smt_t('Oil & Gas','البترول والغاز')]
] as $item): ?><article class="home-industry-card reveal"><i class="fa-solid <?php echo esc_attr($item[0]); ?>"></i><strong><?php echo esc_html($item[1]); ?></strong></article><?php endforeach; ?>
</div></div></section>
