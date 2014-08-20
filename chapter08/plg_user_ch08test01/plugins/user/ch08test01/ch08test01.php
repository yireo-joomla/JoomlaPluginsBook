<?php
/**
 * User Plugin for Joomla! - Chapter 08 Test 01
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgUserCh08test01 extends JPlugin
{
	protected $autoloadLanguage = true;

	public function onUserAfterSave($data, $isNew, $result, $error)
	{
	}
}
