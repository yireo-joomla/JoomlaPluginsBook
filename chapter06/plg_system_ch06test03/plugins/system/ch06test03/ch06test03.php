<?php
/**
 * System Plugin for Joomla! - Chapter 06 / Test 03
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgSystemCh06test03 extends JPlugin
{
    public function onAfterInitialise()
    {
        $app = JFactory::getApplication();
        if($app->isSite() == false)
        {
            return false;
        }

        $router = $app->getRouter();
        $callback = array($this, 'buildRoute');
        $router->attachBuildRule($callback);
    }

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
