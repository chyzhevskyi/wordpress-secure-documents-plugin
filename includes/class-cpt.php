<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

class TDA_CPT {

    public const POST_TYPE = 'shared_document';

    public function __construct() {
        add_action('init', [$this, 'register']);
    }

    public function register(): void {
        $labels = [
            'name'          => 'Documents',
            'singular_name' => 'Document',
            'add_new'       => 'Add New Document',
            'edit_item'     => 'Edit Document',
        ];

        $args = [
            'labels'             => $labels,
            'public'             => false,
            'has_archive'        => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'supports'           => ['title', 'editor'],
            'rewrite'            => false,
        ];

        register_post_type(self::POST_TYPE, $args);
    }
}
