<?php

namespace luckcodes\eventsmanager\components;


use luckcodes\eventsmanager\classes\AddHandlers;
/**
 * Class EventManager
 * @package luckcodes\eventmanager\components
 * @version 3.0
 */
class EventsManager
{

    public $events = [];
    public $eventsConnectors = [];
    private $connectorConnected = false;

    /**
     * Connecting Handlers
     */
    private function loadDinamicConnectors()
    {
        if (!$this->connectorConnected && $this->eventsConnectors) {
            foreach ($this->eventsConnectors as $handler) {
                $class = new $handler($this);
                if ($class instanceof AddHandlers) {
                    $class->connect();
                }else{
                    throw new \Exception('"'.get_class($class) .'" is not a class inheritor "'.AddHandlers::class.'"');
                }
            }
            $this->connectorConnected = true;
        }
    }

    public function getEvents($componentNameSpacePath)
    {
        $this->loadDinamicConnectors();
        return isset($this->events[$componentNameSpacePath]) ? $this->events[$componentNameSpacePath] : [];
    }

    /**
     * addEvents('common\test\BlockTpl','init_shortcode', 'common\eventhandler\MainBlocksHandler', 'shortcode')
     * 'common\test\BlockTpl' - class, в котором будет инициализировано событие
     * 'init_shortcode' - имя события
     * 'common\eventhandler\MainBlocksHandler' путь к классу обработчика
     * 'shortcode' - имя метода вызываемое при событии
     */

    public function addEventHandler($componentNameSpacePath, $eventName, $handler, $handlerMethod)
    {
        $this->events[$componentNameSpacePath][$eventName][] = [$handler, $handlerMethod];
    }

}
