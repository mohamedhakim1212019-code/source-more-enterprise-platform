<section class="section section-services showcase-solutions" id="featured-solutions"><div class="container">
<div class="section-heading reveal"><div><span class="section-kicker"><?php echo esc_html(smt_home_value('solutions_kicker')); ?></span><h2><?php echo esc_html(smt_home_value('solutions_title')); ?></h2></div>
<p><?php echo esc_html(smt_home_value('solutions_description')); ?></p></div>
<div class="service-grid showcase-service-grid">
<?php $s=[
['fa-print',smt_t('Managed Print Services','خدمات الطباعة المُدارة'),smt_t('Assess, optimize and manage your print environment with predictable service, supplies and performance visibility.','سيطر على تكلفة الطباعة، وارفع كفاءة الأسطول، واحصل على خدمة ومستلزمات ورؤية أداء ضمن نموذج واحد واضح.'),'managed-print-services','is-featured'],
['fa-file-lines',smt_t('Document Management','إدارة المستندات'),smt_t('Digitize documents, automate approvals and make business information easier to access and govern.','حوّل المستندات إلى معلومات متاحة وآمنة، وسرّع الموافقات، وقلّل الاعتماد على الورق.'),'document-management',''],
['fa-server',smt_t('IT Infrastructure','البنية التحتية'),smt_t('Create resilient networks, servers, endpoints and data environments ready for growth.','ابنِ بنية تحتية مستقرة وآمنة تستوعب توسع أعمالك وتدعم استمراريتها.'),'it-infrastructure',''],
['fa-cloud',smt_t('Cloud & Microsoft','السحابة ومايكروسوفت'),smt_t('Modernize collaboration, identity, productivity and business continuity.','طوّر التعاون والإنتاجية والهوية الرقمية واستمرارية الأعمال بحلول سحابية مرنة.'),'cloud-microsoft-solutions',''],
['fa-shield-halved',smt_t('Cybersecurity','الأمن السيبراني'),smt_t('Protect users, devices, networks, documents and critical operations.','احمِ المستخدمين والأجهزة والبيانات والعمليات الحيوية بمنظومة أمن مترابطة.'),'cybersecurity',''],
['fa-screwdriver-wrench',smt_t('Annual Maintenance','الصيانة والدعم الفني'),smt_t('Keep office and IT assets reliable with planned, responsive lifecycle support.','حافظ على جاهزية أصولك التقنية بدعم مخطط وسريع يقلل الأعطال ويحسن العمر التشغيلي.'),'annual-maintenance','']
]; foreach($s as $x): ?>
<article class="service-card showcase-service-card <?php echo esc_attr($x[4]); ?> reveal"><span class="service-icon"><i class="fa-solid <?php echo esc_attr($x[0]); ?>"></i></span>
<div class="service-card-copy"><h3><?php echo esc_html($x[1]); ?></h3><p><?php echo esc_html($x[2]); ?></p></div>
<a href="<?php echo esc_url(smt_page_url($x[3])); ?>"><?php echo esc_html(smt_t('Explore solution','اكتشف الحل')); ?> <i class="fa-solid fa-arrow-right"></i></a></article><?php endforeach; ?>
</div><div class="section-footer-link reveal"><a class="btn btn-primary" href="<?php echo esc_url(smt_page_url('solutions')); ?>"><?php echo esc_html(smt_home_value('solutions_cta_label')); ?></a></div></div></section>
