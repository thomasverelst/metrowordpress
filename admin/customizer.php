<?php
/* THEME CUSTOMIZER */
/**
 * Adds the Customize page to the WordPress admin area
 */
function metro_customizer_menu() {
    add_theme_page( 'Customize', 'Customize', 'edit_theme_options', 'customize.php' );
}
add_action( 'admin_menu', 'metro_customizer_menu' );


/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
function metro_customizer( $wp_customize ) {
    $wp_customize->add_section(
        'metro_section_footer',
        array(
            'title' => __('Footer', 'metro-template'),
            'description' => '',
            'priority' => 35,
        )
    );

    /* Footer */
    $wp_customize->add_setting(
	    'copyright_textbox',
	    array(
	        'default' => __('Default copyright text', 'metro-template'),
	    )
	);

	$wp_customize->add_control(
	    'copyright_textbox',
	    array(
	        'label' => __('Copyright text', 'metro-template'),
	        'section' => 'metro_section_footer',
	        'type' => 'text',
	    )
	);
	$wp_customize->add_setting(
	    'footer_bg_color',
	    array(
	        'default' => '#444',
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'footer_bg_color',
	        array(
	            'label' => __('Footer background color', 'metro-template'),
	            'section' => 'metro_section_footer',
	            'settings' => 'footer_bg_color',
	        )
	    )
	);
	$wp_customize->add_setting(
	    'copyright_color',
	    array(
	        'default' => '#DDDDDD',
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'copyright_color',
	        array(
	            'label' => __('Copyright text color', 'metro-template'),
	            'section' => 'metro_section_footer',
	            'settings' => 'copyright_color',
	        )
	    )
	);



	/* HEADER */
	$wp_customize->add_section(
        'metro_section_header',
        array(
            'title' => __('Header', 'metro-template'),
            'description' => '',
            'priority' => 30,
        )
    );
    $wp_customize->add_setting(
	    'header_bg_color',
	    array(
	        'default' => '#444',
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'header_bg_color',
	        array(
	            'label' => __('Header background color', 'metro-template'),
	            'section' => 'metro_section_header',
	            'settings' => 'header_bg_color',
	        )
	    )
	);

	$wp_customize->add_setting(
	    'show_search_icon',
	     array(
	        'default' => 'on',
	    )
	);
	$wp_customize->add_control(
	    'show_search_icon',
	    array(
	        'type' => 'checkbox',
	        'label' => __('Show search icon', 'metro-template'),
	        'section' => 'metro_section_general',
	    )
	);

	$wp_customize->add_setting(
	    'site_title_hover_color',
	    array(
	        'default' => SITE_TITLE_HOVER_COLOR,
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'site_title_hover_color',
	        array(
	            'label' => __('Main site title hover color', 'metro-template'),
	            'section' => 'metro_section_header',
	            'settings' => 'site_title_hover_color',
	        )
	    )
	);

	/* PAGES */
	$wp_customize->add_section(
        'metro_section_pages',
        array(
            'title' => __('Pages', 'metro-template'),
            'description' => '',
            'priority' => 30,
        )
    );
    $wp_customize->add_setting(
	    'page_bg_color',
	    array(
	        'default' => PAGE_BG_COLOR,
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'page_bg_color',
	        array(
	            'label' => __('Default page background color', 'metro-template'),
	            'section' => 'metro_section_pages',
	            'settings' => 'page_bg_color',
	        )
	    )
	);

	/* POSTS */
	$wp_customize->add_section(
        'metro_section_posts',
        array(
            'title' => __('Posts', 'metro-template'),
            'description' => '',
            'priority' => 30,
        )
    );
    $wp_customize->add_setting(
	    'post_bg_color',
	    array(
	        'default' => POST_BG_COLOR,
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'post_bg_color',
	        array(
	            'label' => __('Default post background color', 'metro-template'),
	            'section' => 'metro_section_posts',
	            'settings' => 'post_bg_color',
	        )
	    )
	);

	/* NAV */
	$wp_customize->add_setting(
	    'nav_main_active_color',
	    array(
	        'default' => NAV_MAIN_ACTIVE_COLOR,
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'nav_main_active_color',
	        array(
	            'label' => __('Main nav: active item background color'),
	            'section' => 'nav',
	            'settings' => 'nav_main_active_color',
	        )
	    )
	);

	$wp_customize->add_setting(
	    'nav_main_hover_color',
	    array(
	        'default' => NAV_MAIN_HOVER_COLOR,
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'nav_main_hover_color',
	        array(
	            'label' => __('Main nav: active item background color on hover', 'metro-template'),
	            'section' => 'nav',
	            'settings' => 'nav_main_hover_color',
	        )
	    )
	);

	$wp_customize->add_setting(
	    'nav_main_submenu_hover_color',
	    array(
	        'default' => NAV_MAIN_SUBMENU_HOVER_COLOR,
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'nav_main_submenu_hover_color',
	        array(
	            'label' => __('Main nav: submenu text color on hover', 'metro-template'),
	            'section' => 'nav',
	            'settings' => 'nav_main_submenu_hover_color',
	        )
	    )
	);

	/*General */
	$wp_customize->add_section(
        'metro_section_general',
        array(
            'title' => __('General', 'metro-template'),
            'description' => '',
            'priority' => 30,
        )
    );
    $wp_customize->add_setting(
	    'transitions',
	     array(
	        'default' => 'on',
	    )
	);
	$wp_customize->add_control(
	    'transitions',
	    array(
	        'type' => 'checkbox',
	        'label' => __('Use transitions on desktop', 'metro-template'),
	        'section' => 'metro_section_general',
	    )
	);
	
	$wp_customize->add_setting(
	    'mobile_transitions'
	);
	$wp_customize->add_control(
	    'mobile_transitions',
	    array(
	        'type' => 'checkbox',
	        'label' => __('Use transitions on mobile', 'metro-template'),
	        'section' => 'metro_section_general',
	    )
	);
    /*
    $wp_customize->add_setting(
	    'bg_type',
	    array(
	        'default' => 'color',
	    )
	);
	$wp_customize->add_control(
	    'bg_type',
	    array(
	        'type' => 'radio',
	        'label' => 'Background type',
	        'section' => 'metro_section_general',
	        'choices' => array(
	            'color' => 'Color',
	            'pattern' => 'Pattern image',
	            'img' => 'Fullscreen image',
	        ),
	    )
	);

    $wp_customize->add_setting(
	    'bg_color',
	    array(
	        'default' => '#000000',
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'bg_color',
	        array(
	            'label' => 'Background color: (always needed. If you use a pattern or fullscreen image, choose a color that looks the most like the image.)',
	            'section' => 'metro_section_general',
	            'settings' => 'bg_color',
	        )
	    )
	);
	$wp_customize->add_setting( 'bg_img' );
	 
	$wp_customize->add_control(
	    new WP_Customize_Image_Control(
	        $wp_customize,
	        'bg_img',
	        array(
	            'label' => 'Choose pattern or fullscreen image:',
	            'section' => 'metro_section_general',
	            'settings' => 'bg_img'
	        )
	    )
	);

	$wp_customize->add_setting(
	    'bg_scroll'
	);
	 
	$wp_customize->add_control(
	    'bg_scroll',
	    array(
	        'type' => 'checkbox',
	        'label' => 'Scroll background (only when type is fullscreen image)',
	        'section' => 'metro_section_general',
	    )
	);*/
	
	/* COLORS */
	$wp_customize->add_setting(
	    'date_color',
	    array(
	        'default' => DATE_COLOR,
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'date_color',
	        array(
	            'label' => __('Date color', 'metro-template'),
	            'section' => 'colors',
	            'settings' => 'date_color',
	        )
	    )
	);

	$wp_customize->add_setting(
	    'main_title_bg_color',
	    array(
	        'default' => MAIN_TITLE_BG_COLOR,
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'main_title_bg_color',
	        array(
	            'label' => __('Tile page title color', 'metro-template'),
	            'section' => 'colors',
	            'settings' => 'main_title_bg_color',
	        )
	    )
	);


	$wp_customize->add_setting(
	    'post_index_accent_color',
	    array(
	        'default' => '#FF6600',
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'post_index_accent_color',
	        array(
	            'label' => __('Post tile page accent color', 'metro-template'),
	            'section' => 'colors',
	            'settings' => 'post_index_accent_color',
	        )
	    )
	);



	/* TILES */
	$wp_customize->add_section(
        'metro_section_tiles',
        array(
            'title' => 'Tiles',
            'description' => '',
            'priority' => 30,
        )
    );
   
/*	$wp_customize->add_setting(
	    'grouptitle_color',
	    array(
	        'default' => '#444',
	        'sanitize_callback' => 'sanitize_hex_color',
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'grouptitle_color',
	        array(
	            'label' => __('Group title color:', 'metro-template'),
	            'section' => 'metro_section_tiles',
	            'settings' => 'grouptitle_color',
	        )
	    )
	);
*/
	 /*$wp_customize->add_setting(
	    'archive_tile_scale',
	    array(
	        'default' => 140,
	        'sanitize_callback'=>'metro_check_number'
	    )
	);

	$wp_customize->add_control(
	    'archive_tile_scale',
	    array(
	        'label' => __('Archive tile scale', 'metro-template'),
	        'section' => 'metro_section_tiles',
	        'type' => 'text',
	    )
	);
	$wp_customize->add_setting(
	    'archive_post_tile_height',
	    array(
	        'default' => 4,
	        'sanitize_callback'=>'metro_check_number'
	    )
	);

	$wp_customize->add_control(
	    'archive_post_tile_height',
	    array(
	        'label' => __('Archive posts tile height (in scale height, not in px)', 'metro-template'),
	        'section' => 'metro_section_tiles',
	        'type' => 'text',
	    )
	);*/
}
add_action( 'customize_register', 'metro_customizer' );?>