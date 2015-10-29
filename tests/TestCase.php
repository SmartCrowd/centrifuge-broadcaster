<?php

namespace SmartCrowd\Centrifuge;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CentrifugeServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Centrifuge' => CentrifugeFacade::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('broadcasting.default', 'centrifuge');
        $app['config']->set('broadcasting.connections.centrifuge', [
            'driver'          => 'centrifuge',
            'transport'       => 'http', // or redis
            'server'          => 'centrifugo', // or centrifuge
            'redisConnection' => 'default',
            'baseUrl'         => 'http://127.0.0.1:8000',
            'secret'          => 'f27d79a1-821f-4e3f-47b2-7cb308768c77',
            'topLevelFields'  => [
                'centClientId' => 'clientId',
            ]
        ]);
    }
}