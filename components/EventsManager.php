<?php

namespace luckcodes\eventsmanager\components;


/**
 * Class EventManager
 * @package luckcodes\eventmanager\components
 * @version 2.0
 */
class EventsManager
{

    public $events = [];
    public $eventsConnectors = [];
    private $connectorConnected = false;

    /**
     * подключение классов, в которых будет динамическое подключение к событиям
     */
    function loadDinamicConnectors()
    {
        if (!$this->connectorConnected && $this->eventsConnectors) {
            foreach ($this->eventsConnectors as $handler) {

                if (isset($handler['class'], $handler['method'])) {
                    $class = $handler['class'];
                    $method = $handler['method'];
                } elseif (isset($handler[0], $handler[1])) {
                    $class = $handler[0];
                    $method = $handler[1];
                }
                if ($class && $method && is_callable([$class,$method], true, $callable_name)) {
                    call_user_func($callable_name, $this);
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
     * addEvents('common\test\BlockTpl','init_shortcode', ['common\eventhandler\MainBlocksHandler', 'shortcode'])
     * common\test\BlockTpl - namespace путь файла в котором будет инициализировано событие
     * 'init_shortcode' - имя события
     * ['common\eventhandler\MainBlocksHandler', 'shortcode'] - 1 параметр путь к классу обработчика, 2- имя функции вызываемое при событии
     */

    public function addEventHandler($componentNameSpacePath, $eventName, $handlerData)
    {
        $this->events[$componentNameSpacePath][$eventName][] = $handlerData;
    }

}
