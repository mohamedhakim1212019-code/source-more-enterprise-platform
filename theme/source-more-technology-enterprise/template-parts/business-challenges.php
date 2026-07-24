<section class="section business-challenges" id="business-challenges">
  <div class="container">
    <div class="section-heading centered reveal">
      <div>
        <span class="section-kicker"><?php echo esc_html(smt_t('Business Challenges','تحديات الأعمال')); ?></span>
        <h2><?php echo esc_html(smt_t('Technology should remove friction—not create more of it.','يجب أن تزيل التكنولوجيا التعقيد، لا أن تضيف المزيد منه.')); ?></h2>
      </div>
      <p><?php echo esc_html(smt_t('We help organizations solve the operational issues that quietly increase cost, risk, and downtime.','نساعد المؤسسات على معالجة المشكلات التشغيلية التي ترفع التكلفة والمخاطر ووقت التوقف دون وضوح.')); ?></p>
    </div>
    <div class="challenge-grid">
      <?php foreach([
        ['fa-coins',smt_t('Rising operating costs','ارتفاع تكاليف التشغيل'),smt_t('Uncontrolled print, support, licensing, and infrastructure expenses reduce visibility and margin.','تكاليف الطباعة والدعم والتراخيص والبنية التحتية غير المنضبطة تقلل الرؤية والربحية.'),'managed-print-services'],
        ['fa-triangle-exclamation',smt_t('Frequent downtime','تكرار الأعطال'),smt_t('Reactive maintenance and aging assets interrupt teams and critical business processes.','الصيانة التفاعلية والأصول القديمة تعطل فرق العمل والعمليات الحيوية.'),'annual-maintenance'],
        ['fa-file-circle-xmark',smt_t('Manual document workflows','سير عمل يدوي للمستندات'),smt_t('Paper-heavy processes slow approvals, increase errors, and make information harder to find.','الاعتماد الكبير على الورق يبطئ الموافقات ويزيد الأخطاء ويصعب الوصول للمعلومات.'),'document-management'],
        ['fa-shield-virus',smt_t('Security exposure','مخاطر أمنية'),smt_t('Unprotected devices, identities, networks, and documents create avoidable business risk.','الأجهزة والهويات والشبكات والمستندات غير المحمية تخلق مخاطر يمكن تجنبها.'),'cybersecurity'],
        ['fa-network-wired',smt_t('Fragmented infrastructure','بنية تحتية مجزأة'),smt_t('Disconnected systems and vendors make support, scaling, and accountability more difficult.','الأنظمة والموردون غير المترابطين يصعبون الدعم والتوسع وتحديد المسؤولية.'),'it-infrastructure'],
        ['fa-chart-column',smt_t('Limited visibility','ضعف الرؤية والتحكم'),smt_t('Without reliable data, leaders cannot measure utilization, service quality, or improvement.','بدون بيانات موثوقة يصعب قياس الاستخدام وجودة الخدمة وفرص التحسين.'),'services']
      ] as $item): ?>
      <article class="challenge-card reveal">
        <span class="challenge-icon"><i class="fa-solid <?php echo esc_attr($item[0]); ?>"></i></span>
        <h3><?php echo esc_html($item[1]); ?></h3>
        <p><?php echo esc_html($item[2]); ?></p>
        <a href="<?php echo esc_url(smt_page_url($item[3])); ?>"><?php echo esc_html(smt_t('See the solution','اكتشف الحل')); ?> <i class="fa-solid fa-arrow-right"></i></a>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
