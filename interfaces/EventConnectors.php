<?php


namespace luckcodes\eventsmanager\interfaces;

interface EventConnectors
{
    public function addEventHandler($componentNameSpacePath, $eventName, $handler, $handlerMethod);
    public function connect();
}