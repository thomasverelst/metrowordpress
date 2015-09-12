<?php
/* 
CONSTANTS 
*/

// Fonts
define('GOOGLE_FONT', '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,300,600,700');
define('FONT', '13px Open Sans, Segoe UI light, Verdana, Arial, Sans Serif');

// Post index
define('POST_INDEX_HEIGHT', 11);
define('POST_INDEX_SCALE', 40);
define('POST_INDEX_SPACING', 5);
define('POST_INDEX_ACCENT_COLOR', '#F60');

define('PAGE_BG_COLOR', '#00995D');
define('POST_BG_COLOR', '#00995D');

define('DATE_COLOR', '#F60');

define('SITE_TITLE_HOVER_COLOR', '#F90');
define('MAIN_TITLE_BG_COLOR', '#F60');

// Nav
define('NAV_MAIN_ACTIVE_COLOR', '#F60');
define('NAV_MAIN_HOVER_COLOR', '#F90');
define('NAV_MAIN_SUBMENU_HOVER_COLOR', '#F60');

// TIles
define('TILE_LABEL_COLOR', '#1e73be');
define('TILE_BG', '#0066bf');
define('TILE_LABEL_POSITION', 'top');


/* 
INCLUDES
*/
require_once('inc/metro-nav.php');
require_once('inc/widgets.php');
require_once('inc/shortcodes.php');
require_once('inc/walkers/metro-comment-walker.php');
require_once("admin/options.php");


/*
Include jQuery
*/
function metro_jquery_enqueue() {
	wp_deregister_script('jquery');
	wp_register_script('jquery', 'http' . ($_SERVER['SERVER_PORT'] == 443 ? 's' : '') . "://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js", false, null);
	wp_enqueue_script('jquery');
}
if (!is_admin()) add_action("wp_enqueue_scripts", "metro_jquery_enqueue", 11);


/* 
Enqueue styles and scripts 
*/
function metro_files() {
	/* Styles */

	// Google font
	wp_register_style('google-fonts', get_theme_mod('google_font',GOOGLE_FONT));
    wp_enqueue_style( 'google-fonts');

	//...
	wp_enqueue_style( 'normalize', get_template_directory_uri() . '/css/normalize.css');
	wp_enqueue_style( 'metro-main', get_stylesheet_uri() );
	wp_enqueue_style( 'metro-typography', get_template_directory_uri() . '/css/typography.css');
	//wp_enqueue_style( 'metro-theme', get_template_directory_uri() . '/css/theme.css');
	wp_enqueue_style( 'metro-nav',   get_template_directory_uri() . '/css/nav.css');
	wp_enqueue_style( 'metro-tiles', get_template_directory_uri() . '/css/tiles.css');
	wp_enqueue_style( 'metro-mobile', get_template_directory_uri() . '/css/mobile.css');
	wp_enqueue_style( 'metro-custom', get_template_directory_uri() . '/css/custom.css');

	/*Scripts */
	//wp_enqueue_script( 'overthrow', get_template_directory_uri() . '/js/overthrow.js', array("jquery"), '0.7.0', false );
	wp_enqueue_script( 'metro-functions', get_template_directory_uri() . '/js/functions.js', array("jquery"), '', false );
	wp_enqueue_script( 'metro-plugins', get_template_directory_uri() . '/js/plugins.js', array("jquery"), '', false );

	if(get_theme_mod('transitions', 'on') == 'on'){
		wp_enqueue_script( 'metro-page-transitions', get_template_directory_uri() . '/js/page-transitions.js', array("jquery"), '', false );
	}
	wp_enqueue_script( 'metro-main', get_template_directory_uri() . '/js/main.js', array("jquery"), '', false );
	wp_enqueue_script( 'metro-responsive', get_template_directory_uri() . '/js/responsive.js', array("jquery"), '', false );
	wp_enqueue_script( 'metro-tile-functions', get_template_directory_uri() . '/js/tiles-functions.js', array("jquery"), '', false );
	wp_enqueue_script( 'metro-tiles', get_template_directory_uri() . '/js/tiles.js', array("jquery"), '', false );
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	
	/*Send php data to scripts */
	$data_transitions = array(
		"site_url"=>get_site_url(),
		"template_dir"=>get_template_directory_uri(),
		"transitions"=> get_theme_mod('transitions' , get_theme_mod( 'transitions', 'on' )),
		'ln_close_this_window'=>'Close this window'
	);
	wp_localize_script('metro-functions', 'php_data', array("template_dir" => get_template_directory_uri()));
	wp_localize_script('metro-page-transitions', 'php_data', $data_transitions);


}
add_action( 'wp_enqueue_scripts', 'metro_files' );


/* 
Adds support for other languages 
*/
function metro_theme_setup() {
    // Retrieve the directory for the internationalization files
    $lang_dir = get_template_directory() . '/lang';
     
    // Set the theme's text domain using the unique identifier from above
    load_theme_textdomain('metro-template', $lang_dir);
}
add_action('after_setup_theme', 'metro_theme_setup');


/* 
MENU (navigations) 
*/
function metro_register_menu() {
    register_nav_menu( 'main_nav', __( 'Main Navigation' ) );
    register_nav_menu( 'footer_nav', __( 'Footer Navigation' ) );
}
add_action( 'init', 'metro_register_menu' );


/* 
Functions to include tile CSS and JS (DEPRECATED)
*/
function metro_include_tile_scripts($admin = false){
	return true;
}


/* 
Function to include tile php functions. Returns the functions as the $metro_tiles array.
*/
function metro_include_tile_php($admin = false){

	// Includes
	require_once(get_template_directory().'/inc/tile-functions.php');
	require_once(get_template_directory().'/inc/tiles.php');

	// Initialize $metro_tiles array, which stores all tile functions
	$metro_tiles = array(
		'defaults'=>array(
			'bg'=> get_theme_mod('tile_bg_color', TILE_BG),
			'label_color'=> get_theme_mod('tile_label_color', TILE_LABEL_COLOR),
			'label_position'=>get_theme_mod('tile_label_position', TILE_LABEL_POSITION)
		),
		'tiles'=>array()
	);

	// Popuplate $metro_tiles array
	$metro_tiles = apply_filters("metro_include_tiles", $metro_tiles);

	// Return
	return $metro_tiles;
}

/* 
Returns the page type when an post_id is given, additionally a url can be needed if the post id is 0. 
Possibilities of return are : 'post', 'page', 'page-tiles'...
*/
function metro_id_to_template($post_id, $url = null){
	
	$type = 'unknown';
	$frontpage_id = get_option('page_on_front');
	$postpage_id = get_option('page_for_posts');

	if($post_id == 0){
		$url = ($url == null ? get_permalink($post_id) : $url); // doesnt work?

		preg_match("'(.*\/)page\/[0-9]+\/'i", $url, $matches);
		if(count($matches) > 1){
			$url = $matches[1];
		}

		if($url == get_permalink($frontpage_id)){
			$type = get_page_template_slug($frontpage_id);
		}else
		if($url == get_permalink($postpage_id)){
			$type = 'page-tiles';//get_page_template_slug($postpage_id); !!
		}elseif(is_post_type_archive($post_id) ){
			$type = 'page-tiles';
		}
	}else{
		$type = get_page_template_slug($post_id);
	}

	if($type == ''){ // using standard template
		$type = get_post_type($post_id);
	}
	return str_ireplace(".php", "", $type);
}


/* 
Returns the animation data is a string that needs to be echo'ed inside an a-tag, based on the given vars. 
at least a target_id or a url must be supplied, but it's faster if the other variables can be supplied too. 
 */
function metro_get_animation_data($target_id = false, $target_type = false, $target_bg_color = false, $url = false){
	
	if(get_theme_mod('transitions','on') != 'on'){
		return ' ';
	}

	//Get ID if not given
	if(!$target_id){
		$target_id = url_to_postid($url);
	}

	// Return blank if not enough data is given to built animation string
	if(get_post_meta_def($target_id, '_metro_do_transition', true, 'on') != 'on' || ($url == false && $target_id == false)){
		return '';
	}

	//Get page type if not given
	if($target_type == false && $url != false){
		$target_type = metro_id_to_template($target_id, $url);
	}

	// Get target color if not given
	if(!$target_bg_color){
		if($target_type == 'page'){
			$default = get_theme_mod('page_bg_color', PAGE_BG_COLOR);
		}else{
			$default = get_theme_mod('post_bg_color', POST_BG_COLOR);
		}
		
		$target_bg_color = get_post_meta_def( $target_id, '_metro_bg_color', true, $default);
	}
	
	// Return
	return 
	' 
	data-target-type = "'.$target_type.'"
	data-target-id = "'.$target_id.'"
	data-target-bg-color = "'.$target_bg_color.'" 
	';	
}


/* 
Converts an array of attributes to a string.
Example: array('title'=>'test', 'alt'=>'test-image') becomes 'title = "test" alt="test-image"
*/
function metro_get_attr_data($attr_arr){
	$attr_str = '';
	if(is_array($attr)){
		foreach($attr as $att=>$val){
			$attr_str .= ' '.$att.' ="'.$val.'" ';
		}
	}
	return $attr_str;
}

/* 
Returns the code for a loading image 
*/
function metro_loader(){
	$str = '';
	$str .= '<div id="metro-loader-overlay">';
		$str .= '<div id="metro-loader-wrapper">';
			$str .= '<img width="24" height="24" id="metro-loader" src="'.get_template_directory_uri().'/img/icons/dark/loader.gif"/>';
		$str .= '</div>';
	$str .= '</div>';
	return $str;
}




/* 
Register sidebars
*/
function metro_widgets_init() {
	register_sidebar( array(
		'name' => __('Post sidebar', 'metro-template'),
		'id' => 'metro_post_sidebar',
		'before_widget' => '<div class="metro-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => __('Page sidebar', 'metro-template'),
		'id' => 'metro_page_sidebar',
		'before_widget' => '<div class="metro-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', 'metro_widgets_init' );


/* 
Custom sanitizers 
*/
function metro_check_number( $value ) {
    $value = (int) $value; // Force the value into integer type.
    return ( 0 < $value ) ? $value : null;
}


/* 
Theme support 
*/
// HTML5
add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

// Background
function metro_custom_background_setup(){
	$defaults = array(
		'default-color'          => '#EEE',
		'default-image'          => '',
		'wp-head-callback'       => '_custom_background_cb',
		'admin-head-callback'    => '',
		'admin-preview-callback' => ''
	);
	add_theme_support( 'custom-background', $defaults );
}
add_action( 'after_setup_theme', 'metro_custom_background_setup' );

//Thumbs
add_theme_support( 'post-thumbnails' );

// Custom header
require_once(get_template_directory().'/inc/custom-header.php');


/* 
Add a theme for siteorigins panels 
*/
function metro_panels_row_styles($styles) {
	$styles['metro-style'] = __('Metro', 'metro');
	return $styles;
}
add_filter('siteorigin_panels_row_styles', 'metro_panels_row_styles');


/*
An improved get_post_meta which accepts a default value as fourth argument.
*/
function get_post_meta_def($post_id, $key, $single = false, $default = '')
{
    $value = get_post_meta($post_id, $key, $single);
    if(empty($value))
        $value = $default;
    return $value;
}

?>