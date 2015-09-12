tiles = {
	show : function(){
		if(!tiles.isLoaded()){
			return
		}
		metroActions.exec('beforeTilesShow')
		responsive.timer(0);

		transitions.hideLoader()

		$(".group-title, #metro-tiles-title").stop().fadeIn(200)

		if(tiles.isVisible()){ // if tiles are already visible, no advanced anim
			$(".tile").css({"display":"block","opacity":1})
		}else{
			$("#metro-tile-sizer").addClass("loading").stop().css("padding-left",30).transition({"padding-left":0}, 800, "ease-out");
			
			var prev_margin_left = false;
			var delay = 0
			
			$(".tile")
			.css({"display":"block","opacity":0})
			.each(function(index) {
				// Wave effect
				this_margin_left = parseInt($(this).css("margin-left"))
				if(prev_margin_left == this_margin_left){ // same column...
					delay += 20
				}else{
					delay += 70
				}
				
				$(this)
					.delay(delay)
					.css({ transformOrigin: '70% 80%' })
					.css({"scale":0.95})
					.transition({"opacity":1,"scale":1},400, function(){
/*						$(this).css({'scale':'standard'})*/
					})

				prev_margin_left = this_margin_left
			})
			setTimeout(function(){
				$("#metro-tile-sizer").removeClass("loading")
			}, Math.max(800, delay))

			setTimeout(function(){
				responsive.timer(0)
				metroActions.exec('afterTilesShow')
			}, delay+100)
		}

	},

	isLoaded: function(){
		return $("#metro-tile-wrapper").find(".tile").length > 0
	},

	isVisible: function(){
		$tile = $(".tile")
		return $tile.css("display") == "block" && $tile.css("opacity") > 0 
	},

	isBusy: function(){
		return $("#metro-tile-sizer").hasClass("loading")
	},

	loadedID: function(){

	},

	hide : function(){
		metroActions.exec('beforeTileHide')

		sw = $("#metro-tile-wrapper").scrollLeft()+$(window).width()/2
		sh = $(window).height()/2
		$("#metro-tiles-title").fadeOut(200)
		$("#metro-tile-sizer")
		.addClass("loading")
		.css({ transformOrigin: sw+"px "+sh+"px " })
		.stop().transition({"scale":0.8,"opacity":0}, 400, function(){
			$(this).find(".tile, .group-title").css({"display":"none"})
			$(this).css({"scale":1,"opacity":1})

			$(this).removeClass("loading")
			metroActions.exec('afterTilesHide')
		})
	}

}