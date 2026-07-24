<?php
if (!defined('ABSPATH')) exit;

/**
 * Gutenberg-first starter content for Enterprise v7.1.
 * Existing editor content is never overwritten.
 */
function smt_v71_block_content(string $slug): string {
    $contact = esc_url(smt_page_url('contact'));
    $solutions = esc_url(smt_page_url('solutions'));
    $products = esc_url(get_post_type_archive_link('smt_product') ?: smt_page_url('products'));
    $calculator = esc_url(smt_page_url('fleet-savings-calculator'));

    $pages = [];
    $pages['home'] = <<<HTML
<!-- wp:group {"align":"full","className":"smt-block-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull smt-block-hero"><!-- wp:paragraph {"className":"smt-eyebrow"} --><p class="smt-eyebrow">ONE SOURCE. MORE VALUE.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"xx-large"} --><h1 class="wp-block-heading has-xx-large-font-size">Technology that makes business work better.</h1><!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"large"} --><p class="has-large-font-size">Source More Technology delivers managed print, workplace technology, IT infrastructure, cloud, cybersecurity, and business software through one accountable partner.</p><!-- /wp:paragraph -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{$solutions}">See the Solutions</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="{$contact}">Talk to an Expert</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"smt-block-section","layout":{"type":"constrained"}} --><div class="wp-block-group alignwide smt-block-section"><!-- wp:heading --><h2 class="wp-block-heading">Integrated technology. Measurable business outcomes.</h2><!-- /wp:heading --><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Managed Print Services</h3><!-- /wp:heading --><p>Control print cost, improve uptime, and manage your fleet with clear service governance.</p></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">IT & Cloud</h3><!-- /wp:heading --><p>Modern infrastructure, Microsoft solutions, cybersecurity, networking, and support.</p></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Business Software</h3><!-- /wp:heading --><p>Print management, document workflows, automation, and secure digital operations.</p></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->
<!-- wp:group {"align":"full","className":"smt-block-cta","layout":{"type":"constrained"}} --><div class="wp-block-group alignfull smt-block-cta"><!-- wp:heading --><h2 class="wp-block-heading">Find the right solution for your organization.</h2><!-- /wp:heading --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{$products}">Browse Products</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="{$calculator}">Calculate Savings</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->
HTML;

    $pages['about-us'] = <<<HTML
<!-- wp:group {"align":"full","className":"smt-block-hero smt-block-hero--compact","layout":{"type":"constrained"}} --><div class="wp-block-group alignfull smt-block-hero smt-block-hero--compact"><!-- wp:paragraph {"className":"smt-eyebrow"} --><p class="smt-eyebrow">ABOUT SOURCE MORE</p><!-- /wp:paragraph --><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">One accountable technology partner.</h1><!-- /wp:heading --><!-- wp:paragraph {"fontSize":"large"} --><p class="has-large-font-size">We help organizations simplify technology sourcing, improve operational performance, and create more value from every investment.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"smt-block-section","layout":{"type":"constrained"}} --><div class="wp-block-group alignwide smt-block-section"><!-- wp:heading --><h2 class="wp-block-heading">Who we are</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Source More Technology is an Egyptian B2B technology and business-solutions company serving enterprises, government organizations, factories, and growing businesses. Our portfolio combines managed print services, enterprise printing, office automation, document management, IT infrastructure, cloud, cybersecurity, software, and annual maintenance.</p><!-- /wp:paragraph --><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Mission</h3><!-- /wp:heading --><p>Deliver practical, reliable, and cost-effective technology solutions that improve efficiency and measurable business performance.</p></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Vision</h3><!-- /wp:heading --><p>Become a trusted integrated technology partner known for accountability, service excellence, and long-term customer value.</p></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:heading --><h2 class="wp-block-heading">Why Source More</h2><!-- /wp:heading --><!-- wp:list --><ul><li>One source for integrated technology requirements</li><li>Consultative, business-first approach</li><li>Flexible outright sale, subscription, rental, and managed-service models</li><li>Strong governance, reporting, and after-sales support</li></ul><!-- /wp:list --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{$contact}">Start a Conversation</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->
HTML;

    $pages['solutions'] = <<<HTML
<!-- wp:group {"align":"full","className":"smt-block-hero smt-block-hero--compact","layout":{"type":"constrained"}} --><div class="wp-block-group alignfull smt-block-hero smt-block-hero--compact"><!-- wp:paragraph {"className":"smt-eyebrow"} --><p class="smt-eyebrow">SOLUTIONS</p><!-- /wp:paragraph --><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Integrated solutions for the modern workplace.</h1><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:group {"align":"wide","className":"smt-block-section","layout":{"type":"constrained"}} --><div class="wp-block-group alignwide smt-block-section"><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><h3>Managed Print Services</h3><p>Fleet optimization, service governance, supplies automation, reporting, and cost control.</p></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><h3>Enterprise Printing</h3><p>A4 and A3 printers, multifunction devices, production printing, scanning, and finishing.</p></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><h3>Document Management</h3><p>Capture, OCR, workflow, archiving, search, security, and digital document processes.</p></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><h3>IT Infrastructure</h3><p>Networks, servers, storage, endpoint computing, backup, and managed support.</p></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><h3>Cloud & Microsoft</h3><p>Microsoft 365, Azure, collaboration, identity, migration, and cloud operations.</p></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><h3>Cybersecurity</h3><p>Security assessment, endpoint protection, network security, identity, and resilience.</p></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->
HTML;

    $pages['products'] = <<<HTML
<!-- wp:group {"align":"full","className":"smt-block-hero smt-block-hero--compact","layout":{"type":"constrained"}} --><div class="wp-block-group alignfull smt-block-hero smt-block-hero--compact"><!-- wp:paragraph {"className":"smt-eyebrow"} --><p class="smt-eyebrow">OUTRIGHT SALES & BUSINESS SOFTWARE</p><!-- /wp:paragraph --><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Product Center</h1><!-- /wp:heading --><!-- wp:paragraph {"fontSize":"large"} --><p class="has-large-font-size">Explore business hardware, software, subscriptions, and integrated workplace technology.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:shortcode -->[smtp_products]<!-- /wp:shortcode -->
HTML;

    $pages['industries'] = '<!-- wp:group {"align":"full","className":"smt-block-hero smt-block-hero--compact","layout":{"type":"constrained"}} --><div class="wp-block-group alignfull smt-block-hero smt-block-hero--compact"><h1>Industries</h1><p>Technology solutions aligned to sector-specific operating, security, and compliance needs.</p></div><!-- /wp:group --><!-- wp:group {"align":"wide","className":"smt-block-section"} --><div class="wp-block-group alignwide smt-block-section"><!-- wp:columns --><div class="wp-block-columns"><div class="wp-block-column"><h3>Banking & Financial Services</h3><p>Secure document workflows, controlled printing, infrastructure, and compliance support.</p></div><div class="wp-block-column"><h3>Manufacturing</h3><p>Reliable devices, barcode and labeling, production workflows, and multi-site support.</p></div><div class="wp-block-column"><h3>Government</h3><p>Secure, scalable technology with governance, reporting, and service accountability.</p></div></div><!-- /wp:columns --></div><!-- /wp:group -->';
    $pages['resources'] = '<!-- wp:group {"align":"full","className":"smt-block-hero smt-block-hero--compact","layout":{"type":"constrained"}} --><div class="wp-block-group alignfull smt-block-hero smt-block-hero--compact"><h1>Resources</h1><p>Insights, guides, downloads, and practical tools for better technology decisions.</p></div><!-- /wp:group --><!-- wp:group {"align":"wide","className":"smt-block-section"} --><div class="wp-block-group alignwide smt-block-section"><h2>Knowledge Center</h2><p>Publish articles, case studies, brochures, white papers, and product datasheets from WordPress.</p></div><!-- /wp:group -->';
    return $pages[$slug] ?? '';
}

function smt_v71_migrate_editable_pages(): void {
    if (get_option('smt_v71_content_migrated')) return;
    $map = [
        'home' => 'Home', 'about-us' => 'About Us', 'solutions' => 'Solutions',
        'products' => 'Products', 'industries' => 'Industries', 'resources' => 'Resources'
    ];
    foreach ($map as $slug => $title) {
        $page = get_page_by_path($slug);
        $id = $page ? (int)$page->ID : smt_v7_ensure_page($title, $slug);
        if (!$id || trim((string)get_post_field('post_content', $id)) !== '') continue;
        $content = smt_v71_block_content($slug);
        if ($content !== '') wp_update_post(['ID'=>$id,'post_content'=>$content]);
    }
    update_option('smt_v71_content_migrated', 1, false);
}
add_action('admin_init', 'smt_v71_migrate_editable_pages', 30);

/** Allow administrators to re-seed only empty pages. */
add_action('admin_menu', function(){
    add_theme_page('Source More Content Setup','Content Setup','manage_options','smt-content-setup','smt_v71_content_setup_page');
});
function smt_v71_content_setup_page(): void {
    if (!current_user_can('manage_options')) return;
    if (isset($_POST['smt_seed_content']) && check_admin_referer('smt_seed_content')) {
        delete_option('smt_v71_content_migrated'); smt_v71_migrate_editable_pages();
        echo '<div class="notice notice-success"><p>Editable starter content was added to empty core pages.</p></div>';
    }
    echo '<div class="wrap"><h1>Source More Content Setup</h1><p>This tool adds Gutenberg blocks to empty core pages. Existing content is never overwritten.</p><form method="post">';
    wp_nonce_field('smt_seed_content'); submit_button('Populate Empty Pages','primary','smt_seed_content'); echo '</form></div>';
}
