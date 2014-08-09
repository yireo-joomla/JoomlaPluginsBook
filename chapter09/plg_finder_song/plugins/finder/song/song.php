<?php
/**
 * Finder Plugin for Joomla! - Song
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/adapter.php';

class PlgFinderSong extends FinderIndexerAdapter
{
	protected $context = 'Song';

	protected $extension = 'com_music';

	protected $layout = 'song';

	protected $type_title = 'Song';

	protected $table = '#__music_songs';

	protected $autoloadLanguage = true;

	protected function index(FinderIndexerResult $item, $format = 'html')
    {
        //if (JComponentHelper::isEnabled($this->extension) == false)
        //{
        //    return;
        //}
    
        // Prepare the item
        $item->access = 1;

        // Define these items as songs
        $item->addTaxonomy('Type', 'Song');

        // Add artist information
        $item->addInstruction(FinderIndexer::META_CONTEXT, 'artist');
        $item->addTaxonomy('Artist', $item->artist);

        // Set language
		//$item->setLanguage();
		//$item->addTaxonomy('Language', $item->language);

        // Set URLs
        $item->route = 'index.php?option=com_music&view=song&id='.$item->id;
		$item->url = $item->route;
		$item->path = FinderIndexerHelper::getContentPath($item->route);

        // Allow others to hook into our $item as well
		FinderIndexerHelper::getContentExtras($item);

        $this->indexer->index($item);
    }

	protected function setup()
    {
        //require_once JPATH_SITE.'/components/com_music/helpers/route.php';

        return true;
    }

	protected function getListQuery($query = null)
    {
        $db = JFactory::getDbo(); 
        $query = $db->getQuery(true); 
        $query->select('a.*');
        $query->select('p.name AS artist'); 
        $query->from($db->quoteName('#__music_songs', 'a'));
        $query->innerJoin($db->quoteName('#__music_artists', 'p')
            .' ON ('.$db->quoteName('a.artist_id').'='.$db->quoteName('p.id').')'
        );

        $debugQuery = str_replace('#__', $db->getPrefix(), trim($query));
        echo "[SONGS]\n".$debugQuery."\n[/SONGS]\n";
        return $query;
    }

	protected function getStateQuery()
	{
		$query = $this->db->getQuery(true);
		$query->select('a.id');
		$query->select('a.'.$this->state_field.' AS state');
		$query->from($this->table . ' AS a');
        jimport('joomla.log.log');
        JLog::add($query, JLog::WARNING, 'jerror');
		return $query;
	}


	public function onFinderChangeState($context, $pks, $value)
    {
		if ($context == 'com_music.song')
		{
			$this->itemStateChange($pks, $value);
		}

		if ($context == 'com_plugins.plugin' && $value === 0)
		{
			$this->pluginDisable($pks);
		}
    }

	public function onFinderCategoryChangeState($extension, $pks, $value)
    {
		if ($extension == 'com_music')
		{
			$this->categoryStateChange($pks, $value);
		}
    }

	public function onFinderAfterDelete($context, $table)
    {
		if ($context == 'com_music.song')
		{
			$id = $table->id;
		}
		else
		{
			return true;
		}

		return $this->remove($id);
    }

	public function onFinderAfterSave($context, $row, $isNew)
    {
		if ($context == 'com_music.song')
		{
			if (!$isNew && $this->old_access != $row->access)
			{
				$this->itemAccessChange($row);
			}

			$this->reindex($row->id);
		}

		if ($context == 'com_categories.category')
		{
			if (!$isNew && $this->old_cataccess != $row->access)
			{
				$this->categoryAccessChange($row);
			}
		}
    }

	public function onFinderBeforeSave($context, $row, $isNew)
    {
		if ($context == 'com_music.song' && $isNew == false)
		{
			$this->checkItemAccess($row);
		}

		if ($context == 'com_categories.category' && $isNew == false)
		{
			$this->checkCategoryAccess($row);
		}
    }
}
