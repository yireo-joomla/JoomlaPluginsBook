<?php
/**
 * Extension Plugin for Joomla! - Custom
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgExtensionCustom
 *
 * @since  September 2014
 */
class PlgExtensionCustom extends JPlugin
{
	/**
	 * Event method onExtensionBeforeSave
	 *
	 * @param   string  $context  Current context
	 * @param   JTable  $table    JTable instance
	 * @param   bool    $isNew    Flag to determine whether this is a new extension
	 *
	 * @return void
	 */
	public function onExtensionBeforeSave($context, $table, $isNew)
	{
		$this->debug('[onExtensionBeforeSave] context', $context);
		$this->debug('[onExtensionBeforeSave] table', get_class($table));
	}

	/**
	 * Event method onExtensionAfterSave
	 *
	 * @param   string  $context  Current context
	 * @param   JTable  $table    JTable instance
	 * @param   bool    $isNew    Flag to determine whether this is a new extension
	 *
	 * @return void
	 */
	public function onExtensionAfterSave($context, $table, $isNew)
	{
		$this->debug('[onExtensionAfterSave] context', $context);
		$this->debug('[onExtensionAfterSave] table', get_class($table));
	}

	/**
	 * Method to log something
	 *
	 * @param   string  $message   Message to log
	 * @param   mixed   $variable  Optional variable to add to the message
	 *
	 * @return void
	 */
	protected function debug($message, $variable = null)
	{
		if (!empty($variable))
		{
			$message .= var_export($variable, true);
		}

		$message .= "\n";

		JLog::add($message, JLog::NOTICE, 'plg_extension_custom');
	}
}
