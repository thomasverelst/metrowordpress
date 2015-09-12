<!doctype html>

<html>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<title><?php wp_title ( '|', true,'right' ); ?> <?php _e(get_bloginfo("name"), 'metro-template');?></title>

	<meta name="description" content="<?php bloginfo('description'); ?>" />
	<meta name="viewport" content="width=device-width, target-densitydpi=160">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<?php wp_head();?>
	<?php include("css/css.php");?>

	<script>
		  document.createElement('header');
		  document.createElement('section');
		  document.createElement('article');
		  document.createElement('aside');
		  document.createElement('nav');
		  document.createElement('footer');
	</script>

	<noscript>
		<style>
			.tile{display:block !important;opacity:1 !important;}
			.group-title{display: block !important;}
		</style>
	</noscript>

</head>
<body <?php body_class($class); ?>>
<?php
/*if(get_option("metro-general-bg-type") == "img" && get_option("metro-general-bg-img-scroll") == 'on'){
	echo "<img src='".get_option("metro-general-bg-img")."' alt='background image' id='metro-bg-img'/>";
}*/
?>
<div id="metro-pjax">

<header id='metro-header'>
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
				href="<?php echo get_site_url();?>" 
				data-target-type="page-tiles" 
				data-target-id="<?php echo get_option('page_on_front')?>">
					<?php _e(get_bloginfo("name"), 'metro-template');?>
			</a>
			<div id="site-desc"><?php _e(get_bloginfo("description"), 'metro-template');?></div>
			<?php do_action('metro_header_titles');?>
			<?php
		}

		?>
	</div>

	<?php 
	$search_menu = (get_theme_mod('show_search_icon', 'on') != 'on') ? '' :
	'
	<li id="metro-nav-search-button" class="menu-item-has-children">
		<img width="16" height="16" src="'.get_stylesheet_directory_uri().'/img/icons/light/search_16x16.png">
		<div class="sub-menu-wrap" style="text-align:right;">
			<ul class="sub-menu">
				<li class="menu-item">
					'.get_search_form(false).'
				</li>
			</ul>
		</div>
	</li>
	';

	$search_menu = apply_filters('metro_main_nav', $search_menu);

	wp_nav_menu( array( 'sort_column' => 'menu_order', 'theme_location' => 'main_nav', 
						'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s'.$search_menu.'</ul>',
						'container_class'=>'main-nav', 'depth'=>2,  'walker' => new metro_main_nav_walker() ) ); ?>
</header>