<?php
/*
Template Name: Tile Page
Description: A page that contains tiles as main content, with horizontal scroll layout
*/
?>

<?php
get_header();
?>

<div id="metro-wrapper" class="type-page-tiles"><!-- class gives current type of page-->
   <?php 
   if(have_posts()) :
   		while(have_posts()) : the_post();?>
	   		<div id="metro-tile-wrapper">
	   		<?php
	   		if(get_post_meta_def(get_the_ID(), '_metro_show_page_title', true, 'on') == 'on'){
	   			echo '<h2 id="metro-tiles-title" style="background-color:'.get_theme_mod('main_title_bg_color', MAIN_TITLE_BG_COLOR ).'">';
	   			the_title();
	   			echo '</h2>';
	   		}
	   		?>  
	        </h2>
	   			<div id="metro-tile-scroller" 
	   			<?php if(get_post_meta_def(get_the_ID(), '_metro_show_page_title', true, 'on') == 'on'){
	   				echo ' style="top:35px;" ';
	   			} 
	   			?>
	   			>
					<div id="metro-tile-sizer"
					data-tiles-id ="<?php echo get_the_ID();?>"
					data-tiles-url = "<?php echo get_permalink()?>"
					data-scale='<?php echo get_post_meta_def(get_the_ID(), '_metro_tile_scale', true, 140)?>'
           			data-spacing='<?php echo get_post_meta_def(get_the_ID(), '_metro_tile_spacing', true, 10)?>'>
					<?php
		            include_once("inc/parse-tiles.php");
		            metro_parse_tiles(null, get_post_meta_def(get_the_ID(), '_metro_tile_scale', true, 140), get_post_meta_def(get_the_ID(), '_metro_tile_spacing', true, 10), get_the_ID()); 
		            ?>
		            </div>
		        </div>
			</div>
	        <?php        
		endwhile;
	endif;?>
	 <div id="metro-content-overlay" style="display:none;">
        <div id="metro-content-wrapper">
        	<main id="metro-content">
        	</main>
        </div>
    </div>
</div>

<?php
get_footer();
?>