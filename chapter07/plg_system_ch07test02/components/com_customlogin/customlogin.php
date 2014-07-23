<?php 
defined('_JEXEC') or die; 

$controller = JControllerLegacy::getInstance('Customlogin'); 
$controller->execute(JFactory::getApplication()->input->get('task')); 
$controller->redirect();
