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

		$lang = SMTP_I18n::language();
		wp_enqueue_style( 'smtp-platform-products', SMTP_PLATFORM_URL . 'assets/css/products.css', array(), SMTP_PLATFORM_VERSION );
		wp_enqueue_script( 'smtp-platform-products', SMTP_PLATFORM_URL . 'assets/js/products.js', array(), SMTP_PLATFORM_VERSION, true );
		wp_localize_script(
			'smtp-platform-products',
			'SMTPProducts',
			array(
				'rest'     => esc_url_raw( rest_url( 'source-more/v3/quote-request' ) ),
				'nonce'    => wp_create_nonce( 'wp_rest' ),
				'language' => $lang,
				'sending'  => SMTP_I18n::text( 'Sending your request…', 'جارٍ إرسال طلبك…', $lang ),
				'success'  => SMTP_I18n::text( 'Thank you. Our sales team will contact you shortly.', 'شكرًا لك. سيتواصل معك فريق المبيعات قريبًا.', $lang ),
				'error'    => SMTP_I18n::text( 'We could not send your request. Please review the form and try again.', 'تعذر إرسال طلبك. يرجى مراجعة البيانات والمحاولة مرة أخرى.', $lang ),
			)
		);
	}

	/** @param array<string,mixed> $atts */
	public function products_shortcode( $atts = array() ): string {
		$lang  = SMTP_I18n::language();
		$atts  = shortcode_atts( array( 'limit' => 24 ), $atts, 'smtp_products' );
		$query = $this->repository->query_published( absint( $atts['limit'] ) );

		ob_start();
		echo '<div class="smtp-product-center">';

		if ( ! $query->have_posts() ) {
			echo '<div class="smtp-products-empty"><h3>' . esc_html( SMTP_I18n::text( 'Products are being prepared', 'جارٍ تجهيز المنتجات', $lang ) ) . '</h3><p>' . esc_html( SMTP_I18n::text( 'Published hardware, software, and services will appear here automatically.', 'ستظهر هنا الأجهزة والبرمجيات والخدمات المنشورة تلقائيًا.', $lang ) ) . '</p></div>';
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
				echo '<div class="smtp-product-card__body"><div class="smtp-product-meta">' . esc_html( $this->product_type_label( (string) $product['type'], $lang ) );
				if ( $product['brand'] ) {
					echo ' · ' . esc_html( $product['brand'] );
				}
				echo '</div><h3><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
				if ( $product['model'] ) {
					echo '<p class="smtp-product-model">' . esc_html( $product['model'] ) . '</p>';
				}
				echo '<p>' . esc_html( $product['excerpt'] ) . '</p>';
				echo '<div class="smtp-product-card__footer"><span>' . esc_html( $this->availability_label( (string) $product['availability'], $lang ) ) . '</span><a class="button" href="' . esc_url( get_permalink() ) . '">' . esc_html( SMTP_I18n::text( 'View Product', 'عرض المنتج', $lang ) ) . '</a></div></div></article>';
			}
			echo '</div>';
			wp_reset_postdata();
		}

		echo '</div>';
		return (string) ob_get_clean();
	}

	/** @param array<string,mixed> $atts */
	public function quote_shortcode( $atts = array() ): string {
		$lang       = SMTP_I18n::language();
		$atts       = shortcode_atts( array( 'product_id' => 0 ), $atts, 'source_more_quote_form' );
		$product_id = absint( $atts['product_id'] );
		if ( ! $product_id && is_singular( SMTP_Products_Content_Types::POST_TYPE ) ) {
			$product_id = get_the_ID();
		}
		$product_name = $product_id ? get_the_title( $product_id ) : '';

		ob_start();
		?>
		<form class="smtp-quote-form" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-language="<?php echo esc_attr( $lang ); ?>" novalidate>
			<div class="smtp-quote-grid">
				<label><?php echo esc_html( SMTP_I18n::text( 'Company name', 'اسم الشركة', $lang ) ); ?><input name="company" required autocomplete="organization"></label>
				<label><?php echo esc_html( SMTP_I18n::text( 'Contact person', 'اسم مسؤول التواصل', $lang ) ); ?><input name="contact_name" required autocomplete="name"></label>
				<label><?php echo esc_html( SMTP_I18n::text( 'Business email', 'البريد الإلكتروني للعمل', $lang ) ); ?><input name="email" type="email" required autocomplete="email"></label>
				<label><?php echo esc_html( SMTP_I18n::text( 'Phone / WhatsApp', 'الهاتف / واتساب', $lang ) ); ?><input name="phone" required autocomplete="tel" dir="ltr"></label>
				<label><?php echo esc_html( SMTP_I18n::text( 'Product', 'المنتج', $lang ) ); ?><input name="product_name" value="<?php echo esc_attr( $product_name ); ?>" required></label>
				<label><?php echo esc_html( SMTP_I18n::text( 'Quantity', 'الكمية', $lang ) ); ?><input name="quantity" type="number" min="1" value="1" required></label>
			</div>
			<label><?php echo esc_html( SMTP_I18n::text( 'Requirements / notes', 'المتطلبات / الملاحظات', $lang ) ); ?><textarea name="message" rows="4"></textarea></label>
			<label class="smtp-quote-consent"><input name="consent" type="checkbox" value="1" required> <?php echo esc_html( SMTP_I18n::text( 'I agree that Source More Technology may contact me about this request.', 'أوافق على تواصل سورس مور تكنولوجي معي بخصوص هذا الطلب.', $lang ) ); ?></label>
			<input class="smtp-honeypot" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
			<button class="btn btn-gold" type="submit"><?php echo esc_html( SMTP_I18n::text( 'Request a Quote', 'اطلب عرض سعر', $lang ) ); ?></button>
			<div class="smtp-quote-status" role="status" aria-live="polite"></div>
		</form>
		<?php
		return (string) ob_get_clean();
	}

	private function product_type_label( string $type, string $lang ): string {
		$labels = array(
			'hardware' => array( 'Hardware', 'أجهزة' ),
			'software' => array( 'Software', 'برمجيات' ),
			'service'  => array( 'Service / Subscription', 'خدمة / اشتراك' ),
		);
		$label = $labels[ $type ] ?? array( ucwords( str_replace( '-', ' ', $type ) ), ucwords( str_replace( '-', ' ', $type ) ) );
		return SMTP_I18n::text( $label[0], $label[1], $lang );
	}

	private function availability_label( string $availability, string $lang ): string {
		$labels = array(
			'available'     => array( 'Available', 'متاح' ),
			'limited'       => array( 'Limited availability', 'متاح بكمية محدودة' ),
			'pre-order'     => array( 'Pre-order', 'طلب مسبق' ),
			'on-request'    => array( 'Available on request', 'متاح عند الطلب' ),
			'out-of-stock'  => array( 'Out of stock', 'غير متوفر حاليًا' ),
		);
		$label = $labels[ $availability ] ?? array( ucwords( str_replace( '-', ' ', $availability ) ), ucwords( str_replace( '-', ' ', $availability ) ) );
		return SMTP_I18n::text( $label[0], $label[1], $lang );
	}
}
