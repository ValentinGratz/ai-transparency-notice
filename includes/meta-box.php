<?php

if (!defined('ABSPATH')) {
    exit;
}

function vtnai_add_meta_box() {
    add_meta_box(
        'vtnai_box',
        __('AI Content Transparency', 'ai-transparency-notice'),
        'vtnai_meta_box_html',
        'post',
        'side'
    );
}
add_action('add_meta_boxes', 'vtnai_add_meta_box');

function vtnai_meta_box_html($post) {
    $value = get_post_meta($post->ID, '_vtnai_level', true);

    wp_nonce_field('vtnai_save_meta', 'vtnai_nonce');
    ?>
    <label for="vtnai-level"><?php echo esc_html__('AI assistance level', 'ai-transparency-notice'); ?></label>
    <select id="vtnai-level" name="vtnai_level" style="width:100%">
        <option value="none" <?php selected($value, 'none'); ?>><?php echo esc_html__('No AI', 'ai-transparency-notice'); ?></option>
        <option value="assist" <?php selected($value, 'assist'); ?>><?php echo esc_html__('AI assistance', 'ai-transparency-notice'); ?></option>
        <option value="important" <?php selected($value, 'important'); ?>><?php echo esc_html__('Significant AI use', 'ai-transparency-notice'); ?></option>
    </select>
    <?php
}

function vtnai_save_meta($post_id) {
    $nonce = isset($_POST['vtnai_nonce'])
        ? sanitize_text_field(wp_unslash($_POST['vtnai_nonce']))
        : '';

    if (!$nonce || !wp_verify_nonce($nonce, 'vtnai_save_meta')) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    if (isset($_POST['vtnai_level'])) {
        $level = sanitize_key(wp_unslash($_POST['vtnai_level']));
        $allowed_levels = ['none', 'assist', 'important'];

        if (in_array($level, $allowed_levels, true)) {
            update_post_meta($post_id, '_vtnai_level', $level);
        }
    }
}

add_action('save_post', 'vtnai_save_meta');
