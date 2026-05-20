<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

class TDA_Frontend {

    public function __construct() {
        add_action('template_redirect', [$this, 'handle_request'], 1);
    }

    public function handle_request(): void {
        if (!isset($_GET['view_doc'], $_GET['token'], $_GET['expires'])) {
            return;
        }

        $post_id = absint($_GET['view_doc']);
        $token   = sanitize_text_field($_GET['token']);
        $expires = absint($_GET['expires']);

        if ($post_id === 0 || $expires === 0 || empty($token)) {
            wp_die('Invalid request parameters.', 'Error', ['response' => 400]);
        }

        if (get_post_type($post_id) !== TDA_CPT::POST_TYPE) {
            wp_die('Document not found.', 'Error', ['response' => 404]);
        }

        if (time() > $expires) {
            wp_die('Link expired.', 'Access denied', ['response' => 403]);
        }

        if (!TDA_Token_Manager::verify_hash($token, $post_id, $expires)) {
            wp_die('Invalid access token.', 'Access denied', ['response' => 403]);
        }

        $this->load_template($post_id);
    }

    private function load_template(int $post_id): void {
        $template_path = locate_template('tda-templates/single-document.php');
        
        if (!$template_path) {
            $template_path = TDA_PLUGIN_DIR . 'templates/single-document.php';
        }

        global $post;
        $post = get_post($post_id);
        setup_postdata($post);

        require $template_path;
        exit;
    }
}