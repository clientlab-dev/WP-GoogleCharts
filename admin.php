<?php


 add_action ('admin_head', 'ex_add_my_tc_button');


function ex_add_my_tc_button() {
    global $typenow;
    // проверяем права доступа
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
    	return;
    }
    // проверяем тип поста
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;
    // проверяем что WYSIWYG включен
    if ( get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "ex_add_tinymce_plugin");
        add_filter('mce_buttons', 'ex_register_my_first_button');
    }
}


function ex_add_tinymce_plugin($plugin_array) {
    $plugin_array['ex_first_button'] = GCS_URL."btn.js"; // Укажите имя ВАШЕГО файла
    return $plugin_array;
}

function ex_register_my_first_button($buttons) {
   array_push($buttons, "ex_first_button");
   return $buttons;
}

?>
