<?php
/******* STYLE TINY MCE EDITOR *******/

// Include CSS in tinymce
function metro_add_editor_styles() {
	// Typography
	add_editor_style( 'css/editor-typography.css' );

	//Google font
	$font_url = urlencode( '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,300,600,700' );
    add_editor_style( $font_url );
}
add_action( 'after_setup_theme', 'metro_add_editor_styles' );

// Change tinymce background
function metro_tinymce_settings( $settings ) {
    global $post;
    $bg_color = get_post_meta($post->ID,"_metro_bg_color",true);
    if($bg_color == ""){
        $bg_color = get_option("metro-page-def-bg");
    }
    //dirty but works... 
    $settings['init_instance_callback'] = "function(ed){tinyMCE.activeEditor.getWin().document.body.style.background='".$bg_color."';}";
    return $settings;
}
add_filter( 'tiny_mce_before_init', 'metro_tinymce_settings' );

/*** TINYMCE BUTTONS ***/
// Add Formats Dropdown Menu To MCE
if (!function_exists('wpex_style_select')) {
    function wpex_style_select($buttons){
        array_push( $buttons, 'styleselect');
        return $buttons;
    }
}
add_filter( 'mce_buttons', 'wpex_style_select' );
// Add new styles to the TinyMCE "formats" menu dropdown
if ( ! function_exists( 'wpex_styles_dropdown' ) ) {
	function wpex_styles_dropdown( $settings ) {

		// Create array of new styles
		$new_styles = array(
			array(
				'title'	=> __( 'Custom Styles', 'wpex' ),
				'items'	=> array(
					array(
						'title'		=> __('Theme Button','wpex'),
						'selector'	=> 'a',
						'classes'	=> 'theme-button'
					),
					array(
						'title'		=> __('Highlight','wpex'),
						'inline'	=> 'span',
						'classes'	=> 'text-highlight',
					),
				),
			),
			array(
				'title'	=> __( 'Boxes', 'metro' ),
				'items'	=> array(
					array(
						'title'		=> __('Content box','metro'),
						'block'	=> 'div',
						'classes'	=> 'metro-box-content'
					),
					array(
						'title'		=> __('Hint box','metro'),
						'block'	=> 'div',
						'classes'	=> 'metro-box-hint'
					),
					array(
						'title'		=> __('Download box','metro'),
						'block'	=> 'div',
						'classes'	=> 'metro-box-download'
					),
					array(
						'title'		=> __('Info box','metro'),
						'block'	=> 'div',
						'classes'	=> 'metro-box-info'
					),
					array(
						'title'		=> __('Warning box','metro'),
						'block'	=> 'div',
						'classes'	=> 'metro-box-warning',
					),
				),
			)
		);

		// Merge old & new styles
		$settings['style_formats_merge'] = true;

		// Add new styles
		$settings['style_formats'] = json_encode( $new_styles );

		// Return New Settings
		return $settings;

	}
}
add_filter( 'tiny_mce_before_init', 'wpex_styles_dropdown' );
?>