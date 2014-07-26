<?php
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_users/controller.php';
require_once JPATH_SITE.'/components/com_users/controllers/user.php';

class CustomloginController extends UsersControllerUser
{
	public function login()
	{
		JSession::checkToken('post') or jexit(JText::_('JInvalid_Token'));

		$app = JFactory::getApplication();

		// Populate the data array:
		$data = array();
		$data['return'] = base64_decode($app->input->post->get('return', '', 'BASE64'));
		$data['username'] = JRequest::getVar('username', '', 'method', 'username');
		$data['password'] = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
		$data['secretkey'] = JRequest::getString('secretkey', '');

		// Set the return URL if empty.
		if (empty($data['return']))
		{
			$data['return'] = 'index.php?option=com_users&view=profile';
		}

		// Set the return URL in the user state to allow modification by plugins
		$app->setUserState('users.login.form.return', $data['return']);

		// Get the log in options.
		$options = array();
		$options['remember'] = $this->input->getBool('remember', false);
		$options['return'] = $data['return'];

		// Get the log in credentials.
		$credentials = array();
		$credentials['username']  = $data['username'];
		$credentials['password']  = $data['password'];
		$credentials['secretkey'] = $data['secretkey'];

		// Perform the log in.
		if (true === $app->login($credentials, $options))
		{
			// Success
			if ($options['remember'] = true)
			{
				$app->setUserState('rememberLogin', true);
			}

			$app->setUserState('users.login.form.data', array());
			$app->redirect(JRoute::_($app->getUserState('users.login.form.return'), false));
		}
		else
		{
			// Login failed !
			$data['remember'] = (int) $options['remember'];
			$app->setUserState('users.login.form.data', $data);
			$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
		}
	}
}
