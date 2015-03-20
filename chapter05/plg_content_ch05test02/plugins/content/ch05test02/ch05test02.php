<?php
/**
 * Content Plugin for Joomla! - Chapter 05 / Test 02
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgContentCh05test02
 *
 * @since  September 2014
 */
class PlgContentCh05test02 extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  Instance of JEventDispatcher
	 * @param   array   $config    Configuration
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();

		$stylesheet = 'style.css';
		$this->addStyleSheet($stylesheet);
	}

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

		$include_categories = $this->params->get('include_categories');

		if (empty($include_categories))
		{
			return true;
		}

		if (empty($data))
		{
			$input = JFactory::getApplication()->input;
			$data  = (object) $input->post->get('jform', array(), 'array');
		}

		if (is_array($data))
		{
			jimport('joomla.utilities.arrayhelper');
			$data = JArrayHelper::toObject($data);
		}

		if (empty($data->catid))
		{
			return true;
		}

		if (!in_array($data->catid, $include_categories))
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
	 * @return	boolean  Return value
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
	 * @param   mixed   &$params  The item params
	 * @param   int     $page     Current page
	 *
	 * @return	null
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
	 * Event method run before content is saved
	 *
	 * @param   string  $context  The context for the content passed to the plugin.
	 * @param   object  $data     The content to be saved
	 * @param   int     $isNew    Flag indicating this item is new or not
	 *
	 * @return	null
	 */
	public function onContentBeforeSave($context, $data, $isNew)
	{
		if (!in_array($context, array('com_content.article')))
		{
			return true;
		}

		$include_categories = $this->params->get('include_categories');

		if (empty($include_categories))
		{
			return true;
		}

		if (!in_array($data->catid, $include_categories))
		{
			return true;
		}

		$input = JFactory::getApplication()->input;
		$form = $input->post->get('jform', null, 'array');

		$test = null;

		if (is_array($form) && isset($form['test']))
		{
			$test = $form['test'];
		}

		if (empty($test))
		{
			$data->setError(JText::_('PLG_CONTENT_TEST05_ERROR_TEST_EMPTY'));

			return false;
		}

		return true;
	}

	/**
	 * Task method to save the test value to the database
	 *
	 * @param   int     $content_id  Content ID in the #__test table
	 * @param   string  $context     The context for the content passed to the plugin.
	 * @param   mixed   $test        Test value
	 *
	 * @return	bool
	 */
	protected function saveTest($content_id, $context, $test)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select($db->quoteName('content_id'))
			->from($db->quoteName('#__test'))
			->where($db->quoteName('content_id') . ' = ' . $content_id);

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
	}

	/**
	 * Task method to load the test value from the database
	 *
	 * @param   object  $data  The content that is being loaded
	 *
	 * @return mixed
	 */
	protected function loadTest($data)
	{
		if (empty($data->id))
		{
			return $data;
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__test'))
			->where($db->quoteName('content_id') . ' = ' . $data->id);

		$db->setQuery($query);
		$testData = $db->loadAssoc();
		$data->test = $testData['test'];

		return $data;
	}

	/**
	 * Task method to add a stylesheet
	 *
	 * @param   string  $stylesheet  Stylesheet file
	 *
	 * @return mixed
	 */
	protected function addStyleSheet($stylesheet)
	{
		$tmpl = JFactory::getApplication()->getTemplate();
		$document = JFactory::getDocument();

		$original_path = 'media/plg_content_ch05test02/css/';
		$tmpl_path = 'templates/' . $tmpl . '/css/plg_content_ch05test02/';

		if (file_exists(JPATH_SITE . '/' . $tmpl_path . $stylesheet))
		{
			$document->addStyleSheet($tmpl_path . $stylesheet);
		}
		else
		{
			$document->addStyleSheet($original_path . $stylesheet);
		}
	}
}
