<?php


namespace luckcodes\eventsmanager\classes;


use luckcodes\eventsmanager\components\EventsManager;
use luckcodes\eventsmanager\interfaces\EventConnectors;

class AddHandlers implements EventConnectors
{
    private $eventManagerObj;

    public function __construct(EventsManager $eventManagerObj)
    {
        $this->eventManagerObj = $eventManagerObj;
    }

    public function addEventHandler($componentNameSpacePath, $eventName, $handler, $handlerMethod)
    {
        $this->eventManagerObj->addEventHandler($componentNameSpacePath, $eventName, $handler, $handlerMethod);
    }

    public function connect(){}
}