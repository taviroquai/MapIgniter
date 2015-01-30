<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Array Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/array_helper.html
 */

// ------------------------------------------------------------------------

if ( ! function_exists('mapfile_map'))
{
    function mapfile_map($mapfile, $private_data_path) {
    
        ob_start();
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
    FONTSET '<?=mapfile_getitempath($mapfile->fontset, $private_data_path)?>'<?=PHP_EOL?>
    SYMBOLSET '<?=mapfile_getitempath($mapfile->symbolset, $private_data_path)?>'<?=PHP_EOL?>
    
    <?php if (!empty($mapfile->projection)) : ?>
    
    PROJECTION
        <?php
    $params = explode(' ', $mapfile->projection);
    foreach ($params as $item) {?>'<?=$item?>'<?=PHP_EOL?><?php } ?>
    END
    <?php endif; ?>
    
    # Background color for the map canvas -- change as desired
    IMAGECOLOR <?=$mapfile->imagecolor . PHP_EOL?>
    IMAGETYPE <?=$mapfile->imagetype . PHP_EOL?>
    
    <?php
    $legends = $mapfile->ownMslegend;
    if (!empty($legends)) :
        $mslegend = reset($legends);
    ?><?=mapfile_legend($mslegend, $private_data_path)?>
    <?php endif; ?>
    
    WEB
        # Set IMAGEPATH to the path where MapServer should
        # write its output.
        IMAGEPATH '/tmp/'

        # Set IMAGEURL to the url that points to IMAGEPATH
        # as defined in your web server configuration
        IMAGEURL '/tmp/'

        <?php $msmapfilemd = $mapfile->ownMsmapfilemd;
        if (!empty($msmapfilemd)) : ?>
        
        METADATA
            <?php foreach ( $msmapfilemd as $metadata) { ?>'<?=$metadata->msmetadata->name?>' '<?=$metadata->value?>'<?=PHP_EOL?>
            <?php } ?>
        
        END
        <?php endif; ?>

    END

    <?php foreach ($mapfile->sharedMslayer as $mslayer) { ?>
    <?=mapfile_layer($mslayer, $private_data_path);?>
    <?php } ?>

END
        <?php
        return ob_get_clean();
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('mapfile_legend'))
{
    function mapfile_legend($mslegend, $private_data_path) {
    
        ob_start();
        ?>
LEGEND
        IMAGECOLOR <?=$mslegend->imagecolor . PHP_EOL?>
        KEYSIZE <?=$mslegend->keysize . PHP_EOL?>
        KEYSPACING <?=$mslegend->keyspacing . PHP_EOL?>
        <?php if (!empty($mslegend->outlinecolor)) : ?>OUTLINECOLOR <?=$mslegend->outlinecolor . PHP_EOL?><?php endif; ?>
        
        POSITION <?=$mslegend->position . PHP_EOL?>
        POSTLABELCACHE <?=$mslegend->postlabelcache . PHP_EOL?>
        STATUS <?=$mslegend->status . PHP_EOL?>
        TEMPLATE '<?=mapfile_getitempath($mslegend->template, $private_data_path)?>'

        <?php if ($mslabel = $mslegend->mslabel) mapfile_label($mslabel, $private_data_path); ?>
        
    END
        <?php
        return ob_get_clean();
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('mapfile_layer'))
{
    function mapfile_layer($mslayer, $private_data_path) {
    
        ob_start();
        ?>
    
    LAYER
        NAME '<?=$mslayer->layer->alias?>'
        STATUS <?=$mslayer->status . PHP_EOL?>
        TYPE <?=$mslayer->mslayertype->name . PHP_EOL?>
        <?php if ($mslayer->mslayerconntype->name != 'local') : ?>    CONNECTIONTYPE <?=$mslayer->mslayerconntype->name . PHP_EOL?><?php endif; ?>
        <?php if (!empty($mslayer->connection)) : ?>    CONNECTION "<?=$mslayer->connection?>"<?php endif; ?>
        
        DATA "<?=mapfile_getitempath($mslayer->data, $private_data_path)?>"<?=PHP_EOL?>

        <?php if (!empty($mslayer->extent)) : ?>
        EXTENT <?=$mslayer->extent . PHP_EOL?>
        <?php endif; ?>

        <?php if (!empty($mslayer->projection)) : ?>PROJECTION
        <?php $params = explode(' ', $mslayer->projection); ?>
        <?php foreach ($params as $item) {?>
            '<?=$item?>'
        <?php } ?>

        END<?php endif; ?>
        
        <?php if (!empty($mslayer->labelitem)) : ?>LABELITEM "<?=$mslayer->labelitem?>"<?php endif; ?>
        <?php if (!empty($mslayer->classitem)) : ?>CLASSITEM "<?=$mslayer->classitem?>"<?php endif; ?>

        DUMP <?=$mslayer->dump . PHP_EOL?>
        OPACITY <?=$mslayer->opacity . PHP_EOL?>
        TEMPLATE "<?=mapfile_getitempath($mslayer->template, $private_data_path)?>"
        METADATA
            <?php foreach ($mslayer->ownMslayermd as $metadata) { ?>'<?=$metadata->msmetadata->name?>' '<?=$metadata->value?>'<?=PHP_EOL?>
            <?php } ?>
            
        END
        # PROCESSING "LABEL_NO_CLIP=1"

        <?php foreach ($mslayer->ownMsclass as $msclass) { ?>
        <?=mapfile_class($msclass, $private_data_path);?>
        <?php } ?>
        
    END
        <?php
        return ob_get_clean();
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('mapfile_class'))
{
    function mapfile_class($msclass, $private_data_path) {
    
        ob_start();
        ?>
        
        CLASS
            NAME '<?=$msclass->name?>'
            STATUS <?=$msclass->status . PHP_EOL?>
            DEBUG <?=$msclass->debug . PHP_EOL?>
            <?php if (!empty($msclass->expression)) : ?>EXPRESSION <?=$msclass->expression . PHP_EOL?><?php endif; ?>
            <?php if (!empty($msclass->maxscaledenom)) : ?>MAXSCALEDENOM <?=$msclass->maxscaledenom . PHP_EOL?><?php endif; ?>
            <?php if (!empty($msclass->minscaledenom)) : ?>MINSCALEDENOM <?=$msclass->minscaledenom . PHP_EOL?><?php endif; ?>
            <?php if (!empty($msclass->text)) : ?>TEXT <?=$msclass->text . PHP_EOL?><?php endif; ?>
            <?php if (!empty($msclass->symbol)) : ?>SYMBOL '<?=mapfile_getitempath($msclass->symbol, $private_data_path)?>'<?php endif; ?>
            <?php if (!empty($msclass->color)) : ?>COLOR <?=$msclass->color . PHP_EOL?><?php endif; ?>
            <?php if (!empty($msclass->bgcolor)) : ?>BACKGROUNDCOLOR <?=$msclass->bgcolor . PHP_EOL?><?php endif; ?>
            <?php if (!empty($msclass->outlinecolor)) : ?>OUTLINECOLOR <?=$msclass->outlinecolor . PHP_EOL?><?php endif; ?>
            <?php if (!empty($msclass->size)) : ?>SIZE <?=$msclass->size . PHP_EOL?><?php endif; ?>
           
            <?php foreach ($msclass->sharedMsstyle as $msstyle) {?>
            
            <?=mapfile_style($msstyle, $private_data_path); ?>
            
            <?php } ?>

            <?php
            $labels = $msclass->sharedMslabel;
            if (!empty($labels)) :
            $mslabel = reset($labels);?>
            
            <?=mapfile_label($mslabel, $private_data_path)?>
            
            <?php endif;?>

        END
        <?php
        return ob_get_clean();
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('mapfile_label'))
{
    function mapfile_label($mslabel, $private_data_path) {
    
        ob_start();
        ?>
            
            LABEL
                ALIGN <?=$mslabel->align . PHP_EOL?>
                ANGLE <?=$mslabel->angle . PHP_EOL?>
                ANTIALIAS <?=$mslabel->antialias . PHP_EOL?>
                BUFFER <?=$mslabel->buffer . PHP_EOL?>
                COLOR <?=$mslabel->color . PHP_EOL?>
                ENCODING '<?=$mslabel->encoding?>'
                FONT '<?=$mslabel->font?>'
                FORCE <?=$mslabel->force . PHP_EOL?>
                MAXLENGTH <?=$mslabel->maxlength . PHP_EOL?>
                
                # Incompatible with 
                # MAXOVERLAPANGLE <?=$mslabel->maxoverlapangle . PHP_EOL?>
                MAXSIZE <?=$mslabel->maxsize . PHP_EOL?>
                MINDISTANCE <?=$mslabel->mindistance . PHP_EOL?>
                MINFEATURESIZE <?=$mslabel->minfeaturesize . PHP_EOL?>
                MINSIZE <?=$mslabel->minsize . PHP_EOL?>
                OFFSET <?=$mslabel->offset . PHP_EOL?>
                <?php if (!empty($mslabel->outlinecolor)) :?>OUTLINECOLOR <?=$mslabel->outlinecolor . PHP_EOL?>
                OUTLINEWIDTH <?=$mslabel->outlinewidth . PHP_EOL?><?php endif; ?>
                
                PARTIALS <?=$mslabel->partials . PHP_EOL?>
                POSITION <?=$mslabel->position . PHP_EOL?>
                PRIORITY <?=$mslabel->priority . PHP_EOL?>
                <?php if ($mslabel->repeatdistance) :?>REPEATDISTANCE <?=$mslabel->repeatdistance?><?php endif; ?><?=PHP_EOL?>
                <?php if ($mslabel->shadowcolor) :?>SHADOWCOLOR '<?=$mslabel->shadowcolor?>'<?php endif; ?>
                <?php if ($mslabel->shadowsize) :?>SHADOWSIZE <?=$mslabel->shadowsize?><?php endif; ?><?=PHP_EOL?>
                SIZE <?=$mslabel->size . PHP_EOL?>
                TYPE <?=$mslabel->type . PHP_EOL?>
                <?php if ($mslabel->wrap) :?>WRAP '<?=$mslabel->wrap?>'<?php endif; ?>

                <?php if ($msstyle = $mslabel->style) mapfile_style($msstyle, $private_data_path); ?>

            END
        <?php
        return ob_get_clean();
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('mapfile_style'))
{
    function mapfile_style($msstyle, $private_data_path) {
        
        ob_start();
        ?>STYLE
                ANGLE <?=$msstyle->angle . PHP_EOL?>
                ANTIALIAS <?=$msstyle->antialias . PHP_EOL?>
                BACKGROUNDCOLOR <?=$msstyle->bgcolor . PHP_EOL?>
                COLOR <?=$msstyle->color . PHP_EOL?>
                GAP <?=$msstyle->gap . PHP_EOL?>
                <?php if ($msstyle->geomtransform) :?>GEOMTRANSFORM <?=$msstyle->geomtransform . PHP_EOL?><?php endif; ?>
                LINECAP <?=$msstyle->linecap . PHP_EOL?>
                LINEJOIN <?=$msstyle->linejoin . PHP_EOL?>
                LINEJOINMAXSIZE <?=$msstyle->linejoinmaxsize . PHP_EOL?>
                MAXSIZE <?=$msstyle->maxsize . PHP_EOL?>
                MAXWIDTH <?=$msstyle->maxwidth . PHP_EOL?>
                MINSIZE <?=$msstyle->minsize . PHP_EOL?>
                MINWIDTH <?=$msstyle->minwidth . PHP_EOL?>
                OFFSET <?=$msstyle->offset . PHP_EOL?>
                OPACITY <?=$msstyle->opacity . PHP_EOL?>
                OUTLINECOLOR <?=$msstyle->outlinecolor . PHP_EOL?>
                PATTERN <?=$msstyle->pattern?> END
                SIZE <?=$msstyle->size . PHP_EOL?>
                <?php if (!empty($msstyle->symbol)) : ?>SYMBOL '<?=mapfile_getitempath($msstyle->symbol, $private_data_path)?>'<?php endif; ?>
                
                WIDTH <?=$msstyle->width . PHP_EOL?>
            END
        <?php
        return ob_get_clean();
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('mapfile_getitempath'))
{   
    function mapfile_getitempath($item, $private_data_path) {
        $fullpath = realpath($private_data_path.$item);
        if (file_exists($fullpath)) {
            return $fullpath;
        } else {
            return $item;
        }
    }

}
