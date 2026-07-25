<?php
if (!defined('ABSPATH')) { exit; }
final class SMTP_Rate_Limiter {
    public static function check(string $bucket, int $limit = 10, int $window = 600): bool {
        $ip = self::ip();
        $key = 'smtp_rl_' . md5($bucket . '|' . $ip);
        $data = get_transient($key);
        if (!is_array($data)) { set_transient($key, ['count' => 1], $window); return true; }
        if ((int)$data['count'] >= $limit) { return false; }
        $data['count'] = (int)$data['count'] + 1;
        set_transient($key, $data, $window);
        return true;
    }

    /**
     * Backward-compatible alias used by product quote endpoints.
     */
    public static function allow(string $bucket, int $limit = 10, int $window = 600): bool {
        return self::check($bucket, $limit, $window);
    }
    private static function ip(): string {
        $ip = isset($_SERVER['REMOTE_ADDR']) ? wp_unslash($_SERVER['REMOTE_ADDR']) : 'unknown';
        return preg_replace('/[^0-9a-fA-F:\.]/', '', $ip) ?: 'unknown';
    }
}
