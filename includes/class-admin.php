<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

class TDA_Admin {

    private const AJAX_ACTION = 'tda_generate_link';

    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_meta_box']);
        add_action('wp_ajax_' . self::AJAX_ACTION, [$this, 'ajax_handler']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function add_meta_box(): void {
        add_meta_box(
            'tda_access_manager',
            'Access Management',
            [$this, 'render_meta_box'],
            TDA_CPT::POST_TYPE,
            'side',
            'high'
        );
    }

    public function render_meta_box(\WP_Post $post): void {
        $current_link = get_post_meta($post->ID, TDA_Token_Manager::META_KEY, true);
        $nonce_field  = wp_create_nonce(self::AJAX_ACTION . '_' . $post->ID);
        ?>
        <div class="tda-meta-box" style="padding: 10px 0;">
            <p>
                <input type="text" id="tda_link_input" readonly="readonly" class="large-text" 
                       value="<?php echo esc_url($current_link ?: ''); ?>" 
                       placeholder="Link not generated yet" style="margin-bottom: 10px;" />
            </p>
            <p>
                <button type="button" class="button button-primary" id="tda_btn_generate" 
                        data-post-id="<?php echo absint($post->ID); ?>" 
                        data-nonce="<?php echo esc_attr($nonce_field); ?>">
                    Generate Link (1 hour)
                </button>
            </p>
            <span class="spinner" id="tda_spinner" style="float: none; margin: 0;"></span>
        </div>
        <?php
    }

    public function ajax_handler(): void {
        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
        
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), self::AJAX_ACTION . '_' . $post_id)) {
            wp_send_json_error('Security error', 403);
        }

        if (!current_user_can('edit_post', $post_id)) {
            wp_send_json_error('Insufficient permissions', 403);
        }

        $expires = time() + TDA_Token_Manager::TTL;
        $token   = TDA_Token_Manager::generate_hash($post_id, $expires);

        $link = add_query_arg([
            'view_doc' => $post_id,
            'token'    => $token,
            'expires'  => $expires,
        ], home_url('/'));

        update_post_meta($post_id, TDA_Token_Manager::META_KEY, esc_url_raw($link));

        wp_send_json_success(['link' => esc_url_raw($link)]);
    }

    public function enqueue_scripts(string $hook): void {
        global $typenow;
        if ($typenow !== TDA_CPT::POST_TYPE) {
            return;
        }

        wp_enqueue_script(
            'tda-admin-js',
            TDA_PLUGIN_URL . 'assets/js/admin.js',
            [],
            '1.0.0',
            true
        );

        wp_localize_script('tda-admin-js', 'tdaData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'action'  => self::AJAX_ACTION,
        ]);
    }
}
