<?php

class plgSystemArticletextTest extends PHPUnit_Framework_TestCase
{
    public function initJoomla()
    {
        $_SERVER['HTTP_HOST'] = null;
         
        // Neccessary definitions
        define('_JEXEC', 1);
        define('DOCUMENT_ROOT', dirname(dirname(__FILE__)).'/');
        define('JPATH_BASE', DOCUMENT_ROOT);

        if(!is_file(JPATH_BASE.'/includes/framework.php')) {
            die('Incorrect Joomla! base-path');
        }
        chdir(JPATH_BASE);
         
        // Include the framework
        require_once(JPATH_BASE.'/includes/defines.php');
        require_once(JPATH_BASE.'/includes/framework.php');

        jimport('joomla.environment.request');
        jimport('joomla.database.database');
         
        $app = JFactory::getApplication('site');
        $app->initialise();

        jimport('joomla.plugin.helper');
        JPluginHelper::importPlugin('system');
    }

    public function testReplaceTags()
    {
        $this->initJoomla();
        $dispatcher = JEventDispatcher::getInstance();
        $plugin = new plgSystemArticletext($dispatcher, array());

        $tests = array();
        $tests[] = array(
            'text' => 'test without articletext',
            'articletext' => false,
        );
        $tests[] = array(
            'text' => 'test with articletext: {articletext id=2}',
            'articletext' => true,
        );

        foreach($tests as $test) {
            $textBefore = $test['text'];
            $textAfter = $plugin->replaceTags($testBefore);
        
            if($test['articletext'] == true) {
                $this->assertNotEquals($testBefore, $textAfter);
            } else {
                $this->assertEquals($testBefore, $textAfter);
            }
        }
    }
}

