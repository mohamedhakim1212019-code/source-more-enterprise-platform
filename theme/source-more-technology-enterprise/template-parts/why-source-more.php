<section class="section why-home"><div class="container why-home-grid">
<div class="why-home-copy reveal"><span class="section-kicker light"><?php echo esc_html(smt_home_value('why_kicker')); ?></span><h2><?php echo esc_html(smt_home_value('why_title')); ?></h2><p><?php echo esc_html(smt_home_value('why_description')); ?></p><a class="btn btn-gold" href="<?php echo esc_url(smt_page_url('contact')); ?>"><?php echo esc_html(smt_home_value('why_cta_label')); ?></a></div>
<div class="why-home-cards">
<?php foreach([
['fa-compass-drafting',smt_t('Assessment-led','نفهم قبل أن نوصي'),smt_t('Recommendations begin with your fleet, workflows, infrastructure, and business priorities.','نبدأ بفهم بيئة العمل والتحديات والأهداف قبل اقتراح أي منتج أو حل.')],
['fa-puzzle-piece',smt_t('Integrated delivery','كل ما تحتاجه من مصدر واحد'),smt_t('Print, IT, cloud, security, and automation can work as one coordinated environment.','نجمع الطباعة والبنية التحتية والسحابة والأمن والبرمجيات في منظومة مترابطة.')],
['fa-chart-pie',smt_t('Business-value focused','استثمار تقني يحقق قيمة'),smt_t('We connect technical decisions to cost, productivity, continuity, and measurable performance.','نربط كل قرار تقني بالتكلفة والإنتاجية والاستمرارية ونتائج يمكن قياسها.')],
['fa-headset',smt_t('Responsive local support','شراكة تستمر بعد التنفيذ'),smt_t('Clear ownership, practical communication, and support designed around service continuity.','نواصل المتابعة والدعم والتطوير بما يتناسب مع نمو أعمالك وتغير احتياجاتها.')]
] as $x): ?><article class="why-home-card reveal"><i class="fa-solid <?php echo esc_attr($x[0]); ?>"></i><div><h3><?php echo esc_html($x[1]); ?></h3><p><?php echo esc_html($x[2]); ?></p></div></article><?php endforeach; ?>
</div></div></section>
