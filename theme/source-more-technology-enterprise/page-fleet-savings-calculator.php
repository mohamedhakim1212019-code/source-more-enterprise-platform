<?php /* Template Name: Fleet Savings Calculator */
if (!defined('ABSPATH')) exit;
get_header();
?>
<main class="fleet-calculator-page">
<section class="calculator-page-hero">
  <div class="container calculator-page-hero-grid">
    <div>
      <span class="eyebrow"><?php echo esc_html(smt_t('Free Fleet Cost Assessment','تقييم مجاني لتكلفة أسطول الطباعة')); ?></span>
      <h1><?php echo esc_html(smt_t('Calculate your potential print savings.','احسب التوفير المحتمل في تكاليف الطباعة.')); ?></h1>
      <p><?php echo esc_html(smt_t('Use your current monthly print volumes and operating costs to generate an immediate high-level savings estimate.','استخدم أحجام الطباعة الشهرية وتكاليف التشغيل الحالية للحصول على تقدير فوري للتوفير المحتمل.')); ?></p>
    </div>
    <div class="calculator-hero-points">
      <span><i class="fa-solid fa-clock"></i><?php echo esc_html(smt_t('Takes about 2 minutes','يستغرق نحو دقيقتين')); ?></span>
      <span><i class="fa-solid fa-lock"></i><?php echo esc_html(smt_t('No registration required','لا يلزم التسجيل')); ?></span>
      <span><i class="fa-solid fa-chart-line"></i><?php echo esc_html(smt_t('Instant indicative result','نتيجة تقديرية فورية')); ?></span>
    </div>
  </div>
</section>

<section class="section calculator-workspace-section">
  <div class="container">
    <form class="fleet-calculator" id="fleet-calculator" novalidate>
      <div class="calculator-input-panel">
        <div class="calculator-panel-heading">
          <span>01</span><div><h2><?php echo esc_html(smt_t('Your current print environment','بيئة الطباعة الحالية')); ?></h2><p><?php echo esc_html(smt_t('Use approximate figures if exact data is not available.','استخدم أرقاماً تقريبية إذا لم تتوفر بيانات دقيقة.')); ?></p></div>
        </div>
        <div class="calculator-fields">
          <label><span><?php echo esc_html(smt_t('Number of printers and MFPs','عدد الطابعات والأجهزة متعددة الوظائف')); ?></span><input type="number" id="calc-devices" min="1" step="1" value="20" required></label>
          <label><span><?php echo esc_html(smt_t('Monthly mono pages','عدد صفحات الأبيض والأسود شهرياً')); ?></span><input type="number" id="calc-mono-pages" min="0" step="100" value="100000" required></label>
          <label><span><?php echo esc_html(smt_t('Monthly color pages','عدد صفحات الألوان شهرياً')); ?></span><input type="number" id="calc-color-pages" min="0" step="100" value="10000" required></label>
          <label><span><?php echo esc_html(smt_t('Mono cost per page (EGP)','تكلفة صفحة الأبيض والأسود (جنيه)')); ?></span><input type="number" id="calc-mono-cpp" min="0" step="0.01" value="0.35" required></label>
          <label><span><?php echo esc_html(smt_t('Color cost per page (EGP)','تكلفة صفحة الألوان (جنيه)')); ?></span><input type="number" id="calc-color-cpp" min="0" step="0.01" value="3.50" required></label>
          <label><span><?php echo esc_html(smt_t('Monthly service, rental and maintenance (EGP)','الخدمة والإيجار والصيانة شهرياً (جنيه)')); ?></span><input type="number" id="calc-fixed-cost" min="0" step="100" value="25000" required></label>
        </div>
        <div class="optimization-control">
          <div><label for="calc-saving-rate"><?php echo esc_html(smt_t('Estimated optimization potential','نسبة التحسين المتوقعة')); ?></label><p><?php echo esc_html(smt_t('A typical initial planning range is 10%–30%. Select a conservative assumption.','النطاق المبدئي المعتاد للتخطيط بين 10% و30%. اختر افتراضاً متحفظاً.')); ?></p></div>
          <div class="range-control"><output id="calc-rate-output">20%</output><input type="range" id="calc-saving-rate" min="5" max="35" step="1" value="20"></div>
        </div>
        <button class="btn btn-gold calculator-submit" type="submit"><i class="fa-solid fa-calculator"></i><?php echo esc_html(smt_t('Calculate My Savings','احسب التوفير')); ?></button>
      </div>

      <aside class="calculator-results-panel" aria-live="polite">
        <div class="results-label"><?php echo esc_html(smt_t('Your indicative result','نتيجتك التقديرية')); ?></div>
        <div class="main-result"><span><?php echo esc_html(smt_t('Potential annual savings','التوفير السنوي المحتمل')); ?></span><strong id="result-annual-savings">EGP 0</strong></div>
        <div class="result-grid">
          <article><i class="fa-solid fa-wallet"></i><span><?php echo esc_html(smt_t('Current annual cost','التكلفة السنوية الحالية')); ?></span><strong id="result-current-cost">EGP 0</strong></article>
          <article><i class="fa-solid fa-arrow-trend-down"></i><span><?php echo esc_html(smt_t('Optimized annual cost','التكلفة السنوية بعد التحسين')); ?></span><strong id="result-optimized-cost">EGP 0</strong></article>
          <article><i class="fa-solid fa-calendar-days"></i><span><?php echo esc_html(smt_t('Three-year savings','التوفير خلال 3 سنوات')); ?></span><strong id="result-three-year">EGP 0</strong></article>
          <article><i class="fa-solid fa-print"></i><span><?php echo esc_html(smt_t('Annual pages','عدد الصفحات سنوياً')); ?></span><strong id="result-annual-pages">0</strong></article>
        </div>
        <div class="result-breakdown">
          <div><span><?php echo esc_html(smt_t('Page production cost','تكلفة إنتاج الصفحات')); ?></span><strong id="result-page-cost">EGP 0</strong></div>
          <div><span><?php echo esc_html(smt_t('Service and fixed cost','تكلفة الخدمة والتكاليف الثابتة')); ?></span><strong id="result-fixed-cost">EGP 0</strong></div>
          <div><span><?php echo esc_html(smt_t('Average annual cost per device','متوسط التكلفة السنوية لكل جهاز')); ?></span><strong id="result-device-cost">EGP 0</strong></div>
        </div>
        <a class="btn btn-white result-cta" href="<?php echo esc_url(smt_page_url('contact')); ?>"><?php echo esc_html(smt_t('Request a Professional Fleet Assessment','اطلب تقييماً احترافياً لأسطول الطباعة')); ?></a>
        <p class="result-note"><?php echo esc_html(smt_t('This tool provides a high-level estimate and is not a commercial quotation. A verified assessment requires device, meter, contract, consumable, and service data.','توفر الأداة تقديراً مبدئياً وليست عرضاً تجارياً. يتطلب التقييم المعتمد بيانات الأجهزة والعدادات والعقود والمستهلكات والخدمة.')); ?></p>
      </aside>
    </form>
    <?php if (shortcode_exists('smt_fleet_lead_form')) { echo do_shortcode('[smt_fleet_lead_form]'); } else { ?>
      <div class="smtp-plugin-notice"><strong><?php echo esc_html(smt_t('Enable report and lead capture','فعّل التقرير وتسجيل العملاء المحتملين')); ?></strong><p><?php echo esc_html(smt_t('Install and activate Source More Platform v2.1 or later to enable secure lead capture and PDF reports.','ثبّت وفعّل إضافة Source More Platform الإصدار 2.1 أو أحدث لتفعيل تسجيل العملاء وتقارير PDF الآمنة.')); ?></p></div>
    <?php } ?>
  </div>
</section>

<section class="section calculator-next-step">
  <div class="container section-heading"><div><span class="section-kicker"><?php echo esc_html(smt_t('What happens next','الخطوة التالية')); ?></span><h2><?php echo esc_html(smt_t('Turn an estimate into a verified savings plan.','حوّل التقدير إلى خطة توفير موثقة.')); ?></h2></div><p><?php echo esc_html(smt_t('Source More can validate the opportunity through a structured fleet assessment covering volumes, utilization, contracts, workflow, security, and support requirements.','يمكن لسورس مور التحقق من فرصة التوفير من خلال تقييم منظم يشمل الأحجام والاستخدام والعقود وسير العمل والأمان ومتطلبات الدعم.')); ?></p></div>
  <div class="container calculator-steps">
    <article><span>01</span><h3><?php echo esc_html(smt_t('Collect','جمع البيانات')); ?></h3><p><?php echo esc_html(smt_t('Device inventory, meter readings, invoices, and support costs.','حصر الأجهزة وقراءات العدادات والفواتير وتكاليف الدعم.')); ?></p></article>
    <article><span>02</span><h3><?php echo esc_html(smt_t('Analyze','التحليل')); ?></h3><p><?php echo esc_html(smt_t('Utilization, cost per page, device placement, and workflow gaps.','تحليل الاستخدام وتكلفة الصفحة وتوزيع الأجهزة وفجوات سير العمل.')); ?></p></article>
    <article><span>03</span><h3><?php echo esc_html(smt_t('Optimize','التحسين')); ?></h3><p><?php echo esc_html(smt_t('A right-sized fleet, service model, controls, and implementation roadmap.','أسطول بالحجم المناسب ونموذج خدمة وضوابط وخطة تنفيذ.')); ?></p></article>
  </div>
</section>
</main>
<?php get_footer(); ?>
