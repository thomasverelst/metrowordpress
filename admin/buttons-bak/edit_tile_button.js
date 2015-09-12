(function() {
    function getCursorPosition(editor) {
        //set a bookmark so we can return to the current position after we reset the content later
        var bm = editor.selection.getBookmark(0);    

        //select the bookmark element
        var selector = "[data-mce-type=bookmark]";
        var bmElements = editor.dom.select(selector);

        //put the cursor in front of that element
        editor.selection.select(bmElements[0]);
        editor.selection.collapse();

        //add in my special span to get the index...
        //we won't be able to use the bookmark element for this because each browser will put id and class attributes in different orders.
        var elementID = ("######cursor######");
        var positionString = '<span id="'+elementID+'"></span>';
        editor.selection.setContent(positionString);

        //get the content with the special span but without the bookmark meta tag
        var content = editor.getContent({format: "html"});
        //find the index of the span we placed earlier
        var index = content.indexOf(positionString);

        //remove my special span from the content
        editor.dom.remove(elementID, false);            

        //move back to the bookmark
        editor.selection.moveToBookmark(bm);

        return index;
    }

    tinymce.PluginManager.add('metro_edit_tile_mce_button', function( editor, url ) {
        function createShortcode(type, data){
            var content = "[ "+type+" ] "
            if(type == "group"){
                content += " "+data['metro-editor-group-title']+" "
            }else{
                for(var arg in tileFunc[type]){
                    //alert(arg + " "+data['metro-editor-'+arg] +" "+  tileFunc[type][arg])
                    if(data['metro-editor-'+arg] !=  tileFunc[type][arg]){ // don't insert if it's the same as default, to avoid the setup page becoming a mess
                        content += "[ "+arg+" = "
                        content += data['metro-editor-'+arg]
                        content += " ] "
                    }
                }  
            }
            content += "[/ "+type+" ] <p>"
            return content
        }    
        function findSelectedType(){

            var ed = tinymce.activeEditor
            var content = ed.getContent()
            var cursorIndex = getCursorPosition(ed)
            /*var beforeCursor = content.substr(0, cursorIndex)
            var afterCursor = content.slice(cursorIndex)
            */


            re = /[^\\]\[\s*(\w+)\s*\](.*?[^\\]?)\[\s*\/\s*\1\s*[^\\]?\]/gi;
            prev_m = null

            while (m = re.exec(content)) { // check all matches
               if(m.index > cursorIndex){ // if match index is greater than cursorindex, we need the match before this one
                break;
               }
               prev_m = m
            }
            return prev_m // [1] = tag, [2] = content, can be null in case no tag is found
/*
            alert(beforeCursor)
            alert(afterCursor)*/
            return "";
        }
        function createEditBody(type, content){

            if(type == "group"){
                //var this_body = [];
                var this_body = [
                {
                    type: 'textbox',
                    name: 'metro-editor-group-title',
                    label: 'Group title',
                    value: content,
                    maxWidth: 400
                }]
                return this_body
            }else if(type in tileFunc){
                content = " "+content // needed for right regex

                //process all attributes
                var args =  {}
                /* Copy all args */
                for(var arg in tileFunc[type]){
                    args[arg] = tileFunc[type][arg]
                }

                var re = /[^\\]\[\s*(.*?)[\s=]+(.*?)\](?!\\)/gi
                content = content.replace(/\/]/g, ']/') // so we can do a lookahead instead of behind (not supported in js)
                content.replace(re, function(m, key, value){ 
                    args[key] = value; 
                });
                
                if( typeof tileAdmin[type] != "undefined"){
                    var this_body = []
                    
                    for(var index in tileAdmin[type]){
                        var temp = tileAdmin[type][index]
                        var this_arg = temp.name.split("-").pop()
                        if(typeof args[this_arg] == "undefined"){
                            temp.value = ""
                        }else{
                            temp.value  = args[this_arg].toString()
                        }
                        this_body[this_body.length] = temp
                    }
                }else{
                    var this_body = [];
                    for(var arg in tileFunc[type]){
                        this_body[this_body.length] = 
                        {
                            type: 'textbox',
                            name: 'metro-editor-'+arg,
                            label: arg,
                            value: args[arg].toString(),
                        }                
                    }
                }

                
/*
                var this_body = [];
                for(var arg in args){
                    this_body[this_body.length] = 
                    {
                        type: 'textbox',
                        name: 'metro-editor-'+arg,
                        label: arg,
                        value: args[arg].toString(),
                    }  
                }*/
                return this_body
            }else{
                return ""
            }

           
        }    

        editor.addButton( 'metro_edit_tile_mce_button', {
            text: 'Edit tile',
            icon: false,
            onclick: function() {
                var elem = findSelectedType()
                if(elem == null){
                     alert("No target element found. Did you position the cursor inside the tags of a tile or group?")
                     return false
                }
                var type = jQuery.trim(elem[1])
                var content = jQuery.trim(elem[2])
                var this_body = createEditBody(type, content)
                editor.windowManager.open( {
                    title: 'Edit '+type,
                    body: this_body,
                    onsubmit: function( e ) {
                        var ed = tinymce.activeEditor
                        var content = ed.getContent()
                        // take out the selected element and insert the new content
                        var newContent = content.substr(0, prev_m.index+1) + createShortcode(type, e.data)+ content.substr(prev_m.index+ prev_m[0].length)
                        ed.setContent(newContent);
                    }
                });
            }
        });
    });
})();