<section class="section section-services"><div class="container">
<div class="section-heading reveal"><div><span class="section-kicker"><?php echo esc_html(smt_t('Integrated Solutions','حلول متكاملة')); ?></span><h2><?php echo esc_html(smt_t('Everything your organization needs to work smarter.','كل ما تحتاجه مؤسستك للعمل بذكاء أكبر.')); ?></h2></div>
<p><?php echo esc_html(smt_t('Deploy one solution or combine multiple services into a managed technology environment built around your priorities.','طبّق حلاً واحدًا أو اجمع عدة خدمات ضمن بيئة تكنولوجية مُدارة مبنية حول أولوياتك.')); ?></p></div>
<div class="service-grid">
<?php $s=[
['fa-print',smt_t('Managed Print Services','خدمات الطباعة المُدارة'),smt_t('Control fleets, automate supplies, improve uptime, and reduce total print cost.','تحكم في الأسطول وأتمت المستلزمات وحسّن الجاهزية وخفّض التكلفة الكلية للطباعة.'),'managed-print-services'],
['fa-file-lines',smt_t('Document Management','إدارة المستندات'),smt_t('Digitize documents, automate workflows, and improve access to information.','رقمن المستندات وأتمت سير العمل وحسّن الوصول إلى المعلومات.'),'document-management'],
['fa-server',smt_t('IT Infrastructure','البنية التحتية'),smt_t('Build resilient networks, servers, endpoints, and data environments.','أنشئ شبكات وخوادم وأجهزة وبيئات بيانات مرنة.'),'it-infrastructure'],
['fa-cloud',smt_t('Cloud & Microsoft','السحابة ومايكروسوفت'),smt_t('Modernize collaboration, identity, productivity, and business continuity.','طوّر التعاون والهوية والإنتاجية واستمرارية الأعمال.'),'cloud-microsoft-solutions'],
['fa-shield-halved',smt_t('Cybersecurity','الأمن السيبراني'),smt_t('Protect users, devices, networks, data, and critical operations.','احمِ المستخدمين والأجهزة والشبكات والبيانات والعمليات الحيوية.'),'cybersecurity'],
['fa-screwdriver-wrench',smt_t('Annual Maintenance','الصيانة السنوية'),smt_t('Keep office and IT equipment reliable through planned, responsive support.','حافظ على موثوقية أجهزة المكتب وتكنولوجيا المعلومات من خلال دعم مخطط وسريع.'),'annual-maintenance']
]; foreach($s as $x): ?>
<article class="service-card reveal"><span class="service-icon"><i class="fa-solid <?php echo esc_attr($x[0]); ?>"></i></span>
<h3><?php echo esc_html($x[1]); ?></h3><p><?php echo esc_html($x[2]); ?></p>
<a href="<?php echo esc_url(smt_page_url($x[3])); ?>"><?php echo esc_html(smt_t('Learn more','اعرف المزيد')); ?> <i class="fa-solid fa-arrow-right"></i></a></article><?php endforeach; ?>
</div><div class="section-footer-link reveal"><a class="btn btn-primary" href="<?php echo esc_url(smt_page_url('solutions')); ?>"><?php echo esc_html(smt_t('View All Solutions','عرض كل الحلول')); ?></a></div></div></section>
