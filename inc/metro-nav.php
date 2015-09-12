<?php
/*--------------------------------------------*
* Based on http://www.wpexplorer.com/adding-custom-attributes-to-wordpress-menus/ 
* Thanks!
*--------------------------------------------*/

/* Includes */
include_once( 'walkers/metro-nav-walker-edit.php' );
include_once( 'walkers/metro-nav-walker.php' );


/* Functions */

// add custom menu fields to menu
function metro_add_custom_nav_fields( $menu_item ) {
    $menu_item->notransition = get_post_meta( $menu_item->ID, '_menu_item_notransition', true );
    return $menu_item;  
}
add_filter( 'wp_setup_nav_menu_item', 'metro_add_custom_nav_fields' );

// save menu custom fields
function metro_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
    // Check if element is properly sent
    if ( is_array( $_REQUEST['menu-item-notransition']) ) {
        echo $checked;
        $checked = $_REQUEST['menu-item-notransition'][$menu_item_db_id];
        update_post_meta( $menu_item_db_id, '_menu_item_notransition', $checked );
    }
}
add_action( 'wp_update_nav_menu_item', 'metro_update_custom_nav_fields', 10, 3 );

// edit menu walker
function metro_edit_walker($walker,$menu_id) {
	return 'Walker_Nav_Menu_Edit_Custom';
}
add_filter( 'wp_edit_nav_menu_walker',  'metro_edit_walker', 10, 2 );



?>