<?php

namespace SmartCrowd\Centrifuge;

use GuzzleHttp\Client;

class CentrifugeHttpBroadcaster extends CentrifugeBaseBroadcaster
{
    /**
     * @var Client
     */
    protected $client;

    public function __constructor(Client $client)
    {
        $this->client = $client;
    }

    protected function sendCommands($commands)
    {
        $commandsJson = json_encode($commands);
        $postFields = [
            'data' => $commandsJson,
            'sign' => app('centrifuge')->generateApiSign($commandsJson)
        ];

        $this->client->post('', [
            'form_params' => $postFields
        ]);
    }

}