<?php
$hero_primary_url = smt_home_url_value('hero_primary_url', smt_page_url('solutions'));
$hero_secondary_url = smt_home_url_value('hero_secondary_url', smt_page_url('contact'));
$hero_image = smt_home_value('hero_image_url');
?>
<section class="hero showcase-hero" aria-labelledby="showcase-hero-title">
  <div class="showcase-hero-grid" aria-hidden="true"></div>
  <div class="container hero-grid showcase-hero-inner">
    <div class="hero-copy reveal">
      <span class="showcase-kicker"><i class="fa-solid fa-sparkles"></i><?php echo esc_html(smt_home_value('hero_kicker')); ?></span>
      <h1 id="showcase-hero-title"><?php echo wp_kses(smt_home_value('hero_title'), ['span'=>['class'=>true], 'strong'=>[], 'br'=>[]]); ?></h1>
      <p><?php echo esc_html(smt_home_value('hero_description')); ?></p>
      <div class="hero-actions">
        <a class="btn btn-gold" href="<?php echo esc_url($hero_primary_url); ?>"><?php echo esc_html(smt_home_value('hero_primary_label')); ?><i class="fa-solid fa-arrow-right"></i></a>
        <a class="btn btn-outline" href="<?php echo esc_url($hero_secondary_url); ?>"><?php echo esc_html(smt_home_value('hero_secondary_label')); ?></a>
      </div>
      <div class="showcase-proof" aria-label="<?php echo esc_attr(smt_t('Our delivery strengths','نقاط قوة التنفيذ')); ?>">
        <span><i class="fa-solid fa-circle-check"></i><?php echo esc_html(smt_t('Vendor-neutral guidance','اختيارات مدروسة تناسب احتياجك')); ?></span>
        <span><i class="fa-solid fa-circle-check"></i><?php echo esc_html(smt_t('Local delivery and support','تنفيذ محلي ودعم مستمر')); ?></span>
        <span><i class="fa-solid fa-circle-check"></i><?php echo esc_html(smt_t('Measurable business outcomes','نتائج أعمال قابلة للقياس')); ?></span>
      </div>
    </div>

    <div class="hero-visual reveal">
      <div class="showcase-visual-shell">
        <span class="showcase-orbit showcase-orbit-one"></span>
        <span class="showcase-orbit showcase-orbit-two"></span>
        <?php if ($hero_image): ?>
          <img class="smt-visual-image" src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr(smt_t('Integrated technology platform covering print, cloud, infrastructure, security and automation','منظومة تقنية متكاملة تشمل الطباعة والسحابة والبنية التحتية والأمان والأتمتة')); ?>" width="1600" height="900" loading="eager" decoding="async" fetchpriority="high">
        <?php else: ?>
          <?php smt_visual_picture('homepage_hero', smt_t('Source More team in an integrated print, cloud, infrastructure and security workplace','فريق سورس مور داخل بيئة عمل متكاملة تجمع الطباعة والسحابة والبنية التحتية والأمان'), ['loading'=>'eager','fetchpriority'=>'high','class'=>'smt-visual-image showcase-hero-photo']); ?>
        <?php endif; ?>
        <div class="showcase-float showcase-float-top"><i class="fa-solid fa-shield-halved"></i><div><strong><?php echo esc_html(smt_t('Secure by Design','أمان يبدأ من التصميم')); ?></strong><small><?php echo esc_html(smt_t('People, data and devices','الأفراد والبيانات والأجهزة')); ?></small></div></div>
        <div class="showcase-float showcase-float-bottom"><i class="fa-solid fa-chart-line"></i><div><strong><?php echo esc_html(smt_t('Operational Visibility','رؤية أوضح للأداء')); ?></strong><small><?php echo esc_html(smt_t('Cost, performance and control','التكلفة والأداء والتحكم')); ?></small></div></div>
      </div>
    </div>
  </div>

  <div class="container showcase-hero-stats reveal">
    <article><strong>20+</strong><span><?php echo esc_html(smt_t('Years of combined market experience','عامًا من الخبرة المتراكمة في السوق')); ?></span></article>
    <article><strong>8</strong><span><?php echo esc_html(smt_t('Core enterprise solution areas','مجالات أساسية لحلول الأعمال')); ?></span></article>
    <article><strong>1</strong><span><?php echo esc_html(smt_t('Integrated delivery model','شريك واحد للتنفيذ المتكامل')); ?></span></article>
    <article><strong>EG</strong><span><?php echo esc_html(smt_t('Local expertise for organizations in Egypt','خبرة محلية تفهم احتياجات السوق المصري')); ?></span></article>
  </div>
</section>
