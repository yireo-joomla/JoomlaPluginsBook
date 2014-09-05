<?php
/**
 * System Plugin for Joomla! - Chapter 06 / Test 03
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class plgSystemCh06test03
 *
 * @since  September 2014
 */
class PlgSystemCh06test03 extends JPlugin
{
	/**
	 * Event method onAfterInitialise
	 *
	 * @return bool
	 */
	public function onAfterInitialise()
	{
		$app = JFactory::getApplication();

		if ($app->isSite() == false)
		{
			return false;
		}

		$router = $app->getRouter();
		$callback = array($this, 'buildRoute');
		$router->attachBuildRule($callback);
	}

	/**
	 * Method to build the router
	 *
	 * @param   JRouter  $router  JRouter instance
	 * @param   JURI     $uri     Current JURI instance
	 *
	 * @return null
	 */
	public function buildRoute($router, $uri)
	{
		$routerClone = clone $router;
		$vars = $routerClone->parse($uri);

		if (isset($vars['view']) && $vars['view'] == 'article')
		{
			$uri->setVar('test', 1);
		}
	}
}
