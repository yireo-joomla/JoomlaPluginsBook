<?php
/**
 * Authentication Plugin for Joomla! - Chapter 07 / Test 02
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */
    
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgAuthenticationCh07test02 extends JPlugin
{
    public function onUserAuthenticate($credentials, $options, &$response)
    {
        $login_hash = $credentials['loginhash']; 
        // @todo: Deal with the rest of this
    }
}
