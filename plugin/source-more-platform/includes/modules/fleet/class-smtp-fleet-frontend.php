<?php
/**
 * Fleet calculator public lead-capture form and assets.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Fleet_Frontend {
	private bool $registered = false;

	public function register_hooks(): void {
		if ( $this->registered ) {
			return;
		}
		add_shortcode( 'smt_fleet_lead_form', array( $this, 'shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
		$this->registered = true;
	}

	public function assets(): void {
		if ( ! is_page_template( 'page-fleet-savings-calculator.php' ) && ! is_page( 'fleet-savings-calculator' ) ) {
			return;
		}

		$lang = SMTP_I18n::language();
		wp_enqueue_style( 'smtp-platform', SMTP_PLATFORM_URL . 'assets/css/platform.css', array(), SMTP_PLATFORM_VERSION );
		wp_enqueue_script( 'smtp-platform', SMTP_PLATFORM_URL . 'assets/js/platform.js', array(), SMTP_PLATFORM_VERSION, true );
		wp_localize_script(
			'smtp-platform',
			'SMTPPlatform',
			array(
				'rest'     => esc_url_raw( rest_url( 'source-more/v1/lead' ) ),
				'nonce'    => wp_create_nonce( 'wp_rest' ),
				'language' => $lang,
				'messages' => array(
					'sending' => SMTP_I18n::text( 'Preparing your report…', 'جارٍ إعداد تقريرك…', $lang ),
					'success' => SMTP_I18n::text( 'Your report is ready.', 'تقريرك جاهز.', $lang ),
					'error'   => SMTP_I18n::text( 'We could not prepare the report. Please check the fields and try again.', 'تعذر إعداد التقرير. يرجى مراجعة البيانات والمحاولة مرة أخرى.', $lang ),
				),
			)
		);
	}

	public function shortcode(): string {
		$lang = SMTP_I18n::language();
		$industries = array(
			array( 'Banking & Financial Services', 'البنوك والخدمات المالية' ),
			array( 'Manufacturing', 'التصنيع' ),
			array( 'Healthcare', 'الرعاية الصحية' ),
			array( 'Education', 'التعليم' ),
			array( 'Government', 'الجهات الحكومية' ),
			array( 'Professional Services', 'الخدمات المهنية' ),
			array( 'Retail & Distribution', 'التجزئة والتوزيع' ),
			array( 'Other', 'أخرى' ),
		);

		ob_start();
		?>
		<section class="smtp-lead-capture" id="smtp-lead-capture" dir="<?php echo 'ar' === $lang ? 'rtl' : 'ltr'; ?>">
			<div class="smtp-lead-heading">
				<span class="smtp-step">02</span>
				<div><h2><?php echo esc_html( SMTP_I18n::text( 'Receive your branded savings report', 'احصل على تقرير التوفير الخاص بمؤسستك', $lang ) ); ?></h2><p><?php echo esc_html( SMTP_I18n::text( 'Enter your business details to save the result, download the PDF, and request a free fleet assessment.', 'أدخل بيانات مؤسستك لحفظ النتيجة وتحميل تقرير PDF وطلب تقييم مجاني لأسطول الطباعة.', $lang ) ); ?></p></div>
			</div>
			<form id="smtp-lead-form" class="smtp-lead-form" data-language="<?php echo esc_attr( $lang ); ?>" novalidate>
				<div class="smtp-form-grid">
					<label><?php echo esc_html( SMTP_I18n::text( 'Company name', 'اسم الشركة', $lang ) ); ?><input name="company" type="text" autocomplete="organization" required></label>
					<label><?php echo esc_html( SMTP_I18n::text( 'Contact person', 'اسم مسؤول التواصل', $lang ) ); ?><input name="contact_name" type="text" autocomplete="name" required></label>
					<label><?php echo esc_html( SMTP_I18n::text( 'Business email', 'البريد الإلكتروني للعمل', $lang ) ); ?><input name="email" type="email" autocomplete="email" required></label>
					<label><?php echo esc_html( SMTP_I18n::text( 'Phone / WhatsApp', 'الهاتف / واتساب', $lang ) ); ?><input name="phone" type="tel" autocomplete="tel" dir="ltr" required></label>
					<label><?php echo esc_html( SMTP_I18n::text( 'Industry', 'القطاع', $lang ) ); ?><select name="industry"><option value=""><?php echo esc_html( SMTP_I18n::text( 'Select industry', 'اختر القطاع', $lang ) ); ?></option><?php foreach ( $industries as $industry ) : ?><option value="<?php echo esc_attr( $industry[0] ); ?>"><?php echo esc_html( SMTP_I18n::text( $industry[0], $industry[1], $lang ) ); ?></option><?php endforeach; ?></select></label>
					<label><?php echo esc_html( SMTP_I18n::text( 'Number of locations', 'عدد المواقع', $lang ) ); ?><input name="locations" type="number" min="1" value="1"></label>
				</div>
				<label class="smtp-consent"><input type="checkbox" name="consent" value="1" required><span><?php echo esc_html( SMTP_I18n::text( 'I agree that Source More Technology may contact me about this assessment. My data will not be sold to third parties.', 'أوافق على تواصل سورس مور تكنولوجي معي بخصوص هذا التقييم. لن يتم بيع بياناتي لأي طرف ثالث.', $lang ) ); ?></span></label>
				<input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="smtp-honeypot" aria-hidden="true">
				<button type="submit" class="btn btn-gold smtp-submit"><i class="fa-solid fa-file-pdf"></i> <?php echo esc_html( SMTP_I18n::text( 'Create My Savings Report', 'أنشئ تقرير التوفير', $lang ) ); ?></button>
				<div class="smtp-form-status" id="smtp-form-status" role="status" aria-live="polite"></div>
			</form>
			<div class="smtp-success" id="smtp-success" hidden>
				<i class="fa-solid fa-circle-check"></i><h3><?php echo esc_html( SMTP_I18n::text( 'Your report is ready', 'تقريرك جاهز', $lang ) ); ?></h3>
				<p><?php echo esc_html( SMTP_I18n::text( 'A copy of the report link has also been sent to your business email when WordPress email delivery is configured.', 'تم إرسال نسخة من رابط التقرير إلى بريدك الإلكتروني عند تفعيل خدمة البريد في WordPress.', $lang ) ); ?></p>
				<div class="smtp-success-actions"><a id="smtp-download-report" class="btn btn-gold" href="#"><?php echo esc_html( SMTP_I18n::text( 'Download PDF Report', 'تحميل تقرير PDF', $lang ) ); ?></a><a class="btn smtp-secondary" href="<?php echo esc_url( SMTP_I18n::page_url( 'contact', $lang ) ); ?>"><?php echo esc_html( SMTP_I18n::text( 'Book a Free Fleet Assessment', 'احجز تقييمًا مجانيًا لأسطول الطباعة', $lang ) ); ?></a></div>
			</div>
		</section>
		<?php
		return (string) ob_get_clean();
	}
}
