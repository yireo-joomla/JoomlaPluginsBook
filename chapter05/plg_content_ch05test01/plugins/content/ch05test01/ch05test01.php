<?php
/**
 * Content Plugin for Joomla! - Chapter 05 / Test 01
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgContentCh05test01
 *
 * @since  September 2014
 */
class PlgContentCh05test01 extends JPlugin
{
	/**
	 * Event method that runs on content preparation
	 *
	 * @param   JForm    $form  The form object
	 * @param   integer  $data  The form data
	 *
	 * @return bool
	 */
	public function onContentPrepareForm($form, $data)
	{
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');

			return false;
		}

		$name = $form->getName();

		if (!in_array($name, array('com_content.article')))
		{
			return true;
		}

		JForm::addFormPath(__DIR__ . '/form');
		$form->loadFile('form');

		if (!empty($data->id))
		{
			$data = $this->loadTest($data);
		}

		return true;
	}

	/**
	 * Event method that is run after an item is saved
	 *
	 * @param   string   $context  The context of the content
	 * @param   object   $item     A JTableContent object
	 * @param   boolean  $isNew    If the content is just about to be created
	 *
	 * @return  boolean  Return value
	 */
	public function onContentAfterSave($context, $item, $isNew)
	{
		if ($context != 'com_content.article')
		{
			return true;
		}

		$jinput = JFactory::getApplication()->input;
		$form = $jinput->post->get('jform', null, 'array');

		if (is_array($form) && isset($form['test']))
		{
			$test = $form['test'];
		}
		else
		{
			return true;
		}

		$content_id = $item->id;
		$this->saveTest($content_id, $context, $test);

		return true;
	}

	/**
	 * Event method run after content is deleted
	 *
	 * @param   string  $context  The context for the content passed to the plugin.
	 * @param   object  $item     A JTableContent object
	 *
	 * @return null
	 */
	public function onContentAfterDelete($context, $item)
	{
	}

	/**
	 * Event method run before content is displayed
	 *
	 * @param   string  $context  The context for the content passed to the plugin.
	 * @param   object  &$item    The content to be displayed
	 * @param   mixed   &$params  The article params
	 * @param   int     $page     Current page
	 *
	 * @return  null
	 */
	public function onContentBeforeDisplay($context, &$item, &$params, $page = 0)
	{
		if (!empty($item->id))
		{
			$item = $this->loadTest($item);
		}

		if (!empty($item->test))
		{
			$item->text .= '<p>TEST: ' . $item->test . '<p>';
		}
	}

	/**
	 * Task method to save the test value to the database
	 *
	 * @param   int     $content_id  Content ID in the #__test table
	 * @param   string  $context     The context for the content passed to the plugin.
	 * @param   mixed   $test        Test value
	 *
	 * @return  bool
	 */
	protected function saveTest($content_id, $context, $test)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select($db->quoteName('content_id'))
			->from($db->quoteName('#__test'))
			->where($db->quoteName('content_id') . '=' . $content_id);

		$db->setQuery($query);
		$db->execute();
		$exists = (bool) $db->getNumRows();

		$data = new stdClass;
		$data->content_id = $content_id;
		$data->context = $context;
		$data->test = $test;

		if ($exists)
		{
			$result = $db->updateObject('#__test', $data, 'content_id');
		}
		else
		{
			$result = $db->insertObject('#__test', $data);
		}

		return $result;
	}

	/**
	 * Task method to load the test value from the database
	 *
	 * @param   object  $item  The content that is being loaded
	 *
	 * @return mixed
	 */
	protected function loadTest($item)
	{
		if (empty($item->id))
		{
			return $item;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__test'))
			->where($db->quoteName('content_id') . '=' . $item->id);

		$db->setQuery($query);
		$testData = $db->loadAssoc();

		$item->test = $testData['test'];

		return $item;
	}
}