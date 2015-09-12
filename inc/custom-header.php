<?php
//Custom headers

/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * You can add an optional custom header image to header.php like so ...
 
    <?php $header_image = get_header_image();
    if ( ! empty( $header_image ) ) { ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
            <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
        </a>
    <?php } // if ( ! empty( $header_image ) ) ?>
 
 *
 * @package Shape
 * @since Shape 1.0
 */
 
/**
 * Setup the WordPress core custom header feature.
 *
 * Use add_theme_support to register support for WordPress 3.4+
 * as well as provide backward compatibility for previous versions.
 * Use feature detection of wp_get_theme() which was introduced
 * in WordPress 3.4.
 *
 * @uses shape_header_style()
 * @uses shape_admin_header_style()
 * @uses shape_admin_header_image()
 *
 * @package Shape
 */
function metro_custom_header_setup() {
	$defaults = array(
		'default-image'          => '',
		'random-default'         => false,
		'width'                  => 400,
		'height'                 => 100,
		'flex-height'            => true,
		'flex-width'             => true,
		'default-text-color'     => 'FFFFFF',
		'header-text'            => true,
		'uploads'                => true,
		'wp-head-callback'       => 'metro_header_style',
		'admin-head-callback'    => 'metro_admin_header_style',
		'admin-preview-callback' => 'metro_admin_header_image',
	);
 
    //$args = apply_filters( 'shape_custom_header_args', $args );
    add_theme_support( 'custom-header', $defaults );
}
add_action( 'after_setup_theme', 'metro_custom_header_setup' );

/**
 * Styles the header image and text displayed on the blog
 *
 * @see shape_custom_header_setup().
 *
 * @since Shape 1.0
 */
function metro_header_style() {
 
    // If no custom options for text are set, let's bail
    // get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
    if ( HEADER_TEXTCOLOR == get_header_textcolor() && get_header_image() == '' )
        return;
    // If we get this far, we have custom styles. Let's do this.
    ?>
    <style type="text/css">
    </style>

    <?php
}

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see shape_custom_header_setup().
 *
 * @since Shape 1.0
 */
function metro_admin_header_style() {
	?>
    <style type="text/css">
    #header-titles{
    	background-color:<?php echo get_theme_mod('header_bg','#444');?>;
    }
	#site-title{
		margin:0;
		padding:2px 0 2px 0;
		display:  inline-block;
		font-weight: 300;
		font-size:38px;
		color:#FFF;
		text-decoration: none;
		-webkit-transition:color 0.3s;
		        transition:color 0.3s;
	}
	#site-title:hover{
		color:#F90;
	}
	#site-desc{
		padding:0 0 5px 3px;
		font-weight: 300;
		font-size:14px;
		color:#DDD;
		display: inline-block;
	}
	</style>
	<?php
}
 

/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see shape_custom_header_setup().
 *
 * @since Shape 1.0
 */
function metro_admin_header_image() { 
    /*<div id="headimg">
        <?php
        if ( 'blank' == get_header_textcolor() || get_header_textcolor() == '')
            $style = ' style="display:none;"';
        else
            $style = ' style="color:#' . get_header_textcolor() . ';"';
        ?>
        <h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
        <div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
        <?php $header_image = get_header_image();
        if ( ! empty( $header_image ) ) : ?>
            <img src="<?php echo esc_url( $header_image ); ?>" alt="" />
        <?php endif; ?>
    </div>*/
    ?>


    <div id="header-titles">
    	<?php
    	if(get_header_image() != ''){
			?>
			<img src='<?php header_image();?>' alt='<?php _e(get_bloginfo("name"), 'metro-template');?> - <?php _e(get_bloginfo("description"), 'metro-template');?>' title='<?php _e(get_bloginfo("name"), 'metro-template');?>'/>
			<?php
		}
    	if (get_header_textcolor() != 'blank' && get_header_textcolor() != ''){
    		?>
			<a id="site-title" 
				style='color:#<?php header_textcolor();?>;'
				href="<?php site_url();?>" 
				data-target-type="page-tiles" 
				data-target-id="<?php echo get_option('page_on_front')?>">
					<?php _e(get_bloginfo("name"), 'metro-template');?>
			</a>
			<div id="site-desc"><?php _e(get_bloginfo("description"), 'metro-template');?></div>
			<?php
		}

		?>
	</div>

<?php 
}
?>