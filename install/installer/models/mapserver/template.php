<?php

/**
 * MapIgniter
 *
 * An open source GeoCMS application
 *
 * @package		MapIgniter
 * @author		Marco Afonso
 * @copyright	Copyright (c) 2012-2013, Marco Afonso
 * @license		dual license, one of two: Apache v2 or GPL
 * @link		http://mapigniter.com/
 * @since		Version 1.1
 * @filesource
 */

// ------------------------------------------------------------------------

?>
# Map file created by MapIgniter

MAP
    NAME "<?=$mapfile->map->alias?>"
    # Map image size
    MAXSIZE 2600
    SIZE <?=$mapfile->sizex?> <?=$mapfile->sizey . PHP_EOL?>
    UNITS <?=$mapfile->msunits->name . PHP_EOL?>
    CONFIG "MS_ERRORFILE" "<?=$private_data_path?>mapserver.log"
    DEBUG <?=$mapfile->debug . PHP_EOL?>
    
    <?php if ($mapfile->debug != 'off') : ?>
    CONFIG "CPL_DEBUG" "ON"
    CONFIG "PROJ_DEBUG" "ON"
    <?php endif; ?>

    EXTENT <?=$mapfile->extent . PHP_EOL?>
    FONTSET '<?=getitempath($private_data_path, $mapfile->fontset)?>'<?=PHP_EOL?>
    SYMBOLSET '<?=getitempath($private_data_path, $mapfile->symbolset)?>'<?=PHP_EOL?>
    <?php if (!empty($mapfile->projection)) : ?>PROJECTION
    <?php $params = explode(' ', $mapfile->projection); ?>
    <?php foreach ($params as $item) {?>
        '<?=$item?>'
    <?php } ?>
    END<?php endif; ?>

    # Background color for the map canvas -- change as desired
    IMAGECOLOR <?=$mapfile->imagecolor . PHP_EOL?>
    IMAGETYPE <?=$mapfile->imagetype . PHP_EOL?>

    # OUTPUTFORMAT
    #  NAME agg
    #  DRIVER AGG/PNG
    #  IMAGEMODE RGB
    # END

    <?php
    $legends = $mapfile->ownMslegend;
    if (!empty($legends)) :
    $mslegend = reset($legends);
    ?># Legend
    LEGEND
        IMAGECOLOR <?=$mslegend->imagecolor . PHP_EOL?>
        KEYSIZE <?=$mslegend->keysize . PHP_EOL?>
        KEYSPACING <?=$mslegend->keyspacing . PHP_EOL?>
        <?php if (!empty($mslegend->outlinecolor)) : ?>OUTLINECOLOR <?=$mslegend->outlinecolor . PHP_EOL?><?php endif; ?>
        
        POSITION <?=$mslegend->position . PHP_EOL?>
        POSTLABELCACHE <?=$mslegend->postlabelcache . PHP_EOL?>
        STATUS <?=$mslegend->status . PHP_EOL?>
        TEMPLATE '<?=getitempath($private_data_path, $mslegend->template)?>'

        <?php if ($mslabel = $mslegend->mslabel) addLabel($mslabel); ?>
    
    END
    <?php endif; ?>
  
    # Web interface definition. Only the template parameter
    # is required to display a map. See MapServer documentation
    WEB
        # Set IMAGEPATH to the path where MapServer should
        # write its output.
        IMAGEPATH '/tmp/'

        # Set IMAGEURL to the url that points to IMAGEPATH
        # as defined in your web server configuration
        IMAGEURL '/tmp/'

        <?php $msmapfilemd = $mapfile->ownMsmapfilemd;
        if (!empty($msmapfilemd)) : ?>
        # WMS server settings
        METADATA
          <?php foreach ( $msmapfilemd as $metadata) { ?>
          '<?=$metadata->msmetadata->name?>' '<?=$metadata->value?>'
          <?php } ?>
        END
        <?php endif; ?>

        #Scale range at which web interface will operate
        # Template and header/footer settings
        # Only the template parameter is required to display a map. See MapServer documentation
        # TEMPLATE '.'
    END

    <?php foreach ($mapfile->sharedMslayer as $mslayer) { ?>
    LAYER
        NAME '<?=$mslayer->layer->alias?>'
        STATUS <?=$mslayer->status . PHP_EOL?>
        TYPE <?=$mslayer->mslayertype->name . PHP_EOL?>
        <?php if ($mslayer->mslayerconntype->name != 'local') : ?>    CONNECTIONTYPE <?=$mslayer->mslayerconntype->name . PHP_EOL?><?php endif; ?>
        <?php if (!empty($mslayer->connection)) : ?>   CONNECTION "<?=$mslayer->connection?>"<?php endif; ?>
        
        DATA "<?=getitempath($private_data_path, $mslayer->data)?>"<?=PHP_EOL?>
        EXTENT <?=$mslayer->extent . PHP_EOL?>
        <?php if (!empty($mslayer->projection)) : ?>PROJECTION
        <?php $params = explode(' ', $mslayer->projection); ?>
        <?php foreach ($params as $item) {?>
            '<?=$item?>'
        <?php } ?>

        END<?php endif; ?>
        
<?php if (!empty($mslayer->labelitem)) : ?>        LABELITEM "<?=$mslayer->labelitem?>"<?php endif; ?>
<?php if (!empty($mslayer->classitem)) : ?>        CLASSITEM "<?=$mslayer->classitem?>"<?php endif; ?>

        DUMP <?=$mslayer->dump . PHP_EOL?>
        OPACITY <?=$mslayer->opacity . PHP_EOL?>
        TEMPLATE "<?=getitempath($private_data_path, $mslayer->template)?>"
        METADATA
          <?php foreach ($mslayer->ownMslayermd as $metadata) { ?>
          '<?=$metadata->msmetadata->name?>' '<?=$metadata->value?>'
          <?php } ?>
        END
        # PROCESSING "LABEL_NO_CLIP=1"

        <?php foreach ($mslayer->ownMsclass as $msclass) { ?>

        CLASS
           NAME '<?=$msclass->name?>'
           STATUS <?=$msclass->status . PHP_EOL?>
           DEBUG <?=$msclass->debug . PHP_EOL?>
<?php if (!empty($msclass->expression)) : ?>           EXPRESSION <?=$msclass->expression . PHP_EOL?><?php endif; ?>
<?php if (!empty($msclass->maxscaledenom)) : ?>           MAXSCALEDENOM <?=$msclass->maxscaledenom . PHP_EOL?><?php endif; ?>
<?php if (!empty($msclass->minscaledenom)) : ?>           MINSCALEDENOM <?=$msclass->minscaledenom . PHP_EOL?><?php endif; ?>
<?php if (!empty($msclass->text)) : ?>           TEXT <?=$msclass->text . PHP_EOL?><?php endif; ?>
<?php if (!empty($msclass->symbol)) : ?>           SYMBOL '<?=getitempath($private_data_path, $msclass->symbol)?>'<?php endif; ?>
<?php if (!empty($msclass->color)) : ?>           COLOR <?=$msclass->color . PHP_EOL?><?php endif; ?>
<?php if (!empty($msclass->bgcolor)) : ?>           BACKGROUNDCOLOR <?=$msclass->bgcolor . PHP_EOL?><?php endif; ?>
<?php if (!empty($msclass->outlinecolor)) : ?>           OUTLINECOLOR <?=$msclass->outlinecolor . PHP_EOL?><?php endif; ?>
<?php if (!empty($msclass->size)) : ?>           SIZE <?=$msclass->size . PHP_EOL?><?php endif; ?>
           
           <?php foreach ($msclass->sharedMsstyle as $msstyle) mapfile_style($private_data_path, $msstyle); ?>

           <?php
           $labels = $msclass->sharedMslabel;
           if (!empty($labels)) :
           $mslabel = reset($labels);
           mapfile_label($mslabel);
           endif;
           ?>

        END
    <?php } ?>

    END
<?php } ?>

END
