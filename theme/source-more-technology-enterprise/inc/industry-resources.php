<?php
if (!defined('ABSPATH')) exit;

/**
 * Bilingual industries and resources catalogue for Theme v7.6.0.
 * Copy follows the active Polylang language through smt_t().
 */
function smt_industry_catalog(): array {
    return [
        'banking' => [
            'icon' => 'fa-building-columns',
            'title' => smt_t('Banking & Financial Services','البنوك والخدمات المالية'),
            'tagline' => smt_t('Secure, controlled technology for regulated, high-volume financial operations.','تكنولوجيا آمنة ومحكومة تدعم العمليات المالية كثيفة الاستخدام والمتطلبات الرقابية.'),
            'intro' => smt_t(
                'We help banks and financial institutions protect sensitive information, standardize branch operations, improve service availability, and control document and infrastructure costs across distributed environments.',
                'نساعد البنوك والمؤسسات المالية على حماية المعلومات الحساسة، وتوحيد عمليات الفروع، ورفع جاهزية الخدمات، والتحكم في تكلفة المستندات والبنية التحتية عبر بيئات عمل موزعة.'
            ),
            'priorities' => [
                smt_t('Regulatory compliance and auditable document controls','الامتثال والرقابة القابلة للتدقيق على المستندات'),
                smt_t('Secure printing, scanning, and customer information handling','طباعة ومسح ضوئي آمنان وإدارة محكمة لبيانات العملاء'),
                smt_t('Reliable branch and head-office infrastructure','بنية تحتية موثوقة للفروع والمقرات الرئيسية'),
                smt_t('Cost visibility across devices, sites, and users','رؤية أوضح للتكلفة حسب الأجهزة والمواقع والمستخدمين'),
            ],
            'solutions' => [
                ['fa-print',smt_t('Secure Managed Print','طباعة مُدارة وآمنة'),smt_t('Authentication, usage policy, reporting, and controlled output across branches.','مصادقة وسياسات استخدام وتقارير وتحكم في المخرجات عبر الفروع.')],
                ['fa-file-shield',smt_t('Document Governance','حوكمة المستندات'),smt_t('Capture, approvals, retention, access control, and audit trails.','التقاط وموافقات واحتفاظ وصلاحيات وصول وسجلات تدقيق.')],
                ['fa-network-wired',smt_t('Branch Infrastructure','بنية الفروع'),smt_t('Resilient connectivity, endpoints, backup, and centralized support.','اتصال مرن وأجهزة مستخدمين ونسخ احتياطي ودعم مركزي.')],
                ['fa-shield-halved',smt_t('Cybersecurity Controls','ضوابط الأمن السيبراني'),smt_t('Layered protection for identities, devices, data, and business continuity.','حماية متعددة الطبقات للهويات والأجهزة والبيانات واستمرارية الأعمال.')],
            ],
            'outcomes' => [smt_t('Stronger compliance','امتثال أقوى'),smt_t('Lower operating cost','تكلفة تشغيل أقل'),smt_t('Higher service availability','جاهزية أعلى للخدمات'),smt_t('Better customer experience','تجربة أفضل للعملاء')],
        ],
        'government' => [
            'icon' => 'fa-landmark',
            'title' => smt_t('Government & Public Sector','الجهات الحكومية والقطاع العام'),
            'tagline' => smt_t('Reliable technology that improves public-service delivery and administrative efficiency.','تكنولوجيا موثوقة ترفع كفاءة الخدمات العامة والعمليات الإدارية.'),
            'intro' => smt_t(
                'We support government entities with secure document workflows, standardized printing, resilient infrastructure, and managed services designed around governance, service continuity, and public accountability.',
                'ندعم الجهات الحكومية بحلول آمنة للمستندات، وطباعة موحدة، وبنية تحتية مرنة، وخدمات مُدارة مصممة حول الحوكمة واستمرارية الخدمة والمسؤولية العامة.'
            ),
            'priorities' => [
                smt_t('Secure citizen and administrative information','حماية بيانات المواطنين والمعلومات الإدارية'),
                smt_t('Service continuity across locations and departments','استمرارية الخدمة عبر المواقع والإدارات'),
                smt_t('Transparent procurement and lifecycle cost control','شفافية المشتريات والتحكم في التكلفة طوال دورة الحياة'),
                smt_t('Digital workflows that reduce paper and waiting time','إجراءات رقمية تقلل الورق ووقت الانتظار'),
            ],
            'solutions' => [
                ['fa-folder-tree',smt_t('Digital Records & Workflow','السجلات الرقمية وسير العمل'),smt_t('Structured archiving, approvals, retrieval, and retention.','أرشفة منظمة وموافقات واسترجاع وسياسات احتفاظ.')],
                ['fa-print',smt_t('Fleet Standardization','توحيد أسطول الطباعة'),smt_t('Right-sized devices, centralized support, and usage governance.','أجهزة بالحجم المناسب ودعم مركزي وحوكمة للاستخدام.')],
                ['fa-server',smt_t('Resilient Infrastructure','بنية تحتية مرنة'),smt_t('Scalable platforms for critical administrative and public services.','منصات قابلة للتوسع للخدمات الإدارية والعامة الحيوية.')],
                ['fa-headset',smt_t('Managed Support','دعم مُدار'),smt_t('Clear SLAs, preventive service, reporting, and escalation.','مستويات خدمة واضحة وصيانة وقائية وتقارير وتصعيد.')],
            ],
            'outcomes' => [smt_t('Faster public services','خدمات عامة أسرع'),smt_t('Improved governance','حوكمة أفضل'),smt_t('Reduced operational waste','هدر تشغيلي أقل'),smt_t('More reliable operations','عمليات أكثر اعتمادية')],
        ],
        'manufacturing' => [
            'icon' => 'fa-industry',
            'title' => smt_t('Manufacturing','التصنيع'),
            'tagline' => smt_t('Technology that protects uptime and improves control from plant floor to head office.','تكنولوجيا تحافظ على الجاهزية وتدعم التحكم من أرض المصنع إلى الإدارة الرئيسية.'),
            'intro' => smt_t(
                'We connect production, warehouse, quality, maintenance, and office teams through dependable printing, document workflows, infrastructure, security, and support services.',
                'نربط فرق الإنتاج والمخازن والجودة والصيانة والمكاتب من خلال حلول موثوقة للطباعة والمستندات والبنية التحتية والأمن والدعم.'
            ),
            'priorities' => [
                smt_t('Production uptime and fast issue resolution','استمرارية الإنتاج وسرعة حل المشكلات'),
                smt_t('Controlled work instructions, quality, and compliance documents','التحكم في تعليمات العمل ومستندات الجودة والامتثال'),
                smt_t('Reliable plant, warehouse, and office connectivity','اتصال موثوق للمصنع والمخازن والمكاتب'),
                smt_t('Lifecycle cost control for distributed technology assets','التحكم في تكلفة دورة حياة الأصول التقنية الموزعة'),
            ],
            'solutions' => [
                ['fa-print',smt_t('Production & Office Print','طباعة الإنتاج والمكاتب'),smt_t('Devices matched to workload, location, media, and availability needs.','أجهزة مناسبة لحجم العمل والموقع والخامات ومتطلبات الجاهزية.')],
                ['fa-diagram-project',smt_t('Workflow Automation','أتمتة سير العمل'),smt_t('Digitize approvals, quality records, maintenance, and operational forms.','رقمنة الموافقات وسجلات الجودة والصيانة والنماذج التشغيلية.')],
                ['fa-wifi',smt_t('Plant Connectivity','اتصال المصنع'),smt_t('Networks, endpoints, backup, and resilient infrastructure.','شبكات وأجهزة مستخدمين ونسخ احتياطي وبنية مرنة.')],
                ['fa-screwdriver-wrench',smt_t('Lifecycle Support','دعم دورة الحياة'),smt_t('Preventive service, asset visibility, spare-parts planning, and SLAs.','صيانة وقائية ورؤية للأصول وتخطيط لقطع الغيار ومستويات خدمة.')],
            ],
            'outcomes' => [smt_t('Higher operational uptime','جاهزية تشغيل أعلى'),smt_t('Faster workflows','إجراءات أسرع'),smt_t('Better quality control','تحكم أفضل في الجودة'),smt_t('Predictable support cost','تكلفة دعم قابلة للتوقع')],
        ],
        'healthcare' => [
            'icon' => 'fa-heart-pulse',
            'title' => smt_t('Healthcare','الرعاية الصحية'),
            'tagline' => smt_t('Secure, available technology for patient, clinical, and administrative workflows.','تكنولوجيا آمنة ومتاحة تدعم مسارات العمل الطبية والإدارية وخدمة المرضى.'),
            'intro' => smt_t(
                'We help healthcare organizations protect sensitive information, improve document availability, standardize output, and maintain reliable infrastructure across clinical and administrative areas.',
                'نساعد مؤسسات الرعاية الصحية على حماية المعلومات الحساسة، وتحسين إتاحة المستندات، وتوحيد المخرجات، والحفاظ على بنية تحتية موثوقة في المناطق الطبية والإدارية.'
            ),
            'priorities' => [
                smt_t('Patient-information confidentiality and controlled access','سرية معلومات المرضى والتحكم في الوصول'),
                smt_t('Reliable printing and scanning near points of care','طباعة ومسح ضوئي موثوقان بالقرب من نقاط تقديم الخدمة'),
                smt_t('Fast clinical and administrative document retrieval','استرجاع سريع للمستندات الطبية والإدارية'),
                smt_t('Service continuity across facilities and departments','استمرارية الخدمة عبر المنشآت والإدارات'),
            ],
            'solutions' => [
                ['fa-user-shield',smt_t('Secure Document Access','وصول آمن للمستندات'),smt_t('Authentication, role controls, audit trails, and protected release.','مصادقة وصلاحيات حسب الدور وسجلات تدقيق وإخراج محمي.')],
                ['fa-notes-medical',smt_t('Clinical Document Workflow','سير المستندات الطبية'),smt_t('Capture, classify, route, retrieve, and retain critical information.','التقاط وتصنيف وتوجيه واسترجاع واحتفاظ بالمعلومات الحيوية.')],
                ['fa-print',smt_t('Reliable Device Fleet','أسطول أجهزة موثوق'),smt_t('Right devices, preventive maintenance, monitoring, and supplies.','أجهزة مناسبة وصيانة وقائية ومراقبة ومستهلكات.')],
                ['fa-server',smt_t('Infrastructure & Continuity','البنية والاستمرارية'),smt_t('Resilient networks, backup, endpoint protection, and support.','شبكات مرنة ونسخ احتياطي وحماية للأجهزة ودعم.')],
            ],
            'outcomes' => [smt_t('Better information security','أمان أفضل للمعلومات'),smt_t('Faster staff workflows','إجراءات أسرع للفرق'),smt_t('Higher service reliability','اعتمادية أعلى للخدمة'),smt_t('Improved patient experience','تجربة أفضل للمريض')],
        ],
        'education' => [
            'icon' => 'fa-graduation-cap',
            'title' => smt_t('Education','التعليم'),
            'tagline' => smt_t('Connected, cost-controlled technology for learning, administration, and campus services.','تكنولوجيا مترابطة ومحكومة التكلفة للتعلم والإدارة وخدمات المؤسسة التعليمية.'),
            'intro' => smt_t(
                'We enable schools, universities, institutes, and training organizations to manage print, collaboration, infrastructure, security, and digital workflows across campuses and departments.',
                'نساعد المدارس والجامعات والمعاهد ومؤسسات التدريب على إدارة الطباعة والتعاون والبنية التحتية والأمن والإجراءات الرقمية عبر الفروع والإدارات.'
            ),
            'priorities' => [
                smt_t('Cost control across students, departments, and campuses','التحكم في التكلفة حسب الطلاب والإدارات والفروع'),
                smt_t('Reliable access to learning and administrative services','وصول موثوق لخدمات التعلم والإدارة'),
                smt_t('Secure collaboration and identity management','تعاون آمن وإدارة للهويات'),
                smt_t('Digital admissions, records, approvals, and communication','رقمنة القبول والسجلات والموافقات والتواصل'),
            ],
            'solutions' => [
                ['fa-print',smt_t('Campus Print Management','إدارة طباعة المؤسسة التعليمية'),smt_t('Quotas, authentication, reporting, and optimized fleet placement.','حصص ومصادقة وتقارير وتوزيع محسّن للأجهزة.')],
                ['fa-cloud',smt_t('Cloud Collaboration','التعاون السحابي'),smt_t('Microsoft productivity, communication, storage, and identity solutions.','حلول مايكروسوفت للإنتاجية والتواصل والتخزين والهوية.')],
                ['fa-folder-open',smt_t('Digital Administration','إدارة رقمية'),smt_t('Document capture, records, approvals, and workflow automation.','التقاط المستندات والسجلات والموافقات وأتمتة الإجراءات.')],
                ['fa-shield-halved',smt_t('Campus Security','أمن المؤسسة التعليمية'),smt_t('Endpoint, email, identity, backup, and network protection.','حماية الأجهزة والبريد والهوية والنسخ الاحتياطي والشبكة.')],
            ],
            'outcomes' => [smt_t('Lower print and IT cost','تكلفة أقل للطباعة والتقنية'),smt_t('Better student services','خدمات أفضل للطلاب'),smt_t('Improved staff productivity','إنتاجية أعلى للفرق'),smt_t('Safer digital learning','تعلم رقمي أكثر أمانًا')],
        ],
        'retail' => [
            'icon' => 'fa-store',
            'title' => smt_t('Retail & Distribution','التجزئة والتوزيع'),
            'tagline' => smt_t('Scalable branch technology for fast-moving customer and back-office operations.','تكنولوجيا قابلة للتوسع للفروع وعمليات العملاء والمكاتب الخلفية سريعة الحركة.'),
            'intro' => smt_t(
                'We help retail and distribution businesses connect locations, standardize devices, secure information, automate documents, and support growth without multiplying operational complexity.',
                'نساعد شركات التجزئة والتوزيع على ربط المواقع وتوحيد الأجهزة وحماية المعلومات وأتمتة المستندات ودعم النمو دون زيادة التعقيد التشغيلي.'
            ),
            'priorities' => [
                smt_t('Consistent technology across many locations','تكنولوجيا موحدة عبر عدد كبير من المواقع'),
                smt_t('Fast support for customer-facing operations','دعم سريع للعمليات المواجهة للعملاء'),
                smt_t('Secure pricing, inventory, finance, and HR documents','حماية مستندات الأسعار والمخزون والمالية والموارد البشرية'),
                smt_t('Central cost and asset visibility','رؤية مركزية للتكلفة والأصول'),
            ],
            'solutions' => [
                ['fa-sitemap',smt_t('Branch Standardization','توحيد الفروع'),smt_t('Repeatable device, network, security, and support standards.','معايير قابلة للتكرار للأجهزة والشبكات والأمن والدعم.')],
                ['fa-print',smt_t('Distributed Print Management','إدارة الطباعة الموزعة'),smt_t('Remote monitoring, supplies, service, usage policy, and reporting.','مراقبة عن بُعد ومستهلكات وخدمة وسياسات استخدام وتقارير.')],
                ['fa-boxes-stacked',smt_t('Document & Inventory Workflows','إجراءات المستندات والمخزون'),smt_t('Digitize approvals, invoices, stock documents, and internal forms.','رقمنة الموافقات والفواتير ومستندات المخزون والنماذج الداخلية.')],
                ['fa-headset',smt_t('Centralized Support','دعم مركزي'),smt_t('One service model across branches with clear ownership and SLAs.','نموذج خدمة واحد للفروع بمسؤولية ومستويات خدمة واضحة.')],
            ],
            'outcomes' => [smt_t('Faster branch support','دعم أسرع للفروع'),smt_t('Consistent customer operations','عمليات أكثر اتساقًا للعملاء'),smt_t('Central cost control','تحكم مركزي في التكلفة'),smt_t('Easier expansion','توسع أسهل')],
        ],
        'logistics' => [
            'icon' => 'fa-truck-fast',
            'title' => smt_t('Logistics & Distribution','الخدمات اللوجستية والتوزيع'),
            'tagline' => smt_t('Connected, documented, and responsive operations across warehouses, offices, and routes.','عمليات مترابطة وموثقة وسريعة الاستجابة عبر المخازن والمكاتب ومسارات التوزيع.'),
            'intro' => smt_t(
                'We support logistics organizations with reliable infrastructure, document and label workflows, secure connectivity, managed printing, and centralized support across distributed sites.',
                'ندعم مؤسسات الخدمات اللوجستية ببنية تحتية موثوقة وإجراءات للمستندات والملصقات واتصال آمن وطباعة مُدارة ودعم مركزي عبر المواقع الموزعة.'
            ),
            'priorities' => [
                smt_t('Always-available warehouse and office systems','أنظمة متاحة باستمرار للمخازن والمكاتب'),
                smt_t('Fast, accurate operational documents and labels','مستندات وملصقات تشغيلية سريعة ودقيقة'),
                smt_t('Secure connectivity between locations','اتصال آمن بين المواقع'),
                smt_t('Central monitoring, support, and asset visibility','مراقبة ودعم ورؤية مركزية للأصول'),
            ],
            'solutions' => [
                ['fa-barcode',smt_t('Operational Print & Capture','الطباعة والالتقاط التشغيلي'),smt_t('Reliable documents, labels, scanning, and workflow capture.','مستندات وملصقات ومسح ضوئي والتقاط لسير العمل بشكل موثوق.')],
                ['fa-network-wired',smt_t('Multi-site Connectivity','ربط المواقع'),smt_t('Secure networks, wireless coverage, endpoints, and remote access.','شبكات آمنة وتغطية لاسلكية وأجهزة مستخدمين ووصول عن بُعد.')],
                ['fa-file-circle-check',smt_t('Workflow Visibility','وضوح سير العمل'),smt_t('Digitize handovers, approvals, records, and exception handling.','رقمنة التسليمات والموافقات والسجلات ومعالجة الاستثناءات.')],
                ['fa-gauge-high',smt_t('Managed Availability','جاهزية مُدارة'),smt_t('Monitoring, preventive support, SLA control, and reporting.','مراقبة ودعم وقائي وتحكم في مستوى الخدمة وتقارير.')],
            ],
            'outcomes' => [smt_t('Fewer operational delays','تأخيرات تشغيل أقل'),smt_t('Higher document accuracy','دقة أعلى للمستندات'),smt_t('Better site visibility','رؤية أفضل للمواقع'),smt_t('More reliable service','خدمة أكثر اعتمادية')],
        ],
        'oil-gas' => [
            'icon' => 'fa-oil-well',
            'title' => smt_t('Oil & Gas','البترول والغاز'),
            'tagline' => smt_t('Resilient, secure technology for critical operations and distributed sites.','تكنولوجيا مرنة وآمنة للعمليات الحيوية والمواقع الموزعة.'),
            'intro' => smt_t(
                'We help oil and gas organizations maintain secure document control, dependable site infrastructure, protected connectivity, and service continuity across demanding operational environments.',
                'نساعد مؤسسات البترول والغاز على الحفاظ على التحكم الآمن في المستندات وبنية مواقع موثوقة واتصال محمي واستمرارية الخدمة في بيئات تشغيل تتطلب أعلى درجات الاعتمادية.'
            ),
            'priorities' => [
                smt_t('Business continuity at remote and critical sites','استمرارية الأعمال في المواقع البعيدة والحيوية'),
                smt_t('Secure technical, HSE, commercial, and operational documents','حماية المستندات الفنية والسلامة والتجارية والتشغيلية'),
                smt_t('Resilient networks, backup, and endpoint operations','شبكات ونسخ احتياطي وأجهزة تشغيل مرنة'),
                smt_t('Clear support ownership and escalation','مسؤولية واضحة عن الدعم والتصعيد'),
            ],
            'solutions' => [
                ['fa-tower-broadcast',smt_t('Remote-site Infrastructure','بنية المواقع البعيدة'),smt_t('Reliable networks, endpoints, backup, and monitored platforms.','شبكات وأجهزة ونسخ احتياطي ومنصات مراقبة موثوقة.')],
                ['fa-file-signature',smt_t('Controlled Documents','مستندات محكومة'),smt_t('Secure capture, version control, approvals, retention, and retrieval.','التقاط آمن وتحكم في الإصدارات وموافقات واحتفاظ واسترجاع.')],
                ['fa-shield-halved',smt_t('Cyber Resilience','مرونة سيبرانية'),smt_t('Identity, endpoint, network, data, and recovery protection.','حماية الهوية والأجهزة والشبكة والبيانات والتعافي.')],
                ['fa-headset',smt_t('Critical Support Model','نموذج دعم للعمليات الحيوية'),smt_t('Preventive service, escalation, reporting, and continuity planning.','خدمة وقائية وتصعيد وتقارير وتخطيط للاستمرارية.')],
            ],
            'outcomes' => [smt_t('Higher site resilience','مرونة أعلى للمواقع'),smt_t('Stronger information control','تحكم أقوى في المعلومات'),smt_t('Reduced operational risk','مخاطر تشغيل أقل'),smt_t('Faster issue escalation','تصعيد أسرع للمشكلات')],
        ],
    ];
}

function smt_resource_catalog(): array {
    return [
        'fleet-savings-calculator' => [
            'icon'=>'fa-calculator',
            'title'=>smt_t('Fleet Savings Calculator','حاسبة وفر الطباعة'),
            'text'=>smt_t('Estimate the financial opportunity in your current print environment using a structured high-level model.','قدّر فرص التوفير المالي في بيئة الطباعة الحالية من خلال نموذج مبدئي منظم.'),
            'cta'=>smt_t('Calculate Potential Savings','احسب فرص التوفير'),
        ],
        'company-profile' => [
            'icon'=>'fa-file-pdf',
            'title'=>smt_t('Company Profile','الملف التعريفي للشركة'),
            'text'=>smt_t('Review our company positioning, solution portfolio, delivery approach, sectors, and customer value proposition.','تعرّف على مكانة الشركة ومحفظة الحلول ومنهجية التنفيذ والقطاعات والقيمة التي نقدمها للعملاء.'),
            'cta'=>smt_t('View Company Profile','استعرض الملف التعريفي'),
        ],
        'technology-assessment' => [
            'icon'=>'fa-clipboard-check',
            'title'=>smt_t('Technology Assessment','تقييم البيئة التقنية'),
            'text'=>smt_t('Start a structured review of your print, infrastructure, cloud, security, or workflow environment.','ابدأ مراجعة منظمة لبيئة الطباعة أو البنية التحتية أو السحابة أو الأمن أو سير العمل.'),
            'cta'=>smt_t('Start an Assessment','ابدأ التقييم'),
        ],
        'faq-support' => [
            'icon'=>'fa-circle-question',
            'title'=>smt_t('FAQs & Support','الأسئلة الشائعة والدعم'),
            'text'=>smt_t('Get clear answers about our engagement model, implementation, service, support, and next steps.','احصل على إجابات واضحة حول نموذج التعاون والتنفيذ والخدمة والدعم والخطوات التالية.'),
            'cta'=>smt_t('Browse FAQs','استعرض الأسئلة الشائعة'),
        ],
        'insights' => [
            'icon'=>'fa-lightbulb',
            'title'=>smt_t('Insights & Articles','الرؤى والمقالات'),
            'text'=>smt_t('Practical perspectives on technology planning, cost optimization, security, and digital transformation.','رؤى عملية حول التخطيط التقني وتحسين التكلفة والأمن والتحول الرقمي.'),
            'cta'=>smt_t('Explore Insights','استكشف الرؤى'),
        ],
        'downloads' => [
            'icon'=>'fa-download',
            'title'=>smt_t('Downloads Center','مركز التحميل'),
            'text'=>smt_t('Access company materials, solution guides, checklists, and selected business resources.','حمّل مواد الشركة وأدلة الحلول وقوائم المراجعة والموارد المختارة للأعمال.'),
            'cta'=>smt_t('Open Downloads','افتح مركز التحميل'),
        ],
    ];
}

function smt_industry_key_from_current_page(): string {
    $catalog = smt_industry_catalog();
    $id = get_queried_object_id();
    foreach (array_keys($catalog) as $slug) {
        $source = get_page_by_path($slug, OBJECT, 'page');
        if (!$source) continue;
        if ((int)$source->ID === (int)$id) return $slug;
        if (function_exists('pll_get_post_translations')) {
            $translations = pll_get_post_translations($source->ID);
            if (is_array($translations) && in_array((int)$id, array_map('intval',$translations), true)) return $slug;
        }
    }
    $slug = (string)get_post_field('post_name',$id);
    return isset($catalog[$slug]) ? $slug : 'banking';
}

function smt_render_industry_detail(string $key): void {
    $catalog = smt_industry_catalog();
    $d = $catalog[$key] ?? reset($catalog);
    ?>
    <main class="industry-sector-page">
      <?php smt_breadcrumbs(); ?>
      <section class="ui-page-hero industry-sector-hero"><div class="container industry-sector-hero-grid"><div>
        <span class="eyebrow"><?php echo esc_html(smt_t('Sector-focused expertise','خبرة متخصصة للقطاعات')); ?></span>
        <h1><?php echo esc_html($d['title']); ?></h1><p><?php echo esc_html($d['tagline']); ?></p>
        <div class="hero-actions"><a class="btn btn-gold" href="<?php echo esc_url(smt_page_url('contact')); ?>"><?php echo esc_html(smt_t('Discuss Your Environment','ناقش بيئة عملك')); ?></a><a class="btn btn-outline" href="<?php echo esc_url(smt_page_url('industries')); ?>"><?php echo esc_html(smt_t('View All Industries','جميع القطاعات')); ?></a></div>
      </div><aside class="industry-sector-symbol"><i class="fa-solid <?php echo esc_attr($d['icon']); ?>"></i><strong><?php echo esc_html(smt_t('Technology aligned with sector priorities','تكنولوجيا متوافقة مع أولويات القطاع')); ?></strong><span><?php echo esc_html(smt_t('Security • Efficiency • Continuity • Growth','الأمان • الكفاءة • الاستمرارية • النمو')); ?></span></aside></div></section>

      <section class="section"><div class="container industry-overview-grid"><div><?php smt_section_heading(['kicker'=>smt_t('Your operating reality','طبيعة بيئة العمل'),'title'=>smt_t('A sector approach grounded in real operational priorities','منهج متخصص يبدأ من الأولويات التشغيلية الفعلية'),'text'=>$d['intro']]); ?></div><aside class="industry-priority-panel"><h2><?php echo esc_html(smt_t('Priority areas','مجالات الأولوية')); ?></h2><ul><?php foreach($d['priorities'] as $item): ?><li><i class="fa-solid fa-circle-check"></i><span><?php echo esc_html($item); ?></span></li><?php endforeach; ?></ul></aside></div></section>

      <section class="section ui-surface-section"><div class="container"><?php smt_section_heading(['kicker'=>smt_t('How we help','كيف نساعدك'),'title'=>smt_t('Integrated capabilities designed for your sector','قدرات متكاملة مصممة لتحديات قطاعك'),'text'=>smt_t('We combine the right technology, delivery model, and support structure around your sites, users, risk profile, and business goals.','نجمع بين التكنولوجيا المناسبة ونموذج التنفيذ وهيكل الدعم وفق مواقعك ومستخدميك ومستوى المخاطر وأهداف الأعمال.'),'alignment'=>'center']); ?><div class="industry-solution-grid"><?php foreach($d['solutions'] as $solution): ?><article class="industry-solution-card reveal"><div><i class="fa-solid <?php echo esc_attr($solution[0]); ?>"></i></div><h2><?php echo esc_html($solution[1]); ?></h2><p><?php echo esc_html($solution[2]); ?></p></article><?php endforeach; ?></div></div></section>

      <section class="section ui-dark-section"><div class="container industry-result-layout"><div><?php smt_section_heading(['kicker'=>smt_t('Business outcomes','نتائج الأعمال'),'title'=>smt_t('Technology measured by the value it creates','نقيس التكنولوجيا بالقيمة التي تصنعها'),'text'=>smt_t('Our recommendations are built to improve performance, control cost, reduce risk, and strengthen service continuity.','نبني توصياتنا لتحسين الأداء والتحكم في التكلفة وتقليل المخاطر وتعزيز استمرارية الخدمة.'),'theme'=>'dark']); ?></div><div class="industry-result-grid"><?php foreach($d['outcomes'] as $index=>$outcome): ?><article><span>0<?php echo (int)$index+1; ?></span><strong><?php echo esc_html($outcome); ?></strong></article><?php endforeach; ?></div></div></section>

      <section class="section industry-delivery-section"><div class="container"><?php smt_section_heading(['kicker'=>smt_t('A practical engagement','تعاون عملي'),'title'=>smt_t('From discovery to measurable improvement','من فهم التحديات إلى تحسين يمكن قياسه'),'alignment'=>'center']); ?><ol class="industry-delivery-flow"><li><span>01</span><h3><?php echo esc_html(smt_t('Understand','نفهم')); ?></h3><p><?php echo esc_html(smt_t('Sites, users, processes, assets, costs, and risks.','المواقع والمستخدمون والإجراءات والأصول والتكلفة والمخاطر.')); ?></p></li><li><span>02</span><h3><?php echo esc_html(smt_t('Prioritize','نحدد الأولويات')); ?></h3><p><?php echo esc_html(smt_t('The highest-value operational and technology opportunities.','أعلى فرص التحسين قيمةً على المستوى التشغيلي والتقني.')); ?></p></li><li><span>03</span><h3><?php echo esc_html(smt_t('Deliver','ننفذ')); ?></h3><p><?php echo esc_html(smt_t('A controlled implementation with ownership and testing.','تنفيذ منضبط بمسؤوليات واختبارات واضحة.')); ?></p></li><li><span>04</span><h3><?php echo esc_html(smt_t('Improve','نطوّر')); ?></h3><p><?php echo esc_html(smt_t('Support, reporting, optimization, and roadmap updates.','دعم وتقارير وتحسين وتحديث مستمر لخارطة الطريق.')); ?></p></li></ol></div></section>

      <section class="ui-cta-section"><div class="container ui-cta-inner"><div><span class="section-kicker light"><?php echo esc_html(smt_t('Start with your priorities','ابدأ من أولوياتك')); ?></span><h2><?php echo esc_html(smt_t('Let us design the right technology roadmap for your organization.','دعنا نصمم خارطة الطريق التقنية المناسبة لمؤسستك.')); ?></h2><p><?php echo esc_html(smt_t('Share your current challenges and receive a practical recommendation for the next step.','شاركنا تحدياتك الحالية واحصل على توصية عملية للخطوة التالية.')); ?></p></div><div class="ui-cta-actions"><a class="btn btn-gold" href="<?php echo esc_url(smt_page_url('contact')); ?>"><?php echo esc_html(smt_t('Book a Sector Consultation','احجز استشارة متخصصة')); ?></a></div></div></section>
    </main>
    <?php
}
