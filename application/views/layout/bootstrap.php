<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MapIgniter</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=base_url('web/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?=base_url('web/bootstrap/css/dashboard.css')?>" rel="stylesheet">
    <link href="<?=base_url('web/css/general.css')?>" rel="stylesheet">

    <!-- Modules CSS -->
    <?php foreach ($_slot['_links'] as $_href) { ?>
    <link rel="stylesheet" href="<?=$_href?>" />
    <?php } ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?=base_url('web/js/jquery.min.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('web/js/jquery.form.js')?>"></script>

    <script type="text/javascript">
        $.noConflict();
        var base_url = '<?=base_url()?>';
    </script>

    <?php foreach ($_slot['_scripts'] as $_src) { ?>
    <script type="text/javascript" src="<?=$_src?>"></script>
    <?php } ?>

  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?=base_url()?>">MapIgniter</a>
        </div>
        
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <?php if (!empty($_slot['slot2'])) : ?>
                <?=$_slot['slot2']?>
            <?php endif; ?>
          </ul>
        </div>
        <?php if (!empty($_slot['slot5'])) : ?>
            <?=$_slot['slot5']?>
        <?php endif; ?>
      </div>
    </nav>

    <div class="container-fluid fill">
      <div class="row fill">
        <div class="col-sm-3 col-md-2 sidebar">
          <?php if (!empty($_slot['slot3'])) : ?>
          <div id="slot3">
              <div class="content-padding"><?=$_slot['slot3']?></div>
          </div>
          <?php endif; ?>
          <?php if (!empty($content)) : ?>
          <div id="slot-content" class="content-padding">
              <?=$content?>
          </div>
          <?php endif; ?>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main fill">
          
          <div id="slot1" class="fill">
            <?=$_slot['slot1']?>
          </div>
          
        </div>
      </div>
    </div>

    <?php if (!empty($_slot['slot4'])) : ?>
    <div id="slot4" style="position: absolute; bottom: 0; width: 100%; z-index: 1000">
        <?=$_slot['slot4']?>
    </div>
    <?php endif; ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?=base_url('web/bootstrap/js/bootstrap.min.js')?>"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?=base_url('web/bootstrap/js/ie10-viewport-bug-workaround.js')?>"></script>
  </body>
</html>
