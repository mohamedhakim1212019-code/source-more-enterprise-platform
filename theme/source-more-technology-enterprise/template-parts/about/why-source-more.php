<?php if (!defined('ABSPATH')) exit; ?>
<section class="section about-brand-story" aria-labelledby="brand-story-title">
  <div class="container">
    <div class="section-heading centered reveal">
      <span class="section-kicker light"><?php echo esc_html(smt_t('Our Brand Philosophy','فلسفة علامتنا')); ?></span>
      <h2 id="brand-story-title"><?php echo esc_html(smt_t('Why “Source More”?','لماذا سورس مور؟')); ?></h2>
      <p><?php echo esc_html(smt_t(
        'Our name reflects a simpler, more accountable way to acquire, implement, manage, and improve business technology through one relationship.',
        'اسمنا يعكس طريقة أبسط وأكثر مسؤولية لإدارة احتياجات التكنولوجيا: مصدر واحد يمنح مؤسستك حلولًا أكثر تكاملًا وقيمة أكبر في كل مرحلة.'
      )); ?></p>
    </div>
    <div class="promise-grid">
      <?php
      $items = [
        ['fa-handshake', smt_t('One Trusted Partner','شريك واحد موثوق'), smt_t('One accountable relationship across procurement, implementation, support, and optimization.','جهة واحدة مسؤولة عن التوريد والتنفيذ والدعم والتحسين المستمر.')],
        ['fa-gauge-high', smt_t('More Efficiency','كفاءة أكبر'), smt_t('Connected solutions that simplify operations and reduce duplication.','حلول مترابطة تبسط العمليات وتقلل التكرار والهدر.')],
        ['fa-shield-halved', smt_t('More Reliability','اعتمادية أعلى'), smt_t('Enterprise-grade technologies backed by responsive local support.','تقنيات مؤسسية مدعومة بخدمة محلية سريعة ومسؤولة.')],
        ['fa-lightbulb', smt_t('More Innovation','ابتكار عملي'), smt_t('Modern tools that help organizations automate, adapt, and transform.','تقنيات حديثة تساعد المؤسسات على الأتمتة والتطور والتحول بثقة.')],
        ['fa-chart-line', smt_t('More Value','قيمة قابلة للقياس'), smt_t('Technology investments aligned with business outcomes and long-term return.','استثمارات تقنية مرتبطة بنتائج الأعمال والعائد طويل الأجل.')],
      ];
      foreach ($items as $item) : ?>
        <article class="promise-card reveal"><i class="fa-solid <?php echo esc_attr($item[0]); ?>" aria-hidden="true"></i><h3><?php echo esc_html($item[1]); ?></h3><p><?php echo esc_html($item[2]); ?></p></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
