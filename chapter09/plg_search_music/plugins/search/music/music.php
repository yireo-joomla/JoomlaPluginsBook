<?php
/**
 * Search Plugin for Joomla! - Music
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgSearchMusic
 *
 * @since  September 2014
 */
class PlgSearchMusic extends JPlugin
{
	/**
	 * Event method onContentSearchAreas
	 *
	 * @return  array
	 */
	public function onContentSearchAreas()
	{
		static $areas = array(
			'songs' => 'PLG_SEARCH_MUSIC_AREA_SONGS',
			'artists' => 'PLG_SEARCH_MUSIC_AREA_ARTISTS',
		);

		return $areas;
	}

	/**
	 * Event method onContentSearch
	 *
	 * @param   string  $text      The search phrase
	 * @param   string  $phrase    String how to match the phrase
	 * @param   string  $ordering  String describing the ordering
	 * @param   mixed   $areas     Array of search areas or null
	 *
	 * @return  null
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		if (empty($text))
		{
			return array();
		}

		if (is_array($areas))
		{
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
			{
				return array();
			}
		}

		$results = array();

		if (empty($areas) || in_array('songs', $areas))
		{
			$songs = $this->getSongs($text, $phrase, $ordering);

			if (!empty($songs))
			{
				$results = array_merge($results, $songs);
			}
		}

		if (empty($areas) || in_array('artists', $areas))
		{
			$artists = $this->getArtists($text, $phrase, $ordering);

			if (!empty($artists))
			{
				$results = array_merge($results, $artists);
			}
		}


		return $results;
	}

	/**
	 * Method to fetch all songs matching a certain search phrase
	 *
	 * @param   string  $text      The search phrase
	 * @param   string  $phrase    String how to match the phrase
	 * @param   string  $ordering  String describing the ordering
	 *
	 * @return  array
	 */
	protected function getSongs($text, $phrase='', $ordering='')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('s.*');
		$query->from($db->quoteName('#__music_songs') . ' AS s');
		$query->where('s.' . $db->quoteName('state') . ' = 1');

		$words = explode(' ', $text);

		foreach ($words as $wordIndex => $word)
		{
			$words[$wordIndex] = trim($word);
		}

		$searchFields = array('title', 'text');
		$where = array();

		switch ($phrase)
		{
			case 'exact':

				foreach ($searchFields as $searchField)
				{
					$search = $db->Quote('%' . $text . '%');
					$searchField = $db->quoteName($searchField);
					$where[] = 's.' . $searchField . ' LIKE ' . $search;
				}
				break;

			case 'all':
				foreach ($searchFields as $searchField)
				{
					$searchField = $db->quoteName($searchField);
					$wordWhere = array();

					foreach ($words as $word)
					{
						$search = $db->Quote('%' . $word . '%');
						$wordWhere[] = 's.' . $searchField . ' LIKE ' . $search;
					}

					$where[] = '(' . implode(' AND ', $wordWhere) . ')';
				}
				break;

			case 'any':
			default:
				foreach ($searchFields as $searchField)
				{
					$searchField = $db->quoteName($searchField);
					$wordWhere = array();

					foreach ($words as $word)
					{
						$search = $db->Quote('%' . $word . '%');
						$wordWhere[] = 's.' . $searchField . ' LIKE ' . $search;
					}

					$where[] = '(' . implode(' OR ', $wordWhere) . ')';
				}
				break;
		}

		$query->where('(' . implode(' OR ', $where) . ')');

		switch ($ordering)
		{
			case 'alpha':
				$query->order('title ASC');
				break;

			case 'popular':
			case 'category':
			case 'oldest':
			case 'newest':
			default:
				break;
		}

		$db->setQuery($query);
		echo str_replace('#_', $db->getPrefix(), $db->getQuery()) . '<br/>';
		$results = $db->loadObjectList();

		foreach ($results as $result)
		{
			$result->href = 'index.php?option=com_music&view=song&id=' . $result->id;
			$result->section = JText::_('PLG_SEARCH_MUSIC_AREA_SONGS');
			$result->created = 'today';
		}

		return $results;
	}

	/**
	 * Method to fetch all artists matching a certain search phrase
	 *
	 * @param   string  $text      The search phrase
	 * @param   string  $phrase    String how to match the phrase
	 * @param   string  $ordering  String describing the ordering
	 *
	 * @return  array
	 */
	protected function getArtists($text, $phrase = '', $ordering = '')
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from($db->quoteName('#__music_artists') . ' AS a');
		$query->where('a.' . $db->quoteName('name') . ' LIKE ' . $db->quote('%' . $text . '%'));

		$db->setQuery($query);
		echo str_replace('#_', $db->getPrefix(), $db->getQuery());
		$results = $db->loadObjectList();

		foreach ($results as $result)
		{
			$result->title = $result->name;
			$result->text = $result->name;
			$result->href = 'index.php?option=com_music&view=article&id=' . $result->id;
			$result->section = JText::_('PLG_SEARCH_MUSIC_AREA_ARTISTS');
			$result->created = 'today';
		}

		return $results;
	}
}
