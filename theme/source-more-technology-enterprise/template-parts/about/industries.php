<?php if (!defined('ABSPATH')) exit; ?>
<section class="section section-industries" aria-labelledby="industries-title">
  <div class="container">
    <div class="section-heading centered reveal"><span class="section-kicker">Industries We Serve</span><h2 id="industries-title">Technology aligned with real operational needs</h2><p>We support organizations across diverse sectors, adapting each solution to its users, workflows, security requirements, and growth plans.</p></div>
    <div class="industry-grid">
      <?php
      $industries = [
        ['fa-building-columns','Banking & Finance'],['fa-landmark','Government'],['fa-heart-pulse','Healthcare'],['fa-industry','Manufacturing'],['fa-graduation-cap','Education'],['fa-cart-shopping','Retail & Distribution'],['fa-truck-fast','Logistics'],['fa-oil-well','Oil & Gas'],['fa-hotel','Hospitality'],['fa-briefcase','Corporate Enterprises'],['fa-store','SMEs']
      ];
      foreach ($industries as $industry) : ?>
        <div class="industry-pill reveal"><i class="fa-solid <?php echo esc_attr($industry[0]); ?>" aria-hidden="true"></i><span><?php echo esc_html($industry[1]); ?></span></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
