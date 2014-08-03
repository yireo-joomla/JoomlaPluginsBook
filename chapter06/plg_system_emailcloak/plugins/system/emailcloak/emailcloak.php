<?php
$path = JPATH_SITE.'/plugins/content/emailcloak/emailcloak.php';
JLoader::register('PlgContentEmailcloak', $path);

class plgSystemEmailcloak extends PlgContentEmailcloak
{
    public function onAfterRender()
    {
        $ app = JFactory::getApplication();
        if($app->isSite())
        {
            $body = $app->getBody();
            $params = new JRegistry();
            $this->_cloak(&$body, $params);
            $app->setBody($body);
        }
    }
}
