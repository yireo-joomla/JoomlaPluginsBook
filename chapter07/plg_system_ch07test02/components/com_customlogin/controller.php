<?php 
defined('_JEXEC') or die; 
require_once JPATH_SITE.'/components/com_users/controller.php'; 
require_once JPATH_SITE.'/components/com_users/controllers/user.php';
 
class CustomloginController extends UsersControllerUser 
{ 
    public function login() 
    {
        // Custom login procedure here ...?
        $rt = parent::login();
        // ... or here?
        return $rt;
    }
}
