<?php
/**
 * System Plugin for Joomla! - Usergroup Description
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 * 
 * Adds an usergroup description [book chapter 08]
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgSystemUsergroupdescription extends JPlugin
{
	protected $autoloadLanguage = true;

    protected $allowedContext = array(
        'com_users.group',
    );

    public function onAfterInitialise()
    {
        $input = JFactory::getApplication()->input; 
        if (JFactory::getApplication()->isAdmin()
            && $input->getCmd('option') == 'com_users'
            && $input->getCmd('view') == 'group'
            && $input->getCmd('layout') == 'edit'
        ) {

            JRequest::setVar('view', 'groupextra');
            JLoader::register('UsersViewGroupextra', __DIR__.'/views/group/view.html.php');
        }
    }

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

	public function onUserAfterSaveGroup($context, $data, $isNew)
	{
            print_r($data);exit;
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

	public function onUserAfterDeleteGroup($usergroup, $success, $msg)
	{
		if (!$success)
		{
			return false;
		}

		$usergroupId = JArrayHelper::getValue($usergroup, 'id', 0, 'int');

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

    protected function getFields($usergroupId)
    {
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $columns = array('description');
        $query->select($db->quoteName($columns));
        $query->from($db->quoteName('#__usergroup_fields'));
        $query->where($db->quoteName('usergroup_id').' = '.(int)$usergroupId);

		$db->setQuery($query);

		$results = $db->loadRowList();
        return $results;
    }

    protected function deleteFields($usergroupId)
    {
        $db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->delete($db->quoteName('#__usergroup_fields'))
			->where($db->quoteName('usergroup_id').' = '.(int)$usergroupId);
		$db->setQuery($query);

		$db->execute();
    }

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
