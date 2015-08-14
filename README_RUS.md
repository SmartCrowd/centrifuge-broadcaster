# centrifuge-broadcaster
Centrifuge и centrifugo бродкастер для laravel 5.1

## Установка
1. `composer require smart-crowd/centrifuge-broadcaster`
2. В файле `config/broadcasting`:
```php
    'default' => 'centrifuge',
    'connections' => [
        ...
        'centrifuge' => [
            'driver'          => 'centrifuge',
            'server'          => 'centrifugo', // или centrifuge
            'transport'       => 'http', // или redis
            'redisConnection' => 'default', // только для транспорта redis
            'project'         => 'myProject',
            'baseUrl'         => 'http://myapp.exapmle:8000',
            'projectSecret'   => 'f27d79a1-821f-4e3f-47b2-7cb308768c77'
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
<script src="//cdn.jsdelivr.net/sockjs/1.0.0/sockjs.min.js"></script>
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
