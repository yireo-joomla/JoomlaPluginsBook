<?php
/**
 * Authentication Plugin for Joomla! - Test 07
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgAuthenticationTest07 extends JPlugin
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
        return false;
        $username = $credentials['username'];
        $password = $credentials['password'];
        
        $url = 'https://api.twitter.com/oauth2/token';
        $post = array('grant_type' => 'client_credentials');
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $result = curl_exec($ch);
        curl_close($ch);

        if (empty($result)) {
            return false;
        }

        $data = json_decode($result, true);
        print_r($data);
        exit;
        if (!empty($result['errors'])) {
            return false;
        }

        print_r($data);
        exit;
    }
}
