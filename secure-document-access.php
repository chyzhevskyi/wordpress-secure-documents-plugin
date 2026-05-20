<?php
/**
 * Plugin Name: Secure Document Access
 * Description: Secure document access system via unique links.
 * Version: 1.0.0
 * Requires PHP: 8.2
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

define('TDA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TDA_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once TDA_PLUGIN_DIR . 'includes/class-cpt.php';
require_once TDA_PLUGIN_DIR . 'includes/class-token-manager.php';
require_once TDA_PLUGIN_DIR . 'includes/class-admin.php';
require_once TDA_PLUGIN_DIR . 'includes/class-frontend.php';

add_action('plugins_loaded', function() {
    new TDA_CPT();
    new TDA_Admin();
    new TDA_Frontend();
});
