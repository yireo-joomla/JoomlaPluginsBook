<?php
/**
 * System Plugin for Joomla! - Chapter 06 / Test 01
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgSystemCh06test01 extends JPlugin
{
    public function onAfterRender()
    {
        $app = JFactory::getApplication();
        $body = $app->getBody();

        if ($app->isSite() == false)
        {
            return false;
        }

        $body = str_replace('</body>', '<foobar></foobar></body>', $body);
        $app->setBody($body);
    }
}
