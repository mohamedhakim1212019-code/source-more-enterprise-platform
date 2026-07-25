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

		wp_enqueue_style( 'smtp-platform', SMTP_PLATFORM_URL . 'assets/css/platform.css', array(), SMTP_PLATFORM_VERSION );
		wp_enqueue_script( 'smtp-platform', SMTP_PLATFORM_URL . 'assets/js/platform.js', array(), SMTP_PLATFORM_VERSION, true );
		wp_localize_script(
			'smtp-platform',
			'SMTPPlatform',
			array(
				'rest'     => esc_url_raw( rest_url( 'source-more/v1/lead' ) ),
				'nonce'    => wp_create_nonce( 'wp_rest' ),
				'messages' => array(
					'sending' => 'Preparing your report…',
					'success' => 'Your report is ready.',
					'error'   => 'We could not prepare the report. Please check the fields and try again.',
				),
			)
		);
	}

	public function shortcode(): string {
		ob_start();
		?>
		<section class="smtp-lead-capture" id="smtp-lead-capture">
			<div class="smtp-lead-heading">
				<span class="smtp-step">02</span>
				<div><h2>Receive your branded savings report</h2><p>Enter your business details to save the result, download the PDF, and request a free fleet assessment.</p></div>
			</div>
			<form id="smtp-lead-form" class="smtp-lead-form" novalidate>
				<div class="smtp-form-grid">
					<label>Company name<input name="company" type="text" autocomplete="organization" required></label>
					<label>Contact person<input name="contact_name" type="text" autocomplete="name" required></label>
					<label>Business email<input name="email" type="email" autocomplete="email" required></label>
					<label>Phone / WhatsApp<input name="phone" type="tel" autocomplete="tel" required></label>
					<label>Industry<select name="industry"><option value="">Select industry</option><option>Banking &amp; Financial Services</option><option>Manufacturing</option><option>Healthcare</option><option>Education</option><option>Government</option><option>Professional Services</option><option>Retail &amp; Distribution</option><option>Other</option></select></label>
					<label>Number of locations<input name="locations" type="number" min="1" value="1"></label>
				</div>
				<label class="smtp-consent"><input type="checkbox" name="consent" value="1" required><span>I agree that Source More Technology may contact me about this assessment. My data will not be sold to third parties.</span></label>
				<input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="smtp-honeypot" aria-hidden="true">
				<button type="submit" class="btn btn-gold smtp-submit"><i class="fa-solid fa-file-pdf"></i> Create My Savings Report</button>
				<div class="smtp-form-status" id="smtp-form-status" role="status" aria-live="polite"></div>
			</form>
			<div class="smtp-success" id="smtp-success" hidden>
				<i class="fa-solid fa-circle-check"></i><h3>Your report is ready</h3>
				<p>A copy of the report link has also been sent to your business email when WordPress email delivery is configured.</p>
				<div class="smtp-success-actions"><a id="smtp-download-report" class="btn btn-gold" href="#">Download PDF Report</a><a class="btn smtp-secondary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Book a Free Fleet Assessment</a></div>
			</div>
		</section>
		<?php
		return (string) ob_get_clean();
	}
}
