<section class="section home-industries"><div class="container">
<div class="section-heading centered reveal"><div><span class="section-kicker"><?php echo esc_html(smt_t('Industries We Support','القطاعات التي نخدمها')); ?></span><h2><?php echo esc_html(smt_t('Technology shaped around sector-specific priorities.','تكنولوجيا مصممة وفق أولويات كل قطاع.')); ?></h2></div><p><?php echo esc_html(smt_t('We align technology decisions with operating realities, security needs, service continuity, and compliance expectations.','نربط القرارات التكنولوجية بالواقع التشغيلي واحتياجات الأمان واستمرارية الخدمة ومتطلبات الامتثال.')); ?></p></div>
<div class="home-industry-grid">
<?php foreach([
['fa-building-columns',smt_t('Banking & Finance','البنوك والقطاع المالي')],['fa-landmark',smt_t('Government','القطاع الحكومي')],['fa-industry',smt_t('Manufacturing','التصنيع')],['fa-heart-pulse',smt_t('Healthcare','الرعاية الصحية')],['fa-graduation-cap',smt_t('Education','التعليم')],['fa-store',smt_t('Retail & Distribution','التجزئة والتوزيع')],['fa-truck-fast',smt_t('Logistics','الخدمات اللوجستية')],['fa-oil-well',smt_t('Oil & Gas','البترول والغاز')]
] as $item): ?><article class="home-industry-card reveal"><i class="fa-solid <?php echo esc_attr($item[0]); ?>"></i><strong><?php echo esc_html($item[1]); ?></strong></article><?php endforeach; ?>
</div></div></section>
