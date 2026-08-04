<?php

if (!defined('ABSPATH')) {
    exit;
}



function aitn_shortcode(){


return aitn_notice('assist');


}



add_shortcode(

'ai_transparency',

'aitn_shortcode'

);