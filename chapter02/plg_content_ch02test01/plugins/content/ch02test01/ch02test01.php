<?php
/**
 * Content Plugin for Joomla! - Chapter 02 / Test 01
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgContentCh02test01 extends JPlugin
{
    public function onContentBeforeDisplay($context, &$row, &$params, $page = 0)
    {
        $row->title = $row->title.' [test01]'; 
        $row->text = $row->text.'<p>[test01]</p>';
    }

    protected function doSomethingWithinThisPlugin()
    {
    }
}
