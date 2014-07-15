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
        $this->debug('context', $context);
        $this->debug('table', get_class($table));
    }

    public function onExtensionAfterSave($context, $table, $isNew)
    {
    }

    protected function debug($message, $variable = null)
    {
        if (!empty($variable)) $message .= var_export($variable, true);
        $message .= "\n";
        file_put_contents('/tmp/jisse', $message, FILE_APPEND);
    }
}
