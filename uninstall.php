<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('vtnai_settings');
delete_post_meta_by_key('_vtnai_level');
