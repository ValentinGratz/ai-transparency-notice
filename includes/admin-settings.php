<?php

if (!defined('ABSPATH')) {
    exit;
}

function aitn_admin_menu() {
    add_options_page(
        'AI Transparency Notice',
        'AI Transparency',
        'manage_options',
        'aitn-settings',
        'aitn_settings_page'
    );
}
add_action('admin_menu', 'aitn_admin_menu');

function aitn_settings_page() {
    if (isset($_POST['aitn_save'])) {
        check_admin_referer('aitn_save_settings');

        update_option('aitn_settings', [
            'enabled' => isset($_POST['enabled']) ? 'yes' : 'no',
            'position' => sanitize_text_field(wp_unslash($_POST['position'] ?? 'after')),
            'text' => wp_kses_post(wp_unslash($_POST['text'] ?? ''))
        ]);

        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $settings = get_option('aitn_settings');
    ?>
    <div class="wrap">
        <h1>AI Transparency Notice</h1>
        <form method="post">
            <?php wp_nonce_field('aitn_save_settings'); ?>
            <table class="form-table">
                <tr><th>Enable notice</th><td><input type="checkbox" name="enabled" <?php checked($settings['enabled'], 'yes'); ?>></td></tr>
                <tr><th>Position</th><td>
                    <select name="position">
                        <option value="before">Before content</option>
                        <option value="after" <?php selected($settings['position'], 'after'); ?>>After content</option>
                        <option value="both">Before and after</option>
                    </select>
                </td></tr>
                <tr><th>Text</th><td><textarea name="text" rows="8" cols="80"><?php echo esc_textarea($settings['text']); ?></textarea></td></tr>
            </table>
            <input type="submit" name="aitn_save" class="button button-primary" value="Save">
        </form>
    </div>
    <?php
}
