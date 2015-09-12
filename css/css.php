<?php
/* Dynamic css. Will be included in header. Not the most bandwidth efficient, but easy. */
?>
<style>
html,body{
	font-size:13px;
	font: <?php echo get_theme_mod('font', FONT);?>;
}

<?php
if(is_admin_bar_showing()){
	?>
/*	 html,body{margin:0 !important;}*/
	#metro-pjax{
		top:32px; /* to compensate for header height, will be corrected by js */
	}
	html{
		height: calc(100% - 32px);
		min-height: calc(100% - 32px);
		overflow-x:hidden;
	}
	<?php
}
?>

/*Background */
<?php
$repeat = get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ) );
if($repeat == 'no-repeat'){
	?>
body{
	background-size: cover;
}
	<?php
}
?>


/*Header */
#site-title:hover{
	color:<?php echo get_theme_mod('site_title_hover_color', SITE_TITLE_HOVER_COLOR);?>;
}
header#metro-header{
	color:#FFF;
	background-color: <?php echo get_theme_mod('header_bg_color','#333');?>;
}
footer#metro-footer{
	color:<?php echo get_theme_mod('copyright_color','#DDD');?>;
	background-color: <?php echo get_theme_mod('footer_bg_color','#333');?>;
}
footer#metro-footer>.copyright{
	color:<?php echo get_theme_mod('copyright_color','#DDD');?>;
}
#metro-content-wrapper{
	background:<?php echo get_theme_mod('page_bg_color', PAGE_BG_COLOR)?>;
}



/* Colorize nav */
.main-nav>ul>li.current-menu-item>a{
	background-color:<?php echo get_theme_mod('nav_main_active_color', NAV_MAIN_ACTIVE_COLOR);?>;
}
.main-nav>ul>li.current-menu-item>a:hover{
	background-color:<?php echo get_theme_mod('nav_main_hover_color', NAV_MAIN_HOVER_COLOR);?>;
}
.main-nav .sub-menu a:hover{
	color: <?php echo get_theme_mod('nav_main_submenu_hover_color', NAV_MAIN_SUBMENU_HOVER_COLOR);?>;
}
.main-nav.mini:hover{
	background-color: <?php echo get_theme_mod('nav_main_hover_color', NAV_MAIN_HOVER_COLOR);?>;
}
.main-nav.mini .sub-menu-wrap{
	border-left:<?php echo get_theme_mod('nav_main_active_color', NAV_MAIN_ACTIVE_COLOR);?> 1px solid;
}



</style>
