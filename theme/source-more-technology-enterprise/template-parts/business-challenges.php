<section class="section business-challenges" id="business-challenges">
  <div class="container">
    <div class="section-heading centered reveal">
      <div>
        <span class="section-kicker"><?php echo esc_html(smt_home_value('challenges_kicker')); ?></span>
        <h2><?php echo esc_html(smt_home_value('challenges_title')); ?></h2>
      </div>
      <p><?php echo esc_html(smt_home_value('challenges_description')); ?></p>
    </div>
    <div class="challenge-grid">
      <?php foreach([
        ['fa-coins',smt_t('Rising operating costs','تكاليف ترتفع دون رؤية واضحة'),smt_t('Uncontrolled print, support, licensing, and infrastructure expenses reduce visibility and margin.','نساعدك على كشف مصادر الهدر وربط الإنفاق بالأداء الفعلي والقيمة المتحققة.'),'managed-print-services'],
        ['fa-triangle-exclamation',smt_t('Frequent downtime','أعطال تؤثر على استمرارية العمل'),smt_t('Reactive maintenance and aging assets interrupt teams and critical business processes.','نحوّل الصيانة من رد فعل إلى منظومة استباقية تحافظ على استمرارية فرق العمل.'),'annual-maintenance'],
        ['fa-file-circle-xmark',smt_t('Manual document workflows','إجراءات بطيئة تعتمد على الورق'),smt_t('Paper-heavy processes slow approvals, increase errors, and make information harder to find.','نرقمن المستندات ونختصر دورة الموافقات لنقل المعلومات بسرعة ودقة أكبر.'),'document-management'],
        ['fa-shield-virus',smt_t('Security exposure','مخاطر أمنية تتوسع مع النمو'),smt_t('Unprotected devices, identities, networks, and documents create avoidable business risk.','نبني طبقات حماية مترابطة للمستخدمين والأجهزة والشبكات والبيانات.'),'cybersecurity'],
        ['fa-network-wired',smt_t('Fragmented infrastructure','أنظمة وموردون يعملون في جزر منفصلة'),smt_t('Disconnected systems and vendors make support, scaling, and accountability more difficult.','نوحّد الرؤية والمسؤولية لنجعل الدعم والتوسع أكثر بساطة ووضوحًا.'),'it-infrastructure'],
        ['fa-chart-column',smt_t('Limited visibility','قرارات تُتخذ دون بيانات كافية'),smt_t('Without reliable data, leaders cannot measure utilization, service quality, or improvement.','نمنحك مؤشرات أوضح للاستخدام والتكلفة وجودة الخدمة وفرص التحسين.'),'services']
      ] as $item): ?>
      <article class="challenge-card reveal">
        <span class="challenge-icon"><i class="fa-solid <?php echo esc_attr($item[0]); ?>"></i></span>
        <h3><?php echo esc_html($item[1]); ?></h3>
        <p><?php echo esc_html($item[2]); ?></p>
        <a href="<?php echo esc_url(smt_page_url($item[3])); ?>"><?php echo esc_html(smt_t('See the solution','اكتشف الحل المناسب')); ?> <i class="fa-solid fa-arrow-right"></i></a>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
