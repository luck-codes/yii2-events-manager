
# Yii 2 Events Manager

Компонент для [Yii 2](http://www.yiiframework.com/) приложения

Организация подключения событий через компонент: Менеджер событий.

Обработчики событий подключаются только в момент вызова первого события и не нагружают систему.

Установка
------------

Предпочтительный способ установки этого расширения через [composer](http://getcomposer.org/download/).

Либо запустить

```
php composer.phar require --prefer-dist luckcodes/yii2-events-manager "~3.0"
```

или добавить

```
"luckcodes/yii2-events-manager": "~3.0"
```

в требуемый раздел вашего `composer.json` файл.

## Подкючение в config файлах

```
'components' => [
    'eventsManager' => [
        'class'=>'luckcodes\eventsmanager\components\EventsManager',
    ]
]
```

## Подключение события вручную

```
'components' => [
    'eventsManager' => [
        'class'=>'luckcodes\eventsmanager\components\EventsManager',
        'events'=>[
           'common\test\BlockTpl' =>[
              'init_shortcode' =>[
                   ['common\eventhandler\MainBlocksHandler', 'shortcode']
              ]
           ]
        ],
    ]
]
```
В примере мы подключили для события `init_shortcode` класса `common\test\BlockTpl`
обработчик события  `['common\eventhandler\MainBlocksHandler', 'shortcode']`.

Где `common\eventhandler\MainBlocksHandler` - это путь к классу обработчика, а `shortcode` - метод,
который будет вызван при наступлении события `init_shortcode`.

## Подключение через дополнительный обработчик
Чтобы не загромождать конфиг файлы большим количеством подключений событий, в менеджере имеется возможность подключения
дополнительных обработчиков, которые внутри себя (используя необходимую логику) могут подключать только необходимые события.

### Метод подключения собственных обработчиков подключения событий
```
'components' => [
    'eventsManager' => [
        'class'=>'luckcodes\eventsmanager\components\EventsManager',
        'eventsConnectors'=>[
           'luckcodes\items\handlers\events\ConnectEventsFrontend'
        ],
    ]
]
```

Класс `luckcodes\items\handlers\events\ConnectEventsFrontend` должен быть дочерним класом `luckcodes\eventsmanager\classes\AddHandlers`

### Пример дополнительного обработчика подключения событий
файл: `luckcodes\items\handlers\events\ConnectEventsFrontend`

```
<?php

namespace luckcodes\themeblocks\src\handlers;

use luckcodes\eventsmanager\classes\AddHandlers;

class EventsManagerHandler extends AddHandlers
{
    public function connect()
    {
        $this->addEventHandler(
            'common\components\MenuBuilder',
            'menu_build',
            'common\modules\item\eventhandler\MenuBuilderComponent',
            'addOptionsMenu'
        );
    }
}
```

Данный класс подключит к событию `menu_build` класса `common\components\MenuBuilder` обработчик `common\modules\item\eventhandler\MenuBuilderComponent` 
и будет вызван метод `addOptionsMenu`.


## Поведение
Для удобства в пакет включено поведение `luckcodes\eventsmanager\behaviors\EventsManagerBehavior`.
Его необходимо подключить в компонент, который будет генерировать событие.

Берет указанные обработчики события из `luckcodes\eventsmanager\components\EventsManager`
Если есть обработчик для данного события, то подключает и создает обертку для триггера

### Подключение в компоненте

```
  public function behaviors()
  {
      return [
          [
              'class' => EventsManagerBehavior::class,
          ]
      ];
  }
```

Вызов события через обертку триггера событий
```
$this->eventTrigger(Event name,**Event** Event data);
```


