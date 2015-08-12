<?php

namespace SmartCrowd\Centrifuge;

use Illuminate\Contracts\Broadcasting\Broadcaster;

abstract class CentrifugeBaseBroadcaster implements Broadcaster
{
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $payload = ['event' => $event, 'data' => $payload];

        $commands = [];

        foreach ($channels as $channel) {
            $commands[] = [
                'method' => 'publish',
                'params' => [
                    'channel' => $channel,
                    'data' => $payload
                ]
            ];
        }

        static::sendCommands($commands);
    }

    abstract protected function sendCommands($commands);
}