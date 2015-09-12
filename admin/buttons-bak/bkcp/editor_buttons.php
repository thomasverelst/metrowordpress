<?php
add_action( 'init', 'metro_editor_buttons' );
function metro_editor_buttons() {
    add_filter( "mce_external_plugins", "metro_editor_add_buttons" );
    add_filter( 'mce_buttons', 'metro_editor_register_buttons' );
}
function metro_editor_add_buttons( $plugin_array ) {
    $plugin_array['wptuts'] = get_template_directory_uri() . '/admin/buttons/editor_buttons.js';
    return $plugin_array;
}
function metro_editor_register_buttons( $buttons ) {
    array_push( $buttons, 'insert_tile'); // dropcap', 'recentposts
    return $buttons;
}
?>