<?php
/**
 * The Template for displaying all single posts
 *
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
    <div id="metro-tile-sizer">
    </div>
    </div>
    <div id="metro-content-overlay">
        <div id="metro-content-wrapper" class="color-<?php echo get_option("metro-page-def-color")?>" style="background: <?php echo get_post_meta( $post->ID, '_metro_bg_color', true );?>">
            <main id="metro-content">
                <div style="display:inline-block;font-size:76px;font-weight:300;margin:0 15px 0 0;">404</div>
                <div style="display:inline-block">
                    <h2 style='margin-bottom:5px;'>
                        <?php _e("We're sorry.", 'metro-template');?><span style='letter-spacing:5px; position:relative;margin-left:10px;'>:(</span>
                    </h2>
                    <h5 style='font-size:14px;margin-top:0px;'>
                        <?php _e("The page you're looking for is not found.", 'metro-template');?>
                   </h5>
                </div>
                <hr style="margin:30px 0 40px 0"/>
                <p class="center" style="font-size:16px;">
                <?php 
                printf(__('Why don\'t you try the %1$s homepage %2$s ?', 'metro-template'), 
                    '<a title="'.__(get_bloginfo("name"), 'metro-template').'" href="'.get_site_url().'">', 
                    '</a>');
                ?>
                <hr style="margin:40px 0 40px 0"/>
                <p> You can also try a search:

                <div class='box-content'><?php get_search_form();?></div>
                <p>Or maybe you'll find what you're looking for by browsing around:

                <div class="error-row panel-row-style-metro-style center">
                <style>
                .error-row.panel-row-style-metro-style .divider-third{
                    width:33%;
                    display: inline-block;
                    vertical-align: top;
                }
                .error-row.panel-row-style-metro-style .divider-third .panel.widget{
                    margin:5px;
                    text-align: left;
                }
                .error-row.panel-row-style-metro-style .divider-third .panel.widget a{
                    color:#FFF;
                    text-decoration: none;
                }
                .error-row.panel-row-style-metro-style .divider-third .panel.widget a:hover{
                    color:#F90;
                }
                </style>
                    <div class="divider-third">
                        <div class="panel widget" style="margin-left:0;">
                            <h3 class="widget-title"><?php _e('Archives', 'metro-template');?></h3>
                            <div>
                            <?php wp_get_archives( ); ?> 
                            </div>
                        </div>
                    </div>
                    <div class="divider-third">
                        <div class="panel widget">
                            <h3 class="widget-title"><?php _e('Categories', 'metro-template');?></h3>
                            <div>
                            <?php wp_list_categories( array('title_li'=>'')); ?> 
                            </div>
                        </div>
                    </div>
                    <div class="divider-third">
                        <div class="panel widget" style="margin-right:0;">
                            <h3 class="widget-title"><?php _e('Pages', 'metro-template');?></h3>
                            <div>
                            <?php wp_list_pages(array('title_li'=>'')); ?> 
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<?php
get_footer();
?>