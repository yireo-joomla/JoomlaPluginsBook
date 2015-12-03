<?php
/**
 * System Plugin for Joomla! - Chapter 06 / Test 02
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class plgSystemCh06test02
 *
 * @since  September 2014
 */
class PlgSystemCh06test02 extends JPlugin
{
	/**
	 * Event method onAfterRoute
	 *
	 * @return null
	 */
	public function onAfterRoute()
	{
		$app = JFactory::getApplication();
		$affiliate_id = $app->input->getInt('affiliate_id');
		$url = JURI::getInstance()->current();
		$this->trackAffiliate($affiliate_id, $url);
	}

	/**
	 * Method to insert an affiliate request to the database
	 *
	 * @param   int     $affiliate_id  Affiliate ID
	 * @param   string  $url           Current URL
	 *
	 * @return null
	 */
	protected function trackAffiliate($affiliate_id, $url)
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$referer = $_SERVER['HTTP_REFERER'];

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->insert($db->quoteName('#__affiliate_requests'))
			->columns(
				array(
					$db->quoteName('affiliate_id'), 
					$db->quoteName('url'), 
					$db->quoteName('ip'), 
					$db->quoteName('referer'), 
					$db->quoteName('created_at')
				)
			)
			->values(
				implode(', ', (
						array(
							(int) $affiliate_id,
							$db->quote($url),
							$db->quote($ip),
							$db->quote($referer),
							$db->quote(JFactory::getDate()->toSql())
						)
					)
				)
			);
		$db->setQuery($query);
		$db->execute();
	}
}
