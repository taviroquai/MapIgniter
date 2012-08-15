<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

    <!-- Basic Page Needs
================================================== -->
    <meta charset="utf-8">
    <title><?=empty($pagetitle) ? 'MapIgniter' : $pagetitle?></title>
    <meta name="description" content="<?=empty($pagedescription) ? 'MapIgniter' : $pagedescription?>">
    <meta name="author" content="<?=empty($pageauthor) ? 'MapIgniter' : $pageauthor?>">

    <!-- Mobile Specific Metas
================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSS
================================================== -->
    <link rel="stylesheet" href="<?=base_url()?>web/css/skeleton/base.css">
    <link rel="stylesheet" href="<?=base_url()?>web/css/skeleton/skeleton.css">
    <link rel="stylesheet" href="<?=base_url()?>web/css/skeleton/layout.css">
    
    <!-- General CSS -->
    <link rel="stylesheet" href="<?=base_url()?>web/css/general.css">
    
    <!-- Public layout CSS -->
    <link rel="stylesheet" href="<?=base_url()?>web/css/public.css">
    
    <!-- CSS helpers -->
    <link rel="stylesheet" href="<?=base_url()?>web/admin/dataexplorer.css" />

    <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Favicons
    ================================================== -->
    <link rel="shortcut icon" href="<?=base_url()?>web/images/skeleton/favicon.ico">
    <link rel="apple-touch-icon" href="<?=base_url()?>web/images/skeleton/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=base_url()?>web/skeleton/images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=base_url()?>web/skeleton/images/apple-touch-icon-114x114.png">
    
    <? foreach ($_slot['_links'] as $_href) { ?>
    <link rel="stylesheet" href="<?=$_href?>" />
    <? } ?>
    
    <script src="<?=base_url()?>web/js/vendor/jqueryui/js/jquery-1.7.2.min.js"></script>
    <script src="<?=base_url()?>web/js/skeleton/tabs.js"></script>
    <script src="<?=base_url()?>web/js/jquery.form.js"></script>
    <script type="text/javascript">
        $.noConflict();
        var base_url = '<?=base_url()?>';
    </script>

    <? foreach ($_slot['_scripts'] as $_src) { ?>
    <script type="text/javascript" src="<?=$_src?>"></script>
    <? } ?>

</head>
<body>
    <div class="header">
        <div class="container">
            <div class="sixteen columns">
                <img src="<?=base_url()?>web/images/milogo_white_131x70.png" alt="MapIgniter Logo" title="MapIgniter" />
            </div>
        </div>
    </div>
    <div class="container">
        <div class="sixteen columns">
            <? if (!empty($pagetitle)) : ?>
            <h1 style="margin-top: 20px"><?=$pagetitle?></h1>
            <? endif; ?>
        </div>
        <div class="eight columns">
            <?=$_slot['slot1']?>
        </div>
        <div class="eight columns">
            <?=$_slot['slot2']?>
        </div>
        <div class="sixteen columns">
            <?=$content?>
        </div>
        <div class="sixteen columns">
            <?=$_slot['slot3']?>
            <span><img style="vertical-align: middle;" src="<?=base_url()?>web/images/milogo_168x35.png" alt="MapIgniter Logo" />&copy; 2012 by Marco Afonso</span>
            This <span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" rel="dct:type">work</span> is licensed under a dual license: <a rel="license" href="http://www.apache.org/licenses/LICENSE-2.0.html">Apache v2</a> or <a rel="license" href="http://www.gnu.org/licenses/gpl.txt">GPL</a>
        </div>
    </div><!-- container -->
    <!-- JS
    ================================================== -->
    
<!-- End Document
================================================== -->
</body>
</html>
