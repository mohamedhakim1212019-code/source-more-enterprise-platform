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
		if ( $this->registered ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'wp_footer', array( $this, 'markup' ) );
		$this->registered = true;
	}

	public function enabled(): bool {
		$options = SMTP_Settings::get();
		return ! empty( $options['assistant_enabled'] );
	}

	public function assets(): void {
		if ( ! $this->enabled() ) {
			return;
		}

		wp_enqueue_style( 'smtp-assistant', SMTP_PLATFORM_URL . 'assets/css/assistant.css', array(), SMTP_PLATFORM_VERSION );
		wp_enqueue_script( 'smtp-assistant', SMTP_PLATFORM_URL . 'assets/js/assistant.js', array(), SMTP_PLATFORM_VERSION, true );
		wp_localize_script(
			'smtp-assistant',
			'SMTPAssistant',
			array(
				'rest'       => esc_url_raw( rest_url( 'source-more/v2/assistant' ) ),
				'leadRest'   => esc_url_raw( rest_url( 'source-more/v2/assistant/lead' ) ),
				'nonce'      => wp_create_nonce( 'wp_rest' ),
				'calculator' => esc_url_raw( home_url( '/fleet-savings-calculator/' ) ),
				'products'   => esc_url_raw( home_url( '/products/' ) ),
				'contact'    => esc_url_raw( home_url( '/contact/' ) ),
				'sourceUrl'  => esc_url_raw( $this->current_url() ),
				'leadCapture'=> ! empty( SMTP_Settings::get()['assistant_lead_capture'] ),
				'messages'   => array(
					'error'       => 'The assistant is temporarily unavailable. Please use the Contact page.',
					'leadError'   => 'We could not save your request. Please check the fields and try again.',
					'leadSending' => 'Sending your request…',
				),
			)
		);
	}

	public function markup(): void {
		if ( ! $this->enabled() ) {
			return;
		}
		?>
		<button class="smtp-ai-fab" id="smtp-ai-open" aria-label="Open Source More assistant" aria-controls="smtp-ai-panel" aria-expanded="false"><span>Ask Source More</span></button>
		<section class="smtp-ai-panel" id="smtp-ai-panel" role="dialog" aria-label="Source More Assistant" hidden>
			<header><div><strong>Source More Assistant</strong><small>MPS &amp; technology guidance</small></div><button type="button" id="smtp-ai-close" aria-label="Close">×</button></header>
			<div class="smtp-ai-messages" id="smtp-ai-messages" aria-live="polite"><div class="smtp-ai-msg bot">Hello. I can help with print costs, Managed Print Services, document management, cloud, cybersecurity, products, and the next best step for your business.</div></div>
			<div class="smtp-ai-quick">
				<button type="button" data-q="How can MPS reduce our costs?">MPS savings</button>
				<button type="button" data-action="calculator">Calculate savings</button>
				<button type="button" data-action="lead">Talk to sales</button>
			</div>
			<form id="smtp-ai-form" class="smtp-ai-question-form">
				<label class="screen-reader-text" for="smtp-ai-input">Your question</label>
				<input id="smtp-ai-input" maxlength="500" autocomplete="off" placeholder="Ask a business technology question…" required>
				<button aria-label="Send">Send</button>
			</form>
			<form id="smtp-ai-lead-form" class="smtp-ai-lead-form" hidden novalidate>
				<div class="smtp-ai-lead-heading"><strong>Request a callback</strong><button type="button" id="smtp-ai-lead-cancel" aria-label="Cancel contact form">×</button></div>
				<label>Company<input name="company" autocomplete="organization" required></label>
				<label>Contact person<input name="contact_name" autocomplete="name" required></label>
				<label>Business email<input name="email" type="email" autocomplete="email" required></label>
				<label>Phone / WhatsApp<input name="phone" type="tel" autocomplete="tel" required></label>
				<label>How can we help?<textarea name="message" rows="3" maxlength="1000"></textarea></label>
				<label class="smtp-ai-consent"><input type="checkbox" name="consent" value="1" required><span>I agree that Source More Technology may contact me about this request.</span></label>
				<input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="smtp-ai-honeypot" aria-hidden="true">
				<button type="submit" class="smtp-ai-lead-submit">Send request</button>
				<div id="smtp-ai-lead-status" class="smtp-ai-lead-status" role="status" aria-live="polite"></div>
			</form>
			<small class="smtp-ai-note">Guidance is indicative. Commercial recommendations require an assessment.</small>
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
