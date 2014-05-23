<?php
defined('_JEXEC') or die;

class PlgSearchSphinx extends JPlugin
{
	protected $autoloadLanguage = true;

	public function onContentSearchAreas()
	{
		static $areas = array(
			'sphinx' => 'PLG_SEARCH_SPHINX_SPHINX'
		);

		return $areas;
	}

	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		if (is_array($areas))
		{
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
			{
				return array();
			}
		}

		$text = trim($text);
        $results = $this->getSphinxResults($text, $phrase, $ordering);
        if (empty($results['matches']))
        {
            return array();
        }

        $ids = array();
        foreach ($results['matches'] as $resultId => $result)
        {
            $ids[] = $resultId;
        }
        
        $results = $this->getArticles($ids);
        return $results;
    }

    protected function getSphinxResults($text, $phrase = '', $ordering = '')
    {
        $host = $this->params->get('host', 'localhost');
        $port = $this->params->get('port', 9312);
        $s = new SphinxClient();
        $s->setServer($host, $port);
        $s->setMatchMode(SPH_MATCH_ANY);
        $s->setMaxQueryTime(20);
        $result = $s->query($text);
        return $result;
    }

    protected function getArticles($ids)
    {
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());

		$limit = $this->params->def('search_limit', 50);
		$nullDate = $db->getNullDate();
		$date = JFactory::getDate();
		$now = $date->toSql();

        $query = $db->getQuery(true);
        $query->select(array('a.id, a.catid, a.title, a.alias, a.created'))
			->select($query->concatenate(array('a.introtext', 'a.fulltext')) . ' AS text')
            ->select(array('c.title AS section', 'c.alias AS catalias'))
            ->from('#__content AS a')
			->join('INNER', '#__categories AS c ON c.id=a.catid')
            ->where('a.id IN ('.implode(',', $ids).') '
                . 'AND a.state=1 AND c.published = 1 AND a.access IN ('.$groups.') '
				. 'AND c.access IN ('.$groups .') '
				. 'AND (a.publish_up = '.$db->quote($nullDate).' OR a.publish_up <= '.$db->quote($now).') '
				. 'AND (a.publish_down = '.$db->quote($nullDate).' OR a.publish_down >= '.$db->quote($now).')'
            )
        ;

		if ($app->isSite() && JLanguageMultilang::isEnabled())
		{
			$query->where('a.language in ('.$db->quote($tag).','.$db->quote('*') . ')')
				->where('c.language in ('.$db->quote($tag).','.$db->quote('*') . ')');
		}

		$db->setQuery($query, 0, $limit);
		$items = $db->loadObjectList();

		if (isset($items))
        {
		    require_once JPATH_SITE . '/components/com_content/helpers/route.php';
            foreach ($items as $key => $item)
            {
                $item->slug = $item->id.':'.$item->alias;
                $item->catslug = $item->catid.':'.$item->catalias;
                $item->href = ContentHelperRoute::getArticleRoute($item->slug, $item->catslug);
                $items[$key] = $item;
            }
        }

        return $items;
    }
}
