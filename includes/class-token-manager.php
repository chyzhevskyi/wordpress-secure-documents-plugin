<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

class TDA_Token_Manager {

    public const TTL = 3600;
    public const META_KEY = '_temp_access_link';

    public static function generate_hash(int $post_id, int $expires): string {
        return hash_hmac('sha256', (string) $post_id . (string) $expires, wp_salt());
    }

    public static function verify_hash(string $token, int $post_id, int $expires): bool {
        $expected_token = self::generate_hash($post_id, $expires);
        return hash_equals($expected_token, $token);
    }
}
