<?php
$about_image = smt_home_value('about_image_url');
?>
<section class="section home-about"><div class="container split-grid">
<div class="image-frame reveal"><?php if ($about_image): ?><img src="<?php echo esc_url($about_image); ?>" alt="<?php echo esc_attr(smt_t('Source More business approach','منهج عمل سورس مور')); ?>" width="1600" height="900" loading="lazy" decoding="async"><?php else: ?><?php smt_visual_picture('homepage_about', smt_t('Source More consultation focused on business outcomes','جلسة استشارية من سورس مور تركز على نتائج الأعمال'), ['class'=>'smt-visual-image']); ?><?php endif; ?>
<div class="experience-badge"><strong>20+</strong><span><?php echo esc_html(smt_t('Years of combined market experience','عامًا من الخبرة المتراكمة')); ?></span></div></div>
<div class="section-copy reveal"><span class="section-kicker"><?php echo esc_html(smt_home_value('about_kicker')); ?></span>
<h2><?php echo esc_html(smt_home_value('about_title')); ?></h2>
<p><?php echo esc_html(smt_home_value('about_description')); ?></p>
<div class="mini-points"><span><i class="fa-solid fa-check"></i><?php echo esc_html(smt_t('Solutions aligned with operational needs','حلول تبدأ من احتياجات التشغيل الفعلية')); ?></span><span><i class="fa-solid fa-check"></i><?php echo esc_html(smt_t('Consulting, implementation and support','استشارات وتنفيذ ودعم من جهة واحدة')); ?></span><span><i class="fa-solid fa-check"></i><?php echo esc_html(smt_t('Long-term focus on continuity and value','تركيز طويل الأجل على الاستمرارية والقيمة')); ?></span></div>
<a class="text-link" href="<?php echo esc_url(smt_page_url('about-us')); ?>"><?php echo esc_html(smt_home_value('about_cta_label')); ?> <i class="fa-solid fa-arrow-right"></i></a></div>
</div></section>
