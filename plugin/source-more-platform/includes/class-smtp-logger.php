<?php
if (!defined('ABSPATH')) { exit; }
final class SMTP_Logger {
    const OPTION = 'smtp_platform_logs';
    public static function info(string $message, array $context = []): void { self::write('info', $message, $context); }
    public static function warning(string $message, array $context = []): void { self::write('warning', $message, $context); }
    public static function error(string $message, array $context = []): void { self::write('error', $message, $context); }
    private static function write(string $level, string $message, array $context): void {
        $opts = get_option('smtp_platform_options', []);
        if (empty($opts['logging_enabled']) && $level === 'info') { return; }
        unset($context['token'], $context['nonce'], $context['api_key']);
        $logs = get_option(self::OPTION, []);
        if (!is_array($logs)) { $logs = []; }
        array_unshift($logs, ['time' => current_time('mysql'), 'level' => $level, 'message' => sanitize_text_field($message), 'context' => $context]);
        update_option(self::OPTION, array_slice($logs, 0, 200), false);
        if (defined('WP_DEBUG') && WP_DEBUG && $level === 'error') { error_log('[Source More Platform] ' . $message); }
    }
    public static function get(): array { $logs = get_option(self::OPTION, []); return is_array($logs) ? $logs : []; }
    public static function clear(): void { delete_option(self::OPTION); }
}
