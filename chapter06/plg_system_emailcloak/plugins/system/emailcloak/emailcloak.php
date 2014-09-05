<?php
/**
 * System Plugin for Joomla! - Email Cloaking
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

// Include the parent class
$path = JPATH_SITE.'/plugins/content/emailcloak/emailcloak.php';
JLoader::register('PlgContentEmailcloak', $path);

/**
 * Class PlgSystemEmailcloak
 *
 * @since  September 2014
 */
class PlgSystemEmailcloak extends PlgContentEmailcloak
{
	/**
	 * Event method onAfterRender
	 *
	 * @return null
	 */
	public function onAfterRender()
	{
		$app = JFactory::getApplication();

		if ($app->isSite())
		{
			$body = $app->getBody();
			$params = new JRegistry();
			$this->_cloak(&$body, $params);
			$app->setBody($body);
		}
	}
}
