<?php if (!defined('ABSPATH')) exit; ?>
<section class="about-hero" aria-labelledby="about-hero-title">
  <div class="container about-hero-grid">
    <div class="about-hero-copy reveal">
      <span class="eyebrow"><?php echo esc_html(smt_t('About Source More','عن سورس مور')); ?></span>
      <h1 id="about-hero-title"><?php echo esc_html(smt_t(
        'Technology partnerships built around measurable business value.',
        'شراكة تقنية تحوّل التحديات إلى نتائج تدفع أعمالك للأمام.'
      )); ?></h1>
      <p><?php echo esc_html(smt_t(
        'We help organizations simplify technology decisions, improve operational performance, control costs, strengthen security, and move forward with confidence.',
        'نساعد المؤسسات على تبسيط قرارات التكنولوجيا، ورفع كفاءة التشغيل، وضبط التكاليف، وتعزيز الأمان، والانطلاق بثقة نحو المرحلة التالية من النمو.'
      )); ?></p>
      <div class="hero-actions">
        <a class="btn btn-gold" href="<?php echo esc_url(smt_page_url('contact')); ?>"><?php echo esc_html(smt_t('Talk to an Expert','تحدث مع خبير')); ?></a>
        <a class="btn btn-outline" href="<?php echo esc_url(smt_page_url('solutions')); ?>"><?php echo esc_html(smt_t('Explore Our Solutions','استكشف حلولنا')); ?></a>
      </div>
    </div>
    <div class="about-hero-visual smt-photo-panel reveal">
      <?php smt_visual_picture('about_hero', smt_t('Source More leadership team collaborating on business technology strategy','فريق قيادة سورس مور يتعاون لوضع استراتيجية تكنولوجيا الأعمال'), ['loading'=>'eager','fetchpriority'=>'high','class'=>'smt-visual-image']); ?>
      <div class="smt-photo-caption"><i class="fa-solid fa-handshake"></i><span><strong><?php echo esc_html(smt_t('Business-first partnership','شراكة تبدأ من أهداف الأعمال')); ?></strong><small><?php echo esc_html(smt_t('Strategy, implementation and accountable support','استراتيجية وتنفيذ ودعم مسؤول')); ?></small></span></div>
    </div>
  </div>
</section>
