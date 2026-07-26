<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Bilingual enterprise solution catalogue.
 * Slugs remain stable while visible copy follows the active Polylang language.
 */
function smt_solution_catalog() {
  return array(
    'managed-print-services' => array(
      'icon'=>'fa-print',
      'title'=>smt_t('Managed Print Services','خدمات الطباعة المُدارة'),
      'tagline'=>smt_t('Control print costs. Improve uptime. Simplify fleet management.','حوّل بيئة الطباعة من تكلفة غير واضحة إلى خدمة أكثر كفاءة وتحكمًا.'),
      'intro'=>smt_t(
        'A data-led service that assesses, optimizes, monitors, and supports your complete print environment under clear service levels and predictable costs.',
        'خدمة متكاملة تعتمد على البيانات لتقييم أسطول الطباعة وتحسينه ومراقبته ودعمه، ضمن مستويات خدمة واضحة وتكلفة تشغيل يمكن توقعها.'
      ),
      'challenges'=>array(
        smt_t('Uncontrolled print expenditure and limited cost visibility','تكاليف طباعة مرتفعة دون رؤية واضحة لمصادر الإنفاق'),
        smt_t('Mixed device fleets with inconsistent service and supplies','أجهزة متعددة وموردون مختلفون ومستوى خدمة غير ثابت'),
        smt_t('Frequent downtime and reactive support','أعطال متكررة واعتماد على الصيانة بعد حدوث المشكلة'),
        smt_t('Security and sustainability risks across the print environment','مخاطر أمنية وهدر في الورق والطاقة والمستهلكات')
      ),
      'features'=>array(
        smt_t('Fleet assessment and right-sizing','تقييم أسطول الطباعة وإعادة توزيع الأجهزة بالحجم المناسب'),
        smt_t('Remote monitoring and meter collection','مراقبة الأجهزة وقراءات العدادات عن بُعد'),
        smt_t('Automated toner replenishment','إدارة وتوريد الأحبار تلقائيًا قبل نفادها'),
        smt_t('Preventive maintenance and SLA management','صيانة وقائية وإدارة مستويات الخدمة'),
        smt_t('Secure print and user policies','طباعة آمنة وسياسات استخدام للمستخدمين'),
        smt_t('Usage reporting and continuous optimization','تقارير استخدام وتحسين مستمر للأداء والتكلفة')
      ),
      'benefits'=>array(
        smt_t('Lower total printing cost','خفض التكلفة الكلية للطباعة'),
        smt_t('Higher device availability','رفع جاهزية الأجهزة وتقليل التوقف'),
        smt_t('Predictable operating expenditure','تكلفة تشغيل أكثر وضوحًا وقابلية للتوقع'),
        smt_t('Reduced administrative workload','تقليل العبء الإداري على فرق العمل'),
        smt_t('Improved print security','تعزيز أمان المستندات والطباعة'),
        smt_t('Less waste and lower environmental impact','تقليل الهدر والأثر البيئي')
      )
    ),
    'enterprise-printing' => array(
      'icon'=>'fa-building',
      'title'=>smt_t('Enterprise Printing Solutions','حلول الطباعة المؤسسية'),
      'tagline'=>smt_t('Reliable output for demanding business environments.','أداء ثابت وجودة موثوقة لبيئات العمل ذات المتطلبات العالية.'),
      'intro'=>smt_t(
        'Business printers, multifunction devices, production systems, and secure print technologies selected around workload, workflow, and lifecycle requirements.',
        'طابعات وماكينات متعددة الوظائف وأنظمة إنتاج وحلول طباعة آمنة، يتم اختيارها وفق حجم العمل وسير العمليات والتكلفة طوال دورة حياة الجهاز.'
      ),
      'challenges'=>array(
        smt_t('Devices that cannot handle actual business workloads','أجهزة لا تتحمل حجم العمل الفعلي داخل المؤسسة'),
        smt_t('High consumable and maintenance costs','ارتفاع تكلفة الأحبار والصيانة مع مرور الوقت'),
        smt_t('Inconsistent output quality','تفاوت جودة المخرجات بين الأقسام والمواقع'),
        smt_t('Limited security, mobility, and finishing capabilities','ضعف إمكانات الأمان والطباعة المتنقلة والتشطيب')
      ),
      'features'=>array(
        smt_t('Office printers and multifunction devices','طابعات مكتبية وماكينات متعددة الوظائف'),
        smt_t('Departmental and production printing','حلول طباعة للإدارات والإنتاج'),
        smt_t('Secure and mobile printing','طباعة آمنة ومتنقلة'),
        smt_t('Scanning and document capture','مسح ضوئي والتقاط المستندات'),
        smt_t('Finishing and workflow options','خيارات تشطيب وربط بسير العمل'),
        smt_t('Deployment, configuration, and training','توريد وتركيب وإعداد وتدريب المستخدمين')
      ),
      'benefits'=>array(
        smt_t('Reliable business continuity','استمرارية تشغيل أكثر اعتمادية'),
        smt_t('Consistent professional output','جودة مخرجات احترافية ومتسقة'),
        smt_t('Improved user productivity','رفع إنتاجية المستخدمين'),
        smt_t('Lower lifecycle cost','خفض التكلفة طوال دورة الحياة'),
        smt_t('Stronger document security','حماية أقوى للمستندات'),
        smt_t('Scalable fleet architecture','أسطول قابل للتوسع مع نمو المؤسسة')
      )
    ),
    'document-management' => array(
      'icon'=>'fa-file-lines',
      'title'=>smt_t('Document Management','إدارة المستندات وسير العمل'),
      'tagline'=>smt_t('Turn documents into controlled digital workflows.','حوّل المستندات من عبء يومي إلى تدفق معلومات أسرع وأكثر تحكمًا.'),
      'intro'=>smt_t(
        'Capture, classify, store, retrieve, route, and protect business information through structured document management and workflow automation.',
        'حلول لالتقاط المستندات وتصنيفها وحفظها واسترجاعها وتوجيهها وحمايتها ضمن نظام رقمي منظم وأتمتة واضحة لسير العمل.'
      ),
      'challenges'=>array(
        smt_t('Slow paper-based approvals and manual filing','موافقات ورقية بطيئة وأرشفة يدوية تستنزف الوقت'),
        smt_t('Difficulty finding documents and controlling versions','صعوبة الوصول إلى المستند الصحيح والتحكم في الإصدارات'),
        smt_t('Compliance and retention risks','مخاطر مرتبطة بالاحتفاظ بالمستندات والامتثال'),
        smt_t('Disconnected information across departments','معلومات موزعة بين الإدارات دون رؤية موحدة')
      ),
      'features'=>array(
        smt_t('Document capture and OCR','التقاط المستندات والتعرف الضوئي على النصوص OCR'),
        smt_t('Digital archiving and indexing','أرشفة رقمية وفهرسة ذكية'),
        smt_t('Workflow and approval automation','أتمتة الإجراءات والموافقات'),
        smt_t('Version and access control','إدارة الإصدارات وصلاحيات الوصول'),
        smt_t('Retention and audit trails','سياسات احتفاظ وسجلات تدقيق'),
        smt_t('Integration with business applications','تكامل مع أنظمة وتطبيقات الأعمال')
      ),
      'benefits'=>array(
        smt_t('Faster access to information','وصول أسرع للمعلومات'),
        smt_t('Shorter approval cycles','تقليل زمن الموافقات'),
        smt_t('Reduced paper and storage cost','خفض تكلفة الورق والتخزين'),
        smt_t('Improved compliance','تحسين الامتثال والحوكمة'),
        smt_t('Better collaboration','تعاون أفضل بين الإدارات'),
        smt_t('Secure business continuity','استمرارية وحماية للمعلومات')
      )
    ),
    'it-infrastructure' => array(
      'icon'=>'fa-server',
      'title'=>smt_t('IT Infrastructure','البنية التحتية لتكنولوجيا المعلومات'),
      'tagline'=>smt_t('A secure and scalable foundation for business growth.','أساس تقني قوي وآمن يدعم أداء مؤسستك ونموها.'),
      'intro'=>smt_t(
        'Design, supply, implementation, and support for servers, storage, networks, endpoints, virtualization, backup, and business continuity.',
        'تصميم وتوريد وتنفيذ ودعم الخوادم والتخزين والشبكات وأجهزة المستخدمين والافتراضية والنسخ الاحتياطي واستمرارية الأعمال.'
      ),
      'challenges'=>array(
        smt_t('Aging infrastructure and performance bottlenecks','بنية تحتية قديمة وبطء يؤثر على فرق العمل'),
        smt_t('Unplanned downtime and weak resilience','توقفات غير مخططة وضعف في القدرة على التعافي'),
        smt_t('Capacity limitations and fragmented vendors','قيود في السعة وتعدد الموردين دون إدارة موحدة'),
        smt_t('Inadequate backup and recovery readiness','نسخ احتياطي غير كافٍ وخطط تعافٍ غير جاهزة')
      ),
      'features'=>array(
        smt_t('Servers, storage, and virtualization','خوادم وتخزين وحلول افتراضية'),
        smt_t('LAN, WAN, wireless, and structured cabling','شبكات LAN وWAN ولاسلكية وكابلات منظمة'),
        smt_t('Endpoint and workplace infrastructure','أجهزة المستخدمين وبنية بيئة العمل'),
        smt_t('Backup and disaster recovery','نسخ احتياطي وتعافٍ من الكوارث'),
        smt_t('Monitoring and capacity planning','مراقبة وتخطيط للسعة'),
        smt_t('Deployment, migration, and support','تنفيذ وترحيل ودعم فني')
      ),
      'benefits'=>array(
        smt_t('Higher availability','جاهزية أعلى للأنظمة'),
        smt_t('Improved performance','أداء أسرع وأكثر استقرارًا'),
        smt_t('Scalable capacity','سعة قابلة للتوسع'),
        smt_t('Reduced operational risk','خفض مخاطر التشغيل'),
        smt_t('Simpler management','إدارة أبسط للبنية التقنية'),
        smt_t('Stronger business continuity','استمرارية أعمال أقوى')
      )
    ),
    'cloud-microsoft-solutions' => array(
      'icon'=>'fa-cloud',
      'title'=>smt_t('Cloud & Microsoft Solutions','حلول السحابة ومايكروسوفت'),
      'tagline'=>smt_t('Enable secure work, collaboration, and continuity from anywhere.','مكّن فرقك من العمل والتعاون بأمان من أي مكان.'),
      'intro'=>smt_t(
        'Microsoft 365, Azure, identity, migration, collaboration, backup, and cloud management services designed for modern organizations.',
        'حلول Microsoft 365 وAzure والهوية الرقمية والترحيل والتعاون والنسخ الاحتياطي وإدارة السحابة للمؤسسات الحديثة.'
      ),
      'challenges'=>array(
        smt_t('Legacy email and collaboration platforms','أنظمة بريد وتعاون قديمة تعيق الإنتاجية'),
        smt_t('Limited remote-work capability','قدرات محدودة للعمل عن بُعد'),
        smt_t('Identity and access complexity','تعقيد في إدارة الهوية والصلاحيات'),
        smt_t('Unplanned cloud cost and governance gaps','تكاليف سحابية غير منضبطة وفجوات في الحوكمة')
      ),
      'features'=>array(
        smt_t('Microsoft 365 licensing and deployment','ترخيص وتنفيذ Microsoft 365'),
        smt_t('Azure infrastructure and services','بنية وخدمات Microsoft Azure'),
        smt_t('Email and tenant migration','ترحيل البريد والـTenant'),
        smt_t('Teams and SharePoint collaboration','تعاون عبر Teams وSharePoint'),
        smt_t('Identity and access management','إدارة الهوية والوصول'),
        smt_t('Cloud backup, security, and governance','نسخ احتياطي وأمان وحوكمة للسحابة')
      ),
      'benefits'=>array(
        smt_t('Productive hybrid work','عمل هجين أكثر إنتاجية'),
        smt_t('Secure collaboration','تعاون آمن بين الفرق'),
        smt_t('Flexible capacity','مرونة في السعة والموارد'),
        smt_t('Lower infrastructure burden','تقليل عبء إدارة البنية المحلية'),
        smt_t('Improved continuity','استمرارية أفضل للخدمات'),
        smt_t('Centralized identity and management','إدارة موحدة للهوية والأنظمة')
      )
    ),
    'cybersecurity' => array(
      'icon'=>'fa-shield-halved',
      'title'=>smt_t('Cybersecurity','الأمن السيبراني'),
      'tagline'=>smt_t('Protect users, identities, endpoints, networks, and data.','احمِ المستخدمين والهوية والأجهزة والشبكات والبيانات.'),
      'intro'=>smt_t(
        'Layered security solutions that reduce exposure, strengthen resilience, and help organizations manage cyber risk across their technology environment.',
        'حلول حماية متعددة الطبقات تقلل التعرض للهجمات، وتعزز القدرة على الاستجابة والتعافي، وتمنح المؤسسة رؤية أوضح للمخاطر السيبرانية.'
      ),
      'challenges'=>array(
        smt_t('Growing ransomware, phishing, and account compromise','تزايد هجمات الفدية والتصيد واختراق الحسابات'),
        smt_t('Unmanaged endpoints and weak visibility','أجهزة غير مُدارة وضعف في الرؤية الأمنية'),
        smt_t('Legacy firewalls and inconsistent security controls','جدران حماية قديمة وضوابط أمنية غير متسقة'),
        smt_t('Limited recovery and incident preparedness','ضعف الاستعداد للحوادث والتعافي')
      ),
      'features'=>array(
        smt_t('Endpoint detection and protection','حماية وكشف متقدم لأجهزة المستخدمين'),
        smt_t('Network security and next-generation firewalls','أمن الشبكات وجدران الحماية الحديثة'),
        smt_t('Email and collaboration security','حماية البريد ومنصات التعاون'),
        smt_t('Identity and privileged access controls','إدارة الهوية والوصول المميز'),
        smt_t('Vulnerability assessment and hardening','تقييم الثغرات وتقوية الأنظمة'),
        smt_t('Security awareness, backup, and recovery','توعية أمنية ونسخ احتياطي وتعافٍ')
      ),
      'benefits'=>array(
        smt_t('Reduced attack exposure','خفض فرص التعرض للهجمات'),
        smt_t('Faster threat detection','اكتشاف أسرع للتهديدات'),
        smt_t('Protected business data','حماية بيانات الأعمال'),
        smt_t('Improved compliance posture','تعزيز الامتثال الأمني'),
        smt_t('Greater operational resilience','مرونة تشغيلية أعلى'),
        smt_t('Clearer security visibility','رؤية أمنية أوضح للإدارة')
      )
    ),
    'it-consulting' => array(
      'icon'=>'fa-comments',
      'title'=>smt_t('IT Consulting','استشارات تكنولوجيا المعلومات'),
      'tagline'=>smt_t('Make clearer technology decisions with a practical business-first roadmap.','اتخذ قرارات تقنية أوضح من خلال خارطة طريق عملية تبدأ من أهداف العمل.'),
      'intro'=>smt_t(
        'Independent advisory support that aligns technology priorities, budgets, governance, sourcing, and implementation decisions with measurable business outcomes.',
        'دعم استشاري مستقل يربط أولويات التكنولوجيا والميزانيات والحوكمة واختيار الموردين وقرارات التنفيذ بنتائج أعمال واضحة وقابلة للقياس.'
      ),
      'challenges'=>array(
        smt_t('Technology investments without a clear business case','استثمارات تقنية دون مبرر أعمال واضح أو عائد يمكن قياسه'),
        smt_t('Fragmented vendors, contracts, and responsibilities','تعدد الموردين والعقود والمسؤوليات دون إدارة موحدة'),
        smt_t('Unclear priorities and competing project demands','أولويات غير واضحة وتنافس بين مشروعات متعددة على الموارد'),
        smt_t('Limited governance, documentation, and decision visibility','ضعف الحوكمة والتوثيق ووضوح القرارات أمام الإدارة')
      ),
      'features'=>array(
        smt_t('Technology strategy and roadmap development','إعداد استراتيجية وخارطة طريق للتكنولوجيا'),
        smt_t('Current-state assessment and gap analysis','تقييم الوضع الحالي وتحليل الفجوات'),
        smt_t('Solution architecture and vendor evaluation','تصميم الحلول وتقييم الموردين والعروض'),
        smt_t('Budget, TCO, and business-case analysis','تحليل الميزانية والتكلفة الكلية ومبررات الاستثمار'),
        smt_t('Governance, policies, and implementation planning','الحوكمة والسياسات وتخطيط التنفيذ'),
        smt_t('Project oversight and executive reporting','متابعة المشروعات وتقارير الإدارة التنفيذية')
      ),
      'benefits'=>array(
        smt_t('Better investment decisions','قرارات استثمارية أفضل وأكثر وضوحًا'),
        smt_t('Aligned business and technology priorities','مواءمة أهداف الأعمال مع الأولويات التقنية'),
        smt_t('Reduced project and vendor risk','خفض مخاطر المشروعات والموردين'),
        smt_t('Clearer budgets and lifecycle costs','وضوح أكبر للميزانيات وتكلفة دورة الحياة'),
        smt_t('Stronger governance and accountability','حوكمة ومسؤوليات أكثر قوة'),
        smt_t('A practical, executable roadmap','خارطة طريق عملية وقابلة للتنفيذ')
      )
    ),
    'digital-transformation' => array(
      'icon'=>'fa-arrow-trend-up',
      'title'=>smt_t('Digital Transformation','التحول الرقمي'),
      'tagline'=>smt_t('Redesign work, information, and customer journeys for faster, smarter growth.','أعد تصميم العمل والمعلومات وتجربة العميل لتحقيق نمو أسرع وأكثر ذكاءً.'),
      'intro'=>smt_t(
        'A structured transformation program that combines process redesign, document digitization, automation, cloud, data, security, and change management around clear business priorities.',
        'برنامج تحول منظم يجمع إعادة تصميم العمليات ورقمنة المستندات والأتمتة والسحابة والبيانات والأمان وإدارة التغيير حول أولويات أعمال واضحة.'
      ),
      'challenges'=>array(
        smt_t('Manual processes that slow service and decision-making','عمليات يدوية تبطئ الخدمة واتخاذ القرار'),
        smt_t('Disconnected systems and duplicated information','أنظمة منفصلة ومعلومات مكررة بين الإدارات'),
        smt_t('Limited visibility into performance and customer journeys','ضعف الرؤية حول الأداء وتجربة العميل'),
        smt_t('Transformation initiatives without adoption or measurable value','مبادرات تحول لا تحقق تبنيًا فعليًا أو قيمة قابلة للقياس')
      ),
      'features'=>array(
        smt_t('Digital maturity and opportunity assessment','تقييم النضج الرقمي وفرص التحسين'),
        smt_t('Process redesign and workflow automation','إعادة تصميم العمليات وأتمتة سير العمل'),
        smt_t('Document digitization and information governance','رقمنة المستندات وحوكمة المعلومات'),
        smt_t('Cloud, collaboration, and modern workplace enablement','تمكين السحابة والتعاون وبيئة العمل الحديثة'),
        smt_t('Data, dashboards, and performance visibility','البيانات ولوحات المتابعة ووضوح الأداء'),
        smt_t('Change management, adoption, and continuous improvement','إدارة التغيير والتبني والتحسين المستمر')
      ),
      'benefits'=>array(
        smt_t('Faster and simpler operations','عمليات أسرع وأبسط'),
        smt_t('Improved customer and employee experience','تجربة أفضل للعملاء والموظفين'),
        smt_t('Better information and decision visibility','رؤية أوضح للمعلومات والقرارات'),
        smt_t('Lower process cost and manual effort','خفض تكلفة العمليات والمجهود اليدوي'),
        smt_t('Stronger agility and scalability','مرونة وقابلية توسع أكبر'),
        smt_t('Measurable transformation outcomes','نتائج تحول يمكن قياسها')
      )
    ),
    'office-automation' => array(
      'icon'=>'fa-gears',
      'title'=>smt_t('Office Automation','أتمتة بيئة العمل'),
      'tagline'=>smt_t('Remove repetitive work and streamline daily operations.','قلل العمل المتكرر وحوّل الإجراءات اليومية إلى عمليات أسرع وأكثر انسيابية.'),
      'intro'=>smt_t(
        'Integrated automation for forms, routing, approvals, scanning, communications, and repetitive office processes.',
        'أتمتة متكاملة للنماذج والتوجيه والموافقات والمسح الضوئي والاتصالات والمهام المكتبية المتكررة.'
      ),
      'challenges'=>array(
        smt_t('Manual repetitive tasks and duplicate data entry','مهام يدوية متكررة وإدخال بيانات أكثر من مرة'),
        smt_t('Slow internal approvals','موافقات داخلية بطيئة'),
        smt_t('Paper-heavy processes','إجراءات تعتمد بشكل كبير على الورق'),
        smt_t('Disconnected tools and limited process visibility','أدوات منفصلة وضعف في متابعة مراحل العمل')
      ),
      'features'=>array(
        smt_t('Digital forms and workflow automation','نماذج رقمية وأتمتة سير العمل'),
        smt_t('Scanning and intelligent capture','مسح ضوئي والتقاط ذكي للبيانات'),
        smt_t('Electronic approvals and notifications','موافقات وتنبيهات إلكترونية'),
        smt_t('Process integration and routing','ربط وتوجيه العمليات بين الأنظمة'),
        smt_t('Reporting and dashboards','تقارير ولوحات متابعة'),
        smt_t('User training and continuous improvement','تدريب المستخدمين وتحسين مستمر')
      ),
      'benefits'=>array(
        smt_t('Faster process completion','إنجاز أسرع للإجراءات'),
        smt_t('Fewer manual errors','أخطاء يدوية أقل'),
        smt_t('Higher employee productivity','إنتاجية أعلى للموظفين'),
        smt_t('Improved visibility','رؤية أوضح لمراحل العمل'),
        smt_t('Reduced operating cost','خفض تكلفة التشغيل'),
        smt_t('Consistent customer service','خدمة أكثر اتساقًا للعملاء')
      )
    ),
    'annual-maintenance' => array(
      'icon'=>'fa-screwdriver-wrench',
      'title'=>smt_t('Annual Maintenance Contracts','عقود الصيانة والدعم السنوي'),
      'tagline'=>smt_t('Planned support that protects uptime and equipment value.','دعم مخطط يحافظ على استمرارية التشغيل وقيمة أصولك التقنية.'),
      'intro'=>smt_t(
        'Preventive and corrective maintenance programs with agreed service levels, qualified engineers, reporting, and optional parts and consumables coverage.',
        'برامج صيانة وقائية وتصحيحية بمستويات خدمة متفق عليها، ومهندسين مؤهلين، وتقارير واضحة، وخيارات لتغطية قطع الغيار والمستهلكات.'
      ),
      'challenges'=>array(
        smt_t('Unexpected failures and operational disruption','أعطال مفاجئة وتعطل في العمليات'),
        smt_t('Inconsistent repair quality and response times','تفاوت جودة الإصلاح وزمن الاستجابة'),
        smt_t('No maintenance history or lifecycle planning','غياب سجل صيانة وخطة لدورة حياة الأصول'),
        smt_t('Uncontrolled spare parts and service costs','تكاليف غير منضبطة لقطع الغيار والخدمة')
      ),
      'features'=>array(
        smt_t('Preventive maintenance schedules','جداول صيانة وقائية'),
        smt_t('Corrective on-site support','دعم وإصلاح بالموقع'),
        smt_t('SLA and escalation management','إدارة مستويات الخدمة والتصعيد'),
        smt_t('Remote diagnosis and help desk','تشخيص عن بُعد ومكتب مساعدة'),
        smt_t('Parts and consumables options','خيارات لتغطية قطع الغيار والمستهلكات'),
        smt_t('Service reporting and asset history','تقارير خدمة وسجل كامل للأصول')
      ),
      'benefits'=>array(
        smt_t('Higher equipment availability','جاهزية أعلى للمعدات'),
        smt_t('Predictable support cost','تكلفة دعم يمكن توقعها'),
        smt_t('Longer asset life','إطالة العمر التشغيلي للأصول'),
        smt_t('Faster issue resolution','حل أسرع للمشكلات'),
        smt_t('Reduced operational disruption','تقليل تعطل الأعمال'),
        smt_t('Clear service accountability','مسؤولية واضحة عن مستوى الخدمة')
      )
    ),
  );
}

function smt_render_solution_page( $slug ) {
  $catalog = smt_solution_catalog();
  if ( empty( $catalog[$slug] ) ) return;
  $s = $catalog[$slug];
  ?>
  <main class="solution-detail-page">
    <section class="ui-page-hero solution-detail-hero"><div class="container solution-hero-grid"><div>
      <span class="eyebrow"><?php echo esc_html(smt_t('Source More Technology','سورس مور تكنولوجي')); ?></span><h1><?php echo esc_html($s['title']); ?></h1><p><?php echo esc_html($s['tagline']); ?></p>
      <div class="hero-actions"><a class="btn btn-gold" href="<?php echo esc_url(smt_page_url('contact')); ?>"><?php echo esc_html(smt_t('Request a Consultation','اطلب استشارة')); ?></a><a class="btn btn-outline" href="<?php echo esc_url(smt_page_url('solutions')); ?>"><?php echo esc_html(smt_t('View All Solutions','جميع الحلول')); ?></a></div>
    </div><div class="solution-hero-symbol"><i class="fa-solid <?php echo esc_attr($s['icon']); ?>"></i></div></div></section>
    <section class="section"><div class="container solution-intro-grid"><div><?php smt_section_heading(array('kicker'=>smt_t('Business-focused technology','تكنولوجيا تخدم أهداف الأعمال'),'title'=>smt_t('A practical solution built around your operational needs','حل عملي مصمم حول احتياجات التشغيل الفعلية'),'text'=>$s['intro'])); ?></div><aside class="solution-consult-card"><i class="fa-solid fa-comments"></i><h3><?php echo esc_html(smt_t('Start with an expert assessment','ابدأ بتقييم متخصص')); ?></h3><p><?php echo esc_html(smt_t('We review your environment, identify risks and opportunities, and recommend a clear implementation roadmap.','نراجع بيئة العمل ونحدد المخاطر وفرص التحسين ثم نضع خارطة طريق واضحة وقابلة للتنفيذ.')); ?></p><a href="<?php echo esc_url(smt_page_url('contact')); ?>"><?php echo esc_html(smt_t('Talk to our experts','تحدث مع خبرائنا')); ?> <i class="fa-solid fa-arrow-right"></i></a></aside></div></section>
    <section class="section ui-surface-section"><div class="container"><?php smt_section_heading(array('kicker'=>smt_t('The challenge','التحديات'),'title'=>smt_t('Common issues we help organizations solve','تحديات نساعد المؤسسات على تحويلها إلى فرص تحسين'),'text'=>smt_t('Our approach begins with the business problem, not a product catalogue.','نبدأ من المشكلة ونتيجة الأعمال المطلوبة، وليس من قائمة منتجات جاهزة.'))); ?><div class="solution-challenge-grid"><?php foreach($s['challenges'] as $item): ?><article class="solution-check-card reveal"><i class="fa-solid fa-triangle-exclamation"></i><p><?php echo esc_html($item); ?></p></article><?php endforeach; ?></div></div></section>
    <section class="section"><div class="container"><?php smt_section_heading(array('kicker'=>smt_t('Our solution','الحل'),'title'=>smt_t('Capabilities included in this service','قدرات متكاملة يتم تصميمها وفق احتياجات مؤسستك'),'text'=>smt_t('The final scope is tailored to your environment, priorities, and budget.','يتم تحديد النطاق النهائي وفق بيئة العمل والأولويات والميزانية.'))); ?><div class="solution-feature-grid"><?php foreach($s['features'] as $item): ?><article class="solution-feature-card reveal"><span><i class="fa-solid fa-check"></i></span><h3><?php echo esc_html($item); ?></h3></article><?php endforeach; ?></div></div></section>
    <section class="section ui-dark-section"><div class="container"><?php smt_section_heading(array('kicker'=>smt_t('Business outcomes','نتائج الأعمال'),'title'=>smt_t('Value you can measure','قيمة تظهر في الأداء والتكلفة والاستمرارية'),'text'=>smt_t('We focus on operational, financial, security, and user-experience outcomes.','نركز على نتائج تشغيلية ومالية وأمنية وتحسين تجربة المستخدم.'),'alignment'=>'center','theme'=>'dark')); ?><div class="solution-benefit-grid"><?php foreach($s['benefits'] as $item): ?><article><i class="fa-solid fa-circle-check"></i><span><?php echo esc_html($item); ?></span></article><?php endforeach; ?></div></div></section>
    <section class="section solution-process-section"><div class="container"><?php smt_section_heading(array('kicker'=>smt_t('How we deliver','منهجية التنفيذ'),'title'=>smt_t('A controlled path from assessment to optimization','مسار واضح من التقييم إلى التشغيل والتحسين'),'alignment'=>'center')); ?><ol class="ui-process-grid solution-process"><li><span>01</span><h3><?php echo esc_html(smt_t('Assess','نقيّم')); ?></h3><p><?php echo esc_html(smt_t('Understand the current environment, costs, risks, and requirements.','نفهم البيئة الحالية والتكلفة والمخاطر والمتطلبات.')); ?></p></li><li><span>02</span><h3><?php echo esc_html(smt_t('Design','نصمم')); ?></h3><p><?php echo esc_html(smt_t('Create the right technical and commercial solution.','نصمم الحل الفني والتجاري الأنسب.')); ?></p></li><li><span>03</span><h3><?php echo esc_html(smt_t('Implement','ننفذ')); ?></h3><p><?php echo esc_html(smt_t('Deploy with clear ownership, testing, and user readiness.','ننشر الحل بمسؤوليات واضحة واختبارات وتجهيز للمستخدمين.')); ?></p></li><li><span>04</span><h3><?php echo esc_html(smt_t('Support & Optimize','ندعم ونطوّر')); ?></h3><p><?php echo esc_html(smt_t('Measure results, maintain performance, and improve continuously.','نقيس النتائج ونحافظ على الأداء ونطوّر الحل باستمرار.')); ?></p></li></ol></div></section>
    <section class="ui-cta-section"><div class="container ui-cta-inner"><div><span class="section-kicker light"><?php echo esc_html(smt_t('Speak with our team','تحدث مع فريقنا')); ?></span><h2><?php echo esc_html(smt_t('Ready to improve your technology environment?','جاهز لتحويل التكنولوجيا إلى قيمة حقيقية لمؤسستك؟')); ?></h2><p><?php echo esc_html(smt_t('Discuss your priorities and receive a practical recommendation for the next step.','ناقش أولوياتك معنا واحصل على توصية عملية للخطوة التالية.')); ?></p></div><div class="ui-cta-actions"><a class="btn btn-gold" href="<?php echo esc_url(smt_page_url('contact')); ?>"><?php echo esc_html(smt_t('Book a Consultation','احجز استشارة')); ?></a></div></div></section>
  </main>
  <?php
}
