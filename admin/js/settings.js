jQuery(document).ready(function(){
	 jQuery(".choose-color").wpColorPicker();
})

/* Insert image */
jQuery(document).on('click','.upload-image-button',function() {
	// formfield = jQuery(this).parent().children(".upload-image-field").attr('name')
	jQuery(this).parent().children(".upload-image-field").addClass("upload-image-field-active")
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	 return false;
});
window.send_to_editor = function(html) {
	var imgurl = jQuery('img',html).attr('src');
	jQuery('.upload-image-field-active').val(imgurl).removeClass("upload-image-field-active");
	tb_remove();
}

jQuery(document).on("click","#metro-general-bg-type",function(){
	jQuery(".metro-general-bg-type-wrap").hide()

	var val = jQuery("#metro-general-bg-type").val()
	jQuery("#metro-general-bg-"+val).closest("tr").show()
})

jQuery(document).ready(function(){

	// Fix select
	jQuery(".metro-general-bg-type-wrap").hide()
	var val = jQuery("#metro-general-bg-type").val()
	jQuery("#metro-general-bg-"+val).closest("tr.metro-general-bg-type-wrap").show()

})