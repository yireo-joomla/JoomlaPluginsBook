<?php
/**
 * Content Plugin for Joomla! - Test 02
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

class plgContentTest02InstallerScript
{
    public function install(JAdapterInstance $adapter)
    {
        return true; 
    }

    public function update(JAdapterInstance $adapter)
    {
        return true; 
    }

    public function uninstall(JAdapterInstance $adapter)
    {
        return true; 
    }

    public function preflight(string $route, JAdapterInstance $adapter)
    {
        $file = JPATH_SITE.'/plugins/content/test01/test01.php'; 
        if(file_exists($file) == true) { 
            return true; 
        } 
        JError::raiseNotice('warning', 'Install "Test 01" first'); 
        return false; 
    }

    public function postflight(string $route, JAdapterInstance $adapter)
    {
        return true; 
    }
}
