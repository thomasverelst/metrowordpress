<?php
/* Page and Tile editor code */

include("tinymce.php");


 /******* META BOXES ******/

/* Adds the metro meta box to the page */
function metro_add_meta_box()
{
	$screens = array('page','post');
	foreach($screens as $screen){
		add_meta_box(
	        'metro-meta-box', // id, used as the html id att
	        __( 'Metro Attributes' ), // meta box title, like "Page Attributes"
	        'metro_meta_box_cb', // callback function, spits out the content
	        $screen, // post type or page. We'll add this to pages only
	        'side'//, // context (where on the screen
	       // 'low' // priority, where should this go in the context?
	    );
	} 
}
add_action( 'add_meta_boxes', 'metro_add_meta_box' );

/* Callback function for our meta box.  Echos out the content */
function metro_meta_box_cb( $post )
{
	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'metro_meta_box', 'metro_meta_box_nonce' );

	$template = metro_id_to_template($post->ID); // get type of page (based on template)4
	if($template == 'page'){ 
		/* Just a normal page  */
		include('page-editor.php');	
	}elseif($template == 'post'){
		/* A post  */
		include('post-editor.php');
	}elseif($template == "page-tiles"){
		/* Tile page */
		include("tile-editor.php");	
	}

	/*Always add this warning when changing the page template */
	?>
	<script type="text/javascript">
	jQuery(document).on('change','#pageparentdiv select#page_template',function(){
		alert('Please save/update this page before continuing!')
	})
	</script>
	<?php
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function metro_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['metro_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['metro_meta_box_nonce'], 'metro_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe to save the data now. */
	if(isset( $_POST['metro_bg_color'] ) ) {
		/* For pages */
		
		$my_data = sanitize_text_field( $_POST['metro_bg_color'] );
		update_post_meta( $post_id, '_metro_bg_color', $my_data );

		$chk = isset( $_POST['metro_do_transition'] ) && $_POST['metro_do_transition'] ? 'on' : 'off';
		update_post_meta( $post_id, '_metro_do_transition', $chk );

		$chk = isset( $_POST['metro_do_comments'] ) && $_POST['metro_do_comments'] ? 'on' : 'off';
		update_post_meta( $post_id, '_metro_do_comments', $chk );	

		$chk = isset( $_POST['metro_do_sidebar'] ) && $_POST['metro_do_sidebar'] ? 'on' : 'off';
		update_post_meta( $post_id, '_metro_do_sidebar', $chk );	


			
	}elseif(isset( $_POST['metro-wys-tile-data'])){
		/* For tiles */

		//Delete processed tiles array
		delete_post_meta( $post_id, "_metro_proc_tile_data" );

		// Save tile data
		$tile_data = $_POST['metro-wys-tile-data'];
		update_post_meta( $post_id, '_metro_tile_data', $tile_data );

		// Save tile scale daata
		if(isset( $_POST['metro-tile-scale'])){
			$data = $_POST['metro-tile-scale'];
			if(!is_int(absint($data))){
				$data = 140;
			}
			update_post_meta( $post_id, '_metro_tile_scale', $data );
		}

		// Save tile spacing data
		if(isset( $_POST['metro-tile-spacing'])){
			$data = $_POST['metro-tile-spacing'];
			if(!is_int(absint($data))){
				$data = 10;
			}
			update_post_meta( $post_id, '_metro_tile_spacing', $data );
		}

		// Save show-page-title checkbox
		$chk = isset( $_POST['metro-show-page-title'] ) && $_POST['metro-show-page-title'] ? 'on' : 'off';
		update_post_meta( $post_id, '_metro_show_page_title', $chk );	
	}


}
add_action( 'save_post', 'metro_save_meta_box_data' );


?>