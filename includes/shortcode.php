<?php

if (!defined('ABSPATH')) {
    exit;
}

function vtnai_shortcode() {
    return vtnai_notice('assist');
}

add_shortcode(
    'valentin_ai_transparency',
    'vtnai_shortcode'
);
