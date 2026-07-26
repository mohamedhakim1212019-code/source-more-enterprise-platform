<?php if (!defined('ABSPATH')) exit; ?>
<section class="section section-industries" aria-labelledby="industries-title">
  <div class="container">
    <div class="section-heading centered reveal"><span class="section-kicker"><?php echo esc_html(smt_t('Industries We Serve','القطاعات التي نخدمها')); ?></span><h2 id="industries-title"><?php echo esc_html(smt_t('Technology aligned with real operational needs','حلول تفهم طبيعة قطاعك وتحدياته اليومية')); ?></h2><p><?php echo esc_html(smt_t('We adapt each solution to the sector’s users, workflows, security requirements, regulations, and growth plans.','نصمم كل حل وفقًا لطبيعة المستخدمين وسير العمل ومتطلبات الأمان واللوائح وخطط النمو داخل كل قطاع.')); ?></p></div>
    <div class="industry-grid">
      <?php
      $industries = [
        ['fa-building-columns',smt_t('Banking & Finance','البنوك والخدمات المالية')],['fa-landmark',smt_t('Government','الجهات الحكومية')],['fa-heart-pulse',smt_t('Healthcare','الرعاية الصحية')],['fa-industry',smt_t('Manufacturing','التصنيع')],['fa-graduation-cap',smt_t('Education','التعليم')],['fa-cart-shopping',smt_t('Retail & Distribution','التجزئة والتوزيع')],['fa-truck-fast',smt_t('Logistics','الخدمات اللوجستية')],['fa-oil-well',smt_t('Oil & Gas','البترول والغاز')],['fa-hotel',smt_t('Hospitality','الضيافة')],['fa-briefcase',smt_t('Corporate Enterprises','الشركات والمؤسسات')],['fa-store',smt_t('SMEs','الشركات الصغيرة والمتوسطة')]
      ];
      foreach ($industries as $industry) : ?>
        <div class="industry-pill reveal"><i class="fa-solid <?php echo esc_attr($industry[0]); ?>" aria-hidden="true"></i><span><?php echo esc_html($industry[1]); ?></span></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
