<?php
// Echo all meta fields
$value = get_post_meta_def( $post->ID, '_metro_bg_color', true, get_theme_mod('page_bg_color', PAGE_BG_COLOR) );
echo '<label for="metro_bg_color">';
_e( 'Metro background color', 'metro-template' );
echo '</label> ';
echo '<input type="text" id="metro_bg_color" name="metro_bg_color" value="' . esc_attr( $value) . '" size="7" />';

$value = get_post_meta_def( $post->ID, '_metro_do_transition', true, 'on' );
echo '<p><label for="metro_do_transition">';
_e( 'Transition to this page', 'metro-template' );
echo '</label> ';
echo '<input type="checkbox" id="metro_do_transition" name="metro_do_transition" '.checked( $value, "on",0) . '/>';

$default = '';
$value = get_post_meta_def( $post->ID, '_metro_do_comments', true, $default);
echo '<p><label for="metro_do_comments">';
_e( 'Enable comments', 'metro-template' );
echo '</label> ';
echo '<input type="checkbox" id="metro_do_comments" name="metro_do_comments" '.checked( $value, "on",0) . '/>';

$default = '';
$value = get_post_meta_def( $post->ID, '_metro_do_sidebar', true, $default );
echo '<p><label for="metro_do_sidebar">';
_e( 'Enable sidebar', 'metro-template' );
echo '</label> ';
echo '<input type="checkbox" id="metro_do_sidebar" name="metro_do_sidebar" '.checked( $value, "on",0) . '/>';

?>