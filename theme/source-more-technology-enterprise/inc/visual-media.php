<?php
/**
 * Visual media library and admin controls.
 *
 * Bundled WebP assets provide an immediate polished experience. Administrators
 * may override any visual with a WordPress Media Library image without editing
 * theme files. The same visual is used across English and Arabic pages.
 *
 * @package Source_More_Technology
 */
if (!defined('ABSPATH')) exit;

function smt_visual_media_catalog(): array {
    return [
        'homepage_hero' => [
            'label' => 'Homepage hero',
            'file' => 'homepage-hero.webp',
            'mobile' => 'homepage-hero-mobile.webp',
            'description' => 'Primary homepage image showing integrated print, cloud, security, and workplace technology.',
        ],
        'homepage_about' => [
            'label' => 'Homepage company section',
            'file' => 'consultation.webp',
            'mobile' => 'consultation-mobile.webp',
            'description' => 'Consultative business conversation used in the homepage company section.',
        ],
        'about_hero' => [
            'label' => 'About page hero',
            'file' => 'leadership-team.webp',
            'mobile' => 'leadership-team-mobile.webp',
            'description' => 'Leadership and collaboration visual for the About page.',
        ],
        'about_story' => [
            'label' => 'About page company story',
            'file' => 'consultation.webp',
            'mobile' => 'consultation-mobile.webp',
            'description' => 'Advisory meeting visual for the company story section.',
        ],
        'solutions_hero' => [
            'label' => 'Solutions hub hero',
            'file' => 'homepage-hero.webp',
            'mobile' => 'homepage-hero-mobile.webp',
            'description' => 'Integrated technology visual for the Solutions hub.',
        ],
        'managed_print' => [
            'label' => 'Print, document, and workplace solutions',
            'file' => 'managed-print.webp',
            'mobile' => 'managed-print-mobile.webp',
            'description' => 'Managed print and office technology environment.',
        ],
        'infrastructure_cloud' => [
            'label' => 'Infrastructure and cloud solutions',
            'file' => 'infrastructure-cloud.webp',
            'mobile' => 'infrastructure-cloud-mobile.webp',
            'description' => 'Modern infrastructure, cloud, server, and workplace technology environment.',
        ],
        'cybersecurity' => [
            'label' => 'Cybersecurity solutions',
            'file' => 'cybersecurity.webp',
            'mobile' => 'cybersecurity-mobile.webp',
            'description' => 'Cybersecurity operations and monitoring environment.',
        ],
        'industries_hero' => [
            'label' => 'Industries pages',
            'file' => 'leadership-team.webp',
            'mobile' => 'leadership-team-mobile.webp',
            'description' => 'Executive team and sector-focused collaboration visual.',
        ],
        'resources_hero' => [
            'label' => 'Resources pages',
            'file' => 'consultation.webp',
            'mobile' => 'consultation-mobile.webp',
            'description' => 'Advisory and knowledge-sharing visual.',
        ],
        'products_hero' => [
            'label' => 'Product Center',
            'file' => 'product-center.webp',
            'mobile' => 'product-center-mobile.webp',
            'description' => 'Technology showroom with print, endpoint, server, and workplace products.',
        ],
        'contact_hero' => [
            'label' => 'Contact page',
            'file' => 'consultation.webp',
            'mobile' => 'consultation-mobile.webp',
            'description' => 'Professional consultation visual for the contact experience.',
        ],
    ];
}

function smt_visual_media_options(): array {
    return wp_parse_args((array) get_option('smt_visual_media', []), array_fill_keys(array_keys(smt_visual_media_catalog()), ''));
}

function smt_visual_media_url(string $key, bool $mobile = false): string {
    $catalog = smt_visual_media_catalog();
    if (!isset($catalog[$key])) return '';
    $options = smt_visual_media_options();
    if (!empty($options[$key])) return esc_url_raw($options[$key]);
    $filename = $mobile ? ($catalog[$key]['mobile'] ?? $catalog[$key]['file']) : $catalog[$key]['file'];
    return get_template_directory_uri() . '/assets/images/visuals/' . ltrim($filename, '/');
}

function smt_solution_visual_key(string $slug): string {
    $map = [
        'managed-print-services' => 'managed_print',
        'enterprise-printing' => 'managed_print',
        'document-management' => 'managed_print',
        'office-automation' => 'managed_print',
        'annual-maintenance' => 'managed_print',
        'it-infrastructure' => 'infrastructure_cloud',
        'cloud-microsoft-solutions' => 'infrastructure_cloud',
        'cybersecurity' => 'cybersecurity',
        'it-consulting' => 'industries_hero',
        'digital-transformation' => 'industries_hero',
    ];
    return $map[$slug] ?? 'solutions_hero';
}

function smt_visual_picture(string $key, string $alt, array $args = []): void {
    $args = wp_parse_args($args, [
        'class' => 'smt-visual-image',
        'loading' => 'lazy',
        'fetchpriority' => '',
        'width' => 1600,
        'height' => 900,
        'sizes' => '(max-width: 700px) 100vw, 50vw',
    ]);
    $desktop = smt_visual_media_url($key, false);
    $mobile = smt_visual_media_url($key, true);
    if (!$desktop) return;

    $options = smt_visual_media_options();
    $custom = !empty($options[$key]);
    ?>
    <picture class="smt-visual-picture">
        <?php if (!$custom && $mobile) : ?>
            <source media="(max-width: 700px)" srcset="<?php echo esc_url($mobile); ?>">
        <?php endif; ?>
        <img
            class="<?php echo esc_attr($args['class']); ?>"
            src="<?php echo esc_url($desktop); ?>"
            alt="<?php echo esc_attr($alt); ?>"
            width="<?php echo esc_attr((string) $args['width']); ?>"
            height="<?php echo esc_attr((string) $args['height']); ?>"
            loading="<?php echo esc_attr($args['loading']); ?>"
            decoding="async"
            sizes="<?php echo esc_attr($args['sizes']); ?>"
            <?php if ($args['fetchpriority']) : ?>fetchpriority="<?php echo esc_attr($args['fetchpriority']); ?>"<?php endif; ?>
        >
    </picture>
    <?php
}


add_action('wp_head', function () {
    if (!is_front_page()) return;
    $url = smt_visual_media_url('homepage_hero', false);
    if ($url) printf('<link rel="preload" as="image" href="%s" fetchpriority="high">\n', esc_url($url));
}, 2);

add_action('admin_init', function () {
    register_setting('smt_visual_media_group', 'smt_visual_media', [
        'type' => 'array',
        'sanitize_callback' => function ($input) {
            $out = [];
            foreach (smt_visual_media_catalog() as $key => $item) {
                $out[$key] = isset($input[$key]) ? esc_url_raw((string) $input[$key]) : '';
            }
            return $out;
        },
        'default' => [],
    ]);
});

add_action('admin_menu', function () {
    add_theme_page(
        __('Visual Media', 'source-more-technology'),
        __('Visual Media', 'source-more-technology'),
        'edit_theme_options',
        'smt-visual-media',
        'smt_visual_media_admin_page'
    );
});

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'appearance_page_smt-visual-media') return;
    wp_enqueue_media();
    wp_enqueue_script('smt-visual-media-admin', get_template_directory_uri() . '/assets/js/visual-media-admin.js', ['jquery'], '7.8.0', true);
    wp_enqueue_style('smt-visual-media-admin', get_template_directory_uri() . '/assets/css/visual-media-admin.css', [], '7.8.0');
});

function smt_visual_media_admin_page(): void {
    if (!current_user_can('edit_theme_options')) return;
    $catalog = smt_visual_media_catalog();
    $options = smt_visual_media_options();
    ?>
    <div class="wrap smt-visual-admin">
        <h1><?php esc_html_e('Source More Visual Media', 'source-more-technology'); ?></h1>
        <p><?php esc_html_e('The theme includes optimized WebP visuals. Override any image with your Media Library while keeping the same image across English and Arabic pages.', 'source-more-technology'); ?></p>
        <form method="post" action="options.php">
            <?php settings_fields('smt_visual_media_group'); ?>
            <div class="smt-visual-admin-grid">
                <?php foreach ($catalog as $key => $item) :
                    $current = $options[$key] ?: smt_visual_media_url($key);
                ?>
                    <section class="smt-visual-admin-card" data-visual-card>
                        <div class="smt-visual-preview"><img src="<?php echo esc_url($current); ?>" alt=""></div>
                        <div class="smt-visual-admin-copy">
                            <h2><?php echo esc_html($item['label']); ?></h2>
                            <p><?php echo esc_html($item['description']); ?></p>
                            <input type="url" class="regular-text smt-visual-url" name="smt_visual_media[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($options[$key]); ?>" placeholder="<?php echo esc_attr(smt_visual_media_url($key)); ?>">
                            <div class="smt-visual-actions">
                                <button type="button" class="button button-secondary smt-select-image"><?php esc_html_e('Choose image', 'source-more-technology'); ?></button>
                                <button type="button" class="button smt-reset-image" data-default="<?php echo esc_url(smt_visual_media_url($key)); ?>"><?php esc_html_e('Use bundled default', 'source-more-technology'); ?></button>
                            </div>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>
            <?php submit_button(__('Save Visual Media', 'source-more-technology')); ?>
        </form>
    </div>
    <?php
}
