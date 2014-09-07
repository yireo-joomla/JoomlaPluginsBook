<?php
/**
 * System Plugin for Joomla! - Event Log
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgSystemEventlog
 *
 * @since  September 2014
 */
class PlgSystemEventlog extends JPlugin
{
	/**
	 * Constructor.
	 *
	 * @param   object  &$subject  The object to observe.
	 * @param   array   $config    An optional associative array of configuration settings.
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		jimport('joomla.log.log');
		JLog::addLogger(array('text_file' => 'plg_system_eventlog.log.php'), JLog::ALL, array('plg_system_eventlog'));
	}

	/**
	 * Event method onAfterInitialise
	 *
	 * @return null
	 */
	public function onAfterInitialise()
	{
		$this->log('onAfterInitialise');
	}

	/**
	 * Event method onAfterSessionStart
	 *
	 * @return null
	 */
	public function onAfterSessionStart()
	{
		$this->log('onAfterSessionStart');
	}

	/**
	 * Event method onAfterRoute
	 *
	 * @return null
	 */
	public function onAfterRoute()
	{
		$this->log('onAfterRoute');
	}

	/**
	 * Event method onAfterDispatch
	 *
	 * @return null
	 */
	public function onAfterDispatch()
	{
		$this->log('onAfterDispatch');
	}

	/**
	 * Event method onAfterRender
	 *
	 * @return null
	 */
	public function onAfterRender()
	{
		$this->log('onAfterRender');
	}

	/**
	 * Event method onAfterExecute
	 *
	 * @return null
	 */
	public function onAfterExecute()
	{
		$this->log('onAfterExecute');
	}

	/**
	 * Event method onAfterRespond
	 *
	 * @return null
	 */
	public function onAfterRespond()
	{
		$this->log('onAfterRespond');
	}

	/**
	 * Event method onBeforeCompileHead
	 *
	 * @return null
	 */
	public function onBeforeCompileHead()
	{
		$this->log('onBeforeCompileHead');
	}

	/**
	 * Event method onBeforeExecute
	 *
	 * @return null
	 */
	public function onBeforeExecute()
	{
		$this->log('onBeforeExecute');
	}

	/**
	 * Event method onBeforeRender
	 *
	 * @return null
	 */
	public function onBeforeRender()
	{
		$this->log('onBeforeRender');
	}

	/**
	 * Event method onBeforeRespond
	 *
	 * @return null
	 */
	public function onBeforeRespond()
	{
		$this->log('onBeforeRespond');
	}

	/**
	 * Event method onFork
	 *
	 * @return null
	 */
	public function onFork()
	{
		$this->log('onFork');
	}

	/**
	 * Event method onReceiveSignal
	 *
	 * @return null
	 */
	public function onReceiveSignal()
	{
		$this->log('onReceiveSignal');
	}

	/**
	 * Method to log an event
	 *
	 * @param   string  $event  Name of the event
	 *
	 * @return null
	 */
	protected function log($event)
	{
		$request = null;

		if (isset($_SERVER['REQUEST_URI']))
		{
			$request .= $_SERVER['REQUEST_URI'];
		}

		if (isset($_SERVER['HTTP_HOST']))
		{
			$request .= $_SERVER['HTTP_HOST'];
		}

		if (isset($_SERVER['REMOTE_ADDR']))
		{
			$request .= $_SERVER['REMOTE_ADDR'];
		}

		$id = md5($request);

		$log = '[' . $id . '] ' . $event;
		JLog::add($event, JLog::NOTICE, 'plg_system_eventlog');
	}
}
