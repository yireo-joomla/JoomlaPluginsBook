<?php
/**
 * System Plugin for Joomla! - Custom
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgSystemCustom extends JPlugin
{
    protected $replacementStrings = array(
    );

    public function onAfterRender()
    {
        $app = JFactory::getApplication();
        if($app->isAdmin()) {
            return;
        }

        $this->replaceStrings();
    } 

    protected function replaceStrings()
    {
        $body = JResponse::getBody();
        if(!empty($this->replacementStrings)) {
            foreach($this->replacementStrings as $original => $replacement) {
                $body = str_replace($original, $replacement, $body);
            }
        }
        JResponse::setBody($body);
    }
}
