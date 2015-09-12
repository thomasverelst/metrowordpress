<?php
class metro_tile_widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		// widget actual processes
		parent::__construct(
			'metro_tile_widget', // Base ID
			__('Metro tile widget', 'text_domain'), // Name
			array( 'description' => __( 'metro tile widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		global $metro_tiles;


		//Get required things
		$metro_tiles = metro_include_tile_php($admin = false);
		metro_include_tile_scripts($admin = false);

		$type = $instance['type'];
		$args = array("x"=>0,
					"y"=>0,
					'width'=>0,
					'height'=>1,
					"anim_data"=>array("target_id"=>false, "target_type"=>false, "target_bg_color"=>false),
					"attr"=>array(),
					"spacing"=>10,
					"scale_spacing"=>10+$instance['scale'],
					"group_x" => array(0));
		$args = array_merge($args, $instance);

		if(is_callable($metro_tiles['tiles'][$type]['function'])){
			call_user_func($metro_tiles['tiles'][$type]['function'], $args);
		}else{
			echo "Warning: tile type '".$type."' not defined (widget)";
		}
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		global $metro_tiles;

		/*Includes */
		if(!function_exists("metro_admin_scripts")){
			wp_enqueue_script('wp-color-picker');
			function metro_admin_scripts() {
				wp_enqueue_script('media-upload');
				wp_enqueue_script('thickbox');
				wp_enqueue_script('wp-link');
			}			
		}
		if(!function_exists("metro_admin_styles")){
			wp_enqueue_style('wp-color-picker');	
			function metro_admin_styles() {
				wp_enqueue_style('thickbox');		
			}
			add_action('admin_print_scripts', 'metro_admin_scripts');
			add_action('admin_print_styles', 'metro_admin_styles');
			add_thickbox(); 		
		}


		//Tiles
		$metro_tiles = metro_include_tile_php(true);


		/*Output form */
		//print_r($instance);
		//print_r($tile_admin);
		//print_r(metro_include_tile_php(true));
		if($metro_tiles != null ){
			if(isset($instance['type'])){
				$type = $instance['type'];
			}else{
				$type = 'simple';
			}
			
			if(!array_key_exists($type, $metro_tiles['tiles'])){
				$type = "simple";
			}
			?>
			<p>
			<label for="<?php echo $this->get_field_name('type'); ?>"><?php _e( 'Tile type:' ); ?></label>
			<br/><select name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type'); ?>" class='metro-tile-type-select'>
			<?php
			/*Display all tile type as option */
			foreach($metro_tiles['tiles'] as $this_type=>$tile){
				if(isset($metro_tiles['tiles'][$this_type]['admin'])){
					?>
					<option value="<?php echo $this_type?>" <?php selected($this_type, $type);?> ><?php echo $this_type;?></option>
					<?php
				}
				
			}
			?>
			</select><br/>

			<?php
			$scale = (!empty($instance['scale'])) ? $instance['scale'] : 90 ;
			?>
			<p>
			<label for="<?php echo $this->get_field_name('scale'); ?>"><?php _e( 'Tile height in px:' ); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('scale')?>" name="<?php echo $this->get_field_name('scale')?>" value="<?php echo esc_attr($scale)?>" type="number"/><br/>
			</p>
			<?php
		/*	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">*/
			?>
			<?php
			/*Display all arguments */
			foreach($metro_tiles['tiles'][$type]['admin'] as $prop=>$this_el){

				//Get value, if set
				if(isset($instance[$prop])){
					$this_val = $instance[$prop];
				}else{
					$this_val = $metro_tiles['tiles'][$type]['defaults'][$prop];
				}

				// Get label
				$label = ($this_el[0] == "" ? $prop : $this_el[0]);
				?>
				<p><label for="<?php echo $this->get_field_name($prop); ?>"><?php echo $label; ?></label><br/>
				<?php

				//...
				if($prop == "new_tab"){
					$prop_type = "new_tab";
				}else{
					$prop_type = $this_el[1];
				}
				//Output input field
				switch($this_el[1]){
					case "select":
						if(isset($this_el[2])){
							echo  '<select id="'.$this->get_field_id($prop).'" name="'.$this->get_field_name($prop).'">';
							foreach($this_el[2] as $index=>$val){
								?>
								<option value="<?php echo $index?>" <?php selected($val, $this_val);?>><?php echo  $val ?></option>
								<?php
							}
							echo '</select>';
						}
					break;
					case "array":
						echo '<div class="metro-array-container" data-array-prop-type="'.$this_el[2].'" data-array-prop-name="'. $this->get_field_name($prop).'">';
						if(is_array($this_val) && count($this_val) > 0){
							foreach($this_val as $key=>$val){
								echo metro_get_prop_content($this_el[2], $prop, $val, $this->get_field_id($prop.'-'.$key), $this->get_field_name($prop.'-'.$key));
							}
						}else{
							echo metro_get_prop_content($this_el[2], $prop, ' ', $this->get_field_id($prop.'-0'), $this->get_field_name($prop.'-0'));
						}
						echo '<button class="metro-add-array-entry button">Add</button>';
						echo '</div>';
					break;
					default:
					echo metro_get_prop_content($prop_type, $prop, $this_val, $this->get_field_id($prop), $this->get_field_name($prop));
				}
			}?>

			<?php // inline scripts, slow but easy ?>
			<script>
			
			jQuery(document).ready(function(){
				if(typeof(metro_widgets_test_var) == "undefined"){
					metro_widgets_test_var = true
					
					/* INSERT BUTTONS in popups */					
					/* Insert image */
					jQuery('#upload_image_button').unbind("click.metro")
					// Uploading files
					var file_frame;
					 
					jQuery('#upload_image_button').live('click', function( event ){

						event.preventDefault();

						// Do the needed things
						jQuery(this).parent().addClass('active-img-field')

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
						  file_frame.open();
						  return;
						}

						// Create the media frame.
						file_frame = wp.media.frames.file_frame = wp.media({
						  title: jQuery( this ).data( 'uploader_title' ),
						  button: {
						    text: jQuery( this ).data( 'uploader_button_text' ),
						  },
						  multiple: false  // Set to true to allow multiple files to be selected
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
						  // We set multiple to false so only get one image from the uploader
						  attachment = file_frame.state().get('selection').first().toJSON();

						  // Do something with attachment.id and/or attachment.url here
						  console.log(attachment)
						var img = attachment.url;
						var imgAlt = attachment.alt;
						var imgTitle = attachment.title;
						jQuery('.active-img-field').children('.metro-img-input-field').val(img);
						if(jQuery('.active-img-field').children("#metro-tile-prop-img_alt").length>0){
							jQuery('.active-img-field').children("#metro-tile-prop-img_alt").val(imgAlt)
						}
						if(jQuery('.active-img-field').children("#metro-tile-prop-img_title").length>0){
							jQuery('.active-img-field').children("#metro-tile-prop-img_title").val(imgAlt)
						}
						jQuery('.active-img-field').removeClass('active-img-field')



						});

						// Finally, open the modal
						file_frame.open();
					});



					/* Insert link */
					jQuery('#insert_url_button').unbind("click.metro")
					jQuery('#wp-link-submit').unbind("click.metro")
					jQuery('#wp-link-cancel, #wp-link-close').unbind("click.metro")
					jQuery(document).on('click', '#insert_url_button', function(event) {
					    jQuery(this).parent().addClass('active-url-field')
					    jQuery(this).closest('metro-wys-prop-fields').addClass('active-prop-fields')
					    wpActiveEditor = true; //we need to override this var as the link dialogue is expecting an actual wp_editor instance
					    wpLink.open(); //open the link popup
					    return false;
					});
					jQuery(document).on('click', '#wp-link-submit', function(event) {
					    if(jQuery('#metro-wys-popup').css("display") != 'none'){
						    var linkAtts = wpLink.getAttrs();//the links attributes (href, tpropet) are stored in an object, which can be access via  wpLink.getAttrs()
						    jQuery('.active-url-field').find('.metro-url-input-field').val(linkAtts.href);//get the href attribute and add to a textfield, or use as you see fit
						    if(jQuery('.active-prop-fields').find('#metro-tile-prop-new_tab').length>0){
						    	jQuery('.active-prop-fields').find('#metro-tile-prop-new_tab').attr('checked', linkAtts.tpropet == "_blank");
						    }
						    
						    wpLink.textarea = jQuery('#metro-tile-prop-url'); //to close the link dialogue, it is again expecting an wp_editor instance, so you need to give it something to set focus back to. In this case, I'm using body, but the textfield with the URL would be fine
						    wpLink.close();//close the dialogue
						}
						//trap any events
					    event.preventDefault ? event.preventDefault() : event.returnValue = false;
					    event.stopPropagation();
					    return false;
					});
					jQuery(document).on('click', '#wp-link-cancel, #wp-link-close', function(event) {
					    wpLink.textarea = jQuery('#insert_url');
					    wpLink.close();
					    event.preventDefault ? event.preventDefault() : event.returnValue = false;
					    event.stopPropagation();
					    return false;
					});



					jQuery('.metro-add-array-entry').unbind('click.metro')
					jQuery(document).on('click.metro','.metro-add-array-entry',function(){
						var $parent = jQuery(this).closest('.metro-array-container')
						var name = $parent.data('array-prop-name').slice(0,-1)+ "-" + (parseInt($parent.children('input, select, label, button').length)-1) + "]"
						var data = getPropContent($parent.data('array-prop-type'), name, '')
						$parent.children().last().before(data);
/*						$el = jQuery(this).parent().find('input, select').eq(0).clone().insertBefore(jQuery(this))
						$el.val("")
						var newName = $el.attr("id").match(/(.*)[-]/gi)
						newName = newName + "" + (parseInt($el.parent().children('input, select').length)-1) + "]"
						$el.attr("id",newName);
						$el.attr("name",newName);

*/
						event.preventDefault ? event.preventDefault() : event.returnValue = false;
					    event.stopPropagation();
					    return false;
					})

					jQuery('.metro-tile-type-select').unbind('change.metro')
					jQuery(document).on('change.metro', '.metro-tile-type-select', function(){
						alert("Please click 'save' before starting!")
						return false;
					})

					if( typeof getPropContent == 'undefined'){
						/* EDITED copypaste from tile-editor.js  ONLY USED FOR ARRAYS*/
						function getPropContent(propType, name, val, clas){
							/*This is used to built the property fields of forms */
							clas = clas || ""
							var content = ""
							switch(propType){
								case "image":
									content +='\
										<label for="metro-tile-prop-img">\
										<input class="metro-img-input-field widefat '+clas+'" type="text" size="32" name="'+name+'" value="'+val+'" /><br/>\
										<input id="upload_image_button" type="button" value="Upload Image" class="button"/>\
										Enter a URL or upload an image.\
										</label><br/>'
								break;
								case "int":
									content += "<input type='number'  size='32' class='widefat "+clas+"' name='"+name+"' value='"+val+"'/>"
								break;
								case "text":
									content += "<input type='text'  size='32' class='widefat "+clas+"' name='"+name+"' value='"+val+"'/>"
								break;
								case "color":
									content += "<input type='text' size='32' class='widefat "+clas+" choose_color' name='"+name+"' value='"+val+"'/>"
								break;
								case "checkbox":
									var checked = (val == true || val == "on") ? " checked " : ""
									content += "<input type='checkbox' class='"+clas+"' name='"+name+"' "+checked+"/>"
								break;
								case "url":
									content += '<label for="metro-tile-prop-url">\
										<input class="metro-url-input-field widefat '+clas+'" type="text" size="32" name="'+name+'" value="'+val+'" /><br/>\
										<input id="insert_url_button" type="button" value="Select URL" class="button"/>\
										Enter a URL or use the page selector.\
										</label><br/>'
								break;
								default:
								content = ""
							}
							return content
						}		
					}

				}

				// add colorpicker
				jQuery('.choose-color').wpColorPicker();
			})
			</script>
			</p>
			<?php 
		}else{
			?>
			<p>Please click the 'save' button first.</p>
			<p></p>
			<?php
		};
		
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		global $metro_tiles;

		// Include tiles
		$metro_tiles = metro_include_tile_php(true);

		// processes widget options to be saved
		$instance =  array();
		$type = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : 'simple';

		$instance['type'] = $type;
		$instance['scale'] = ( ! empty( $new_instance['scale'] ) ) ? strip_tags( $new_instance['scale'] ) : 90;

		foreach($metro_tiles['tiles'][$type]['admin'] as $prop=>$this_el){
			if($this_el[1] == "array"){
				$key = 0;
				$vals = array();
				while($key >= 0){
					if(isset($new_instance[$prop."-".$key])){	
						if(trim($new_instance[$prop."-".$key]) != ""){
							$vals[] = strip_tags($new_instance[$prop."-".$key]);
						}
						$key++;
					}else{
						$key = -1;
						break;
					}
				}
				$instance[$prop] = $vals;
			}else{
				$default = ( ! empty( $old_instance[$prop] ) ) ? strip_tags( $old_instance[$prop] ) :  $metro_tiles['tiles'][$type]['defaults'][$prop];
				$instance[$prop] = ( ! empty( $new_instance[$prop] ) ) ? strip_tags( $new_instance[$prop] ) : $default;
			}
		
		}
		//$instance['type'] = $instance;
		$instance["instance"] = $instance;
		$instance["vars"] = $new_instance['type'];

		return $instance;
	}
}
add_action( 'widgets_init', function(){
     register_widget( 'metro_tile_widget' );
});


function metro_get_prop_content($prop_type, $prop, $val, $id, $name){
	$content = "";
	switch($prop_type){
		case "text":
			$content .= '<input class="widefat" id="'.$id.'" name="'.$name.'" type="text" value="'.esc_attr($val).'"/><br/>';
		break;
		case "image":
			$content .= '<label>
				<input type="text" class="metro-img-input-field widefat" id="'.$id.'"" name="'.$name.'"" value="'.esc_attr($val).'" />
				<input id="upload_image_button" type="button" class="button" value="Upload Image" />
				Enter an URL or upload an image.
				</label><br/>';
		break;
		case "color":
			$content .= '<input class="widefat choose-color" id="'.$id.'" name="'.$name.'" value="'.esc_attr($val).'" type="text"/><br/>';
		break;
		case "int":
			$content .= '<input class="widefat" id="'.$id.'" name="'.$name.'" value="'.esc_attr($val).'" type="number"/><br/>';
		break;
		case "url":
			$content .= '<label>
				<input id="'.$id.'" class="metro-url-input-field widefat" type="text" name="'.$name.'" value="'.esc_attr($val).'" />
				<input id="insert_url_button" type="button" class="button" value="Select URL" />
				Enter an URL or use the page selector.
				</label><br/>';
		break;
		case "checkbox":
			$content .= '<input type="checkbox" name = "'.$name.'" id="'.$id.'" '.checked($val).' /><br/>';
		break;
		case "new_tab":
			$content .= '<input class="metro-new-tab" type="checkbox" name = "'.$name.'" id="'.$id.'" '.checked($val).' /><br/>';
		break;
		default:
	}
	return $content;
}
?>