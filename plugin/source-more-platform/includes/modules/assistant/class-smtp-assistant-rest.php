<?php
/** AI Assistant REST endpoints. @package SourceMorePlatform */
if ( ! defined( 'ABSPATH' ) ) exit;

final class SMTP_Assistant_REST {
	private SMTP_Assistant_Service $service;
	private SMTP_Assistant_Lead_Converter $lead_converter;
	private bool $registered = false;
	public function __construct( SMTP_Assistant_Service $service, SMTP_Assistant_Lead_Converter $lead_converter ) { $this->service = $service; $this->lead_converter = $lead_converter; }
	public function register_hooks(): void { if ( $this->registered ) return; add_action( 'rest_api_init', array( $this, 'routes' ) ); $this->registered = true; }
	public function routes(): void {
		foreach ( array( 'source-more/v1', 'source-more/v2' ) as $namespace ) register_rest_route( $namespace, '/assistant', array( 'methods'=>'POST', 'callback'=>array($this,'reply'), 'permission_callback'=>'__return_true' ) );
		foreach ( array( 'source-more/v2', 'source-more/v3' ) as $namespace ) register_rest_route( $namespace, '/assistant/lead', array( 'methods'=>'POST', 'callback'=>array($this,'capture_lead'), 'permission_callback'=>'__return_true' ) );
	}
	public function reply( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$data = $request->get_json_params(); $data = is_array($data) ? $data : $request->get_params(); $lang = SMTP_I18n::request_language($data);
		$security = $this->security( $request, 'assistant', 20, 600, $lang ); if ( is_wp_error( $security ) ) return $security;
		$result = $this->service->reply( sanitize_text_field($data['message']??''), absint($data['conversation_id']??0), sanitize_text_field($data['conversation_token']??''), $lang );
		return is_wp_error($result) ? $result : new WP_REST_Response($result,200);
	}
	public function capture_lead( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$data = $request->get_json_params(); $data = is_array($data) ? $data : $request->get_params(); $lang = SMTP_I18n::request_language($data); $data['language']=$lang;
		$security = $this->security( $request, 'assistant_lead', 5, 900, $lang ); if ( is_wp_error($security) ) return $security;
		$result = $this->lead_converter->convert($data); return is_wp_error($result) ? $result : new WP_REST_Response($result,201);
	}
	private function security( WP_REST_Request $request, string $bucket, int $limit, int $window, string $lang ): bool|WP_Error {
		if ( ! wp_verify_nonce( $request->get_header('X-WP-Nonce'), 'wp_rest' ) ) return new WP_Error('bad_nonce',SMTP_I18n::text('Security check failed.','فشل التحقق الأمني.',$lang),array('status'=>403));
		if ( ! SMTP_Rate_Limiter::check($bucket,$limit,$window) ) return new WP_Error('rate_limited',SMTP_I18n::text('Too many requests. Please try again shortly.','تم إرسال عدد كبير من الطلبات. يرجى المحاولة بعد قليل.',$lang),array('status'=>429));
		return true;
	}
}
