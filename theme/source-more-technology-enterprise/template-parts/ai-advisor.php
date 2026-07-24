<section class="section ai-advisor-section" id="ai-advisor">
  <div class="container">
    <div class="ai-advisor-card reveal">
      <div class="ai-advisor-copy">
        <span class="section-kicker light"><?php echo esc_html(smt_t('Ask Source More AI','اسأل مساعد Source More الذكي')); ?></span>
        <h2><?php echo esc_html(smt_t('Get guidance before you speak with sales.','احصل على إرشاد قبل التحدث مع فريق المبيعات.')); ?></h2>
        <p><?php echo esc_html(smt_t('Use our website assistant to explore services, compare options, understand managed print, or identify the right next step for your organization.','استخدم مساعد الموقع لاستكشاف الخدمات ومقارنة الخيارات وفهم الطباعة المُدارة أو تحديد الخطوة المناسبة لمؤسستك.')); ?></p>
        <div class="advisor-prompts" aria-label="<?php echo esc_attr(smt_t('Suggested questions','أسئلة مقترحة')); ?>">
          <span><i class="fa-solid fa-message"></i><?php echo esc_html(smt_t('How can I reduce print costs?','كيف أخفض تكاليف الطباعة؟')); ?></span>
          <span><i class="fa-solid fa-message"></i><?php echo esc_html(smt_t('Which solution fits my company?','ما الحل المناسب لشركتي؟')); ?></span>
          <span><i class="fa-solid fa-message"></i><?php echo esc_html(smt_t('What is Managed Print Services?','ما هي خدمات الطباعة المُدارة؟')); ?></span>
        </div>
        <div class="ai-advisor-actions">
          <button class="btn btn-gold" type="button" data-open-smtp-assistant><i class="fa-solid fa-wand-magic-sparkles"></i><?php echo esc_html(smt_t('Open AI Advisor','افتح المساعد الذكي')); ?></button>
          <a class="btn btn-outline" href="<?php echo esc_url(smt_page_url('contact')); ?>"><?php echo esc_html(smt_t('Talk to a Consultant','تحدث مع مستشار')); ?></a>
        </div>
      </div>
      <div class="advisor-window" aria-hidden="true">
        <div class="advisor-window-head"><span><i class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i></span><strong>Source More AI</strong></div>
        <div class="advisor-message advisor-bot"><i class="fa-solid fa-robot"></i><p><?php echo esc_html(smt_t('Tell me your business goal and I will guide you to the most relevant solution.','أخبرني بهدفك وسأرشدك إلى الحل الأنسب.')); ?></p></div>
        <div class="advisor-options"><span><?php echo esc_html(smt_t('Reduce costs','خفض التكاليف')); ?></span><span><?php echo esc_html(smt_t('Improve security','تحسين الأمان')); ?></span><span><?php echo esc_html(smt_t('Digitize workflows','رقمنة سير العمل')); ?></span></div>
      </div>
    </div>
  </div>
</section>
