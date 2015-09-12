(function() {

    tinymce.PluginManager.add('metro_insert_tile_mce_button', function( editor, url ) {
        function createTileShortcode(type, data){
            var content = "[ "+type+" ] "

            for(var arg in tileFunc[type]){
                if(data['metro-editor-'+arg] !=  tileFunc[type][arg]){ // don't insert if it's the same as default, to avoid the setup page becoming a mess
                    content += "[ "+arg+" = "
                    content += data['metro-editor-'+arg]
                    content += " ] "
                }
            }
            content += "[/ "+type+" ] <p>"
            return content
        }        


        var insert_tile_dropdown = []

        for(var type in tileFunc){
            if( typeof tileAdmin[type] != "undefined"){
                var this_body = []
                for(var index in tileAdmin[type]){
                    var temp = tileAdmin[type][index]
                    var this_arg = temp.name.split("-").pop()
                    if(typeof tileFunc[type][arg] == "undefined"){
                        temp.value = ""
                    }else{
                        temp.value  = tileFunc[type][arg].toString()
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
                        value: tileFunc[type][arg].toString(),
                        maxWidth: 400
                    }                
                }
            }

            insert_tile_dropdown[insert_tile_dropdown.length] =
            {
                text: type,
                onclick: function() {
                    editor.windowManager.open( {
                        title: 'Insert tile '+type,
                        body: this_body,
                        onsubmit: function( e ) {
                            editor.insertContent( createTileShortcode(type, e.data));
                            //editor.insertContent( '[random_shortcode textbox="' + e.data.textboxName + '" multiline="' + e.data.multilineName + '" listbox="' + e.data.listboxName + '"]');
                        }
                    });
                }
            }
        }

        editor.addButton( 'metro_insert_tile_mce_button', {
            text: 'Insert tile',
            icon: false,
            type: 'menubutton',
            menu: insert_tile_dropdown
        });
    });
})();