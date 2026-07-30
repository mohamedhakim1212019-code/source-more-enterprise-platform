<?php
/**
 * Enterprise, bilingual Fleet Assessment portal and print report renderer.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Fleet_PDF {
	public function output_lead( int $lead_id ): void {
		$meta = static fn( string $key ) => get_post_meta( $lead_id, $key, true );
		$lang = 'ar' === strtolower( (string) $meta( 'language' ) ) ? 'ar' : 'en';
		$rtl  = 'ar' === $lang;

		$company       = (string) $meta( 'company' );
		$contact_name  = (string) $meta( 'contact_name' );
		$email         = (string) $meta( 'email' );
		$phone         = (string) $meta( 'phone' );
		$industry      = (string) $meta( 'industry' );
		$locations     = max( 1, (int) $meta( 'locations' ) );
		$devices       = max( 1, (int) $meta( 'devices' ) );
		$mono_pages    = max( 0, (float) $meta( 'mono_pages' ) );
		$color_pages   = max( 0, (float) $meta( 'color_pages' ) );
		$current_cost  = max( 0, (float) $meta( 'current_cost' ) );
		$annual_saving = max( 0, (float) $meta( 'annual_savings' ) );
		$optimized     = max( 0, (float) $meta( 'optimized_cost' ) );
		$three_year    = max( 0, (float) $meta( 'three_year' ) );
		$saving_rate   = min( 100, max( 0, (float) $meta( 'saving_rate' ) ) );
		$five_year     = $annual_saving * 5;
		$report_number = 'SM-FLT-' . str_pad( (string) $lead_id, 6, '0', STR_PAD_LEFT );
		$generated     = wp_date( 'd/m/Y' );
		$logo_url      = $this->logo_url();
		$business      = $this->business_details( $rtl );
		$contact_url   = SMTP_I18n::page_url( 'contact', $lang );
		$whatsapp_url  = $this->whatsapp_url( $business['phone'], $company, $report_number, $rtl );

		$total_pages_year = ( $mono_pages + $color_pages ) * 12;
		$paper_reduction  = $total_pages_year * ( $saving_rate / 100 );
		$trees_saved      = $paper_reduction / 8333;
		$co2_reduction    = ( $paper_reduction / 1000 ) * 4.8;
		$energy_saved     = ( $paper_reduction / 1000 ) * 17;
		$color_share      = ( $mono_pages + $color_pages ) > 0 ? ( $color_pages / ( $mono_pages + $color_pages ) ) * 100 : 0;
		$mono_share       = 100 - $color_share;
		$optimized_pct    = $current_cost > 0 ? ( $optimized / $current_cost ) * 100 : 0;

		$t = static function ( string $en, string $ar ) use ( $rtl ): string {
			return $rtl ? $ar : $en;
		};
		$currency = $rtl ? 'ج.م' : 'EGP';

		while ( ob_get_level() ) {
			ob_end_clean();
		}
		nocache_headers();
		header( 'X-Content-Type-Options: nosniff' );
		header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset', 'UTF-8' ) );
		header( 'Content-Disposition: inline; filename="source-more-fleet-savings-' . sanitize_file_name( $company ?: (string) $lead_id ) . '.html"' );
		?>
<!doctype html>
<html lang="<?php echo esc_attr( $lang ); ?>" dir="<?php echo $rtl ? 'rtl' : 'ltr'; ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo esc_html( $t( 'Fleet Savings Assessment Portal', 'بوابة تقرير تقييم التوفير لأسطول الطباعة' ) . ' — ' . $company ); ?></title>
	<style>
		:root{--navy:#071f3d;--navy2:#103a63;--gold:#c9a55c;--gold2:#e8d3a5;--ink:#182432;--muted:#647385;--line:#dfe6ed;--paper:#fff;--bg:#edf2f6;--green:#08785a;--greenbg:#edf8f4;--bluebg:#edf4fb}
		*{box-sizing:border-box}html{background:var(--bg);scroll-behavior:smooth}body{margin:0;color:var(--ink);font-family:Arial,"Tahoma","Segoe UI",sans-serif;line-height:1.52;background:var(--bg)}a{color:inherit}
		.toolbar{position:sticky;top:0;z-index:30;display:flex;justify-content:center;align-items:center;gap:10px;flex-wrap:wrap;padding:12px;background:rgba(7,31,61,.97);box-shadow:0 5px 22px rgba(0,0,0,.18)}.toolbar a,.toolbar button{border:0;border-radius:8px;padding:10px 17px;font:700 13px Arial,Tahoma,sans-serif;cursor:pointer;text-decoration:none}.primary-action{background:var(--gold);color:var(--navy)}.secondary-action{background:#fff;color:var(--navy)}.ghost-action{background:rgba(255,255,255,.11);color:#fff;border:1px solid rgba(255,255,255,.22)!important}
		.report{width:210mm;margin:22px auto;background:var(--paper);box-shadow:0 20px 55px rgba(7,31,61,.15);overflow:hidden}.page{min-height:297mm;display:flex;flex-direction:column;background:#fff}.page+.page{border-top:18px solid var(--bg)}
		.hero{padding:12mm 16mm 11mm;background:linear-gradient(135deg,var(--navy),var(--navy2));color:#fff;position:relative;overflow:hidden}.hero:after{content:"";position:absolute;inset:auto -25mm -47mm auto;width:105mm;height:105mm;border:14mm solid rgba(201,165,92,.13);border-radius:50%}.brand{display:flex;align-items:center;justify-content:space-between;gap:16px;position:relative;z-index:1}.brand img{display:block;width:auto;max-width:66mm;max-height:18mm;object-fit:contain}.brand-fallback{font-size:22px;font-weight:900}.brand-fallback small{display:block;color:#d7e2ed;font-size:10px;font-weight:400}.confidential{font-size:9px;color:#d8e4ee;border:1px solid rgba(255,255,255,.25);border-radius:99px;padding:5px 10px;text-transform:uppercase;letter-spacing:.7px}.hero-grid{display:grid;grid-template-columns:1.5fr .7fr;gap:20px;align-items:end;margin-top:10mm;position:relative;z-index:1}.eyebrow{color:var(--gold);font-weight:900;text-transform:uppercase;letter-spacing:1px;font-size:10px}.hero h1{font-size:29px;line-height:1.22;margin:6px 0 9px;max-width:125mm}.hero p{margin:0;color:#dce7f1;font-size:12px}.report-meta{border-inline-start:1px solid rgba(255,255,255,.23);padding-inline-start:17px}.report-meta div+div{margin-top:7px}.report-meta span{display:block;color:#b9cad9;font-size:8px;text-transform:uppercase;letter-spacing:.55px}.report-meta strong{display:block;color:#fff;font-size:11px;margin-top:1px}
		.content{padding:8mm 16mm 7mm;flex:1}.section{margin-top:6mm}.section:first-child{margin-top:0}.section-heading{display:flex;align-items:center;gap:9px;margin-bottom:3.5mm}.section-heading:before{content:"";width:5px;height:25px;background:var(--gold);border-radius:4px}.section-heading h2{margin:0;color:var(--navy);font-size:17px}.section-heading p{margin:1px 0 0;color:var(--muted);font-size:10px}
		.client-card{display:grid;grid-template-columns:1.2fr 1fr 1fr;border:1px solid var(--line);border-radius:12px;overflow:hidden}.client-card>div{padding:12px 14px;border-inline-end:1px solid var(--line)}.client-card>div:last-child{border-inline-end:0}.label{font-size:8.5px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px}.value{font-size:12px;font-weight:800;color:var(--navy);margin-top:3px;word-break:break-word}
		.kpis{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}.kpi{border:1px solid var(--line);border-radius:12px;padding:12px;min-height:87px}.kpi.primary{background:var(--navy);border-color:var(--navy)}.kpi.accent{background:#f8eed9;border-color:#ead7ac}.kpi.success{background:var(--greenbg);border-color:#c9e7dc}.kpi .label{font-size:8.5px}.kpi.primary .label{color:#c8d6e3}.amount{font-size:20px;line-height:1.18;font-weight:900;margin-top:7px;color:var(--navy)}.kpi.primary .amount{color:var(--gold)}.kpi.success .amount{color:var(--green)}.kpi small{display:block;margin-top:5px;color:var(--muted);font-size:8.5px}.kpi.primary small{color:#d4e0eb}
		.dashboard{display:grid;grid-template-columns:.8fr 1.2fr;gap:12px}.donut-card,.chart-card{border:1px solid var(--line);border-radius:13px;padding:14px}.donut-wrap{display:flex;align-items:center;gap:16px}.donut{width:92px;height:92px;border-radius:50%;background:conic-gradient(var(--gold) 0 <?php echo esc_attr( $saving_rate ); ?>%,#e8edf2 <?php echo esc_attr( $saving_rate ); ?>% 100%);position:relative;flex:0 0 auto}.donut:after{content:"";position:absolute;inset:13px;background:#fff;border-radius:50%}.donut-value{position:absolute;inset:0;display:grid;place-items:center;z-index:1;font-size:19px;font-weight:900;color:var(--navy)}.legend{font-size:10px;color:var(--muted)}.legend strong{display:block;color:var(--navy);font-size:12px;margin-bottom:5px}.legend-row{display:flex;align-items:center;gap:7px;margin-top:5px}.dot{width:8px;height:8px;border-radius:50%;background:var(--gold)}.dot.grey{background:#dfe5eb}
		.bars{display:grid;gap:11px}.bar-top{display:flex;justify-content:space-between;gap:10px;color:var(--navy);font-size:10px;font-weight:800}.bar-track{height:12px;background:#e9eef3;border-radius:99px;overflow:hidden;margin-top:5px}.bar-fill{height:100%;border-radius:99px;background:var(--navy)}.bar-fill.optimized{background:var(--gold);width:<?php echo esc_attr( max( 2, min( 100, $optimized_pct ) ) ); ?>%}.bar-fill.saving{background:var(--green);width:<?php echo esc_attr( max( 2, min( 100, $saving_rate ) ) ); ?>%}
		.projection{display:grid;grid-template-columns:repeat(5,1fr);gap:8px;align-items:end;height:120px;padding-top:10px}.year{display:flex;flex-direction:column;align-items:center;justify-content:flex-end;height:100%;gap:5px}.year-bar{width:78%;min-height:12px;border-radius:6px 6px 2px 2px;background:linear-gradient(180deg,var(--gold),#b68d42)}.year strong{font-size:9px;color:var(--navy)}.year span{font-size:8px;color:var(--muted)}
		.table-wrap{border:1px solid var(--line);border-radius:12px;overflow:hidden}table{width:100%;border-collapse:collapse;font-size:9.3px}thead{background:var(--navy);color:#fff}th,td{padding:9px 10px;text-align:start;border-bottom:1px solid var(--line)}tbody tr:last-child td{border-bottom:0}.impact{font-weight:900;color:var(--green)}
		.environment{display:grid;grid-template-columns:repeat(3,1fr);border:1px solid var(--line);border-radius:12px;overflow:hidden}.datum{padding:12px;border-inline-end:1px solid var(--line);border-bottom:1px solid var(--line)}.datum:nth-child(3n){border-inline-end:0}.datum:nth-last-child(-n+3){border-bottom:0}.datum .value{font-size:13px}
		.environmental{display:grid;grid-template-columns:repeat(4,1fr);gap:9px}.eco{background:var(--greenbg);border:1px solid #c8e6da;border-radius:12px;padding:13px}.eco-icon{font-size:18px}.eco strong{display:block;color:var(--green);font-size:17px;margin-top:5px}.eco span{font-size:8.5px;color:var(--muted)}
		.timeline{display:grid;grid-template-columns:repeat(5,1fr);gap:0;counter-reset:steps}.step{position:relative;text-align:center;padding:0 7px}.step:before{counter-increment:steps;content:counter(steps);margin:0 auto 8px;width:28px;height:28px;display:grid;place-items:center;border-radius:50%;background:var(--gold);color:var(--navy);font-weight:900;font-size:11px;position:relative;z-index:1}.step:not(:last-child):after{content:"";position:absolute;top:13px;inset-inline-start:55%;width:90%;height:2px;background:#dfe5eb}.step strong{display:block;color:var(--navy);font-size:9.5px}.step span{display:block;color:var(--muted);font-size:8px;margin-top:3px}
		.cta{margin-top:6mm;background:linear-gradient(135deg,var(--navy),var(--navy2));border-radius:13px;padding:16px;color:#fff;display:flex;align-items:center;justify-content:space-between;gap:18px}.cta h3{margin:0;color:var(--gold);font-size:15px}.cta p{margin:4px 0 0;color:#d6e3ee;font-size:9px}.cta-actions{display:flex;gap:8px;white-space:nowrap}.cta-actions a{text-decoration:none;padding:8px 11px;border-radius:7px;font-size:9px;font-weight:900}.cta-primary{background:var(--gold);color:var(--navy)}.cta-secondary{background:#fff;color:var(--navy)}
		.note{margin-top:5mm;padding:11px 13px;border-inline-start:4px solid var(--gold);background:#f6f8fa;color:var(--muted);font-size:8.5px}.footer{display:grid;grid-template-columns:1.1fr 1.7fr 1fr;align-items:center;gap:12px;padding:4.5mm 16mm;background:var(--navy);color:#d8e3ed;font-size:7.8px;line-height:1.5}.footer-brand{display:flex;align-items:center;gap:9px}.footer-logo{display:block;width:auto;max-width:37mm;max-height:10mm;object-fit:contain}.footer strong{color:var(--gold);font-size:8.5px}.footer-contact{border-inline-start:1px solid rgba(255,255,255,.2);border-inline-end:1px solid rgba(255,255,255,.2);padding-inline:12px}.footer-contact span{display:block}.footer a{color:#fff;text-decoration:none}.footer-meta{text-align:end}.footer-meta span{display:block;color:#aebfd0}
		[dir="rtl"] .eyebrow,[dir="rtl"] .label,[dir="rtl"] .report-meta span,[dir="rtl"] .confidential{text-transform:none;letter-spacing:0}
		@media(max-width:900px){.report{width:100%;margin:0}.page{min-height:0}.page+.page{border-top:10px solid var(--bg)}.hero,.content{padding-inline:6vw}.hero-grid,.client-card,.kpis,.dashboard,.environment,.environmental,.timeline,.cta,.footer{grid-template-columns:1fr}.report-meta{border-inline-start:0;padding-inline-start:0}.client-card>div,.datum{border-inline-end:0;border-bottom:1px solid var(--line)}.projection{height:100px}.cta{display:block}.cta-actions{margin-top:12px;flex-wrap:wrap}.footer-contact{border:0;padding:0}.footer-meta{text-align:start}.toolbar{position:relative}}
		@page{size:A4;margin:0}@media print{html,body{background:#fff}.toolbar{display:none!important}.report{width:210mm;margin:0;box-shadow:none}.page{min-height:297mm;page-break-after:always}.page:last-child{page-break-after:auto}.page+.page{border-top:0}.hero,.kpi.primary,.footer,.bar-fill,.year-bar,.step:before,.cta,thead,.eco{-webkit-print-color-adjust:exact;print-color-adjust:exact}.section,.kpi,.chart-card,.donut-card,.table-wrap,.environment,.environmental,.timeline,.cta{break-inside:avoid}}
	</style>
</head>
<body>
	<div class="toolbar" role="toolbar" aria-label="<?php echo esc_attr( $t( 'Report portal actions', 'إجراءات بوابة التقرير' ) ); ?>">
		<button class="primary-action" type="button" onclick="window.print()"><?php echo esc_html( $t( 'Print / Save PDF', 'طباعة / حفظ PDF' ) ); ?></button>
		<a class="secondary-action" href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( $t( 'Book Free Assessment', 'احجز تقييمًا مجانيًا' ) ); ?></a>
		<?php if ( $whatsapp_url ) : ?><a class="ghost-action" href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $t( 'Discuss on WhatsApp', 'ناقش التقرير عبر واتساب' ) ); ?></a><?php endif; ?>
	</div>
	<main class="report">
		<section class="page">
			<header class="hero">
				<div class="brand">
					<?php if ( $logo_url ) : ?><img src="<?php echo esc_url( $logo_url ); ?>" alt="Source More Technology"><?php else : ?><div class="brand-fallback">Source More<small>One Source. More Value.</small></div><?php endif; ?>
					<span class="confidential"><?php echo esc_html( $t( 'Confidential Executive Assessment', 'تقييم تنفيذي سري' ) ); ?></span>
				</div>
				<div class="hero-grid">
					<div><div class="eyebrow"><?php echo esc_html( $t( 'Enterprise Print Intelligence', 'تحليلات الطباعة للمؤسسات' ) ); ?></div><h1><?php echo esc_html( $t( 'Fleet Savings Assessment Report', 'تقرير تقييم التوفير لأسطول الطباعة' ) ); ?></h1><p><?php echo esc_html( $t( 'A strategic estimate of the current print environment, optimization opportunities, financial impact, and recommended next steps.', 'تقدير استراتيجي لبيئة الطباعة الحالية وفرص التحسين والأثر المالي والخطوات التنفيذية المقترحة.' ) ); ?></p></div>
					<div class="report-meta"><div><span><?php echo esc_html( $t( 'Report number', 'رقم التقرير' ) ); ?></span><strong><?php echo esc_html( $report_number ); ?></strong></div><div><span><?php echo esc_html( $t( 'Issue date', 'تاريخ الإصدار' ) ); ?></span><strong><?php echo esc_html( $generated ); ?></strong></div><div><span><?php echo esc_html( $t( 'Prepared for', 'أُعد لصالح' ) ); ?></span><strong><?php echo esc_html( $company ); ?></strong></div></div>
				</div>
			</header>
			<div class="content">
				<section class="section"><div class="section-heading"><div><h2><?php echo esc_html( $t( 'Client Profile', 'بيانات العميل' ) ); ?></h2><p><?php echo esc_html( $t( 'Business and assessment profile', 'بيانات المؤسسة والتقييم' ) ); ?></p></div></div><div class="client-card"><div><div class="label"><?php echo esc_html( $t( 'Company / Contact', 'الشركة / مسؤول التواصل' ) ); ?></div><div class="value"><?php echo esc_html( $company ); ?><br><?php echo esc_html( $contact_name ); ?></div></div><div><div class="label"><?php echo esc_html( $t( 'Contact details', 'بيانات التواصل' ) ); ?></div><div class="value" dir="ltr"><?php echo esc_html( $email ); ?><br><?php echo esc_html( $phone ); ?></div></div><div><div class="label"><?php echo esc_html( $t( 'Industry / Locations', 'القطاع / عدد المواقع' ) ); ?></div><div class="value"><?php echo esc_html( $industry ?: '—' ); ?><br><?php echo esc_html( number_format_i18n( $locations ) ); ?></div></div></div></section>
				<section class="section"><div class="section-heading"><div><h2><?php echo esc_html( $t( 'Executive Summary', 'الملخص التنفيذي' ) ); ?></h2><p><?php echo esc_html( $t( 'Headline financial indicators', 'أهم المؤشرات المالية' ) ); ?></p></div></div><div class="kpis">
					<div class="kpi"><div class="label"><?php echo esc_html( $t( 'Current annual cost', 'التكلفة السنوية الحالية' ) ); ?></div><div class="amount"><?php echo esc_html( $this->money( $current_cost, $currency, $rtl ) ); ?></div><small><?php echo esc_html( $t( 'Current estimated baseline', 'خط الأساس التقديري الحالي' ) ); ?></small></div>
					<div class="kpi primary"><div class="label"><?php echo esc_html( $t( 'Annual savings opportunity', 'فرصة التوفير السنوية' ) ); ?></div><div class="amount"><?php echo esc_html( $this->money( $annual_saving, $currency, $rtl ) ); ?></div><small><?php echo esc_html( $t( 'Potential annual value unlocked', 'القيمة السنوية المتوقع تحقيقها' ) ); ?></small></div>
					<div class="kpi accent"><div class="label"><?php echo esc_html( $t( 'Optimized annual cost', 'التكلفة السنوية بعد التحسين' ) ); ?></div><div class="amount"><?php echo esc_html( $this->money( $optimized, $currency, $rtl ) ); ?></div><small><?php echo esc_html( $t( 'Projected optimized baseline', 'التكلفة المتوقعة بعد التحسين' ) ); ?></small></div>
					<div class="kpi success"><div class="label"><?php echo esc_html( $t( 'Optimization potential', 'نسبة التحسين المتوقعة' ) ); ?></div><div class="amount"><?php echo esc_html( number_format_i18n( $saving_rate, 0 ) ); ?>%</div><small><?php echo esc_html( $t( 'Subject to detailed validation', 'تخضع للتقييم التفصيلي' ) ); ?></small></div>
					<div class="kpi"><div class="label"><?php echo esc_html( $t( 'Three-year savings', 'التوفير خلال 3 سنوات' ) ); ?></div><div class="amount"><?php echo esc_html( $this->money( $three_year, $currency, $rtl ) ); ?></div><small><?php echo esc_html( $t( 'Indicative cumulative benefit', 'منفعة تراكمية تقديرية' ) ); ?></small></div>
					<div class="kpi"><div class="label"><?php echo esc_html( $t( 'Five-year opportunity', 'فرصة التوفير خلال 5 سنوات' ) ); ?></div><div class="amount"><?php echo esc_html( $this->money( $five_year, $currency, $rtl ) ); ?></div><small><?php echo esc_html( $t( 'Long-term value potential', 'القيمة المحتملة طويلة الأجل' ) ); ?></small></div>
				</div></section>
				<section class="section"><div class="section-heading"><div><h2><?php echo esc_html( $t( 'Fleet Analytics Dashboard', 'لوحة تحليلات أسطول الطباعة' ) ); ?></h2><p><?php echo esc_html( $t( 'Current versus optimized cost profile', 'مقارنة ملف التكلفة الحالي والمحسّن' ) ); ?></p></div></div><div class="dashboard"><div class="donut-card"><div class="donut-wrap"><div class="donut"><div class="donut-value"><?php echo esc_html( number_format_i18n( $saving_rate, 0 ) ); ?>%</div></div><div class="legend"><strong><?php echo esc_html( $t( 'Optimization opportunity', 'فرصة التحسين' ) ); ?></strong><div class="legend-row"><span class="dot"></span><?php echo esc_html( $t( 'Potential savings', 'التوفير المحتمل' ) ); ?></div><div class="legend-row"><span class="dot grey"></span><?php echo esc_html( $t( 'Optimized operating cost', 'تكلفة التشغيل بعد التحسين' ) ); ?></div></div></div></div><div class="chart-card"><div class="bars"><div><div class="bar-top"><span><?php echo esc_html( $t( 'Current annual cost', 'التكلفة السنوية الحالية' ) ); ?></span><span><?php echo esc_html( $this->money( $current_cost, $currency, $rtl ) ); ?></span></div><div class="bar-track"><div class="bar-fill" style="width:100%"></div></div></div><div><div class="bar-top"><span><?php echo esc_html( $t( 'Optimized annual cost', 'التكلفة السنوية بعد التحسين' ) ); ?></span><span><?php echo esc_html( $this->money( $optimized, $currency, $rtl ) ); ?></span></div><div class="bar-track"><div class="bar-fill optimized"></div></div></div><div><div class="bar-top"><span><?php echo esc_html( $t( 'Annual savings', 'التوفير السنوي' ) ); ?></span><span><?php echo esc_html( $this->money( $annual_saving, $currency, $rtl ) ); ?></span></div><div class="bar-track"><div class="bar-fill saving"></div></div></div></div></div></div></section>
			</div>
			<?php $this->footer( $logo_url, $business, $report_number, $t ); ?>
		</section>

		<section class="page">
			<div class="content">
				<section class="section"><div class="section-heading"><div><h2><?php echo esc_html( $t( 'Financial Projection', 'التحليل المالي المتوقع' ) ); ?></h2><p><?php echo esc_html( $t( 'Cumulative savings potential over five years', 'التوفير التراكمي المحتمل خلال خمس سنوات' ) ); ?></p></div></div><div class="chart-card"><div class="projection"><?php for ( $year = 1; $year <= 5; $year++ ) : $value = $annual_saving * $year; ?><div class="year"><strong><?php echo esc_html( $this->compact_money( $value, $currency, $rtl ) ); ?></strong><div class="year-bar" style="height:<?php echo esc_attr( 20 + ( $year * 14 ) ); ?>%"></div><span><?php echo esc_html( $t( 'Year ', 'السنة ' ) . $year ); ?></span></div><?php endfor; ?></div></div></section>
				<section class="section"><div class="section-heading"><div><h2><?php echo esc_html( $t( 'Optimization Opportunities', 'فرص التحسين' ) ); ?></h2><p><?php echo esc_html( $t( 'Priority areas to validate during the professional assessment', 'مجالات الأولوية المطلوب التحقق منها أثناء التقييم الاحترافي' ) ); ?></p></div></div><div class="table-wrap"><table><thead><tr><th><?php echo esc_html( $t( 'Area', 'المجال' ) ); ?></th><th><?php echo esc_html( $t( 'Current indication', 'الوضع الحالي' ) ); ?></th><th><?php echo esc_html( $t( 'Recommended direction', 'التوصية' ) ); ?></th><th><?php echo esc_html( $t( 'Expected impact', 'الأثر المتوقع' ) ); ?></th></tr></thead><tbody>
					<tr><td><?php echo esc_html( $t( 'Fleet sizing', 'حجم الأسطول' ) ); ?></td><td><?php echo esc_html( sprintf( $t( '%d devices across %d locations', '%d جهازًا عبر %d مواقع' ), $devices, $locations ) ); ?></td><td><?php echo esc_html( $t( 'Validate utilization and consolidate underused devices', 'تحليل الاستخدام ودمج الأجهزة منخفضة الاستفادة' ) ); ?></td><td class="impact"><?php echo esc_html( $t( 'High', 'مرتفع' ) ); ?></td></tr>
					<tr><td><?php echo esc_html( $t( 'Color governance', 'حوكمة الطباعة الملونة' ) ); ?></td><td><?php echo esc_html( number_format_i18n( $color_share, 1 ) . '% ' . $t( 'of monthly volume', 'من الحجم الشهري' ) ); ?></td><td><?php echo esc_html( $t( 'Apply user policies, quotas, and default mono rules', 'تطبيق سياسات المستخدمين والحصص والطباعة الأحادية الافتراضية' ) ); ?></td><td class="impact"><?php echo esc_html( $color_share > 10 ? $t( 'High', 'مرتفع' ) : $t( 'Medium', 'متوسط' ) ); ?></td></tr>
					<tr><td><?php echo esc_html( $t( 'Print management', 'إدارة الطباعة' ) ); ?></td><td><?php echo esc_html( $t( 'No policy data provided', 'لا توجد بيانات سياسات ضمن المدخلات' ) ); ?></td><td><?php echo esc_html( $t( 'Introduce secure print, rules, reporting, and user accountability', 'تطبيق الطباعة الآمنة والسياسات والتقارير ومساءلة المستخدمين' ) ); ?></td><td class="impact"><?php echo esc_html( $t( 'High', 'مرتفع' ) ); ?></td></tr>
					<tr><td><?php echo esc_html( $t( 'Service model', 'نموذج الخدمة' ) ); ?></td><td><?php echo esc_html( $this->money( (float) $meta( 'fixed_cost' ), $currency, $rtl ) . ' / ' . $t( 'month', 'شهر' ) ); ?></td><td><?php echo esc_html( $t( 'Move to proactive managed service with measurable SLA and visibility', 'التحول إلى خدمة مُدارة استباقية بمؤشرات SLA واضحة' ) ); ?></td><td class="impact"><?php echo esc_html( $t( 'Medium–High', 'متوسط–مرتفع' ) ); ?></td></tr>
					<tr><td><?php echo esc_html( $t( 'Workflow digitization', 'رقمنة سير العمل' ) ); ?></td><td><?php echo esc_html( $t( 'Paper dependency requires validation', 'الاعتماد على الورق يحتاج إلى تقييم' ) ); ?></td><td><?php echo esc_html( $t( 'Digitize recurring forms, approvals, and document routing', 'رقمنة النماذج المتكررة والموافقات ومسارات المستندات' ) ); ?></td><td class="impact"><?php echo esc_html( $t( 'Strategic', 'استراتيجي' ) ); ?></td></tr>
				</tbody></table></div></section>
				<section class="section"><div class="section-heading"><div><h2><?php echo esc_html( $t( 'Current Print Environment', 'بيئة الطباعة الحالية' ) ); ?></h2><p><?php echo esc_html( $t( 'Inputs used for this indicative assessment', 'المدخلات المستخدمة في هذا التقييم التقديري' ) ); ?></p></div></div><div class="environment">
				<?php $data_points = array(
					array( $t( 'Devices / MFPs', 'عدد الأجهزة والطابعات متعددة الوظائف' ), number_format_i18n( $devices ) ),
					array( $t( 'Monthly mono pages', 'صفحات أحادية اللون شهريًا' ), number_format_i18n( $mono_pages, 0 ) ),
					array( $t( 'Monthly color pages', 'صفحات ملونة شهريًا' ), number_format_i18n( $color_pages, 0 ) ),
					array( $t( 'Mono cost per page', 'تكلفة الصفحة الأحادية' ), $this->money( (float) $meta( 'mono_cpp' ), $currency, $rtl, 2 ) ),
					array( $t( 'Color cost per page', 'تكلفة الصفحة الملونة' ), $this->money( (float) $meta( 'color_cpp' ), $currency, $rtl, 2 ) ),
					array( $t( 'Monthly service & maintenance', 'الخدمة والصيانة الشهرية' ), $this->money( (float) $meta( 'fixed_cost' ), $currency, $rtl ) ),
				); foreach ( $data_points as $point ) : ?><div class="datum"><div class="label"><?php echo esc_html( $point[0] ); ?></div><div class="value"><?php echo esc_html( $point[1] ); ?></div></div><?php endforeach; ?>
				</div></section>
			</div>
			<?php $this->footer( $logo_url, $business, $report_number, $t ); ?>
		</section>

		<section class="page">
			<div class="content">
				<section class="section"><div class="section-heading"><div><h2><?php echo esc_html( $t( 'Indicative Environmental Impact', 'الأثر البيئي التقديري' ) ); ?></h2><p><?php echo esc_html( $t( 'Potential impact based on reduced print volume; values are planning estimates', 'أثر محتمل مبني على خفض حجم الطباعة؛ القيم تقديرية لأغراض التخطيط' ) ); ?></p></div></div><div class="environmental"><div class="eco"><div class="eco-icon">▤</div><strong><?php echo esc_html( number_format_i18n( $paper_reduction, 0 ) ); ?></strong><span><?php echo esc_html( $t( 'pages potentially avoided annually', 'صفحة يمكن تجنبها سنويًا' ) ); ?></span></div><div class="eco"><div class="eco-icon">♧</div><strong><?php echo esc_html( number_format_i18n( $trees_saved, 1 ) ); ?></strong><span><?php echo esc_html( $t( 'tree equivalents potentially preserved', 'ما يعادل أشجارًا يمكن الحفاظ عليها' ) ); ?></span></div><div class="eco"><div class="eco-icon">CO₂</div><strong><?php echo esc_html( number_format_i18n( $co2_reduction, 0 ) ); ?> kg</strong><span><?php echo esc_html( $t( 'estimated CO₂ reduction', 'خفض تقديري لانبعاثات الكربون' ) ); ?></span></div><div class="eco"><div class="eco-icon">⚡</div><strong><?php echo esc_html( number_format_i18n( $energy_saved, 0 ) ); ?> kWh</strong><span><?php echo esc_html( $t( 'estimated paper-production energy avoided', 'طاقة تقديرية يتم تجنبها في إنتاج الورق' ) ); ?></span></div></div></section>
				<section class="section"><div class="section-heading"><div><h2><?php echo esc_html( $t( 'Recommended Engagement Roadmap', 'خارطة طريق التنفيذ المقترحة' ) ); ?></h2><p><?php echo esc_html( $t( 'A controlled path from indicative estimate to verified business case', 'مسار منظم من التقدير المبدئي إلى دراسة جدوى معتمدة' ) ); ?></p></div></div><div class="timeline"><div class="step"><strong><?php echo esc_html( $t( 'Discovery', 'الاستكشاف' ) ); ?></strong><span><?php echo esc_html( $t( 'Scope, objectives, stakeholders', 'النطاق والأهداف وأصحاب المصلحة' ) ); ?></span></div><div class="step"><strong><?php echo esc_html( $t( 'On-site Assessment', 'التقييم الميداني' ) ); ?></strong><span><?php echo esc_html( $t( 'Devices, meters, contracts, workflows', 'الأجهزة والعدادات والعقود وسير العمل' ) ); ?></span></div><div class="step"><strong><?php echo esc_html( $t( 'Solution Design', 'تصميم الحل' ) ); ?></strong><span><?php echo esc_html( $t( 'Right-sized fleet and service model', 'أسطول مناسب ونموذج خدمة' ) ); ?></span></div><div class="step"><strong><?php echo esc_html( $t( 'Business Case', 'دراسة الجدوى' ) ); ?></strong><span><?php echo esc_html( $t( 'Validated TCO, savings, SLA, roadmap', 'TCO والتوفير وSLA وخطة التنفيذ' ) ); ?></span></div><div class="step"><strong><?php echo esc_html( $t( 'Implementation', 'التنفيذ' ) ); ?></strong><span><?php echo esc_html( $t( 'Deployment, adoption, reporting', 'التركيب والتبني والتقارير' ) ); ?></span></div></div></section>
				<section class="section"><div class="section-heading"><div><h2><?php echo esc_html( $t( 'Source More Recommendation', 'توصية Source More' ) ); ?></h2><p><?php echo esc_html( $t( 'Convert the estimate into a verified and actionable plan', 'تحويل التقدير إلى خطة معتمدة وقابلة للتنفيذ' ) ); ?></p></div></div><div class="table-wrap"><table><tbody><tr><td><strong><?php echo esc_html( $t( 'Immediate action', 'الإجراء الفوري' ) ); ?></strong></td><td><?php echo esc_html( $t( 'Conduct a professional fleet assessment covering equipment, volumes, contracts, service performance, security, and workflows.', 'إجراء تقييم احترافي للأسطول يغطي الأجهزة والأحجام والعقود وأداء الخدمة والأمن وسير العمل.' ) ); ?></td></tr><tr><td><strong><?php echo esc_html( $t( 'Primary outcome', 'النتيجة الأساسية' ) ); ?></strong></td><td><?php echo esc_html( $t( 'A validated current-state baseline, optimized fleet design, implementation roadmap, and commercial proposal.', 'خط أساس معتمد للوضع الحالي وتصميم أسطول محسن وخطة تنفيذ وعرض تجاري.' ) ); ?></td></tr><tr><td><strong><?php echo esc_html( $t( 'Decision criterion', 'معيار القرار' ) ); ?></strong></td><td><?php echo esc_html( $t( 'Proceed only after financial, operational, technical, and service assumptions are validated with the customer team.', 'الانتقال للتنفيذ بعد اعتماد الافتراضات المالية والتشغيلية والفنية والخدمية مع فريق العميل.' ) ); ?></td></tr></tbody></table></div></section>
				<div class="cta"><div><h3><?php echo esc_html( $t( 'Turn this estimate into verified savings', 'حوّل هذا التقدير إلى توفير معتمد' ) ); ?></h3><p><?php echo esc_html( $t( 'Book a free consultation with Source More Technology to validate the opportunity and receive a tailored roadmap.', 'احجز استشارة مجانية مع Source More Technology للتحقق من الفرصة والحصول على خارطة طريق مخصصة.' ) ); ?></p></div><div class="cta-actions"><a class="cta-primary" href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( $t( 'Book Assessment', 'احجز التقييم' ) ); ?></a><?php if ( $whatsapp_url ) : ?><a class="cta-secondary" href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $t( 'WhatsApp', 'واتساب' ) ); ?></a><?php endif; ?></div></div>
				<div class="note"><strong><?php echo esc_html( $t( 'Important note:', 'ملاحظة مهمة:' ) ); ?></strong> <?php echo esc_html( $t( 'This report is an indicative planning assessment and is not a commercial quotation or a guaranteed-savings commitment. Financial and environmental values require validation through a detailed review of devices, contracts, volumes, service records, energy assumptions, and operating conditions.', 'هذا التقرير تقييم استرشادي لأغراض التخطيط ولا يُعد عرضًا تجاريًا أو التزامًا بضمان التوفير. تتطلب القيم المالية والبيئية التحقق من خلال مراجعة تفصيلية للأجهزة والعقود والأحجام وسجلات الخدمة وافتراضات الطاقة وظروف التشغيل.' ) ); ?></div>
			</div>
			<?php $this->footer( $logo_url, $business, $report_number, $t ); ?>
		</section>
	</main>
</body>
</html>
		<?php
	}

	private function footer( string $logo_url, array $business, string $report_number, callable $t ): void {
		?>
		<footer class="footer"><div class="footer-brand"><?php if ( $logo_url ) : ?><img class="footer-logo" src="<?php echo esc_url( $logo_url ); ?>" alt="Source More Technology"><?php else : ?><div><strong>Source More Technology</strong><br>One Source. More Value.</div><?php endif; ?></div><div class="footer-contact"><span dir="ltr"><strong><?php echo esc_html( $t( 'Phone', 'الهاتف' ) ); ?>:</strong> <?php echo esc_html( $business['phone'] ); ?></span><span dir="ltr"><strong><?php echo esc_html( $t( 'Email', 'البريد الإلكتروني' ) ); ?>:</strong> <a href="mailto:<?php echo esc_attr( $business['email'] ); ?>"><?php echo esc_html( $business['email'] ); ?></a></span><span><strong><?php echo esc_html( $t( 'Address', 'العنوان' ) ); ?>:</strong> <?php echo esc_html( $business['address'] ); ?></span></div><div class="footer-meta"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( wp_parse_url( home_url( '/' ), PHP_URL_HOST ) ?: home_url( '/' ) ); ?></a><span><?php echo esc_html( $report_number ); ?></span><span><?php echo esc_html( $t( 'Confidential assessment', 'تقييم سري' ) ); ?></span></div></footer>
		<?php
	}

	private function logo_url(): string {
		$theme_file = trailingslashit( get_stylesheet_directory() ) . 'assets/images/branding/source-more-logo-en-on-dark.png';
		$theme_url  = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/images/branding/source-more-logo-en-on-dark.png';
		if ( is_readable( $theme_file ) ) {
			return $theme_url;
		}
		$custom_logo_id = (int) get_theme_mod( 'custom_logo' );
		if ( $custom_logo_id ) {
			$logo = wp_get_attachment_image_url( $custom_logo_id, 'full' );
			if ( $logo ) {
				return (string) $logo;
			}
		}
		return '';
	}

	private function business_details( bool $rtl ): array {
		$options = wp_parse_args( (array) get_option( 'smt_business_settings', array() ), array( 'phone' => '+20 100 000 0000', 'email' => 'info@sourcemoreg.com', 'address_en' => 'Cairo, Egypt', 'address_ar' => 'القاهرة، مصر' ) );
		return array( 'phone' => sanitize_text_field( (string) $options['phone'] ), 'email' => sanitize_email( (string) $options['email'] ), 'address' => sanitize_text_field( (string) $options[ $rtl ? 'address_ar' : 'address_en' ] ) );
	}

	private function whatsapp_url( string $phone, string $company, string $report_number, bool $rtl ): string {
		$number = preg_replace( '/\D+/', '', $phone );
		if ( ! $number ) {
			return '';
		}
		$message = $rtl ? "مرحبًا Source More، أود مناقشة تقرير تقييم التوفير {$report_number} الخاص بشركة {$company}." : "Hello Source More, I would like to discuss fleet savings report {$report_number} for {$company}.";
		return 'https://wa.me/' . $number . '?text=' . rawurlencode( $message );
	}

	private function money( float $number, string $currency, bool $rtl, int $decimals = 0 ): string {
		$formatted = number_format_i18n( $number, $decimals );
		return $rtl ? $formatted . ' ' . $currency : $currency . ' ' . $formatted;
	}

	private function compact_money( float $number, string $currency, bool $rtl ): string {
		if ( $number >= 1000000 ) {
			$value = number_format_i18n( $number / 1000000, 1 ) . 'M';
		} elseif ( $number >= 1000 ) {
			$value = number_format_i18n( $number / 1000, 0 ) . 'K';
		} else {
			$value = number_format_i18n( $number, 0 );
		}
		return $rtl ? $value . ' ' . $currency : $currency . ' ' . $value;
	}
}
