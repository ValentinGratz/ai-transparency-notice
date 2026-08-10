<?php

if (!defined('ABSPATH')) {
    exit;
}

function vtnai_admin_menu() {
    add_options_page(
        'Valentin Transparency Notice for AI Content',
        'AI Content Transparency',
        'manage_options',
        'vtnai-settings',
        'vtnai_settings_page'
    );
}
add_action('admin_menu', 'vtnai_admin_menu');

function vtnai_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $settings = get_option('vtnai_settings', [
        'enabled'  => 'yes',
        'position' => 'after',
        'text'     => ''
    ]);

    if (isset($_POST['vtnai_save'])) {
        check_admin_referer('vtnai_save_settings');

        update_option('vtnai_settings', [
            'enabled'  => isset($_POST['enabled']) ? 'yes' : 'no',
            'position' => sanitize_text_field(wp_unslash($_POST['position'] ?? 'after')),
            'text'     => wp_kses_post(wp_unslash($_POST['text'] ?? ''))
        ]);

        $settings = get_option('vtnai_settings', $settings);

        echo '<div class="updated"><p>Settings saved.</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Valentin Transparency Notice for AI Content</h1>
        <form method="post">
            <?php wp_nonce_field('vtnai_save_settings'); ?>
            <table class="form-table">
                <tr>
                    <th>Enable notice</th>
                    <td><input type="checkbox" name="enabled" <?php checked($settings['enabled'] ?? 'yes', 'yes'); ?>></td>
                </tr>
                <tr>
                    <th>Position</th>
                    <td>
                        <select name="position">
                            <option value="before" <?php selected($settings['position'] ?? 'after', 'before'); ?>>Before content</option>
                            <option value="after" <?php selected($settings['position'] ?? 'after', 'after'); ?>>After content</option>
                            <option value="both" <?php selected($settings['position'] ?? 'after', 'both'); ?>>Before and after</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Text</th>
                    <td><textarea name="text" rows="8" cols="80"><?php echo esc_textarea($settings['text'] ?? ''); ?></textarea></td>
                </tr>
            </table>
            <input type="submit" name="vtnai_save" class="button button-primary" value="Save">
        </form>
    </div>
    <?php
}
