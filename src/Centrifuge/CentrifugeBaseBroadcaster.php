<?php

namespace SmartCrowd\Centrifuge;

use Illuminate\Contracts\Broadcasting\Broadcaster;

abstract class CentrifugeBaseBroadcaster implements Broadcaster
{
    /**
     * Fields from payload that should be on top level of centrifuge message
     *
     * key - payload search key,
     * value - top level field key
     *
     * @var array
     */
    protected $topLevelFields = [];

    /**
     * @param array $topLevelFields
     */
    public function setTopLevelFields(array $topLevelFields)
    {
        $this->topLevelFields = $topLevelFields;
    }

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
        $topLevelFields = [];
        foreach ($this->topLevelFields as $search => $key) {
            $topLevelFields[$key] = array_pull($payload, $search);
        }
    
        $payload = ['event' => $event, 'data' => $payload];

        $commands = [];

        foreach ($channels as $channel) {
            $commands[] = array_merge(
                [
                    'method' => 'publish',
                    'params' => [
                        'channel' => $channel,
                        'data' => $payload
                    ]
                ],
                $topLevelFields
            );
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