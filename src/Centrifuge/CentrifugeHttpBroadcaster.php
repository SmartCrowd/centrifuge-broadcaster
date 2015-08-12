<?php

namespace SmartCrowd\Centrifuge;

use GuzzleHttp\Client;

class CentrifugeHttpBroadcaster extends CentrifugeBaseBroadcaster
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function sendCommands($commands)
    {
        $commandsJson = json_encode($commands);
        $postFields = [
            'data' => $commandsJson,
            'sign' => app('centrifugeManager')->generateApiSign($commandsJson)
        ];

        $this->client->post('', [
            'form_params' => $postFields
        ]);
    }

}