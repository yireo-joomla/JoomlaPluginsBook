<?php
/**
 * System Plugin for Joomla! - Chapter 06 / Test 02
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgSystemCh06test02 extends JPlugin
{
    public function onAfterRoute()
    {
        $app = JFactory::getApplication();
        $affiliate_id = $app->input->getInt('affiliate_id');
        $url = JURI::getInstance()->current();
        $this->trackAffiliate($affiliate_id, $url);
    }

    protected function trackAffiliate($affiliate_id, $url)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $referer = $_SERVER['HTTP_REFERER'];
        
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->insert($db->quoteName('#__affiliate_requests'))
            ->set($db->quoteName('affiliate_id').' = '.$affiliate_id)
            ->set($db->quoteName('url').' = '.$db->Quote($url))
            ->set($db->quoteName('ip').' = '.$db->Quote($ip))
            ->set($db->quoteName('referer').' = '.$db->Quote($referer))
            ->set($db->quoteName('created_at').' = NOW()')
        ;
    }
}
