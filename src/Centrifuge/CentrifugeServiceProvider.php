<?php

namespace SmartCrowd\Centrifuge;

use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;


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
            return new CentrifugeBroadcaster(
                $this->app->make('redis'),
                Arr::get($config, 'connection'),
                Arr::get($config, 'project')
            );
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