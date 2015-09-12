<?php
/* TILE SIMPLE */
add_filter('metro_include_tiles', function($metro_tiles){
	$metro_tiles['tiles']['dummy'] =
	array(
		'defaults' => array( /* Defaults*/
			'x'=>0,
			'y'=>0,
			'width'=>2,
			'height'=>1,
			'background'=>'#999',
			'classes'=>array(),
			'anim_data'=>array('target_id'=>false, 'target_type'=>false, 'target_bg_color'=>false),
			'attr'=>array()
		),

		'admin' => array(/* Admin panel*/
			'classes'=>array(__('extra CSS classes (optional)', 'metro-template') ,'array','text')
		),

		'function' => function($args){
		}
	);
/*	$metro_tiles['tiles']['renderd_dummy'] =
	array(
		'defaults' => array( /* Defaults*
			'x'=>0,
			'y'=>0,
			'width'=>2,
			'height'=>1,
			'background'=>'#999',
			'classes'=>array(),
			'anim_data'=>array('target_id'=>false, 'target_type'=>false, 'target_bg_color'=>false),
			'attr'=>array()
		),

		'admin' => array(/* Admin panel*
			'classes'=>array(__('extra CSS classes (optional)', 'metro-template') ,'array','text')
		),

		'function' => function($args){
		}
	);
*/
	$metro_tiles['tiles']['simple'] =
	array(
		'defaults' => array( /* Defaults*/
			'x'=>0,
			'y'=>0,
			'width'=>2,
			'height'=>1,
			'background'=>$metro_tiles['defaults']['bg'],
			'url'=>'',
			'new_tab'=>false,
			'title'=>'The title',
			'text'=>'the description text',
			'img'=>'',
		/*	'img_alt'=>'',
			'img_title'=>'',*/
			'img_size'=>'50',
			/*'img_top'=>'5',
			'img_left'=>'5',*/
			'label_text'=>'',
			'label_color'=>$metro_tiles['defaults']['label_color'],
			'label_position'=>$metro_tiles['defaults']['label_position'],
			'classes'=>array(),
			'anim_data'=>array('target_id'=>false, 'target_type'=>false, 'target_bg_color'=>false),
			'attr'=>array()
		),

		'admin' => array(/* Admin panel*/
			'background'=>array(__('background color', 'metro-template'),'color'),
			'url'=>array(__('url', 'metro-template'),'url'),
			'new_tab'=>array(__('open link in new tab', 'metro-template'),'checkbox'),
			'title'=>array(__('the title', 'metro-template'),'text'),
			'text'=>array(__('the small text', 'metro-template'),'text'),
			'img'=>array(__('icon image (must be square size!)', 'metro-template'),'image'),
			/*'img_alt'=>array('alternative text for image (not required)', 'text'),*/
			/*'img_title'=>array('image title (seen on hover) (not required)', 'text'),*/
			'img_size'=>array(__('image size in px', 'metro-template'),'int'),
			/*'img_top'=>array('','int'),
			'img_left'=>array('','int'),*/
			'label_text'=>array(__('Label text (leave blank for no label)', 'metro-template'),'text'),
			'label_color'=>array(__('Label color', 'metro-template'),'color'),
			'label_position'=>array(__('Label position', 'metro-template'),'select',array('top'=>'top','bottom'=>'bottom')),
			'classes'=>array(__('extra CSS classes (optional)', 'metro-template') ,'array','text')
		),

		'function' => function($args){
			// Process arguments
			foreach($args as $key=>$arg){
				$$key = $arg;
			}

			/*Get animation */
			if($anim_data != false && !$new_tab){
				$animation_data = strip_tags(metro_get_animation_data($anim_data['target_id'], $anim_data['target_type'], $anim_data['target_bg_color'], $url));
			}else{
				$animation_data = '';
			}

			/*Dimensions */
			$margin_left = metro_margin_left($group, $x, $group_x, $scale_spacing);
			$margin_top = metro_margin_top($group,$y, $scale_spacing);
			$tile_width = $width*$scale_spacing-$spacing;
			$tile_height = $height*$scale_spacing-$spacing;

			/*Img processing */
			$hasImg = $img !== false && $img != '';
			?>

		  	<?php
			metro_open_tile_tag($url, $new_tab);
			?>
		  	class="tile tile-simple group<?php echo $group?> <?php echo join(' ',$classes);?>" 
		  	style="
		    margin-top:<?php echo $margin_top;?>px; 
		    margin-left:<?php echo $margin_left;?>px;
			width:<?php echo $tile_width?>px; 
			height:<?php echo $tile_height?>px;
			background:<?php echo $background;?>;
			" 
			<?php echo metro_pos_val($x, $y, $width, $height); ?>
			<?php echo $animation_data; ?>
			<?php echo metro_get_attr_data($attr);?>
			>
		    <div class="tile-vertical-wrap">
			    <div class="tile-content-wrap">
				    <?php 
				    if( $hasImg )
				    {
						?>
						<img class = "tile-icon-img" src='<?php echo $img?>' height="<?php echo $img_size?>"/>
				    	<?php 
				    } 
				    ?>
				    <div class="tile-text-wrap">
						<h3 class='tile-title'><?php echo $title?></h3>
						<div class='tile-desc' ><?php echo $text ?></div>
					</div>
				</div>
			</div>
		    <?php
		     if($label_text != ''){
				if($label_position == 'top'){
					echo "<div class='tile-label-wrap top' style='border-top-color:".$label_color.";'><div class='tile-label top' >".$label_text."</div></div>";
				}else{
					echo "<div class='tile-label-wrap bottom'><div class='tile-label bottom' style='border-bottom-color:".$label_color.";'>".$label_text."</div></div>";
				}
			}
			metro_close_tile_tag($url);
		}
	);

	/* TILE POST */
	$metro_tiles['tiles']['post'] =
	array(
		'defaults'=> array( /* Defaults*/
			'x'=>0,
			'y'=>0,
			'width'=>2,
			'height'=>1,
			'background'=>get_option('metro-page-def-bg'),
			'url'=>'',
			'new_tab'=>false,
			'text'=>'',
			'img'=>'',
			'img_size'=>'50',
			'label_text'=>'',
			'label_color'=>$metro_tiles['defaults']['label_color'],
			'label_position'=>$metro_tiles['defaults']['position'],
			'classes'=>'',
			'anim_data'=>array('target_id'=>false, 'target_bg_color'=>false, 'target_type'=>false),
			'attr'=>array()
		),
		'function'=> function($args){
			// Process arguments
			foreach($args as $key=>$arg){
				$$key = $arg;
			}

			//Get animation
			if($anim_data != false && !$new_tab){
				$animation_data = strip_tags(metro_get_animation_data($anim_data['target_id'], $anim_data['target_type'], $anim_data['target_bg_color'], $url));
			}else{
				$animation_data = '';
			}

			//Process attributes
			$attr_str = '';
			if(is_array($attr)){
				foreach($attr as $att=>$val){
					$attr_str .= ' '.$att.' ="'.$val.'" ';
				}
			}

			/*Dimensions */
			$margin_left = metro_margin_left($group, $x, $group_x, $scale_spacing);
			$margin_top = metro_margin_top($group,$y, $scale_spacing);
			$tile_width = $width*$scale_spacing-$spacing;
			$tile_height = $height*$scale_spacing-$spacing;

			/*Img processing */
			$hasImg = $img !== false && $img != '';

			?>

			<?php
			metro_open_tile_tag($url, $new_tab);
			?>
		  	class="tile post group<?php echo $group?> <?php echo join(' ',$classes);?>" 
		  	style="
		    margin-top:<?php echo $margin_top;?>px; 
		    margin-left:<?php echo $margin_left;?>px;
			width:<?php echo $tile_width?>px; 
			height:<?php echo $tile_height?>px;
			background:<?php echo $background;?>;
			" 
			<?php echo metro_pos_val($x, $y, $width, $height); ?>
			<?php echo $animation_data; ?>
			> 
		    <div class="tile-content-wrap">
			    <?php 
			    if( $hasImg )
			    {
					?>
					<img class = "tile-icon-img" src='<?php echo $img?>' height="<?php echo $img_size?>"/>
			    	<?php 
			    } 
			    ?>
				<div class='tile-text' ><?php echo $text ?></div>
				<div class='fadeout' style='
				background: -moz-linear-gradient(top, transparent 0%, <?php echo $background;?>);
				background: -webkit-linear-gradient(top, transparent 0%,<?php echo $background;?>);
				background: -o-linear-gradient(top, transparent 0% ,<?php echo $background;?>);
				background: -ms-linear-gradient(top, transparent 0%,<?php echo $background;?>);
				background: linear-gradient(to bottom, transparent 0%,<?php echo $background;?> );
				'></div>
			</div>
		    <?php
		    if($label_text!=''){
				if($label_position=='top'){
					echo "<div class='tile-label-wrap top' style='border-top-color:".$label_color.";'><div class='tile-label top' >".$label_text."</div></div>";
				}else{
					echo "<div class='tile-label-wrap bottom'><div class='tile-label bottom' style='border-bottom-color:".$label_color.";'>".$label_text."</div></div>";
				}
			}
			metro_close_tile_tag($url);
		}
	);
	?>

	<?php
	/* TILE SIMPLE */
	$widget_types = array();
	foreach( array_keys( $GLOBALS['wp_widget_factory']->widgets ) as $index=>$widget_type){
		$widget_types[$widget_type] = $widget_type;
	}

	$metro_tiles['tiles']['widget'] =
	array(
		'defaults' => array( /* Defaults**/
			'x'=>0,
			'y'=>0,
			'width'=>2,
			'height'=>1,
			'background'=>$metro_tiles['defaults']['bg'],
			'url'=>false,
			'new_tab'=>false,
			'widget_type'=>'',
			'widget_props'=>array(),
			'label_text'=>'',
			'label_color'=>$metro_tiles['defaults']['label_color'],
			'label_position'=>$metro_tiles['defaults']['label_position'],
			'classes'=>array(),
			'attr'=>array()
		),
		'admin' => array( /* Admin panel*/
			'background'=>array('background color','color'),
			'widget_type'=>array('The type of widget','select', $widget_types),
			'widget_props'=>array(' ','widget_props'),
			'label_text'=>array('Label text (leave blank for no label)','text'),
			'label_color'=>array('','color'),
			'label_position'=>array('','select',array('top'=>'top','bottom'=>'bottom')),
			'classes'=>array('','array','text')
		),
		'function' => function($args){
			// Process arguments
			foreach($args as $key=>$arg){
				$$key = $arg;
			}

			//Process attributes
			$attr_str = '';
			if(is_array($attr)){
				foreach($attr as $att=>$val){
					$attr_str .= ' '.$att.' ="'.$val.'" ';
				}
			}

			/*Dimensions */
			$margin_left = metro_margin_left($group, $x, $group_x, $scale_spacing);
			$margin_top = metro_margin_top($group,$y, $scale_spacing);
			$tile_width = $width*$scale_spacing-$spacing;
			$tile_height = $height*$scale_spacing-$spacing;

			/*Img processing */
			$hasImg = $img !== false && $img != '';

			?>

		  	<?php
			metro_open_tile_tag($url, $new_tab);
			?>
		  	class="tile widget group<?php echo $group?> <?php echo join(' ',$classes);?>" 
		  	style="
		    margin-top:<?php echo $margin_top;?>px; 
		    margin-left:<?php echo $margin_left;?>px;
			width:<?php echo $tile_width?>px; 
			height:<?php echo $tile_height?>px;
			background:<?php echo $background;?>;
			" 
			<?php echo metro_pos_val($x, $y, $width, $height); ?>
			<?php echo $attr_str ?>
			> 
			<section id="sidebar" >
		    <?php the_widget($widget_type, $widget_props);?>
			</section>


		    <?php
		    if($label_text!=''){
				if($label_position == 'top'){
					echo "<div class='tile-label-wrap top' style='border-top-color:".$label_color.";'><div class='tile-label top' >".$label_text."</div></div>";
				}else{
					echo "<div class='tile-label-wrap bottom'><div class='tile-label bottom' style='border-bottom-color:".$label_color.";'>".$label_text."</div></div>";
				}
			}
			metro_close_tile_tag($url);
		}
	);


	$metro_tiles['tiles']['centered'] =
	array(
		'defaults' => array( /* Defaults*/
			"x"=>0,
			"y"=>0,
			'width'=>2,
			'height'=>1,
			"background"=>$metro_tiles['defaults']['bg'],
			"background_hover"=>"",
			"color"=>"#FFF",
			"color_hover"=>"",
			"url"=>"",
			"new_tab"=>false,
			"title"=>"Example",
			"img"=>"",
			"img_size"=>"50",
			"label_text"=>"",
			"label_color"=>$metro_tiles['defaults']['label_color'],
			"label_position"=>$metro_tiles['defaults']['label_position'],
			"classes"=>array(),
			"anim_data"=>array("target_id"=>false, "target_bg_color"=>false, "target_type"=>false),
			"attr"=>array()
		),
		'admin' => array( /* Admin panel*/
			"background"=>array("background color","color"),
			"background_hover"=>array("background color on hover","color"),
			"color"=>array("text color","color"),
			"color_hover"=>array("text color on hover","color"),
			"url"=>array("url","url"),
			"new_tab"=>array("open link in new tab","checkbox"),
			"title"=>array("text","text"),
			"img"=>array("","image"),
			"img_size"=>array("","int"),
			"label_text"=>array("Label text (leave blank for no label)","text"),
			"label_color"=>array("","color"),
			"label_position"=>array("","select",array("top"=>"top","bottom"=>"bottom")),
			"classes"=>array("","array","text"),
		),
		'function' => function($args){
			// Process arguments
			foreach($args as $key=>$arg){
				$$key = $arg;
			}

			//Get animation
			if($anim_data != false && !$new_tab){
				$animation_data = metro_get_animation_data($anim_data['target_id'], $anim_data['target_type'], $anim_data['target_bg_color'], $url);
			}else{
				$animation_data = "";
			}

			//Process attributes
			$attr_str = "";
			if(is_array($attr)){
				foreach($attr as $att=>$val){
					$attr_str .= ' '.$att.' ="'.$val.'" ';
				}
			}
			/*Dimensions */
			$margin_left = metro_margin_left($group, $x, $group_x, $scale_spacing);
			$margin_top = metro_margin_top($group,$y, $scale_spacing);
			$tile_width = $width*$scale_spacing-$spacing;
			$tile_height = $height*$scale_spacing-$spacing;

			/*Img processing */
			$hasImg = $img !== false && $img != "";
			?>

		  	<?php
			metro_open_tile_tag($url, $new_tab);
			?>
		  	class="tile tile-centered group<?php echo $group?> <?php echo join(" ",$classes);?>" 
		  	style="
		    margin-top:<?php echo $margin_top;?>px; 
		    margin-left:<?php echo $margin_left;?>px;
			width:<?php echo $tile_width?>px; 
			height:<?php echo $tile_height?>px;
			background:<?php echo $background;?>;
			" 
			<?php echo metro_pos_val($x, $y, $width, $height); ?>
			<?php echo $animation_data; ?>
			>  
		    
		    <div class="container" style="background:<?php echo $background;?>;"
			    <?php if($background_hover != ""){
					?>
					onMouseOver="javascript:$(this).css('background','<?php echo $background_hover?>')"
					onMouseOut="javascript:$(this).css('background','<?php echo $background;?>')"
					<?php
				}?>
				>
			    <h3 style='color:<?php echo $color?>'
				    <?php if($color_hover != ""){
				    	?>
						onMouseOver="javascript:$(this).css('color','<?php echo $color_hover?>')"
						onMouseOut="javascript:$(this).css('color','<?php echo $color?>')"
						<?php
					}?>
				    >
				    <?php if($img != ""){?>
				    <img title='<?php echo $img_title?>' alt='<?php echo $img_alt?>' 
				    	src='<?php echo $img?>' 
				    	height="<?php echo $img_size?>" 
				  	/>
				    <?php } ?>
				    <?php echo $title;?>
			    </h3>
			</div>
		    <?php
		    if($label_text!=""){
				if($label_position=='top'){
					echo "<div class='tile-label-wrap top' style='border-top-color:".$label_color.";'><div class='tile-label top' >".$label_text."</div></div>";
				}else{
					echo "<div class='tile-label-wrap bottom'><div class='tile-label bottom' style='border-bottom-color:".$label_color.";'>".$label_text."</div></div>";
				}
			}
			?>	 
		    <?php
			metro_close_tile_tag($url);
			?>
		    <?php
		}
	);


	$metro_tiles['tiles']['centered_slide'] =
	array(
		'defaults' => array( /* Defaults*/
			"x"=>0,
			"y"=>0,
			'width'=>2,
			'height'=>1,
			"background"=>$metro_tiles['defaults']['bg'],
			"color"=>"#FFF",
			"url"=>"",
			"new_tab"=>false,
			"title"=>"This title will be on front",
			"text"=>"This text will show up when hovered",
			"direction"=>"left", /* top, right, bottom, left*/
			"img"=>"",
			"img_size"=>"50",
			"label_text"=>"",
			"label_color"=>$metro_tiles['defaults']['label_color'],
			"label_position"=>$metro_tiles['defaults']['label_position'],
			"classes"=>array(),
			"anim_data"=>array("target_id"=>false, "target_bg_color"=>false, "target_type"=>false),
			"attr"=>array()
		),
		'admin' => array( /* Admin panel*/
			"background"=>array("background color","color"),
			"color"=>array("text color","color"),
			"url"=>array("url","url"),
			"new_tab"=>array("open link in new tab","checkbox"),
			"title"=>array("Title","text"),
			"text"=>array("Description text","text"),
			"direction"=>array('Direction of slide', "select", array("top"=>"top", "right"=>"right", "bottom"=>"bottom", "left"=>"left") ),
			"img"=>array("Icon image","image"),
			"img_size"=>array("Icon size in px","int"),
			"label_text"=>array("Label text (leave blank for no label)","text"),
			"label_color"=>array("","color"),
			"label_position"=>array("","select",array("top"=>"top","bottom"=>"bottom")),
			"classes"=>array("","array","text"),
		),
		'function' => function($args){
			// Process arguments
			foreach($args as $key=>$arg){
				$$key = $arg;
			}

			//Get animation
			if($anim_data != false && !$new_tab){
				$animation_data = metro_get_animation_data($anim_data['target_id'], $anim_data['target_type'], $anim_data['target_bg_color'], $url);
			}else{
				$animation_data = "";
			}

			//Process attributes
			$attr_str = "";
			if(is_array($attr)){
				foreach($attr as $att=>$val){
					$attr_str .= ' '.$att.' ="'.$val.'" ';
				}
			}

			//add direction to classes
			$classes[] = $direction;

			/*Dimensions */
			$margin_left = metro_margin_left($group, $x, $group_x, $scale_spacing);
			$margin_top = metro_margin_top($group,$y, $scale_spacing);

			$tile_width = $width*$scale_spacing-$spacing;
			$tile_height = $height*$scale_spacing-$spacing;

			/*Img processing */
			$hasImg = $img !== false && $img != "";
			?>

		  	<?php
			metro_open_tile_tag($url, $new_tab);
			?>
		  	class="tile tile-centered-slide group<?php echo $group?> <?php echo join(" ",$classes);?>" 
		  	style="
		    margin-top:<?php echo $margin_top;?>px; 
		    margin-left:<?php echo $margin_left;?>px;
			width:<?php echo $tile_width?>px; 
			height:<?php echo $tile_height?>px;
			background:<?php echo $background;?>;
			" 
			<?php echo metro_pos_val($x, $y, $width, $height); ?>
			<?php echo $animation_data; ?>
			>  
			<div class="container1">
		    	<h3>
		     	<?php if($img != ""){?>
		    	<img src='<?php echo $img?>' height="<?php echo $img_size?>"/>
		    	<?php } ?>

			    <?php echo $title;?>
		    	</h3>
		  	</div>
		    <div class="container2">
		    	<h5><?php echo $text;?></h5>
		    </div>

		    <?php
		    if($label_text!=""){
				if($label_position=='top'){
					echo "<div class='tile-label-wrap top' style='border-top-color:".$label_color.";'><div class='tile-label top' >".$label_text."</div></div>";
				}else{
					echo "<div class='tile-label-wrap bottom'><div class='tile-label bottom' style='border-bottom-color:".$label_color.";'>".$label_text."</div></div>";
				}
			}
			?> 
				
		    <?php
			metro_close_tile_tag($url);
			?>
		    <?php
		}
	);

	$metro_tiles['tiles']['slideshow'] =
	array(
		'defaults' => array( /* Defaults*/
			'x'=>0,
			'y'=>0,
			'width'=>2,
			'height'=>1,
			'background'=>$metro_tiles['defaults']['bg'],
			'url'=>'',
			'new_tab'=>false,
			'imgs'=>array(),
			'img_alts'=> array(),
			'img_descs' => array(),
			'effect'=>'slide-right',
			'speed'=>6000,
			'show_arrows'=>'on',
			'label_text'=>'',
			'label_color'=>$metro_tiles['defaults']['label_color'],
			'label_position'=>$metro_tiles['defaults']['label_position'],
			'classes'=>array(),
			'anim_data'=>array('target_id'=>false, 'target_type'=>false, 'target_bg_color'=>false),
			'attr'=>array()
		),

		'admin' => array(/* Admin panel*/
			'background'=>array(__('background color', 'metro-template'),'color'),
			'url'=>array(__('url', 'metro-template'),'url'),
			'new_tab'=>array(__('open link in new tab', 'metro-template'),'checkbox'),
			'imgs'=>array(__('Slideshow images', 'metro-template'),'array','image'),
			'img_alts'=>array(__('Image alt text (optional, must match the amount of images)', 'metro-template'),'array','text'),
			'img_descs'=>array(__('Image descriptions. Must match the amount of images, or just add one for all images. Leave blank for no description', 'metro-template'),'array','text'),
			'effect'=>array(__('Transition effect', 'metro-template'),'select',array('slide-right'=>'slide-right', 'slide-left'=>'slide-left', 'slide-up'=>'slide-up', 'slide-down'=> 'slide-down', 'fade'=>'fade', 
											   										 'slide-horizontal-alternate'=>'slide-horizontal-alternate', 'slide-vertical-alternate'=>'slide-vertical-alternate')),
			'speed'=>array(__('Delay (time to show each image in millisecons)', 'metro-template'),'int'),
			'show_arrows'=>array(__('Show next/previous arrows', 'metro-template'),'checkbox'),
			'label_text'=>array(__('Label text (leave blank for no label)', 'metro-template'),'text'),
			'label_color'=>array(__('Label color', 'metro-template'),'color'),
			'label_position'=>array(__('Label position', 'metro-template'),'select',array('top'=>'top','bottom'=>'bottom')),
			'classes'=>array(__('extra CSS classes (optional)', 'metro-template') ,'array','text')
		),

		'function' => function($args){
			// Process arguments
			foreach($args as $key=>$arg){
				$$key = $arg;
			}

			/*Get animation */
			if($anim_data != false && !$new_tab){
				$animation_data = strip_tags(metro_get_animation_data($anim_data['target_id'], $anim_data['target_type'], $anim_data['target_bg_color'], $url));
			}else{
				$animation_data = '';
			}

			/*Dimensions */
			$margin_left = metro_margin_left($group, $x, $group_x, $scale_spacing);
			$margin_top = metro_margin_top($group,$y, $scale_spacing);
			$tile_width = $width*$scale_spacing-$spacing;
			$tile_height = $height*$scale_spacing-$spacing;

			?>

		  	<?php
			metro_open_tile_tag($url, $new_tab);
			?>
		  	class="tile tile-slideshow group<?php echo $group?> <?php echo join(' ',$classes);?>" 
		  	style="
		    margin-top:<?php echo $margin_top;?>px; 
		    margin-left:<?php echo $margin_left;?>px;
			width:<?php echo $tile_width?>px; 
			height:<?php echo $tile_height?>px;
			background:<?php echo $background;?>;
			" 
			data-imgs = '<?php echo json_encode($imgs);?>'
			data-img_alts = '<?php echo json_encode($img_alts);?>'
			data-img_descs = '<?php echo json_encode($img_descs);?>'
			data-effect = '<?php echo $effect;?>'
			data-speed = '<?php echo $speed;?>' 
			<?php echo metro_pos_val($x, $y, $width, $height); ?>
			<?php echo $animation_data; ?>
			<?php echo metro_get_attr_data($attr);?>
			>
		    <div class='slideshow-img-wrap-back' style="width: <?php echo $tile_width+2?>px; height:<?php echo $tile_height+2?>px">
		    	<img src='<?php echo $imgs[0]?>' class='img-fill' alt='<?php echo $img_alts[0]?>'/>
		    </div>
			<div class='slideshow-img-wrap' style="width: <?php echo $tile_width+2?>px; height:<?php echo $tile_height+2?>px">
				<img src='<?php echo $imgs[0]?>' class='img-fill'  alt='<?php echo $img_alts[0]?>' />
			</div>
		   
		    <?php
			if(count($img_descs)>0){
				echo  "<div class='slideshow-img-desc'>".$img_descs[0]."</div>";
			}
			?>

		    <?php
			if($show_arrows || $show_arrows == 'on'){
				echo '<div class="slideshow-arrow-left"><img src="'.get_template_directory_uri().'/img/arrows/light/arrow_left_alt.png" alt="Slideshow - previous"/></div>';
				echo '<div class="slideshow-arrow-right"><img src="'.get_template_directory_uri().'/img/arrows/light/arrow_right_alt.png" alt="Slideshow - next"/></div>';
			}
			?>
			<?php

		    if($label_text != ''){
				if($label_position == 'top'){
					echo "<div class='tile-label-wrap top' style='border-top-color:".$label_color.";'><div class='tile-label top' >".$label_text."</div></div>";
				}else{
					echo "<div class='tile-label-wrap bottom'><div class='tile-label bottom' style='border-bottom-color:".$label_color.";'>".$label_text."</div></div>";
				}
			}
			metro_close_tile_tag($url);
		}
	);

	
	$metro_tiles['tiles']['flip'] =
	array(
		'defaults' => array( /* Defaults*/
			'x'=>0,
			'y'=>0,
			'width'=>2,
			'height'=>1,
			'background'=>$metro_tiles['defaults']['bg'],
			'url'=>'',
			'new_tab'=>false,
			'front_text'=>'This is on front',
			'front_img'=>'',
			'front_img_size'=>50,
			'back_text'=>'This is on the backside',
			'back_img'=>'',
			'back_img_size'=>50,
			'direction'=>'horizontal',
			'label_text'=>'',
			'label_color'=>$metro_tiles['defaults']['label_color'],
			'label_position'=>$metro_tiles['defaults']['label_position'],
			'classes'=>array(),
			'anim_data'=>array('target_id'=>false, 'target_type'=>false, 'target_bg_color'=>false),
			'attr'=>array()
		),

		'admin' => array(/* Admin panel*/
			'background'=>array(__('background color', 'metro-template'),'color'),
			'url'=>array(__('url', 'metro-template'),'url'),
			'new_tab'=>array(__('open link in new tab', 'metro-template'),'checkbox'),
			'front_text'=>array(__('Text on front (leave blank for only a front image)', 'metro-template'), 'text'),
			'front_img'=>array(__("Image on front. If front text is not blank, it will show as a small image with size defined by the next parameter. If front text is empty it will fill the tile." , 'metro-template'), 'image'),
			'front_img_size'=> array(__('Size of front image. If front text is not blank, it will show as a small image with size defined by this parameter. If front text is empty it will just fill the tile.', 'metro-template'), 'int'),
			'back_text'=>array(__('Text on backside', 'metro-template'), 'text'),
			'back_img'=>array(__('Back image', 'metro-template'), 'image'),
			'back_img_size'=>array(__('Back image size', 'metro-template'), 'int'),
			'direction'=>array(__('Flip direction', 'metro-template'), 'select', array('horizontal'=>'horizontal', 'vertical'=> 'vertical')),
			'label_text'=>array(__('Label text (leave blank for no label)', 'metro-template'),'text'),
			'label_color'=>array(__('Label color', 'metro-template'),'color'),
			'label_position'=>array(__('Label position', 'metro-template'),'select',array('top'=>'top','bottom'=>'bottom')),
			'classes'=>array(__('extra CSS classes (optional)', 'metro-template') ,'array','text')
		),

		'function' => function($args){
			// Process arguments
			foreach($args as $key=>$arg){
				$$key = $arg;
			}

			/*Get animation */
			if($anim_data != false && !$new_tab){
				$animation_data = strip_tags(metro_get_animation_data($anim_data['target_id'], $anim_data['target_type'], $anim_data['target_bg_color'], $url));
			}else{
				$animation_data = '';
			}

			/*Dimensions */
			$margin_left = metro_margin_left($group, $x, $group_x, $scale_spacing);
			$margin_top = metro_margin_top($group,$y, $scale_spacing);
			$tile_width = $width*$scale_spacing-$spacing;
			$tile_height = $height*$scale_spacing-$spacing;
			?>

		  	<?php
			metro_open_tile_tag($url, $new_tab);
			?>
		  	class="tile tile-flip <?php echo $direction;?> support3D group<?php echo $group?> <?php echo join(' ',$classes);?>" 
		  	style="
		    margin-top:<?php echo $margin_top;?>px; 
		    margin-left:<?php echo $margin_left;?>px;
			width:<?php echo $tile_width?>px; 
			height:<?php echo $tile_height?>px;
			" 
			<?php echo metro_pos_val($x, $y, $width, $height); ?>
			<?php echo $animation_data; ?>
			<?php echo metro_get_attr_data($attr);?>
			>
		    <div class='flip-container'>
				<div class='flip-front' style="background:<?php echo $background;?>;">
			        <?php 
					if($label_text != ''){
						if($label_position == 'top'){
							echo "<div class='tile-label-wrap top' style='border-top-color:".$label_color.";'><div class='tile-label top' >".$label_text."</div></div>";
						}else{
							echo "<div class='tile-label-wrap bottom'><div class='tile-label bottom' style='border-bottom-color:".$label_color.";'>".$label_text."</div></div>";
						}
					}
					?>
			        <div class='flip-centerer'>
			        	<?php
			        	if($front_text != ''){
			        		if($front_img != ''){
			        			echo '<img class="flip-img-small" src="'.$front_img.'" width="'.$front_img_size.'" height="'.$front_img_size.'">';
			        		}
			        		echo '<h5>'.$front_text.'</h5>';
			        	}else{
			        		/* Image only*/
			        		echo '<img class="flip-img-large img-fill" src="'.$front_img.'">';
			        		?>
			        		<script>
			        		metroActions.add('init', function(){
								$('.tile.tile-flip').find('img.img-fill').metro_fill()
							})
							</script>
			        		<?php
			        	}
			        	?>
			        </div>
		        </div>
				<div class='flip-back' style="background:<?php echo $background;?>;">
					<div class='flip-centerer'>
			        	<?php
			        	if($back_text != ''){
			        		if($back_img != ''){
			        			echo '<img class="flip-img-small" src="'.$back_img.'" width="'.$back_img_size.'" height="'.$back_img_size.'">';
			        		}
			        		echo '<h5>'.$back_text.'</h5>';
			        	}else{
			        		/*Image only */
			        		echo '<img class="flip-img-large img-fill" src="'.$back_img.'">';
			        		?>
			        		<script>
							$('.tile.tile-flip').find('img.img-fill').metro_fill()
							</script>
			        		<?php
			        	}
			        	?>
			        </div>	
				</div>
		        
			</div>
			<?php
			metro_close_tile_tag($url);
		}
	);

	
	$metro_tiles['tiles']['image'] =
	array(
		'defaults' => array( /* Defaults*/
			'x'=>0,
			'y'=>0,
			'width'=>2,
			'height'=>1,
			'background'=>$metro_tiles['defaults']['bg'],
			'url'=>'',
			'new_tab'=>false,
			'img'=>'',
			'img_alt'=>'',
			'desc'=>'',
			'show_desc'=>true,
			'label_text'=>'',
			'label_color'=>$metro_tiles['defaults']['label_color'],
			'label_position'=>$metro_tiles['defaults']['label_position'],
			'classes'=>array(),
			'anim_data'=>array('target_id'=>false, 'target_type'=>false, 'target_bg_color'=>false),
			'attr'=>array()
		),

		'admin' => array(/* Admin panel*/
			'background'=>array(__('background color', 'metro-template'),'color'),
			'url'=>array(__('url', 'metro-template'),'url'),
			'new_tab'=>array(__('open link in new tab', 'metro-template'),'checkbox'),
			'img'=>array(__('image', 'metro-template'),'image'),
			'img_alt'=>array(__('alt text for image', 'metro-template'),'text'),
			'desc'=>array(__('Description text', 'metro-template'),'text'),
			'show_desc'=>array(__('Always show description (if unchchecked, the description is visible only when hovered)', 'metro-template'), 'checkbox'),
			'label_text'=>array(__('Label text (leave blank for no label)', 'metro-template'),'text'),
			'label_color'=>array(__('Label color', 'metro-template'),'color'),
			'label_position'=>array(__('Label position', 'metro-template'),'select',array('top'=>'top','bottom'=>'bottom')),
			'classes'=>array(__('extra CSS classes (optional)', 'metro-template') ,'array','text')
		),

		'function' => function($args){
			// Process arguments
			foreach($args as $key=>$arg){
				$$key = $arg;
			}

			/*Get animation */
			if($anim_data != false && !$new_tab){
				$animation_data = strip_tags(metro_get_animation_data($anim_data['target_id'], $anim_data['target_type'], $anim_data['target_bg_color'], $url));
			}else{
				$animation_data = '';
			}

			/*Dimensions */
			$margin_left = metro_margin_left($group, $x, $group_x, $scale_spacing);
			$margin_top = metro_margin_top($group,$y, $scale_spacing);
			$tile_width = $width*$scale_spacing-$spacing;
			$tile_height = $height*$scale_spacing-$spacing;

			/*Img processing */
			$hasImg = $img !== false && $img != '';
			?>

		  	<?php
			metro_open_tile_tag($url, $new_tab);
			?>
		  	class="tile tile-img group<?php echo $group?> <?php echo join(' ',$classes);?>" 
		  	style="
		    margin-top:<?php echo $margin_top;?>px; 
		    margin-left:<?php echo $margin_left;?>px;
			width:<?php echo $tile_width?>px; 
			height:<?php echo $tile_height?>px;
			background:<?php echo $background;?>;
			" 
			<?php echo metro_pos_val($x, $y, $width, $height); ?>
			<?php echo $animation_data; ?>
			<?php echo metro_get_attr_data($attr);?>
			>
			<img class="img-fill" src="<?php echo $img?>" alt="<?php echo $img_alt;?>"/>
			<script>
			metroActions.add('init', function(){
				$('.tile.tile-img').children('img.img-fill').metro_fill()
			})
			</script>
			<?php
			if($desc != ''){
				?>
				<div class="img-desc-wrap">
					<?php
				     if($label_text != '' && $label_position == 'bottom'){
						echo "<div class='tile-label-wrap bottom'><div class='tile-label bottom' style='border-bottom-color:".$label_color.";'>".$label_text."</div></div>";
					}
					?>
					<div class="img-desc <?php if($show_desc != 'on'){ echo 'toggle';}?>">
			    		<?php echo $desc; ?>
					</div>	
				</div>
				<?php
			}else{
				if($label_text != '' && $label_position == 'bottom'){
					echo "<div class='tile-label-wrap bottom'><div class='tile-label bottom' style='border-bottom-color:".$label_color.";'>".$label_text."</div></div>";
				}
			}?>
		   
		    <?php
		     if($label_text != '' && $label_position == 'top'){
				echo "<div class='tile-label-wrap top' style='border-top-color:".$label_color.";'><div class='tile-label top' >".$label_text."</div></div>";
			}
			?>
			<?php
			metro_close_tile_tag($url);
		}
	);


	$metro_tiles['tiles']['slide'] =
	array(
		'defaults' => array( /* Defaults*/
			'x'=>0,
			'y'=>0,
			'width'=>2,
			'height'=>1,
			'background'=>$metro_tiles['defaults']['bg'],
			'url'=>'',
			'new_tab'=>false,
			'img'=>'',
			'img_alt'=>'',
			'text'=>'text behind image',
			'direction'=>'left',
			'label_text'=>'',
			'label_color'=>$metro_tiles['defaults']['label_color'],
			'label_position'=>$metro_tiles['defaults']['label_position'],
			'do_slide_label'=>'on',
			'classes'=>array(),
			'anim_data'=>array('target_id'=>false, 'target_type'=>false, 'target_bg_color'=>false),
			'attr'=>array()
		),

		'admin' => array(/* Admin panel*/
			'background'=>array(__('background color', 'metro-template'),'color'),
			'url'=>array(__('url', 'metro-template'),'url'),
			'new_tab'=>array(__('open link in new tab', 'metro-template'),'checkbox'),
			'img'=>array(__('icon image (must be square size!)', 'metro-template'),'image'),
			'img_alt'=>array(__('alt text for image', 'metro-template'), 'text'),
			'text'=>array(__('the text behind the image', 'metro-template'),'text'),	
			'direction'=>array(__('Slide direction', 'metro-template'), 'select', array('top'=>'top','right'=>'right', 'bottom'=>'bottom', 'left'=>'left', 'fold'=>'fold left')),
			'label_text'=>array(__('Label text (leave blank for no label)', 'metro-template'),'text'),
			'label_color'=>array(__('Label color', 'metro-template'),'color'),
			'label_position'=>array(__('Label position', 'metro-template'),'select',array('top'=>'top','bottom'=>'bottom')),
			'do_slide_label'=>array(__('Slide label when hovered','metro-template'), 'checkbox'),
			'classes'=>array(__('extra CSS classes (optional)', 'metro-template') ,'array','text')
		),

		'function' => function($args){
			// Process arguments
			foreach($args as $key=>$arg){
				$$key = $arg;
			}

			/*Get animation */
			if($anim_data != false && !$new_tab){
				$animation_data = strip_tags(metro_get_animation_data($anim_data['target_id'], $anim_data['target_type'], $anim_data['target_bg_color'], $url));
			}else{
				$animation_data = '';
			}

			/*Dimensions */
			$margin_left = metro_margin_left($group, $x, $group_x, $scale_spacing);
			$margin_top = metro_margin_top($group,$y, $scale_spacing);
			$tile_width = $width*$scale_spacing-$spacing;
			$tile_height = $height*$scale_spacing-$spacing;
			?>

		  	<?php
			metro_open_tile_tag($url, $new_tab);
			?>
		  	class="tile tile-slide <?php echo $direction;?> group<?php echo $group?> <?php echo join(' ',$classes);?>" 
		  	style="
		    margin-top:<?php echo $margin_top;?>px; 
		    margin-left:<?php echo $margin_left;?>px;
			width:<?php echo $tile_width?>px; 
			height:<?php echo $tile_height?>px;
			background:<?php echo $background;?>;
			" 
			<?php echo metro_pos_val($x, $y, $width, $height); ?>
			<?php echo $animation_data; ?>
			<?php echo metro_get_attr_data($attr);?>
			>
		    <div class="slide-img-wrap">
				<img src='<?php echo $img;?>' class='img-fill' alt="<?php echo $img_alt?>"/> 
				<script>
				metroActions.add('init', function(){
					$('.tile.tile-slide').find('img.img-fill').metro_fill()
				})
				</script>
				<?php 
				if(!$do_slide_label){
					echo "</div>";
				}

			if($label_text != ''){
				if($label_position == 'top'){
					echo "<div class='tile-label-wrap top' style='border-top-color:".$label_color.";'><div class='tile-label top' >".$label_text."</div></div>";
				}else{
					echo "<div class='tile-label-wrap bottom'><div class='tile-label bottom' style='border-bottom-color:".$label_color.";'>".$label_text."</div></div>";
				}
			}

			if($do_slide_label){
				echo "</div>";
			}
			?> 
			<div class="img-desc-wrap">
				<h5 class='img-desc vertical-center '><?php echo $text; ?></h5>
			</div>
		    <?php
			metro_close_tile_tag($url);
		}
	);

	$metro_tiles['tiles']['html'] =
	array(
		'defaults' => array( /* Defaults*/
			'x'=>0,
			'y'=>0,
			'width'=>2,
			'height'=>1,
			'background'=>$metro_tiles['defaults']['bg'],
			'url'=>'',
			'new_tab'=>false,
			'html'=>'<h3>Put some html code here </h3>',
			'label_text'=>'',
			'label_color'=>$metro_tiles['defaults']['label_color'],
			'label_position'=>$metro_tiles['defaults']['label_position'],
			'classes'=>array(),
			'anim_data'=>array('target_id'=>false, 'target_type'=>false, 'target_bg_color'=>false),
			'attr'=>array()
		),

		'admin' => array(/* Admin panel*/
			'background'=>array(__('background color', 'metro-template'),'color'),
			'url'=>array(__('url', 'metro-template'),'url'),
			'new_tab'=>array(__('open link in new tab', 'metro-template'),'checkbox'),
			'html'=>array(__('HTML code', 'metro-template'),'text'),
			'label_text'=>array(__('Label text (leave blank for no label)', 'metro-template'),'text'),
			'label_color'=>array(__('Label color', 'metro-template'),'color'),
			'label_position'=>array(__('Label position', 'metro-template'),'select',array('top'=>'top','bottom'=>'bottom')),
			'classes'=>array(__('extra CSS classes (optional)', 'metro-template') ,'array','text')
		),

		'function' => function($args){
			// Process arguments
			foreach($args as $key=>$arg){
				$$key = $arg;
			}

			/*Get animation */
			if($anim_data != false && !$new_tab){
				$animation_data = strip_tags(metro_get_animation_data($anim_data['target_id'], $anim_data['target_type'], $anim_data['target_bg_color'], $url));
			}else{
				$animation_data = '';
			}

			/*Dimensions */
			$margin_left = metro_margin_left($group, $x, $group_x, $scale_spacing);
			$margin_top = metro_margin_top($group,$y, $scale_spacing);
			$tile_width = $width*$scale_spacing-$spacing;
			$tile_height = $height*$scale_spacing-$spacing;
			?>

		  	<?php
			metro_open_tile_tag($url, $new_tab);
			?>
		  	class="tile tile-simple group<?php echo $group?> <?php echo join(' ',$classes);?>" 
		  	style="
		    margin-top:<?php echo $margin_top;?>px; 
		    margin-left:<?php echo $margin_left;?>px;
			width:<?php echo $tile_width?>px; 
			height:<?php echo $tile_height?>px;
			background:<?php echo $background;?>;
			" 
			<?php echo metro_pos_val($x, $y, $width, $height); ?>
			<?php echo $animation_data; ?>
			<?php echo metro_get_attr_data($attr);?>
			>
		    <?php echo $html; ?>
		    <?php
		     if($label_text != ''){
				if($label_position == 'top'){
					echo "<div class='tile-label-wrap top' style='border-top-color:".$label_color.";'><div class='tile-label top' >".$label_text."</div></div>";
				}else{
					echo "<div class='tile-label-wrap bottom'><div class='tile-label bottom' style='border-bottom-color:".$label_color.";'>".$label_text."</div></div>";
				}
			}
			metro_close_tile_tag($url);
		}
	);





	










	// End
	return $metro_tiles;
})

?>

