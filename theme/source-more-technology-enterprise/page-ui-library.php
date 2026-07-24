<?php
/**
 * Template Name: UI Component Library
 *
 * Internal preview page for the Source More Technology design system.
 *
 * @package Source_More_Technology
 */
get_header();
?>
<main id="primary" class="site-main ui-library-page">
	<section class="ui-page-hero">
		<div class="container">
			<span class="eyebrow"><?php esc_html_e( 'Design System v3.2', 'source-more-technology' ); ?></span>
			<h1><?php esc_html_e( 'Enterprise UI Component Library', 'source-more-technology' ); ?></h1>
			<p><?php esc_html_e( 'Reusable, responsive and bilingual-ready interface components for every Source More Technology page.', 'source-more-technology' ); ?></p>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<?php smt_section_heading( array(
				'kicker' => __( 'Foundation', 'source-more-technology' ),
				'title'  => __( 'Buttons and actions', 'source-more-technology' ),
				'text'   => __( 'Consistent actions with accessible focus states and responsive sizing.', 'source-more-technology' ),
			) ); ?>
			<div class="ui-button-row">
				<?php smt_button( array( 'label' => __( 'Primary Action', 'source-more-technology' ), 'url' => '#', 'style' => 'primary', 'icon' => 'fa-solid fa-arrow-right' ) ); ?>
				<?php smt_button( array( 'label' => __( 'Gold Action', 'source-more-technology' ), 'url' => '#', 'style' => 'gold' ) ); ?>
				<?php smt_button( array( 'label' => __( 'Outline Action', 'source-more-technology' ), 'url' => '#', 'style' => 'outline-blue' ) ); ?>
				<?php smt_button( array( 'label' => __( 'Text Action', 'source-more-technology' ), 'url' => '#', 'style' => 'text', 'icon' => 'fa-solid fa-arrow-right' ) ); ?>
			</div>
		</div>
	</section>

	<section class="section ui-surface-section">
		<div class="container">
			<?php smt_section_heading( array(
				'kicker'    => __( 'Reusable cards', 'source-more-technology' ),
				'title'     => __( 'Solutions, features and values', 'source-more-technology' ),
				'text'      => __( 'A shared card system keeps all service and industry pages visually consistent.', 'source-more-technology' ),
				'alignment' => 'center',
			) ); ?>
			<div class="ui-card-grid">
				<?php smt_icon_card( array( 'icon' => 'fa-solid fa-print', 'title' => __( 'Managed Print Services', 'source-more-technology' ), 'text' => __( 'Control costs, improve uptime and optimize your entire print environment.', 'source-more-technology' ), 'url' => home_url( '/managed-print-services/' ) ) ); ?>
				<?php smt_icon_card( array( 'icon' => 'fa-solid fa-cloud', 'title' => __( 'Cloud & Microsoft', 'source-more-technology' ), 'text' => __( 'Secure, scalable cloud services that support modern workplace productivity.', 'source-more-technology' ), 'url' => home_url( '/cloud-microsoft-solutions/' ) ) ); ?>
				<?php smt_icon_card( array( 'icon' => 'fa-solid fa-shield-halved', 'title' => __( 'Cybersecurity', 'source-more-technology' ), 'text' => __( 'Protect users, infrastructure and business-critical information.', 'source-more-technology' ), 'url' => home_url( '/cybersecurity/' ) ) ); ?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<?php smt_section_heading( array( 'kicker' => __( 'Business impact', 'source-more-technology' ), 'title' => __( 'Statistics cards', 'source-more-technology' ), 'alignment' => 'center' ) ); ?>
			<div class="ui-stat-grid">
				<article class="ui-stat-card"><strong>30%</strong><span><?php esc_html_e( 'Potential print-cost reduction', 'source-more-technology' ); ?></span></article>
				<article class="ui-stat-card"><strong>24/7</strong><span><?php esc_html_e( 'Service visibility', 'source-more-technology' ); ?></span></article>
				<article class="ui-stat-card"><strong>1</strong><span><?php esc_html_e( 'Trusted technology partner', 'source-more-technology' ); ?></span></article>
			</div>
		</div>
	</section>

	<section class="section ui-dark-section">
		<div class="container">
			<?php smt_section_heading( array( 'kicker' => __( 'Delivery approach', 'source-more-technology' ), 'title' => __( 'A clear implementation process', 'source-more-technology' ), 'text' => __( 'The timeline component can be reused across every solution page.', 'source-more-technology' ), 'alignment' => 'center', 'theme' => 'dark' ) ); ?>
			<ol class="ui-process-grid">
				<li><span>01</span><h3><?php esc_html_e( 'Discover', 'source-more-technology' ); ?></h3><p><?php esc_html_e( 'Assess current systems, challenges and business priorities.', 'source-more-technology' ); ?></p></li>
				<li><span>02</span><h3><?php esc_html_e( 'Design', 'source-more-technology' ); ?></h3><p><?php esc_html_e( 'Build a practical solution aligned with operational goals.', 'source-more-technology' ); ?></p></li>
				<li><span>03</span><h3><?php esc_html_e( 'Deploy', 'source-more-technology' ); ?></h3><p><?php esc_html_e( 'Implement, configure and train users with minimal disruption.', 'source-more-technology' ); ?></p></li>
				<li><span>04</span><h3><?php esc_html_e( 'Optimize', 'source-more-technology' ); ?></h3><p><?php esc_html_e( 'Measure performance and continuously improve outcomes.', 'source-more-technology' ); ?></p></li>
			</ol>
		</div>
	</section>

	<section class="section">
		<div class="container ui-content-narrow">
			<?php smt_section_heading( array( 'kicker' => __( 'Questions', 'source-more-technology' ), 'title' => __( 'FAQ accordion', 'source-more-technology' ), 'alignment' => 'center' ) ); ?>
			<div class="ui-faq" data-accordion>
				<article class="ui-faq-item"><h3><button type="button" aria-expanded="true"><?php esc_html_e( 'Are all components bilingual-ready?', 'source-more-technology' ); ?><i class="fa-solid fa-plus" aria-hidden="true"></i></button></h3><div class="ui-faq-answer"><p><?php esc_html_e( 'Yes. All interface text uses WordPress translation functions and the layout supports both LTR and RTL directions.', 'source-more-technology' ); ?></p></div></article>
				<article class="ui-faq-item"><h3><button type="button" aria-expanded="false"><?php esc_html_e( 'Can these components be reused on future pages?', 'source-more-technology' ); ?><i class="fa-solid fa-plus" aria-hidden="true"></i></button></h3><div class="ui-faq-answer" hidden><p><?php esc_html_e( 'Yes. The component helpers and CSS classes are designed for the homepage, solution pages, industries and resources.', 'source-more-technology' ); ?></p></div></article>
				<article class="ui-faq-item"><h3><button type="button" aria-expanded="false"><?php esc_html_e( 'Will the design work on mobile devices?', 'source-more-technology' ); ?><i class="fa-solid fa-plus" aria-hidden="true"></i></button></h3><div class="ui-faq-answer" hidden><p><?php esc_html_e( 'Yes. Every component includes tablet and mobile behavior, touch-friendly controls and readable spacing.', 'source-more-technology' ); ?></p></div></article>
			</div>
		</div>
	</section>

	<section class="ui-cta-section">
		<div class="container ui-cta-inner">
			<div><span class="section-kicker light"><?php esc_html_e( 'One Source. More Value.', 'source-more-technology' ); ?></span><h2><?php esc_html_e( 'Ready to build the next page?', 'source-more-technology' ); ?></h2><p><?php esc_html_e( 'The v3.2 component system is ready to support the Solutions page and future modules.', 'source-more-technology' ); ?></p></div>
			<div class="ui-cta-actions"><?php smt_button( array( 'label' => __( 'Talk to an Expert', 'source-more-technology' ), 'url' => home_url( '/contact/' ), 'style' => 'gold' ) ); ?></div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
