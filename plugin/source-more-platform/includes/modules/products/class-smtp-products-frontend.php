<?php
/**
 * Product Center public rendering and assets.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Products_Frontend {
	private SMTP_Products_Repository $repository;
	private bool $registered = false;

	public function __construct( SMTP_Products_Repository $repository ) {
		$this->repository = $repository;
	}

	/**
	 * Register public Product Center hooks exactly once.
	 */
	public function register_hooks(): void {
		if ( $this->registered ) {
			return;
		}

		add_shortcode( 'source_more_quote_form', array( $this, 'quote_shortcode' ) );
		add_shortcode( 'smtp_products', array( $this, 'products_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );

		$this->registered = true;
	}

	public function assets(): void {
		if ( ! is_singular( SMTP_Products_Content_Types::POST_TYPE ) && ! is_post_type_archive( SMTP_Products_Content_Types::POST_TYPE ) && ! is_page( 'products' ) ) {
			return;
		}

		wp_enqueue_style(
			'smtp-platform-products',
			SMTP_PLATFORM_URL . 'assets/css/products.css',
			array(),
			SMTP_PLATFORM_VERSION
		);

		wp_enqueue_script(
			'smtp-platform-products',
			SMTP_PLATFORM_URL . 'assets/js/products.js',
			array(),
			SMTP_PLATFORM_VERSION,
			true
		);

		wp_localize_script(
			'smtp-platform-products',
			'SMTPProducts',
			array(
				'rest'    => esc_url_raw( rest_url( 'source-more/v3/quote-request' ) ),
				'nonce'   => wp_create_nonce( 'wp_rest' ),
				'sending' => 'Sending your request…',
				'success' => 'Thank you. Our sales team will contact you shortly.',
				'error'   => 'We could not send your request. Please review the form and try again.',
			)
		);
	}

	/**
	 * Render the Product Center grid shortcode.
	 *
	 * @param array<string, mixed> $atts Shortcode attributes.
	 */
	public function products_shortcode( $atts = array() ): string {
		$atts  = shortcode_atts( array( 'limit' => 24 ), $atts, 'smtp_products' );
		$query = $this->repository->query_published( absint( $atts['limit'] ) );

		ob_start();
		echo '<div class="smtp-product-center">';

		if ( ! $query->have_posts() ) {
			echo '<div class="smtp-products-empty"><h3>Products are being prepared</h3><p>Add hardware or software from Source More CRM → Products. Published products will appear here automatically.</p></div>';
		} else {
			echo '<div class="smtp-product-grid">';

			while ( $query->have_posts() ) {
				$query->the_post();
				$product_id = get_the_ID();
				$product    = $this->repository->card_data( $product_id );

				echo '<article class="smtp-product-card">';

				if ( has_post_thumbnail() ) {
					echo '<a class="smtp-product-image" href="' . esc_url( get_permalink() ) . '">' . get_the_post_thumbnail( $product_id, 'medium_large' ) . '</a>';
				}

				echo '<div class="smtp-product-card__body"><div class="smtp-product-meta">' . esc_html( ucfirst( $product['type'] ) );

				if ( $product['brand'] ) {
					echo ' · ' . esc_html( $product['brand'] );
				}

				echo '</div><h3><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';

				if ( $product['model'] ) {
					echo '<p class="smtp-product-model">' . esc_html( $product['model'] ) . '</p>';
				}

				echo '<p>' . esc_html( $product['excerpt'] ) . '</p>';
				echo '<div class="smtp-product-card__footer"><span>' . esc_html( ucwords( str_replace( '-', ' ', $product['availability'] ) ) ) . '</span><a class="button" href="' . esc_url( get_permalink() ) . '">View Product</a></div></div></article>';
			}

			echo '</div>';
			wp_reset_postdata();
		}

		echo '</div>';

		return (string) ob_get_clean();
	}

	/**
	 * Render the reusable product quote form shortcode.
	 *
	 * @param array<string, mixed> $atts Shortcode attributes.
	 */
	public function quote_shortcode( $atts = array() ): string {
		$atts       = shortcode_atts( array( 'product_id' => 0 ), $atts, 'source_more_quote_form' );
		$product_id = absint( $atts['product_id'] );

		if ( ! $product_id && is_singular( SMTP_Products_Content_Types::POST_TYPE ) ) {
			$product_id = get_the_ID();
		}

		$product_name = $product_id ? get_the_title( $product_id ) : '';

		ob_start();
		?>
		<form class="smtp-quote-form" data-product-id="<?php echo esc_attr( $product_id ); ?>" novalidate>
			<div class="smtp-quote-grid">
				<label>Company name<input name="company" required autocomplete="organization"></label>
				<label>Contact person<input name="contact_name" required autocomplete="name"></label>
				<label>Business email<input name="email" type="email" required autocomplete="email"></label>
				<label>Phone / WhatsApp<input name="phone" required autocomplete="tel"></label>
				<label>Product<input name="product_name" value="<?php echo esc_attr( $product_name ); ?>" required></label>
				<label>Quantity<input name="quantity" type="number" min="1" value="1" required></label>
			</div>
			<label>Requirements / notes<textarea name="message" rows="4"></textarea></label>
			<label class="smtp-quote-consent"><input name="consent" type="checkbox" value="1" required> I agree that Source More Technology may contact me about this request.</label>
			<input class="smtp-honeypot" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
			<button class="btn btn-gold" type="submit">Request a Quote</button>
			<div class="smtp-quote-status" role="status" aria-live="polite"></div>
		</form>
		<?php
		return (string) ob_get_clean();
	}
}
