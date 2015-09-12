/* This file is a mess, needs to be cleaned up! */

/* MOUSEWHEEL Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)*/
 (function(a,b){function e(d){var f=d||window.event,g=[].slice.call(arguments,1),h=0,i=0,j=0;return d=a.event.fix(f),d.type="mousewheel",f.wheelDelta&&(h=f.wheelDelta/120),f.detail&&(f.type==c[2]?(this.removeEventListener(c[0],e,!1),h=-f.detail/42):h=-f.detail/3),j=h,f.axis!==b&&f.axis===f.HORIZONTAL_AXIS&&(j=0,i=-1*h),f.wheelDeltaY!==b&&(j=f.wheelDeltaY/120),f.wheelDeltaX!==b&&(i=-1*f.wheelDeltaX/120),g.unshift(d,h,i,j),(a.event.dispatch||a.event.handle).apply(this,g)}var c=["DOMMouseScroll","mousewheel","MozMousePixelScroll"];if(a.event.fixHooks)for(var d=c.length;d;)a.event.fixHooks[c[--d]]=a.event.mouseHooks;a.event.special.mousewheel={setup:function(){if(this.addEventListener)for(var a=c.length;a;)this.addEventListener(c[--a],e,!1);else this.onmousewheel=e},teardown:function(){if(this.removeEventListener)for(var a=c.length;a;)this.removeEventListener(c[--a],e,!1);else this.onmousewheel=null}}})(jQuery);

function metro_wys_fixScrolling(){
	jQuery("#metro-wys-tile-wrap").bind("mousewheel.scrollTiles", function(event, delta) { // Mouse scroll to move tilepages
			if(delta>0){
			 	jQuery("#metro-wys-tile-wrap").stop().animate({scrollLeft:"-=250"},30,function(){
			 	});
			}else{
			 	jQuery("#metro-wys-tile-wrap").stop().animate({scrollLeft:"+=250"},30,function(){
			 	});
			}	
		event.preventDefault();
	});
}

function isset(v){
	return typeof v != "undefined"
}

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

/****************************** GROUP FUNCTIONS *************************/
/* BUTTONS for each group */
jQuery(document).on("click","#edit-group-button",function(){
	var $thisGroup = jQuery(this).parent().children(".metro-wys-groupTitle")
	$thisGroup.addClass('editing')
	var url = (typeof $thisGroup.data('metro-url') == 'undefined') ? '' : $thisGroup.data('metro-url') 
	var margin_left = (typeof $thisGroup.data('metro-margin-left') == 'undefined') ? 1 : $thisGroup.data('metro-margin-left') 

	var content ='';
	content += '<h2>'+php_data.ln_edit_group+'</h2>'
	content += '<div id="metro-wys-prop-fields">'
	content += '<table>'
	content += '<tr><td>'+php_data.ln_title+'</td>'
	content += '<td>'
	content += '<input type="text" id="metro-group-title" value="'+ $thisGroup.html()+ '">'
	content += '</td><tr>'

	content += '<tr><td>'+php_data.ln_url+' ('+php_data.ln_leave_blank+'):</td>'
	content += '<td>'
	content += '<input id="metro-tile-prop-url" type="text" size="32" name="insert_url" value="'+url+'" />\
				<input id="insert_url_button" type="button" value="'+php_data.ln_select_url+'" class="button"/>\
				'+php_data.ln_enter_url+'\
				</label>'
	content += '</td><tr>'

	content += '<tr><td>'+php_data.ln_group_margin_left+':</td>'
	content += '<td>'
	content += '<input type="number" id="metro-group-margin-left" value="'+margin_left+'" class="button"/>'
	content += '</td><tr>'
	content += '</table>'

	content += '<div id="metro-wys-form-buttons">\
	        		<button id="metro-add-group-form" class="button-primary">'+php_data.ln_apply_changes+'/button> \
	        		<button id="metro-wys-popup-cancel" class="button">'+php_data.ln_cancel+'</button>\
	        	</div>'
	content += '</div>'

	metroOpenPopup(content)
	/*var new_name = prompt("Enter the new name for this group", jQuery(this).parent().children(".metro-wys-groupTitle").html())
	if(new_name){
		$my_title.html(new_name)
	}*/
	return false
})

jQuery(document).on('click', '#metro-add-group-form', function(){
	/* Save group editing form */
	var $thisGroup = jQuery('#metro-wys-tile-sizer').find(".metro-wys-groupTitle.editing")
	var $fields = jQuery('#metro-wys-popup').find('#metro-wys-prop-fields')
	var newName = $fields.find('#metro-group-title').val()
	$thisGroup.html(newName)
	var newUrl = $fields.find('#metro-tile-prop-url').val()
	var newSpacing = $fields.find("#metro-group-margin-left").val()
	$thisGroup.data({'metro-url': newUrl, 'metro-group-margin-left': newSpacing});
	$thisGroup.removeClass('editing')
	updateTileData();
	metroClosePopup();
	return false
})

jQuery(document).on('click',"#delete-group-button",function(){
	//var $my_title = jQuery(this).parent().children(".metro-wys-groupTitle")
	if(confirm(php_data.ln_delete_group)){
		var $el = jQuery(this).closest("div.gridster")
		var group = parseInt($el.attr("id").replace("gr",""))
		$el.remove()
		gridster[group] = null
		updateTileData()
	}
	return false
})
// move group to right
jQuery(document).on("click","#move-group-right-button",function(){
	var $parent = jQuery(this).closest(".gridster")
	var $next = $parent.next(".gridster")
	if(isset($next)){
		$next.after($parent)
	}
	updateTileData();
	return false
})
// move group to left
jQuery(document).on("click","#move-group-left-button",function(){
	var $parent = jQuery(this).closest(".gridster")
	var $next = $parent.prev(".gridster")
	if(isset($next)){
		$next.before($parent)
	}
	updateTileData();
	return false
})

/********************************** POPUPS ***************************************/
/* Custom popups, because otherwise we can't use the built in media selector */
function metroOpenPopup(data){
	jQuery("#metro-wys-popup").fadeIn(300).children("#metro-wys-popup-window").html(data)
}
function metroClosePopup(){
	jQuery("#metro-wys-popup").fadeOut(300).children("#metro-wys-popup-window").empty()
}
jQuery(document).on("click","#metro-wys-popup-cancel",function(){
	metroClosePopup();
	return false
})


/* Img insert */
var file_frame;
 
jQuery('#upload_image_button').live('click', function( event ){

	event.preventDefault();

	// Do the needed things
	jQuery(this).parent().addClass('active-img-field')
	jQuery("#metro-wys-add-wrap").parent().hide() //?

	// If the media frame already exists, reopen it.
	if ( file_frame ) {
	  file_frame.open();
	  return;
	}

	// Create the media frame.
	file_frame = wp.media.frames.file_frame = wp.media({
	  title: jQuery( this ).data( 'uploader_title' ),
	  button: {
	    text: jQuery( this ).data( 'uploader_button_text' ),
	  },
	  multiple: false  // Set to true to allow multiple files to be selected
	});

	// When an image is selected, run a callback.
	file_frame.on( 'select', function() {
	  // We set multiple to false so only get one image from the uploader
	  attachment = file_frame.state().get('selection').first().toJSON();

	  // Do something with attachment.id and/or attachment.url here
	  //console.log(attachment)
	var img = attachment.url;
	var imgAlt = attachment.alt;
	var imgTitle = attachment.title;
	jQuery('.active-img-field').children('.metro-img-input-field').val(img);
	if(jQuery('.active-img-field').children("#metro-tile-prop-img_alt").length>0){
		jQuery('.active-img-field').children("#metro-tile-prop-img_alt").val(imgAlt)
	}
	if(jQuery('.active-img-field').children("#metro-tile-prop-img_title").length>0){
		jQuery('.active-img-field').children("#metro-tile-prop-img_title").val(imgAlt)
	}
	jQuery('.active-img-field').removeClass('active-img-field')



	});

	// Finally, open the modal
	file_frame.open();
});




/* Insert link */
jQuery(document).on('click', '#insert_url_button', function(event) {
	jQuery(this).parent().addClass('active-url-field')
    wpActiveEditor = true; //we need to override this var as the link dialogue is expecting an actual wp_editor instance
    wpLink.open(); //open the link popup
    return false;
});
jQuery(document).on('click', '#wp-link-submit', function(event) {
    if(jQuery('#metro-wys-popup').css("display") != 'none'){
	    var linkAtts = wpLink.getAttrs();//the links attributes (href, tpropet) are stored in an object, which can be access via  wpLink.getAttrs()
	    jQuery('#metro-wys-popup').find('.active-url-field').find('.metro-url-input-field').val(linkAtts.href);//get the href attribute and add to a textfield, or use as you see fit
	    if(jQuery('#metro-wys-popup').find('#metro-tile-prop-new_tab').length>0){
	    	jQuery('#metro-wys-popup').find('#metro-tile-prop-new_tab').attr('checked', linkAtts.tpropet == "_blank");
	    }
	    
	    wpLink.textarea = jQuery('#metro-tile-prop-url'); //to close the link dialogue, it is again expecting an wp_editor instance, so you need to give it something to set focus back to. In this case, I'm using body, but the textfield with the URL would be fine
	    wpLink.close();//close the dialogue
	}
	//trap any events
    event.preventDefault ? event.preventDefault() : event.returnValue = false;
    event.stopPropagation();
    return false;
});
jQuery(document).on('click', '#wp-link-cancel, #wp-link-close', function(event) {
    wpLink.textarea = jQuery('#insert_url');
    wpLink.close();
    event.preventDefault ? event.preventDefault() : event.returnValue = false;
    event.stopPropagation();
    return false;
});

/*********************************** BUTTON BAR *************************************/
// Add tile
jQuery(document).on("click","#metro-wys-add-tile-button",function(){
	var content = ''
	content += '<h2>'+php_data.ln_add_tile+'</h2>'
	content += '<select id="metro-wys-add-tile-select-type">'
	content += '<option value="none">'+php_data.ln_select_type+'...</option>'	

	/*Sort types*/
	types = []
	for(var type in metroTiles['tiles']){
		types[types.length] = type
	}
	types.sort()

	/*Add types to select */
	for(var i in types){
		type = types[i]
		if(isset(metroTiles['tiles'][type]['admin'])){
			content += '<option value="'+type+'">'+type+'</option>'
		}
	}
	content += '</select>'

	/*Add closing buttons */
	content += '<div id="metro-wys-form-buttons">\
	        		<button id="metro-wys-popup-cancel" class="button">'+php_data.ln_cancel+'</button>\
	        	</div'

	metroOpenPopup(content)
	return false
})

// Add group
jQuery(document).on("click","#metro-wys-add-group-button",function(){
	jQuery("#metro-wys-tile-sizer").append('\
		<div class="gridster" id="gr'+gridster.length+'">\
			<div class="metro-wys-groupTitle-wrap">\
				<div class="metro-wys-groupTitle">'+php_data.ln_new_group+'</div>\
				<button id="edit-group-button">'+php_data.ln_edit+'</button>\
				<button id="delete-group-button">'+php_data.ln_delete+'</button>\
				<button id="move-group-left-button"><<</button>\
				<button id="move-group-right-button">>></button>\
			</div>\
			<ul></ul>\
        </div>')
	gridster[gridster.length] = jQuery(".gridster#gr"+gridster.length+" > ul").gridster(settings).data('gridster');
	updateTileData();
	return false
})



/*********************************** FORMS ***********************************/
/* Build add tile form */
jQuery(document).on("change","#metro-wys-add-tile-select-type",function(){
	removeFormButtons();
	jQuery('#metro-wys-popup').find("#metro-wys-prop-fields").remove();

	var type = jQuery(this).val()
	if(type == "none"){
		return false
	}

	var content = '<div id="metro-wys-prop-fields">'
	if(jQuery("#metro-wys-tile-sizer").children(".gridster").length == 0){
		content += 'No groups added yet!'
	}else{
		content += "<form><table cellpadding='3' class='metro-prop-table'>"

		/* Add group select */
		content += "<tr><td></td><td></td></tr>"
		content += "<tr><td>"+php_data.ln_group+":</td><td>"
		content += "<select id='metro-wys-group'>"
		jQuery("#metro-wys-tile-sizer").children(".gridster").each(function(i, val){
			content += "<option value='"+parseInt(jQuery(this).attr("id").replace("gr",""))+"'>"+i+" "
			content += jQuery(this).find(".metro-wys-groupTitle").html()
			content += "</option>"
		})
		content += "</select>"
		content += "</td></tr>"

		/* Add tile properties */
		var thisAdmin = metroTiles['tiles'][type]['admin']
		for(var propName in thisAdmin){
			var thisEl = thisAdmin[propName]

			// Get default val
			var this_val = metroTiles['tiles'][type]['defaults'][propName]

			// Add description label
			var label = (thisEl[0] == "") ? propName : thisEl[0]
			content += "<tr><td>"+label+"</td><td>"


			var propType = thisEl[1]
			var propOpt = (isset(thisEl[2])) ? thisEl[2] : null;
			content += metroGetPropCode(propType, propName, propOpt, this_val)

			content += "</td></tr>"
		}

		content += "</table></form>"
	}

	content += '</div><div id="metro-wys-form-buttons">\
	        		<button id="metro-add-tile-submit" class="button-primary">'+php_data.ln_insert_tile+'</button> \
	        		<button id="metro-wys-popup-cancel" class="button">'+php_data.ln_cancel+'</button>\
	        	</div'

	jQuery("#metro-wys-popup-window").append(content) // append because of form tag "bug"
	// add colorpicker
	jQuery('.choose_color').wpColorPicker();

	// check height
	jQuery(window).resize()
})

// Submits the add tile form */
jQuery(document).on("click","#metro-add-tile-submit",function(){
	$popup = jQuery("#metro-wys-popup")
 
	var props = {}
	props.group = jQuery("#metro-wys-popup").find("#metro-wys-group").val()
	props.type = $popup.find("#metro-wys-add-tile-select-type").val()

	if(props.type == 'widget'){
		$popup.children('#metro-wys-popup-window').append(php_data.ln_processing+'...')
		props = getFormProps(props)

		/* Build widget data */
		var widgetData = {}
		var form_data = jQuery('#metro-widget-args-wrap').serializeArray()
		for(var index in form_data){
			//console.log(form_data[index])
			var key = form_data[index]['name']
			var val = form_data[index]['value']
			if(/\[\]\[(.+)\]/i.test(key)){
				key = key.match(/\[\]\[(.+)\]/i)[1];
			}
			widgetData[key] = val
		}
		
		jQuery.ajax({
			type: 'POST',
			url: php_data.theme_dir+'/admin/ajax/save-widget-form.php',
			data: widgetData,
			props:props,
			success: function(data){
				try{
					var data = JSON.parse(data)
					props = getFormProps(this.props)
					props.widget_props = data
					insertTile(props)

					//update hidden field
					updateTileData()
					metroClosePopup()
				}catch(e){
					//console.log(data)
					alert(php_data.ln_widget_try_again)
					metroClosePopup()
				}
	
			}
		})

	}else{
		props = getFormProps(props)
		insertTile(props)

		//update hidden field
		updateTileData()
		metroClosePopup()
	}
	
	
	return false
})

// Build edit tile form
jQuery(document).on("click",".metro-wys-edit-tile", function(){
	removeFormButtons();

	$tile = jQuery(this).closest("li")
	$tile.addClass("editing")
	// Note: group is not in props
	var props = $tile.data("metro-props")
	var type = props.type

	//Get group of chosen tile
	var $parent = jQuery(this).closest(".gridster")
	var group = parseInt($parent.attr("id").replace("gr",""))

	if(!isset(type) || type == "none"){ // just in case...
		return false
	}

	var content = "<h2 style='margin-top:0;'>"+php_data.ln_edit_group+"</h2> <i>Type "+type+"</i>"
	content += '<div id="metro-wys-prop-fields">'
	content += "<form><input type='hidden' id = 'metro-tile-prop-type' value='"+type+"'/>"
	content += "<input type='hidden' id = 'metro-tile-prop-group' value='"+group+"'/>"
	content += "<table cellpadding='3' class='metro-prop-table'>"

	/* Add tile properties */
	var thisAdmin = metroTiles['tiles'][type]['admin']
	for(var propName in thisAdmin){
		var thisEl = thisAdmin[propName]

		// Fill in props by selected ones
		if(isset(props[propName])){
			var this_val = props[propName]
		}else{
			var this_val = metroTiles['tiles'][type]['defaults'][propName]
		}
		
		// Add description label
		var label = (thisEl[0] == "") ? propName : thisEl[0]
		content += "<tr><td>"+label+"</td><td>"

		var propType = thisEl[1]
		var propOpt = (isset(thisEl[2])) ? thisEl[2] : null;
		content += metroGetPropCode(propType, propName, propOpt, this_val)

		content += "</td></tr>"
	}
	content += "</table></form>"
	content += '</div>'

	metroOpenPopup(content)
	jQuery("#metro-wys-popup-window").append(
		'<div id="metro-wys-form-buttons">\
	        <button id="metro-edit-tile-form" class="button-primary">'+php_data.ln_apply_changes+'</button> \
	        <button id="metro-wys-popup-cancel" class="button">'+php_data.ln_cancel+'</button>\
	    </div>'
	)

	// add colorpicker
	jQuery('.choose_color').wpColorPicker();

	// check height
	jQuery(window).resize()
	return false
})

// Submits the edit tile form */
jQuery(document).on("click","#metro-edit-tile-form",function(){
	var type = jQuery("#metro-wys-popup").find("#metro-tile-prop-type").val()
	
	var props = {}
	props.group = jQuery("#metro-wys-popup").find("#metro-tile-prop-group").val()
	props.type = type
	props = getFormProps(props)

	$editing = jQuery("li.editing")

	// Add some props that are determined by the gridster position
	props["x"] = parseInt($editing.attr("data-col"))-1
	props["y"] = parseInt($editing.attr("data-row"))-1
	props["width"] = parseInt($editing.attr("data-sizex"))
	props["height"] = parseInt($editing.attr("data-sizey"))
	

	$popup = jQuery('#metro-wys-popup')
	if(props.type == 'widget'){
		$popup.children('#metro-wys-popup-window').append(php_data.ln_processing+'...')
		props = getFormProps(props)

		/* Build widget data */
		var widgetData = {}
		var form_data = jQuery('#metro-widget-args-wrap').serializeArray()
		for(var index in form_data){
			//console.log(form_data[index])
			var key = form_data[index]['name']
			var val = form_data[index]['value']
			if(/\[\]\[(.+)\]/i.test(key)){
				key = key.match(/\[\]\[(.+)\]/i)[1];
			}
			widgetData[key] = val
		}
		
		jQuery.ajax({
			type: 'POST',
			url: php_data.theme_dir+'/admin/ajax/save-widget-form.php',
			data: widgetData,
			props:props,
			success: function(data){
				try{
					var data = JSON.parse(data)
					props.widget_props = data

					// remove old tile
					gridster[props.group].remove_widget( $editing )
					$editing.remove()

					insertTile(props)

					//update hidden field
					updateTileData()

					metroClosePopup();
				}catch(e){
					console.log(data);
					alert(php_data.ln_widget_try_again)
					metroClosePopup();
				}
			}
		})

	}else{
		// remove old tile
		gridster[props.group].remove_widget( $editing )
		$editing.remove()

		//insert new at (hopefully) the same position
		insertTile(props)

		//update hidden field
		updateTileData()

		metroClosePopup();
	}

	return false
})

jQuery(document).on("click","#metro-wys-add-to-array-button",function(){
	/* adds new element to prop-type "array"*/
	var content = '<br/>'+getPropContent(jQuery(this).data("prop-type"),jQuery(this).data("prop-val"), '')
	jQuery(this).parent().children(".metro-wys-array-container").append(content)
	return false
})



function getFormProps(props){
	/* Gets the props values out of the open form popup. props must be a given object with at least the type (as props.type) of tile */
	for(propName in metroTiles['tiles'][props.type]['defaults']){
		if(propName == "type" || propName == 'widget_form'  || propName == 'widget_props'){
			continue
		}
		$thisEl = jQuery("#metro-tile-prop-"+propName)		

		if($thisEl.length>0){

			// Small type check, checkboxes can't use val and we'll need to process arrays..
			// Selects are alright with val
			var propType = metroTiles['tiles'][props.type]['admin'][propName][1]
			switch(propType){ // type of property
				case "checkbox":
					props[propName] = $thisEl.is(":checked")
				break;
				case "array":
					prop_vals = []
					$container = jQuery("#metro-tile-proparray-"+propName)
					if($container.length>0){

						// get each element of the arayt
						$container.find("input").not('input[type=button]').each(function(){
							if(jQuery(this).val() != ''){
								prop_vals[prop_vals.length] = jQuery(this).val()
							}
						})
						props[propName] = prop_vals
					}else{
						props[propName] = $thisEl.val()
					}

				break;
				default:
				props[propName] = $thisEl.val()
			}

			
		}else{
			props[propName] = metroTiles['tiles'][props.type]['defaults'][propName]
		}
	}
	return props
}

function getWidgetFormProps(){
	if(jQuery('#metro-wys-popup').find('#metro-widget-args-wrap').length > 0 ) {
		var props = jQuery("#metro-widget-args-wrap").serialize();
		return props
	}else{
		return {}

	}
}


function insertTile(props){
	var tileButtons = "\
	<button class='metro-wys-move-tile-left'><<</button>\
	<button class='metro-wys-move-tile-right'>>></button>\
	<button class='metro-wys-edit-tile'>"+php_data.ln_edit+"</button>\
	<button class='metro-wys-duplicate-tile' title='Duplicate tile'>Dup</button>\
	<button class='metro-wys-delete-tile'>Del</button>\
	"
	var tileContent = tileButtons

	var type = props.type
	var group = props.group

	// Check if complete, else default it
	tileContent += "<div class='metro-wys-prop-overview'>"
	if(!isset(metroTiles['tiles'][type])){
		return
	}

	tileContent += '<strong>Type: '+type+'</strong><br>'

	// Add text of props inside tile
	for(prop in metroTiles['tiles'][type]['defaults']){
		if(!isset(props[prop])){
			props[prop] = metroTiles['tiles'][type]['defaults'][prop]
		}

		// Display props in tile body, except some obvious ones
		switch(prop){
			case 'x':
			case 'y':
			case 'width':
			case 'height':
			case 'background':
			case 'widget_form':
			case 'label_text':
			case 'label_color':
			case 'label_position':
			case 'anim_data':
			case 'attr':
			break;
			default:
			tileContent += htmlEntities(prop)+': '+htmlEntities(props[prop])+'<br>'
		}
		
	}
	tileContent += '</div>'

	// Add label
	if(isset(props.label_text) && props.label_text != ''){
		if(props.label_position == 'top'){
			tileContent += "<div class='tile-label-wrap top' style='border-top-color:"+props.label_color+";'><div class='tile-label top' >"+props.label_text+"</div></div>"
		}else{
			tileContent += "<div class='tile-label-wrap bottom'><div class='tile-label bottom' style='border-bottom-color:"+props.label_color+";'>"+props.label_text+"</div></div>";
		}
	}



	props.scale = scale
	props.spacing = spacing
	props.scale_spacing = scale + spacing
	propsStr = jQuery.param(props)
	//alert(propsStr)
	// The actual data string that will be added 
	var thisID = uniqID()
	var tile = '<li id="'+thisID+'" class="tile" data-metro-props=\''+JSON.stringify(props)+'\' \
				data-col="5" style="background:'+props['background']+';">'
	/*tile += '<div class="metro-wys-tile-buttons">'+tileButtons+'</div>'
/*	tile += '<iframe width="'+(props['width']*(scale+spacing)-spacing)+'" height="'+(props['height']*(scale+spacing)-spacing)+'" src="'
	tile += php_data.theme_dir
	tile += '/admin/ajax/tile-preview.php?'+propsStr+'" class="tile-content"/>'*/
	tile += '<div class="metro-tile-wysiwyg-wrapper">'
	tile += tileContent
	tile += '</div>'
	tile += '</li>'

	if(!isset(gridster[group])){
		alert('The group you selected doesn\'t exist. Please try reloading the page')
		return false
	}

	gridster[group].add_widget(tile, props['width'], props['height'], props['x']+1, props['y']+1)

	// preview holder might cause bugs
	jQuery('li.preview-holder').remove() 
	updateTileData()
}

function updateTileData(){
	var data = {}
	var length = 0
	jQuery('div.gridster').each(function(i, val){
		var groupUrl = (typeof  jQuery(this).find('.metro-wys-groupTitle').data('metro-url') == 'undefined') ? '' :  jQuery(this).find('.metro-wys-groupTitle').data('metro-url') 
		var spacing = (typeof  jQuery(this).find('.metro-wys-groupTitle').data('metro-group-margin-left') == 'undefined') ? 1 :  jQuery(this).find('.metro-wys-groupTitle').data('metro-group-margin-left') 
		data[length] = {
			type:'group',
			title: jQuery(this).find('.metro-wys-groupTitle').html(),
			url: groupUrl,
			margin_left: spacing
		}
		length++
		jQuery(this).find('ul').children('li.gs-w').each(function(){
			var saved_props = jQuery(this).data('metro-props')
			// .data() doesn't work, don't know why, probably bug...
			saved_props['x'] = parseInt(jQuery(this).attr('data-col'))-1
			saved_props['y'] = parseInt(jQuery(this).attr('data-row'))-1
			saved_props['width'] = parseInt(jQuery(this).attr('data-sizex'))
			saved_props['height'] = parseInt(jQuery(this).attr('data-sizey'))
			data[length] = saved_props
			length++
		})
	})
	data = JSON.stringify(data)
	jQuery('#metro-wys-tile-data').val(data)
	jQuery('.wp-editor-area').html(data)

	console.log('updateTileData')
}

/***************************************** TILE BUTTONS *******************************************/
/* Move tile to other group */
jQuery(document).on("click",".metro-wys-move-tile-left",function(){
	var $thisEl = jQuery(this).closest("li")
	var tile = $thisEl[0].outerHTML
	var $parent = jQuery(this).closest(".gridster")
	var $next = $parent.prev(".gridster")
	if(isset($next) && $next.attr("id") != "undefined"){
		var curr_group = parseInt($parent.attr("id").replace("gr",""))
		var group = parseInt($next.attr("id").replace("gr",""))
		gridster[curr_group].remove_widget($thisEl)
		gridster[group].add_widget(tile, $thisEl.data("sizex"),$thisEl.data("sizey"))
		setTimeout(function(){
			updateTileData()
		},500)
		
	}
	return false
})
jQuery(document).on("click",".metro-wys-move-tile-right",function(){
	var $thisEl = jQuery(this).closest("li")
	var tile = $thisEl[0].outerHTML
	var $parent = jQuery(this).closest(".gridster")
	var $next = $parent.next(".gridster")
	if(isset($next) && $next.attr("id") != "undefined"){
		var curr_group = parseInt($parent.attr("id").replace("gr",""))
		var group = parseInt($next.attr("id").replace("gr",""))
		gridster[curr_group].remove_widget($thisEl)
		gridster[group].add_widget(tile, $thisEl.data("sizex"),$thisEl.data("sizey"))
		setTimeout(function(){
			updateTileData()
		},500)
	}
	return false
})
jQuery(document).on("click",".metro-wys-delete-tile",function(){
	if(confirm(php_data.ln_delete_tile)){
		var $thisEl = jQuery(this).closest("li")
		var $parent = jQuery(this).closest(".gridster")
		var curr_group = parseInt($parent.attr("id").replace("gr",""))
		gridster[curr_group].remove_widget($thisEl)
		setTimeout(function(){
			updateTileData()
		},500)
	}
	return false
})
jQuery(document).on("click",".metro-wys-duplicate-tile",function(){
	var props = jQuery(this).closest('li.tile').data("metro-props")
	insertTile(props)
	return false
})

/*************************************** OTHER **************************************/
jQuery(document).ready(function(){
	metro_wys_fixScrolling();
	jQuery("body").css("overflow","hidden") 
	
	
	jQuery('#metro-wys-tiles-title').html(jQuery('#titlewrap>input#title').val())
	
	setTimeout(function(){
		jQuery(window).resize();
	},100)
})

jQuery(document).on('change','#titlewrap>input#title', function(){
	jQuery('#metro-wys-tiles-title').html(jQuery('#titlewrap>input#title').val())
})

jQuery(document).on('click', 'button#metro-wys-update-page', function(){
	jQuery('form#post').submit();
	return false;
})


jQuery(window).resize(function(){
	jQuery("#metro-wys-popup-window").height(jQuery(window).height()-200)

	jQuery('#metro-wys-tile-wrap').height( jQuery('#metro-wys-wrap').height() - jQuery('#metro-wys-tiles-title').outerHeight()-jQuery('#metro-wys-button-bar').height() -1 )
})

jQuery(document).on("click","#metro-wys-close-editor",function(){
	jQuery("#metro-wys-wrap").fadeOut(200);
	return false
})

jQuery(document).on("click","#metro-wys-open-editor",function(){
	jQuery("#metro-wys-wrap").fadeIn(200)
	return false
})

jQuery(document).on("click","#metro-import-tiles-button",function(){
	var content = php_data.import_tiles_desc+"<br/>"
	content += "<textarea id='metro-import-data-textarea'>"+jQuery("#metro-wys-tile-data").val()+"</textarea><br/>"
	content += "<button id='metro-import-data-apply'>"+php_data.ln_apply_changes+"</button> <button id='metro-wys-popup-cancel'>"+php_data.ln_cancel+"</button>"
	metroOpenPopup(content);
	return false
})
jQuery(document).on('click','#metro-import-data-apply',function(){
	if(jQuery('#metro-import-data-textarea').length>0){
		metroParseData(jQuery.trim(jQuery('#metro-import-data-textarea').val()))
	}
	return false
})


function metroParseData(data){
	jQuery("#metro-wys-tile-data").val(data)
	var data = data || jQuery("#metro-wys-tile-data").val()
	try{
		var data = JSON.parse(data)
	}catch(e){
		console.log('recieved invalid JSON')
		var data = {}
	}
	


	jQuery("#metro-wys-tile-sizer").empty()
	gridster = new Array();

	var group_width = []
	var most_right = 0
	var tiles = []

	for(var index in data){
		var tile = data[index]
		var type = tile['type']
		if(type == 'group'){
			if(group_width.length > 0){
				jQuery(".gridster#gr"+(group_width.length-1)).children("ul").css("min-width", scale*(most_right+2))
			}
			group_width[group_width.length] = most_right
			most_right = 0

			jQuery("#metro-wys-tile-sizer").append(
			'<div class="gridster" id="gr'+(group_width.length-1)+'">\
				<div class="metro-wys-groupTitle-wrap">\
					<div class="metro-wys-groupTitle">'+tile['title']+'</div>\
					<button id="edit-group-button">'+php_data.ln_edit+'</button>\
					<button id="delete-group-button">'+php_data.ln_delete+'</button>\
					<button id="move-group-left-button"><<</button>\
					<button id="move-group-right-button">>></button>\
				</div>\
				<ul></ul>\
	        </div>')
			
			gridster[group_width.length-1] = jQuery(".gridster#gr"+(group_width.length-1)+" > ul").gridster(settings).data("gridster");
		}else{
			tile['group'] = group_width.length-1
			tiles[tiles.length] = tile
			if(tile['x'] + tile['width'] > most_right){
				most_right = tile['x'] + tile['width']
			}
		}


	}

	if(jQuery(".gridster#gr"+(group_width.length-1)).length >0){
			jQuery(".gridster#gr"+(group_width.length-1)).children("ul").css("min-width", scale*(most_right+1))
	}
	
	/* Because gridster sometimes messes it up, we'll have to rearrange manually */
	tiles = sortTiles(tiles)
	for(var index in tiles){
		insertTile(tiles[index])
	}
	


	metroClosePopup()
}

/*function addTiles(tiles,i){
	insertTile(tiles[i])
	if(typeof tiles[i+1] != "undefined"){
		setTimeout(function(){addTiles(tiles, i+1)}, 500)
	}
}*/

function removeFormButtons(){
	jQuery("#metro-wys-popup-window").find('#metro-wys-form-buttons').remove();
	jQuery("#metro-wys-popup-window").find('#metro-widget-args-wrap').remove();
	jQuery("#metro-wys-popup-window").find('#metro-wys-prop-fields').empty();
}


function sortTiles(tiles){
	/* Sorts tiles by X and Y position, otherwise gridster bugs */
	var sorted = {}
	for(j = 0; j<tiles.length; j++){
		var smallestY = false
		var smallestX = false
		var smallestObj = false
		for(i = 0; i<tiles.length; i++){
			/* Find smallest and add to sorted, then set that entry to null (since deleting gives problems) , continue until var tiles is empty */
			if(tiles[i] !== null){
				thisY = tiles[i]['y']
				thisX = tiles[i]['x']
				if(smallestY === false || thisY < smallestY || (thisY == smallestY && thisX < smallestX)){
					smallestY = thisY
					smallestX = thisX
					smallestObj = i
				}
			}	
		}
		if(smallestY !== false){
			sorted[j] = tiles[smallestObj]
			tiles[smallestObj] = null
		}
	}
	return sorted
}

function metroGetPropCode(propType, propName, propOpt, val){
	var content = '';
	switch(propType){ // type of property
		case "select":	
			content += metroGetSelectCode(propName, propOpt, val);
		break;
		case "array":
			content += metroGetArrayCode(propName, propOpt, val)
		break;
		case 'widget_props':
			setTimeout(function(){
				if(jQuery('#metro-tile-prop-widget_type').length>0){
					var widgetType = (jQuery('#metro-tile-prop-widget_type').find('option:selected').text())
					if(widgetType == ''){
						widgetType = jQuery('#metro-tile-prop-widget_type').find('option').eq(0).val()
					}
					if(val != '' && jQuery.isPlainObject(val)){
						var widgetData = val
					}else{
						var widgetData = {}
					}
					
					widgetData['metro_widget_type'] = widgetType

					jQuery.ajax({
						type: 'POST',
						url: php_data.theme_dir+'/admin/ajax/load-widget-form.php', 
						data: widgetData,
						widgetType : widgetType,
						success: function(data){
								if(jQuery('#metro-tile-prop-widget_type').length > 0){
									var str = '<input type="hidden" name="metro_widget_type" id="metro_widget_type" value="'+this.widgetType+'"/>'+data
									jQuery('#metro-wys-popup-window').append('<form id="metro-widget-args-wrap">'+str+'</form>')
								}
							}
						}
					)
				}else{
					alert("Something wen't wrong. Please try reloading the page.")
				}
			},50)
		default:
		content += getPropContent(propType, propName, val)
	}
	return content
}
function metroGetSelectCode(prop, options, val){
	var content = ''
	content += "<select id='metro-tile-prop-"+prop+"'>"
	for(var index in options){
		content += "<option value='"+index+"'"
		if(options[index] == val){	
			content += " selected = 'selected' "
		}
		content +="'>"+options[index]+"</option>"
	}
	content += "</select>"
	return content
}
function metroGetArrayCode(prop, array_item_type, vals){
	var content = ''
	content += "<div id='metro-tile-proparray-"+prop+"' class='metro-wys-array-container'>"
	for(var index in vals){
		content += getPropContent(array_item_type, prop, vals[index])
	}
	if(vals.length == 0){
		content += getPropContent(array_item_type, prop, "")
	}
	content += "</div>"
	content += "<button class='button' id='metro-wys-add-to-array-button' data-prop-type='"+array_item_type+"' data-prop-val='"+prop+"'>"+php_data.ln_add_element+"</button"
	return content
}
function getPropContent(propType, prop, val, clas){
	/*This is used to built the property fields of forms */
	clas = clas || ""
	var content = ""
	switch(propType){
		case "image":
			content +='\
				<label for="metro-tile-prop-img">\
				<input id="metro-tile-prop-'+prop+'" class="metro-img-input-field" '+clas+'" type="text" size="32" name="metro-tile-prop-img" value="'+val+'" /><br/>\
				<input id="upload_image_button" type="button" value="'+php_data.ln_select_img+'" class="button"/>\
				'+php_data.ln_enter_img+'\
				</label>'
		break;
		case "int":
			content += "<input type='number'  size='32' class='"+clas+"' id='metro-tile-prop-"+prop+"' value='"+val+"'/>"
		break;
		case "text":
			content += "<input type='text'  size='32' class='"+clas+"' id='metro-tile-prop-"+prop+"' value='"+val+"'/>"
		break;
		case "color":
			content += "<input type='text' size='32' class='"+clas+" choose_color' id='metro-tile-prop-"+prop+"' value='"+val+"'/>"
		break;
		case "checkbox":
			var checked = (val == true || val == "on") ? " checked " : ""
			content += "<input type='checkbox' class='"+clas+"' id='metro-tile-prop-"+prop+"' "+checked+"/>"
		break;
		case "url":
			content += '<label for="metro-tile-prop-url">\
				<input id="metro-tile-prop-'+prop+'" class="metro-url-input-field '+clas+'" type="text" size="32" name="insert_url" value="'+val+'" /><br/>\
				<input id="insert_url_button" type="button" value="'+php_data.ln_insert_url+'" class="button"/>\
				'+php_data.ln_enter_url+'.\
				</label>'
		break;
		default:
		content = ""
	}
	return content
}

jQuery(document).on('change','#metro-tile-prop-widget_type',function(){
	/* Load widget form */
	if(confirm(php_data.ln_resets_widget)){
		jQuery('#metro-wys-popup').find('#metro-widget-args-wrap').empty();
		jQuery.ajax({
			type: 'POST',
			url: php_data.theme_dir+'/admin/ajax/load-widget-form.php', 
			data: {'metro_widget_type':jQuery(this).find('option:selected').text()},
			widgetType : jQuery(this).find('option:selected').text(),
			success: function(data){
					var str = '<input type="hidden" name="metro_widget_type" id="metro_widget_type" value="'+this.widgetType+'"/>'+data
					jQuery('#metro-wys-popup').find('#metro-widget-args-wrap').html(str)
				}
			}	
		)
	}else{
		var currentType = jQuery('#metro-wys-popup').find('#metro-widget-args-wrap').find('input#metro_widget_type').val();
		jQuery(this).val(currentType)
	}
	
})

/* Code from http://stackoverflow.com/questions/1184624/convert-form-data-to-js-object-with-jquery */
jQuery.fn.serializeObject = function(){
    var o = {};
    var a = this.serializeArray();
    jQuery.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

/* Not needed anymore, still here just in case */
function uniqID() {
    var chars = '_0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'.split('');
    var uniqid = '';
    for (var i = 0; i < 10; i++) {
        uniqid += chars[Math.floor(Math.random() * chars.length)];
    }

    // check if this ID is already taken by an element
    if(jQuery("#"+uniqid).length == 0){
        return uniqid;
    }else{
        return uniqID(20)
    }
}
