<section class="section home-process"><div class="container">
<div class="section-heading centered reveal"><div><span class="section-kicker"><?php echo esc_html(smt_home_value('process_kicker')); ?></span><h2><?php echo esc_html(smt_home_value('process_title')); ?></h2></div><p><?php echo esc_html(smt_home_value('process_description')); ?></p></div>
<ol class="home-process-grid">
<?php foreach([
['01','fa-magnifying-glass-chart',smt_t('Assess','نفهم بيئة العمل'),smt_t('Understand costs, assets, users, workflows, risks, and priorities.','نحلل التكاليف والأصول والمستخدمين وسير العمل والمخاطر والأولويات.')],
['02','fa-pen-ruler',smt_t('Design','نحدد فرص التحسين'),smt_t('Create a right-sized solution, roadmap, service model, and success measures.','نحدد الفرص ونصمم خارطة طريق ونموذج خدمة ومقاييس نجاح واضحة.')],
['03','fa-gears',smt_t('Implement','ننفذ ونربط الأنظمة'),smt_t('Deploy with structured coordination, communication, configuration, and testing.','ننفذ بتنسيق منظم وإعداد واختبار يضمن انتقالًا سلسًا وآمنًا.')],
['04','fa-chart-line',smt_t('Support & Optimize','ندعم ونقيس النتائج'),smt_t('Maintain continuity, monitor performance, and improve as requirements evolve.','نحافظ على الاستمرارية ونراقب الأداء ونطوّر الحل مع تغير احتياجاتك.')]
] as $step): ?><li class="reveal"><span class="process-number"><?php echo esc_html($step[0]); ?></span><i class="fa-solid <?php echo esc_attr($step[1]); ?>"></i><h3><?php echo esc_html($step[2]); ?></h3><p><?php echo esc_html($step[3]); ?></p></li><?php endforeach; ?>
</ol></div></section>
