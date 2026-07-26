<?php if (!defined('ABSPATH')) exit; ?>
<section class="section about-capabilities" aria-labelledby="capabilities-title">
  <div class="container">
    <div class="section-heading centered reveal"><span class="section-kicker"><?php echo esc_html(smt_t('What We Do','ماذا نقدم')); ?></span><h2 id="capabilities-title"><?php echo esc_html(smt_t('Integrated solutions for the modern workplace','حلول متكاملة تصنع بيئة عمل أكثر كفاءة وذكاءً')); ?></h2><p><?php echo esc_html(smt_t('Our portfolio brings together technology, implementation expertise, and ongoing support through one coordinated relationship.','نجمع بين التكنولوجيا وخبرة التنفيذ والدعم المستمر في علاقة واحدة واضحة ومسؤولة.')); ?></p></div>
    <div class="capability-grid">
      <?php
      $items = [
        ['fa-print',smt_t('Managed Print Services','خدمات الطباعة المُدارة')],['fa-building',smt_t('Enterprise Printing','الطباعة المؤسسية')],['fa-gears',smt_t('Office Automation','أتمتة بيئة العمل')],['fa-folder-open',smt_t('Document Management','إدارة المستندات')],['fa-server',smt_t('IT Infrastructure','البنية التحتية لتكنولوجيا المعلومات')],['fa-cloud',smt_t('Cloud & Microsoft Solutions','حلول السحابة ومايكروسوفت')],['fa-shield-halved',smt_t('Network & Cybersecurity','الشبكات والأمن السيبراني')],['fa-screwdriver-wrench',smt_t('Annual Maintenance','الصيانة والدعم السنوي')],['fa-comments',smt_t('IT Consulting','الاستشارات التقنية')],['fa-arrow-trend-up',smt_t('Digital Transformation','التحول الرقمي')]
      ];
      foreach ($items as $item) : ?>
        <article class="capability-item reveal"><i class="fa-solid <?php echo esc_attr($item[0]); ?>" aria-hidden="true"></i><span><?php echo esc_html($item[1]); ?></span></article>
      <?php endforeach; ?>
    </div>
    <div class="center-action reveal"><a class="btn btn-primary" href="<?php echo esc_url(smt_page_url('solutions')); ?>"><?php echo esc_html(smt_t('View All Solutions','استكشف جميع الحلول')); ?></a></div>
  </div>
</section>
