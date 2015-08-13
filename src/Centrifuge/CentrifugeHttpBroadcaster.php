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

    /**
     * Sends commands by sanding http post request to
     * centrifuge server
     *
     * @param array $commands
     */
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