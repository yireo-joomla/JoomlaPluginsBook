<?php
/**
 * Content Plugin for Joomla! - Chapter 02 / Test 01
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgContentCh02test01
 *
 * @since  September 2014
 */
class PlgContentCh02test01 extends JPlugin
{
	/**
	 * Event method onContentBeforeDisplay
	 *
	 * @param   string  $context  The context of the content being passed to the plugin
	 * @param   mixed   &$row     An object with a "text" property
	 * @param   mixed   &$params  Additional parameters
	 * @param   int     $page     Optional page number
	 *
	 * @return  null
	 */
	public function onContentBeforeDisplay($context, &$row, &$params, $page = 0)
	{
		$row->title = $row->title . ' [test01]';
		$row->text = $row->text . '<p>[test01]</p>';
	}

	/**
	 * Method to do something with this plugin
	 *
	 * @return null
	 */
	protected function doSomethingWithinThisPlugin()
	{
	}
}
