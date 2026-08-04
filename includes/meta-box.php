<?php

if (!defined('ABSPATH')) {
    exit;
}



function aitn_add_meta_box(){


add_meta_box(

    'aitn_box',

    '🤖 Transparence IA',

    'aitn_meta_box_html',

    'post',

    'side'

);


}


add_action(
'add_meta_boxes',
'aitn_add_meta_box'
);





function aitn_meta_box_html($post){


$value=get_post_meta(

$post->ID,

'_aitn_level',

true

);



wp_nonce_field(

'aitn_save_meta',

'aitn_nonce'

);


?>


<select name="aitn_level" style="width:100%">


<option value="none"

<?php selected($value,'none'); ?>

>

❌ Aucun

</option>



<option value="assist"

<?php selected($value,'assist'); ?>

>

✅ IA assistance

</option>



<option value="important"

<?php selected($value,'important'); ?>

>

⚠️ IA importante

</option>


</select>


<?php

}




function aitn_save_meta($post_id){


if(

!isset($_POST['aitn_nonce'])

||

!wp_verify_nonce(

$_POST['aitn_nonce'],

'aitn_save_meta'

)

){

return;

}



if(isset($_POST['aitn_level'])){


update_post_meta(

$post_id,

'_aitn_level',

sanitize_text_field(
$_POST['aitn_level']
)

);


}


}


add_action(
'save_post',
'aitn_save_meta'
);