<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

    <!-- Basic Page Needs
================================================== -->
    <meta charset="utf-8">
    <title>MapIgniter - Fullscreen Edition Mode</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Mobile Specific Metas
================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSS
================================================== -->
    <link rel="stylesheet" href="<?=base_url()?>web/css/skeleton/base.css">
    <link rel="stylesheet" href="<?=base_url()?>web/css/skeleton/skeleton.css">
    <link rel="stylesheet" href="<?=base_url()?>web/css/skeleton/layout.css">
    <link rel="stylesheet" href="<?=base_url()?>web/js/vendor/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
    
    <!-- General CSS -->
    <link rel="stylesheet" href="<?=base_url()?>web/css/general.css">
    
    <!-- Public layout CSS -->
    <link rel="stylesheet" href="<?=base_url()?>web/css/admin.css">

    <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Favicons
    ================================================== -->
    <link rel="shortcut icon" href="<?=base_url()?>web/images/skeleton/favicon.ico">
    <link rel="apple-touch-icon" href="<?=base_url()?>web/images/skeleton/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=base_url()?>web/skeleton/images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=base_url()?>web/skeleton/images/apple-touch-icon-114x114.png">
    
    <link rel="stylesheet" href="<?=base_url()?>web/admin/admin.css" />
    <link rel="stylesheet" href="<?=base_url()?>web/admin/dataexplorer.css" />
    
    <style type="text/css">
        html, body {
            margin:0;
            width:100%;
            height:100%;
            overflow: hidden;
        }
        #map_editplacemap {
            margin: 0;
            width: 100%;
            height: 100%;
            float: right;
        }
        
        #editwrapper {
            margin:0;
            padding:0;
            width: 100%;
            height: 100%;
        }

        #content {
            float: left;
            width: 380px;
            background-color: white;
            padding: 0 0.5em 0.5em 0.5em;
            max-height: 100%;
            overflow: auto;
        }
        
        #mapmsgs {
            position: absolute;
            top: 78px;
            left: 440px;
            z-index: 20000;
        }
        #mapmsgs p {
            background-color: yellow;
            color: black;
        }
    </style>
    
    <? foreach ($_slot['_links'] as $_href) { ?>
    <link rel="stylesheet" href="<?=$_href?>" />
    <? } ?>
    
    <script type="text/javascript" src="<?=base_url()?>web/js/vendor/jqueryui/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>web/js/vendor/fancybox/source/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="<?=base_url()?>web/js/jquery.form.js"></script>
    <script type="text/javascript" src="<?=base_url()?>web/js/skeleton/tabs.js"></script>
    <script type="text/javascript" src="<?=base_url()?>web/js/sgbeal-colorpicker.jquery.js"></script>
    <script type="text/javascript" src="<?=base_url()?>web/admin/Utils.js"></script>
    
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.6&sensor=false"></script>
    
    <? foreach ($_slot['_scripts'] as $_src) { ?>
    <script type="text/javascript" src="<?=$_src?>"></script>
    <? } ?>

</head>
<body>
    <div class="header">
        <img src="<?=base_url()?>web/images/milogo_131x70.png" alt="MapIgniter Logo" title="MapIgniter" />
    </div>
    <div id="editwrapper">
        <div id="content"><?=$content?></div>
        <div id="map_editplacemap" class="divmap">
            <div id="mapmsgs"></div>
        </div>
    </div>
</body>
</html>
