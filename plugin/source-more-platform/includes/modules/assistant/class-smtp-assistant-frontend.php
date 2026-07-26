<?php
/**
 * AI Assistant frontend assets and accessible widget markup.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Assistant_Frontend {
	private bool $registered = false;

	public function register_hooks(): void {
		if ( $this->registered ) return;
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'wp_footer', array( $this, 'markup' ) );
		$this->registered = true;
	}

	public function enabled(): bool {
		$options = SMTP_Settings::get();
		return ! empty( $options['assistant_enabled'] );
	}

	public function assets(): void {
		if ( ! $this->enabled() ) return;
		$lang = SMTP_I18n::language();
		wp_enqueue_style( 'smtp-assistant', SMTP_PLATFORM_URL . 'assets/css/assistant.css', array(), SMTP_PLATFORM_VERSION );
		wp_enqueue_script( 'smtp-assistant', SMTP_PLATFORM_URL . 'assets/js/assistant.js', array(), SMTP_PLATFORM_VERSION, true );
		wp_localize_script(
			'smtp-assistant',
			'SMTPAssistant',
			array(
				'rest'        => esc_url_raw( rest_url( 'source-more/v2/assistant' ) ),
				'leadRest'    => esc_url_raw( rest_url( 'source-more/v2/assistant/lead' ) ),
				'nonce'       => wp_create_nonce( 'wp_rest' ),
				'language'    => $lang,
				'calculator'  => esc_url_raw( SMTP_I18n::page_url( 'fleet-savings-calculator', $lang ) ),
				'products'    => esc_url_raw( SMTP_I18n::products_url( $lang ) ),
				'contact'     => esc_url_raw( SMTP_I18n::page_url( 'contact', $lang ) ),
				'sourceUrl'   => esc_url_raw( $this->current_url() ),
				'leadCapture' => ! empty( SMTP_Settings::get()['assistant_lead_capture'] ),
				'messages'    => array(
					'error'       => SMTP_I18n::text( 'The assistant is temporarily unavailable. Please use the Contact page.', 'المساعد غير متاح مؤقتًا. يرجى استخدام صفحة تواصل معنا.', $lang ),
					'leadError'   => SMTP_I18n::text( 'We could not save your request. Please check the fields and try again.', 'تعذر حفظ طلبك. يرجى مراجعة البيانات والمحاولة مرة أخرى.', $lang ),
					'leadSending' => SMTP_I18n::text( 'Sending your request…', 'جارٍ إرسال طلبك…', $lang ),
					'thinking'    => SMTP_I18n::text( 'Thinking…', 'جارٍ إعداد الإجابة…', $lang ),
					'fallback'    => SMTP_I18n::text( 'Please contact Source More Technology for a detailed assessment.', 'يرجى التواصل مع سورس مور تكنولوجي للحصول على تقييم تفصيلي.', $lang ),
					'leadSuccess' => SMTP_I18n::text( 'Thank you. The Source More team will contact you shortly.', 'شكرًا لك. سيتواصل معك فريق سورس مور قريبًا.', $lang ),
				),
			)
		);
	}

	public function markup(): void {
		if ( ! $this->enabled() ) return;
		$lang = SMTP_I18n::language();
		?>
		<button class="smtp-ai-fab" id="smtp-ai-open" aria-label="<?php echo esc_attr( SMTP_I18n::text( 'Open Source More assistant', 'افتح مساعد سورس مور', $lang ) ); ?>" aria-controls="smtp-ai-panel" aria-expanded="false"><span><?php echo esc_html( SMTP_I18n::text( 'Ask Source More', 'اسأل سورس مور', $lang ) ); ?></span></button>
		<section class="smtp-ai-panel" id="smtp-ai-panel" role="dialog" aria-label="<?php echo esc_attr( SMTP_I18n::text( 'Source More Assistant', 'مساعد سورس مور', $lang ) ); ?>" dir="<?php echo 'ar' === $lang ? 'rtl' : 'ltr'; ?>" hidden>
			<header><div><strong><?php echo esc_html( SMTP_I18n::text( 'Source More Assistant', 'مساعد سورس مور', $lang ) ); ?></strong><small><?php echo esc_html( SMTP_I18n::text( 'MPS & technology guidance', 'إرشاد لحلول الطباعة والتكنولوجيا', $lang ) ); ?></small></div><button type="button" id="smtp-ai-close" aria-label="<?php echo esc_attr( SMTP_I18n::text( 'Close', 'إغلاق', $lang ) ); ?>">×</button></header>
			<div class="smtp-ai-messages" id="smtp-ai-messages" aria-live="polite"><div class="smtp-ai-msg bot"><?php echo esc_html( SMTP_I18n::text( 'Hello. I can help with print costs, Managed Print Services, document management, cloud, cybersecurity, products, and the next best step for your business.', 'مرحبًا. يمكنني مساعدتك في تكاليف الطباعة وخدمات الطباعة المُدارة وإدارة المستندات والسحابة والأمن السيبراني والمنتجات والخطوة الأنسب لأعمالك.', $lang ) ); ?></div></div>
			<div class="smtp-ai-quick">
				<button type="button" data-q="<?php echo esc_attr( SMTP_I18n::text( 'How can MPS reduce our costs?', 'كيف تساعد خدمات الطباعة المُدارة في خفض التكاليف؟', $lang ) ); ?>"><?php echo esc_html( SMTP_I18n::text( 'MPS savings', 'وفر الطباعة', $lang ) ); ?></button>
				<button type="button" data-action="calculator"><?php echo esc_html( SMTP_I18n::text( 'Calculate savings', 'احسب التوفير', $lang ) ); ?></button>
				<button type="button" data-action="lead"><?php echo esc_html( SMTP_I18n::text( 'Talk to sales', 'تحدث مع المبيعات', $lang ) ); ?></button>
			</div>
			<form id="smtp-ai-form" class="smtp-ai-question-form">
				<label class="screen-reader-text" for="smtp-ai-input"><?php echo esc_html( SMTP_I18n::text( 'Your question', 'سؤالك', $lang ) ); ?></label>
				<input id="smtp-ai-input" maxlength="500" autocomplete="off" placeholder="<?php echo esc_attr( SMTP_I18n::text( 'Ask a business technology question…', 'اسأل عن حلول التكنولوجيا لأعمالك…', $lang ) ); ?>" required>
				<button aria-label="<?php echo esc_attr( SMTP_I18n::text( 'Send', 'إرسال', $lang ) ); ?>"><?php echo esc_html( SMTP_I18n::text( 'Send', 'إرسال', $lang ) ); ?></button>
			</form>
			<form id="smtp-ai-lead-form" class="smtp-ai-lead-form" data-language="<?php echo esc_attr( $lang ); ?>" hidden novalidate>
				<div class="smtp-ai-lead-heading"><strong><?php echo esc_html( SMTP_I18n::text( 'Request a callback', 'اطلب مكالمة', $lang ) ); ?></strong><button type="button" id="smtp-ai-lead-cancel" aria-label="<?php echo esc_attr( SMTP_I18n::text( 'Cancel contact form', 'إلغاء نموذج التواصل', $lang ) ); ?>">×</button></div>
				<label><?php echo esc_html( SMTP_I18n::text( 'Company', 'الشركة', $lang ) ); ?><input name="company" autocomplete="organization" required></label>
				<label><?php echo esc_html( SMTP_I18n::text( 'Contact person', 'اسم مسؤول التواصل', $lang ) ); ?><input name="contact_name" autocomplete="name" required></label>
				<label><?php echo esc_html( SMTP_I18n::text( 'Business email', 'البريد الإلكتروني للعمل', $lang ) ); ?><input name="email" type="email" autocomplete="email" required></label>
				<label><?php echo esc_html( SMTP_I18n::text( 'Phone / WhatsApp', 'الهاتف / واتساب', $lang ) ); ?><input name="phone" type="tel" autocomplete="tel" dir="ltr" required></label>
				<label><?php echo esc_html( SMTP_I18n::text( 'How can we help?', 'كيف يمكننا مساعدتك؟', $lang ) ); ?><textarea name="message" rows="3" maxlength="1000"></textarea></label>
				<label class="smtp-ai-consent"><input type="checkbox" name="consent" value="1" required><span><?php echo esc_html( SMTP_I18n::text( 'I agree that Source More Technology may contact me about this request.', 'أوافق على تواصل سورس مور تكنولوجي معي بخصوص هذا الطلب.', $lang ) ); ?></span></label>
				<input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="smtp-ai-honeypot" aria-hidden="true">
				<button type="submit" class="smtp-ai-lead-submit"><?php echo esc_html( SMTP_I18n::text( 'Send request', 'إرسال الطلب', $lang ) ); ?></button>
				<div id="smtp-ai-lead-status" class="smtp-ai-lead-status" role="status" aria-live="polite"></div>
			</form>
			<small class="smtp-ai-note"><?php echo esc_html( SMTP_I18n::text( 'Guidance is indicative. Commercial recommendations require an assessment.', 'الإرشادات مبدئية، والتوصيات التجارية تتطلب تقييمًا متخصصًا.', $lang ) ); ?></small>
		</section>
		<?php
	}

	private function current_url(): string {
		$scheme = is_ssl() ? 'https://' : 'http://';
		$host   = sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ?? '' ) );
		$uri    = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ?? '/' ) );
		return $host ? $scheme . $host . $uri : home_url( '/' );
	}
}
