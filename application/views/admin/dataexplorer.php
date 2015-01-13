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

//	count elements in array
$total	= count($list);
$action = base_url().$ctrlpath;
?>
<h2>File Explorer</h2>
<ul class="tabs">
    <li><a class="active" href="#currentdir">Current Directory</a></li>
    <li><a href="#newdir">Create Directory</a></li>
    <li><a href="#newfile">Create File</a></li>
    <li><a href="#selected">Selection</a></li>
</ul>
<ul class="tabs-content">
  <li class="active" id="currentdir">
<h3>Current Directory</h3>
<?php if (!empty($error)) : ?>
    <p class="error"><?=$error?></p>
<?php endif; ?>
<a href="<?=$action?>?security=private"><img src="<?=base_url()?>web/images/icons/png/24x24/home_locked.png" alt="Private data directory" title="Private data directory" /></a>
<a href="<?=$action?>?security=public"><img src="<?=base_url()?>web/images/icons/png/24x24/home.png" alt="Public data directory" title="Public data directory" /></a>
<form method="post" action="<?=$action?>/selected?list=<?=$dir?>">
<table id="dataexplorer1" class="dataexplorer">
    <col class="dxname"></col>
    <col class="dxtype"></col> 
    <col class="dxsize"></col>  
    <col class="dxowner"></col>  
    <tr><th colspan="4"><?=$total-2?> items</th></tr>
    <tr><th>File</th><th class="dxtype">Type</th><th class="dxsize">Size</th><th class="dxowner">Owner</th></tr>
    <?php if (!empty($dir)) : ?>
    <tr>
        <td colspan="4">
            <a href="<?=$action?>?list=<?=$back?>">
                <img src="<?=base_url()?>web/images/icons/png/24x24/arrow-alt-left.png" alt="up" title="Parent Dierctory" />
                <span><?=$dir?></span>
            </a>
        </td>
    </tr>
    <?php endif; ?>
    <?php
    for($index=0; $index < $total; $index++) {
        if (substr($list[$index]['name'], 0, 1) == ".") continue;
        ?>
        <tr>
            <td>
                <input type="checkbox" name="selected[]" <?=in_array($dir.$list[$index]['name'], $selected) ? 'checked="checked"' : ''?> value="<?=$dir.$list[$index]['name']?>" />
                <?php if (filetype($base.$dir.$list[$index]['name']) == 'dir') : ?>
                <a href="<?=$action?>?list=<?=$dir.$list[$index]['name']?>/&back=<?=$dir?>">
                    <img src="<?=base_url()?>web/images/icons/png/16x16/documents.png" alt="enter" title="Enter" />
                    <span><?=$list[$index]['name']?>/</span>
                </a>
                <?php else :?>
                    <?php if ($security == 'public') : ?>
                    <a href="<?=base_url().'web/data/'.$dir.$list[$index]['name']?>">
                        <img src="<?=base_url()?>web/images/icons/png/16x16/document.png" alt="download" title="Download" />
                        <span><?=$list[$index]['name']?></span>
                    </a>
                    <?php else : ?>
                    <a href="<?=$action?>/dl?dir=<?=$dir?>&file=<?=$list[$index]['name']?>">
                        <img src="<?=base_url()?>web/images/icons/png/16x16/document.png" alt="download" title="Download" />
                        <span><?=$list[$index]['name']?></span>
                    </a>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
            <td><?php switch(filetype($base.$dir.$list[$index]['name'])) {
                    case 'file':
                        $mime = exec("file -i -b ".$base.$dir.$list[$index]['name']);
                        $type_info = substr($mime, 0, stripos($mime, ';'));
                        if (strstr($type_info, 'image')) {
                            if ($security == 'public') {
                                $type_info = '<a class="preview" href="'.base_url().'web/data/'.$dir.$list[$index]['name'].'">'.$type_info.'</a>';
                            }
                            else {
                                $type_info = '<a class="preview" href="'.$action.'/dl?dir='.$dir.'&file='.$list[$index]['name'].'">'.$type_info.'</a>';
                            }
                        }
                        break;
                    default: $type_info = 'Directory';
                }?>
                <?=$type_info?>
            </td>
            <td><?=filesize($base.$dir.$list[$index]['name'])?></td>
            <td><?php $sys = $list[$index]['sys'] ?>
                <?=!empty($sys) ? $sys->fetchAs('account')->owner->username : ''?>
            </td>
        </tr>
    <?php } ?>
</table>
    <label>Seleção</label>
    <select name="action">
        <option value="save">Save selection</option>
        <option value="delete">Delete!</option>
    </select>
    <button type="submit">Execute</button>
</form>
  </li>
  <li id="newdir">
<h3>New Directory</h3>
<?php if (!empty($error)) : ?>
    <p class="error"><?=$error?></p>
<?php endif; ?>
<form action="<?=$action?>/createdir?list=<?=$dir?>" method="post">
    <label>Name</label>
    <input type="text" name="name" value="<?=$dir?>" />
    <button type="submit">Create</button>
</form>
  </li>
  <li id="newfile">
<h3>File Upload</h3>
<?php if (!empty($error)) : ?>
    <p class="error"><?=$error?></p>
<?php endif; ?>
<?php if (!empty($upload_data)) : ?>
    <p class="error"><?=$upload_data['file_name']?></p>
<?php endif; ?>

<?php echo form_open_multipart($action.'/upload?list='.$dir);?>
    <label>Choose</label>
    <input type="file" name="userfile" size="20" />
    <label>Overwrite</label>
    <label for="overwrite_opt1">
        <input type="radio" name="overwrite" id="overwrite_opt1" value="1" />
        <span>Yes</span>
    </label>
    <label for="overwrite_opt2">
        <input type="radio" name="overwrite" id="overwrite_opt1" checked="checked" value="0" />
        <span>No</span>
    </label>
    <input type="hidden" name="dir" value="<?=$dir?>" />
    <button type="submit">Start Upload</button>
</form>
  </li>
    <li id="selected">
<h3>Selection</h3>
<?php if (empty($selected)) : ?>
    <p>There are no selected items.</p>
<?php else :
$total = count($selected);    
$list = $selected;
if (!empty($error)) : ?>
    <p class="error"><?=$error?></p>
<?php endif; ?>
<form method="post" action="<?=$action?>/selected?list=<?=$dir?>">
<table id="dataexplorer2" class="dataexplorer">
    <col class="dxname"></col>
    <col class="dxtype"></col> 
    <col class="dxsize"></col>  
    <tr><th colspan="3"><?=$total?> items</th></tr>
    <tr><th>File</th><th class="dxtype">Type</th><th class="dxsize">Size</th></tr>
    <?php for($index=0; $index < $total; $index++) { ?>
        <tr>
            <td>
                <input type="checkbox" name="selected[]" value="<?=$list[$index]?>" />
                <?php if (filetype($base.$list[$index]) == 'dir') : ?>
                <img src="<?=base_url()?>web/images/icons/png/16x16/documents.png" alt="enter" title="Enter" /><span><?=$list[$index]?></span>
                <?php else :?>
                <img src="<?=base_url()?>web/images/icons/png/16x16/document.png" alt="download" title="Download" /><span><?=$list[$index]?></span>
                <?php endif; ?>
            </td>
            <td><?php switch(filetype($base.$list[$index])) {
                    case 'file': echo 'File'; break;
                    default: echo 'Directory';
                }?></td>
            <td><?=filesize($base.$list[$index])?></td>
        </tr>
    <?php } ?>
</table>
    <label>Seleção</label>
    <select name="action">
        <option value="unselect">Unselect</option>
    </select>
    <button type="submit">Execute</button>
</form>
    <?php endif; ?>
    </li>
</ul>
<script type="text/javascript">
    jQuery(document).ready(function($){
        /* CONFIG */
        xOffset = 10;
        yOffset = 30;

        // these 2 variable determine popup's distance from the cursor
        // you might want to adjust to get the right result

        /* END CONFIG */
        $("a.preview").hover(function(e){
            this.t = this.title;
            this.title = "";	
            var c = (this.t != "") ? "<br/>" + this.t : "";
            $("body").append("<p id='explorer_preview'><img src='"+ this.href +"' alt='Image preview' />"+ c +"</p>");
            $("#explorer_preview")
                    .css("top",(e.pageY - xOffset) + "px")
                    .css("left",(e.pageX + yOffset) + "px")
                    .fadeIn("fast");						
        },
        function(){
            this.title = this.t;	
            $("#explorer_preview").remove();
        });	
        $("a.preview").mousemove(function(e){
            $("#explorer_preview")
                .css("top",(e.pageY - xOffset) + "px")
                .css("left",(e.pageX + yOffset) + "px");
        });
    });
</script>