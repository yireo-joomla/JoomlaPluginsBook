<?php
/**
 * Content Plugin for Joomla! - Chapter 05 / Test 02
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgContentCh05test02 extends JPlugin
{
    public function __construct(& $subject, $config) 
    { 
        parent::__construct($subject, $config);
        $this->loadLanguage();

        $stylesheet = 'style.css';
        $this->addStyleSheet($stylesheet);
    }

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
            $data  = (object)$input->post->get('jform', array(), 'array'); 
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

        if(!empty($data->id))
        {
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
        if (is_array($form) && isset($form['test']))
        { 
            $test = $form['test']; 
        }
        else
        {
            return true;
        }

        $content_id = $article->id;
        $this->saveTest($content_id, $context, $test);
        return true;
    }

    public function onContentAfterDelete($context, $article)
    {
    }

    public function onContentBeforeDisplay($context, &$row, &$params, $page = 0)
    {
        if(!empty($row->id))
        { 
            $row = $this->loadTest($row); 
        } 

        if (!empty($row->test))
        { 
            $row->text .= '<p>TEST: '.$row->test.'<p>'; 
        }
    }

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
        if (is_array($form) && isset($form['test'])) { 
            $test = $form['test']; 
        } 

        if (empty($test))
        { 
            $data->setError('PLG_CONTENT_TEST05_ERROR_TEST_EMPTY'); 
            return false; 
        } 

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
        if (empty($data->id))
        {
            return $data;
        }
     
        $db = JFactory::getDbo(); 
        $query = $db->getQuery(true); 
        $query->select('*') 
            ->from($db->quoteName('#__test')) 
            ->where($db->quoteName('content_id') . ' = '.$data->id);

        $db->setQuery($query); 
        $testData = $db->loadAssoc(); 
        $data->test = $testData['test'];
        
        return $data;
    }

    protected function addStyleSheet($stylesheet)
    {
        $tmpl = JFactory::getApplication()->getTemplate();
        $document = JFactory::getDocument();

        $original_path = 'media/plg_content_ch05test02/css/';
        $tmpl_path = 'templates/'.$tmpl.'/css/plg_content_ch05test02/';

        if(file_exists(JPATH_SITE.'/'.$tmpl_path.$stylesheet)) 
        {
            $document->addStyleSheet($tmpl_path.$stylesheet);
        }
        else
        {
            $document->addStyleSheet($original_path.$stylesheet);
        }
    }
}

