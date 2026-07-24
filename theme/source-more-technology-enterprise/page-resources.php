<?php /* Template Name: Resources */ if (!defined('ABSPATH')) exit; get_header();
$resources = [
 ['fa-calculator','Fleet Savings Calculator','Estimate the potential financial impact of optimizing your print fleet.','fleet-savings-calculator','Open calculator'],
 ['fa-file-pdf','Company Profile','Review our capabilities, solution portfolio, delivery approach, and areas of expertise.','contact','Request profile'],
 ['fa-clipboard-check','Technology Assessment','Start a structured review of your print, IT, cloud, security, or workflow environment.','contact','Book assessment'],
 ['fa-circle-question','Frequently Asked Questions','Get clear answers about our services, engagement model, support, and implementation process.','contact','Ask a question'],
 ['fa-lightbulb','Business Guides','Practical guidance for technology planning, cost reduction, security, and digital transformation.','contact','Request a guide'],
 ['fa-headset','Support & Service','Contact our team for technical support, maintenance, service inquiries, or account assistance.','contact','Contact support'],
]; ?>
<main class="resources-page">
<section class="ui-page-hero"><div class="container"><span class="eyebrow">Knowledge & tools</span><h1>Resources for better technology decisions</h1><p>Use our tools, assessments, guides, and expert support to evaluate opportunities and plan your next improvement initiative.</p></div></section>
<section class="section"><div class="container"><?php smt_section_heading(['kicker'=>'Resource center','title'=>'Start with the information you need','text'=>'Choose a resource below or contact our consultants for guidance tailored to your organization.','alignment'=>'center']); ?><div class="resource-detail-grid">
<?php foreach($resources as $resource): ?><article class="resource-detail-card reveal"><div class="resource-detail-icon"><i class="fa-solid <?php echo esc_attr($resource[0]); ?>"></i></div><h2><?php echo esc_html($resource[1]); ?></h2><p><?php echo esc_html($resource[2]); ?></p><a href="<?php echo esc_url(smt_page_url($resource[3])); ?>"><?php echo esc_html($resource[4]); ?> <i class="fa-solid fa-arrow-right"></i></a></article><?php endforeach; ?>
</div></div></section>
<section class="section ui-surface-section"><div class="container resource-feature"><div><span class="section-kicker">Featured tool</span><h2>Understand the savings opportunity in your print environment</h2><p>Our Fleet Savings Calculator helps start a fact-based conversation around device consolidation, operating cost, support, and productivity.</p><a class="btn btn-primary" href="<?php echo esc_url(smt_page_url('fleet-savings-calculator')); ?>">Explore the Calculator</a></div><div class="resource-feature-visual"><i class="fa-solid fa-chart-column"></i><strong>Assessment-led decisions</strong><span>Move from assumptions to measurable opportunities.</span></div></div></section>
<section class="ui-cta-section"><div class="container ui-cta-inner"><div><span class="section-kicker light">Need specific information?</span><h2>Speak with a Source More Technology consultant.</h2><p>Tell us what you are evaluating, and we will direct you to the right solution, resource, or next step.</p></div><div class="ui-cta-actions"><a class="btn btn-gold" href="<?php echo esc_url(smt_page_url('contact')); ?>">Contact Our Experts</a></div></div></section>
</main><?php get_footer(); ?>
