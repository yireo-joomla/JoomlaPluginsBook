<?php
/**
 * Content Plugin for Joomla! - Chapter 03 / Test 01
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgContentCh03test01
 *
 * @since  September 2014
 */
class PlgContentCh03test01 extends JPlugin
{
	/**
	 * Load the language file on instantiation (for Joomla! 3.X only)
	 *
	 * @var    boolean
	 * @since  3.3
	 */
	protected $autoloadLanguage = true;

	/**
	 * Constructor.
	 *
	 * @param   object  &$subject  The object to observe.
	 * @param   array   $config    An optional associative array of configuration settings.
	 *
	 * @since   1.5
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		// Load the language file on instantiation (for Joomla! 2.5 and Joomla! 3.x)
		$this->loadLanguage();
	}

	/**
	 * Event method onContentBeforeDisplay
	 *
	 * @param   string  $context  The context of the content being passed to the plugin
	 * @param   mixed   &$row     An object with a "text" property
	 * @param   mixed   &$params  Additional parameters
	 * @param   int     $page     Optional page number
	 *
	 * @return  null
	 */
	public function onContentBeforeDisplay($context, &$row, &$params, $page = 0)
	{
		$test = JText::_('PLG_CONTENT_CH03TEST01_TEST');
		$row->title = $row->title . ' [' . $test . ']';
		$row->text = $row->text . '<p>[' . $test . ']</p>';
	}
}
