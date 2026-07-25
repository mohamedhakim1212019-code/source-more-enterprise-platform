<?php
if (!defined('ABSPATH')) exit;

/**
 * Bilingual, page-level homepage marketing content.
 * Each Polylang homepage translation stores its own values.
 */
function smt_homepage_content_fields(): array {
    return [
        'hero' => [
            'label' => 'Hero',
            'fields' => [
                'hero_kicker' => ['label'=>'Eyebrow / Kicker','type'=>'text','en'=>'Enterprise Technology. One Accountable Partner.','ar'=>'مصدر واحد. حلول متكاملة. قيمة أكبر.'],
                'hero_title' => ['label'=>'Main headline (HTML allowed: <span>)','type'=>'html','en'=>'One source for <span>print, IT and digital transformation.</span>','ar'=>'نحوّل التكنولوجيا إلى <span>نتائج تدفع أعمالك للأمام.</span>'],
                'hero_description' => ['label'=>'Description','type'=>'textarea','en'=>'Source More Technology brings managed print, office technology, infrastructure, cloud, cybersecurity, and workflow automation together—helping organizations reduce cost, improve control, and move forward with confidence.','ar'=>'تجمع Source More بين حلول الطباعة المُدارة، وتكنولوجيا المعلومات، والبنية التحتية، والسحابة، والأمن السيبراني في منظومة واحدة تساعد مؤسستك على خفض التكاليف، ورفع الكفاءة، والعمل بثقة أكبر.'],
                'hero_primary_label' => ['label'=>'Primary button label','type'=>'text','en'=>'Explore Our Solutions','ar'=>'اكتشف كيف نطوّر أعمالك'],
                'hero_primary_url' => ['label'=>'Primary button URL (leave blank for Solutions page)','type'=>'url','en'=>'','ar'=>''],
                'hero_secondary_label' => ['label'=>'Secondary button label','type'=>'text','en'=>'Request a Consultation','ar'=>'تحدث مع خبير'],
                'hero_secondary_url' => ['label'=>'Secondary button URL (leave blank for Contact page)','type'=>'url','en'=>'','ar'=>''],
                'hero_image_url' => ['label'=>'Hero visual image URL (optional)','type'=>'url','en'=>'','ar'=>''],
            ],
        ],
        'business_challenges' => [
            'label' => 'Business Challenges',
            'fields' => [
                'challenges_kicker' => ['label'=>'Kicker','type'=>'text','en'=>'Business Challenges','ar'=>'تحديات تعطل النمو'],
                'challenges_title' => ['label'=>'Heading','type'=>'textarea','en'=>'Technology should remove friction—not create more of it.','ar'=>'كل تحدٍ تشغيلي هو فرصة لبناء أعمال أكثر كفاءة.'],
                'challenges_description' => ['label'=>'Description','type'=>'textarea','en'=>'We help organizations solve the operational issues that quietly increase cost, risk, and downtime.','ar'=>'نساعد المؤسسات على اكتشاف المشكلات التي تستنزف الوقت والميزانية، ثم نحولها إلى فرص واضحة لخفض التكلفة وتحسين الأداء وتقليل المخاطر.'],
            ],
        ],
        'solutions' => [
            'label' => 'Solutions',
            'fields' => [
                'solutions_kicker' => ['label'=>'Kicker','type'=>'text','en'=>'Integrated Solutions','ar'=>'حلول متكاملة'],
                'solutions_title' => ['label'=>'Heading','type'=>'textarea','en'=>'Build the technology environment your organization actually needs.','ar'=>'حلول متكاملة لكل جانب من جوانب بيئة العمل.'],
                'solutions_description' => ['label'=>'Description','type'=>'textarea','en'=>'Start with one priority or combine print, infrastructure, cloud, security, and automation through one accountable partner.','ar'=>'من أول ورقة تتم طباعتها إلى آخر ملف تتم حمايته على السحابة، نساعدك على بناء بيئة عمل أكثر كفاءة وأمانًا واتصالًا.'],
                'solutions_cta_label' => ['label'=>'Section button label','type'=>'text','en'=>'View the Full Solution Portfolio','ar'=>'استكشف جميع الحلول'],
            ],
        ],
        'technology' => [
            'label' => 'Products & Technology',
            'fields' => [
                'technology_kicker' => ['label'=>'Kicker','type'=>'text','en'=>'Technology Coverage','ar'=>'المنتجات والتكنولوجيا'],
                'technology_title' => ['label'=>'Heading','type'=>'textarea','en'=>'Hardware, software and services—connected around your business.','ar'=>'التقنية المناسبة، في المكان المناسب، لتحقيق أفضل أداء.'],
                'technology_description' => ['label'=>'Description','type'=>'textarea','en'=>'Our portfolio is designed to support the complete workplace technology lifecycle, from device selection and deployment to security, support and optimization.','ar'=>'نوفر الأجهزة والبرمجيات والخدمات التي تحتاجها مؤسستك، مع ضمان التكامل بينها واختيارها وفقًا لطبيعة الاستخدام والأهداف والميزانية.'],
                'technology_cta_title' => ['label'=>'CTA title','type'=>'text','en'=>'Looking for a specific product or solution?','ar'=>'تبحث عن منتج أو حل يناسب احتياجًا محددًا؟'],
                'technology_cta_text' => ['label'=>'CTA text','type'=>'textarea','en'=>'Explore the product center or ask our team to recommend the right fit.','ar'=>'استكشف مركز المنتجات أو تواصل مع فريقنا لترشيح الاختيار الأنسب لمؤسستك.'],
                'technology_cta_label' => ['label'=>'CTA button label','type'=>'text','en'=>'Browse Products','ar'=>'تصفح المنتجات والتقنيات'],
            ],
        ],
        'calculator' => [
            'label' => 'Fleet Savings Calculator',
            'fields' => [
                'calculator_badge' => ['label'=>'Badge','type'=>'text','en'=>'Free 2-minute assessment','ar'=>'تقييم سريع ومجاني'],
                'calculator_kicker' => ['label'=>'Kicker','type'=>'text','en'=>'Fleet Savings Calculator','ar'=>'حاسبة وفر الطباعة'],
                'calculator_title' => ['label'=>'Heading','type'=>'textarea','en'=>'How much is inefficient printing costing your business?','ar'=>'كم تدفع مؤسستك بسبب طباعة غير مُدارة بكفاءة؟'],
                'calculator_description' => ['label'=>'Description','type'=>'textarea','en'=>'Enter a few basic fleet figures to estimate your annual print cost, potential savings, optimized operating cost, and three-year opportunity. No registration is required.','ar'=>'أدخل بعض البيانات الأساسية لتحصل فورًا على تقدير لتكلفة الطباعة الحالية، وفرص التوفير، والأثر المتوقع خلال ثلاث سنوات.'],
                'calculator_cta_label' => ['label'=>'Button label','type'=>'text','en'=>'Calculate My Savings','ar'=>'اكتشف فرص التوفير الآن'],
            ],
        ],
        'why' => [
            'label' => 'Why Source More',
            'fields' => [
                'why_kicker' => ['label'=>'Kicker','type'=>'text','en'=>'Why Source More Technology','ar'=>'لماذا Source More؟'],
                'why_title' => ['label'=>'Heading','type'=>'textarea','en'=>'One accountable partner from assessment to ongoing support.','ar'=>'شريك تقني يفهم أهداف أعمالك، وليس احتياجاتك التقنية فقط.'],
                'why_description' => ['label'=>'Description','type'=>'textarea','en'=>'We focus on practical outcomes: lower operating cost, stronger control, better user experience, reduced risk, and a technology environment that can scale with your organization.','ar'=>'نبدأ بفهم التحديات الحقيقية داخل مؤسستك، ثم نختار ونصمم وننفذ الحل الذي يحقق أفضل قيمة على المدى الطويل.'],
                'why_cta_label' => ['label'=>'Button label','type'=>'text','en'=>'Speak With a Consultant','ar'=>'تحدث مع خبير'],
            ],
        ],
        'industries' => [
            'label' => 'Industries',
            'fields' => [
                'industries_kicker' => ['label'=>'Kicker','type'=>'text','en'=>'Industries We Support','ar'=>'القطاعات التي نخدمها'],
                'industries_title' => ['label'=>'Heading','type'=>'textarea','en'=>'Technology shaped around sector-specific priorities.','ar'=>'حلول تفهم طبيعة قطاعك وتحدياته.'],
                'industries_description' => ['label'=>'Description','type'=>'textarea','en'=>'We align technology decisions with operating realities, security needs, service continuity, and compliance expectations.','ar'=>'تختلف الأولويات من قطاع إلى آخر؛ لذلك نصمم كل حل وفقًا لمتطلبات التشغيل، والأمان، والامتثال، والنمو داخل مؤسستك.'],
            ],
        ],
        'process' => [
            'label' => 'How We Work',
            'fields' => [
                'process_kicker' => ['label'=>'Kicker','type'=>'text','en'=>'How We Work','ar'=>'كيف نعمل'],
                'process_title' => ['label'=>'Heading','type'=>'textarea','en'=>'A structured path from challenge to measurable improvement.','ar'=>'من التحدي إلى الحل… ومن الحل إلى نتائج ملموسة.'],
                'process_description' => ['label'=>'Description','type'=>'textarea','en'=>'Every engagement follows a practical method designed to reduce risk and keep business outcomes visible.','ar'=>'نتبع منهجًا واضحًا يبدأ بفهم بيئة العمل وينتهي بقياس النتائج والتحسين المستمر.'],
            ],
        ],
        'smart_tools' => [
            'label' => 'Smart Tools',
            'fields' => [
                'tools_kicker' => ['label'=>'Kicker','type'=>'text','en'=>'Smart Customer Experience','ar'=>'أدوات ذكية لاتخاذ قرار أفضل'],
                'tools_title' => ['label'=>'Heading','type'=>'textarea','en'=>'Move from interest to a practical next step—faster.','ar'=>'لا تكتفِ بالتخمين. ابدأ ببيانات تساعدك على اتخاذ القرار.'],
                'tools_description' => ['label'=>'Description','type'=>'textarea','en'=>'Our digital tools help visitors understand options, estimate savings and connect with the right specialist before the first meeting.','ar'=>'استخدم أدوات Source More لتقييم احتياجاتك، واكتشاف فرص التوفير، والحصول على توصيات أولية، والتواصل مباشرة مع فريق الحلول.'],
                'tools_ai_label' => ['label'=>'AI button label','type'=>'text','en'=>'Ask Source More AI','ar'=>'تحدث مع مساعد Source More'],
                'tools_calculator_label' => ['label'=>'Calculator button label','type'=>'text','en'=>'Calculate Savings','ar'=>'احسب فرص التوفير'],
            ],
        ],
        'ecosystem' => [
            'label' => 'Technology Ecosystem',
            'fields' => [
                'ecosystem_kicker' => ['label'=>'Kicker','type'=>'text','en'=>'Technology Ecosystem','ar'=>'منظومة تقنية متكاملة'],
                'ecosystem_title' => ['label'=>'Heading','type'=>'textarea','en'=>'The right combination—not a one-size-fits-all recommendation.','ar'=>'حل مصمم لأعمالك، وليس توصية جاهزة للجميع.'],
                'ecosystem_description' => ['label'=>'Description','type'=>'textarea','en'=>'We take a vendor-aware, business-first approach: selecting technologies around requirements, compatibility, lifecycle cost and support—not around a single product category.','ar'=>'نختار التقنية بناءً على احتياجات مؤسستك، والتوافق، وتكلفة دورة الحياة، ومستوى الدعم؛ لنضمن أن كل عنصر يضيف قيمة حقيقية للمنظومة بالكامل.'],
                'ecosystem_cta_label' => ['label'=>'Link label','type'=>'text','en'=>'Discuss a partnership or requirement','ar'=>'ناقش احتياجك مع فريقنا'],
            ],
        ],
        'resources' => [
            'label' => 'Resources',
            'fields' => [
                'resources_kicker' => ['label'=>'Kicker','type'=>'text','en'=>'Resources','ar'=>'موارد تساعدك على الاختيار'],
                'resources_title' => ['label'=>'Heading','type'=>'textarea','en'=>'Useful tools for better technology decisions.','ar'=>'ابدأ بخطوة عملية نحو قرار تقني أفضل.'],
                'resources_description' => ['label'=>'Description','type'=>'textarea','en'=>'Start with a practical tool, learn more about our capabilities, or speak directly with our team.','ar'=>'استخدم أدواتنا، وتعرّف على قدراتنا، أو تواصل مباشرة مع فريقنا لتحديد الخطوة الأنسب.'],
            ],
        ],
        'about' => [
            'label' => 'About Source More',
            'fields' => [
                'about_kicker' => ['label'=>'Kicker','type'=>'text','en'=>'About Source More','ar'=>'عن Source More'],
                'about_title' => ['label'=>'Heading','type'=>'textarea','en'=>'A practical technology partner focused on measurable outcomes.','ar'=>'شريك تقني يحوّل الاحتياجات إلى نتائج يمكن قياسها.'],
                'about_description' => ['label'=>'Description','type'=>'textarea','en'=>'We combine managed print, office technology, IT infrastructure, cloud, cybersecurity, and workflow expertise to help enterprises operate more efficiently and plan confidently for growth.','ar'=>'نجمع بين الخبرة العملية والحلول التقنية المتكاملة لمساعدة المؤسسات على تحسين الأداء، وتقليل التعقيد، وتحقيق نمو أكثر ثقة واستدامة.'],
                'about_cta_label' => ['label'=>'Link label','type'=>'text','en'=>'Discover our approach','ar'=>'اكتشف منهجنا'],
                'about_image_url' => ['label'=>'Section image URL (optional)','type'=>'url','en'=>'','ar'=>''],
            ],
        ],
        'final_cta' => [
            'label' => 'Final Call to Action',
            'fields' => [
                'final_kicker' => ['label'=>'Kicker','type'=>'text','en'=>'Start the Conversation','ar'=>'ابدأ خطوتك التالية'],
                'final_title' => ['label'=>'Heading','type'=>'textarea','en'=>'Ready to simplify your technology environment?','ar'=>'جاهز لبناء بيئة عمل أكثر كفاءة وذكاءً؟'],
                'final_description' => ['label'=>'Description','type'=>'textarea','en'=>'Tell us what you want to improve. We will help you identify a practical next step.','ar'=>'دعنا نراجع احتياجات مؤسستك ونصمم معك حلًا متكاملًا يحقق التوازن بين الأداء والتكلفة والأمان والنمو.'],
                'final_primary_label' => ['label'=>'Primary button label','type'=>'text','en'=>'Request a Consultation','ar'=>'ابدأ استشارتك الآن'],
                'final_primary_url' => ['label'=>'Primary button URL (leave blank for Contact page)','type'=>'url','en'=>'','ar'=>''],
                'final_secondary_label' => ['label'=>'Secondary button label','type'=>'text','en'=>'Explore Solutions','ar'=>'احسب فرص التوفير'],
                'final_secondary_url' => ['label'=>'Secondary button URL (leave blank for Solutions/Calculator default)','type'=>'url','en'=>'','ar'=>''],
            ],
        ],
    ];
}

function smt_homepage_field_definition(string $key): ?array {
    foreach (smt_homepage_content_fields() as $group) {
        if (isset($group['fields'][$key])) return $group['fields'][$key];
    }
    return null;
}

function smt_homepage_post_id(): int {
    return (int) get_queried_object_id();
}

function smt_home_value(string $key, $fallback_en = '', $fallback_ar = ''): string {
    $post_id = smt_homepage_post_id();
    $stored = $post_id ? get_post_meta($post_id, '_smt_home_' . $key, true) : '';
    if (is_string($stored) && trim($stored) !== '') return $stored;

    $definition = smt_homepage_field_definition($key);
    if ($definition) return smt_is_ar() ? (string)($definition['ar'] ?? '') : (string)($definition['en'] ?? '');

    return smt_t((string)$fallback_en, (string)$fallback_ar);
}

function smt_home_url_value(string $key, string $default_url): string {
    $value = trim(smt_home_value($key));
    return $value !== '' ? $value : $default_url;
}

function smt_is_homepage_translation(int $post_id): bool {
    $front_id = (int) get_option('page_on_front');
    if (!$front_id || !$post_id) return false;
    if ($front_id === $post_id) return true;
    if (function_exists('pll_get_post') && function_exists('pll_get_post_language')) {
        $lang = pll_get_post_language($post_id, 'slug');
        if ($lang && (int) pll_get_post($front_id, $lang) === $post_id) return true;
    }
    return false;
}

/**
 * Register every homepage field as REST-aware post meta so Gutenberg can save
 * the English and Arabic homepage translations independently.
 */
function smt_homepage_sanitize_meta_value($value, string $type): string {
    $value = is_scalar($value) ? (string) $value : '';
    if ($type === 'url') return esc_url_raw($value);
    if ($type === 'textarea') return sanitize_textarea_field($value);
    if ($type === 'html') {
        return wp_kses($value, [
            'span' => ['class' => true],
            'strong' => [],
            'br' => [],
        ]);
    }
    return sanitize_text_field($value);
}

add_action('init', function(): void {
    // Required for registered post meta to be exposed to the block editor REST API.
    add_post_type_support('page', 'custom-fields');

    foreach (smt_homepage_content_fields() as $group) {
        foreach ($group['fields'] as $key => $field) {
            $type = (string) ($field['type'] ?? 'text');
            register_post_meta('page', '_smt_home_' . $key, [
                'type' => 'string',
                'single' => true,
                'default' => '',
                'show_in_rest' => true,
                'sanitize_callback' => static function($value) use ($type): string {
                    return smt_homepage_sanitize_meta_value($value, $type);
                },
                'auth_callback' => static function(): bool {
                    return current_user_can('edit_pages');
                },
            ]);
        }
    }
});

function smt_homepage_editor_post_id(): int {
    if (isset($_GET['post'])) return absint($_GET['post']);
    if (isset($_POST['post_ID'])) return absint($_POST['post_ID']);
    global $post;
    return $post instanceof WP_Post ? (int) $post->ID : 0;
}

function smt_homepage_editor_language(int $post_id): string {
    if (function_exists('pll_get_post_language')) {
        $language = pll_get_post_language($post_id, 'slug');
        if (is_string($language) && $language !== '') return $language;
    }
    return get_locale() === 'ar' || is_rtl() ? 'ar' : 'en';
}

function smt_homepage_group_label(string $group_key, string $fallback, string $language): string {
    if ($language !== 'ar') return $fallback;
    $labels = [
        'hero' => 'القسم الرئيسي',
        'business_challenges' => 'تحديات الأعمال',
        'solutions' => 'الحلول',
        'technology' => 'المنتجات والتكنولوجيا',
        'calculator' => 'حاسبة التوفير',
        'why' => 'لماذا Source More',
        'industries' => 'القطاعات',
        'process' => 'طريقة عملنا',
        'smart_tools' => 'الأدوات الذكية',
        'ecosystem' => 'المنظومة التقنية',
        'resources' => 'الموارد',
        'about' => 'عن Source More',
        'final_cta' => 'الدعوة الختامية',
    ];
    return $labels[$group_key] ?? $fallback;
}

function smt_homepage_field_label(string $key, string $fallback, string $language): string {
    if ($language !== 'ar') return $fallback;

    $exact = [
        'hero_kicker' => 'العبارة التمهيدية',
        'hero_title' => 'العنوان الرئيسي',
        'hero_description' => 'الوصف',
        'hero_primary_label' => 'نص الزر الرئيسي',
        'hero_primary_url' => 'رابط الزر الرئيسي',
        'hero_secondary_label' => 'نص الزر الثانوي',
        'hero_secondary_url' => 'رابط الزر الثانوي',
        'hero_image_url' => 'رابط صورة القسم الرئيسي',
        'calculator_badge' => 'الشارة',
        'tools_ai_label' => 'نص زر المساعد الذكي',
        'tools_calculator_label' => 'نص زر حاسبة التوفير',
        'about_image_url' => 'رابط صورة القسم',
    ];
    if (isset($exact[$key])) return $exact[$key];

    $label = strtolower($fallback);
    if (str_contains($label, 'url')) return 'الرابط';
    if (str_contains($label, 'button label')) return 'نص الزر';
    if (str_contains($label, 'link label')) return 'نص الرابط';
    if (str_contains($label, 'cta title')) return 'عنوان الدعوة';
    if (str_contains($label, 'cta text')) return 'نص الدعوة';
    if (str_contains($label, 'description')) return 'الوصف';
    if (str_contains($label, 'heading') || str_contains($label, 'headline')) return 'العنوان';
    if (str_contains($label, 'kicker') || str_contains($label, 'eyebrow')) return 'العبارة التمهيدية';
    if (str_contains($label, 'badge')) return 'الشارة';
    return $fallback;
}

function smt_homepage_editor_schema(int $post_id): array {
    $language = smt_homepage_editor_language($post_id);
    $schema = [];

    foreach (smt_homepage_content_fields() as $group_key => $group) {
        $fields = [];
        foreach ($group['fields'] as $key => $field) {
            $fields[] = [
                'key' => '_smt_home_' . $key,
                'label' => smt_homepage_field_label($key, (string) $field['label'], $language),
                'type' => (string) $field['type'],
                'defaultValue' => (string) ($field[$language === 'ar' ? 'ar' : 'en'] ?? ''),
                'help' => $field['type'] === 'html'
                    ? ($language === 'ar' ? 'يمكن استخدام الوسوم: <span> و<strong> و<br>.' : 'Allowed markup: <span>, <strong>, and <br>.')
                    : '',
            ];
        }
        $schema[] = [
            'key' => $group_key,
            'label' => smt_homepage_group_label($group_key, (string) $group['label'], $language),
            'fields' => $fields,
        ];
    }

    return $schema;
}

/**
 * Reliable homepage editor panel for both Gutenberg and the Classic Editor.
 *
 * WordPress renders compatible legacy meta boxes below the block editor. This
 * avoids depending on private Gutenberg packages that can change between
 * WordPress versions and ensures the bilingual fields remain editable.
 */
add_action('add_meta_boxes_page', function(WP_Post $post): void {
    if (!smt_is_homepage_translation((int) $post->ID)) return;

    $language = smt_homepage_editor_language((int) $post->ID);
    $title = $language === 'ar' ? 'محتوى الصفحة الرئيسية' : 'Homepage Showcase Content';

    add_meta_box(
        'smt-homepage-showcase-content',
        $title,
        'smt_render_homepage_content_metabox',
        'page',
        'normal',
        'high',
        [
            '__block_editor_compatible_meta_box' => true,
            '__back_compat_meta_box' => false,
        ]
    );
});

function smt_render_homepage_content_metabox(WP_Post $post): void {
    wp_nonce_field('smt_save_homepage_content', 'smt_homepage_content_nonce');
    $language = smt_homepage_editor_language((int) $post->ID);
    echo '<p><strong>' . esc_html($language === 'ar' ? 'محتوى الصفحة العربية' : 'English homepage content') . '</strong></p>';
    echo '<p class="description">' . esc_html($language === 'ar' ? 'عدّل النصوص التسويقية لهذه النسخة فقط.' : 'Edit the marketing copy for this language only.') . '</p>';
    echo '<div class="smt-home-fields">';

    foreach (smt_homepage_content_fields() as $group_key => $group) {
        echo '<details><summary>' . esc_html(smt_homepage_group_label($group_key, (string) $group['label'], $language)) . '</summary><div class="smt-home-fields__body">';
        foreach ($group['fields'] as $key => $field) {
            $value = get_post_meta($post->ID, '_smt_home_' . $key, true);
            if (!is_string($value) || $value === '') $value = (string) ($field[$language === 'ar' ? 'ar' : 'en'] ?? '');
            $name = 'smt_homepage[' . esc_attr($key) . ']';
            $id = 'smt_home_' . esc_attr($key);
            echo '<div class="smt-home-field"><label for="' . $id . '">' . esc_html(smt_homepage_field_label($key, (string) $field['label'], $language)) . '</label><div>';
            if (in_array($field['type'], ['textarea', 'html'], true)) {
                echo '<textarea id="' . $id . '" name="' . $name . '">' . esc_textarea($value) . '</textarea>';
            } else {
                $type = $field['type'] === 'url' ? 'url' : 'text';
                echo '<input type="' . esc_attr($type) . '" id="' . $id . '" name="' . $name . '" value="' . esc_attr($value) . '">';
            }
            echo '</div></div>';
        }
        echo '</div></details>';
    }
    echo '</div>';
}

add_action('admin_head-post.php', function(): void {
    $post_id = smt_homepage_editor_post_id();
    if (!$post_id || !smt_is_homepage_translation($post_id)) return;
    $is_ar = smt_homepage_editor_language($post_id) === 'ar';
    ?>
    <style id="smt-homepage-editor-admin-css">
      #smt-homepage-showcase-content{margin-top:18px}
      #smt-homepage-showcase-content .inside{margin:0;padding:18px}
      #smt-homepage-showcase-content .postbox-header h2{font-size:15px}
      .smt-home-fields{width:100%;max-width:none}
      .smt-home-fields details{border:1px solid #dcdcde;border-radius:10px;margin:12px 0;background:#fff;overflow:hidden}
      .smt-home-fields details[open]{box-shadow:0 4px 16px rgba(0,0,0,.04)}
      .smt-home-fields summary{cursor:pointer;padding:14px 16px;font-weight:700;background:#f6f7f7;user-select:none}
      .smt-home-fields summary:hover{background:#f0f0f1}
      .smt-home-fields__body{padding:16px}
      .smt-home-field{display:grid;grid-template-columns:minmax(180px,230px) minmax(0,1fr);gap:16px;align-items:start;margin:0 0 16px}
      .smt-home-field:last-child{margin-bottom:0}
      .smt-home-field label{font-weight:600;padding-top:8px;line-height:1.45}
      .smt-home-field input,.smt-home-field textarea{box-sizing:border-box;width:100%;max-width:none;min-height:40px}
      .smt-home-field textarea{min-height:96px;resize:vertical}
      <?php if ($is_ar): ?>
      #smt-homepage-showcase-content,.smt-home-fields{direction:rtl;text-align:right}
      .smt-home-field input,.smt-home-field textarea{direction:rtl;text-align:right;font-family:Cairo,Tahoma,Arial,sans-serif}
      <?php endif; ?>
      @media(max-width:1100px){.smt-home-field{grid-template-columns:1fr}.smt-home-field label{padding-top:0}}
    </style>
    <?php
});

add_action('save_post_page', function(int $post_id): void {
    // REST/Gutenberg saves registered post meta directly. This path is for the Classic Editor fallback.
    if (!isset($_POST['smt_homepage_content_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['smt_homepage_content_nonce'])), 'smt_save_homepage_content')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (!smt_is_homepage_translation($post_id)) return;

    $submitted = isset($_POST['smt_homepage']) && is_array($_POST['smt_homepage']) ? wp_unslash($_POST['smt_homepage']) : [];
    foreach (smt_homepage_content_fields() as $group) {
        foreach ($group['fields'] as $key => $field) {
            $raw = isset($submitted[$key]) ? (string) $submitted[$key] : '';
            update_post_meta($post_id, '_smt_home_' . $key, smt_homepage_sanitize_meta_value($raw, (string) $field['type']));
        }
    }
});


/**
 * v7.3.5 — Dedicated bilingual homepage content manager.
 *
 * The WordPress block editor can suppress or collapse legacy meta boxes in
 * some versions/configurations. A dedicated Appearance screen gives the
 * marketing fields a stable, full-width editing experience independent of
 * Gutenberg and keeps each Polylang homepage translation isolated.
 */
function smt_homepage_admin_languages(): array {
    $front_id = (int) get_option('page_on_front');
    if (!$front_id) return [];

    $pages = [];
    if (function_exists('pll_languages_list') && function_exists('pll_get_post')) {
        $languages = pll_languages_list(['fields' => 'slug']);
        if (is_array($languages)) {
            foreach ($languages as $language) {
                $language = sanitize_key((string) $language);
                if ($language === '') continue;
                $page_id = (int) pll_get_post($front_id, $language);
                if (!$page_id && function_exists('pll_get_post_language') && pll_get_post_language($front_id, 'slug') === $language) {
                    $page_id = $front_id;
                }
                if (!$page_id) continue;
                $pages[$language] = $page_id;
            }
        }
    }

    if (!$pages) {
        $language = function_exists('pll_get_post_language') ? (string) pll_get_post_language($front_id, 'slug') : '';
        if ($language === '') $language = get_locale() === 'ar' || is_rtl() ? 'ar' : 'en';
        $pages[$language] = $front_id;
    }

    return $pages;
}

function smt_homepage_admin_language_name(string $language): string {
    if ($language === 'ar') return 'العربية';
    if ($language === 'en') return 'English';
    if (function_exists('pll_languages_list')) {
        $slugs = pll_languages_list(['fields' => 'slug']);
        $names = pll_languages_list(['fields' => 'name']);
        if (is_array($slugs) && is_array($names)) {
            $index = array_search($language, $slugs, true);
            if ($index !== false && isset($names[$index])) return (string) $names[$index];
        }
    }
    return strtoupper($language);
}

add_action('admin_menu', function(): void {
    add_theme_page(
        __('Homepage Content', 'source-more-technology'),
        __('Homepage Content', 'source-more-technology'),
        'edit_pages',
        'smt-homepage-content',
        'smt_render_homepage_admin_page'
    );
});

function smt_render_homepage_admin_page(): void {
    if (!current_user_can('edit_pages')) wp_die(esc_html__('You are not allowed to edit this content.', 'source-more-technology'));

    $pages = smt_homepage_admin_languages();
    if (!$pages) {
        echo '<div class="wrap"><h1>' . esc_html__('Homepage Content', 'source-more-technology') . '</h1><div class="notice notice-warning"><p>' . esc_html__('Set a static homepage first under Settings → Reading.', 'source-more-technology') . '</p></div></div>';
        return;
    }

    $requested = isset($_GET['lang']) ? sanitize_key(wp_unslash($_GET['lang'])) : '';
    $default = function_exists('pll_default_language') ? (string) pll_default_language('slug') : '';
    if ($default === '' || !isset($pages[$default])) $default = (string) array_key_first($pages);
    $language = isset($pages[$requested]) ? $requested : $default;
    $post_id = (int) $pages[$language];
    $is_ar = $language === 'ar';
    $page = get_post($post_id);

    echo '<div class="wrap smt-homepage-manager" dir="' . ($is_ar ? 'rtl' : 'ltr') . '">';
    echo '<h1>' . esc_html($is_ar ? 'إدارة محتوى الصفحة الرئيسية' : 'Homepage Content Manager') . '</h1>';
    echo '<p class="description">' . esc_html($is_ar ? 'عدّل النسخة العربية هنا. يتم حفظها بصورة مستقلة عن النسخة الإنجليزية.' : 'Edit each language independently. Changes are applied to the matching Polylang homepage.') . '</p>';

    if (isset($_GET['updated'])) {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($is_ar ? 'تم حفظ محتوى الصفحة الرئيسية بنجاح.' : 'Homepage content saved successfully.') . '</p></div>';
    }

    echo '<nav class="nav-tab-wrapper smt-language-tabs" aria-label="' . esc_attr__('Homepage languages', 'source-more-technology') . '">';
    foreach ($pages as $slug => $id) {
        $url = add_query_arg(['page' => 'smt-homepage-content', 'lang' => $slug], admin_url('themes.php'));
        $active = $slug === $language ? ' nav-tab-active' : '';
        echo '<a class="nav-tab' . esc_attr($active) . '" href="' . esc_url($url) . '">' . esc_html(smt_homepage_admin_language_name($slug)) . '</a>';
    }
    echo '</nav>';

    echo '<div class="smt-homepage-manager__context">';
    echo '<strong>' . esc_html($is_ar ? 'الصفحة المرتبطة:' : 'Linked page:') . '</strong> ' . esc_html($page instanceof WP_Post ? $page->post_title : ('#' . $post_id));
    echo ' &nbsp; <a href="' . esc_url(get_permalink($post_id)) . '" target="_blank" rel="noopener">' . esc_html($is_ar ? 'معاينة الصفحة' : 'Preview page') . '</a>';
    echo '</div>';

    echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
    wp_nonce_field('smt_save_homepage_manager_' . $post_id, 'smt_homepage_manager_nonce');
    echo '<input type="hidden" name="action" value="smt_save_homepage_manager">';
    echo '<input type="hidden" name="post_id" value="' . esc_attr((string) $post_id) . '">';
    echo '<input type="hidden" name="lang" value="' . esc_attr($language) . '">';
    echo '<div class="smt-homepage-manager__groups">';

    $group_index = 0;
    foreach (smt_homepage_content_fields() as $group_key => $group) {
        $open = $group_index === 0 ? ' open' : '';
        echo '<details class="smt-homepage-manager__group"' . $open . '>';
        echo '<summary>' . esc_html(smt_homepage_group_label($group_key, (string) $group['label'], $language)) . '</summary>';
        echo '<div class="smt-homepage-manager__fields">';
        foreach ($group['fields'] as $key => $field) {
            $stored = get_post_meta($post_id, '_smt_home_' . $key, true);
            $value = is_string($stored) && $stored !== '' ? $stored : (string) ($field[$is_ar ? 'ar' : 'en'] ?? '');
            $id = 'smt_manager_' . $key;
            $name = 'smt_homepage[' . $key . ']';
            $label = smt_homepage_field_label($key, (string) $field['label'], $language);
            echo '<div class="smt-homepage-manager__field">';
            echo '<label for="' . esc_attr($id) . '">' . esc_html($label) . '</label>';
            echo '<div class="smt-homepage-manager__control">';
            if (in_array($field['type'], ['textarea', 'html'], true)) {
                echo '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" rows="5">' . esc_textarea($value) . '</textarea>';
            } else {
                $type = $field['type'] === 'url' ? 'url' : 'text';
                echo '<input type="' . esc_attr($type) . '" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '">';
            }
            if ($field['type'] === 'html') {
                echo '<p class="description">' . esc_html($is_ar ? 'يمكن استخدام: <span> و<strong> و<br>.' : 'Allowed markup: <span>, <strong>, and <br>.') . '</p>';
            }
            echo '</div></div>';
        }
        echo '</div></details>';
        $group_index++;
    }

    echo '</div>';
    submit_button($is_ar ? 'حفظ محتوى الصفحة العربية' : 'Save English Homepage Content');
    echo '</form></div>';
}

add_action('admin_post_smt_save_homepage_manager', function(): void {
    if (!current_user_can('edit_pages')) wp_die(esc_html__('You are not allowed to edit this content.', 'source-more-technology'));

    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    $language = isset($_POST['lang']) ? sanitize_key(wp_unslash($_POST['lang'])) : 'en';
    if (!$post_id || !current_user_can('edit_post', $post_id) || !smt_is_homepage_translation($post_id)) {
        wp_die(esc_html__('Invalid homepage selection.', 'source-more-technology'));
    }
    check_admin_referer('smt_save_homepage_manager_' . $post_id, 'smt_homepage_manager_nonce');

    $submitted = isset($_POST['smt_homepage']) && is_array($_POST['smt_homepage']) ? wp_unslash($_POST['smt_homepage']) : [];
    foreach (smt_homepage_content_fields() as $group) {
        foreach ($group['fields'] as $key => $field) {
            $raw = isset($submitted[$key]) ? (string) $submitted[$key] : '';
            $clean = smt_homepage_sanitize_meta_value($raw, (string) $field['type']);
            if ($clean === '') delete_post_meta($post_id, '_smt_home_' . $key);
            else update_post_meta($post_id, '_smt_home_' . $key, $clean);
        }
    }

    $redirect = add_query_arg([
        'page' => 'smt-homepage-content',
        'lang' => $language,
        'updated' => '1',
    ], admin_url('themes.php'));
    wp_safe_redirect($redirect);
    exit;
});

add_action('admin_enqueue_scripts', function(string $hook): void {
    if ($hook !== 'appearance_page_smt-homepage-content') return;
    wp_register_style('smt-homepage-manager-inline', false, [], '7.3.5');
    wp_enqueue_style('smt-homepage-manager-inline');
    $css = <<<'CSS'
.smt-homepage-manager{max-width:1180px}
.smt-homepage-manager[dir="rtl"]{font-family:Cairo,Tahoma,Arial,sans-serif;text-align:right}
.smt-homepage-manager .smt-language-tabs{margin-top:20px}
.smt-homepage-manager[dir="rtl"] .nav-tab{float:right}
.smt-homepage-manager__context{margin:18px 0;padding:14px 16px;border:1px solid #dcdcde;border-radius:8px;background:#fff}
.smt-homepage-manager__groups{display:grid;gap:14px;margin-top:18px}
.smt-homepage-manager__group{border:1px solid #dcdcde;border-radius:10px;background:#fff;overflow:hidden}
.smt-homepage-manager__group[open]{box-shadow:0 6px 20px rgba(0,0,0,.05)}
.smt-homepage-manager__group>summary{cursor:pointer;padding:16px 18px;background:#f6f7f7;font-size:15px;font-weight:700;user-select:none}
.smt-homepage-manager__group>summary:hover{background:#eef0f1}
.smt-homepage-manager__fields{padding:18px}
.smt-homepage-manager__field{display:grid;grid-template-columns:minmax(190px,240px) minmax(0,1fr);gap:18px;margin-bottom:18px;align-items:start}
.smt-homepage-manager__field:last-child{margin-bottom:0}
.smt-homepage-manager__field label{font-weight:600;padding-top:9px;line-height:1.45}
.smt-homepage-manager__control input,.smt-homepage-manager__control textarea{box-sizing:border-box;width:100%;max-width:none}
.smt-homepage-manager__control input{min-height:42px}
.smt-homepage-manager__control textarea{min-height:112px;resize:vertical}
.smt-homepage-manager[dir="rtl"] input,.smt-homepage-manager[dir="rtl"] textarea{direction:rtl;text-align:right;font-family:Cairo,Tahoma,Arial,sans-serif}
.smt-homepage-manager .submit{position:sticky;bottom:0;margin:20px 0 0;padding:14px 0;background:linear-gradient(to top,#f0f0f1 72%,transparent)}
@media(max-width:900px){.smt-homepage-manager__field{grid-template-columns:1fr;gap:7px}.smt-homepage-manager__field label{padding-top:0}}
CSS;
    wp_add_inline_style('smt-homepage-manager-inline', $css);
});

/** Point editors to the dedicated screen instead of the unreliable legacy area. */
add_action('admin_notices', function(): void {
    if (!function_exists('get_current_screen')) return;
    $screen = get_current_screen();
    if (!$screen || $screen->base !== 'post' || $screen->post_type !== 'page') return;
    $post_id = smt_homepage_editor_post_id();
    if (!$post_id || !smt_is_homepage_translation($post_id)) return;
    $language = smt_homepage_editor_language($post_id);
    $url = add_query_arg(['page' => 'smt-homepage-content', 'lang' => $language], admin_url('themes.php'));
    echo '<div class="notice notice-info"><p><strong>' . esc_html($language === 'ar' ? 'تعديل محتوى الصفحة الرئيسية:' : 'Homepage editing:') . '</strong> ';
    echo esc_html($language === 'ar' ? 'استخدم شاشة إدارة المحتوى الكاملة بدل منطقة Meta Boxes.' : 'Use the dedicated full-width content manager instead of the Meta Boxes area.') . ' ';
    echo '<a class="button button-primary" href="' . esc_url($url) . '">' . esc_html($language === 'ar' ? 'فتح محرر الصفحة الرئيسية' : 'Open Homepage Content Manager') . '</a></p></div>';
});
