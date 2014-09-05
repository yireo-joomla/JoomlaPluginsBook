<?php
/**
 * System Plugin for Joomla! - Usergroup Description
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgSystemUsergroupdescription
 *
 * @since  September 2014
 */
class PlgSystemUsergroupdescription extends JPlugin
{
	protected $autoloadLanguage = true;

	protected $allowedContext = array(
		'com_users.group',
	);

	/**
	 * Event method onAfterInitialise
	 *
	 * @return  null
	 */
	public function onAfterInitialise()
	{
		$input = JFactory::getApplication()->input;

		if (JFactory::getApplication()->isAdmin()
			&& $input->getCmd('option') == 'com_users'
			&& $input->getCmd('view') == 'group'
			&& $input->getCmd('layout') == 'edit')
		{
			JRequest::setVar('view', 'groupextra');
			JLoader::register('UsersViewGroupextra', __DIR__ . '/views/group/view.html.php');
		}
	}

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
			$usergroupId = isset($data->id) ? $data->id : 0;

			if (!isset($data->usergroupdescription) and $usergroupId > 0)
			{
				try
				{
					$fields = $this->getFields($usergroupId);
				}
				catch (RuntimeException $e)
				{
					$this->_subject->setError($e->getMessage());

					return false;
				}

				$data->usergroupdescription = array();

				foreach ($fields as $field)
				{
					$data->usergroupdescription[$fieldName] = json_decode($field[1], true);

					if ($data->usergroupdescription[$fieldName] === null)
					{
						$data->usergroupdescription[$fieldName] = $field[1];
					}
				}
			}
		}

		return true;
	}

	/**
	 * Event method onUserAfterSaveGroup
	 *
	 * @param   string  $context  The context of the content being passed to the plugin
	 * @param   array   $data     Form values
	 * @param   int     $isNew    Flag indicating whether this usergroup is new or not
	 *
	 * @return  null
	 */
	public function onUserAfterSaveGroup($context, $data, $isNew)
	{
		$usergroupId = JArrayHelper::getValue($data, 'id', 0, 'int');

		if ($usergroupId && $result && isset($data->usergroupdescription) && (count($data->usergroupdescription)))
		{
			try
			{
				$this->deleteFields($usergroupId);

				$ordering = 0;

				foreach ($data['usergroupdescription'] as $fieldName => $fieldValue)
				{
					$this->insertField($usergroupId, $fieldName, $fieldValue, $ordering);
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
	 * Event method onUserAfterDeleteGroup
	 *
	 * @param   array   $data     Data that was being deleted
	 * @param   bool    $success  Flag to indicate whether deletion was succesful
	 * @param   string  $msg      Message after deletion
	 *
	 * @return  null
	 */
	public function onUserAfterDeleteGroup($data, $success, $msg)
	{
		if (!$success)
		{
			return false;
		}

		$usergroupId = JArrayHelper::getValue($data, 'id', 0, 'int');

		if ($usergroupId)
		{
			try
			{
				$this->deleteFields($usergroupId);
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
	 * Method to get all additional fields of a specific usergroup
	 *
	 * @param   int  $usergroupId  User group Id
	 *
	 * @return  null
	 */
	protected function getFields($usergroupId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$columns = array('description');
		$query->select($db->quoteName($columns));
		$query->from($db->quoteName('#__usergroup_fields'));
		$query->where($db->quoteName('usergroup_id') . '=' . (int) $usergroupId);

		$db->setQuery($query);

		$results = $db->loadRowList();

		return $results;
	}

	/**
	 * Method to delete all records of a specific usergroup
	 *
	 * @param   int  $usergroupId  User group Id
	 *
	 * @return  null
	 */
	protected function deleteFields($usergroupId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->delete($db->quoteName('#__usergroup_fields'))
			->where($db->quoteName('usergroup_id') . '=' . (int) $usergroupId);
		$db->setQuery($query);

		$db->execute();
	}

	/**
	 * Method to insert a new name/value pair for a specific usergroup
	 *
	 * @param   int     $usergroupId  User group Id
	 * @param   string  $name         Name of field
	 * @param   string  $value        Value of field
	 *
	 * @return  null
	 */
	protected function insertField($usergroupId, $name, $value)
	{
		$db = JFactory::getDbo();

		$columns = array('usergroup_id', $name);
		$values = array($usergroupId, $db->quote($value));

		$query = $db->getQuery(true)
			->insert($db->quoteName('#__usergroup_fields'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
		$db->setQuery($query);

		$db->execute();
	}
}
