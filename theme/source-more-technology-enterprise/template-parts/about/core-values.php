<?php if (!defined('ABSPATH')) exit; ?>
<section class="section section-values" aria-labelledby="values-title">
  <div class="container">
    <div class="section-heading reveal"><div><span class="section-kicker">What Guides Us</span><h2 id="values-title">Our Core Values</h2></div><p>These principles shape how we advise customers, deliver projects, and build long-term relationships.</p></div>
    <div class="value-grid">
      <?php
      $values = [
        ['fa-trophy','Customer Success','We begin with customer objectives and measure success by the value our solutions create.'],
        ['fa-wand-magic-sparkles','Innovation','We embrace practical technologies that improve performance and prepare organizations for growth.'],
        ['fa-scale-balanced','Integrity','We work with honesty, transparency, accountability, and professional responsibility.'],
        ['fa-star','Excellence','We maintain high standards from consultation and deployment through ongoing support.'],
        ['fa-people-group','Partnership','We work as an extension of our customers’ teams, not merely as a supplier.'],
        ['fa-arrows-rotate','Continuous Improvement','We continually review and optimize solutions to maximize long-term value.'],
      ];
      foreach ($values as $value) : ?>
        <article class="value-card reveal"><i class="fa-solid <?php echo esc_attr($value[0]); ?>" aria-hidden="true"></i><h3><?php echo esc_html($value[1]); ?></h3><p><?php echo esc_html($value[2]); ?></p></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
