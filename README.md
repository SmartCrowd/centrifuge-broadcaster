# centrifuge-broadcaster
Centrifuge and centrifugo broadcaster for laravel 5.1

## Installation
1. `composer require smart-crowd/centrifuge-broadcaster`
2. In `config/broadcasting`:
```php
    'default' => 'centrifuge',
    'connections' => [
        ...
        'centrifuge' => [
            'driver'          => 'centrifuge',
            'server'          => 'centrifugo', // or centrifuge
            'transport'       => 'http', // or redis
            'redisConnection' => 'default', // for redis transport only
            'project'         => 'myProject',
            'baseUrl'         => 'http://myapp.exapmle:8000',
            'projectSecret'   => 'f27d79a1-821f-4e3f-47b2-7cb308768c77'
        ]
    ]
```
3. Add provider and alias in `config/app.php`
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

## Server configuration
http://fzambia.gitbooks.io/centrifugal/content/index.html

## Send messages to centrifugo
You should just fire broadcastable event. See http://laravel.com/docs/5.1/events#broadcasting-events. Don't forget, that events are broadcasted into queued jobs, so configure your queues.

## Connect from client
Use oficial centrifuge client library https://github.com/centrifugal/centrifuge-js. In your view:
```html
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
