<?php if (!defined('ABSPATH')) exit; ?>
<section class="section about-capabilities" aria-labelledby="capabilities-title">
  <div class="container">
    <div class="section-heading centered reveal"><span class="section-kicker">What We Do</span><h2 id="capabilities-title">Integrated solutions for the modern workplace</h2><p>Our portfolio combines technology, implementation expertise, and ongoing support through one coordinated relationship.</p></div>
    <div class="capability-grid">
      <?php
      $items = [
        ['fa-print','Managed Print Services'],['fa-building','Enterprise Printing'],['fa-gears','Office Automation'],['fa-folder-open','Document Management'],['fa-server','IT Infrastructure'],['fa-cloud','Cloud & Microsoft Solutions'],['fa-shield-halved','Network & Cybersecurity'],['fa-screwdriver-wrench','Annual Maintenance'],['fa-comments','IT Consulting'],['fa-arrow-trend-up','Digital Transformation']
      ];
      foreach ($items as $item) : ?>
        <article class="capability-item reveal"><i class="fa-solid <?php echo esc_attr($item[0]); ?>" aria-hidden="true"></i><span><?php echo esc_html($item[1]); ?></span></article>
      <?php endforeach; ?>
    </div>
    <div class="center-action reveal"><a class="btn btn-primary" href="<?php echo esc_url(smt_page_url('solutions')); ?>">View All Services</a></div>
  </div>
</section>
