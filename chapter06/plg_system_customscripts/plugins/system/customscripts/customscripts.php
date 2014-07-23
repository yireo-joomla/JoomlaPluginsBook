<?php
/**
 * System Plugin for Joomla! - Custom Scripts
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgSystemCustomscripts extends JPlugin
{
    public function onBeforeCompileHead()
    {
        $application = JFactory::getApplication();
        $document = JFactory::getDocument();
        if ($application->isSite() == false) return;

        $scripts = array();
        foreach($document->_scripts as $scriptName => $scriptDetails)
        {
            if (strstr($scriptName, 'mootools'))
            {
                continue;
            }

            if (strstr($scriptName, 'js/jquery.min.js'))
            {
                $scriptName = '//code.jquery.com/jquery-1.11.0.min.js';
            }

            $scripts[$scriptName] = $scriptDetails;
        }

        $document->_scripts = $scripts;
    }
}

// End
