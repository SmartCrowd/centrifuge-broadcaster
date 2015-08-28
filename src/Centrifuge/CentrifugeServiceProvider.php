<?php

namespace SmartCrowd\Centrifuge;

use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;
use GuzzleHttp\Client;


/**
 * Class CentrifugeProvider
 * @package SmartCrowd\Centrifuge
 */
class CentrifugeServiceProvider extends ServiceProvider
{
    /**
     * Add centrifuge broadcaster
     *
     * @param BroadcastManager $broadcastManager
     */
    public function boot(BroadcastManager $broadcastManager)
    {
        $broadcastManager->extend('centrifuge', function ($app, $config) {

            if ($config['transport'] == 'redis') {

                $connection = $app->make('redis')->connection($config['redisConnection']);

                $broadcaster = new CentrifugeRedisBroadcaster(
                    $connection,
                    $config['project'],
                    Arr::get($config, 'server', 'centrifuge')
                );

            } else {

                $client = new Client([
                    'base_uri' => rtrim($config['baseUrl'], '/') . '/api/' . $config['project']
                ]);

                $broadcaster = new CentrifugeHttpBroadcaster($client);

            }

            $broadcaster->setTopLevelFields(Arr::get($config, 'topLevelFields', []));

            return $broadcaster;

        });
    }

    /**
     * Register centrifuge services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('centrifugeManager', '\\SmartCrowd\\Centrifuge\\CentrifugeManager');
    }
}
