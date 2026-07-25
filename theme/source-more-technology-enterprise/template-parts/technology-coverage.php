<section class="section technology-coverage" id="technology-coverage">
  <div class="container">
    <div class="section-heading centered reveal">
      <div><span class="section-kicker"><?php echo esc_html(smt_home_value('technology_kicker')); ?></span><h2><?php echo esc_html(smt_home_value('technology_title')); ?></h2></div>
      <p><?php echo esc_html(smt_home_value('technology_description')); ?></p>
    </div>
    <div class="technology-grid">
      <?php foreach([
        ['fa-print',smt_t('Printers & Multifunction Devices','الطابعات والأجهزة متعددة الوظائف'),smt_t('Office, departmental and enterprise output environments.','حلول طباعة تناسب بيئات العمل المكتبية والإدارية والمؤسسية.')],
        ['fa-scanner',smt_t('Scanning & Capture','المسح والالتقاط'),smt_t('Digitization, capture and document intake workflows.','رقمنة المستندات وتسريع إدخالها إلى مسارات العمل.')],
        ['fa-server',smt_t('Servers & Storage','الخوادم والتخزين'),smt_t('Reliable compute, storage and business continuity foundations.','قدرات حوسبة وتخزين موثوقة تدعم استمرارية الأعمال.')],
        ['fa-network-wired',smt_t('Networks & Connectivity','الشبكات والاتصال'),smt_t('Secure wired, wireless and multi-site connectivity.','اتصال آمن ومستقر داخل المواقع وبينها.')],
        ['fa-shield-halved',smt_t('Security Solutions','حلول الأمن السيبراني'),smt_t('Identity, endpoint, network and data protection.','حماية الهوية والأجهزة والشبكات والبيانات ضمن منظومة واحدة.')],
        ['fa-cloud',smt_t('Microsoft & Cloud','مايكروسوفت والسحابة'),smt_t('Productivity, collaboration, identity and cloud services.','حلول للإنتاجية والتعاون والهوية والخدمات السحابية.')],
        ['fa-diagram-project',smt_t('Workflow Software','برمجيات سير العمل'),smt_t('Document management, print control and automation.','إدارة المستندات والتحكم في الطباعة وأتمتة الإجراءات.')],
        ['fa-headset',smt_t('Service & Lifecycle Support','الخدمة ودعم دورة الحياة'),smt_t('Maintenance, monitoring, supplies and continuous improvement.','صيانة ومراقبة ومستلزمات وتحسين مستمر للأداء.')]
      ] as $item): ?>
        <article class="technology-card reveal"><span><i class="fa-solid <?php echo esc_attr($item[0]); ?>"></i></span><h3><?php echo esc_html($item[1]); ?></h3><p><?php echo esc_html($item[2]); ?></p></article>
      <?php endforeach; ?>
    </div>
    <div class="technology-cta reveal"><div><strong><?php echo esc_html(smt_home_value('technology_cta_title')); ?></strong><span><?php echo esc_html(smt_home_value('technology_cta_text')); ?></span></div><a class="btn btn-primary" href="<?php echo esc_url(get_post_type_archive_link('smt_product') ?: smt_page_url('products')); ?>"><?php echo esc_html(smt_home_value('technology_cta_label')); ?></a></div>
  </div>
</section>
