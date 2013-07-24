<?php
/**
 * This file is a template override for the Joomla Estate Agency - Joomla! extension for real estate agency
 * based on PHILIP Sylvain searchmap.php
 * @version     1.0: searchmap.php 2013-05-30
 * @copyright	Copyright (C) 2013 webnology gmbh. www.webnology.ch All rights reserved.
 * @copyright	Copyright (C) 2008 PHILIP Sylvain. All rights reserved.
 * @license	GNU/GPL, see LICENSE.txt
 * This template override for the Joomla Estate Agency is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses.
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::stylesheet('media/com_jea/css/jea.css');
JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/html');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHTML::script('templates/yoo_master/html/com_jea/js/jshashtable-2.1_src.js', true);
JHTML::script('templates/yoo_master/html/com_jea/js/jquery.numberformatter-1.2.3.js', true);
JHTML::script('templates/yoo_master/html/com_jea/js/tmpl.js', true);
JHTML::script('templates/yoo_master/html/com_jea/js/jquery.dependClass-0.1.js', true);
JHTML::script('templates/yoo_master/html/com_jea/js/draggable-0.1.js', true);
JHTML::script('templates/yoo_master/html/com_jea/js/jquery.slider.js', true);
JHTML::stylesheet('templates/yoo_master/html/com_jea/css/jslider.css');
JHTML::stylesheet('templates/yoo_master/html/com_jea/css/jslider.round.plastic.css');

$transationType = $this->params->get('searchform_transaction_type');
$Itemid = JFactory::getApplication()->input->getInt('Itemid', 0);

$states = array();
$filters = $this->get('Filters');

foreach ($filters as $name => $defaultValue) {
    $states['filter_'.$name] = $this->state->get('filter.'.$name, $defaultValue);
}
if (empty($transationType) && empty($states['filter_transaction_type'])) {
    // Set SELLING as default transaction_type state
    $states['filter_transaction_type'] = 'SELLING';
} elseif (!empty($transationType) && empty($states['filter_transaction_type'])) {
    $states['filter_transaction_type'] = $transationType;
}

$fields = json_encode($states);

JHTML::script('media/com_jea/js/search.js', true);
JHTML::script('media/com_jea/js/geoSearch.js');
JHTML::script('media/com_jea/js/geoxml3.js');


$langs  = explode('-', $this->document->getLanguage());
$lang   = $langs[0];
$region = $langs[1];

$this->document->addScript('http://maps.google.com/maps/api/js?sensor=false&amp;language=' . $lang
. '&amp;region=' . $region );

$model = $this->getModel();

//get initial values for sliders
$fieldsLimitAR = array(
    'RENTING' => array(
        'price'   => $model->getFieldLimit('price', 'RENTING'),
        'surface' => $model->getFieldLimit('living_space', 'RENTING')
        ),
    'SELLING' => array(
        'price'   => $model->getFieldLimit('price', 'SELLING'),
        'surface' => $model->getFieldLimit('living_space', 'SELLING')
     ),
);


$fieldsLimit = json_encode($fieldsLimitAR);



$default_area = $this->params->get('searchform_default_map_area', $lang);
$currency_symbol = $this->params->get('currency_symbol', '€');
$surface_measure = $this->params->get( 'surface_measure');
$map_width  = $this->params->get('searchform_map_width', 0);
$map_height = $this->params->get( 'searchform_map_height', 400);


//initialize the form when the page load
$this->document->addScriptDeclaration("

window.addEvent('domready', function() {

    var jeaSearch = new JEASearch('jea-search-form', {fields:$fields, useAJAX:true});

    geoSearch = new JEAGeoSearch('map_canvas', {
        counterElement : 'properties_count',
        defaultArea : '{$default_area}',
        form : 'jea-search-form',
        Itemid : {$Itemid}
    });

    geoSearch.refresh();
    jeaSearch.refresh();
    
});");


//inizialize the slider values for SELLING or RENTING
$this->document->addScriptDeclaration("

function transactionTipe()
    {	
        var transaction_type = 'SELLING';
        var form = document.id('jea-search-form');

        var transTypes = document.getElements('[name=filter_transaction_type]');
        transTypes.each(function(item) {
                if (item.get('checked')) {
                transaction_type = item.get('value');
        }
    });

    var fieldsLimit = $fieldsLimit;
    return {minPrice : fieldsLimit[transaction_type].price[0], maxPrice : fieldsLimit[transaction_type].price[1],
    minSpace: minSpace = fieldsLimit[transaction_type].surface[0],maxSpace :maxSpace = fieldsLimit[transaction_type].surface[1],
    transaction : transaction_type};
}"
);
?>

<?php if ($this->params->get('show_page_heading', 1)) : ?>
  <?php if ($this->params->get('page_heading')) : ?>
  <h1><?php echo $this->escape($this->params->get('page_heading')) ?></h1>
  <?php else: ?>
  <h1><?php echo $this->escape($this->params->get('page_title')) ?></h1>
  <?php endif ?>
<?php endif ?>

<form action="<?php echo JRoute::_('index.php?option=com_jea&task=properties.search') ?>" 
      method="post" id="jea-search-form" enctype="application/x-www-form-urlencoded">
  <p>
  <?php if ($transationType == 'RENTING'): ?>
    <input type="hidden" name="filter_transaction_type" value="RENTING" />
  <?php elseif($transationType == 'SELLING'): ?>
    <input type="hidden" name="filter_transaction_type" value="SELLING" />
  <?php else: ?>
    <input type="radio" name="filter_transaction_type" id="jea-search-selling" value="SELLING"
        <?php if ($states['filter_transaction_type'] == 'SELLING') echo 'checked="checked"' ?> />
    <label for="jea-search-selling"><?php echo JText::_('COM_JEA_OPTION_SELLING') ?></label>

    <input type="radio" name="filter_transaction_type" id="jea-search-renting" value="RENTING"
        <?php if ($states['filter_transaction_type'] == 'RENTING') echo 'checked="checked"' ?> />
    <label for="jea-search-renting"><?php echo JText::_('COM_JEA_OPTION_RENTING') ?></label>
  <?php endif ?>
  </p>

  <div id="map_canvas" style="width: <?php echo $map_width ? $map_width.'px': '100%'?>; height: <?php echo $map_height.'px'?>"></div>
  <div class="clr"></div>
  <?php if ($this->params->get('searchform_show_budget', 1)): ?>
    <div class="jea_search_slider_block_left">
        <h2><?php echo JText::_('COM_JEA_BUDGET') ?></h2>
        <input id="budget_min" type="hidden" name="filter_budget_min" value="<?php echo $fieldsLimitAR['SELLING']['price'][0] ?>" />
        <input id="budget_max" type="hidden" name="filter_budget_max" value="<?php echo $fieldsLimitAR['SELLING']['price'][1] ?>" />	
    <div class="layout-slider">
        <input id="Slider_Budget" type="slider" name="Slider_Budget" value="<?php echo $fieldsLimitAR['SELLING']['price'][0] ?>;
            <?php echo $fieldsLimitAR['SELLING']['price'][1] ?>" />
    </div>
    <script type="text/javascript" charset="utf-8">
        jQuery("#Slider_Budget").slider({ 
            from: <?php echo $fieldsLimitAR['SELLING']['price'][0] ?>, 
            to: <?php echo $fieldsLimitAR['SELLING']['price'][1] ?>,  
            limits: true, 
            step: 1000,
            dimension: '&nbsp;<?php echo $currency_symbol ?>', 
            skin: "round_plastic",
            callback: function(value) {
                var splitVals = value.split(';');
                jQuery('input[name="filter_budget_min"]').val(splitVals[0]);
                jQuery('input[name="filter_budget_max"]').val(splitVals[1]);
                geoSearch.refresh();
            }
        });

        jQuery(document).ready(function(){
            jQuery("input[name='filter_transaction_type']").change(function(){ 
                var price_values = null;
                price_values = transactionTipe();
                jQuery('input[name="filter_budget_min"]').val(price_values.minPrice);
                jQuery('input[name="filter_budget_max"]').val(price_values.maxPrice);
                jQuery('input[name="Slider_Budget"]').val(price_values.minPrice + ';' + price_values.maxPrice);
                
                jQuery("#Slider_Budget").slider("redraw", { 
                    from: price_values.minPrice,
                    to: price_values.maxPrice, 
                    limits: true, 
                    step: 500000,
                    dimension: '&nbsp;<?php echo $currency_symbol ?>',
                    skin: "round_plastic"
                });
            jQuery("#Slider_Budget").slider("value", price_values.minPrice,price_values.maxPrice);
            geoSearch.refresh();
            });
        });
    </script>
   </div>
    <?php endif; ?>

    <?php if ($this->params->get('searchform_show_living_space', 1)): ?>
    <div class="jea_search_slider_block_right">
      <h2><?php echo JText::_('COM_JEA_FIELD_LIVING_SPACE_LABEL') ?></h2>
      <input id="living_space_min" type="hidden" name="filter_living_space_min" value="<?php echo $fieldsLimitAR['SELLING']['surface'][0] ?>" />
      <input id="living_space_max" type="hidden" name="filter_living_space_max" value="<?php echo $fieldsLimitAR['SELLING']['surface'][1] ?>" />	
      <div class="layout-slider">
          <input id="Slider_Surface" type="slider" name="Slider_Surface" value="<?php echo $fieldsLimitAR['SELLING']['surface'][0] ?>;
              <?php echo $fieldsLimitAR['SELLING']['surface'][1] ?>" />
      </div>
      <script type="text/javascript" charset="utf-8">
          jQuery("#Slider_Surface").slider({ 
              from: <?php echo $fieldsLimitAR['SELLING']['surface'][0] ?>, 
              to: <?php echo $fieldsLimitAR['SELLING']['surface'][1] ?>, 
              limits: true, 
              step: 5,
              dimension: '&nbsp;<?php echo $surface_measure ?>', 
              skin: "round_plastic",
              callback: function(value) {
                var splitVals = value.split(';');
                jQuery('input[name="filter_living_space_min"]').val(splitVals[0]);
                jQuery('input[name="filter_living_space_max"]').val(splitVals[1]);
                geoSearch.refresh();
            }
        });
        jQuery(document).ready(function(){
            jQuery("input[name='filter_transaction_type']").change(function(){ 
                var price_values = null;
                space_values = transactionTipe();
                jQuery('input[name="filter_living_space_min"]').val(space_values.minSpace);
                jQuery('input[name="filter_living_space_max"]').val(space_values.maxSpace);
                jQuery('input[name="Slider_Surface"]').val(space_values.minSpace + ';' + space_values.maxSpace);
                jQuery("#Slider_Surface").slider("redraw", { 
                    from: space_values.minSpace,
                    to: space_values.maxSpace,
                    limits: true, 
                    step: 1, 
                    dimension: '&nbsp;<?php echo $surface_measure ?>',
                    skin: "round_plastic"
                });
                jQuery("#Slider_Surface").slider("value", space_values.minSpace,space_values.maxSpace);
                geoSearch.refresh();
                });
        });
    </script>
    </div>
    <?php endif; ?>
    <div class="clr"></div>
  <p>
        <input type="submit" class="button-primary" value="<?php echo JText::_('COM_JEA_LIST_PROPERTIES') ?>" />
  </p>
</form>
