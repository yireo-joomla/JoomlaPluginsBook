<?php
/**
 * Authentication Plugin for Joomla! - Chapter 07 / Test 02
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgAuthenticationCh07test02
 *
 * @since  September 2014
 */
class PlgAuthenticationCh07test02 extends JPlugin
{
	/**
	 * Event method onUserAuthenticate
	 *
	 * @param   array   $credentials  Array holding the user credentials
	 * @param   array   $options      Authentication options
	 * @param   object  &$response    Feedback object
	 *
	 * @return bool
	 */
	public function onUserAuthenticate($credentials, $options, &$response)
	{
		$login_hash = $credentials['loginhash'];

		// @todo: Deal with the rest of this

		return true;
	}
}
