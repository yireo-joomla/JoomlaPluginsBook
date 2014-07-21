<?php
/**
 * Content Plugin for Joomla! - Chapter 04 / Test 02
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('JPATH_BASE') or die();

jimport('joomla.form.formfield');
class JFormFieldTestselect1 extends JFormField
{
    public $type = 'Test select 1';

    protected function getInput() 
    { 
        $options = array(); 
        $options[] = JHTML::_('select.option', 'sample01', JText::_('JFORM_FIELDTYPE_TESTSELECT1_SAMPLE01'), 'value', 'text');
        $options[] = JHTML::_('select.option', 'sample02', JText::_('JFORM_FIELDTYPE_TESTSELECT1_SAMPLE02'), 'value', 'text');
        return JHTML::_('select.genericlist', $options, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->name); 
    }
}
