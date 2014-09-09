<?php
/**
 * Authentication Plugin for Joomla! - IMAP
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgAuthenticationImap
 *
 * @since  September 2014
 */
class PlgAuthenticationImap extends JPlugin
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
		$response->type = 'imap';

		if (empty($credentials['password']))
		{
			$response->status = JAuthentication::STATUS_FAILURE;
			$response->error_message = JText::_('JGLOBAL_AUTH_EMPTY_PASS_NOT_ALLOWED');

			return false;
		}

		if ($this->doAuthenticate($credentials) == true)
		{
			$response->status = JAuthentication::STATUS_SUCCESS;
			$response->error_message = '';

			return true;
		}
		else
		{
			$response->status = JAuthentication::STATUS_FAILURE;
			$response->error_message = JText::_('JGLOBAL_AUTH_FAIL');

			return false;
		}
	}

	/**
	 * Method to authenticate the credentials
	 *
	 * @param   array  $credentials  Array holding the user credentials
	 *
	 * @return bool
	 */
	protected function doAuthenticate($credentials)
	{
		$username = $credentials['username'];
		$password = $credentials['password'];

		$server = $this->params->get('server');
		$port = (int) $this->params->get('port');

		if ($this->params->get('ssl') === '1')
		{
			$suffix = '/ssl/novalidate-cert';
		}
		else
		{
			$suffix = '/notls';
		}

		$mailbox = '{' . $server . ':' . $port . $suffix . '}';
		$mbox = imap_open($mailbox, $username, $password);

		if ($mbox === false)
		{
			return false;
		}

		return true;
	}
}
