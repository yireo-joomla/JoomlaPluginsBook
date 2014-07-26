<?php
defined('_JEXEC') or die;

JLoader::register('UsersViewGroup', JPATH_ADMINISTRATOR.'/components/com_users/views/group/view.html.php');
JLoader::register('UsersModelGroup', JPATH_ADMINISTRATOR.'/components/com_users/models/group.php');

class UsersViewGroupextra extends UsersViewGroup
{
    public function display($tpl = null)
    {
        $this->addTemplatePath(__DIR__ . '/tmpl/');
        parent::display($tpl);
    }
}

class UsersModelGroupextra extends UsersModelGroup {}
