<?php
/**
 * Content Plugin for Joomla! - Test 05
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgContentTest05 extends JPlugin
{
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
		$form->loadFile('form', false);

        if(!empty($data->id)) {
            $data = $this->loadTest($data);
        }

        return true;
    }

    public function onContentAfterSave($context, $article, $isNew)
    {
        if ($context != 'com_content.article') 
        {
            return true;
        }

        $jinput = JFactory::getApplication()->input;
        $form = $jinput->post->get('jform', null, 'array');

        if (is_array($form) && isset($form['test'])) {
            $test = $form['test'];
        }

        $content_id = $article->id;
        $this->saveTest($content_id, $context, $test);
        return true;
    }
    
    protected function saveTest($content_id, $context, $test)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName('content_id'))
            ->from($db->quoteName('#__test'))
            ->where($db->quoteName('content_id') . ' = '.$content_id);

        $db->setQuery($query);
        $db->execute();
        $exists = (bool)$db->getNumRows();

        $data = new stdClass();
        $data->content_id = $content_id;
        $data->context = $context;
        $data->test = $test;

        if($exists) {
            $result = $db->updateObject('#__test', $data, 'content_id');
        } else {
            $result = $db->insertObject('#__test', $data);
        }
    }

    protected function loadTest($data)
    {
        if ($data->id > 0)
        {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*')
                ->from($db->quoteName('#__test'))
                ->where($db->quoteName('content_id') . ' = '.$data->id);
            $db->setQuery($query);
            $testData = $db->loadAssoc();
            $data->test = $testData['test'];
        }

        return $data;
    }

    public function onContentBeforeDisplay($context, &$row, &$params, $page = 0)
    {
        if(!empty($row->id)) {
            $row = $this->loadTest($row);
        }

        if (!empty($row->test)) {
            $row->text .= '<p>TEST: '.$row->test.'<p>';
        }
    }
}
