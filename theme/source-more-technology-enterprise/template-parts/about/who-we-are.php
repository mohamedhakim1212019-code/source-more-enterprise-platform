<?php if (!defined('ABSPATH')) exit; ?>
<section class="section about-who" aria-labelledby="who-title">
  <div class="container split-grid">
    <div class="image-frame reveal">
      <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/who-we-are-placeholder.svg'); ?>" alt="<?php echo esc_attr(smt_t('Source More Technology team and workplace','فريق Source More Technology وبيئة العمل')); ?>" width="1200" height="900" loading="lazy">
      <div class="experience-badge"><strong><?php echo esc_html(smt_t('One','شريك واحد')); ?></strong><span><?php echo esc_html(smt_t('trusted source for integrated technology','لمختلف احتياجات التكنولوجيا')); ?></span></div>
    </div>
    <div class="section-copy reveal">
      <span class="section-kicker"><?php echo esc_html(smt_t('Who We Are','من نحن')); ?></span>
      <h2 id="who-title"><?php echo esc_html(smt_t(
        'Technology should make business simpler, faster, and more resilient.',
        'نؤمن أن التكنولوجيا يجب أن تجعل الأعمال أبسط وأسرع وأكثر قدرة على الاستمرار.'
      )); ?></h2>
      <p><?php echo esc_html(smt_t(
        'Source More Technology is an Egyptian business technology company helping organizations modernize the way they print, communicate, secure data, manage infrastructure, and run daily operations.',
        'سورس مور تكنولوجي شركة مصرية متخصصة في حلول تكنولوجيا الأعمال، نساعد المؤسسات على تطوير بيئة الطباعة، والاتصالات، وحماية البيانات، والبنية التحتية، والعمليات اليومية.'
      )); ?></p>
      <p><?php echo esc_html(smt_t(
        'Instead of coordinating multiple disconnected suppliers, our customers work with one accountable partner that connects technology, implementation, service, and continuous improvement.',
        'بدلًا من التعامل مع موردين متعددين وحلول منفصلة، يحصل عملاؤنا على شريك واحد مسؤول يربط بين التكنولوجيا والتنفيذ والخدمة والتطوير المستمر.'
      )); ?></p>
      <p><?php echo esc_html(smt_t(
        'Every recommendation is designed around measurable outcomes: lower operating cost, higher productivity, stronger protection, clearer visibility, and sustainable growth.',
        'كل توصية نقدمها تُبنى حول نتائج واضحة وقابلة للقياس: تكلفة تشغيل أقل، إنتاجية أعلى، حماية أقوى، رؤية أوضح، ونمو مستدام.'
      )); ?></p>
      <blockquote class="brand-quote">One Source. More Value.</blockquote>
    </div>
  </div>
</section>
