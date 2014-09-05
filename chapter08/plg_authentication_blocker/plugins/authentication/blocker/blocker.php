<?php
/**
 * Authentication Plugin for Joomla! - Blocker
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgAuthenticationBlocker
 *
 * @since  September 2014
 */
class PlgAuthenticationBlocker extends JPlugin
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
		if (isset($credentials['username']))
		{
			$this->checkDeny($credentials['username']);
		}

		return false;
	}

	/**
	 * Event method onUserLoginFailure
	 *
	 * @param   object  $response  Feedback object
	 *
	 * @return null
	 */
	public function onUserLoginFailure($response)
	{
		$this->logAttempt($response['username']);
	}

	/**
	 * Method onUserAuthenticate
	 *
	 * @param   string  $username  Users username
	 *
	 * @return null
	 */
	protected function checkDeny($username)
	{
		$failed = $this->countFailedAttempts($username);
		$failedTreshold = 10;

		if ($failed > $failedTreshold)
		{
			JError::raiseError(403, JText::_('JGLOBAL_AUTH_ACCESS_DENIED'));
		}
	}

	/**
	 * Method to count the number of failed login attempts for a specific username
	 *
	 * @param   string  $username  Username to check
	 *
	 * @return mixed
	 */
	protected function countFailedAttempts($username)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$ip = $_SERVER['REMOTE_ADDR'];

		$columns = array('id');
		$query->select($db->quoteName($columns));
		$query->from($db->quoteName('#__user_login_attempts'));
		$query->where($db->quoteName('username') . '=' . $db->quote($username));
		$query->where($db->quoteName('ip') . '=' . $db->quote($ip));
		$query->where($db->quoteName('success') . '=0');

		$db->setQuery($query);
		$db->execute();

		return $db->getNumRows();
	}

	/**
	 * Method to log a failed login attempt
	 *
	 * @param   string  $username  Username that was used in the attempt
	 * @param   int     $success   Flag indicating a success login or not
	 *
	 * @return null
	 */
	protected function logAttempt($username, $success = 0)
	{
		$db = JFactory::getDbo();
		$ip = $_SERVER['REMOTE_ADDR'];

		$columns = array('username', 'ip', 'success', 'date');
		$values = array(
			$db->quote($username),
			$db->quote($ip),
			$success,
			$db->quote(JFactory::getDate()->toSQL()),
		);

		$query = $db->getQuery(true)
			->insert($db->quoteName('#__user_login_attempts'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
		$db->setQuery($query);

		$db->query();
	}
}

