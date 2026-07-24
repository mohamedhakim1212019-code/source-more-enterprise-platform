<?php
if (!defined('ABSPATH')) exit;

function smt_contact_submit() {
    if (!check_ajax_referer('smt_contact_submit','nonce',false)) {
        wp_send_json_error(['message'=>smt_t('Security validation failed. Please refresh and try again.','فشل التحقق الأمني. حدّث الصفحة وحاول مرة أخرى.')],403);
    }
    $honeypot = isset($_POST['website']) ? trim((string) wp_unslash($_POST['website'])) : '';
    if ($honeypot !== '') wp_send_json_success(['message'=>smt_t('Thank you.','شكرًا لك.')]);

    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $company = sanitize_text_field(wp_unslash($_POST['company'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
    $interest = sanitize_text_field(wp_unslash($_POST['interest'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
    $consent = !empty($_POST['consent']);

    if (!$name || !is_email($email) || !$message || !$consent) {
        wp_send_json_error(['message'=>smt_t('Please complete all required fields and accept the privacy notice.','يرجى استكمال الحقول المطلوبة والموافقة على إشعار الخصوصية.')],422);
    }

    $to = smt_get_setting('lead_email') ?: smt_get_setting('email') ?: get_option('admin_email');
    $subject = sprintf('[Source More Website] %s — %s', $interest ?: 'New inquiry', $name);
    $body = "New website inquiry\n\nName: {$name}\nCompany: {$company}\nEmail: {$email}\nPhone: {$phone}\nInterest: {$interest}\n\nMessage:\n{$message}\n";
    $headers = ['Content-Type: text/plain; charset=UTF-8', 'Reply-To: '.$name.' <'.$email.'>'];
    $sent = wp_mail($to,$subject,$body,$headers);
    if (!$sent) wp_send_json_error(['message'=>smt_t('Your message could not be sent. Please contact us by email or WhatsApp.','تعذر إرسال رسالتك. يرجى التواصل عبر البريد الإلكتروني أو واتساب.')],500);

    wp_send_json_success(['message'=>smt_t('Thank you. A Source More consultant will contact you shortly.','شكرًا لك. سيتواصل معك أحد مستشاري سورس مور قريبًا.')]);
}
add_action('wp_ajax_smt_contact_submit','smt_contact_submit');
add_action('wp_ajax_nopriv_smt_contact_submit','smt_contact_submit');
