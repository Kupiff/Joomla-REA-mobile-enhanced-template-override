<?php
/**
 * This file is part of Joomla Estate Agency - Joomla! extension for real estate agency
 *
 * @version     default_contactform.php 2012-06-12
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
$uri = JFactory::getURI();
$app = JFactory::getApplication();
JHTML::stylesheet('templates/'.$app->getTemplate().'/html/com_jea/css/modal.css');
?>
        
<form  action="<?php echo JRoute::_('index.php?option=com_jea&task=default.sendContactForm') ?>" method="post" id="jea-contact-form" enctype="application/x-www-form-urlencoded">
    <fieldset class="modal-fieldset-color">
    <legend class="modal-legend" style="background: url(templates/<?php echo $app->getTemplate() ?>/html/com_jea/img/hd-bg.png)">
        <?php echo JText::_('COM_JEA_CONTACT_FORM_LEGEND') ?>
    </legend>
    <dl>
      <dt><label  for="name"><?php echo JText::_('COM_JEA_NAME') ?> :</label></dt>
      <dd><input type="text" name="name" id="name" size="30" value="<?php echo $this->escape($this->state->get('contact.name')) ?>" /></dd>

      <dt><label for="email"><?php echo JText::_('COM_JEA_EMAIL') ?> :</label></dt>
      <dd><input type="text" name="email" id="email" size="30" value="<?php echo $this->escape($this->state->get('contact.email')) ?>" /></dd>

      <dt><label for="telephone"><?php echo JText::_('COM_JEA_TELEPHONE') ?> :</label></dt>
      <dd><input type="text" name="telephone" id="telephone" size="30" value="<?php echo $this->escape($this->state->get('contact.telephone')) ?>" /></dd>

      <dt><label for="subject"><?php echo JText::_('COM_JEA_SUBJECT') ?> :</label></dt>
      <dd><input type="text" name="subject" id="subject" value="<?php echo JText::_('COM_JEA_REF') ?> : <?php echo $this->escape($this->row->ref) ?>" size="30" /></dd>

      <dt><label for="e_message"><?php echo JText::_('COM_JEA_MESSAGE') ?> :</label></dt>
      <dd><textarea name="message" id="e_message" rows="5"  class="e-message"><?php echo $this->escape($this->state->get('contact.message')) ?></textarea></dd>

      <?php if ($this->params->get('use_captcha')):?> 
      <script type="text/javascript">
        var RecaptchaOptions = {
        lang : 'es',
      };
</script>
      <dd><?php echo $this->displayCaptcha() ?></dd>
      <?php endif ?>
      <dd>
        <input type="hidden" name="id" value="<?php echo $this->row->id ?>" />
        <?php echo JHTML::_( 'form.token' ) ?>
        <input type="hidden" name="propertyURL" value="<?php echo base64_encode($uri->toString())?>" />
        <input type="submit" value="<?php echo JText::_('COM_JEA_SEND') ?>" />
      </dd>
    </dl>
  </fieldset>
</form>
