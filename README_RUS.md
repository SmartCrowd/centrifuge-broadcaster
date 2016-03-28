# centrifuge-broadcaster
Centrifugo бродкастер для laravel 5.1

Centrifuge не поддерживается на данный момент из за измененних внесеных при [обновлении Centrifugo до 1.0](https://github.com/centrifugal/centrifugo/releases/tag/v1.0.0) была нарушена обратная совместимость.

Если вы не можете перейти на Centrifugo, используйте версию пакета [0.3.1](https://github.com/SmartCrowd/centrifuge-broadcaster/tree/V0.3.1)

## Установка
1. `composer require smart-crowd/centrifuge-broadcaster`
2. В файле `config/broadcasting`:
```php
    'default' => 'centrifuge',
    'connections' => [
        ...
        'centrifuge' => [
            'driver'          => 'centrifuge',
            'transport'       => 'http', // или redis
            'redisConnection' => 'default', // только для транспорта redis
            'baseUrl'         => 'http://myapp.exapmle:8000',
            'secret'          => 'f27d79a1-821f-4e3f-47b2-7cb308768c77',
            'topLevelFields'  => [
                /**
                 * Чтобы реализовать проверку на отправителя сообщения во фронтенде
                 * http://fzambia.gitbooks.io/centrifugal/content/mixed/exclude_sender.html
                 * вам необходимо передавать в бродкастер поле centClientId
                 */
                'centClientId' => 'client',
            ],
        ]
    ]
```
3. Добавьте провайдер и алиас в файле `config/app.php`
```php
    'providers' => [
        ...
        SmartCrowd\Centrifuge\CentrifugeServiceProvider::class
    ],
    
    'aliases' => [
        ...
        'Centrifuge' => SmartCrowd\Centrifuge\CentrifugeFacade::class
    ]
```

## Настройка сервера вебсокетов
http://fzambia.gitbooks.io/centrifugal/content/index.html

## Отправка сообщений
Вам необходимо всего-лишь инициировать событие, которое настроено на бродкастинг. 
Подробнее по ссылке http://laravel.com/docs/5.1/events#broadcasting-events. 
Не забывайте, что такие события обрабатываются всегда в отложенных задачах, так что настройте и запустите обработчик очередей.

## Подключение во фронтенде
Используйте для этого официальную клиентскую js библитеку https://github.com/centrifugal/centrifuge-js:
```html
<script src="https://cdn.jsdelivr.net/sockjs/1.0/sockjs.min.js"></script>
<script src="https://rawgit.com/centrifugal/centrifuge-js/master/centrifuge.js"></script>

<script>
    var centrifuge = new Centrifuge({!! json_encode(Centrifuge::getConnection($isSockJS = true)) !!});

    centrifuge.connect();

    centrifuge.on('connect', function() {
        var subscription = centrifuge.subscribe('test-channel', function(message) {
            console.log(message);
        });
    });
</script>
```
