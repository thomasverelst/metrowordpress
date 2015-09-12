<?php
require_once("../../../../../wp-load.php");
$metro_tiles = metro_include_tile_php($admin = true);
metro_include_tile_scripts($admin = true);

// Dequeueu 
function metro_remove_scripts(){
	wp_dequeue_script('metro-responsive');
	wp_dequeue_script('metro-page-transitions');
	wp_deregister_script('metro-responsive');
	wp_dequeue_style('metro-nav');
	wp_dequeue_style('metro-archive');
}
add_action('wp_enqueue_scripts', 'metro_remove_scripts')


?>

<html>
<head>

<?php 



wp_head();
include("../../css/css.php");
?>

<style>
body{
	padding:0;
	margin:0;
}
.tile{
	display:block;
	opacity: 1;
	margin:0 !important;
}
</style>
</head>
<body>
<?php
if ( current_user_can('edit_pages') ) {
 
	// For debugging reasons
	if(!isset($_GET['type'])){
		$_GET['type'] = 'simple';
		$_GET['scale'] = 140;
		$_GET['spacing'] = 10;
		$_GET['scale_spacing'] = 150;
	}

	$type = $_GET['type'];
	if(isset($metro_tiles['tiles'][$type])){
		// Load props
/*		foreach($metro_tiles['tiles'][$type]['defaults'] as $prop=>$default){
			if(isset($_GET[$prop])){
				$tile[$prop] = $_GET[$prop];
			}else{
				$tile[$prop] = $default;
			}
		}*/
		$tile = array_merge($metro_tiles['tiles'][$type]['defaults'], $_GET);
		
		//$tile = array_merge($tile, $metro_tiles['tiles'][$type]['defaults']);

		$tile['x'] = 0;
		$tile['y'] = 0;
		$tile['url'] =  '';

		if(is_callable($metro_tiles['tiles'][$type]['function'])) {
			call_user_func($metro_tiles['tiles'][$type]['function'], $tile);
		}
	}else{
		die("Tile type '".$type.'" not found.');
	}	
}

?>
</body>
</html>