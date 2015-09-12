<?php
require_once("tile-functions.php");
$metro_tiles = metro_include_tile_php();
metro_include_tile_scripts();

function metro_parse_tiles($tiles = null, $scale = 140, $spacing = 10, $post_id = null){
	global $metro_tiles;

	$scale_spacing = $scale + $spacing;

	if($tiles == null && $post_id == null)
	{
		return false;
	}

	if($tiles == null )
	{
		$proc_tiles = get_post_meta( $post_id, "_metro_proc_tile_data", true );
		$tiles = json_decode(get_post_meta( $post_id, "_metro_tile_data", true ), true);
	}
	else
	{
/*		$scale = (int)get_option("metro-tile-scale");
		$spacing = (int)get_option("metro-tile-spacing");*/
		
	}

	if(true || !isset($proc_tiles)|| !$proc_tiles || $proc_tiles == "" || !is_array($proc_tiles) || count($proc_tiles) != count($tiles)){ // we don't have cached data, process the tiles	
		$proc_tiles = array(); // processed tiles
		$group_x = array();
		$most_right = 0;
		
		if(is_array($tiles) && count($tiles)>0){
			foreach($tiles as $tile){
				$type = $tile['type'];
				if($type == "group"){

					// update mostright
					if(isset($tile["margin_left"])){
						$margin_left = $tile["margin_left"];
					}else{
						$margin_left = 1;
					}
					if(count($group_x) > 0){
						$most_right += $margin_left;
					}

					//set props
					if(isset($tile["title"])){
						$title = $tile["title"];
					}else{
						$title = "";
					}
					
					/*Get animation */
					if($url != "" && (isset($tile['anim_data']) && $tile['anim_data'] != false) && (!isset($tile['new_tab']) && $tile['new_tab'] != true )){
						$animation_data = metro_get_animation_data($tile['anim_data']['target_id'], $tile['anim_data']['target_type'], $tile['anim_data']['target_bg_color'], $url);
					}else{
						$animation_data = "";
					}

					$proc_tiles[] = array("type"=>"group","nb"=>count($group_x), "url"=>$url,"margin_left"=>$margin_left, "most_right"=>$most_right, "title"=>$title, "animation_data"=>$animation_data);
					$group_x[] = $most_right;
					
					
				}else if(array_key_exists($type,$metro_tiles['tiles'])){
					// Check if all props set
					$props = array('group'=>count($group_x)-1);
					$props = array_merge($props, $metro_tiles['tiles'][$type]['defaults']);

					$props = metro_process_elements($tile, $props);
					$props['group_x']=$group_x;
					$props['scale']= $scale;
					$props['spacing']= $spacing;
					$props['scale_spacing']= $scale_spacing;

					//Update $most_right;
					if(end($group_x)+$props['x']+$props['width']>$most_right){
						$most_right = end($group_x)+$props['x']+$props['width'];
					}

					$proc_tiles[] = $props;
				}else{
					echo "Warning: tile type ".$type." not defined. ";
				}
			}

			$proc_tiles = array_msort($proc_tiles, array('group'=>SORT_ASC, 'x'=>SORT_ASC, 'y'=>SORT_ASC, 'width'=>SORT_ASC, 'height'=>SORT_ASC));
/*			foreach($proc_tiles as $tile){
				if(isset($tile['x'])){
					echo $tile['x']." ".$tile['y'].'<br/>';
				}
			}*/
			// save data
			//add_post_meta( $post->ID, '_metro_proc_tile_data',  $proc_tiles );
		}
	}
			


/*		foreach($tiles as $key=>$tile){
			$type = $tile["type"];

			if($type == "group"){
				$group_cnt += 1;
				$metro_wrapper_content.= get_group_div($group_cnt, $tile["title"]);
				$metro_wrapper_content.= '<script>jQuery(function(){
		        gridster['.$group_cnt.'] = jQuery(".gridster#gr'.$group_cnt.' > ul").gridster(settings).data("gridster");
		        })</script>';
		       
			}else{
				$tile["group"]=$group_cnt;
				$metro_wrapper_content.= '<script>jQuery(function(){insert_tile('.json_encode($tile).')})</script>';
			}
		}	*/


	//array_multisort($tiles); // for the animation
/*	// Outer loop prints cluster name as array key
	foreach ($saved_tiles as $cluster => $array) {
	  echo "<strong>$cluster: </strong><br>";
	  // Inner loop prints space-separated array values
	  foreach ($array as $key=>$val) {
	    echo $key." : ".$val."<br>";
	  }
	}*/
	metro_render_tiles($metro_tiles, $proc_tiles, $scale_spacing);
}

function metro_render_tiles($metro_tiles, $tiles, $scale_spacing){
	foreach($tiles as $tile){
		$type = $tile['type'];
		unset($tile['type']);
		if($type == 'group') {

			//Echo group html
			echo '<h3 class="group-title" id="group-title-'.$tile['nb'].'" ';
			echo 'data-x = "'.$tile['most_right'].'" data-margin-left = "'.$tile['margin_left'].'" '; 
			echo 'style="margin-left:'.metro_margin_left(0,$tile['most_right'], array(0), $scale_spacing).'px" >';

			if($tile['url'] != '') {
				echo '<a href="'.$tile['url'].'" '.$tile['animation_data'].'>';
			}

			echo $tile['title'];

			if($tile['url'] != ''){
				echo '<span class="group-title-link-indicator">></span>';
			}

			if($tile['url'] != '') {
				echo '</a>';
			}
			echo '</h3>';
			
		} else if(is_callable($metro_tiles['tiles'][$type]['function'])) {
			call_user_func($metro_tiles['tiles'][$type]['function'],$tile);
		} else {
			echo 'Warning: tile type '.$type.' not defined (parsing). ';
		}
	}
	/*global $group_x, $tile_func, $tile_admin;

	$most_right = 0;

	foreach($tiles as $tile){
		$type = $tile["type"];
		unset($tile["type"]);
		if($type == "group"){

			// update mostright
			if(count($group_x) > 0){
				$most_right++;
			}

			echo "<h2 class='groupTitle' id='groupTitle".count($group_x)."' data-x = '".$most_right."' "; 
			echo "style='margin-left:".metro_margin_left(0,$most_right)."px'>".$tile["title"]."</h2>";

			$group_x[] = $most_right;
			
		}else if(array_key_exists($type,$tile_func)){
			// Check if all props set
			$props = array("group"=>count($group_x)-1);
			$props = array_merge($props, $tile_func[$type]);
			$props = metro_process_elements($tile, $props);

			//Update $most_right;
			if(end($group_x)+$props["x"]+$props["width"]>$most_right){
				$most_right = end($group_x)+$props["x"]+$props["width"];
			}
			//$props["type"] = $type;

			if(is_callable("tile_".$type)){
				call_user_func("tile_".$type,$props);
			}else{
				echo "Warning: tile type ".$type." not defined";
			}
		}else{
			echo "Warning: tile type ".$type." not defined";
		}
	}*/
}

function metro_process_elements($props, $init_props){
	// replace defaults by retrieved ones
	foreach($props as $prop=>$val){
		$init_props[$prop] = $val;
	}
	return $init_props;
}


/*
$f = " 
[ simple ] 
	[x = 0] 
	[y = 0] 
	[width = 2] 
	[height = 1] 
	[background = #FFF] 
	[url_tag = ] 
	[title = Hello world] 
	[text = This is the text of this tile]
	[img http://localhost/img.png] 
	[imgAlt Try to get some]
[ / simple ] 
[centered]

[x 5] [y 1] [width 1] [height 4] [background #DDD] 
[img][imgAlt]
[/centered] ";
echo metro_parse_tiles($f)*/


function array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;

}

?>