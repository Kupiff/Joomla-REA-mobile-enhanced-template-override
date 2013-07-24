<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     1.0: default.php 2013-05-30
 * @copyright   Copyright (C) 2013 webnology gmbh  www.webnology.ch . All rights reserved.
 * @copyright   Copyright (C) 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * This template override for the Joomla Estate Agency is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses.
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::stylesheet('media/com_jea/css/jea.css');


$app = JFactory::getApplication();

JHTML::script('templates/'.$app->getTemplate().'/html/com_jea/js/jquery.rs.modal.js');
JHTML::script('templates/'.$app->getTemplate().'/html/com_jea/js/jquery.flexslider-min.js');
JHTML::stylesheet('templates/'.$app->getTemplate().'/html/com_jea/css/simplegrid.css');
JHTML::stylesheet('templates/'.$app->getTemplate().'/html/com_jea/css/modal.css');
JHTML::stylesheet('templates/'.$app->getTemplate().'/html/com_jea/css/flexslider.css');

JPluginHelper::importPlugin('jea');


?>
<div>
    <h2 style="color: #4488BB"><?php echo $this->page_title ?></h2>
</div>
<div id="container" >
  <section class="slider">
    <div class="flexslider" id='flexslider'>
      <ul class="slides">

        <?php  

            $first = true;
            foreach($this->row->images as $v){
                if($first === true){
                    echo '<li data-thumb="' ; echo $v->minURL; echo '">'; 
                    echo '<p id="flex-caption" class="flex-caption">Loading Images....</p>';
                    if($this->row->slogan){
                        echo '<p id="flex-slogan" class="flex-slogan">'.$this->row->slogan.'</p>';
                    }               
                    echo '<img src="'.$v->URL.'" /></li>';
                    
                    $first = false;
                    }
                else{
                    echo '<li data-thumb="' ; echo $v->minURL; echo '">';                  
                    echo '<img src="'.$v->URL.'" /></li>';                    
                }
            }
        ?>

      </ul>
    </div>
  </section>
  <section class="slider">
    <div class="flexslider" id='carousel'>
      <ul class="slides">

        <?php               
         foreach($this->row->images as $v){
            echo '<li data-thumb="' ; echo $v->minURL; echo '">'; 
            echo '<p id="flex-caption" class="flex-caption">Loading Images....</p>';
            echo '<img src="'.$v->minURL.'" /></li>';
         }
        ?>

      </ul>
    </div>
  </section>    
</div>     
<script type="text/javascript">
    jQuery( document ).ready(function() {

            jQuery('#carousel').flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                itemWidth: 90,
                itemMargin: 1,
                //smoothHeight:true,
                asNavFor: '#flexslider'
            });
             jQuery('#flexslider').flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                itemMargin: 5,
                smoothHeight:true,
                sync: "#carousel",
                start: function(){
                    jQuery('.flex-caption').remove();
                }
             });
    });
</script>



<div id="container-grid" class="box-content grid grid-pad" style="padding-right: 0px;">
    <div class="col-1-2" style="font-size: 9pt;">
<dl class="separator">
    <dt><?php echo JText::_('COM_JEA_REF')?></dt>
    <dd style="margin-left: 161px;"><?php echo $this->escape($this->row->ref) ?></dd>
    <?php if ($this->row->address || $this->row->zip_code || $this->row->town ): ?>
        <dt><?php echo JText::_('COM_JEA_FIELD_ADDRESS_LABEL') ?></dt>
        <dd style="margin-left: 161px;"><?php echo $this->escape($this->row->address ) ?></dd>
            <?php if ($this->row->zip_code) echo '<dd style="margin-left: 161px;">'.$this->escape($this->row->zip_code ).'</dd>' ?>
            <?php if ($this->row->town) echo '<dd style="margin-left: 161px;">'.$this->escape($this->row->town ).'</dd>' ?>
    <?php endif ?>
    <?php if ($this->row->area) :?>
    <dt><?php echo JText::_('COM_JEA_FIELD_AREA_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo$this->escape( $this->row->area ) ?></dd>
    <?php endif  ?>
    <?php if (!empty($this->row->amenities)): ?>
    <dt><?php echo JText::_('COM_JEA_AMENITIES')?></dt>
    <dd style="margin-left: 161px;"><?php echo JHtml::_('amenities.bindList', $this->row->amenities, 'ul') ?></dd>
    <?php endif ?>
    <?php if (intval($this->row->availability)): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_PROPERTY_AVAILABILITY_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo JHTML::_('date',  $this->row->availability, JText::_('DATE_FORMAT_LC3') ) ?></dd>
    <?php endif  ?>
    
    
    <dt><strong><?php echo $this->row->transaction_type == 'RENTING' ? JText::_('COM_JEA_FIELD_PRICE_RENT_LABEL') :  JText::_('COM_JEA_FIELD_PRICE_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo JHtml::_('utility.formatPrice', (float) $this->row->price, JText::_('COM_JEA_CONSULT_US')) ?></strong></dd>
    <?php if ($this->row->transaction_type == 'RENTING' && (float)$this->row->price != 0.0): ?>
        <dd style="margin-left: 161px;"><?php echo JText::_('COM_JEA_PRICE_PER_FREQUENCY_'. $this->row->rate_frequency) ?></dd>
    <?php endif ?>
    <?php if ((float)$this->row->charges > 0): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_CHARGES_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo JHtml::_('utility.formatPrice', (float) $this->row->charges) ?></dd>
    <?php endif  ?>
    <?php if ($this->row->transaction_type == 'RENTING' &&  (float) $this->row->deposit > 0 ): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_DEPOSIT_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo JHtml::_('utility.formatPrice', (float) $this->row->deposit) ?></dd>
    <?php endif  ?>
    <?php if ((float)$this->row->fees > 0): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_FEES_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo JHtml::_('utility.formatPrice', (float) $this->row->fees) ?></dd>
    <?php endif  ?>
    <?php if ($this->row->condition): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_CONDITION_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo $this->escape($this->row->condition) ?></dd>
    <?php endif  ?>
    <?php if ($this->row->living_space): ?>
    <dt><?php echo  JText::_( 'COM_JEA_FIELD_LIVING_SPACE_LABEL' ) ?></dt>
    <dd style="margin-left: 161px;"><?php echo JHtml::_('utility.formatSurface', (float) $this->row->living_space ) ?></dd>
    <?php endif ?>
    <?php if ($this->row->land_space): ?>
    <dt><?php echo  JText::_( 'COM_JEA_FIELD_LAND_SPACE_LABEL' ) ?></dt>
    <dd style="margin-left: 161px;"><?php echo JHtml::_('utility.formatSurface', (float) $this->row->land_space ) ?></dd>
    <?php endif ?>
    <?php if ($this->row->rooms): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_NUMBER_OF_ROOMS_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo $this->row->rooms ?></dd>  
    <?php endif  ?>
    <?php if ($this->row->bedrooms): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_NUMBER_OF_BEDROOMS_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo $this->row->bedrooms ?></dd>
    <?php endif  ?>
    <?php if ( $this->row->bathrooms ): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_NUMBER_OF_BATHROOMS_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo $this->row->bathrooms ?></dd>
    <?php endif  ?>
    <?php if ($this->row->toilets): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_NUMBER_OF_TOILETS_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo $this->row->toilets ?></dd>
    <?php endif  ?>
    <?php if ($this->row->orientation != '0'): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_ORIENTATION_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php
     switch ($this->row->orientation) {
         case 'N':
             echo JText::_('COM_JEA_OPTION_NORTH');
             break;
         case 'NW':
             echo JText::_('COM_JEA_OPTION_NORTH_WEST');
             break;
         case 'NE':
             echo JText::_('COM_JEA_OPTION_NORTH_EAST');
             break;
         case 'E':
             echo JText::_('COM_JEA_OPTION_EAST');
             break;
         case 'W':
             echo JText::_('COM_JEA_OPTION_WEST');
             break;
         case 'S':
             echo JText::_('COM_JEA_OPTION_SOUTH');
             break;
         case 'SW':
             echo JText::_('COM_JEA_OPTION_SOUTH_WEST');
             break;
         case 'SE':
             echo JText::_('COM_JEA_OPTION_SOUTH_EAST');
             break;
     }
    ?></dd>  
    <?php endif ?>
    <?php if ( $this->row->floors_number ): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_FLOORS_NUMBER_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo $this->row->floors_number ?></dd>
    <?php endif  ?>     
    <?php if ($this->row->floor): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_FLOOR_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo $this->row->floor ?></dd>
    <?php endif  ?>   
    <?php if ( $this->row->heating_type_name ): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_HEATINGTYPE_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo $this->escape( $this->row->heating_type_name) ?></dd>
    <?php endif  ?>
    <?php if ( $this->row->hot_water_type_name ): ?>
    <dt><?php echo JText::_('COM_JEA_FIELD_HOTWATERTYPE_LABEL') ?></dt>
    <dd style="margin-left: 161px;"><?php echo $this->escape( $this->row->hot_water_type_name) ?></dd>
    <?php endif  ?>  
</dl>	
    </div>

    
    
<div class="col-1-2">    
<?php if ( $this->params->get('show_googlemap') ): ?>  

    <?php echo $this->loadTemplate('googlemap') ?>  
    

<?php endif ?> 
</div>
    
</div>
<div class="box-content">
    <h3><?php echo JText::_('COM_JEA_DESCRIPTION') ?>:</h3>
    <?php echo $this->row->description ?>
</div> 





<?php if ( $this->params->get('show_contactform') ): ?>
<div id="dummy-content"  style="display: none ;">

    <?php echo $this->loadTemplate('contactform') ?>
</div>
    <a id="open_modal" name="jea-contact-form" href="#jea-contact-form" class="button-primary"><?php echo JText::_('COM_JEA_CONTACT_FORM_LEGEND') ?></a>
    <script type="text/javascript" charset="utf-8">        
        jQuery(document).ready(function(){
            jQuery('#open_modal').click(function (e) {
                    e.preventDefault();
                    jQuery.modal.open(jQuery('#jea-contact-form').clone(), {
                            //maxHeight: 900,
                            //maxWidth: 900,
                            closeText: 'cerrar X',
                            width: 'auto',
                            height: 'auto',
                            fitViewport: true
                    });
            });
    });
    </script>     
     
<?php endif  ?>


<div class="grid grid-pad box-note">    
        <div class="prev-next-navigation" style="float:left">
        <?php echo $this->getPrevNextNavigation() ?>
        </div>
        <div class="back-to-listing">
          <a href="<?php echo JRoute::_('index.php?option=com_jea&view=properties')?>"><?php echo JText::_('COM_JEA_RETURN_TO_THE_LIST')?> </a>
        </div>
        <?php if ($this->params->get('show_print_icon')): ?>
        <div class="print-icon">
          <a href="javascript:window.print()" title="<?php echo JText::_('JGLOBAL_PRINT') ?>">
          <?php echo JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), null, true) ?></a>
        </div>
        <?php endif ?>   
</div>