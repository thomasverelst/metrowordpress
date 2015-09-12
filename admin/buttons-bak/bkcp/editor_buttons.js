(function() {
    tinymce.create('tinymce.plugins.WPTuts', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {
            ed.addCommand('insert_tile', function() {
                jQuery("#metro-insert-tile").show()
                var selected_text = ed.selection.getContent();
                var return_text = '';
                return_text = '<span class="dropcap">' + selected_text + '</span>';
                ed.execCommand('mceInsertContent', 0, return_text);
            });

           /* ed.addCommand('showrecent', function() {
                var number = prompt("How many posts you want to show ? "), 
                    shortcode;
                if (number !== null) {
                    number = parseInt(number);
                    if (number > 0 && number <= 20) {
                        shortcode = '[recent-posts numbers="' + number + '"/]';
                        ed.execCommand('mceInsertContent', 0, shortcode);
                    } else {
                        alert("The number value is invalid. It should be from 0 to 20.");
                    }
                }      
            });*/
            
            ed.addButton('insert_tile', {
                title : 'Insert tile',
                cmd : 'insert_tile',
                image : url + '/dropcap.png'
            });
/*
            ed.addButton('showrecent', {
                title : 'Add recent posts shortcode',
                cmd : 'showrecent',
                image : url + '/recent.png'
            });*/
        },

        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            return null;
        },

        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                    longname : 'WPTuts Buttons',
                    author : 'Lee',
                    authorurl : 'http://wp.tutsplus.com/author/leepham',
                    infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
                    version : "0.1"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('wptuts', tinymce.plugins.WPTuts);
})();

function metro_editor_insert_text(text){
    window.parent.send_to_editor(text);
    window.parent.tb_remove();
}

function metro_editor_insert_tile(){
    metro_editor_insert_text("jaaa")
}

jQuery(document).ready(function(){
    var 
    content = '<div id ="metro-insert-tile" style="display:none;">'
        content += '<fieldset>'
            content += '<select id="metro-editor-select-type">'
                content += '<option value="none">Select type</option>'
                for(var type in tileFunc){
                content += '<option value="'+type+'">'+type+'</option>'
                }
            content += '</select>'
            content += '<div id="metro-add-tile-content"></div>'
        content += '</fieldset>'
    content += '</div>'
    jQuery("body").append(content)
    jQuery("body>#metro-insert-tile").height(jQuery(document).height())


    jQuery("#metro-editor-select-type").change(function(){
        selected = jQuery("#metro-editor-select-type :selected").val()
        $metro_add_tile_content = jQuery("#metro-add-tile-content")
        if(selected == "none"){
            $metro_add_tile_content.empty().hide()
        }else{
            
            if( selected in tileFunc){

                content = "<table>";

                for(var arg in tileFunc[selected]){
                    content += "<tr><td>"
                    content += arg
                    content += "</td><td>"
                    content += "<input type='text' size='40' value='"+tileFunc[selected][arg]+"'/>"
                    content + "</td></tr>"

                }

                content += "</table>"
                content += '<button id="metro-insert-tile-button" onclick="javascript:metro_insert_tile();">Insert tile</button>'
                
            }else{
                content += "Something went wrong..."
            }
            $metro_add_tile_content.html(content).show();
        }
    })  
})
jQuery(window).resize(function(){
    jQuery("body>#metro-insert-tile").height(jQuery(document).height())
})

/*<fieldset>
            <legend>Add tile</legend>
            <label for="metro-add-tile">Select type:</label>
            <select id="metro-editor-select-type">
                <?php foreach($tile_func as $type=>$tile_args){
                    ?>
                    <option value="none">Select...</option>
                    <option value="<?php echo $type?>"><?php echo $type?></option>
                    <?php
                }?>
            </select>
            */