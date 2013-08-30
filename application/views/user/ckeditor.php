<?php

/**
 * MapIgniter
 *
 * An open source GeoCMS application
 *
 * @package		MapIgniter
 * @author		Marco Afonso
 * @copyright	Copyright (c) 2012, Marco Afonso
 * @license		dual license, one of two: Apache v2 or GPL
 * @link		http://mapigniter.com/
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------
?>
<script type="text/javascript">
    
    jQuery(document).ready(function($){
        // Create a new editor inside the <div id="editor">, setting its value to html
            var config = {
                toolbar : 'Registered',
                uiColor : '#d1d1d1',
                //extraPlugins: 'MediaEmbed',
                filebrowserBrowseUrl:       base_url+'user/userdataexplorer',
                filebrowserUploadUrl:       base_url+'user/userdataexplorer',
                filebrowserImageBrowseUrl:  base_url+'user/userdataexplorer',
                filebrowserImageUploadUrl:  base_url+'user/userdataexplorer',
                filebrowserWindowWidth:     800,
                filebrowserWindowHeight:    600
            };
            config.toolbar_Registered =
            [
                    { name: 'document', items : [ 'Source','-','NewPage' ] },
                    { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
                    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                    { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
                    { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
                    { name: 'insert', items : [ 
                            'Image',
                            //'MediaEmbed',
                            'Iframe',
                            'HorizontalRule',
                            'SpecialChar',
                            'PageBreak' ] 
                    }
            ];
            $('textarea.wysiwyg').ckeditor(config);
    });
    
</script>