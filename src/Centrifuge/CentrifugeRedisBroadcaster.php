<?php

namespace SmartCrowd\Centrifuge;

use Predis\ClientInterface;

/**
 * Class CentrifugeBroadcaster
 * @package SmartCrowd\Centrifuge
 */
class CentrifugeRedisBroadcaster extends CentrifugeBaseBroadcaster
{
    /**
     * @var ClientInterface
     */
    protected $connection;

    /**
     * @var string
     */
    protected $project;

    /**
     * @var string
     */
    protected $server;

    /**
     * Create a new broadcaster instance.
     *
     * @param ClientInterface $connection
     * @param string $project
     * @param string $server Can be centrifuge or centrifugo
     */
    public function __construct(ClientInterface $connection, $project = 'default', $server = 'centrifuge')
    {
        $this->connection = $connection;
        $this->project = $project;
        $this->server = $server;
    }

    /**
     * Sends commands by pushing into redis list
     *
     * @param array $commands
     */
    protected function sendCommands($commands)
    {
        $centrifugeData = [
            'data' => $commands
        ];

        $this->connection->rpush($this->server . '.api', json_encode($centrifugeData));
    }
}