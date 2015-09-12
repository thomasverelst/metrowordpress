
metroActions.add('loaded', function(){
		// if it's the wp theme customizer, turn off reloading the wp admin ba
		if($('body', window.parent.document).length > 0){
			if($('body', window.parent.document).hasClass("wp-customizer")){
				transitions.adminbar = false
			}
		}

		// turn off animations for old browsers
		if($.browser.name == 'msie' && $.browser.version < 9 || $('html').hasClass('no-overflow') || (php_data.transitions != '1' && php_data.transitions != 'on')){
				transitions.on = false
		};

		if(transitions.on){ // fast enable/disable
		 	if (typeof history.pushState != "undefined") {
		   		//var historyCount = 0;


		   		setTimeout(function(){ // catch onpostate after page is loaded (!)
				    history.replaceState({targetType:page.currentType(), targetID:transitions.currentTilesID()}, document.title, document.location.href);

				    window.onpopstate = function(event){
				    	if(!isset(event.state) || event.state == null || typeof event.state.targetType == "undefined"){ // no transtition because there's something wrong
				    		transitions.displayLoader();
				    		document.location = document.location
				    		return false
				    		
				    	}else{
					    	//if(!page.first){
					    		transitions.goTo(document.location, event.state.targetID, event.state.targetType, event.state.targetBgColor, false )
					    	//}
					      //  if(historyCount>0) {
					        	
					      //  }
					       // historyCount++;
					    }
				    };
				},300)
				$(document).on("click","a[href^='"+php_data.site_url+"']",function(){
					if(transitions.on){
						if($(this).parent().parent().hasClass("metro-anim-ul")){
							$(this).attr({"data-target-type":"post",
										  "data-target-bg-color":"",
										  "data-target-id":"-1"})
						}
						if(!$(this).hasClass("notransition") && ( $(this).is("[data-target-type]") || $(this).parent().parent().hasClass("metro-anim-ul"))){
							transitions.goTo($(this).attr("href"), $(this).data("target-id"), $(this).attr("data-target-type"), $(this).data("target-bg-color"),true);
							return false;
						}else {

							var target = $(this).attr("target")
							if (!isset(target) || (isset(target) && target != "_blank" && target != "blank")) {
							    transitions.displayLoader();
							}
						}	
					}

				});
			}
		}
});

transitions = {
	on: true,
	adminbar: true,

	goTo: function(href, targetID, targetType, targetBgColor, push){

		if(transitions.isLoading()){
    		transitions.displayLoader();
    		document.location = document.location
    		return false
    	}


		// fix some common equivalent types
		switch(targetType){ 
			case "post":
			case "single":
			var targetTypeRel = "page"
			break
			case 'page-tiles':
			case "tiles":
			case 'index':
			var targetTypeRel = "page-tiles"
			break
			default:
			var targetTypeRel = targetType
			break
		}

		var currentType = page.currentType()
		switch(currentType){ 
			case "post":
			case "single":
			currentType = "page"
			break
			case 'page-tiles':
			case 'tiles':
			case 'index':
			currentType = "page-tiles"
			break
		}	
	
		var transition = transitions[ (currentType + "_to_" + targetTypeRel).replace(/-/g,"_")];
		
		// if no such transition exists, load page normally
		if(! isset(transition) || $("#metro-content").hasClass("loading")){ 
			console.log('page still loading or transition not found:'+(currentType + "_to_" + targetTypeRel).replace(/-/g,"_"))
			transitions.displayLoader();
			document.location.href = href
			return false
		}



		//execute transition
		transition(href, targetID, targetType, targetBgColor, push)

		//prevent default event
		return false
	},


	/*Define some extra functions */
	pjaxified : function(data){
		/* Checks if page has pjax wrapper */
		return $(data).filter("#metro-pjax").length > 0
	},

	currentTilesID: function (){
		/* Returns the ID of the page of the current loaded tiles, and -1 if no tiles are loaded */
		return (elementExists("#metro-tile-sizer")) ? $("#metro-tile-sizer").data("tiles-id") : -1
	},

	updateHeader: function (data){
		/* Updates the header and title and footer with new contents and returns title of current page */

		/* Add stylesheets on the fly */
		$(data).filter('link[rel="stylesheet"]').each(function(){
			var href= $(this).attr("href")
			if (!$('link[href="'+href+'"]').length){
  				$('<link href="'+href+'" rel="stylesheet">').appendTo("head");
			}
		})
		$(data).filter('style').each(function(){
			$("head").append($(this))
		})
		
		var title = $(data).filter("title").html();
		document.title = title

		var header = $(data).filter("#metro-pjax").children("header#metro-header").html();
		var footer = $(data).filter("#metro-pjax").children("footer#metro-footer").html();
		
		$("footer").html(footer)
		$("header").html(header) 
		responsive.timer(0)

		if(!transitions.adminbar){
			$("#wpadminbar").remove()
		}		

		return title

	},

	getBg: function(c){
		return (isset(c)) ? c : ''
	},

	scrollToHash: function(href){

		if(!isset(href) || typeof href != 'string'){
			return
		}
		if(href.indexOf("#")<0){
			return
		}
		parts = href.split("#")
		if(parts.length > 1) {
     		var hash = parts[1]
     		if($("#"+hash).length > 0 ){
     			$("#metro-content-wrapper").animate({"scrollTop": $("#"+hash).position().top }, 200)
     		}
		}
	},

	replaceState: function(href, title, targetType, targetID, targetBgColor){
		history.replaceState({targetType:targetType, targetID: targetID, targetBgColor:targetBgColor}, title, href);
	},

	setLoading : function(){
		$("body").addClass("transition-loading")
	},

	unsetLoading : function(){
		$("body").removeClass("transition-loading")
	},

	isLoading : function(){
		return $("body").hasClass("transition-loading")
	},

	/* All the transitions */
	page_tiles_to_page: function(href, targetID, targetType, targetBgColor, push){
		/* 
		Fade in content parts
		If background tiles of page are the same as the loaded tiles, keep tiles
		Otherwise load in background tiles of page
		*/
		transitions.setLoading();

		//add history entry so users can press "back" when post has not loaded yet
		if(push){
			history.pushState({targetType:"page", targetID:targetID}, "Loading...", href);
		}
		page.updateType("page")


		$("#metro-content-overlay").fadeIn(300);
		var $metro_content_wrapper = $("#metro-content-wrapper")
		var $metro_content = $metro_content_wrapper.children("#metro-content") //! the element will be removed and reinitated after content loaded!
		$metro_content.addClass("loading")
		$metro_content_wrapper			
			.css({rotateY:'-120deg', scale: 0.8, background: transitions.getBg(targetBgColor)})
			.transition({rotateY:'0deg', scale: 1},500, 'ease', function(){
				$metro_content_wrapper.css('transform','none')
				// only show loading image if ajax content is not loaded yet
				$metro_content
				.html('<img width="24" height="24" id="metro-content-loader" src="'+php_data.template_dir+'/img/icons/light/loader.gif"/>')
				.removeClass('loading')
				.fadeIn(200)
			})

		jQuery.ajax({
			url:href,
			dataType:"html",
			success: function(data){
				/*Ok, weve got the content, now extract the things we need out of it */
				data = $.parseHTML(data);

				if(!transitions.pjaxified(data) ){ // this page is not pjaxified
					document.location.href = href;
					return false;
				}

				if(! page.isType("page", data) && 
				   ! page.isType("single", data) ){ // not what we expected...
					console.log("Page type not as expected (type-page or type-single)")
					document.location.href = href;
					return false;	
				}				
				
				// wait until transition is done
				waiter = function(){
					if($metro_content.hasClass("loading")){
						setTimeout("waiter()", 50)
					}else{
						var body = $(data).find("#metro-content-wrapper").html();
						var receivedBgColor = $(data).find("#metro-content-wrapper").css("background-color")
						var title = transitions.updateHeader(data)
						page.updateTypeFromData(data)
						
						$metro_content.removeClass("loading").stop().fadeOut(200, function(){
							$metro_content_wrapper.html(body)
							$('#metro-content').stop().hide().fadeIn(200)
							transitions.scrollToHash(href)

							transitions.unsetLoading();
							init();
							page.addCloseButton()
						})

						if(receivedBgColor != targetBgColor){
							$metro_content_wrapper.css("background",receivedBgColor)
						}
						

						if(push){
							transitions.replaceState(href, title, "page", targetID);
						}
					}
				}
				waiter()
				
			},
			error: function(data){
				document.location.href = href;
				return false;
			}
		})
	},

	page_to_page_tiles :function(href, targetID, targetType, targetBgColor, push){
		transitions.setLoading();

		if(push){
			history.pushState({targetType:"page-tiles"}, "Loading...", href);
		}

		$("#metro-content-wrapper")
/*			.transition({height:"50%","max-width":"40%"},400, 'ease')
			.children('#metro-content').addClass('loading').fadeOut(400, function(){
				$(this).removeClass('loading')
			})/*/
			.css({scale:1, opacity:1})
			.transition({scale:0.5, opacity:0.5},400, 'ease', function(){
				$(this).children('#metro-content').removeClass('loading')
			})
			.children('#metro-content').addClass('loading')
		//responsive.timer(0) // to fix scrolling
		var alreadyLoaded = (isset(targetID) && targetID == transitions.currentTilesID()) 
		if(!alreadyLoaded){
			$("#metro-tile-wrapper").fadeOut(350)
		}

		$("#metro-content-overlay").stop().fadeOut(400, function(){
			if(alreadyLoaded){ // if current background is the requested tilepage, no need to get ajax contents (but we still need title from ajax...)
				transitions.hideLoader()
				$(this).children("#metro-content-wrapper").empty().append("<div id='metro-content'></div>");
				page.updateType(targetType)
				transitions.unsetLoading() // because otherwise annoying
				
				responsive.timer(0); // Fix scrolling 

				$('#metro-content-wrapper').css({scale:1, opacity:1})
				
			}else{
				transitions.displayLoader(false, false);
				
			}
		});
		
		jQuery.ajax({
			url:href,
			dataType:"html",
			success: function(data){
				transitions.hideLoader()
				/* weve got the content, now extract the things we need out of it */
				data = $.parseHTML(data);

				if( !transitions.pjaxified(data)  ){ // this page is not pjaxified
					document.location.href = href;
					return false;
				}

				if(!page.isType("page-tiles",data)){ // not what we expected...
					console.log("Page type not as expected (type-page-tiles)")
					document.location.href = href;
					return false;	
				}

				waiter = function(){
					if($('#metro-content').hasClass('loading')){
						setTimeout("waiter()", 50)
					}else{
						var body = $(data).find("#metro-tile-wrapper").html()
						var title = transitions.updateHeader(data)
						page.updateTypeFromData(data)

						if(isset(targetID) &&  targetID == transitions.currentTilesID()){
							// the tiles we wanted to load are already loaded, so do nothing
							//$("#metro-tile-wrapper").html(body);
					 	}else{
							$("#metro-tile-wrapper").html(body).fadeIn(300);
/*							if(transitions.currentTilesID() != $(data).find("#metro-tile-sizer").data("tiles-id")){ 
								// not the same tile page, so make a transition
								var body = $(data).find("#metro-tile-wrapper").html()
								$("#metro-tile-wrapper").html(body).fadeIn(300);
							}*/
							//Just to be sure
							$('#metro-content-wrapper').css({scale:1, opacity:1})
							$("#metro-content-overlay").fadeOut(300, function(){
								$(this).children().empty().append("<div id='metro-content'></div>");						
							});
							
						}
						

						transitions.unsetLoading();
						init();
				
						if(push){
							transitions.replaceState(href, title, "page-tiles", transitions.currentTilesID());
						}
					}
				}
				waiter()
			},
			error: function(data){
				document.location.href = href;
				return false;
			}
		})
	},


	page_to_page :function(href, targetID, targetType, targetBgColor, push){
		transitions.setLoading();

		if(push){
			history.pushState({targetType:targetType, targetBgColor:targetBgColor}, "Loading...", href);
		}
		page.updateType(targetType)


		/* Flips the current page div  and loads new content*/
		$("#metro-content-overlay").show()
		
		/*Flip transition */
		var $metro_content_wrapper = $("#metro-content-wrapper")
		var $metro_content = $metro_content_wrapper.children("#metro-content")
		$metro_content.addClass("loading")
		$metro_content_wrapper
			.css({rotateY:'0deg'})
			.transition({rotateY:'-90deg', height:"90%", "max-width":800},200, 'ease-in' , function(){
				$(this).css({background: transitions.getBg(targetBgColor)})
				.children("#metro-content").hide()
			})
			.transition({rotateY:'-180deg', height:"100%", "max-width":900},200, 'ease-out', function(){
				// only show loading image if ajax content is not loaded yet
				$(this).css({rotateY:'0deg'})
				$metro_content.removeClass("loading").html("<img id='metro-content-loader' src='"+php_data.template_dir+"/img/icons/light/loader.gif'/>")
				$(this).css({transform:"none"}) // fix blurry fonts in IE
				$metro_content.fadeIn(200)
			})
			
		jQuery.ajax({
			url:href,
			dataType:"html",
			success: function(data){
				/*Ok, weve got the content, now extract the things we need out of it */
				data = $.parseHTML(data);

				if( !transitions.pjaxified(data) ){ // this page is not pjaxified
					document.location.href = href;
					return false;
				}

				if(! page.isType("page", data) && 
				   ! page.isType("single", data) ){ // not what we expected...
					console.log("Page type not as expected (type-page or type-single)")
					document.location.href = href;
					return false;	
				}

				// wait until transition is done
				waiter = function(){
					if($metro_content.hasClass("loading")){
						setTimeout("waiter()", 50)
					}else{
						var body = $(data).find("#metro-content-wrapper").html();
						var receivedBgColor = $(data).find("#metro-content-wrapper").css("background-color")
						var title = transitions.updateHeader(data)
						page.updateTypeFromData(data)
						
						$metro_content.removeClass("loading").stop().fadeOut(200, function(){
							$metro_content_wrapper.html(body)
							$('#metro-content').stop().hide().fadeIn(200)
							transitions.scrollToHash(href)

							transitions.unsetLoading();
							init();
							page.addCloseButton()
						})

						if(receivedBgColor != targetBgColor){
							$metro_content_wrapper.css("background",receivedBgColor)
						}
						
						

						if(push){
							transitions.replaceState(href, title, "page", targetID);
						}
					}
				}
				waiter()	

			},
			error: function(data){
				document.location.href = href;
				return false;
			}
		})

	},

	page_tiles_to_page_tiles : function(href, targetID, targetType, targetBgColor, push){
		transitions.setLoading();

		if(push){
			history.pushState({targetType:targetType, targetBgColor:targetBgColor}, "Loading...", href);
		}

		tiles.hide()
		if(isset(targetID) &&  targetID == transitions.currentTilesID()){
			
			setTimeout(function(){
				tiles.show()
				transitions.unsetLoading()
			}, 500) // wait for hide animation to be finished
			
		}else{
			transitions.displayLoader(false, false)
		}
		jQuery.ajax({
			url:href,
			dataType:"html",
			success: function(data){
				/* weve got the content, now extract the things we need out of it */
				data = $.parseHTML(data);

				if( !transitions.pjaxified(data)  ){ // this page is not pjaxified
					document.location.href = href;
					return false;
				}

				if(!page.isType("page-tiles",data)){ // not what we expected...
					console.log("Page type not as expected (type-page-tiles)")
					document.location.href = href;
					return false;	
				}				

				// wait until hiding of tiles is done
				waiter = function(){
					if(tiles.isBusy()){
						setTimeout("waiter()", 50)
					}else{
						var body = $(data).find("#metro-tile-wrapper").html()
						var title =	transitions.updateHeader(data)
						page.updateTypeFromData(data) // just in case...


						if(isset(targetID) &&  targetID == transitions.currentTilesID()){
							tiles.show()
							// the tiles we wanted to load are already loaded, so do nothing
					 	}else{
							transitions.hideLoader()
							$("#metro-tile-wrapper").html(body).fadeIn(400);

							// just in case...
							$("#metro-content-overlay").fadeOut(300, function(){
								$(this).children().empty().append("<div id='metro-content'></div>");
							});

							
						}
						transitions.unsetLoading();
						init();

						if(push){
							//history.replaceState({targetType:"page-tiles"}, title, href);
							transitions.replaceState(href, title, "page-tiles", transitions.currentTilesID());
						}	
					}
				}
				waiter()				
			},
			error: function(data){
				document.location.href = href;
				return false;
			}
		})
	},

	unknown : function(href, targetID, targetType, targetBgColor, push){
		transitions.setLoading();

		$("#metro-wrapper").fadeOut(300)
		jQuery.ajax({
			url:href,
			dataType:"html",
			success: function(data){
				/*Ok, weve got the content, now extract the things we need out of it */
				data = $.parseHTML(data);

				if( !transitions.pjaxified(data)){ // this page is not pjaxified
					document.location.href = href;
					return false;
				}

				var body = $(data).find("#metro-wrapper").hide().end().filter("#metro-pjax").html()
				var title = $(data).filter("title").html()
				$("title").html(title)
				$("#metro-pjax").html(body)
				$(".tile").show()
				$("#metro-wrapper").fadeIn(300)
				transitions.unsetLoading();
				init();

				if(push){
					history.pushState({targetType:"unknown"}, title, href);
				}
			},
			error: function(data){
				document.location.href = href;
				return false;
			}
		})
	},

	displayLoader: function(overlay_bg, wrapper_bg){
		//General loader, will be used when a "normal" page is loading, to avoid confusion
		if(!isset(overlay_bg) || overlay_bg){
			$("#metro-loader-overlay").css("background","")
		}else{
			$("#metro-loader-overlay").css("background","transparent")
		}
		if(!isset(wrapper_bg) || wrapper_bg){
			$("#metro-loader-wrapper").css("background","")
		}else{
			$("#metro-loader-wrapper").css("background","transparent")
		}
		$("#metro-loader-wrapper").show()
		$("#metro-loader-overlay").stop().fadeIn(500)
	},
	hideLoader: function(){
		//General loader, will be used when a "normal" page is loading, to avoid confusion
		$("#metro-loader-overlay").stop().fadeOut(300)
	}

}