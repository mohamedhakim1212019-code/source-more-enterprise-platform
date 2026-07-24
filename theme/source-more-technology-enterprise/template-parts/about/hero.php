<?php if (!defined('ABSPATH')) exit; ?>
<section class="about-hero" aria-labelledby="about-hero-title">
  <div class="container about-hero-grid">
    <div class="about-hero-copy reveal">
      <span class="eyebrow">About Source More Technology</span>
      <h1 id="about-hero-title">Empowering Businesses Through Integrated Technology Solutions</h1>
      <p>We help organizations improve efficiency, reduce costs, strengthen security, and accelerate digital transformation through reliable, business-focused technology solutions.</p>
      <div class="hero-actions">
        <a class="btn btn-gold" href="<?php echo esc_url(home_url('/contact/')); ?>">Talk to an Expert</a>
        <a class="btn btn-outline" href="<?php echo esc_url(smt_page_url('solutions')); ?>">Explore Our Solutions</a>
      </div>
    </div>
    <div class="about-hero-visual reveal">
      <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/about-hero-placeholder.svg'); ?>" alt="Integrated business technology illustration" width="1920" height="850">
    </div>
  </div>
</section>
