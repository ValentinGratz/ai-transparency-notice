<?php
/*
Plugin Name: Valentin Transparency Notice for AI Content
Plugin URI: https://github.com/ValentinGratz/ai-transparency-notice
Description: Helps website owners disclose when content has been created or assisted by artificial intelligence.
Version: 2.1.0
Author: Valentin Grätz
License: GPL-2.0-or-later
Text Domain: ai-transparency-notice
*/

if (!defined('ABSPATH')) {
    exit;
}

define('VTNAI_VERSION', '2.1.0');
define('VTNAI_PATH', plugin_dir_path(__FILE__));
define('VTNAI_URL', plugin_dir_url(__FILE__));

require_once VTNAI_PATH . 'includes/admin-settings.php';
require_once VTNAI_PATH . 'includes/meta-box.php';
require_once VTNAI_PATH . 'includes/frontend.php';
require_once VTNAI_PATH . 'includes/shortcode.php';

register_activation_hook(__FILE__, 'vtnai_activate');

function vtnai_activate() {
    if (!get_option('vtnai_settings')) {
        add_option('vtnai_settings', [
            'enabled'  => 'yes',
            'position' => 'after',
            'text'     => '🤖 <strong>Transparency:</strong><br>\n            This article was created with the help of artificial intelligence tools.\n            The content was reviewed, corrected, and edited before publication.'
        ]);
    }
}
