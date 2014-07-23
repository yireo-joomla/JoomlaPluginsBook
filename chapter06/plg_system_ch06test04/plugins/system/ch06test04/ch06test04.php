<?php
/**
 * System Plugin for Joomla! - Chapter 06 / Test 04
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */
    
// @todo: Does this actually work?

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgSystemCh06test04 extends JPlugin
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

        $callback = array($this, 'parseRoute');
        $router->attachParseRule($callback);
    }

    public function buildRoute($router, $uri)
    {
        if ($uri->getVar('view') == 'article')
        {
            $query = $uri->getQuery();
            $query = preg_replace('/\&id=([0-9]+):([a-z0-9\-\_]+)/', '&id=\2', $query);
            $uri->setQuery($query);
        }
        return;
    }

    public function parseRoute($router, $uri)
    {
        $path = $uri->getPath();
        $segments = explode('/', $path);
        $alias = end($segments);
        if (preg_match('/^([0-9])\-/', $alias) == false)
        {
            $alias = preg_replace('/\-$/', '', $alias);
            $slug = $this->getSlugByAlias($alias);
            if (!empty($slug))
            {
                $path = str_replace($alias, $slug, $path);
                $uri->setPath($path);
            }
        }

        return array();
    }

    protected function getSlugByAlias($path)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id', 'alias')));
        $query->from($db->quoteName('#__content'));
        $query->where($db->quoteName('alias').' = '.$db->quote($path));
        $db->setQuery($query);
        $row = $db->loadObject();
        
        if (!empty($row))
        {
            return $row->id.':'.$row->alias;
        }
    }
}
