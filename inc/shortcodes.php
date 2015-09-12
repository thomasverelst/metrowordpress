<?php

function metro_thumb_shortcode( $atts , $content = null) {
	if (has_post_thumbnail()){
		return '<div class="post-thumb">'.get_the_post_thumbnail().'</div>';
	}
	return '';
}
add_shortcode( 'post-thumb', 'metro_thumb_shortcode' );
?>