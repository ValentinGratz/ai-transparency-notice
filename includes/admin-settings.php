<?php

if (!defined('ABSPATH')) {
    exit;
}

function vtnai_admin_menu() {
    add_options_page(
        __('Valentin Transparency Notice for AI Content', 'ai-transparency-notice'),
        __('AI Content Transparency', 'ai-transparency-notice'),
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

        $position = sanitize_key(wp_unslash($_POST['position'] ?? 'after'));
        $allowed_positions = ['before', 'after', 'both'];

        if (!in_array($position, $allowed_positions, true)) {
            $position = 'after';
        }

        update_option('vtnai_settings', [
            'enabled'  => isset($_POST['enabled']) ? 'yes' : 'no',
            'position' => $position,
            'text'     => wp_kses_post(wp_unslash($_POST['text'] ?? ''))
        ]);

        $settings = get_option('vtnai_settings', $settings);

        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved.', 'ai-transparency-notice') . '</p></div>';
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Valentin Transparency Notice for AI Content', 'ai-transparency-notice'); ?></h1>
        <form method="post">
            <?php wp_nonce_field('vtnai_save_settings'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="vtnai-enabled"><?php echo esc_html__('Enable notice', 'ai-transparency-notice'); ?></label></th>
                    <td><input id="vtnai-enabled" type="checkbox" name="enabled" <?php checked($settings['enabled'] ?? 'yes', 'yes'); ?>></td>
                </tr>
                <tr>
                    <th scope="row"><label for="vtnai-position"><?php echo esc_html__('Position', 'ai-transparency-notice'); ?></label></th>
                    <td>
                        <select id="vtnai-position" name="position">
                            <option value="before" <?php selected($settings['position'] ?? 'after', 'before'); ?>><?php echo esc_html__('Before content', 'ai-transparency-notice'); ?></option>
                            <option value="after" <?php selected($settings['position'] ?? 'after', 'after'); ?>><?php echo esc_html__('After content', 'ai-transparency-notice'); ?></option>
                            <option value="both" <?php selected($settings['position'] ?? 'after', 'both'); ?>><?php echo esc_html__('Before and after', 'ai-transparency-notice'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="vtnai-text"><?php echo esc_html__('Text', 'ai-transparency-notice'); ?></label></th>
                    <td><textarea id="vtnai-text" name="text" rows="8" cols="80"><?php echo esc_textarea($settings['text'] ?? ''); ?></textarea></td>
                </tr>
            </table>
            <?php submit_button(__('Save', 'ai-transparency-notice'), 'primary', 'vtnai_save'); ?>
        </form>
    </div>
    <?php
}
