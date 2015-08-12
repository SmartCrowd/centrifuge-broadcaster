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
     * Create a new broadcaster instance.
     *
     * @param ClientInterface $connection
     * @param string $project
     */
    public function __construct(ClientInterface $connection, $project = 'default')
    {
        $this->connection = $connection;
        $this->project = $project;
    }

    protected function sendCommands($commands)
    {
        $centrifugeData = [
            'project' => $this->project,
            'data' => $commands
        ];

        $this->connection->rpush('centrifugo.api', json_encode($centrifugeData));
    }
}