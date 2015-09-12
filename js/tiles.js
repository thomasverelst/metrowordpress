/* SLIDESHOW TILE */
metroActions.add('init', function(){
	tileSlideshow.init();
	$('.tile.tile-slideshow').find('img.img-fill').metro_fill()
})
tileSlideshow = {
	init: function(){
		timers.clear('slideshow')
		timers.slideshow = []

		timers.slideshow = setTimeout(function(){
			timers.clear('slideshow')
			timers.slideshow = []
			
			$('.tile.tile-slideshow').each(function(){
				$this = $(this)

				// Preload images
				$imgs = $this.data('imgs')
				for(var i in $imgs){
					var image = $('<img />').attr('src', $imgs[i]);
				}

				// Set timers
				var timer = setTimeout(function(){tileSlideshow.nextSlideshow($this)}, $this.data('speed') )
				$this.data('timer', timer)
				timers.slideshow[timers.slideshow.length] = timer
			})
		}, 100)
		
	},

	nextSlideshow: function($obj, dir, speed_factor){
		if(!isset($obj) || $obj.length == 0){
			return false
		}

		dir = dir || 1 // Direction of animation, -1 is reverse
		speed_factor = isset(speed_factor) ? speed_factor : 1 // Multiplies the speed with this
		

		if(page.isType('page-tiles') || $obj.parent().attr('id') == 'sidebar'){

			var imgs = $obj.data('imgs')
			if(!isset(imgs)){
				return false
			}
			var imgAlts = $obj.data('img_alts')
			var imgDescs = $obj.data('img_descs')

			var currImg = $obj.data('curr_img')
			if(!isset(currImg)){
				currImg = 0;
			}
			var nextImg = currImg+dir
			if(nextImg >= imgs.length){
				nextImg = 0
			}
			if(nextImg < 0){
				nextImg = imgs.length-1
			}
			
			var effect = $obj.data('effect')

			$obj.children('.slideshow-img-wrap').children('img').attr('src',imgs[currImg])
			$obj.children('.slideshow-img-wrap-back').children('img').attr('src',imgs[currImg])

			var nextAlt = (imgs.length == imgAlts.length) ? imgAlts[nextImg] : '';
			var nextDesc = (imgs.length == imgDescs.length) ? imgDescs[nextImg] : (imgDescs.length == 1) ? imgDescs[0] : false;

			var $wrap = $obj.children('.slideshow-img-wrap')
			var $img = $wrap.children('img')
			var $wrapBack = $obj.children('.slideshow-img-wrap-back')
			var $imgBack = $wrapBack.children('img')

			switch(effect){
				case "slide-horizontal-alternate":
					if(isset($obj.data('slide-side')) && $obj.data('slide-side') != 0){
						dir = -1
						$obj.data('slide-side', 0)
					}else{
						$obj.data('slide-side', 1)
					}
				case 'slide-left':
					dir = -dir;
				case 'slide-right':
					$imgBack.attr('src',imgs[nextImg])
					$wrapBack.stop().css('x',-dir*100+'%').css('y',0).show().transition({x:0},1200*speed_factor,'easeOutCubic');
					$imgBack.metro_fill();
					$wrap.stop().css('y',0).css('x', 0).show().transition({x:dir*100+'%'}, 1200*speed_factor, 'easeOutCubic', function(){
						$img.attr('src', imgs[nextImg]).metro_fill()
						$wrap.css('x',0)
						$wrapBack.hide();
					});
				break;


				case "slide-vertical-alternate":
					if(isset($obj.data('slide-side')) && $obj.data('slide-side') != 0){
						dir = -1
						$obj.data('slide-side', 0)
					}else{
						$obj.data('slide-side', 1)
					}
				case 'slide-up':
					dir = -dir;
				case 'slide-down':
					$imgBack.attr('src',imgs[nextImg])
					$wrapBack.stop().css('y',-dir*100+'%').css('x',0).show().transition({y:0},1200*speed_factor,'easeOutCubic');
					$imgBack.metro_fill();
					$wrap.stop().css('y',0).css('x', 0).show().transition({y:dir*100+'%'}, 1200*speed_factor, 'easeOutCubic', function(){
						$img.attr('src', imgs[nextImg]).metro_fill()
						$wrap.css('y',0)
						$wrapBack.hide();
					});
				break;

				case 'fade':
				default:
					$wrapBack.show()
					$imgBack.attr('src', imgs[nextImg]).metro_fill()
					$wrap.stop().fadeOut(500*speed_factor, function(){
						$img.attr('src', imgs[nextImg]).metro_fill()
						$img.attr('alt', nextAlt)
						$wrap.stop().show();
						$wrapBack.stop().hide();
					})
				break;
			}

			if(imgDescs.length > 1 && nextDesc != false){
				$obj.find('.slideshow-img-desc').stop().slideUp(500*speed_factor, function(){
					if($.trim(nextDesc) != ''){
						$obj.find('.slideshow-img-desc').stop().html(nextDesc).slideDown(500*speed_factor)
					}
				})
				
			}

			/* Do transition */
			$obj.data('curr_img', nextImg)
		}

		if(!isset(timers.slideshow)){
			timers.slideshow = []
		}
		var timer = setTimeout(function(){tileSlideshow.nextSlideshow($obj)}, $obj.data('speed') )
		$obj.data('timer', timer)
		timers.slideshow[timers.slideshow.length] = timer
	}
}
$(document).on('click', '.slideshow-arrow-left', function(){
	var $obj = $(this).closest('.tile.tile-slideshow')
	var timer = $obj.data('timer')
	if(isset(timer)){
		clearTimeout(timer)
	}

	tileSlideshow.nextSlideshow($obj, -1, 0);
})
$(document).on('click', '.slideshow-arrow-right', function(){
	var $obj = $(this).closest('.tile.tile-slideshow')
	var timer = $obj.data('timer')
	if(isset(timer)){
		clearTimeout(timer)
	}
	tileSlideshow.nextSlideshow($obj, 1, 0);
})


/* IMG TILE */
$(document).on('mouseenter', '.tile.tile-img', function(){
	$(this).find('.img-desc.toggle').hide().slideDown(200)
})
$(document).on('mouseleave', '.tile.tile-img', function(){
	$(this).find('.img-desc.toggle').show().slideUp(200)
})


/* TILE FLIP */
$(document).on("mouseenter",".tile.tile-flip",function(){
	$this = $(this)
	$this.addClass("hovered");
	setTimeout(function(){
		$this.addClass("hovered2");
	},1)
	clearTimeout(timers[$(this)]);

}).on("mouseleave",".tile.tile-flip",function(){
	$(this).removeClass('hovered2')
	timers[$(this)] = setTimeout(function(){$(".tile.tile-flip.hovered").removeClass("hovered")},401);
	
})


/* SLIDE FX */
if(($.browser.name=="msie"  || $.browser.name == 'unknown')){ // if notIE
	$(document).on("mouseenter",".tile.tile-slide.fold",function(){
		$(this).addClass('left').removeClass('fold')
	})
}else{
	$(document).on("mouseenter",".tile.tile-slide.fold",function(){
		$this = $(this)

		// Check if tile is already processed, otherwise do it now
		if($this.children('.slide-img-wrap').length > 0){
			var itemwidth = $this.width(),
				img		= $this.find('img').attr( 'src' ),
				struct	= '<div class="slice s1">\
				<div class="slice s2"  style="background-position: -'+itemwidth*0.25+'px 0px;">\
				<div class="slice s3" style="background-position: -'+itemwidth*0.5+'px 0px;">\
				<div class="slice s4" style="background-position:-'+itemwidth*0.75+'px 0px;">\
				</div></div></div></div>';
			$this.children('.slide-img-wrap').remove().end()
				.append(struct)
				.find('.slice').css("width", itemwidth*0.25).css( 'background-image','url('+img+')')
				.prepend('<span class="overlay" style="width:'+itemwidth*0.25+'px;"></span>')
		}

		// Add hovered classes
		$this.addClass("hovered");
		setTimeout(function(){
			$this.addClass("hovered2");
		},1)
		
		clearTimeout(timers[$(this)]);
	}).on("mouseleave",".tile.tile-slide.fold",function(){

		// Remove hovered classes
		$(this).removeClass('hovered2')
		timers[$(this)] = setTimeout(function(){$(".tile.tile-slide.fold").removeClass("hovered")},201);
	})
	
}

/*GENERAL IMAGE FITTING */

/*metroActions.add('newContent', function(){
	$('img.img-fill').each(function(){
		var imgClass = (this.width/this.height > 1) ? 'wide' : 'tall';
  		$(this).addClass(imgClass);
 	})
})
*/

/* FLIP TILE */