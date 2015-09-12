$(window).resize(function(event){
	responsive.timer(400);
});

scrollSpeed = -20;

responsive = {
	scrollLeft: 0,
	timer:function(time){
		clearTimeout(timers.responsive)
		timers.responsive = setTimeout("responsive.event()", time); //timeout to enhance performance
	},

	event:function(){
		responsive.header()
		responsive.recalcTileSizer()
		rearrange.event()
		setTimeout("responsive.recalcTileSizer(); responsive.recalcMouseScroll();", 600); // wait for resize animation do be finished
	},

	recalcTileSizer:function(){
		if($("#metro-tile-sizer").length > 0){
			var mostRight = 0
			var mostDown = 0
			$("#metro-tile-sizer").children().each(function(){
				thisDown = parseInt($(this).css("margin-top")) + $(this).height()
				if(thisDown > mostDown){
					mostDown = thisDown
				}

				thisRight = parseInt($(this).css("margin-left")) + $(this).width()
				if(thisRight > mostRight){
					mostRight = thisRight
				}
			}).end().width(mostRight+2+8*$("body").hasClass("full")).height(mostDown+15)
			if($('html').hasClass('no-overflow')){
				$('#metro-tile-scroller').css('min-width', mostRight).css('min-height',mostDown+50)
			}
		}
	},

	recalcMouseScroll:function (){
		// Check mouse things
		if( $("#metro-tile-sizer").length > 0 && $("#metro-tile-sizer").height() <= $("#metro-wrapper").height() && page.isView("full") && page.isType("page-tiles")) {
			$(document).bind("mousewheel.scrollTiles", function(event, delta) { // Mouse scroll to move tilepages
				 if(mouseScroll == true){
				 	
					if(delta>0){ // scroll to left
						if(scrollSpeed < 0 && scrolling){
							scrollSpeed -= 10;
						}else{
							scrollSpeed = -40
							responsive.scrollLeft = $("#metro-tile-scroller").scrollLeft();
							$("#metro-tile-scroller").stop()
						}
						
					 					 	
					}else{ // scroll to right
						if(scrollSpeed > 0 && scrolling){
							scrollSpeed += 10;
						}else{
							scrollSpeed = 40
							responsive.scrollLeft = $("#metro-tile-scroller").scrollLeft();
							$("#metro-tile-scroller").stop()	
						}
					}

					scrolling = true
					scrollSpeed = Math.max(scrollSpeed, -100)
					responsive.scrollLeft += scrollSpeed
					responsive.scrollLeft = Math.min(responsive.scrollLeft, $("#metro-tile-sizer").width() + 30 - $(window).width())
					responsive.scrollLeft = Math.max(responsive.scrollLeft, 0)
					var duration = Math.min(500, 7*Math.pow(Math.abs(responsive.scrollLeft - $("#metro-tile-scroller").scrollLeft() ) , 0.7) )
				 	$("#metro-tile-scroller").stop().animate({scrollLeft:responsive.scrollLeft}, parseInt(duration), "easeOutCubic",function(){
				 		setTimeout(function(){scrolling = false},500);
				 	});
				}
				event.preventDefault();
			});
	    }else{
	    	$(document).unbind("mousewheel.scrollTiles")
	    }
	},

	header : function(){
		// Fix wp bar
		if($("#wpadminbar").length>0){
			$("#metro-pjax").css("top", $("#wpadminbar").height())
		}

		var $site_title = $("#site-title")
		if($site_title.length > 0){
			var $main_nav = $('header').children('.main-nav')
			var $site_desc = $('#site-desc')
			// First undo all the responsive changes
			$site_title.css({"display":"inline-block", "font-size": $site_title.data("orig-font-size")});
			
			if($main_nav.length > 0 && ($main_nav.css('display') == 'block' || $main_nav.css('display') == 'inline-block' )){
				$main_nav.removeClass("mini")

				// And apply them again if needed	
				//alert($site_desc.length > 0 && $site_desc.position().left + $site_desc.width() > $main_nav.position().left);

				if($site_desc.length > 0 && $site_desc.position().left + $site_desc.width() > $main_nav.position().left){
					$site_title.css("display","block") // places the site description under the main title

					if($site_desc.position().left + $site_desc.width() > $main_nav.position().left){
						// Still not enough...
						$main_nav.addClass("mini")
					}
				}
			}
			

			while( $site_title.height() > parseInt($site_title.css("font-size"))*1.5 && parseInt($site_title.css("font-size")) > 26){ // when title is wrapped, change font size
				$site_title.css("font-size", "-=10");
			}
			
		}
		$("#metro-wrapper").css("top",$("header").height())
		
	}
}

/*Responsive rearrange functions */
rearrange = {
	event:function(){
		var scale = rearrange.getScale();
	 	var spacing = rearrange.getSpacing();
	 	var scaleSpacing = scale + spacing;
		
		var maxRight =  Math.floor(($("#metro-tile-wrapper").width()-20)/scaleSpacing) // Max amount of width determined by current page width
		var minRight =  Math.floor(300/scaleSpacing) // The minimum amount of width
		var columnWidth = Math.floor((500) /scaleSpacing); // The 360 width is the trigger value, columnwidth gives the amount of tiles to have a 'column'
		
		/* Display column version if:
		-maxRight > columnWidth, which means the max amount of x positions is smaller than the threshold set by columnWidth
		-if it's a touch device and it's browser width (is not the same as device resolution!) is smaller than 700 px */
		if(maxRight>columnWidth && !$('html').hasClass('no-overflow') && ( !$('html').hasClass('touch-device') || $(window).width() > 700)){
			if($('#metro-wrapper').hasClass('page-index')){
				rearrange.horizontalIndex()
			}else{
				rearrange.horizontal()
			}			
		}else{
			rearrange.column(Math.max(maxRight, minRight));
		}
		rearrange.sidebar();
		
	},
	addUsedPos:function(usedPos, pos){
		/* Adds all occupied positions of the array to usedPos. Pos is an array (x, y, width, height) */

		for(var y = pos[1]; y < pos[1] + pos[3]; y++){
			for (var x = pos[0]; x < pos[0] + pos[2]; x++){
				usedPos[y][x] = true
			}
		}
		return usedPos
	},
		
	findFreePos:function(maxDown, width, height, usedPos){
		/* Returns the position as array[ x, y] of a suited open position */
		var x = 0
		while(x<50){
			for(var y = 0; y<=maxDown-height;y++){
				if(rearrange.isOpen(x,y, x+width, y+height, usedPos)){
					return [x,y, width, height]
				}
			}
			x ++
		}
		return false
	},

	sidebar: function(){
		if($(window).width() < 600){
			// Place sidebar under content
			$('#metro-content').find("aside#sidebar, article.post").css('display', 'block')
		}else{
			// PLace sidebar on side of content
			$('#metro-content').find("aside#sidebar, article.post").css('display', 'table-cell')
		}
	},

	isOpen: function (xstart, ystart, xend, yend, usedPos){
		/* Checks if all positions in the rectangle defined by the arguments in the arrray usedPos are open */
		for(var y = ystart; y<yend; y++){
			for(var x= xstart; x<xend; x++){
				if(isset(usedPos[y][x]) && usedPos[y][x] != false){
					return false
				}
			}
		}
		return true
	},

	horizontal:function(){
		var scale = rearrange.getScale();
	 	var spacing = rearrange.getSpacing();
	 	var scaleSpacing = scale + spacing;
		
		$("body").removeClass("column").addClass("full")
	 	/* Changes the position of the tile so it fits the screen height */
		var maxDown = Math.max(minTileHeight, Math.floor(($("#metro-wrapper").height() - $(".group-title").height() - parseInt($('#metro-tile-wrapper').css('top'))) / scaleSpacing)); // THE 55 ...
			
		//Loop all groups
		var mostRight = 0
		for(var group=0;group<$('#metro-tile-sizer').children(".group-title").length;group++){
			// Create 2dim array  WARNING: Y - X
			if(group>0){
				mostRight += $('#metro-tile-sizer').children("#group-title-"+group).data("margin-left")
			}
			
			var mostRightPrev = mostRight

			$('#metro-tile-sizer').children("#group-title-"+group).css({"margin-left": mostRightPrev * scaleSpacing, "margin-top":0})

			var usedPos = new Array(maxDown);
			for (var i = 0; i < maxDown; i++) { usedPos[i] = new Array() }

	  		//Add the positions of tiles that are on the right place
			$("#metro-tile-sizer").children(".group"+group).each(function(){
				pos = $(this).data("pos")// 4 elements in array: x, y, width, height
				thisDown = pos[1] + pos[3] 
				if(thisDown <= maxDown){					
					usedPos = rearrange.addUsedPos(usedPos, pos)
					$(this).css({"margin-left": mostRightPrev * scaleSpacing + pos[0]*scaleSpacing,
								 "margin-top": 60 + pos[1]*scaleSpacing,
								 "width": (pos[2]-1)*scaleSpacing + scale,
								 "height":(pos[3]-1)*scaleSpacing + scale})

					if( mostRightPrev + pos[0] + pos[2] > mostRight){ // update mostRight, needed to correctly place groups
						mostRight =  mostRightPrev + pos[0] + pos[2]
					}
				} 

			})

			// Fix all wrong tiles
			$("#metro-tile-sizer").children(".group"+group).each(function(){
				pos = $(this).data("pos") // 4 elements in array: x, y, width, height
				thisDown = pos[1] + pos[3] 
				if(pos[3] > maxDown){ //if height is greater than max height, resize, if enabled..	
					pos[3] = maxDown
				}
				if(thisDown > maxDown){
					var freePos = rearrange.findFreePos(maxDown, pos[2], pos[3], usedPos)
					if(freePos != false){
						$(this).css({"margin-left": mostRightPrev * scaleSpacing + freePos[0]*scaleSpacing,
									"margin-top": 60 + freePos[1]*scaleSpacing,
									"width": (pos[2]-1)*scaleSpacing + scale,
								 	"height":(pos[3]-1)*scaleSpacing + scale}) // set position of tile
							   //.data("pos-curr", [freePos[0], freePos[1], pos[2], pos[3]]) // set data of current position
						usedPos = rearrange.addUsedPos(usedPos, freePos)

						if( mostRightPrev + freePos[0] + pos[2] > mostRight){ // update mostRight, needed to correctly place groups
							mostRight =  mostRightPrev + freePos[0] + pos[2]
						}
					}
				}
			})
		}
	},
	horizontalIndex:function(){
		var scale = rearrange.getScale();
	 	var spacing = rearrange.getSpacing();
	 	var scaleSpacing = scale + spacing;
		$("body").removeClass("column").addClass("full")
	 	/* Changes the position of the tile so it fits the screen height */
		var maxDown = Math.max(7, Math.floor(($("#metro-wrapper").height() - $('#metro-tile-sizer').children(".group-title").height() - parseInt($('#metro-tile-wrapper').css('top')) -60) / scaleSpacing)-2); // THE 55 ...
		
		//Loop all groups
		for(var group=0;group<$('#metro-tile-sizer').children(".group-title").length;group++){
			var $postTile = $("#metro-tile-sizer").children(".tile.post.group"+group)
			
			// Update title
			var $groupTitle = $('#group-title-'+group)
			$groupTitle.css({
				'margin-top':0,
				'margin-left':$groupTitle.data('x')*scaleSpacing
			})

			
			// Update other tiles
			$('#metro-tile-sizer').children('.tile.group'+group).each(function(){
				var pos = $(this).data('pos')
				var x = parseInt(pos[0]), y = parseInt(pos[1]), width = parseInt(pos[2]), height = parseInt(pos[3])
				$(this).css({"margin-left": $groupTitle.data('x') * scaleSpacing + x*scaleSpacing,
								"margin-top": 60 + y*scaleSpacing,
								"width": width*scaleSpacing - spacing,
								"height":  height * scaleSpacing - spacing })
			})

			// Update post tile
			if($postTile.length >0){
				var pos = $postTile.data('pos')
				var x = parseInt(pos[0]), y = parseInt(pos[1]), width = parseInt(pos[2]), height = parseInt(pos[3])
				var thisDown =  y+ height
				if(thisDown +1 > maxDown){
					var thisHeight =  (height - (thisDown + 1 - maxDown))
					var thisDown = y + thisHeight
					$postTile.css("height",  (thisHeight) * scaleSpacing - spacing )
				}else{
					var thisDown = y + height
					$postTile.height((height)*scaleSpacing - spacing)
				}

				// Update tiles under post tile */
				$('.tile.simple.group'+group).each(function(){
					var pos = $(this).data('pos')
					var x = parseInt(pos[0]), y = parseInt(pos[1]), width = parseInt(pos[2]), height = parseInt(pos[3])

					$(this).css({"margin-top": 60 + thisDown*scaleSpacing})
				})

			}


		}
	},
	column:function(columnWidth){
	 	/* Changes the position of the tile so it fits the screen height */
	 	var scale = rearrange.getScale();
	 	var spacing = rearrange.getSpacing();
	 	var scaleSpacing = scale + spacing;
		
	 	//page.view = "column"
	 	$("body").removeClass("full").addClass("column")

		//Loop all groups
		var mostDown = -0.5-50/scaleSpacing
		var maxWidth = $('#metro-tile-scroller').width()-15*($('#metro-tile-sizer').height()>$('#metro-tile-scroller').height());
		for(var group=0;group<$('#metro-tile-sizer').children(".group-title").length;group++){

			mostDown += 0.5+50/scaleSpacing
			var mostDownPrev = mostDown

			$('#metro-tile-sizer').children("#group-title-"+group).css({"margin-left":0, "margin-top": mostDownPrev * scaleSpacing})
			
			// Create 2dim array  WARNING: X - Y indexes
			var usedPos = new Array(2);
			for (var i = 0; i < columnWidth; i++) { usedPos[i] = new Array() }

	  		//Just add tiles from first to last in order
			$("#metro-tile-sizer").children(".group"+group).each(function(){
				var pos =  $(this).data("pos")
				var x = pos[0], y = pos[1], width = pos[2], height = pos[3]
				if(width > columnWidth){ // If width is greater than 2, set it to 2
					 width = columnWidth
				}
				
				var freePos = rearrange.findFreePos(columnWidth, height, width, usedPos) // we can use findFreePos, but remind everything is turend 90 degrees (since findFreePos is for x y)
				if(freePos != false){
					var new_x = freePos[1], new_y = freePos[0]
					usedPos = rearrange.addUsedPos(usedPos, [new_y,new_x , height, width])
					$(this).css({"margin-left": new_x*scaleSpacing,
								 "margin-top":   70 + mostDownPrev*scaleSpacing + new_y*scaleSpacing,
									"width": Math.min(maxWidth, (width-1)*scaleSpacing + scale),
								 	"height":(height-1)*scaleSpacing + scale}) // set position of tile
							   //.data("pos-curr", [freePos[0], freePos[1], pos[2], pos[3]]) // set data of current position

					if( mostDownPrev + new_y + height > mostDown){ // update mostRight, needed to correctly place groups
						mostDown =  mostDownPrev + new_y + height
					}
				}
			})
		}
	},

	getScale: function(){
		var scale = $("#metro-tile-sizer").data("scale");
		return (scale != undefined) ? scale : 140;
	},
	getSpacing: function(){
		var spacing = $("#metro-tile-sizer").data("spacing");
		return (spacing != undefined) ? spacing : 10;
	}
}