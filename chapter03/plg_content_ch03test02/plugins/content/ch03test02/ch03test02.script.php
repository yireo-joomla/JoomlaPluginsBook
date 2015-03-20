<?php
/**
 * Content Plugin for Joomla! - Chapter 03 / Test 02
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

/**
 * Class plgContentCh03test02InstallerScript
 *
 * @since  September 2014
 */
class PlgContentCh03test02InstallerScript
{
	/**
	 * Method run when installing your plugin
	 *
	 * @param   JAdapterInstance  $adapter  The adapter instance
	 *
	 * @return bool
	 */
	public function install($adapter)
	{
		return true;
	}

	/**
	 * Method run when updating your plugin
	 *
	 * @param   JAdapterInstance  $adapter  The adapter instance
	 *
	 * @return bool
	 */
	public function update($adapter)
	{
		return true;
	}

	/**
	 * Method run when uninstalling your plugin
	 *
	 * @param   JAdapterInstance  $adapter  The adapter instance
	 *
	 * @return bool
	 */
	public function uninstall($adapter)
	{
		return true;
	}

	/**
	 * Method run before doing anything
	 *
	 * @param   string            $route    Current route
	 * @param   JAdapterInstance  $adapter  The adapter instance
	 *
	 * @return bool
	 */
	public function preflight($route, $adapter)
	{
        if ($route != 'install')
        {
            return true;
        }

		$file = JPATH_SITE . '/plugins/content/ch03test01/ch03test01.php';

		if (file_exists($file) == true)
		{
			return true;
		}

		JError::raiseNotice('warning', 'Install "Chapter 03 / Test 01" first');

		return false;
	}

	/**
	 * Method run after doing anything
	 *
	 * @param   string            $route    Current route
	 * @param   JAdapterInstance  $adapter  The adapter instance
	 *
	 * @return bool
	 */
	public function postflight($route, $adapter)
	{
		return true;
	}
}
