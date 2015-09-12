<?php
/*
Template Name: Archive page
Description: Shows the latest posts, the results of a search query, the posts of a category, the posts with a certain date... in a tile-based layout.
*/
?>

<?php
get_header();
/* Build tile content to show posts */
$tiles = array();
$nb_posts = 0;
if(have_posts()){
    // Next / prev post
    if ( !$max_page ){
        $max_page = $wp_query->max_num_pages;
    }
    if ( !$paged ){
        $paged = 1;  
    }
    $nextpage = intval($paged) + 1;



    if ( !is_single() && $paged > 1 ) {
        //Add group (no title)
        $tiles[] = array('type'=>'group','title'=>'');
        
        //Add post body
        $tiles[] = array(
            'type'=>'simple',
            'x'=>0,
            'y'=>0,
            'width'=>3,
            'height'=>3,
            'background'=>get_theme_mod('post_index_accent_color',POST_INDEX_ACCENT_COLOR),
            'url'=>previous_posts( false ),
            'title'=>'Newer posts',
            'text'=>'',
            'new_tab'=>false,
            'classes'=>array('metro-posts-nav'),
            'anim'=>false,
            'anim_data'=>array('target_id'=>'', 'target_type'=>'page-tiles', 'target_bg_color'=>'')
        );
    }

    while(have_posts()): the_post();?>
        <?php
        // Get the right background
        $type = metro_id_to_template(get_the_ID(), get_permalink());
        if($type == 'page-tiles'){
            continue;
        }else if($type == 'page'){
            $default = get_theme_mod('page_bg_color', PAGE_BG_COLOR);
        }else{
            $default = get_theme_mod('post_bg_color', POST_BG_COLOR);
        }

        $nb_posts++; // count number of posts, to determine if we need to show "no posts found"

        $bg = get_post_meta_def( get_the_ID(), '_metro_bg_color', true, $default);

        //Add post title
        $tiles[] = array(
            'type'=>'group',
            'title'=>get_the_title(), 
            'url'=>get_permalink(),
            'margin_left'=>1,
            'anim_data'=>array('target_id'=>get_the_ID(), 'target_type'=>$type, 'target_bg_color'=>$bg)
        );
        

        $archive_year  = get_the_time('Y'); 
        $archive_month = get_the_time('m'); 
        $archive_day   = get_the_time('d'); 
        
        //Add post body
        $post_body = '<div class="date small" title="'.get_the_time(get_option('date_format').' '.get_option("time_format")).'"
                         style="background-color:'.get_theme_mod('date_color',DATE_COLOR).';">
                            <span class="left">
                                <a class="day" title="'.__('See all posts made on this day.').'"
                                    href="'.get_day_link( $archive_year, $archive_month, $archive_day).'">
                                    '.get_the_time('d').'
                                </a>
                               <a class="month" title="'.__('See all posts made in this month.').'"
                                        href="'.get_month_link( $archive_year, $archive_month ).'">
                                    '.get_the_time('M').'
                                </a>
                            </span>
                            <a class="year" title="'.__('See all posts made in this year.').'"
                                    href="'.get_year_link( $archive_year ).'">
                                    '.get_the_time('Y').'
                            </a>
                        </div>';
        $post_body .= do_shortcode( get_the_content() );
        print get_the_tag_list();
        $post_body .= '<div class="metro-post-meta"><div class="metro-post-label">'.get_the_tag_list('', ', ', '').'</div>';
        $post_body .= '<a href="'.get_permalink().'" title="Read post" '.metro_get_animation_data(get_the_ID(), $type, $bg, get_permalink()).' class="metro-post-read-more">&raquo</a></div>';


        /* Top img */
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array(300,140));
        $url = $thumb['0'];
/*        $tiles[] = array(
            'type'=>'image',
            'x'=>0,
            'y'=>0,
            'width'=>4,
            'height'=>2,
            'background'=>$color,
            'url'=>get_permalink(),
            'img'=>$url,
            'classes'=>array(),
            'anim_data'=>array('target_id'=>get_the_id(), 'target_type'=>'post', 'target_bg_color'=>$bg)
        );*/
        if(has_post_thumbnail()){
            $tiles[] = array(
                'type'=>'flip',
                'x'=>0,
                'y'=>0,
                'width'=>10,
                'height'=>3,
                'background'=>'#F60',
                'url'=>get_permalink(),
                'direction'=>'vertical',
                'front_text'=>'',
                'front_img'=>$url,
                'back_text'=>'<h2 style="margin:0;display:inline;">Read more...</h2>',
                'classes'=>array(),
                'anim_data'=>array('target_id'=>get_the_ID(), 'target_type'=>$type, 'target_bg_color'=>$bg)
            );
        }

        

        /* Text */
        $tiles[] = array(
            'type'=>'post',
            'x'=>0,
            'y'=>has_post_thumbnail()*3,
            'width'=>10,
            'height'=>POST_INDEX_HEIGHT - has_post_thumbnail()*3,
            'background'=>$bg,
            'text'=> $post_body,
            'classes'=>array(),
            'label_position'=>'top',
            'label_color'=>get_theme_mod('post_index_accent_color',POST_INDEX_ACCENT_COLOR),
            'label_text'=>'<span style="color:#EEE">Posted in </span>'.get_the_category_list(', '),

            // 'label_text'=>get_the_category_list(', ').'<img title="'.__('Tags').'" width="14" height="14" src="'.get_stylesheet_directory_uri().'/img/icons/light/tag_16x16.png" style="margin: 1px 3px 0 5px;vertical-align:top;">',
        );

        //Add "read more" button
       /* $tiles[] = array(
            'type'=>'simple',
            'x'=>4,
            'y'=>2,
            'width'=>2,
            'height'=>2,
            'background'=>get_theme_mod('post_tile_page_accent_color','#F60'),
            'url'=>get_permalink(),
            'title'=>'',
            'text'=>'<div style="font-size:16px;margin-top:3px;">'.__('Read more','metro-template').'</div>',
            'classes'=>array(),
            'anim_data'=>array('target_id'=>get_the_id(), 'target_type'=>'post', 'target_bg_color'=>$bg)
        );*/
        


        // Add comments button
        $num_comments = get_comments_number(); // get_comments_number returns only a numeric value
        $color = get_theme_mod('post_index_accent_color',POST_INDEX_ACCENT_COLOR);
        if ( comments_open() ) {
            $comments = '<div style="font-size:16px;margin-top:7px;">';
            if ( $num_comments == 0 ) {
                $comments .= __('No Comments','metro-template');
            } elseif ( $num_comments > 1 ) {
                $comments .= $num_comments . __(' Comments');
            } else {
                $comments .= __('1 Comment');
            }
            $comments .= '</div>';
        } else {
            $comments =  '<div style="font-size:13px;margin-top:7px;">'.__('Comments turned off').'</div>';
            $color = '#777';
        }

        $tiles[] = array(
            'type'=>'simple',
            'x'=>0,
            'y'=>POST_INDEX_HEIGHT,
            'width'=>5,
            'height'=>1,
            'background'=>$color,
            'url'=>get_comments_link($post->ID),
            'title'=>'',
            'text'=>$comments,
            'classes'=>array(),
            'anim_data'=>array('target_id'=>get_the_ID(), 'target_type'=>$type, 'target_bg_color'=>$bg)
        );

        // Add author button
        $tiles[] = array(
            'type'=>'simple',
            'x'=>5,
            'y'=>POST_INDEX_HEIGHT,
            'width'=>5,
            'height'=>1,
            'background'=>'#777',
            'title'=>'',
            'url'=>get_author_posts_url(get_the_author_id()),
            'text'=>'<div style="font-size:12px;margin-top:7px;"">'.sprintf(__('Posted by %s','metro'),get_the_author()).'</div>',
            'classes'=>array()
        );
        ?>
    <?php endwhile;

    if ( !is_single() && ( $nextpage <= $max_page) ) {
        $tiles[] = array(
            'type'=>'group',
            'title'=>'',
            'margin_left'=>1
        );
        $tiles[] = array(
            'type'=>'simple',
            'x'=>0,
            'y'=>0,
            'width'=>3,
            'height'=>3,
            'background'=>get_theme_mod('post_index_accent_color',POST_INDEX_ACCENT_COLOR),
            'url'=>next_posts( $max_page, false ),
            'title'=>'Older posts',
            'text'=>'',
            'new_tab'=>false,  
            'classes'=>array('metro-posts-nav'),
            'anim_data'=>array('target_id'=>'', 'target_type'=>'page-tiles', 'target_bg_color'=>'')
        );
    }
}
if($nb_posts == 0){ // No posts to show
     //Add post title
    $tiles[] = array('type'=>'group','title'=>'');
    
    //Add post body
    $tiles[] = array(
        'type'=>'simple',
        'x'=>0,
        'y'=>0,
        'width'=>6,
        'height'=>3,
        'background'=>get_theme_mod('post_index_accent_color',POST_INDEX_ACCENT_COLOR),
        'url'=>'',
        'title'=>'No posts found',
        'text'=>'',
        'new_tab'=>false,
        'classes'=>array(),
        'anim'=>false,
        'anim_data'=>array('target_id'=>'', 'target_type'=>'page-tiles', 'target_bg_color'=>'')
    );
} //End have_posts




    /*
    <div class="pagination">
        <ul>
            <li class="older"><?php next_posts_link("Older" );?></li>
            <li class="newer"><?php previous_posts_link("Newer" );?></li>
        </ul>
    </div>
<?php// else: /*?>
    <h2> Nothing found</h2>
    <p>Sorry, we could not find the page you were looking for</p>
    <p><a href="<?php echo get_option('home');?>"> Return to the homepage</a></p>
<?php // endif;*/
?>

<div id="metro-wrapper" class="type-page-tiles page-index"><!-- class gives current type of page-->
    <div id="metro-tile-wrapper">
        <h2 id="metro-tiles-title" style="background-color:<?php echo get_theme_mod('main_title_bg_color', MAIN_TITLE_BG_COLOR )?>">
            <?php
            if(is_search()){
                ?>
                <span style='color:rgba(255,255,255,0.9);'><?php _e('Searching for','metro-template');?></span> '<?php the_search_query(); ?>'
                <?php
            }elseif(is_category()){
                single_cat_title();
            }elseif(is_tag()){
                single_tag_title();
            }elseif(is_year()){
                 printf(__('Posts made in %s', 'metro-template'),  get_the_time('Y'));
            }elseif(is_month()){
                 printf(__('Posts made in %s', 'metro-template'), get_the_time('F, Y'));
            }elseif(is_day()){
                 printf(__('Posts made on %s', 'metro-template'), get_the_time(get_option('date_format')));
            }elseif(is_author()){
                if(get_query_var('author_name')){
                    $curauth = get_user_by('slug', get_query_var('author_name'));
                }else{
                    $curauth = get_userdata(get_query_var('author'));
                }
                printf(__('Posts made by %s', 'metro-template'), $curauth->nickname);
            }elseif(is_archive()){ // Just to be sure
                single_cat_title();
            }else{
                _e('All posts', 'metro-template');
            }?>
            <span id='metro-tiles-sub-title'> 
                <?php 
                if(!have_posts() && $max_page != NULL){
                    echo __('Page').' '.$paged.' '.__('of').' '.$max_page;
                }
                ?>
            </span>
        </h2>
        <div id="metro-tile-scroller" style='padding-top:35px;' > 
            <div id="metro-tile-sizer" data-tiles-id ="<?php echo get_the_ID();?>" 
            data-tiles-id ="<?php echo get_the_ID();?>"
            data-tiles-url = "<?php echo get_permalink()?>"
            data-scale='<?php echo POST_INDEX_SCALE;?>'
            data-spacing='<?php echo POST_INDEX_SPACING;?>'>
                <?php
                include_once('inc/parse-tiles.php');
                metro_parse_tiles($tiles, POST_INDEX_SCALE, POST_INDEX_SPACING); 
                ?>
            </div>
        </div>
    </div>
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