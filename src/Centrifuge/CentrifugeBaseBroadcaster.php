<?php

namespace SmartCrowd\Centrifuge;

use Illuminate\Contracts\Broadcasting\Broadcaster;

abstract class CentrifugeBaseBroadcaster implements Broadcaster
{
    /**
     * Broadcast the given event.
     *
     * @param  array  $channels
     * @param  string  $event
     * @param  array  $payload
     * @return void
     */
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

        $this->sendCommands($commands);
    }

    /**
     * Sends generated commands data to centrifuge server
     *
     * @param array $commands
     */
    abstract protected function sendCommands($commands);
}