<?php
/**
 * System Plugin for Joomla! - Event Log
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgSystemEventlog extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

        jimport('joomla.log.log');
        JLog::addLogger(array('text_file' => 'plg_system_eventlog.log.php'), JLog::ALL, array('plg_system_eventlog'));
    }

    public function onAfterInitialise() { $this->log('onAfterInitialise'); }
    public function onAfterSessionStart() { $this->log('onAfterSessionStart'); }
    public function onAfterRoute() { $this->log('onAfterRoute'); }
    public function onAfterDispatch() { $this->log('onAfterDispatch'); }
    public function onAfterExecute() { $this->log('onAfterExecute'); }
    public function onAfterRespond() { $this->log('onAfterRespond'); }
    public function onBeforeCompileHead() { $this->log('onBeforeCompileHead'); }
    public function onBeforeExecute() { $this->log('onBeforeExecute'); }
    public function onBeforeRender() { $this->log('onBeforeRender'); }
    public function onBeforeRespond() { $this->log('onBeforeRespond'); }
    public function onFork() { $this->log('onFork'); }
    public function onReceiveSignal() { $this->log('onReceiveSignal'); }

    protected function log($event)
    {
        $request = null;
        if(isset($_SERVER['REQUEST_URI'])) $request .= $_SERVER['REQUEST_URI']; 
        if(isset($_SERVER['HTTP_HOST'])) $request .= $_SERVER['HTTP_HOST']; 
        if(isset($_SERVER['REMOTE_ADDR'])) $request .= $_SERVER['REMOTE_ADDR']; 
        $id = md5($request);

        $log = '['.$id.'] '.$event;
        JLog::add($event, JLog::NOTICE, 'plg_system_eventlog');
    }
}
