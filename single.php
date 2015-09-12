<?php
/*
Template Name: Single post
Description: Shows a single post with normal layout. Can have a sidebar on the right side.
 */
get_header();
?>
<style>
#metro-tile-wrapper .tile{
	display:block;
}
</style>
<div id="metro-wrapper" class="type-single">	
	<div id="metro-tile-wrapper" >
        <div id="metro-tile-scroller">
           <div id="metro-tile-sizer">
           </div>
        </div>
    </div>
	<div id="metro-content-overlay">
		<div id="metro-content-wrapper" 
            style="background: <?php echo get_post_meta_def( $post->ID, '_metro_bg_color', true, get_theme_mod('page_bg_color', PAGE_BG_COLOR));?>;">

			<main id="metro-content"> <!--/single.php/-->
                <?php
                $postpage_id = get_option('page_for_posts');
                $postpage_url = get_permalink($postpage_id);
                if(isset($postpage_url) && $postpage_url != false ){
                	?>
                	<a id='metro-content-close' href="<?php echo $postpage_url;?>" 
	                data-target-type = "page-tiles"
	                data-target-id = "<?php echo $postpage_id;?>"
	                title='<?php _e('Close this window','metro-template');?>'>X</a>
                	<?php
                }
                ?>
                 
			    

			    <?php
			    if(have_posts()) : while(have_posts()) : the_post(); ?>
			    <section id="metro-content-divider">
				    <article class="post">
				    		<?php 
							$archive_year  = get_the_time('Y'); 
							$archive_month = get_the_time('m'); 
							$archive_day   = get_the_time('d'); 
							?>

				    		<div class="date" title="<?php the_time(get_option('date_format')."  ".get_option("time_format")) ?>" 
				    			style="background-color:<?php echo get_theme_mod('date_color',DATE_COLOR);?>;">
				    			<span class="left">
					    			<a class="day" title="<?php _e('See all posts made on this day.')?>"
					    				href="<?php echo get_day_link( $archive_year, $archive_month, $archive_day); ?>">
					    				<?php the_time('d') ?>
					    			</a>
					    			<a class="month" title="<?php _e('See all posts made in this month.')?>"
					    				href="<?php echo get_month_link( $archive_year, $archive_month ); ?>">
					    				<?php the_time('M') ?>
					    			</a>
					    		</span>
				    			<a class="year" title="<?php _e('See all posts made in this year.')?>"
				    				href="<?php echo get_year_link( $archive_year ); ?>">
				    				<?php the_time('Y') ?>
				    			</a>
				    		</div>
				    	<header>
				    		<h2 class="title"><?php the_title();?></h2>
				    		<div class="post-author"><?php _e('by'); ?> <?php the_author(); ?></div>
				    	</header>
				    	<?php the_content();?>


				    	<footer id="metro-post-footer">
				    		<?php 
				    		_e('Posted in '); 
				    		the_category(', '); 
				    		_e(' by ');
				    		the_author();
				    		_e(" at ");
				    		the_time(get_option('date_format')."  ".get_option("time_format")); 
				    		?>
				    		<br>
				    		<?php
				    		the_tags( ' Tags: ', ', ', '</p>');
				    		?>
				    		
						</footer>
				    	

				    </article>
				    <?php 
				    if(get_post_meta_def(get_the_ID(), '_metro_do_sidebar', true, 'on') == 'on'){
                        get_sidebar();
                    }
                    ?>
			    
			    </section>
			    <div class="pagination post-pagination">
			    <?php
			    $prev_link = get_previous_posts_link('&laquo; '.__('Older Entries', 'metro-template'));
				$next_link = get_next_posts_link(__('Newer Entries', 'metro-template').' &raquo;');

				// as suggested in comments
				if ($prev_link || $next_link) {
				  echo '<ul class="metro-anim-ul">';
				  if ($prev_link){
				    echo '<li>'.$prev_link .'</li>';
				  }
				  if ($next_link){
				    echo '<li>'.$next_link .'</li>';
				  }
				}
				?>
			    </div>

			    <?php 
			    if(get_post_meta_def( get_the_ID(), '_metro_do_comments', true, '') == 'on'){
                    comments_template();
                }
                ?>
			    <?php endwhile; // End post loop ?>
			    <?php endif; // End have_posts?>
			   
			</div>
		</main>
	</div>
</div>
<?php
get_footer();
?>