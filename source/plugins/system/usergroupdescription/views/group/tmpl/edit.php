<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'group.cancel' || document.formvalidator.isValid(document.id('group-form')))
		{
			Joomla.submitform(task, document.getElementById('group-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_users&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="group-form" class="form-validate form-horizontal">
	<fieldset>
		<legend><?php echo JText::_('COM_USERS_USERGROUP_DETAILS');?></legend>
        <?php foreach($this->form->getFieldset() as $field) : ?>
		<div class="control-group">
            <?php if($field->hidden == false) : ?>
			<div class="control-label">
                <?php echo $field->label; ?>
			</div>
			<div class="controls">
                <?php echo $field->input; ?>
			</div>
            <?php endif; ?>
		</div>
        <?php endforeach; ?>
	</fieldset>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
