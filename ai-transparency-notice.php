<?php
/*
Plugin Name: AI Transparency Notice
Plugin URI: https://github.com/ValentinGratz/ai-transparency-notice
Description: Ajoute une mention de transparence IA dans les contenus WordPress.
Version: 2.0.0
Author: Valentin
License: GPL-2.0
*/

if (!defined('ABSPATH')) {
    exit;
}

define('AITN_VERSION', '2.0.0');
define('AITN_PATH', plugin_dir_path(__FILE__));
define('AITN_URL', plugin_dir_url(__FILE__));


require_once AITN_PATH . 'includes/admin-settings.php';
require_once AITN_PATH . 'includes/meta-box.php';
require_once AITN_PATH . 'includes/frontend.php';
require_once AITN_PATH . 'includes/shortcode.php';



register_activation_hook(__FILE__, 'aitn_activate');


function aitn_activate() {

    if (!get_option('aitn_settings')) {

        add_option('aitn_settings', [

            'enabled' => 'yes',

            'position' => 'after',

            'text' =>
            '🤖 <strong>Transparence :</strong><br>
            Cet article a été réalisé avec l’aide d’outils d’intelligence artificielle.
            Le contenu a été vérifié, corrigé et édité avant publication.'

        ]);

    }

}