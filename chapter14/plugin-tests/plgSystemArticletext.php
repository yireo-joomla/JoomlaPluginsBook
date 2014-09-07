<?php
/**
 * PHPUnit test for Articletext System Plugin
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

/**
 * Class PlgSystemArticletextTest
 *
 * @since  September 2014
 */
class PlgSystemArticletextTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Method to initialize Joomla application
	 *
	 * @return null
	 */
	public function initJoomla()
	{
		$_SERVER['HTTP_HOST'] = null;

		// Neccessary definitions
		define('_JEXEC', 1);
		define('DOCUMENT_ROOT', dirname(dirname(__FILE__)) . '/');
		define('JPATH_BASE', DOCUMENT_ROOT);

		if (!is_file(JPATH_BASE . '/includes/framework.php'))
		{
			die('Incorrect Joomla! base-path');
		}

		chdir(JPATH_BASE);

		// Include the framework
		require_once JPATH_BASE . '/includes/defines.php';
		require_once JPATH_BASE . '/includes/framework.php';

		jimport('joomla.environment.request');
		jimport('joomla.database.database');

		$app = JFactory::getApplication('site');
		$app->initialise();

		jimport('joomla.plugin.helper');
		JPluginHelper::importPlugin('system');
	}

	/**
	 * PHPUnit test for replacing tags
	 *
	 * @return null
	 */
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

		foreach ($tests as $test)
		{
			$textBefore = $test['text'];
			$textAfter = $plugin->replaceTags($testBefore);

			if ($test['articletext'] == true)
			{
				$this->assertNotEquals($testBefore, $textAfter);
			}
			else
			{
				$this->assertEquals($testBefore, $textAfter);
			}
		}
	}
}

