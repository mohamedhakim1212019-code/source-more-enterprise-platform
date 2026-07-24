<?php if (!defined('ABSPATH')) exit; ?>
<section class="section section-choose" aria-labelledby="choose-title">
  <div class="container">
    <div class="section-heading reveal"><div><span class="section-kicker light">Our Difference</span><h2 id="choose-title">Why choose Source More Technology?</h2></div><p>We combine commercial understanding, technical expertise, and accountable local support.</p></div>
    <div class="choose-grid">
      <?php
      $items = [
        ['01','One Source. More Value.','One coordinated partner across multiple technology requirements.'],
        ['02','Business-First Approach','We define the business challenge before recommending technology.'],
        ['03','Enterprise Expertise','Practical, scalable solutions built for demanding operational environments.'],
        ['04','Cost Optimization','Recommendations designed to reduce total cost and maximize return.'],
        ['05','Reliable Support','Responsive service, preventive maintenance, and long-term commitment.'],
        ['06','Scalable Solutions','Technology that evolves with your organization and future requirements.'],
      ];
      foreach ($items as $item) : ?>
        <article class="choose-card reveal"><span><?php echo esc_html($item[0]); ?></span><h3><?php echo esc_html($item[1]); ?></h3><p><?php echo esc_html($item[2]); ?></p></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
