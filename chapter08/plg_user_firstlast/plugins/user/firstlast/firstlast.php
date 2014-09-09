<?php
/**
 * User Plugin for Joomla! - First Last name
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgUserFirstlast
 *
 * @since  September 2014
 */
class PlgUserFirstlast extends JPlugin
{
	/**
	 * Load the language file on instantiation (for Joomla! 3.X only)
	 *
	 * @var    boolean
	 * @since  3.3
	 */
	protected $autoloadLanguage = true;

	/**
	 * Definition of which contexts to allow in this plugin
	 *
	 * @var    array
	 */
	protected $allowedContext = array(
		'com_users.profile',
		'com_users.user',
		'com_users.registration',
		'com_admin.profile',
	);

	/**
	 * Event method onContentPrepareForm
	 *
	 * @param   mixed  $form  JForm instance
	 * @param   array  $data  Form values
	 *
	 * @return  bool
	 */
	public function onContentPrepareForm($form, $data)
	{
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');

			return false;
		}

		$context = $form->getName();

		if (!in_array($context, $this->allowedContext))
		{
			return true;
		}

		JForm::addFormPath(__DIR__ . '/form');
		$form->loadFile('form', false);

		return true;
	}

	/**
	 * Event method onContentPrepareData
	 *
	 * @param   string  $context  The context of the content being passed to the plugin
	 * @param   array   $data     Form values
	 *
	 * @return  bool
	 */
	public function onContentPrepareData($context, $data)
	{
		if (!in_array($context, $this->allowedContext))
		{
			return true;
		}

		if (is_object($data))
		{
			$userId = isset($data->id) ? $data->id : 0;

			if (!isset($data->firstlast) and $userId > 0)
			{
				try
				{
					$fields = $this->getFields($userId);
				}
				catch (RuntimeException $e)
				{
					$this->_subject->setError($e->getMessage());

					return false;
				}

				$data->firstlast = array();

				foreach ($fields as $field)
				{
					$fieldName = str_replace('firstlast.', '', $field[0]);
					$data->firstlast[$fieldName] = json_decode($field[1], true);

					if ($data->firstlast[$fieldName] === null)
					{
						$data->firstlast[$fieldName] = $field[1];
					}
				}
			}
		}

		if (empty($data->firstlast['firstname']) && empty($data->firstlast['lastname']) && !empty($data->name))
		{
			$name = explode(' ', $data->name);

			if (count($name) >= 2)
			{
				$data->firstlast['firstname'] = trim(array_shift($name));
				$data->firstlast['lastname'] = trim(implode(' ', $name));
			}
		}

		if (!empty($data->firstlast['firstname']) && !empty($data->firstlast['lastname']) && empty($data->name))
		{
			$data->name = $data->firstlast['firstname'] . ' ' . $data->firstlast['lastname'];
		}

		return true;
	}

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
		$userId = JArrayHelper::getValue($data, 'id', 0, 'int');

		if ($userId && $success && isset($data['firstlast']) && (count($data['firstlast'])))
		{
			try
			{
				$this->deleteFields($userId);

				$ordering = 0;

				foreach ($data['firstlast'] as $fieldName => $fieldValue)
				{
					$this->insertField($userId, $fieldName, $fieldValue, $ordering);
					$ordering++;
				}
			}
			catch (RuntimeException $e)
			{
				$this->_subject->setError($e->getMessage());

				return false;
			}
		}

		return true;
	}

	/**
	 * Event method onUserAfterDelete
	 *
	 * @param   array   $data     Data that was being deleted
	 * @param   bool    $success  Flag to indicate whether deletion was succesful
	 * @param   string  $msg      Message after deletion
	 *
	 * @return  null
	 *
	 * @return  null
	 */
	public function onUserAfterDelete($data, $success, $msg)
	{
		if (!$success)
		{
			return false;
		}

		$userId = JArrayHelper::getValue($data, 'id', 0, 'int');

		if ($userId)
		{
			try
			{
				$this->deleteFields($userId);
			}
			catch (Exception $e)
			{
				$this->_subject->setError($e->getMessage());

				return false;
			}
		}

		return true;
	}

	/**
	 * Event method onUserLoad
	 *
	 * @param   JUser  $user  JUser instance
	 *
	 * @return  null
	 */
	public function onUserLoad($user)
	{
		if (empty($user) || empty($user->id))
		{
			return false;
		}

		try
		{
			$fields = $this->getFields($user->id);
		}
		catch (Exception $e)
		{
			$this->_subject->setError($e->getMessage());

			return false;
		}

		foreach ($fields as $field)
		{
			$fieldName = str_replace('firstlast.', '', $field[0]);
			$fieldValue = $field[1];
			$user->set($fieldName, $fieldValue);
		}
	}

	/**
	 * Method to get records belonging to a specific user
	 *
	 * @param   int  $userId  User ID
	 *
	 * @return  array
	 */
	protected function getFields($userId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$columns = array('profile_key', 'profile_value');
		$query->select($db->quoteName($columns));
		$query->from($db->quoteName('#__user_profiles'));
		$query->where($db->quoteName('profile_key') . ' LIKE ' . $db->quote('firstlast.%'));
		$query->where($db->quoteName('user_id') . '=' . (int) $userId);
		$query->order('ordering ASC');

		$db->setQuery($query);

		$results = $db->loadRowList();

		return $results;
	}

	/**
	 * Method to delete all records belonging to a specific user
	 *
	 * @param   int  $userId  User ID
	 *
	 * @return null
	 */
	protected function deleteFields($userId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->delete($db->quoteName('#__user_profiles'))
			->where($db->quoteName('user_id') . '=' . (int) $userId)
			->where($db->quoteName('profile_key') . ' LIKE ' . $db->quote('firstlast.%'));
		$db->setQuery($query);

		$db->execute();
	}

	/**
	 * Method to insert records belonging to a specific user
	 *
	 * @param   int     $userId    User ID
	 * @param   string  $name      Field name
	 * @param   string  $value     Field value
	 * @param   int     $ordering  Ordering number
	 *
	 * @return null
	 */
	protected function insertField($userId, $name, $value, $ordering)
	{
		$db = JFactory::getDbo();

		$columns = array('user_id', 'profile_key', 'profile_value', 'ordering');
		$values = array($userId, $db->quote('firstlast.' . $name), $db->quote($value), $ordering);

		$query = $db->getQuery(true)
			->insert($db->quoteName('#__user_profiles'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
		$db->setQuery($query);

		$db->execute();
	}
}
