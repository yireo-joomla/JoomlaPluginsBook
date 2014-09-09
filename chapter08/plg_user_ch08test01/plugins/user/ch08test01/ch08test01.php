<?php
/**
 * User Plugin for Joomla! - Chapter 08 Test 01
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgContentCh03test01
 *
 * @since  September 2014
 */
class PlgUserCh08test01 extends JPlugin
{
	/**
	 * Load the language file on instantiation (for Joomla! 3.X only)
	 *
	 * @var    boolean
	 * @since  3.3
	 */
	protected $autoloadLanguage = true;

	/**
	 * Event method onUserAfterSave
	 *
	 * @param   array   $data     Form values
	 * @param   int     $isNew    Flag indicating whether this usergroup is new or not
	 * @param   bool    $success  Flag to indicate whether deletion was succesful
	 * @param   string  $msg      Message after deletion
	 *
	 * @return  null
	 */
	public function onUserAfterSave($data, $isNew, $success, $msg)
	{
	}
}
