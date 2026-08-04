<?php

if (!defined('ABSPATH')) {
    exit;
}




function aitn_notice($level){


if($level==="important") {


return '

<div class="aitn-box">

🤖 <strong>Transparence :</strong><br>

Une partie importante de cet article a été réalisée avec l’aide d’outils d’intelligence artificielle.
Le contenu a été vérifié avant publication.

</div>';

}



$settings=get_option('aitn_settings');


return '

<div class="aitn-box">

'.$settings['text'].'

</div>';

}




function aitn_display_content($content){



if(!is_singular('post')) {

return $content;

}



global $post;



$level=get_post_meta(

$post->ID,

'_aitn_level',

true

);



if(!$level || $level==="none"){

return $content;

}



$notice=aitn_notice($level);



$settings=get_option('aitn_settings');



if($settings['position']=="before"){

return $notice.$content;

}



if($settings['position']=="both"){

return $notice.$content.$notice;

}



return $content.$notice;



}



add_filter(

'the_content',

'aitn_display_content'

);





function aitn_load_css(){


wp_enqueue_style(

'aitn-style',

AITN_URL.'assets/css/style.css',

[],

AITN_VERSION

);


}


add_action(

'wp_enqueue_scripts',

'aitn_load_css'

);