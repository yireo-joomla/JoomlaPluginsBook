<?php
/**
 * Extension Plugin for Joomla! - Custom
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgExtensionCustom extends JPlugin
{
    public function onExtensionBeforeSave($context, $table, $isNew)
    {
        $this->debug('[onExtensionBeforeSave] context', $context);
        $this->debug('[onExtensionBeforeSave] table', get_class($table));
    }

    public function onExtensionAfterSave($context, $table, $isNew)
    {
        $this->debug('[onExtensionAfterSave] context', $context);
        $this->debug('[onExtensionAfterSave] table', get_class($table));
    }

    protected function debug($message, $variable)
    {
        if (!empty($variable)) $message .= var_export($variable, true);
        $message .= "\n";

        JLog::add($message, JLog::NOTICE, 'plg_extension_custom');
    }
}
