<?php
/**
 * System Plugin for Joomla! - Article Text
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgSystemArticletext extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
    }

    public function onAfterRender()
    {
        $application = JFactory::getApplication();
        if ($application->isSite() == false) return;

        $body = $application->getBody();
        $body = $this->replaceTags($body);
        $application->setBody($body);
    }

    public function replaceTags($text)
    {
        if (!preg_match_all('/\{articletext\ ([^\}]+)\}/', $text, $matches)) 
        {
            return $text;
        }

        foreach ($matches[1] as $matchIndex => $match)
        {
            $tag = $matches[0][$matchIndex];
            $tagArgs = $this->convertTagArgs($match);
            $aText = $this->getArticleText($tagArgs);
            $text = str_replace($tag, $aText, $text);
        }

        return $text;
    }

    protected function convertTagArgs($tagArgs)
    {
        $args = array();
        $namevalues = explode(' ', trim($tagArgs));
        foreach ($namevalues as $namevalue)
        {
            $namevalue = explode('=', $namevalue);
            $name = $namevalue[0];
            $value = $namevalue[1];
            $value = preg_replace('/([^0-9]+)/', '', $value);
            $args[$name] = $value;
        }

        return $args;
    }

    protected function getArticleText($args)
    {
        $html = (isset($args['html'])) ? (bool)$args['html'] : true;
        $id = (isset($args['id'])) ? (int)$args['id'] : 0;
        if (!$id > 0) return null;

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('introtext', 'fulltext')));
        $query->from($db->quoteName('#__content'));
        $query->where($db->quoteName('id').'='.$id);

        $db->setQuery($query);
        $row = $db->loadObject();
        if (empty($row)) return null;

        $text = $row->introtext.$row->fulltext;
        if ($html == false) $text = strip_tags($text);

        return $text;
    }
}
