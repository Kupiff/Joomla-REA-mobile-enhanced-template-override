<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     0.1: default.php 2013-06-01
 * @copyright   Copyright (C) 2013 webnology gmbh  www.webnology.ch . All rights reserved.
 * @copyright   Copyright (C) 2012 PHILIP Sylvain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * This template override for the Joomla Estate Agency is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses.
 */
// no direct access
defined('_JEXEC') or die();
require_once 'components/com_jea/models/properties.php';

$fields = json_encode($states);
$ajax = $useAjax? 'true': 'false';
JHTML::script('media/com_jea/js/search.js', true);

JHTML::stylesheet('templates/'.$app->getTemplate().'/html/com_jea/css/jslider.css');
JHTML::stylesheet('templates/'.$app->getTemplate().'/html/com_jea/css/jslider.round.plastic.css');
JHTML::script('templates/'.$app->getTemplate().'/html/com_jea/js/jshashtable-2.1_src.js', true);
JHTML::script('templates/'.$app->getTemplate().'/html/com_jea/js/jquery.numberformatter-1.2.3.js', true);
JHTML::script('templates/'.$app->getTemplate().'/html/com_jea/js/tmpl.js', true);
JHTML::script('templates/'.$app->getTemplate().'/html/com_jea/js/jquery.dependClass-0.1.js', true);
JHTML::script('templates/'.$app->getTemplate().'/html/com_jea/js/draggable-0.1.js', true);
JHTML::script('templates/'.$app->getTemplate().'/html/com_jea/js/jquery.slider.js', true);

$document = JFactory::getDocument();

$paramsjea = &JComponentHelper::getParams( 'com_jea' );
$currency_symbol =  $paramsjea->get('currency_symbol', '&euro;');
$surface_measure =  $paramsjea->get('surface_measure', 'm');


$JeaModelProperties = new JeaModelProperties();

$searchcontext= $JeaModelProperties->getState('searchcontext');


$fieldsLimitAR = array(
    'RENTING' => array(
        'price'   => $JeaModelProperties->getFieldLimit('price', 'RENTING'),
        'surface' => $JeaModelProperties->getFieldLimit('living_space', 'RENTING'),
        'land'    => $JeaModelProperties->getFieldLimit('land_space','RENTING')
        ),
    'SELLING' => array(
        'price'   => $JeaModelProperties->getFieldLimit('price', 'SELLING'),
        'surface' => $JeaModelProperties->getFieldLimit('living_space', 'SELLING'),
        'land'    => $JeaModelProperties->getFieldLimit('land_space','SELLING')
     ),
);

$fieldsLimit = json_encode($fieldsLimitAR);

$document->addScriptDeclaration("
	function transactionTipe()
	{	
                var transaction_type = 'SELLING';
                var form = document.id('mod-jea-search-form-$uid');

                var transTypes = document.getElements('[name=filter_transaction_type]');
                transTypes.each(function(item) {
                        if (item.get('checked')) {
                            transaction_type = item.get('value');
                        }
                });               
		var fieldsLimit = $fieldsLimit;                    
		return {
                    minPrice : fieldsLimit[transaction_type].price[0], 
                    maxPrice : fieldsLimit[transaction_type].price[1],
                    minSpace : fieldsLimit[transaction_type].surface[0],
                    maxSpace : fieldsLimit[transaction_type].surface[1],
                    minLand : fieldsLimit[transaction_type].land[0], 
                    maxLand : fieldsLimit[transaction_type].land[1],
                    transaction : transaction_type
                };
	}"

);

?>

<form action="<?php echo $formURL ?>" method="post" id="mod-jea-search-form-<?php echo $uid ?>">
 
<?php if ($params->get('show_freesearch')): ?>
  <p>
    <label for="jea-search<?php echo $uid ?>"><?php echo JText::_('COM_JEA_SEARCH_LABEL')?> : </label>
    <input type="text" name="filter_search" id="jea-search<?php echo $uid ?>" value="<?php echo htmlspecialchars($states['filter_search'], ENT_QUOTES, 'UTF-8') ?>" /> 
    <input type="submit" class="button" value="<?php echo JText::_('JSEARCH_FILTER_SUBMIT')?>" />
  </p>
  <hr />
<?php endif ?>

  <p>
  <?php echo JHtml::_('features.types', $states['filter_type_id'], 'filter_type_id', array('id' => 'type_id'.$uid)) ?>
  </p>
  
  <p>
  <?php if ($transationType == 'RENTING'): ?>
    <input type="hidden" name="filter_transaction_type" value="RENTING" />
  <?php elseif($transationType == 'SELLING'): ?>
    <input type="hidden" name="filter_transaction_type" value="SELLING" />
  <?php else: ?>
    <input type="radio" name="filter_transaction_type" id="jea-search-selling<?php echo $uid ?>" value="SELLING" 
           <?php if ($states['filter_transaction_type'] == 'SELLING') echo 'checked="checked"' ?> /> 
    <label for="jea-search-selling<?php echo $uid ?>"><?php echo JText::_('COM_JEA_OPTION_SELLING') ?></label>

    <input type="radio" name="filter_transaction_type" id="jea-search-renting<?php echo $uid ?>" value="RENTING"
           <?php if ($states['filter_transaction_type'] == 'RENTING') echo 'checked="checked"' ?> />
    <label for="jea-search-renting<?php echo $uid ?>"><?php echo JText::_('COM_JEA_OPTION_RENTING') ?></label>
  <?php endif ?>
  </p>

<?php if ($showLocalization): ?>
  <p><strong><?php echo JText::_('COM_JEA_LOCALIZATION') ?> :</strong></p>

  <p>
    <?php if ($params->get('show_departments', 1)): ?>
    <?php echo JHtml::_('features.departments', $states['filter_department_id'], 'filter_department_id', array('id' => 'department_id'.$uid)) ?>
    <?php endif ?><br />

    <?php if ($params->get('show_towns', 1)): ?>
    <?php echo JHtml::_('features.towns', $states['filter_town_id'], 'filter_town_id', array('id' => 'town_id'.$uid )) ?>
    <?php endif ?><br />

    <?php if ($params->get('show_areas', 1)): ?>
    <?php echo JHtml::_('features.areas', $states['filter_area_id'], 'filter_area_id', array('id' => 'area_id'.$uid )) ?>
    <?php endif ?><br />
  </p>

  <?php if ($params->get('show_zip_codes', 1)): ?>
  <p>
    <label for="jea-search-zip-codes<?php echo $uid ?>"><?php echo JText::_('COM_JEA_SEARCH_ZIP_CODES') ?> : </label>
    <input id="jea-search-zip-codes<?php echo $uid ?>" type="text" name="filter_zip_codes" size="20" value="<?php echo $states['filter_zip_codes'] ?>" />
    <em><?php echo JText::_('COM_JEA_SEARCH_ZIP_CODES_DESC') ?></em>
  </p>
  <?php endif ?>
<?php endif ?>

<?php if ($params->get('show_budget', 1)): ?>
  <p><strong><?php echo JText::_('COM_JEA_BUDGET') ?> :</strong></p>
    <input id="jea-search-budget-min" type="hidden" name="filter_budget_min" value="<?php echo $fieldsLimitAR[$states['filter_transaction_type']]['price'][0] ?>" />
    <input id="jea-search-budget-max" type="hidden" name="filter_budget_max" value="<?php echo $fieldsLimitAR[$states['filter_transaction_type']]['price'][1] ?>" />	
    <div class="layout-slider">
      <input id="Slider_Budget" type="slider" name="Slider_Budget" value="<?php echo $fieldsLimitAR[$states['filter_transaction_type']]['price'][0] ?>;<?php echo $fieldsLimitAR[$states['filter_transaction_type']]['price'][1] ?>" />
    </div>
    <script type="text/javascript" charset="utf-8">
        jQuery("#Slider_Budget").slider({ 
            from: <?php echo $fieldsLimitAR[$states['filter_transaction_type']]['price'][0] ?>, 
            to: <?php echo $fieldsLimitAR[$states['filter_transaction_type']]['price'][1] ?>, 
            limits: true, 
            step: 1000,
            dimension: '&nbsp;<?php echo $currency_symbol ?>', 
            skin: "round_plastic",
            callback: function(value) {
                    var splitVals = value.split(';');
                    jQuery('input[name="filter_budget_min"]').val(splitVals[0]);
                    jQuery('input[name="filter_budget_max"]').val(splitVals[1]);
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
                            step: 1000,
                            dimension: '&nbsp;<?php echo $currency_symbol ?>',
                            skin: "round_plastic"

                    });						
                });
        });	
	</script>
<?php endif ?>
  
<?php if ($params->get('show_living_space', 1)): ?>
  <p><strong><?php echo JText::_('COM_JEA_FIELD_LIVING_SPACE_LABEL') ?> :</strong></p>

    <input id="jea-search-living-space-min" type="hidden" name="filter_living_space_min" value="<?php echo $fieldsLimitAR[$states['filter_transaction_type']]['surface'][0] ?>" />
    <input id="jea-search-living-space-max" type="hidden" name="filter_living_space_max" value="<?php echo $fieldsLimitAR[$states['filter_transaction_type']]['surface'][1] ?>" />	
    <div class="layout-slider">
      <input id="Slider_Surface" type="slider" name="Slider_Surface" value="<?php echo $fieldsLimitAR[$states['filter_transaction_type']]['surface'][0] ?>;<?php echo $fieldsLimitAR[$states['filter_transaction_type']]['surface'][1] ?>" />
    </div>
    <script type="text/javascript" charset="utf-8">
      jQuery("#Slider_Surface").slider({ 
			from: <?php echo $fieldsLimitAR[$states['filter_transaction_type']]['surface'][0] ?>, 
			to: <?php echo $fieldsLimitAR[$states['filter_transaction_type']]['surface'][1] ?>, 
			limits: true, 
			step: 5,
			dimension: '&nbsp;<?php echo $surface_measure ?>', 
			skin: "round_plastic",
			callback: function(value) {
				var splitVals = value.split(';');
				jQuery('input[name="filter_living_space_min"]').val(splitVals[0]);
				jQuery('input[name="filter_living_space_max"]').val(splitVals[1]);
			}
			  
		  });


			jQuery(document).ready(function(){
				jQuery("input[name='filter_transaction_type']").change(function(){ 
					var space_values = null;
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
					//jQuery("#Slider_Surface").slider("prc",0,100);
				});
			});
			
			
	</script>


<?php endif ?>

<?php if ($params->get('show_land_space', 1)): ?>
  <p><strong><?php echo JText::_('COM_JEA_FIELD_LAND_SPACE_LABEL') ?> :</strong></p>  
    <input id="jea-search-land-space-min" type="hidden" name="filter_land_space_min" value="<?php echo $fieldsLimitAR[$states['filter_transaction_type']]['land'][0] ?>" />
    <input id="jea-search-land-space-max" type="hidden" name="filter_land_space_max" value="<?php echo $fieldsLimitAR[$states['filter_transaction_type']]['land'][1] ?>" />	
    <div id="Div_Slider_Landspace" class="layout-slider">
      <input id="Slider_Landspace" type="slider" name="Slider_Landspace" value="<?php echo $fieldsLimitAR[$states['filter_transaction_type']]['land'][0] ?>;<?php echo $fieldsLimitAR[$states['filter_transaction_type']]['land'][1] ?>" />
    </div>
    <script type="text/javascript" charset="utf-8">     
      jQuery("#Slider_Landspace").slider({ 
                        from: <?php echo $fieldsLimitAR[$states['filter_transaction_type']]['land'][0] ?>, 
                        to: <?php echo $fieldsLimitAR[$states['filter_transaction_type']]['land'][1] ?>,  
			limits: true, 
			step: 5,
			dimension: '&nbsp;<?php echo $surface_measure ?>', 
			skin: "round_plastic",
			callback: function(value) {
				var splitVals = value.split(';');
				jQuery('input[name="filter_land_space_min"]').val(splitVals[0]);
				jQuery('input[name="filter_land_space_max"]').val(splitVals[1]);
			}
			  
	});
        jQuery(document).ready(function(){
                jQuery("input[name='filter_transaction_type']").change(function(){ 

                    var land_values = null;
                    land_values = transactionTipe(); 
                    jQuery('input[name="filter_land_space_min"]').val(land_values.minLand);
                    jQuery('input[name="filter_land_space_max"]').val(land_values.maxLand);
                    jQuery('input[name="Slider_Landspace"]').val(land_values.minLand + ';' + land_values.maxLand);                     

                    jQuery("#Slider_Landspace").slider("redraw", { 
                            from: jQuery('input[name="filter_land_space_min"]').val(), 
                            to: jQuery('input[name="filter_land_space_max"]').val(), 
                            limits: true, 
                            step: 1, 
                            dimension: '&nbsp;<?php echo $surface_measure ?>',
                            skin: "round_plastic"

                    }); 
                });
        });
			
			
	</script> 
<?php endif ?>

<?php if ($showOtherFilters): ?>
  <p><strong><?php echo JText::_('COM_JEA_SEARCH_OTHER') ?> :</strong></p>

  <ul class="jea-search-other">
      <?php if ($params->get('show_number_of_rooms', 1)): ?>
    <li>
      <label for="jea-search-rooms<?php echo $uid ?>"><?php echo JText::_('COM_JEA_NUMBER_OF_ROOMS_MIN') ?> : </label>
      <input id="jea-search-rooms<?php echo $uid ?>" type="text" name="filter_rooms_min" 
             size="2" value="<?php echo $states['filter_rooms_min'] ?>" />
    </li>
    <?php endif?>
  
    <?php if ($params->get('show_number_of_bedrooms', 1)): ?>
    <li>
      <label for="jea-search-bedrooms<?php echo $uid ?>"><?php echo JText::_('COM_JEA_NUMBER_OF_BEDROOMS_MIN') ?> : </label>
      <input id="jea-search-bedrooms<?php echo $uid ?>" type="text" name="filter_bedrooms_min" 
             size="2" value="<?php echo $states['filter_bedrooms_min'] ?>" />
    </li>
    <?php endif?>
    
    <?php if ($params->get('show_number_of_bathrooms', 0)): ?>
    <li>
      <label for="jea-search-bathrooms<?php echo $uid ?>"><?php echo JText::_('COM_JEA_NUMBER_OF_BATHROOMS_MIN') ?> : </label>
      <input id="jea-search-bathrooms<?php echo $uid ?>" type="text" name="filter_bathrooms_min" 
             size="2" value="<?php echo $states['filter_bathrooms_min'] ?>" />
    </li>
    <?php endif?>

    <?php if ($params->get('show_floor', 1)): ?>
    <li>
      <label for="jea-search-floor<?php echo $uid ?>"><?php echo JText::_('COM_JEA_FIELD_FLOOR_LABEL') ?> : </label>
      <input id="jea-search-floor<?php echo $uid ?>" type="text" name="filter_floor" size="2" value="<?php echo $states['filter_floor'] ?>" />
      <em><?php echo JText::_('COM_JEA_SEARCH_FLOOR_DESC') ?></em>
    </li>
    <?php endif?>

    <?php if ($params->get('show_hotwatertypes', 0)): ?>
    <li><?php echo JHtml::_('features.hotwatertypes', $states['filter_hotwatertype'], 'filter_hotwatertype', array('id' => 'hotwatertype'.$uid ) ) ?></li>
    <?php endif?>

    <?php if ($params->get('show_heatingtypes', 0)): ?>
    <li><?php echo JHtml::_('features.heatingtypes', $states['filter_heatingtype'], 'filter_heatingtype', array('id' => 'heatingtype'.$uid ) ) ?></li>
    <?php endif?>

    <?php if ($params->get('show_conditions', 0)): ?>
    <li><?php echo JHtml::_('features.conditions', $states['filter_condition'], 'filter_condition', array('id' => 'condition'.$uid ) ) ?></li>
    <?php endif?>

    <?php if ($params->get('show_orientation', 1)): ?>
    <li>
    <?php 
        $options = array(
            JHTML::_('select.option', '0',  ' - ' . JText::_('COM_JEA_FIELD_ORIENTATION_LABEL') . ' - ' ),
            JHTML::_('select.option', 'N',  JText::_('COM_JEA_OPTION_NORTH')),
            JHTML::_('select.option', 'NW', JText::_('COM_JEA_OPTION_NORTH_WEST')),
            JHTML::_('select.option', 'NE', JText::_('COM_JEA_OPTION_NORTH_EAST')),
            JHTML::_('select.option', 'E',  JText::_('COM_JEA_OPTION_EAST')),
            JHTML::_('select.option', 'W',  JText::_('COM_JEA_OPTION_WEST')),
            JHTML::_('select.option', 'S',  JText::_('COM_JEA_OPTION_SOUTH')),
            JHTML::_('select.option', 'SW', JText::_('COM_JEA_OPTION_SOUTH_WEST')),
            JHTML::_('select.option', 'SE', JText::_('COM_JEA_OPTION_SOUTH_EAST'))
        );
        echo JHTML::_('select.genericlist', $options, 'filter_orientation', 'size="1"', 'value', 'text',  $states['filter_orientation'], array('id' => 'filter_orientation'.$uid)) 
    ?>
    </li>
    <?php endif?>
  </ul>
<?php endif ?>

<?php if ($params->get('show_amenities', 1)): ?>
  <p><strong><?php echo JText::_('COM_JEA_AMENITIES') ?> :</strong></p>
  <div class="amenities">
    <?php echo JHtml::_('amenities.checkboxes', $states['filter_amenities'], 'filter_amenities', $uid ) ?>
    <?php // In order to prevent nul post for this field ?>
    <input type="hidden" name="filter_amenities[]" value="0" />
  </div>
<?php endif ?>

  <p>
    <input type="reset" class="button-default" value="<?php echo JText::_('JSEARCH_FILTER_CLEAR') ?>" />
    <script type="text/javascript" charset="utf-8">
        jQuery("input[type='reset']").on("click", function(){
            jQuery("#Slider_Budget").slider("prc",0,100);
            jQuery("#Slider_Surface").slider("prc",0,100);
            jQuery("#Slider_Landspace").slider("prc",0,100);
        });
  </script>
    <input type="submit"  class="button-primary" value="<?php echo $useAjax ? JText::_('COM_JEA_LIST_PROPERTIES') : JText::_('JSEARCH_FILTER_SUBMIT')?>" />
  </p>

</form>
