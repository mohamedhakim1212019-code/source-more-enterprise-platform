<?php
if (!defined('ABSPATH')) exit;

/**
 * Returns true when the current request matches an English source page or any
 * Polylang translation connected to it.
 */
function smt_nav_is_page($slugs): bool {
    $slugs = (array) $slugs;
    $ids = [];
    foreach ($slugs as $slug) {
        $page = function_exists('smt_find_page_by_slug') ? smt_find_page_by_slug($slug, 'en') : get_page_by_path($slug);
        if (!$page instanceof WP_Post) continue;
        $ids[] = (int) $page->ID;
        if (function_exists('pll_get_post_translations')) {
            $translations = pll_get_post_translations($page->ID);
            if (is_array($translations)) {
                foreach ($translations as $translation_id) $ids[] = (int) $translation_id;
            }
        }
    }
    $ids = array_values(array_unique(array_filter($ids)));
    return $ids ? is_page($ids) : false;
}

function smt_nav_active_class($slugs): string {
    return smt_nav_is_page($slugs) ? ' current-menu-item' : '';
}

function smt_nav_parent_active_class($slugs): string {
    return smt_nav_is_page($slugs) ? ' current-menu-ancestor' : '';
}

function smt_products_url(): string {
    $archive = post_type_exists('smt_product') ? get_post_type_archive_link('smt_product') : '';
    return $archive ?: smt_page_url('products');
}

function smt_posts_or_resources_url(): string {
    $posts_page = (int) get_option('page_for_posts');
    if ($posts_page) {
        if (function_exists('pll_get_post')) {
            $translated = pll_get_post($posts_page, smt_lang());
            if ($translated) return get_permalink($translated);
        }
        return get_permalink($posts_page);
    }
    return smt_page_url('resources');
}

function smt_render_mega_navigation(): void {
    $solution_slugs = [
        'solutions','managed-print-services','enterprise-printing','document-management',
        'office-automation','it-infrastructure','cloud-microsoft-solutions','cybersecurity',
        'annual-maintenance','it-consulting','digital-transformation'
    ];
    $industry_slugs = ['industries','banking','government','manufacturing','healthcare','education','retail','logistics','oil-gas'];
    $resource_slugs = ['resources','fleet-savings-calculator','company-profile','technology-assessment','faq-support','insights','downloads'];
    ?>
    <ul class="enterprise-menu smt-mega-navigation" id="primary-menu">
      <li class="menu-item<?php echo esc_attr(smt_nav_active_class('home')); ?>"><a href="<?php echo esc_url(smt_home_url()); ?>"><?php echo esc_html(smt_t('Home','الرئيسية')); ?></a></li>
      <li class="menu-item<?php echo esc_attr(smt_nav_active_class('about-us')); ?>"><a href="<?php echo esc_url(smt_page_url('about-us')); ?>"><?php echo esc_html(smt_t('About Us','من نحن')); ?></a></li>

      <li class="menu-item menu-item-has-children has-mega-menu smt-mega-parent<?php echo esc_attr(smt_nav_parent_active_class($solution_slugs)); ?>">
        <a href="<?php echo esc_url(smt_page_url('solutions')); ?>"><?php echo esc_html(smt_t('Solutions','الحلول')); ?></a>
        <div class="sub-menu mega-menu smt-mega-menu smt-mega-menu-solutions" role="group" aria-label="<?php echo esc_attr(smt_t('Solutions menu','قائمة الحلول')); ?>">
          <div class="mega-grid smt-solutions-grid">
            <section>
              <h3><?php echo esc_html(smt_t('Print & Documents','حلول الطباعة والمستندات')); ?></h3>
              <a href="<?php echo esc_url(smt_page_url('managed-print-services')); ?>"><?php echo esc_html(smt_t('Managed Print Services','خدمات الطباعة المُدارة')); ?></a>
              <a href="<?php echo esc_url(smt_page_url('enterprise-printing')); ?>"><?php echo esc_html(smt_t('Enterprise Printing','حلول الطباعة المؤسسية')); ?></a>
              <a href="<?php echo esc_url(smt_page_url('document-management')); ?>"><?php echo esc_html(smt_t('Document Management','إدارة المستندات وسير العمل')); ?></a>
              <a href="<?php echo esc_url(smt_page_url('office-automation')); ?>"><?php echo esc_html(smt_t('Office Automation','أتمتة بيئة العمل')); ?></a>
            </section>
            <section>
              <h3><?php echo esc_html(smt_t('Infrastructure & Cloud','البنية التحتية والسحابة')); ?></h3>
              <a href="<?php echo esc_url(smt_page_url('it-infrastructure')); ?>"><?php echo esc_html(smt_t('IT Infrastructure','البنية التحتية لتكنولوجيا المعلومات')); ?></a>
              <a href="<?php echo esc_url(smt_page_url('cloud-microsoft-solutions')); ?>"><?php echo esc_html(smt_t('Cloud & Microsoft','حلول السحابة ومايكروسوفت')); ?></a>
              <a href="<?php echo esc_url(smt_page_url('cybersecurity')); ?>"><?php echo esc_html(smt_t('Network & Cybersecurity','الشبكات والأمن السيبراني')); ?></a>
            </section>
            <section>
              <h3><?php echo esc_html(smt_t('Business Solutions','خدمات الأعمال')); ?></h3>
              <a href="<?php echo esc_url(smt_page_url('annual-maintenance')); ?>"><?php echo esc_html(smt_t('Annual Maintenance','عقود الصيانة والدعم')); ?></a>
              <a href="<?php echo esc_url(smt_page_url('it-consulting')); ?>"><?php echo esc_html(smt_t('IT Consulting','استشارات تكنولوجيا المعلومات')); ?></a>
              <a href="<?php echo esc_url(smt_page_url('digital-transformation')); ?>"><?php echo esc_html(smt_t('Digital Transformation','التحول الرقمي')); ?></a>
            </section>
            <aside class="mega-feature">
              <span><?php echo esc_html(smt_t('Not sure where to start?','لست متأكدًا من نقطة البداية؟')); ?></span>
              <strong><?php echo esc_html(smt_t('Talk to a technology consultant.','تحدث مع مستشار تقني.')); ?></strong>
              <a class="btn btn-gold" href="<?php echo esc_url(smt_page_url('contact')); ?>"><?php echo esc_html(smt_t('Book a Consultation','احجز استشارة')); ?></a>
            </aside>
          </div>
        </div>
      </li>

      <li class="menu-item<?php echo esc_attr(smt_nav_active_class('products')); ?>"><a href="<?php echo esc_url(smt_products_url()); ?>"><?php echo esc_html(smt_t('Products','المنتجات')); ?></a></li>

      <li class="menu-item menu-item-has-children has-mega-menu smt-mega-parent<?php echo esc_attr(smt_nav_parent_active_class($industry_slugs)); ?>">
        <a href="<?php echo esc_url(smt_page_url('industries')); ?>"><?php echo esc_html(smt_t('Industries','القطاعات')); ?></a>
        <div class="sub-menu mega-menu smt-mega-menu smt-mega-menu-industries" role="group" aria-label="<?php echo esc_attr(smt_t('Industries menu','قائمة القطاعات')); ?>">
          <div class="industry-menu-grid">
            <?php
            $industries = [
              ['banking','fa-building-columns','Banking & Finance','البنوك والخدمات المالية'],
              ['government','fa-landmark','Government','الجهات الحكومية'],
              ['manufacturing','fa-industry','Manufacturing','التصنيع'],
              ['healthcare','fa-heart-pulse','Healthcare','الرعاية الصحية'],
              ['education','fa-graduation-cap','Education','التعليم'],
              ['retail','fa-store','Retail & Distribution','التجزئة والتوزيع'],
              ['logistics','fa-truck-fast','Logistics','الخدمات اللوجستية'],
              ['oil-gas','fa-oil-well','Oil & Gas','البترول والغاز'],
            ];
            foreach ($industries as [$slug,$icon,$en,$ar]) : ?>
              <a href="<?php echo esc_url(smt_page_url($slug)); ?>"><i class="fa-solid <?php echo esc_attr($icon); ?>" aria-hidden="true"></i><span><?php echo esc_html(smt_t($en,$ar)); ?></span></a>
            <?php endforeach; ?>
          </div>
        </div>
      </li>

      <li class="menu-item menu-item-has-children has-mega-menu smt-mega-parent<?php echo esc_attr(smt_nav_parent_active_class($resource_slugs)); ?>">
        <a href="<?php echo esc_url(smt_page_url('resources')); ?>"><?php echo esc_html(smt_t('Resources','الموارد')); ?></a>
        <div class="sub-menu mega-menu smt-mega-menu smt-mega-menu-resources" role="group" aria-label="<?php echo esc_attr(smt_t('Resources menu','قائمة الموارد')); ?>">
          <div class="mega-resources">
            <a href="<?php echo esc_url(smt_page_url('fleet-savings-calculator')); ?>"><i class="fa-solid fa-calculator"></i><span><strong><?php echo esc_html(smt_t('Fleet Savings Calculator','حاسبة وفر الطباعة')); ?></strong><small><?php echo esc_html(smt_t('Estimate your potential savings','اكتشف فرص التوفير المتوقعة')); ?></small></span></a>
            <a href="<?php echo esc_url(smt_page_url('company-profile')); ?>"><i class="fa-solid fa-file-pdf"></i><span><strong><?php echo esc_html(smt_t('Company Profile','الملف التعريفي للشركة')); ?></strong><small><?php echo esc_html(smt_t('Review our capabilities','تعرّف على قدراتنا وحلولنا')); ?></small></span></a>
            <a href="<?php echo esc_url(smt_page_url('technology-assessment')); ?>"><i class="fa-solid fa-clipboard-check"></i><span><strong><?php echo esc_html(smt_t('Technology Assessment','تقييم البيئة التقنية')); ?></strong><small><?php echo esc_html(smt_t('Build a fact-based roadmap','ابنِ خارطة طريق مبنية على البيانات')); ?></small></span></a>
            <a href="<?php echo esc_url(smt_page_url('faq-support')); ?>"><i class="fa-solid fa-circle-question"></i><span><strong><?php echo esc_html(smt_t('FAQs & Support','الأسئلة الشائعة والدعم')); ?></strong><small><?php echo esc_html(smt_t('Get answers and assistance','احصل على إجابات ومساعدة')); ?></small></span></a>
            <a href="<?php echo esc_url(smt_page_url('insights')); ?>"><i class="fa-solid fa-lightbulb"></i><span><strong><?php echo esc_html(smt_t('Insights','الرؤى والمقالات')); ?></strong><small><?php echo esc_html(smt_t('Practical technology guidance','رؤى عملية لاتخاذ قرارات أفضل')); ?></small></span></a>
            <a href="<?php echo esc_url(smt_page_url('downloads')); ?>"><i class="fa-solid fa-download"></i><span><strong><?php echo esc_html(smt_t('Downloads','مركز التحميل')); ?></strong><small><?php echo esc_html(smt_t('Company and solution resources','مواد الشركة والحلول')); ?></small></span></a>
          </div>
        </div>
      </li>
    </ul>
    <?php
}
