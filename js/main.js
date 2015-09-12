mouseScroll = true
scrolling = false
mayHashchangeEvent = true

minTileHeight = 2

timers = { // save timers
	responsive: -1,
	scroll: -1,
	clear: function(name){
		if(isset(timers[name])){
			if($.isArray(timers[name])){
				for(var i in timers[name]){
					clearTimeout(timers[name][i])
				}
			}else{
				clearTimeout(timers[name])
			}
		}
		delete timers.slideshow
	} 
}

$(window).load(function() {
	
	// Detect overflow & fix by css if needed
	if(canOverflowScroll() === true){
		$('html').addClass('overflow')
	}else{
		$('html').addClass('no-overflow')
	}

	// Detect touch devcie

	if(isTouchDevice()){
		$('html').addClass('touch-device')
	}

	// Fix blurry safari font
	if($.browser.safari){
		$('html').css({'-webkit-text-stroke': '0.03px !important', '-webkit-font-smoothing': 'antialiased !important'})
	}

	metroActions.exec('loaded')

	init(true)
})

function init(first){
	if(!isset(first) || first != true){
		// Update google analytics
		if (typeof _gaq !== 'undefined' && _gaq !== null) {_gaq.push(['_trackPageview', document.location.href]); }
		
	}else{
		page.addCloseButton();
	}

	$('#site-title').data('orig-font-size', parseInt($('#site-title').css('font-size')))

	$(window).hashchange();
	responsive.timer(0);

	$('#metro-tile-wrapper').scroll(function(){
		scroll.timer();
	})

	tiles.show();
	metroActions.exec('init')
	metroActions.exec('newContent')

	setTimeout('$(window).hashchange();',10);
}

$(window).hashchange(function(){
	if(mayHashchangeEvent){
		if(page.isType('page-tiles') && page.isView('full')){ // the hash can determine the group scroll position		
			hash = window.location.hash.stripSpaces().toLowerCase().replace('#','');
			$('.group-title').each(function(){
				if($(this).text().stripSpaces().toLowerCase() == hash){
					scroling = true
					$('#metro-tile-wrapper').animate({scrollLeft: parseInt($(this).css('margin-left')) - 40 },200,function(){
					 	scrolling = false
					});
					return
				}
			})
		}
	}
})

page = {
	updateType: function(type){
		/* Sets page type to given type */
		$("[class^='type-']").removeAttr('class');
		$('#metro-wrapper').addClass('type-'+type)
	},

	updateTypeFromData: function(data){
		/* Sets page type from data */
		$("[class^='type-']").removeAttr('class');

		var classes = $(data).find('#metro-wrapper').attr('class');
		$('body').find('#metro-wrapper').addClass(classes)
	},

	isType : function(type, data){
		/* Checks if current loaded content is of the given type.
		Supported types are "page-tiles", "page", "single" */
		data = data || 'body'
		return $(data).find('#metro-wrapper').hasClass('type-'+type)
	},

	currentType: function(){
		/* Returns the current type of the page */
		if(!$('#metro-wrapper').is('[class]') /*|| $("#metro-wrapper").attr("class").match(/type-[\w-]*\b/).length != 1*/ ){
			return 'unkwown'
		}
		var classes = $('#metro-wrapper').attr('class').split(/\s+/);
		for(var i in classes){
			if(/^type-(.*)/i.test(classes[i])){
				var type = classes[i].match(/^type-(.*)/i)[1]
				return type
			}
		}
		return 'unknown'
	},

	isView: function(v){
		return $('body').hasClass(v)
	},

	addCloseButton : function(){
		var $metro_tile_sizer = $('#metro-tile-wrapper').find('#metro-tile-sizer')
		
		if($('main#metro-content').length>0 && $('main#metro-content').children('a#metro-content-close').length == 0 && $metro_tile_sizer.length>0){
			var url = $metro_tile_sizer.data('tiles-url')
			var targetId = $metro_tile_sizer.data('tiles-id')
			if(isset(url) && isset(targetId)){
				$('main#metro-content').prepend("<a id='metro-content-close'\
				href = '"+url+"' \
                data-target-type = 'page-tiles'\
                data-target-id='"+targetId+"' \
                title='"+php_data.ln_close_this_window+"'>X</a>")
			}
		}	
	}
}


/* Scroll things */
scroller = {
	timer:function(){
		clearTimeout(timers.scroll)
		timers.scroll = setTimeout('scroll.event()', 200);
	},
	event:function(){
		/* This is used to change the hash in the url to the current group while scrolling the tiles*/

		if(page.isType('page-tiles') && page.isView('full')){
			for(var group = $('.group-title').length-1; group >0 ; group--){
				$this = $('#group-title'+group)
				if($('#metro-tile-wrapper').scrollLeft()+50 > parseInt($this.css('margin-left'))){
					mayHashchangeEvent = false
					window.location.hash = $this.text().toLowerCase().stripSpaces();
					setTimeout('mayHashchangeEvent = true',10)
					return
				}
			}

			/*No match */
			mayHashchangeEvent = false
			window.location.hash = '';
			setTimeout('mayHashchangeEvent = true',10)
		}
	}
 }

/**
 * Does overflow:scroll work in this browser?
 * @return boolean false if not available.
 * @see http://filamentgroup.github.io/Overthrow
 */
 
function canOverflowScroll() {
    return (
        // Features-first. iOS5 overflow scrolling property check - no UA needed here. thanks Apple :)
        "WebkitOverflowScrolling" in window.document.documentElement.style ||
        // Touch events aren't supported and screen width is greater than X
        // ...basically, this is a loose "desktop browser" check. 
        // It may wrongly opt-in very large tablets with no touch support.
        window.screen.width > 1200 ||
        // Hang on to your hats.
        // Whitelist some popular, overflow-supporting mobile browsers for now and the future
        // These browsers are known to get overlow support right, but give us no way of detecting it.
        (function(){
        		var w = window
				var ua = window.navigator.userAgent,
					// Webkit crosses platforms, and the browsers on our list run at least version 534
					webkit = ua.match( /AppleWebKit\/([0-9]+)/ ),
					wkversion = webkit && webkit[1],
					wkLte534 = wkversion >= 534;
					
				return (
					/* Android 3+ with webkit gte 534
					~: Mozilla/5.0 (Linux; U; Android 3.0; en-us; Xoom Build/HRI39) AppleWebKit/534.13 (KHTML, like Gecko) Version/4.0 Safari/534.13 */
					ua.match( /Android ([0-9]+)/ ) && RegExp.$1 >= 3 && wkLte534 ||
					/* Blackberry 7+ with webkit gte 534
					~: Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en-US) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.0.0 Mobile Safari/534.11+ */
					ua.match( / Version\/([0-9]+)/ ) && RegExp.$1 >= 0 && window.blackberry && wkLte534 ||
					/* Blackberry Playbook with webkit gte 534
					~: Mozilla/5.0 (PlayBook; U; RIM Tablet OS 1.0.0; en-US) AppleWebKit/534.8+ (KHTML, like Gecko) Version/0.0.1 Safari/534.8+ */   
					ua.indexOf( "PlayBook" ) > -1 && wkLte534 && !ua.indexOf( "Android 2" ) === -1 ||
					/* Firefox Mobile (Fennec) 4 and up
					~: Mozilla/5.0 (Mobile; rv:15.0) Gecko/15.0 Firefox/15.0 */
					ua.match(/Firefox\/([0-9]+)/) && RegExp.$1 >= 4 ||
					/* WebOS 3 and up (TouchPad too)
					~: Mozilla/5.0 (hp-tablet; Linux; hpwOS/3.0.0; U; en-US) AppleWebKit/534.6 (KHTML, like Gecko) wOSBrowser/233.48 Safari/534.6 TouchPad/1.0 */
					ua.match( /wOSBrowser\/([0-9]+)/ ) && RegExp.$1 >= 233 && wkLte534 ||
					/* Nokia Browser N8
					~: Mozilla/5.0 (Symbian/3; Series60/5.2 NokiaN8-00/012.002; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/533.4 (KHTML, like Gecko) NokiaBrowser/7.3.0 Mobile Safari/533.4 3gpp-gba 
					~: Note: the N9 doesn't have native overflow with one-finger touch. wtf */
					ua.match( /NokiaBrowser\/([0-9\.]+)/ ) && parseFloat(RegExp.$1) === 7.3 && webkit && wkversion >= 533
				);
		})()
    )
}

/* Column nav button in header */
/*$(document).on('click','.main-nav.mini',function(){
	if($(this).find('.menu').children('li').css('display') == 'none'){
		$(this).find('.menu').children('li').css('display','block');
		setTimeout(function(){
			$(document).bind('click.closeNav',function(){
				$(this).find('.menu').children('li').css('display','none');
				$(document).unbind('click.closeNav');	
			})
		},1);
	}else{
		$(this).find('.menu').children('li').css('display','none');
		$(document).unbind('click.closeNav');	
	}
});*/
$(document).on("click",".main-nav.mini",function(){
	if(!$(this).hasClass("opened")) {
		$(this).addClass("opened");
		$("#metro-loader-overlay").css("background","").fadeIn(200);
		$("#metro-loader-wrapper").hide()
	}else{
		$(this).children('li').css("display","none")
		$(this).removeClass("opened")
		$("#metro-loader-overlay").fadeOut(200);
		
	}
})
$(document).on("click",".main-nav.mini .menu",function(){ // Counter prev function, hacky but works
	if(!$(this).parent().hasClass("opened")) {
		$(this).parent().addClass("opened");
	}else{
		$(this).parent().children('li').css("display","none")
		$(this).parent().removeClass("opened")
	}
	event.stopPropagation();
})
$(document).on("focus",".main-nav.mini input#s", function(){
	$(this).closest(".main-nav.mini").addClass("opened");
})
$(document).on("blur",".main-nav.mini input#s", function(){
	$(this).closest(".main-nav.mini").removeClass("opened");
	$("#metro-loader-overlay").fadeOut(200);
})


$(document).on("mouseenter",".main-nav .menu-item-has-children:not('#metro-nav-search-button')",function(){
	$(".sub-menu-wrap").removeClass("opened")
})
$(document).on("focus",".main-nav #metro-nav-search-button input#s", function(){
	$(this).closest(".sub-menu-wrap").addClass("opened");
})
$(document).on("blur",".main-nav #metro-nav-search-button input#s", function(){
	$(this).closest(".sub-menu-wrap").removeClass("opened");
	$("#metro-loader-overlay").fadeOut(200); 
})