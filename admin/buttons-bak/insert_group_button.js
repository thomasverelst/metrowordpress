(function() {

    tinymce.PluginManager.add('metro_insert_group_mce_button', function( editor, url ) {
        function createGroupShortcode(data){
            var content = "[ group ] "
                content += data['metro-editor-group-title']
                content += " [/ group ]"
            content += "\n\r"
            return content
        }        

        editor.addButton( 'metro_insert_group_mce_button', {
            text: 'Insert group',
            icon: false,
            onclick: function() {
                editor.windowManager.open( {
                    title: 'Insert group',
                    body: [
                        {
                            type: 'textbox',
                            name: 'metro-editor-group-title',
                            label: 'Group title',
                            value: 'A title'
                        }
                    ],
                    onsubmit: function( e ) {
                        editor.insertContent( createGroupShortcode(e.data));
                    }
                });
            }
        });
    });
})();