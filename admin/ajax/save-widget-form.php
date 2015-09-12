<?php
require_once("../../../../../wp-load.php");
if ( current_user_can('edit_pages') ) {
	$type = $_POST['metro_widget_type'];
	if(in_array($type, array_keys( $GLOBALS['wp_widget_factory']->widgets))){
		$widget = new $type();
		echo json_encode($widget->update($_POST, array()));
	}else{
		echo $type;
		die("Something wen't wrong. Please try selecting another widget type, or reselect the current one.");
	}	
}

?>