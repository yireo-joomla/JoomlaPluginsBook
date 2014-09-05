<?php
/**
 * System Plugin for Joomla! - User group description
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

JLoader::register('UsersViewGroup', JPATH_ADMINISTRATOR . '/components/com_users/views/group/view.html.php');
JLoader::register('UsersModelGroup', JPATH_ADMINISTRATOR . '/components/com_users/models/group.php');

/**
 * Class UsersViewGroupextra
 *
 * @since  September 2014
 */
class UsersViewGroupextra extends UsersViewGroup
{
	/**
	 * Override the display method
	 *
	 * @param   string  $tpl  Layout indicator
	 *
	 * @return void
	 */
	public function display($tpl = null)
	{
		$this->addTemplatePath(__DIR__ . '/tmpl/');

		parent::display($tpl);
	}
}

/**
 * Class UsersModelGroupextra
 *
 * @since  September 2014
 */
class UsersModelGroupextra extends UsersModelGroup {}
