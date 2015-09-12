<?php
// Hooks your functions into the correct filters
/*function metro_add_mce_button() {
	global $post;
	// check user permissions
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}

	if(metro_id_to_template($post->ID) == "page-tiles"){
		// check if WYSIWYG is enabled
		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', 'metro_add_tinymce_plugin' );
			add_filter( 'mce_buttons', 'metro_register_mce_button' );
		}
	}	
}
add_action('admin_head', 'metro_add_mce_button');

// Declare script for new button
function metro_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['metro_insert_tile_mce_button'] = get_template_directory_uri() .'/admin/buttons/insert_tile_button.js';
	$plugin_array['metro_insert_group_mce_button'] = get_template_directory_uri() .'/admin/buttons/insert_group_button.js';
	$plugin_array['metro_edit_tile_mce_button'] = get_template_directory_uri() .'/admin/buttons/edit_tile_button.js';
	return $plugin_array;
}

// Register new button in the editor
function metro_register_mce_button( $buttons ) {
	array_push( $buttons, 'metro_insert_tile_mce_button', 'metro_insert_group_mce_button', 'metro_edit_tile_mce_button' );
	return $buttons;
}*/
?>