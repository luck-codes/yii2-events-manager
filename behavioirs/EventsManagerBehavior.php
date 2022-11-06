<?php

namespace luckcodes\eventsmanager\behaviors;

use Yii;
use yii\base\Behavior;

/**
 * Поведение
 * Берет указанные обработчики события из luckcodes\eventsmanager\components\EventManager
 * Если есть обработчик для данного события, то подключает и создает обертку для триггера
 * Подключение в компоненте
 *  public function behaviors()
 *  {
 *      return [
 *          [
 *              'class' => EventsManagerBehavior::class,
 *          ]
 *      ];
 *  }
 * Обертка триггера событий
 * $this->eventTrigger(Event name,**Event** Event data);
 * 
 */
class EventsManagerBehavior extends Behavior
{

    private $eventConected= [];
    private $eventHandlers = [];

    public function eventTrigger($event, $eventData = null)
    {
        if ($this->eventCheckAndOn($event)) {
            $this->owner->trigger($event, $eventData);
        }
    }

    private function eventCheckAndOn($event)
    {
        //если обработчики события уже подключены
        if(isset($this->eventConected[$event])){
            return true;
        }

        // берем данные об обработчиках
        $this->eventHandlers = Yii::$app->eventsManager->getEvents(get_class($this->owner));


        // подключаем обработчики события
        if (isset($this->eventHandlers[$event]) && is_array($this->eventHandlers[$event])) {

            foreach ($this->eventHandlers[$event] as $handler)
            {
                $this->owner->on($event, $handler);
            }
            $this->eventConected[$event] = true;
            return true;
        }
        return false;
    }
}
