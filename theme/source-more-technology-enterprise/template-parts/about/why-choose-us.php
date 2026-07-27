<?php if (!defined('ABSPATH')) exit; ?>
<section class="section section-choose" aria-labelledby="choose-title">
  <div class="container">
    <div class="section-heading reveal"><div><span class="section-kicker light"><?php echo esc_html(smt_t('Our Difference','ما يميزنا')); ?></span><h2 id="choose-title"><?php echo esc_html(smt_t('Why organizations choose Source More','لماذا تختار المؤسسات سورس مور؟')); ?></h2></div><p><?php echo esc_html(smt_t('Commercial understanding, technical expertise, and accountable local support in one partnership.','فهم تجاري، وخبرة تقنية، ودعم محلي مسؤول في شراكة واحدة.')); ?></p></div>
    <div class="choose-grid">
      <?php
      $items = [
        ['01',smt_t('One Source. More Value.','مصدر واحد. قيمة أكبر.'),smt_t('One coordinated partner across multiple technology requirements.','شريك واحد ينسق احتياجات التكنولوجيا المختلفة ويمنحك رؤية أوضح ومسؤولية محددة.')],
        ['02',smt_t('Business-First Approach','نبدأ من هدف العمل'),smt_t('We define the business challenge before recommending technology.','نفهم التحدي والنتيجة المطلوبة أولًا، ثم نوصي بالتكنولوجيا المناسبة.')],
        ['03',smt_t('Enterprise Expertise','خبرة تناسب المؤسسات'),smt_t('Practical, scalable solutions built for demanding operational environments.','حلول عملية وقابلة للتوسع لبيئات العمل التي تتطلب اعتمادية وأداءً مستمرًا.')],
        ['04',smt_t('Cost Optimization','قيمة أفضل للاستثمار'),smt_t('Recommendations designed to reduce total cost and maximize return.','توصيات تهدف إلى خفض التكلفة الكلية وتعظيم العائد من الاستثمار التقني.')],
        ['05',smt_t('Reliable Support','دعم يمكنك الاعتماد عليه'),smt_t('Responsive service, preventive maintenance, and long-term commitment.','خدمة سريعة وصيانة استباقية والتزام يمتد لما بعد التنفيذ.')],
        ['06',smt_t('Scalable Solutions','حلول تنمو معك'),smt_t('Technology that evolves with your organization and future requirements.','تقنيات مرنة تتطور مع مؤسستك وتدعم احتياجاتها المستقبلية.')],
      ];
      foreach ($items as $item) : ?>
        <article class="choose-card reveal"><span><?php echo esc_html($item[0]); ?></span><h3><?php echo esc_html($item[1]); ?></h3><p><?php echo esc_html($item[2]); ?></p></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
