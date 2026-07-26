<?php if (!defined('ABSPATH')) exit; ?>
<section class="about-hero" aria-labelledby="about-hero-title">
  <div class="container about-hero-grid">
    <div class="about-hero-copy reveal">
      <span class="eyebrow"><?php echo esc_html(smt_t('About Source More Technology','عن سورس مور تكنولوجي')); ?></span>
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
    <div class="about-hero-visual reveal">
      <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/about-hero-placeholder.svg'); ?>" alt="<?php echo esc_attr(smt_t('Integrated business technology illustration','تصميم يعبّر عن حلول الأعمال والتكنولوجيا المتكاملة')); ?>" width="1920" height="850">
    </div>
  </div>
</section>
