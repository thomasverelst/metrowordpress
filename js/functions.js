!window.jQuery && document.write('<script src="'+php_data.template_dir+'/js/jquery1.11.1.js"><\/script>'); // Include jquery if needed

jQuery.fx.interval = 25;

/* Some general and frequently used functions */
String.prototype.stripSpaces = function(){ return this.replace(/\s/g,"-")}

/* Check touch device */
function isTouchDevice() {
  return 'ontouchstart' in window // works on most browsers 
      || 'onmsgesturechange' in window; // works on ie10
}

/* ... */
function isset(v){
	return typeof v != "undefined"
}

function elementExists(n){
	return $(n).length > 0
}


/* Img fill */
$.fn.metro_fill = function(){
	if(this.prop('tagName') != 'IMG'){
		return
	}
	var $parent = this.parent()
	var containerAspect = $parent.width()/$parent.height()
	var thisAspect = this.width()/this.height()
	if(thisAspect < containerAspect){
		this.removeClass('wide tall').addClass('tall')
	}else{
		this.removeClass('wide tall').addClass('wide')
	}
}