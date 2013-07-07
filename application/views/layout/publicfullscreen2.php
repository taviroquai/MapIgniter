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
        <link rel="stylesheet" href="<?=base_url()?>web/css/publicfullscreen2.css">
        
        <!-- Modules CSS -->
        <? foreach ($_slot['_links'] as $_href) { ?>
        <link rel="stylesheet" href="<?=$_href?>" />
        <? } ?>

        <!--[if lt IE 9]>
                <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script type="text/javascript" src="<?=base_url()?>web/js/vendor/jqueryui/js/jquery-1.8.2.js"></script>
        <script type="text/javascript" src="<?=base_url()?>web/js/jquery.form.js"></script>
        
        <script type="text/javascript">
            $.noConflict();
            var base_url = '<?=base_url()?>';
        </script>

        <script type="text/javascript" src="web/js/publicfullscreen2.js"></script>
        <? foreach ($_slot['_scripts'] as $_src) { ?>
        <script type="text/javascript" src="<?=$_src?>"></script>
        <? } ?>

    </head>
    <body>

        <div id="slot-header" class="header">
            <img src="<?=base_url()?>web/images/milogo_white_131x70.png" alt="MapIgniter Logo" title="MapIgniter" />
        </div>
        
        <div id="column">
            <a id="column-toggle" style="float: right">
                <img src="web/images/icons/png/32x32/arrow-left.png" alt="Toggle column button" />
            </a>
            <? if (!empty($_slot['slot3'])) : ?>
            <div id="slot3">
                <div class="content-padding"><?=$_slot['slot3']?></div>
            </div>
            <? endif; ?>
            <? if (!empty($content)) : ?>
            <div id="slot-content" class="content-padding">
                <?=$content?>
            </div>
            <? endif; ?>
        </div>

        <div id="slot1">
            <?=$_slot['slot1']?>
        </div>
        
        <? if (!empty($_slot['slot2'])) : ?>
        <div id="slot2">
            <div class="home"><a href="<?=base_url()?>" title="Home">Home</a></div>
            <?=$_slot['slot2']?>
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
        <div id="slot6"><?=$_slot['slot6']?></div>
        <? endif; ?>
          
    </body>
</html>
