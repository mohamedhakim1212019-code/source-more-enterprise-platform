<?php
/**
 * Product Center REST endpoints.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Products_REST {
	private SMTP_Products_Repository $repository;
	private bool $registered = false;

	public function __construct( SMTP_Products_Repository $repository ) {
		$this->repository = $repository;
	}

	public function register_hooks(): void {
		if ( $this->registered ) {
			return;
		}
		add_action( 'rest_api_init', array( $this, 'routes' ) );
		$this->registered = true;
	}

	public function routes(): void {
		foreach ( array( 'source-more/v2', 'source-more/v3' ) as $namespace ) {
			register_rest_route(
				$namespace,
				'/quote-request',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'create_quote' ),
					'permission_callback' => '__return_true',
				)
			);
		}
	}

	public function create_quote( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$data = $request->get_json_params();
		$data = is_array( $data ) ? $data : $request->get_params();
		$lang = SMTP_I18n::request_language( $data );

		if ( ! empty( $data['website'] ) ) {
			return new WP_Error( 'spam', SMTP_I18n::text( 'Request rejected.', 'تم رفض الطلب.', $lang ), array( 'status' => 400 ) );
		}

		if ( class_exists( 'SMTP_Rate_Limiter' ) && ! SMTP_Rate_Limiter::allow( 'rfq', 5, HOUR_IN_SECONDS ) ) {
			return new WP_Error( 'rate_limited', SMTP_I18n::text( 'Please wait before sending another request.', 'يرجى الانتظار قبل إرسال طلب آخر.', $lang ), array( 'status' => 429 ) );
		}

		foreach ( array( 'company', 'contact_name', 'email', 'phone', 'product_name', 'quantity', 'consent' ) as $key ) {
			if ( empty( $data[ $key ] ) ) {
				return new WP_Error( 'missing_field', SMTP_I18n::text( 'Please complete all required fields.', 'يرجى استكمال جميع الحقول المطلوبة.', $lang ), array( 'status' => 422 ) );
			}
		}

		$email = sanitize_email( $data['email'] );
		if ( ! is_email( $email ) ) {
			return new WP_Error( 'invalid_email', SMTP_I18n::text( 'Please enter a valid email address.', 'يرجى إدخال بريد إلكتروني صحيح.', $lang ), array( 'status' => 422 ) );
		}

		$product_name = sanitize_text_field( $data['product_name'] );
		$values       = array(
			'company'      => sanitize_text_field( $data['company'] ),
			'contact_name' => sanitize_text_field( $data['contact_name'] ),
			'email'        => $email,
			'phone'        => sanitize_text_field( $data['phone'] ),
			'product_name' => $product_name,
			'product_id'   => absint( $data['product_id'] ?? 0 ),
			'quantity'     => max( 1, absint( $data['quantity'] ) ),
			'message'      => sanitize_textarea_field( $data['message'] ?? '' ),
			'status'       => 'new',
			'language'     => $lang,
			'source_url'   => esc_url_raw( $data['source_url'] ?? '' ),
			'consent_at'   => current_time( 'mysql' ),
		);

		$post_id = $this->repository->create_quote_request( $values );
		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		$options = class_exists( 'SMTP_Settings' ) ? SMTP_Settings::get() : array( 'notification_email' => get_option( 'admin_email' ) );
		$notification_email = sanitize_email( $options['notification_email'] ?? get_option( 'admin_email' ) );
		if ( ! is_email( $notification_email ) ) {
			$notification_email = sanitize_email( get_option( 'admin_email' ) );
		}

		$admin_subject = 'New product quote request: ' . $product_name;
		$admin_body    = "A new product quote request has been submitted.\n\nCompany: {$values['company']}\nContact: {$values['contact_name']}\nEmail: {$email}\nPhone: {$values['phone']}\nProduct: {$product_name}\nQuantity: {$values['quantity']}\nLanguage: {$lang}\n\nMessage:\n{$values['message']}\n\nView request: " . admin_url( 'post.php?post=' . $post_id . '&action=edit' );
		$admin_sent    = is_email( $notification_email ) && wp_mail( $notification_email, $admin_subject, $admin_body, array( 'Reply-To: ' . $values['contact_name'] . ' <' . $email . '>' ) );

		if ( 'ar' === $lang ) {
			$customer_subject = 'تم استلام طلب عرض السعر: ' . $product_name;
			$customer_body    = "الأستاذ/ة {$values['contact_name']}،\n\nشكرًا لتواصلك مع سورس مور تكنولوجي. تم استلام طلب عرض السعر وسيقوم فريق المبيعات بمراجعته والتواصل معك قريبًا.\n\nالمنتج: {$product_name}\nالكمية: {$values['quantity']}\nرقم الطلب: {$post_id}\n\nمع تحيات،\nسورس مور تكنولوجي\nمصدر واحد. قيمة أكبر.";
		} else {
			$customer_subject = 'We received your quote request: ' . $product_name;
			$customer_body    = "Dear {$values['contact_name']},\n\nThank you for contacting Source More Technology. We received your quotation request and our sales team will review it shortly.\n\nProduct: {$product_name}\nQuantity: {$values['quantity']}\nRequest reference: {$post_id}\n\nRegards,\nSource More Technology\nOne Source, More Value.";
		}

		$customer_headers = is_email( $notification_email ) ? array( 'Reply-To: Source More Technology <' . $notification_email . '>' ) : array();
		$customer_sent    = wp_mail( $email, $customer_subject, $customer_body, $customer_headers );

		if ( class_exists( 'SMTP_Logger' ) ) {
			SMTP_Logger::info( 'Quote request created', array( 'id' => $post_id, 'product' => $product_name, 'language' => $lang, 'notification_email' => $notification_email, 'admin_email_sent' => (bool) $admin_sent, 'customer_email_sent' => (bool) $customer_sent ) );
			if ( ! $admin_sent ) {
				SMTP_Logger::warning( 'Quote request admin email failed', array( 'id' => $post_id, 'recipient' => $notification_email ) );
			}
			if ( ! $customer_sent ) {
				SMTP_Logger::warning( 'Quote request customer confirmation failed', array( 'id' => $post_id, 'recipient' => $email ) );
			}
		}

		do_action( 'smtp_product_quote_request_created', $post_id, $values );
		return new WP_REST_Response(
			array(
				'success'        => true,
				'request_id'     => $post_id,
				'message'        => SMTP_I18n::text( 'Thank you. Our sales team will contact you shortly.', 'شكرًا لك. سيتواصل معك فريق المبيعات قريبًا.', $lang ),
				'email_delivery' => ( $admin_sent && $customer_sent ) ? 'sent' : 'pending',
			),
			201
		);
	}
}
