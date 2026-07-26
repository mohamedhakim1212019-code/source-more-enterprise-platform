<?php
/**
 * Starter-site installer and reliable template routing.
 *
 * Creates the core pages on fresh WordPress installations and assigns the
 * correct templates without overwriting any editor content entered by users.
 */
if (!defined('ABSPATH')) exit;

function smt_starter_pages() {
    return [
        'home' => ['title' => 'Home', 'template' => 'default'],
        'about-us' => ['title' => 'About Us', 'template' => 'page-about.php'],
        'solutions' => ['title' => 'Solutions', 'template' => 'page-services.php'],
        'managed-print-services' => ['title' => 'Managed Print Services', 'template' => 'page-managed-print-services.php'],
        'enterprise-printing' => ['title' => 'Enterprise Printing', 'template' => 'page-enterprise-printing.php'],
        'document-management' => ['title' => 'Document Management', 'template' => 'page-document-management.php'],
        'it-infrastructure' => ['title' => 'IT Infrastructure', 'template' => 'page-it-infrastructure.php'],
        'cloud-microsoft-solutions' => ['title' => 'Cloud & Microsoft Solutions', 'template' => 'page-cloud-microsoft-solutions.php'],
        'cybersecurity' => ['title' => 'Cybersecurity', 'template' => 'page-cybersecurity.php'],
        'office-automation' => ['title' => 'Office Automation', 'template' => 'page-office-automation.php'],
        'annual-maintenance' => ['title' => 'Annual Maintenance Contracts', 'template' => 'page-annual-maintenance.php'],
        'industries' => ['title' => 'Industries', 'template' => 'page-industries.php'],
        'resources' => ['title' => 'Resources', 'template' => 'page-resources.php'],

        'banking' => ['title' => 'Banking & Financial Services', 'template' => 'page-industry.php'],
        'government' => ['title' => 'Government & Public Sector', 'template' => 'page-industry.php'],
        'manufacturing' => ['title' => 'Manufacturing', 'template' => 'page-industry.php'],
        'healthcare' => ['title' => 'Healthcare', 'template' => 'page-industry.php'],
        'education' => ['title' => 'Education', 'template' => 'page-industry.php'],
        'retail' => ['title' => 'Retail', 'template' => 'page-industry.php'],
        'logistics' => ['title' => 'Logistics & Distribution', 'template' => 'page-industry.php'],
        'oil-gas' => ['title' => 'Oil & Gas', 'template' => 'page-industry.php'],
        'insights' => ['title' => 'Insights & Articles', 'template' => 'page-insights.php'],
        'company-profile' => ['title' => 'Company Profile', 'template' => 'page-company-profile.php'],
        'technology-assessment' => ['title' => 'Technology Assessment', 'template' => 'page-technology-assessment.php'],
        'faq-support' => ['title' => 'FAQs & Support', 'template' => 'page-faq-support.php'],
        'downloads' => ['title' => 'Downloads Center', 'template' => 'page-downloads.php'],

        'fleet-savings-calculator' => ['title' => 'Fleet Savings Calculator', 'template' => 'page-fleet-savings-calculator.php'],
        'contact' => ['title' => 'Contact', 'template' => 'page-contact.php'],
    ];
}

function smt_install_starter_site() {
    if (!current_user_can('manage_options') && !wp_doing_cron()) return;

    $page_ids = [];
    foreach (smt_starter_pages() as $slug => $config) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if (!$page) {
            $page_id = wp_insert_post([
                'post_type' => 'page',
                'post_status' => 'publish',
                'post_title' => $config['title'],
                'post_name' => $slug,
                'post_content' => '',
                'comment_status' => 'closed',
            ], true);
            if (is_wp_error($page_id)) continue;
        } else {
            $page_id = (int) $page->ID;
        }

        $page_ids[$slug] = $page_id;
        if ($config['template'] !== 'default') {
            update_post_meta($page_id, '_wp_page_template', $config['template']);
        }
    }

    if (!empty($page_ids['home'])) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_ids['home']);
    }

    // Insights uses the bilingual theme template so it can render language-aware articles.
    update_option('page_for_posts', 0);
    update_option('smt_theme_content_version', '7.6.0');
    flush_rewrite_rules(false);
}
add_action('after_switch_theme', 'smt_install_starter_site');

// Also runs once after an in-place ZIP replacement of an already active theme.
add_action('admin_init', function () {
    if (!current_user_can('manage_options')) return;
    if (get_option('smt_theme_content_version') !== '7.6.0') {
        smt_install_starter_site();
    }
});

/**
 * Route known page slugs to bundled layouts even when a page template was not
 * manually selected. This fixes blank pages on fresh WordPress installations.
 */
add_filter('template_include', function ($template) {
    if (!is_page()) return $template;

    $object = get_queried_object();
    if (!$object || empty($object->post_name)) return $template;

    $map = [
        'about' => 'page-about.php',
        'about-us' => 'page-about.php',
        'services' => 'page-services.php',
        'solutions' => 'page-services.php',
        'managed-print-services' => 'page-managed-print-services.php',
        'enterprise-printing' => 'page-enterprise-printing.php',
        'document-management' => 'page-document-management.php',
        'it-infrastructure' => 'page-it-infrastructure.php',
        'cloud-microsoft-solutions' => 'page-cloud-microsoft-solutions.php',
        'cybersecurity' => 'page-cybersecurity.php',
        'office-automation' => 'page-office-automation.php',
        'annual-maintenance' => 'page-annual-maintenance.php',
        'industries' => 'page-industries.php',
        'resources' => 'page-resources.php',
        'company-profile' => 'page-company-profile.php',
        'technology-assessment' => 'page-technology-assessment.php',
        'faq-support' => 'page-faq-support.php',
        'insights' => 'page-insights.php',
        'downloads' => 'page-downloads.php',
        'banking' => 'page-industry.php', 'government' => 'page-industry.php', 'manufacturing' => 'page-industry.php', 'healthcare' => 'page-industry.php', 'education' => 'page-industry.php', 'retail' => 'page-industry.php', 'logistics' => 'page-industry.php', 'oil-gas' => 'page-industry.php',
        'fleet-savings-calculator' => 'page-fleet-savings-calculator.php',
        'contact' => 'page-contact.php',
    ];

    if (isset($map[$object->post_name])) {
        $candidate = get_template_directory() . '/' . $map[$object->post_name];
        if (is_readable($candidate)) return $candidate;
    }
    return $template;
}, 99);
