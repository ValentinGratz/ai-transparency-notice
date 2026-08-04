<?php

if (!defined('ABSPATH')) {
    exit;
}

function aitn_add_meta_box() {
    add_meta_box(
        'aitn_box',
        'AI Transparency',
        'aitn_meta_box_html',
        'post',
        'side'
    );
}
add_action('add_meta_boxes', 'aitn_add_meta_box');

function aitn_meta_box_html($post) {
    $value = get_post_meta($post->ID, '_aitn_level', true);

    wp_nonce_field('aitn_save_meta', 'aitn_nonce');
    ?>
    <select name="aitn_level" style="width:100%">
        <option value="none" <?php selected($value, 'none'); ?>>No AI</option>
        <option value="assist" <?php selected($value, 'assist'); ?>>AI assistance</option>
        <option value="important" <?php selected($value, 'important'); ?>>Significant AI use</option>
    </select>
    <?php
}

function aitn_save_meta($post_id) {
    if (!isset($_POST['aitn_nonce'])) {
        return;
    }

    if (!wp_verify_nonce(wp_unslash($_POST['aitn_nonce']), 'aitn_save_meta')) {
        return;
    }

    if (isset($_POST['aitn_level'])) {
        update_post_meta(
            $post_id,
            '_aitn_level',
            sanitize_text_field(wp_unslash($_POST['aitn_level']))
        );
    }
}

add_action('save_post', 'aitn_save_meta');
