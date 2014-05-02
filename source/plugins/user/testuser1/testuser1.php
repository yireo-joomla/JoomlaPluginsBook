<?php
/**
 * User Plugin for Joomla! - First Last name
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgUserTestuser1 extends JPlugin
{
	protected $autoloadLanguage = true;

	public function onUserAfterSave($data, $isNew, $result, $error)
	{
	}
}
