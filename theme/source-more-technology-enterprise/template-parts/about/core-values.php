<?php if (!defined('ABSPATH')) exit; ?>
<section class="section section-values" aria-labelledby="values-title">
  <div class="container">
    <div class="section-heading reveal"><div><span class="section-kicker"><?php echo esc_html(smt_t('What Guides Us','ما الذي يوجّهنا')); ?></span><h2 id="values-title"><?php echo esc_html(smt_t('Our Core Values','قيمنا الأساسية')); ?></h2></div><p><?php echo esc_html(smt_t('These principles guide every recommendation, project, and customer relationship.','هذه المبادئ تحكم كل توصية ومشروع وعلاقة نبنيها مع عملائنا.')); ?></p></div>
    <div class="value-grid">
      <?php
      $values = [
        ['fa-trophy',smt_t('Customer Success','نجاح العميل'),smt_t('We measure our success by the business value our customers achieve.','نقيس نجاحنا بالقيمة والنتائج التي يحققها عملاؤنا.')],
        ['fa-wand-magic-sparkles',smt_t('Practical Innovation','ابتكار عملي'),smt_t('We adopt technologies that solve real problems and prepare organizations for growth.','نتبنى تقنيات تحل مشكلات حقيقية وتجهز المؤسسات للنمو.')],
        ['fa-scale-balanced',smt_t('Integrity','النزاهة'),smt_t('We work with honesty, transparency, accountability, and professional responsibility.','نعمل بوضوح وشفافية ومسؤولية مهنية كاملة.')],
        ['fa-star',smt_t('Excellence','التميّز'),smt_t('We maintain high standards from consultation through implementation and support.','نحافظ على مستوى عالٍ من الجودة من الاستشارة حتى التنفيذ والدعم.')],
        ['fa-people-group',smt_t('Partnership','الشراكة'),smt_t('We work as an extension of our customers’ teams, not simply as a supplier.','نعمل كامتداد لفريق العميل، لا كمورد ينتهي دوره عند التسليم.')],
        ['fa-arrows-rotate',smt_t('Continuous Improvement','التحسين المستمر'),smt_t('We review performance and optimize solutions throughout their lifecycle.','نراجع الأداء ونطوّر الحلول باستمرار طوال دورة حياتها.')],
      ];
      foreach ($values as $value) : ?>
        <article class="value-card reveal"><i class="fa-solid <?php echo esc_attr($value[0]); ?>" aria-hidden="true"></i><h3><?php echo esc_html($value[1]); ?></h3><p><?php echo esc_html($value[2]); ?></p></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
