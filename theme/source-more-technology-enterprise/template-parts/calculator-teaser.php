<section class="section calculator-spotlight" id="fleet-savings">
  <div class="container">
    <div class="calculator-spotlight-card reveal">
      <div class="calculator-spotlight-copy">
        <span class="calculator-badge"><i class="fa-solid fa-bolt"></i> <?php echo esc_html(smt_t('Free 2-minute assessment','تقييم مجاني في دقيقتين')); ?></span>
        <span class="section-kicker light"><?php echo esc_html(smt_t('Fleet Savings Calculator','حاسبة توفير أسطول الطباعة')); ?></span>
        <h2><?php echo esc_html(smt_t('How much is inefficient printing costing your business?','كم تكلف الطباعة غير الفعالة شركتك؟')); ?></h2>
        <p><?php echo esc_html(smt_t('Enter a few basic fleet figures to estimate your annual print cost, potential savings, optimized operating cost, and three-year opportunity. No registration is required.','أدخل بعض بيانات أسطول الطباعة الأساسية لتقدير التكلفة السنوية والتوفير المحتمل والتكلفة التشغيلية بعد التحسين وفرصة التوفير خلال ثلاث سنوات. لا يلزم التسجيل.')); ?></p>
        <div class="calculator-benefit-list">
          <span><i class="fa-solid fa-check"></i><?php echo esc_html(smt_t('Annual cost estimate','تقدير التكلفة السنوية')); ?></span>
          <span><i class="fa-solid fa-check"></i><?php echo esc_html(smt_t('Potential savings','التوفير المحتمل')); ?></span>
          <span><i class="fa-solid fa-check"></i><?php echo esc_html(smt_t('Three-year impact','الأثر خلال ثلاث سنوات')); ?></span>
        </div>
        <a class="btn btn-gold calculator-primary-cta" href="<?php echo esc_url(smt_page_url('fleet-savings-calculator')); ?>">
          <i class="fa-solid fa-calculator"></i><?php echo esc_html(smt_t('Calculate My Savings','احسب التوفير الآن')); ?><i class="fa-solid fa-arrow-right"></i>
        </a>
        <small class="calculator-disclaimer"><?php echo esc_html(smt_t('Indicative estimate only. Actual savings depend on fleet condition, usage, contracts, consumables, and service model.','النتائج تقديرية فقط، ويعتمد التوفير الفعلي على حالة الأسطول والاستخدام والعقود والمستهلكات ونموذج الخدمة.')); ?></small>
      </div>
      <div class="calculator-preview" aria-label="<?php echo esc_attr(smt_t('Savings calculator preview','معاينة حاسبة التوفير')); ?>">
        <div class="preview-topline"><span><?php echo esc_html(smt_t('Example opportunity','مثال على فرصة التوفير')); ?></span><i class="fa-solid fa-chart-pie"></i></div>
        <div class="preview-saving"><small><?php echo esc_html(smt_t('Potential annual savings','التوفير السنوي المحتمل')); ?></small><strong>EGP 487,000</strong></div>
        <div class="preview-metrics">
          <article><span><?php echo esc_html(smt_t('Cost reduction','خفض التكلفة')); ?></span><strong>28%</strong></article>
          <article><span><?php echo esc_html(smt_t('3-year impact','أثر 3 سنوات')); ?></span><strong>EGP 1.46M</strong></article>
        </div>
        <div class="preview-chart" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><i></i></div>
        <p><?php echo esc_html(smt_t('Your result is calculated instantly from your own figures.','يتم حساب نتيجتك فوراً بناءً على بياناتك.')); ?></p>
      </div>
    </div>
  </div>
</section>
