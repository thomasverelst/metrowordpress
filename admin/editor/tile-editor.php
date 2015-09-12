<?php
/* Tile page */
$metro_tiles = metro_include_tile_php(true); // include our tiles

// Somehow it doesn't work when the scripts are in the script functions...
wp_enqueue_script('metro-gridster-js', get_template_directory_uri() . '/admin/gridster/jquery.gridster.min.js', array("jquery"), '', false );
wp_enqueue_script('metro-editor', get_template_directory_uri() . '/admin/editor/tile-editor.js', array("jquery", 'wp-color-picker'), '', false );
$data = array(
	'theme_dir' => get_template_directory_uri(),
	'ln_add_tile'=>__('Add tile','metro-template'),
	'ln_edit_tile'=>__('Edit tile', 'metro-template'),
	'ln_edit_group'=>__('Edit group','metro-template'),
	'ln_delete_tile'=>__('Are you sure you want to delete this tile? This cannot be undone!', 'metro-template'),
	'ln_new_group'=>__('New group', 'metro-template'),
	'ln_group'=>__('Group','metro-template'),
	'ln_group_spacing_right'=>__('Margin on the left side (in \'tile\' units)','metro-template'),
	/* translators: title as 'name of something' */
	'ln_title'=>__('Title','metro-template'),
	'ln_enter_url'=>__('Enter a URL or use the page selector','metro-template'),
	'ln_url'=>__('URL','metro-template'),
	/* translators: short version of leave blank if you don't want a particular atrribute (e.g. background image) */
	'ln_leave_blank'=>__('Leave blank if none', 'metro-template'),
	'ln_select_url'=>__('Select URL','metro-template'),
	'ln_apply_changes'=>__('Apply changes','metro-template'),
	'ln_cancel'=>__('Cancel','metro-template'),
	'ln_edit'=>__('Edit','metro-template'),
	'ln_delete'=>__('Delete','metro-template'),
	'ln_delete_group'=>__("Are you sure you want to delete this group? There's no undo!)", 'metro-template'),
	'ln_select_type'=>__('Select type','metro-template'),
	'ln_insert_tile'=>__('Insert tile','metro-template'),
	'ln_processing'=>__('Processing','metro-template'),
	'ln_widget_try_again'=>__("Something wen't wrong. Please try adding the widget again", "metro-template"),
	'ln_confirm_delete_tile'=>__("Are you sure you want to delete this tile?","metro-template"),
	'ln_import_tiles_desc'=>__("Put the tile data in the text area. This data can be found in the Wordpress 'revisions' of this page. See the tutorial for more information.","metro-template"),
	'ln_add_element'=>__('Add element','metro-template'),
	'ln_select_img'=>__('Select Image', 'metro-template'),
	'ln_enter_img'=>__('Enter a URL or upload an image.','metro-template'),
	'ln_resets_widget'=>__("This will reset the widget settings, are you sure you want to continue?",'metro-template'),
	
);
wp_localize_script('metro-editor', 'php_data', $data);


wp_enqueue_style('metro-editor', get_template_directory_uri() . '/admin/editor/tile-editor.css');
wp_enqueue_style( 'metro-gridster-css', get_template_directory_uri() . '/admin/gridster/jquery.gridster.min.css');	 

if(!function_exists("metro_admin_scripts")){
	wp_enqueue_script('wp-color-picker');
	function metro_admin_scripts() {
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('wp-link');
	}			
	add_action('admin_print_scripts', 'metro_admin_scripts');
}
if(!function_exists("metro_admin_styles")){
	wp_enqueue_style('wp-color-picker');	
	function metro_admin_styles() {
		wp_enqueue_style('thickbox');		
	}
	add_action('admin_print_styles', 'metro_admin_styles');
	add_thickbox(); 		
}

/* Process tiles */
?> 
<script>
	metroTiles = <?php echo json_encode($metro_tiles);?>;

	scale =  <?php echo get_post_meta_def(get_the_ID(), '_metro_tile_scale', true, 140)?>;
	spacing = <?php echo get_post_meta_def(get_the_ID(), '_metro_tile_spacing', true, 10)?>;

    gridster = new Array();
	settings = {

	      widget_margins: [spacing/2, spacing/2],
	      widget_base_dimensions: [scale, scale],
	      autogrow_cols: true,
	      resize: {
	        enabled: true,		       
	        stop: function(event, ui){
	        	updateTileData()
	        }
	      },
	      draggable:{
	      	start: function(event,ui){
	        	jQuery(".gridster").children("ul").css("background","rgba(221,221,221,0.7)")
	        },
	      	stop: function(event, ui){
	      		jQuery(".gridster").children("ul").css("background","transparent")
	      		updateTileData()
	      	}
	      },
	      avoid_overlapped_widgets: false
    }    
    jQuery(document).ready(function(){
    	metroParseData('<?php echo str_replace(array("\r", "\n"), '',get_post_meta_def(get_the_ID(), '_metro_tile_data', true, ''));?>')
    })
</script>

<style>
#postdivrich{ /* Hide WP editor */
	display: none;
}
#metro-wys-wrap{
	background-color:#EEE;
	background-color:<?php echo get_option("metro-general-bg-color");?>;
	<?php 
	if(get_option("metro-general-bg-type") == "img"){?>
		background: url(<?php echo get_option("metro-general-bg-img");?>) no-repeat center center fixed; 
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
		<?php
	}elseif(get_option("metro-general-bg-type") == "pattern"){?>
		background: url(<?php echo get_option("metro-general-bg-pattern");?>) repeat;
		<?php
	}
	?>
}

</style>

<button id="metro-wys-open-editor"><?php _e('Open tile editor', 'metro-template');?></button>


<div id="metro-wys-wrap" style="
background-color: <?php echo get_background_color();?>;
<?php
if(get_background_image() != ''){
	?>
	background-image: url('<?php echo get_background_image();?>');
	background-repeat: <?php echo get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ) );?>;

<?php
}

$repeat = get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ) );
if($repeat == 'no-repeat'){
	?>
	background-size: cover;
	<?php
}
?>


"> <!-- the actual tile editor -->
	<input type="hidden" id="metro-wys-tile-data" name="metro-wys-tile-data" value='<?php echo get_post_meta_def(get_the_ID(), '_metro_tile_data', true, '')?>'/>
	<div id="metro-wys-content">
	<div id="metro-wys-tiles-title"></div>
		<div id="metro-wys-tile-wrap">
			<div id="metro-wys-tile-sizer">
		    </div>
		</div>

		<div id="metro-wys-button-bar">
		    <button id="metro-wys-add-tile-button" class="button"><?php _e('Add tile', 'metro-template');?></button>
		    <button id="metro-wys-add-group-button" class="button"><?php _e('Add group', 'metro-template');?></button>
		    <?php _e('Scale', 'metro-template');?>: <input type="number" maxlength="4" size="4" style='width:80px;' name="metro-tile-scale" value="<?php echo get_post_meta_def(get_the_ID(), '_metro_tile_scale', true, 140);?>"/>
		    <?php _e('Spacing', 'metro-template');?>: <input type="number" maxlength="4" size = "4" style='width:80px;' name="metro-tile-spacing"  value="<?php echo get_post_meta_def(get_the_ID(), '_metro_tile_spacing', true, 10);?>"/>
		    <?php _e('Show page title', 'metro-template');?>: <input type="checkbox" name="metro-show-page-title" <?php checked(get_post_meta_def(get_the_ID(), '_metro_show_page_title', true, 'on'), 'on');?>/>
		    <button id="metro-import-tiles-button" class="button"><?php _e('Import tile data', 'metro-template');?></button>
		    <button id="metro-wys-close-editor" class="button" style="float:right;"><?php _e('Close editor', 'metro-template');?></button>
		    <button id="metro-wys-update-page" class="button-primary" style="float:right;"><?php _e('Update', 'metro-template');?></button>
		</div>
		
		<!-- the custom popups -->
		<div id="metro-wys-popup" style="display:none;">
			<div id="metro-wys-popup-window">
				<button id="metro-wys-popup-cancel" class="button">X</button>

			</div>
		</div>

	</div>
</div>