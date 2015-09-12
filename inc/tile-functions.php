<?php
function metro_margin_left($group, $x,$group_x, $scale_spacing){
	return ($group_x[$group])*($scale_spacing)+$x*($scale_spacing);
}

function metro_margin_top($group,$y ,$scale_spacing){
	return $y*$scale_spacing+60;
}

function metro_get_link($url, $new_tab){
	/* Used to set an a tag into a tile. */
	if($url == '' || $url == false){
		return ' ';
	}
	$str =  " href='".esc_url($url)."' ";	
	if($new_tab){
		$str .= " target='_blank' ";
	}
	return $str;
}

function metro_open_tile_tag($url, $new_tab){
	if($url == ''){ //
		echo '<div ';
	}else{
		echo '<a '.metro_get_link($url, $new_tab);
	}	
}

function metro_close_tile_tag($url){
	if($url == ''){
		echo '</div>';
	}else{
		echo '</a>';
	}
}

function metro_pos_val($top, $left, $width, $height){
	return " data-pos='[".$top.",".$left.",".$width.",".$height."]' data-pos-curr='[".$top.",".$left.",".$width.",".$height."]' ";
}

/*function metro_permalink_to_type($url){
	return get_post_type(url_to_postid($url));
}*/

?>