<?php
/**
 * Content Plugin for Joomla! - Chapter 03 / Test 01
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgContentCh03test01 extends JPlugin
{
    protected $autoloadLanguage = true; // For Joomla! 3.X

    public function __construct(&$subject, $config) 
    { 
        parent::__construct($subject, $config); 
        $this->loadLanguage(); // For Joomla! 2.5
    }

    public function onContentBeforeDisplay($context, &$row, &$params, $page = 0)
    {
        $test = JText::_('PLG_CONTENT_CH03TEST01_TEST'); 
        $row->title = $row->title.' ['.$test.']'; 
        $row->text = $row->text.'<p>['.$test.']</p>'; 
    }
}
