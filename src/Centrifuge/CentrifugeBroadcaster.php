<?php

namespace SmartCrowd\Centrifuge;

use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Broadcasting\Broadcasters\RedisBroadcaster;
use Illuminate\Contracts\Redis\Database as RedisDatabase;

/**
 * Class CentrifugeBroadcaster
 * @package SmartCrowd\Centrifuge
 */
class CentrifugeBroadcaster extends RedisBroadcaster implements Broadcaster
{
    protected $project;

    /**
     * Create a new broadcaster instance.
     *
     * @param \Illuminate\Contracts\Redis\Database|RedisDatabase $redis
     * @param  string $connection
     * @param string $project
     */
    public function __construct(RedisDatabase $redis, $connection = null, $project = 'default')
    {
        parent::__construct($redis, $connection);
        $this->project = $project;
    }

    /**
     * {@inheritdoc}
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $connection = $this->redis->connection($this->connection);

        $payload = ['event' => $event, 'data' => $payload];

        $centrifugeData = [
            'project' => $this->project,
            'data' => []
        ];

        foreach ($channels as $channel) {
            $centrifugeData['data'][] = [
                'method' => 'publish',
                'params' => [
                    'channel' => $channel,
                    'data' => $payload
                ]
            ];
        }

        $connection->rpush('centrifugo.api', json_encode($centrifugeData));
    }
}