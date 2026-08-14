<?php

if (!defined('ABSPATH')) {
    exit;
}

function vtnai_notice($level) {
    $settings = get_option('vtnai_settings', [
        'enabled'  => 'yes',
        'position' => 'after',
        'text'     => ''
    ]);

    if ('important' === $level) {
        $notice = '<div class="vtnai-box">\n\n🤖 <strong>' . esc_html__('Transparency:', 'ai-transparency-notice') . '</strong><br>\n\n' . esc_html__('A significant part of this article was created with the help of artificial intelligence tools.', 'ai-transparency-notice') . '<br>' . esc_html__('The content was reviewed before publication.', 'ai-transparency-notice') . '\n\n</div>';

        return wp_kses_post($notice);
    }

    $text = isset($settings['text']) ? wp_kses_post($settings['text']) : '';

    $notice = '<div class="vtnai-box">\n\n' . $text . '\n\n</div>';

    return wp_kses_post($notice);
}

function vtnai_display_content($content) {
    if (!is_singular('post')) {
        return $content;
    }

    $post_id = get_the_ID();

    if (!$post_id) {
        return $content;
    }

    $level = get_post_meta($post_id, '_vtnai_level', true);

    if (!$level || 'none' === $level) {
        return $content;
    }

    $settings = get_option('vtnai_settings', [
        'enabled'  => 'yes',
        'position' => 'after',
        'text'     => ''
    ]);

    if (isset($settings['enabled']) && 'no' === $settings['enabled']) {
        return $content;
    }

    $notice = vtnai_notice($level);

    if (isset($settings['position']) && 'before' === $settings['position']) {
        return $notice . $content;
    }

    if (isset($settings['position']) && 'both' === $settings['position']) {
        return $notice . $content . $notice;
    }

    return $content . $notice;
}

add_filter('the_content', 'vtnai_display_content');

function vtnai_load_css() {
    if (!is_singular()) {
        return;
    }

    wp_enqueue_style(
        'vtnai-style',
        VTNAI_URL . 'assets/css/style.css',
        [],
        VTNAI_VERSION
    );
}

add_action('wp_enqueue_scripts', 'vtnai_load_css');
