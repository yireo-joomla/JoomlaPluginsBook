<?php
/**
 * System Plugin for Joomla! - Custom plugin for anything
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgSystemCustom
 *
 * @since  September 2014
 */
class PlgSystemCustom extends JPlugin
{
	/*
	 * Array of replacement strings (original => replacement)
	 */
	protected $replacementStrings = array(
		'exapmle' => 'example',
	);

	/**
	 * Event method onAfterRender
	 *
	 * @return null
	 */
	public function onAfterRender()
	{
		$app = JFactory::getApplication();

		if ($app->isAdmin())
		{
			return;
		}

		$this->replaceStrings();
	}

	/**
	 * Method to replace strings into strings
	 *
	 * @return null
	 */
	protected function replaceStrings()
	{
		$body = JResponse::getBody();

		if (!empty($this->replacementStrings))
		{
			foreach ($this->replacementStrings as $original => $replacement)
			{
				$body = str_replace($original, $replacement, $body);
			}
		}

		JResponse::setBody($body);
	}
}
