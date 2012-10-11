<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <title>MapIgniter</title>
        
        <link rel="stylesheet" href="<?=base_url()?>web/css/skeleton/base.css">
        <link rel="stylesheet" href="<?=base_url()?>web/css/skeleton/skeleton.css">
        <link rel="stylesheet" href="<?=base_url()?>web/css/skeleton/layout.css">
        
        <!-- MapIgniter CSS -->
        <link rel="stylesheet" href="<?=base_url()?>web/css/general.css">
        <link rel="stylesheet" href="<?=base_url()?>web/css/publicfullscreen.css">
        <link rel="stylesheet" href="<?=base_url()?>web/css/miwindow.css">
        
        <!-- Third party CSS -->
        <link rel="stylesheet" href="<?=base_url()?>web/js/vendor/jqueryui/css/smoothness/jquery-ui-1.9.0.custom.css">
        
        <!-- Modules CSS -->
        <? foreach ($_slot['_links'] as $_href) { ?>
        <link rel="stylesheet" href="<?=$_href?>" />
        <? } ?>

        <!--[if lt IE 9]>
                <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="<?=base_url()?>web/js/vendor/jqueryui/js/jquery-1.8.2.js"></script>
        <script src="<?=base_url()?>web/js/jquery.form.js"></script>
        <script src="<?=base_url()?>web/js/vendor/jqueryui/js/jquery-ui-1.9.0.custom.min.js"></script>
        <script src="<?=base_url()?>web/fullscreen/fullscreen.js"></script>
        
        <script type="text/javascript">
            $.noConflict();
            var base_url = '<?=base_url()?>';
        </script>

        <? foreach ($_slot['_scripts'] as $_src) { ?>
        <script type="text/javascript" src="<?=$_src?>"></script>
        <? } ?>

    </head>
    <body>

        <div id="slot-header" class="header">
            <img src="<?=base_url()?>web/images/milogo_white_131x70.png" alt="MapIgniter Logo" title="MapIgniter" />
        </div>

        <? if (!empty($content)) : ?>
        <div id="slot-content" class="miwindow">
            <div class="miwindow_mouse_handler">
                <div class="miwindow_title"></div>
            </div>
            <div class="miwindow_clear_float"></div>
            <div class="content-padding"><?=$content?></div>
        </div>
        <? endif; ?>
        
        <div id="slot1">
            <?=$_slot['slot1']?>
        </div>
        
        <? if (!empty($_slot['slot2'])) : ?>
        <div id="slot2">
            <?=$_slot['slot2']?>
        </div>
        <? endif; ?>
        
        <? if (!empty($_slot['slot3'])) : ?>
        <div id="slot3" class="miwindow">
            <div class="miwindow_mouse_handler">
                <div class="miwindow_title"> </div>
            </div>
            <div class="miwindow_clear_float"></div>
            <div class="content-padding"><?=$_slot['slot3']?></div>
        </div>
        <? endif; ?>
        
        <? if (!empty($_slot['slot4'])) : ?>
        <div id="slot4">
            <?=$_slot['slot4']?>
        </div>
        <? endif; ?>
        
        <? if (!empty($_slot['slot5'])) : ?>
        <div id="slot5">
            <?=$_slot['slot5']?>
        </div>
        <? endif; ?>
        
        <? if (!empty($_slot['slot6'])) : ?>
        <div id="slot6" class="miwindow">
            <div class="content-padding"><?=$_slot['slot6']?></div>
        </div>
        <? endif; ?>
          
        </div>
    </body>
</html>
