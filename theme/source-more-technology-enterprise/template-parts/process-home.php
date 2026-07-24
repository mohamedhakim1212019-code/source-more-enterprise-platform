<section class="section home-process"><div class="container">
<div class="section-heading centered reveal"><div><span class="section-kicker"><?php echo esc_html(smt_t('How We Work','كيف نعمل')); ?></span><h2><?php echo esc_html(smt_t('A structured path from challenge to measurable improvement.','مسار منظم من التحدي إلى تحسين قابل للقياس.')); ?></h2></div><p><?php echo esc_html(smt_t('Every engagement follows a practical method designed to reduce risk and keep business outcomes visible.','يتبع كل مشروع منهجًا عمليًا لتقليل المخاطر وإبقاء نتائج الأعمال واضحة.')); ?></p></div>
<ol class="home-process-grid">
<?php foreach([
['01','fa-magnifying-glass-chart',smt_t('Assess','التقييم'),smt_t('Understand costs, assets, users, workflows, risks, and priorities.','فهم التكاليف والأصول والمستخدمين وسير العمل والمخاطر والأولويات.')],
['02','fa-pen-ruler',smt_t('Design','التصميم'),smt_t('Create a right-sized solution, roadmap, service model, and success measures.','إنشاء حل مناسب وخارطة طريق ونموذج خدمة ومقاييس نجاح.')],
['03','fa-gears',smt_t('Implement','التنفيذ'),smt_t('Deploy with structured coordination, communication, configuration, and testing.','التنفيذ بتنسيق منظم وتواصل وإعداد واختبار.')],
['04','fa-chart-line',smt_t('Support & Optimize','الدعم والتحسين'),smt_t('Maintain continuity, monitor performance, and improve as requirements evolve.','الحفاظ على الاستمرارية ومراقبة الأداء والتحسين مع تطور الاحتياجات.')]
] as $step): ?><li class="reveal"><span class="process-number"><?php echo esc_html($step[0]); ?></span><i class="fa-solid <?php echo esc_attr($step[1]); ?>"></i><h3><?php echo esc_html($step[2]); ?></h3><p><?php echo esc_html($step[3]); ?></p></li><?php endforeach; ?>
</ol></div></section>
