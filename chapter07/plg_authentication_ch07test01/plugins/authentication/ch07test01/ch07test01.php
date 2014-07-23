<?php
/**
 * Authentication Plugin for Joomla! - Chapter 07 / Test 01
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgAuthenticationCh07test01 extends JPlugin
{
    public function onUserAuthenticate($credentials, $options, &$response)
    {
        $response->type = 'foobar';

        if (empty($credentials['password'])) 
        { 
            $response->status = JAuthentication::STATUS_FAILURE; 
            $response->error_message = JText::_('JGLOBAL_AUTH_EMPTY_PASS_NOT_ALLOWED'); 
            return false; 
        }

        if($this->doAuthenticate($credentials) == true)
        {
            $response->status = JAuthentication::STATUS_SUCCESS; 
            $response->error_message = '';
            return true;
        }
        else 
        {
            $response->status = JAuthentication::STATUS_FAILURE; 
            $response->error_message = JText::_('JGLOBAL_AUTH_FAIL');
            return false;
        }
    }

    protected function doAuthenticate($credentials)
    {
        if($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
            return true;
        }
        return false;
    }
}
