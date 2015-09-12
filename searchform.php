<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/" style="vertical-align:middle;">
	<label class="hidden" for="s"><?php _e('Search:'); ?></label>
	<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
	<?php /*<!--<input type="submit" id="searchsubmit" value="GO" />-->*/?>
	<input type="image" src="<?php echo get_stylesheet_directory_uri(); ?>/img/icons/light/search_16x16.png" 
		class="button align-top" title="<?php _e('Search', 'metro-template');?>" alt="<?php _e('Search', 'metro-template');?>" />
</form>