<?php
/*
Template Name: Single page
Description: Shows a single page with a normal layout. Can have a sidebar on the right side.
 */
get_header();
?>
<style>
#metro-tile-wrapper .tile{
    display:block;
}
</style>
<div id="metro-wrapper" class="type-page">
    <div id="metro-tile-wrapper" >
        <div id="metro-tile-scroller">
            <?php 
            /* Display tiles on background */

            if(get_page_template_slug($post->post_parent) == "page-tiles.php"){
                $parent_post = get_post($post->post_parent); 
                $tile_content = apply_filters('the_content', $parent_post->post_content); 
                ?>
                <div id="metro-tile-sizer" 
                    data-tiles-id ="<?php echo $post->post_parent;?>"
                    data-tiles-url="<?php echo get_permalink($post->post_parent);?>"
                    data-scale='<?php echo get_post_meta_def($post->post_parent, '_metro_tile_scale', true, 140)?>'
                    data-spacing='<?php echo get_post_meta_def($post->post_parent, '_metro_tile_spacing', true, 10)?>'>
                    <?php 
                    include_once("inc/parse-tiles.php");
                    metro_parse_tiles(null, get_post_meta_def($post->post_parent, '_metro_tile_scale', true, 140), get_post_meta_def($post->post_parent, '_metro_tile_spacing', true, 10), $post->post_parent);
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <div id="metro-content-overlay">
        <div id="metro-content-wrapper"
            style="background: <?php echo get_post_meta_def( $post->ID, '_metro_bg_color', true, get_theme_mod('page_bg_color', PAGE_BG_COLOR));?>;">
            <main id="metro-content">
               
                <?php
                if(have_posts()) : while(have_posts()) : the_post(); ?>
                     
                    <article class="post">
                        <h2 class="title"><?php the_title();?></h2>

                        <?php if (has_post_thumbnail()):?>
                        <div class="post-thumb">
                            <a href="<?php the_permalink();?>"><?php the_post_thumbnail();?></a>
                        </div>
                        <?php endif;?>
                        <?php the_content();?>

                    </article>
                    <?php 
                    if(get_post_meta_def( get_the_ID(), '_metro_do_sidebar', true, '') == 'on'){
                        get_sidebar('page');
                    }
                    ?>

                    <?php 
                    if(get_post_meta_def( get_the_ID(), '_metro_do_comments', true, '') == 'on'){
                        comments_template();
                    }
                    ?>
                <?php endwhile; ?>
                <?php endif;?>
            </main>
        </div>
    </div>
</div>
<?php
get_footer();
?>